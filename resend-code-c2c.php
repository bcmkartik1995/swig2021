<?php
ini_set("display_errors", "0");
include_once('../swigappmanager.com/includes/classes/DBQuery.php');

$objDBQuery = new DBQuery();
cors();

$whereArr = array();
$appCode = '4680bb230a519ba9e7e7e54e77ee4734';
$arrSelectDbFields = array('ticketId_PK', 'ticketCode', 'appCode_FK', 'buyerUserCode_FK', 'userCode_FK', 'streamCode_FK', 'status','type','isMasterCode','isUsed', 'amount','buyInformation', 'isAccessCodeSent', 'is24hourReminderSent', 'is15hourReminderSent','createdOn','updatedOn','buyedOn','ipAddress');

$user_code = $_REQUEST['userCode'];
$app_code = $_REQUEST['appCode'];
//print_r($_REQUEST);

$ticketCodeInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_ticket_codes', array('appCode_FK' => $app_code, 'buyerUserCode_FK ' => $user_code));
if (is_array($ticketCodeInfoArray) && !empty($ticketCodeInfoArray))
{
	//echo "innnnnnnn";
	
	
		
		$nunOfRows = count($appStreamsInfoArr);
		$eventStDateTime = "";
		$timezoneOffset = "";
		
		//for ($i = 0; $i < $nunOfRows; $i++)
		//{
		//	$eventStDateTime = $appStreamsInfoArr[$i]['eventStDateTime'];
		//	$timezoneOffset = $appStreamsInfoArr[$i]['timezoneOffset'];
		//}
		
		// response($eventStDateTime, $timezoneOffset);
		$returnObject['request_status'] = 1;
		$returnObject['request_message'] = "Fetched all the records successfully";
		$returnObject['response_data'] = $ticketCodeInfoArray[0];
		
		 response($returnObject);
	
}
else{
		$returnObject['request_status'] = 0;
		$returnObject['request_message'] = "You have not purchased Access Code yet, Please Donate to get your Access Code.";
		 response($returnObject);
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