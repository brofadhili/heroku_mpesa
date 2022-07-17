<?php
//https://url_to_the_root_folder_where_mpesa_folder_is/mpesa/B2C

//chnage this parameters
$url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$mpesa_consumer_key = ''; //put the live key from Daraja portal
$mpesa_consumer_secret = ''; //put the live cunsumer secret from Daraja Portal
$InitiatorName = ''; //put the user created at the mpesa org portal. The user must be of API type
$password = ''; //put the password of the above user
$ResultURL = 'https://url_to_the_root_folder_where_mpesa_folder_is/mpesa/B2C/results.php'; //put the result URL
$QueueTimeOutURL = 'https://url_to_the_root_folder_where_mpesa_folder_is/mpesa/B2C/results.php'; //put the QueueTimeOutURL URL
$Amount = 10; //input amount you want to send
$PartyA = ''; //input the pay bill bulk payments
$PartyB = ''; //input the phone number that will receive money eg. 254712345678

    
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
    $plaintext = $password; 
    
    openssl_public_encrypt($plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
    
    $security_credential = base64_encode($encrypted);
    
    
    $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$cred_password)); //setting custom header
    
    $curl_post_data = array(
        //Fill in the request parameters with valid values
        'InitiatorName' => 'mwenda',
        'SecurityCredential' => $security_credential,
        'CommandID' => 'SalaryPayment',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $PartyB,
        'ResultURL' => $ResultURL,
        'QueueTimeOutURL' => $QueueTimeOutURL,
        'Remarks' => 'none applicable',
        'Occasion' => 'none applicable'
    );
    
    $data_string = json_encode($curl_post_data);
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    
    $curl_response = curl_exec($curl);
    