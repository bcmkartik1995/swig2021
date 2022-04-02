<?php
include_once('web-config.php'); 
include_once('smtp.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();

include_once('includes/functions/common.php'); 

// This cron job is fired every 1 minite 

$whereArr = array();
$appCode = 'd4839b97d3c450682c3bd94a2275383c';
$arrSelectDbFields = array('appCode', 'appName');	

$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode, 'status' => 'A'));
if (is_array($appInfoArray) && !empty($appInfoArray))
{

	$arrSelectDbFlds4Stream = array('streamCode', 'streamTitle', 'streamUrl', 'eventStDateTime', 'timezoneOffset', 'streamImg', 'streamdescription', 'staring', 'streamTrailerUrl');
	$whereCls = "appCode_FK = '$appCode' AND status = 'A' AND eventStDateTime != ''";
	$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', $whereCls, '', '', 'eventStDateTime', 'ASC');
	
	if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
	{
		//$timezoneOffset = $appStreamsInfoArr[$j]['timezoneOffset'];
		
		$nunOfRows = count($appStreamsInfoArr);
		for ($i = 0; $i < $nunOfRows; $i++)
		{
			$eventStDateTime = $appStreamsInfoArr[$i]['eventStDateTime'];
			$timezoneOffset = $appStreamsInfoArr[$i]['timezoneOffset'];
			$eventStDateTime = convertDateTimeSpecificTimeZone($eventStDateTime, $timezoneOffset);
			$eventUnixTime = unixtime64($eventStDateTime);

			// This Logic for 24 hrs before email shot
			$arrCurrentDate = getExpiredDate('1 days');
			$arrCurrentDate2 = getExpiredDate('1 days +1 minutes +30 seconds');		
			
			// This Logic for 15 minites before email shot
			$arrCurrentDateFor15 = getExpiredDate('+15 minutes');
			$arrCurrentDate2For15 = getExpiredDate('+16 minutes +30 seconds');	

			//$emailAbbr = 'app-remainder-before-1';
			$emailAbbr = '';
			if ($eventUnixTime >= $arrCurrentDate['unixtimeExtended'] && $eventUnixTime <= $arrCurrentDate2['unixtimeExtended'])
			{
				echo "24 hrs before";
				$emailAbbr = 'app-remainder-before-1';
			}
			else if ($eventUnixTime >= $arrCurrentDateFor15['unixtimeExtended'] && $eventUnixTime <= $arrCurrentDate2For15['unixtimeExtended'])
			{
			
				echo "15 Minites before";
				$emailAbbr = 'app-remainder-before-2';
			}

			if ($emailAbbr != '')
			{
			
				$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, $emailAbbr);
				
				$streamCode = $appStreamsInfoArr[$i]['streamCode'];
				$eventOrStreamTitle = $appStreamsInfoArr[$i]['streamTitle'];
				
				$dateFormat = 'd/m/Y';
				$timing = date($dateFormat, $eventUnixTime);
				$eventTime = date('h:i A', $eventUnixTime) .' In UTC';
				
				$ticketInfoArr = $objDBQuery->getRecord(0, array('ticketCode', 'buyerUserCode_FK', 'streamCode_FK', 'type'), 'tbl_ticket_codes', array('streamCode_FK' => $streamCode, 'type' => 'L'), '', '', '', '');
				
				if (is_array($ticketInfoArr) && !empty($ticketInfoArr))
				{
					$numOfRows2 = count($ticketInfoArr);
					for ($j = 0; $j < $numOfRows2; $j++)
					{
						$userCode = $ticketInfoArr[$j]['buyerUserCode_FK'];
						$ticketCode = $ticketInfoArr[$j]['ticketCode'];

						$userInfoArr = $objDBQuery->getRecord(0, array('username', 'fname', 'lname', 'email', 'name'), 'tbl_registered_users', array('userCode' => $userCode), '', '', '', '');
						$email = $userInfoArr[0]['email']; 

						$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $userInfoArr[0]['name'], '', '', '', $eventOrStreamTitle, $ticketCode, $timing, $eventTime);

						sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');	
					}
				}
			}
			

		}
		
	}
}
echo "Swig Manager Cron Job Fired";
?>