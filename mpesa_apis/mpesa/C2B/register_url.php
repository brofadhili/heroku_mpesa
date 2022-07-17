<?php
//this will register a URL where results will be received upon payments
https://madukaonline.co.ke/mreceiver/random-238dHjhsk212.php

//kindly change the values below
$mpesa_consumer_key ="replace_me_please"; //replace this  key from Daraja Portal
$mpesa_consumer_secret = "replace_me_please"; //replace this also
$paybill_number = "replace_me_please"; //replace this with your paybill number/till number
$confirmation_url ="https://URL_TO_THE_ROOT_WHERE_MRECEIVER_IS_LOCATED/mreceiver/random-2635dHjhsk212.php/";
$plaintext = 'replace_me_please'; /**replace this with the password of the mpesa user you created in the mpesa portal. It is important if the user is a business operator who has all the privileges apart from those of auditor to avoid conflicts. This user will be used in other sections of the module later**/ 


/**
 * KINDLY DO NOT TOUCH ANYTHING BELOW THIS SECTION
 * UNLESS YOU KNOW WHAT YOU ARE DOING
 * 
 * */

//authentication - generating a bearer token
$url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
$credentials = base64_encode($mpesa_consumer_key.':'.$mpesa_consumer_secret);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
curl_setopt($curl, CURLOPT_HEADER, false); //set false to allow json decode
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //very important
$curl_response = curl_exec($curl);
$cred_password_raw = json_decode($curl_response, true); 
$cred_password = $cred_password_raw['access_token']; 
//setting security credentials
$publicKey_path = 'cert.cer';
$fp=fopen($publicKey_path,"r");
$publicKey=fread($fp,8192);
  fclose($fp);

openssl_public_encrypt($plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);

$security_credential = base64_encode($encrypted);


$url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$cred_password)); //setting custom header

$curl_post_data = array(
  //Fill in the request parameters with valid values
  'ShortCode' => $paybill_number,
  'ResponseType' => 'Completed',
  'ConfirmationURL' => $confirmation_url,
  'ValidationURL' => $confirmation_url
);

$data_string = json_encode($curl_post_data);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

$curl_response = curl_exec($curl);
print_r($curl_response);

echo $curl_response;
?>
