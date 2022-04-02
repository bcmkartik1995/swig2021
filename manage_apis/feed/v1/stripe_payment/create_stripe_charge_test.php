<?php

ini_set("display_errors", "0");
include_once('../../../../web-config.php'); 
include_once('../../../../includes/classes/DBQuery.php'); 
include_once('../../../../includes/functions/common.php'); 
require '../../../../vendor/autoload.php';

$objDBQuery = new DBQuery();
cors();
//echo "sdfsdf";

require_once('stripe-php/init.php');


$userDetails = $_POST;
//$_POST = trimFormValue(0, $_POST);
//echo "emaol=". $_POST['email'];
//print_r($userDetails);
/*
$paymentAmount = 1000;//
$stripeToken = "tok_1IqIhlHt9sZg0cgKnIPA6fLc";//
$stripeAPIToken_Key = "sk_test_Lr0VtmNnhemHch6IUvauT4dI";//pk_test_xijGaojvj6V9Ae1adv6kKMDN
$payment_currency = "USD";//
*/

$userEmail = $userDetails['email'];
$paymentAmount = $userDetails['amount'];//1000;//
$stripeToken = $userDetails['token'];//"sk_test_Lr0VtmNnhemHch6IUvauT4dI";//
//$stripeAPIToken_Key = $userDetails['stripeAPITokenKey'];//"tok_1IqIhlHt9sZg0cgKnIPA6fLc";//
$app_id = $userDetails['appID'];
$stream_guid = $userDetails['streamGuid'];
$menu_guid = $userDetails['menuGuid'];
$payment_currency = $userDetails['paymentCurrency'];//"USD";//
$payment_descrition = $userDetails['paymentDescription'];
$user_code = $userDetails['userCode'];
$streamDate_ID = $userDetails['streamDateID'];
$ipAdd = getRealIpAddr();

$userArray = array('appCode_FK' => $app_id, 'userCode' => $user_code);
$userInfoArray = $objDBQuery->getRecord(0, "*", 'tbl_registered_users', $userArray);

//echo "sdf=".count($ticketCodeInfoArray);

if (is_array($userInfoArray) && !empty($userInfoArray))
{
	
				
		$userPassword = $userInfoArray[0]['orig_password'];
}

function addtoWatchBeem($uEmail, $uPass)
{
    $data = array(
    'email' => $uEmail,
    'password' => $uPass
    );
$postRequest = json_encode($data);
    $cURLConnection = curl_init('http://origin.watchbeem.com:8888/registeruser');
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic c3dpZzpmc0hldXFxTENKTjM0N0Bl',
    'Content-Type: application/json'
	));

    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);

// $apiResponse - available data from the API request
  //  $jsonArrayResponse = json_decode($apiResponse);
	return $apiResponse;
}

$streamPayInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_stream_payment_options', "stream_guid = '".$stream_guid."'", '', '', '', '');
//print_r($streamPayInfoArr);
if (!empty($streamPayInfoArr) && is_array($streamPayInfoArr))
{					
  //echo "<br>cnt2= ".
  //echo "cnt=".
  $numOfRows4Pay = count($streamPayInfoArr);
  if ( $numOfRows4Pay > 0)
  {						
    $stripePaymentMode = $streamPayInfoArr[0]['payment_mode'];
    $stripeAPITokenKeyTest = $streamPayInfoArr[0]['stripe_API_token_test'];
    $stripeAPITokenKeyLive = $streamPayInfoArr[0]['stripe_API_token_live'];
  }
}

$useStripeAPITokenKey = "";
$stripePaymentMode = 'T';

if($stripePaymentMode == 'L')
  $useStripeAPITokenKey = $stripeAPITokenKeyLive;
elseif($stripePaymentMode == 'T')
  $useStripeAPITokenKey = $stripeAPITokenKeyTest;

//echo $stripeAPITokenKeyTest."<br>".$stripeAPITokenKeyLive."<br>Final:".$useStripeAPITokenKey;
//echo $useStripeAPITokenKey;

// Token is created using Stripe Checkout or Elements!
// Get the payment token ID submitted by the form:
$beemResponse = "";

if($useStripeAPITokenKey != "")
{
    try{
      \Stripe\Stripe::setApiKey($useStripeAPITokenKey);

      $chargeResponse = \Stripe\Charge::create([
        'amount' => $paymentAmount,
        'currency' => $payment_currency,
        'description' => $payment_descrition,
        'source' => $stripeToken,
      ]);
    
    	$beemResponse = addtoWatchBeem($userEmail, $userPassword);
    }
    catch (Exception $e) {
      $errorMsg = $e->getMessage();
      $chargeResponse = array("status" => "Failed", "errMsg" => "Error occurred during the Payment, Please Try Again.");
    }
}
else {
  # code...
  $errorMsg = "Stripe API Token key not found.";
}

$arrTrkPaymentInsert = array('userCode_FK' => $user_code, 'stream_guid' => $stream_guid, 'stripe_charges_token' => $stripeToken, 
'appCode_FK' => $app_id, 'stripe_API_token_key' => $useStripeAPITokenKey,
'payment_amount' => $paymentAmount, 'gateway_type'=>'Stripe', 'payment_currency' => $payment_currency, 
'payment_status' => $chargeResponse['status'], 'ipAddress' => $ipAdd, 'charge_response' => $chargeResponse, 
'payment_error_msg' => $errorMsg, 'stream_date_PK' => $streamDate_ID, 'watchbeem_response' => $beemResponse);
$objDBQuery->addRecord(0, $arrTrkPaymentInsert, 'tbl_track_swigit_payment');

//$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, 'app-change-password');
//$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $name, '', $password);

//sendEmailSwigit($email, $arrEmailInfo, 'HTML');

//print_r($streamDate_ID);
header("Content-type:application/json; charset=UTF-8");
echo $chargeResponseStr = json_encode($chargeResponse, JSON_PRETTY_PRINT);
//echo $_POST['email'];//
//echo $chargeResponseStr = json_encode($_REQUEST, JSON_PRETTY_PRINT);



?>