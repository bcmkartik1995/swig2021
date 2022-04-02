<?php
include_once('web-config.php'); 
include_once('smtp.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();

include_once('includes/functions/common.php');

$whereArr = array();
$appCode = '6e644bfad7ce32ec13ce5aa1fd332771'; //Test App Code
//$appCode = '3e909131cbb1a1f308183c838bc005d7'; // C2C App Code

$eventCode = 'fd4ddf309551bf5a9f5355749d44ad73';
$arrSelectDbFields = array('appCode', 'appName');	

$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode, 'status' => 'A'));
if (is_array($appInfoArray) && !empty($appInfoArray))
{
	$arrSelectDbFlds4Stream = array('streamCode', 'streamTitle', 'streamUrl', 'eventStDateTime', 'timezoneOffset', 'streamImg', 'streamdescription', 'staring', 'streamTrailerUrl');
	$whereCls = "streamCode = '$eventCode' AND appCode_FK = '$appCode' AND status = 'A' AND eventStDateTime != ''";
	$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', $whereCls, '', '', 'eventStDateTime', 'ASC');
	
	if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
	{		
		$nunOfRows = count($appStreamsInfoArr);
		for ($i = 0; $i < $nunOfRows; $i++)
		{
			$emailAbbr = 'app-remainder-before-1';
			$streamCode = $appStreamsInfoArr[$i]['streamCode'];
			$eventOrStreamTitle = $appStreamsInfoArr[$i]['streamTitle'];
			$eventStDateTime = $appStreamsInfoArr[$i]['eventStDateTime'];
			$timezoneOffset = $appStreamsInfoArr[$i]['timezoneOffset'];
			$eventStDateTime = convertDateTimeSpecificTimeZone($eventStDateTime, $timezoneOffset);
			
			$whereCls = "subjectAbbr = '$emailAbbr' AND appCode_FK = '$appCode'";
			$emailFormatInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_email_format', $whereCls);
			
			list($eStDate, $eStTime) = explode(" ",  $appStreamsInfoArr[$i]['eventStDateTime'], 2);
			
			$tOffset = str_replace('_', ' ', $timezoneOffset);
			$timing = $eStDate;
			$eventTime = $eStTime .' '.$tOffset;
			
			$whr = "is24hourReminderSent = 'N' AND streamCode_FK = '$streamCode' AND type = 'L' AND buyerUserCode_FK IS NOT NULL";
			$ticketInfoArr = $objDBQuery->getRecord(0, array('ticketId_PK', 'ticketCode', 'buyerUserCode_FK', 'streamCode_FK', 'type'), 'tbl_ticket_codes', $whr, '', '', '', '');
			
			if (is_array($ticketInfoArr) && !empty($ticketInfoArr))
			{
				$numOfRows2 = count($ticketInfoArr);
				for ($j = 0; $j < $numOfRows2; $j++)
				{
					$userCode = $ticketInfoArr[$j]['buyerUserCode_FK'];
					$ticketId_PK = $ticketInfoArr[$j]['ticketId_PK'];
					$ticketCode = $ticketInfoArr[$j]['ticketCode'];

					$userInfoArr = $objDBQuery->getRecord(0, array('username', 'fname', 'lname', 'email', 'name'), 'tbl_registered_users', array('userCode' => $userCode), '', '', '', '');
					$email = $userInfoArr[0]['email']; 

					$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $userInfoArr[0]['name'], '', '', '', $eventOrStreamTitle, $ticketCode, $timing, $eventTime);
					
					//$email = "dhara@swigmedia.com";
					$isMailSent = sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');

					if ($isMailSent == 1)
					{
						$objDBQuery->updateRecord(0, array('is24hourReminderSent' => 'Y','updatedOn' => date(LONG_MYSQL_DATE_FORMAT)), 'tbl_ticket_codes', array('ticketId_PK' => $ticketId_PK));
					}
				}
			}
		}			

	}
}
$strCron =  "Swig Manager Cron Job Fired on ".date('d-m-Y H:i:s')."\n ";
echo $strCron;
sendEmail("dhara.amish@gmail.com", "Freestyle Cron job 24 hour", $strCron, "FreeStyle Cron", "no-reply@swigit.com", 'HTML');
?>