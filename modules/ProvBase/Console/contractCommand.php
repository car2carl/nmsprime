<?php

namespace Modules\provbase\Console;

use Illuminate\Console\Command;
use Modules\ProvBase\Entities\Contract;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class contractCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nms:contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Contract Scheduling Command (call with daily, daily_all_contracts or monthly)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // we don't check contracts that ended before defined here (in days)
        $days_around_start_and_enddates = 7;

        $min_date = date('Y-m-d', strtotime("-$days_around_start_and_enddates days"));
        $max_date = date('Y-m-d', strtotime("+$days_around_start_and_enddates days"));
        $today = date('Y-m-d');

        if (! in_array($this->argument('date'), ['daily', 'daily_all_contracts', 'monthly'])) {
            echo "Wrong/missing argument\n";

            return false;
        }

        if (\Str::endswith($this->argument('date'), '_all_contracts')) {
            // fallback mode – runs conversions on every contract
            $cs = Contract::all();
        } else {
            // shrink the list of contracts to run daily conversion on – this is an expensive operation
            if (\Module::collections()->has('BillingBase')) {
                // attention: do not check if valid_to or valid_from is fixed – we need them both
                //      (change item dates, trigger online state of modems)
                $cs = Contract::where(whereLaterOrEqualThanDate('contract_end', $min_date))
                    ->where('contract_start', '<=', $today)
                    ->join('item', 'contract.id', '=', 'item.contract_id')
                    ->join('product', 'product.id', '=', 'item.product_id')
                    ->whereIn('product.type', ['Internet', 'Voip'])
                    ->whereBetween('item.valid_from', [$min_date, $max_date])
                    ->orWhereBetween('item.valid_to', [$min_date, $max_date])
                    ->groupBy('contract.id')
                    ->get();
            } else {
                $cs = Contract::where(whereLaterOrEqualThanDate('contract_end', $min_date))
                    ->where('contract_start', '<=', date('Y-m-d'))
                    ->get();
            }
        }

        $i = 1;
        $num = count($cs);

        foreach ($cs as $c) {
            echo "contract month: $i/$num \r";
            $i++;

            if ($this->argument('date') == 'daily') {
                $c->daily_conversion();
            }

            if ($this->argument('date') == 'monthly') {
                $c->monthly_conversion();
            }
        }
        echo "\n";

        system('/bin/chown -R apache '.storage_path('logs'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['date', InputArgument::REQUIRED, 'daily/daily_all_contracts/monthly'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            // array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        ];
    }
}
