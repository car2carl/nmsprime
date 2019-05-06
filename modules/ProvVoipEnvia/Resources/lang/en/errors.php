<?php

/*
|--------------------------------------------------------------------------
| Language lines for module ProvVoipEnvia
|--------------------------------------------------------------------------
|
| The following language lines are used by the module ProvVoipEnvia
| As far as we know this module is in use in Germany, only. So no translation
| for other languages is needed at the moment.
|
*/

return [
	'carrier_invalid' => ':0 is not a valid carrier_code.',
    'apiversion_not_float' => 'PROVVOIPENVIA__REST_API_VERSION in .env has to be a float value (e.g.: 1.4)',
    'carrier_no_porting_in' => 'If no incoming porting: Carriercode has to be D057 (envia TEL).',
    'customernumber_not_existing' => 'Customernumber does not exist – try using legacy version.',
    'deac_date_not_set' => 'No deactivation date in PhonenumberManagement set.',
    'document_already_uploaded' => 'Given document has aleady been uploaded.',
    'enviacontract_id_missing' => 'No envia TEL contract ID (contract_external_id) found.',
    'has_to_be_numeric' => ':value has to be numeric',
    'legacy_customernumber_not_existing' => 'Legacy customernumber does not exist – try using normal version.',
    'multiple_envia_contracts_at_modem' => 'There is more than one envia TEL contract used on this modem (:0). Processing this is not yet implemented – please use the envia TEL Web API.',
    'needs_to_be_string_or_array' => ':0 needs to be string or array, :1 given.',
    'no_model_given' => 'No model given',
    'no_numbers_for_envia_contract' => 'No phonenumbers found for envia TEL contract :0.',
    'no_trc_set' => 'TRCclass not set.',
    'orderid_orderdocument_mismatch' => 'Given order_id (:0) not correct for given enviaorderdocument.',
    'relocate_date_missing' => 'Date of installation address change has to be set.',
    'value_not_allowed_for_param' => 'Value :a not allowed for param $level',
];
