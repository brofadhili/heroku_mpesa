<?php

/**
 * For a production app you need to do some checks
 * to ensure this file is not called directly unless
 * there is a real payment happenning.
 * 
 *  You can do a post to this file or take it's content to your app
 * 
 * To test you can copy this url to your browser:
 * https://madukaonline.co.ke/mpesa/STK/confirm.php
 * 
 * */
require("checkout-query.php");

//some of this params may need to be populated by a post payload
$mpesa_consumer_key = "";
$mpesa_consumer_secret = "";
$mpesapass = "";
$shortcode = "";
$CheckoutRequestID = "ws_CO_MER_341020404191482542043240"; /*normally this cannot be static, it changes per transaction */

//do the checkout query and get the feedback
$feedback = checkout_query($mpesa_consumer_key,$mpesa_consumer_secret,$mpesapass,$shortcode,$CheckoutRequestID);

//let's log until until further interaction with db, avoid this for production
file_put_contents("logs_confirm.txt", $feedback);

