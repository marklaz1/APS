<?php
/**
 * Simple cURL request script
 *
 * Test if cURL is available, send request, print response
 *
 *   php curl.php
 */
 
$orderId =  $_GET["orderId"];

$postRequest = http_build_query(array(	
	'apiOperation' => 'RETRIEVE_TRANSACTION',
	'apiPassword' => '1a41730b24323f287209d1887261934b',
	'apiUsername' => 'merchant.UBAPAYFUNLNG',
	'merchant' => 'UBAPAYFUNLNG',
	'order.id' => $orderId,
	'transaction.id' => '1'
));

if(!function_exists('curl_init')) {
    die('cURL not available!');
}

$curl = curl_init('https://test-gateway.mastercard.com/api/nvp/version/58');
curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//Disable CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER by
//setting them to false.
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($curl, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$output = curl_exec($curl);
curl_close($curl);
if ($output === FALSE) {
    echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
}
else {
	parse_str($output,$my_arr);
	foreach($my_arr as $key=>$value){		
		if ($key =='order_id') $order_id = $value;		
		if ($key =='response_acquirerCode') $acquirerCode = $value;	
		if ($key =='response_acquirerMessage') $acquirerMessage = $value;	
		if ($key =='order_lastUpdatedTime') $order_lastUpdatedTime = $value;	
		if ($key =='transaction_amount') $transaction_amount = $value;	
		if ($key =='transaction_currency') $transaction_currency = $value;	
		if ($key =='transaction_receipt') $transaction_receipt = $value;	
		if ($key =='transaction_currency') $transaction_currency = $value;	
		if ($key =='sourceOfFunds_provided_card_brand') $sourceOfFunds_provided_card_brand = $value;		
	}
	
	echo "<h1>Merchant's payment result page</h1>";
	echo "<h2>MyStore.com : $transaction_amount $transaction_currency</h2><hr>";
	echo "<h3>Transaction receipt</h3>Your payment was successful. Thank you for your order.";
	echo "<p>Date: $order_lastUpdatedTime</p>";
	echo "<p>Order ID: $order_id</p>";
	echo "<p>Payment method: $sourceOfFunds_provided_card_brand</p><hr>";
	
	echo "<h1>Backoffice data</h1>";
	print_r($my_arr);
	echo "<hr>acquirerCode : $acquirerCode<br>";
	echo "<hr>acquirerMessage : $acquirerMessage<br>";
	echo "<hr>order_lastUpdatedTime : $order_lastUpdatedTime<br>";
	echo "<hr>transaction_amount : $transaction_amount<br>";
	echo "<hr>transaction_currency : $transaction_currency<br>";
	echo "<hr>transaction_receipt : $transaction_receipt<br>";
}
?>