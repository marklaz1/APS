<!doctype html>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://test-gateway.mastercard.com/checkout/version/58/checkout.js"
		data-error="errorCallback"
		data-cancel="cancelCallback"
		data-complete="completeCallback"
        data-afterRedirect="restorePageState">
		</script>

<h2>African Payment Solutions : Mastercard Payment Gateway - Hosted Payment Page request template</h2>
<h3><a href="https://github.com/marklaz1/APS/blob/main/paymentRequest.php">See .PHP code here</a></h3>
<form action="paymentRequest.php" method="post">
OrderId: <input type="text" name="orderId" value="orderno1"><br>
Amount: <input type="text" name="amount" value="123.45"><br>
<input type="submit" value="pay">
</form>

<?php

/**
 * Simple cURL request script
 *
 * Test if cURL is available, send request, print response
 *
 *   php curl.php
 */

 $orderId =$_POST["orderId"];
 $amount =$_POST["amount"];


 	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$orderRef = substr(str_shuffle($permitted_chars), 0, 8);
	$transRef = substr(str_shuffle($permitted_chars), 0, 8);

	echo "<hr>";

if (!empty($_POST)) {
$postRequest = http_build_query(array(
	'apiOperation' => 'CREATE_CHECKOUT_SESSION',
	'apiPassword' => '1a41730b24323f287209d1887261934b',
	'apiUsername' => 'merchant.UBAPAYFUNLNG',
	'merchant' => 'UBAPAYFUNLNG',
	'interaction.operation' => 'PURCHASE',
	'interaction.merchant.name' => 'GollyGee.com',
	'order.id' => $orderId,
	'order.amount' => $amount,
	'order.currency' => 'USD',
	'order.reference' => $orderRef,
	'order.description' => 'ses goods',
	'transaction.reference' => $transRef
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
    echo $output;

	parse_str($output,$my_arr);
	print_r($my_arr);

	foreach($my_arr as $key=>$value){
		if ($key =='session_id') $sessionId = $value;
		if ($key =='successIndicator') $successIndicator = $value;
		echo "$key : $value<br>";
	}
	echo "$sessionId<br>";
	echo $successIndicator;
	}
}
?>

        <script type="text/javascript">
            function errorCallback(error) {
				console.log(JSON.stringify(error));
            }
            function cancelCallback() {
            }
			function completeCallback(resultIndicator, sessionVersion) {
				console.log(sessionVersion);
				console.log(resultIndicator);
				console.log("<?php echo $successIndicator ?>" == resultIndicator);
				if ("<?php echo $successIndicator ?>" == resultIndicator)
					window.location.replace("paymentResult.php?orderId=<?php echo $orderId ?>");
			}
			function restorePageState() {
                 console.log('restore');
			}

            Checkout.configure({
				session: {id: "<?php echo $sessionId ?>"},
				merchant: 'UBAPAYFUNLNG',
	//			order: {
	//				amount: amount,
	//				currency: 'USD',
	//				description: 'Ordered goods'
	//				id: orderId,
	//				reference: Math.random()
	//			},
//				transaction: {reference: Math.random()},

	//			interaction: {
	//				operation: 'PURCHASE',
    //                merchant: {
    //                    name: 'FantasticStore.com',
    //                    address: {
     //                       line1: '200 Sample St',
     //                       line2: '1234 Example Town'
     //                   }
     //               }
     //          }
            });

			<?php if (!empty($_POST))  ?>
			Checkout.showLightbox();


        </script>
    </head>
 </html>