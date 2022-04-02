<?php
ini_set("display_errors", "0");
include_once('../swigappmanager.com/includes/classes/DBQuery.php');

$objDBQuery = new DBQuery();
cors();

$whereArr = array();

$ipAdd = getRealIpAddr();
$app_code = $_REQUEST['appCode'];
$access_page = $_REQUEST['accessType'];
//print_r($_REQUEST);
$objDBQuery->addRecord(0, array('ipAddress' => $ipAdd, 'appCode_FK' => $app_code, 'access_type' => $access_page), 'tbl_track_users');
	

echo "done";

function getRealIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{				
		// Check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} 
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{   
		// Check if ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} 
	else
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function response($resObject){
 //$response['event_datetime'] = $eventStDateTime;
 //$response['event_timezone'] = $timezoneOffset;
 
 $json_response = json_encode($resObject);
 echo $json_response;
}
// Enter your Host, username, password, database below.

function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

	header("Access-Control-Allow-Headers: X-Requested-With");
}


?>