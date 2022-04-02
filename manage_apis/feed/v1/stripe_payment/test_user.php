<?php
ini_set("display_errors", "1");
//ini_set("display_errors", "0");
include_once('../../../../web-config.php'); 
include_once('../../../../includes/classes/DBQuery.php'); 
include_once('../../../../includes/functions/common.php'); 
require '../../../../vendor/autoload.php';

$objDBQuery = new DBQuery();
cors();
//echo "sdfsdf";
$app_id = "7f912b2a598f9397d282950787b6b9d0";
$user_code = "fb04e0e1b4449af5f48a4358cce0cea6";

$userArray = array('appCode_FK' => $app_id, 'userCode' => $user_code);
$userInfoArray = $objDBQuery->getRecord(0, "*", 'tbl_registered_users', $userArray);

echo "sdf=".count($userInfoArray);

if (is_array($userInfoArray) && !empty($userInfoArray))
{
	
	$uEmail =			$userInfoArray[0]['email'];
	echo "df=".	$userPassword = $userInfoArray[0]['orig_password'];
}

//function addtoWatchBeem($uEmail, $uPass)
//{
    $data = array(
    'email' => $uEmail,
    'password' => $userPassword;
    );

$postRequest = json_encode($data);

    $cURLConnection = curl_init('http://origin.watchbeem.com:8888/registeruser');
print_r($cURLConnection);
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic c3dpZzpmc0hldXFxTENKTjM0N0Bl',
    'Content-Type: application/json'
	));
echo "ddd";
ini_set("display_errors", "1");
    $apiResponse = curl_exec($cURLConnection);
print_r($apiResponse);
    curl_close($cURLConnection);
echo "mmmm";
// $apiResponse - available data from the API request
    $jsonArrayResponse = json_decode($apiResponse);
print_r($jsonArrayResponse);
	//return $jsonArrayResponse;
//}
*/
?>