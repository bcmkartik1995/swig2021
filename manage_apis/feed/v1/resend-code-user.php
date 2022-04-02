<?php
ini_set("display_errors", "0");
include_once('../../../web-config.php'); 
include_once('../../../smtp.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 

$objDBQuery = new DBQuery();
cors();

$whereArr = array();
//$arrSelectDbFields = array('ticketId_PK', 'ticketCode', 'appCode_FK', 'buyerUserCode_FK', 'userCode_FK', 'streamCode_FK', 'status','type','isMasterCode','isUsed', 'amount','buyInformation', 'isAccessCodeSent', 'is24hourReminderSent', 'is15hourReminderSent','createdOn','updatedOn','buyedOn','ipAddress');

$user_code = $_REQUEST['userCode'];
$appCode = $_REQUEST['appId'];
$stream_guid = $_REQUEST['streamGuid'];
$menu_guid = $_REQUEST['menuGuid'];


//print_r($_REQUEST);

$ticketCodeArray = array('appCode_FK' => $appCode, 'userCode_FK' => $user_code, 'streamCode_FK' => $stream_guid);
$ticketCodeInfoArray = $objDBQuery->getRecord(0, "*", 'tbl_ticket_codes', $ticketCodeArray);

//echo "sdf=".count($ticketCodeInfoArray);

if (is_array($ticketCodeInfoArray) && !empty($ticketCodeInfoArray)){
		$nunOfRows = count($ticketCodeInfoArray);
		$eventStDateTime = "";
		$timezoneOffset = "";
				
		$ticketCode = $ticketCodeInfoArray[0]['ticketCode'];
		
		$emailAbbr = "app-resend-ticketcode";
		$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, $emailAbbr);
		$userInfoArr = $objDBQuery->getRecord(0, array('username', 'fname', 'lname', 'email', 'name'), 'tbl_registered_users', array('userCode' => $user_code), '', '', '', '');
		//print_r($userInfoArr);

		//echo "<br>dd=".
        $email = $userInfoArr[0]['email']; 

		
		$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $userInfoArr[0]['name'], '', '', '', '', $ticketCode, '', '');

		//print_r($arrEmailInfo);
		$isMailSent = sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');
		if ($isMailSent == 1)
		{
			$objDBQuery->updateRecord(0, array('is_resendCode_sent' => 'Y','updatedOn' => date(LONG_MYSQL_DATE_FORMAT)), 'tbl_ticket_codes', array('ticketId_PK' => $ticketId_PK));
			$returnObject['request_status'] = 1;
			$returnObject['request_message'] = "Ticketcode email has been sent Successfully.";
		}
		else {
			$returnObject['request_status'] = 0;
			$returnObject['request_message'] = "Ticketcode email could not be sent, please try again.";
		}
}
else{
		$returnObject['request_status'] = 0;
		$returnObject['request_message'] = "You have not purchased Access Code yet, Please Donate to get your Access Code.";
		
}


$json_response = json_encode($returnObject);
echo $json_response;


?>