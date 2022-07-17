<?php
/**
 * For a production environment ensure this file is not called directly
 * at all. It should only be included in the checkout file.
 * Do some checks here, die() or return or exit if accessed directly.
 * 
 * */
function check_transaction_api($mpesa_consumer_key,$mpesa_consumer_secret,$mpesapass,$amount,$phone_no,$shortcode,$systemUrl,$identifierType, $invoice_number){
    //set system time to Nairobi
    //date_default_timezone_set("Africa/Nairobi");
    $timestamp = date("Ymdhis");
    //set pass
    $password = base64_encode($shortcode.$mpesapass.$timestamp);
    
    $curl = curl_init();
    $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    curl_setopt($curl, CURLOPT_URL, $url);
    $credentials = base64_encode($mpesa_consumer_key.':'.$mpesa_consumer_secret);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
    curl_setopt($curl, CURLOPT_HEADER, false); //set false to allow json decode
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //very important
    $curl_response = curl_exec($curl);
    $cred_password_raw = json_decode($curl_response, true); 
    $cred_password = $cred_password_raw['access_token']; 
    
    
    $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$cred_password)); //setting custom header
    
    $curl_post_data = array(
      //Fill in the request parameters with valid values
      'BusinessShortCode' => $shortcode,
      'Password' => $password,
      'Timestamp' => $timestamp,
      'TransactionType' => $identifierType,
      'Amount' => $amount,
      'PartyA' => $phone_no,
      'PartyB' => $shortcode,
      'PhoneNumber' => $phone_no,
      'CallBackURL' => $systemUrl,
      'AccountReference' => $invoice_number,
      'TransactionDesc' => 'not applicable'
    );

    
    $data_string = json_encode($curl_post_data);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    
    $curl_response = curl_exec($curl);
    //echo json_decode($curl_response); /*avoid json_decoding twice */
    $resultStatus_raw = json_decode($curl_response, true); ;
    $resultStatus = $resultStatus_raw['ResponseCode'];
    $MerchantRequestID = $resultStatus_raw['MerchantRequestID'];
    $CheckoutRequestID = $resultStatus_raw['CheckoutRequestID'];
    
    if ($resultStatus === "0"){
        $success_on = "yes#";
        $success_combination = $success_on.$MerchantRequestID."#".$CheckoutRequestID;
        //return $success_combination;
        foreach($resultStatus_raw as $key=>$value){
            $parte .= $key."=>".$value."\n";
        }
        return $parte;
    }
    else{
        $error_on = "error#";
        $error = $error_on.$resultStatus_raw['errorMessage'];
        return $error;
    }
}