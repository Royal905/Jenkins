<?php

require dirname(__DIR__) . '/vendor/autoload.php';
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
// To set up environmental variables, see http://twil.io/secure
$account_sid = 'AC4de0c64a9599785344cd86636d35156e';
$auth_token = 'd5cc181e3cf9ea965475d17d00881e1d';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

// A Twilio number you own with SMS capabilities
$twilio_number = "+15674431342";
try{
	$client = new Client($account_sid, $auth_token);
	$response = $client->messages->create(
	    // Where to send a text message (your cell phone?)
	    '+919726078792',
	    array(
	        'from' => $twilio_number,
	        'body' => 'Santosh sent this from twilio.'
	    )
	);

	echo '<pre>'; print_r($response); echo '</pre>'; die;

}catch(Exception $e){
	echo $e->getMessage(); die;
}
