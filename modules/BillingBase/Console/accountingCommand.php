<?php 
namespace Modules\Billingbase\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Modules\ProvBase\Entities\Contract;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use File;
use DB;
use Modules\BillingBase\Entities\Product;
use Modules\BillingBase\Entities\SepaAccount;
use Modules\BillingBase\Entities\BillingBase;
use Modules\BillingBase\Entities\Bill;
use Modules\BillingBase\Entities\AccountingRecords;
use Modules\BillingBase\Entities\BookingRecords;
use Modules\BillingBase\Entities\Sepaxml;

class accountingCommand extends Command {

	/**
	 * The console command & table name, description, data arrays
	 *
	 * @var string
	 */
	protected $name 		= 'nms:accounting';
	protected $tablename 	= 'accounting';
	protected $description 	= 'Create accounting records table, Direct Debit XML, invoice and transaction list from contracts and related items';
	
	protected $logger;					// billing logger instance for this command - billing
	protected $dates;					// offen needed time strings for faster access - see constructor


	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// instantiate logger for billing
		$this->logger = new Logger('Billing');
		$this->logger->pushHandler(new StreamHandler(storage_path().'/logs/billing-'.date('Y-m').'.log'), Logger::DEBUG, false);

		$this->dates = array(
			'today' 		=> date('Y-m-d'),
			'm' 			=> date('m'),
			'Y' 			=> date('Y'),
			'this_m'	 	=> date('Y-m'),
			'this_m_bill'	=> date('m/Y'),
			'last_m'		=> date('m', strtotime("first day of last month")),			// written this way because of known bug
			'last_m_Y'		=> date('Y-m', strtotime("first day of last month")),			// written this way because of known bug
			'last_m_bill'	=> date('m/Y', strtotime("first day of last month")),
			'null' 			=> '0000-00-00',
			'lastm_01' 		=> date('Y-m-01', strtotime("first day of last month")),
			'thism_01'		=> date('Y-m-01'),
			'nextm_01' 		=> date('Y-m-01', strtotime("+1 month")),
			'last_run' 		=> '',
			'm_in_sec' 		=> 60*60*24*30,			// month in seconds
		);

		parent::__construct();
	}



	/**
	 * Create invoice-, booking records and sepa xml file
	 * Execute the console command - Pay Attention to arguments
	 	* 1 - executed without TV items
	 	* 2 - only TV items
	 	* everything else - both are calculated for bills
	 * TODO: add to app/Console/Kernel.php -> run monthly()->when(function(){ date('Y-m-d') == date('Y-m-10')}) for tenth day in month
	 */
	public function fire()
	{
		$this->logger->addInfo(' #####    Starting Accounting Command    #####');

		switch ($this->argument('cycle'))
		{
			case 2: 
				$this->logger->addInfo('Cycle only for TV items/products'); 
				break;
			case 1: 
				$this->logger->addInfo('Cycle without TV items/products');
			default:
				// remove all entries of this month from accounting table if entries were already created (and create them new)
				$actually_created = DB::table($this->tablename)->where('created_at', '>=', $this->dates['thism_01'])->where('created_at', '<=', $this->dates['nextm_01'])->first();
				if (is_object($actually_created))
				{
					$this->logger->addNotice('Accounting Command was already executed this month - accounting table will be recreated now! (for this month)');
					DB::update('DELETE FROM '.$this->tablename.' WHERE created_at>='.$this->dates['thism_01']);
				}
				break;
		}

		$conf 		= BillingBase::first();
		$sepa_accs  = SepaAccount::all();
		$bills 		= [];
		$acc_recs 	= [];
		$book_recs 	= [];
		$sepa_xmls  = [];

		// check date of last run and get last invoice nr - all item entries after this date have to be included to the current billing cycle
		$last_run = DB::table($this->tablename)->orderBy('created_at', 'desc')->select('created_at')->first();
		if (is_object($last_run))
		{
			$this->dates['last_run'] = $last_run->created_at;
			// Separate invoice_nrs for every SepaAccount
			foreach ($sepa_accs as $acc)
			{
				$invoice_nr[$acc->id] = DB::table($this->tablename)->where('sepa_account_id', '=', $acc->id)->orderBy('invoice_nr', 'desc')->select('invoice_nr')->first();
				$invoice_nr[$acc->id] = $invoice_nr[$acc->id]->invoice_nr;
			}
		}
		else
		{
			// first run for this system
			$this->dates['last_run'] = $this->dates['null'];
			foreach ($sepa_accs as $acc)
				$invoice_nr[$acc->id] = 100000;
		}

		$this->logger->addDebug('Last run was on '.$this->dates['last_run']);


		/*
		 * Loop over all Contracts
		 */
		foreach (Contract::all() as $c)
		{
			// check validity of contract
			if (!$c->check_validity($this->dates))
			{
				$this->logger->addNotice('Contract '.$c->id.' is out of date');
				continue;				
			}

			if (!$c->create_invoice)
			{
				$this->logger->addInfo('Contract '.$c->id.' is out of date');
				continue;
			}

			// variable resets or incrementations
			$charge 	= []; 					// total costs for this month for current contract
			$c->expires = (date('Y-m', strtotime($c->contract_end)) == $this->dates['this_m']);

			// debugging output
			var_dump($c->id);

			/*
			 * Add internet, voip and tv tariffs and all other items and calculate price for this month considering 
			 * contract start & expiration date, calculate total sum of items for booking records
			 */
			foreach ($c->items as $item)
			{
				// check validity
				if (!$item->check_validity($this->dates))
					continue;

				// only TV items for this walk (when argument=2)
				if ($this->argument('cycle') == 2 && $item->product->type != 'TV')
					continue;
				if ($this->argument('cycle') == 1 && $item->product->type == 'TV')
					continue;


				$costcenter = $item->product->costcenter ? $item->product->costcenter : $c->costcenter;
				$ret = $item->calculate_price_and_span($this->dates, $costcenter, $c->expires);
				
				$price = $ret['price'];
				// skip adding item to accounting records and bill if price == 0
				if (!$price)
					continue;

				// get account via costcenter
				$acc_id = $costcenter->sepa_account_id;
				$text   = $ret['text'];

				// increase invoice nr of account, increase charge for account by price, calculate tax
				if (isset($charge[$acc_id]))
				{
					$charge[$acc_id]['gross'] += $price;
					$charge[$acc_id]['tax'] += $item->product->tax ? round($price * $conf->tax/100, 2) : 0;
				}
				else
				{
					$charge[$acc_id] = ['gross' => $price, 'tax' => $item->product->tax ? round($price * $conf->tax/100, 2) : 0];
					$invoice_nr[$acc_id] += 1;
				}

				// save to accounting table as backup for future checking
				$count = $item->count ? $item->count : 1;
				DB::update('INSERT INTO '.$this->tablename.' (created_at, contract_id, name, product_id, ratio, count, invoice_nr, sepa_account_id) VALUES(NOW(),'.$c->id.',"'.$item->name.'",'.$item->product->id.','.$ret['ratio'].','.$count.','.$invoice_nr[$acc_id].','.$acc_id.')');

				// write to accounting records of account
				if (!isset($acc_recs[$acc_id]))
					$acc_recs[$acc_id] = new AccountingRecords($sepa_accs->find($acc_id)->name);
				$acc_recs[$acc_id]->add_item($item, $price, $text, $acc_id.'/'.$invoice_nr[$acc_id]);

				// $records[$acc_id][$rec_arr][] = $this->get_invoice_record($item, $price, $acc_id.'/'.$invoice_nr[$acc_id], $text);


				// create bill for account and contract and add item
				if (!isset($bills[$acc_id][$c->id]))
					$bills[$acc_id][$c->id]	= new Bill($c, $conf, $acc_id.'/'.$invoice_nr[$acc->id]);
				$bills[$acc_id][$c->id]->add_item($count, $price, $text);

			} // end of item loop



			// get actual valid sepa mandate
			$mandate = $c->get_valid_mandate();

			if (!$mandate)
				$this->logger->addNotice('Contract '.$c->id.' has no valid sepa mandate');


			// Add billing file entries
			foreach ($charge as $acc_id => $value)
			{
				$acc = $sepa_accs->find($acc_id);

				// booking record
				if (!isset($book_recs[$acc_id]))
					$book_recs[$acc_id] = new BookingRecords($acc->name);
				$book_recs[$acc_id]->add_record($c, $mandate, $acc_id.'/'.$invoice_nr[$acc_id], $value['gross'], $value['tax'], $conf);
				
				// bill data
				$bills[$acc_id][$c->id]->set_mandate($mandate);
				$bills[$acc_id][$c->id]->set_summary($value['gross'], $value['tax']);
				if (!$bills[$acc_id][$c->id]->set_company_data($acc))
					$this->logger->addError('No Company assigned to Account '.$acc->name);

				// make bill already
				if ($ret = $bills[$acc_id][$c->id]->make_bill())
				{
					switch ($ret)
					{
						case -1: $msg = 'Template or Logo of Company of $acc_id not set'; break;
						case -2: $msg = "Bill for Contract $id could not be created"; break;
					}
					$this->logger->addError($msg);
				}

				if (!$mandate)
					continue;

				// sepa record
				if (!isset($sepa_xmls[$acc_id]))
					$sepa_xmls[$acc_id] = new Sepaxml($acc);
				$sepa_xmls[$acc_id]->add_entry($mandate, $charge[$acc_id]['gross'], $this->dates, $acc_id.'/'.$invoice_nr[$acc_id]);
			}


// if ($c->id == 500007)
// 	dd($mandates, $acc_id);

		} // end of loop over contracts

		// store all billing files
		$this->store_billing_files($book_recs, $acc_recs, $sepa_xmls);

	}


	/*
	 * Store SEPA, Booking & Invoice Records in according Files (foreach type and foreach account)
	 */
	protected function store_billing_files($book_recs, $acc_recs, $sepa_xmls)
	{
		if (!is_dir(storage_path('billing')))
			mkdir(storage_path('billing'));

		// dd($book_recs, '---------', $acc_recs, '---------', $sepa_xmls);
		foreach ($book_recs as $acc_id => $r)
		{
			if (isset($acc_recs[$acc_id]))
				$acc_recs[$acc_id]->make_accounting_record_files();
			if (isset($book_recs[$acc_id]))
				$book_recs[$acc_id]->make_booking_record_files();
			if (isset($sepa_xmls[$acc_id]))
				$sepa_xmls[$acc_id]->make_sepa_xml();
		}
	}




	/**
	 * Get the console command arguments / options
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['cycle', InputArgument::OPTIONAL, '1 - without TV, 2 - only TV'],
		];
	}

	protected function getOptions()
	{
		return [
			// ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}