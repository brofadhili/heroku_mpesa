<?php

/**
 * For a production app you need to do some checks
 * to ensure this file is not called directly unless
 * there is a real payment happenning.
 * 
 *  You can do a post to this file or take it's content to your app
 * 
 * To test you can copy this url to your browser:
 * https://madukaonline.co.ke/mpesa/STK/checkout.php
 * 
 * */
require("checkout-function.php");

//some of this params may need to be populated by a post payload
$mpesa_consumer_key = "";
$mpesa_consumer_secret = "";
$mpesapass = ""; //mpesa key
$amount = "15"; //amount
$phone_no = ""; //phone number to pay
$shortcode = ""; //shortcode to pay to eg paybill/store number/head office no.
$systemUrl = "https://madukaonline.co.ke/mpesa/STK/results.php";
$identifierType ="CustomerPayBillOnline";
$invoice_number ="test";

//do the checkout and get the feedback
$feedback = check_transaction_api($mpesa_consumer_key,$mpesa_consumer_secret,$mpesapass,$amount,$phone_no,$shortcode,$systemUrl,$identifierType, $invoice_number);

//let's log until until further interaction with db, avoid this for production
file_put_contents("logs_checkout.txt", $feedback);

