<?php
$ipn_data = json_decode(file_get_contents('php://input'));
//sanitize
$ipn_transcode = filter_var($ipn_data->TransID, FILTER_SANITIZE_STRING);
$ipn_amount = filter_var($ipn_data->TransAmount, FILTER_SANITIZE_STRING);
$ipn_date = filter_var($ipn_data->TransTime, FILTER_SANITIZE_STRING);
$ipn_account = filter_var($ipn_data->BillRefNumber, FILTER_SANITIZE_STRING);
$ipn_first_name = filter_var($ipn_data->FirstName, FILTER_SANITIZE_STRING);
$ipn_middle_name = filter_var($ipn_data->MiddleName, FILTER_SANITIZE_STRING);
$ipn_last_name = filter_var($ipn_data->LastName, FILTER_SANITIZE_STRING);
$ipn_phone_number = filter_var($ipn_data->MSISDN, FILTER_SANITIZE_STRING);
$ipn_short_code = filter_var($ipn_data->BusinessShortCode, FILTER_SANITIZE_STRING);
$ipn_account_bal = filter_var($ipn_data->OrgAccountBalance, FILTER_SANITIZE_STRING);
$ipn_full_name = $ipn_first_name." ".$ipn_middle_name." ".$ipn_last_name;

//do sth eg insert to db