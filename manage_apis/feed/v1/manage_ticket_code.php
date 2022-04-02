<?php
include_once('../../../web-config.php'); 
include_once('../../../smtp.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();
cors();
$tblName = 'tbl_pay_per_clicks';
$arrFld = array('name', 'userCode', 'username', 'appCode_FK AS appId', 'email', 'accountStatus', 'createdOn', 'updatedOn');
$whereArr = array();
$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod == 'POST')
{
	$postAction = 'POST';
	if (!empty($_POST['postAction'])) $postAction = trim($_POST['postAction']);
	$accessCase = $postAction;
}
else $accessCase = $requestMethod;

switch($accessCase)
{
	case 'buyTicket':
		// Insert User Data	
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];
		$amount = $_POST['amount'];
		$buyInformation = $_POST['buyInformation'];
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_registered_users', array('userCode' => $userCode))) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_streams', array('streamCode' => $streamGuid))) $rtnRes = array('status' => 0, 'msg' => 'Stream Code does not match with our db.');
		else if ($streamGuid != '' && $userCode != '' && $buyInformation != '')
		{		
			$arrSelectDbFlds4Stream = array('streamTitle', 'streamImg', 'menuCode_FK', 'subCatCode_FK', 'subscriptionExpiredFaq', 'subscriptionExpired', 'eventStDateTime', 'eventEndDateTime', 'timezoneOffset');
			$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('streamCode' => $streamGuid), '', '', '', '');
			
			$subscriptionExpiredFaq = $appStreamsInfoArr[0]['subscriptionExpiredFaq'];
			$subscriptionExpired = $appStreamsInfoArr[0]['subscriptionExpired'];
			$interval = "24 hour";

			if ($subscriptionExpiredFaq != '' && $subscriptionExpired != '' && $subscriptionExpired != 0)
			{
				$interval = "$subscriptionExpired $subscriptionExpiredFaq";
			}		

			$frmKeyExcludeArr = array('postAction', 'appId', 'streamGuid', 'userCode');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['buyerUserCode_FK'] = $userCode;
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['streamCode_FK'] = $streamGuid;			
			$dataArr['amount'] = $amount;
			$dataArr['ipAddress'] = getRealIpAddr();
			$dataArr['buyedOn'] = date(LONG_MYSQL_DATE_FORMAT);
			
			$excludeCodesArray = array();
			$ticketCodeInfoArr = $objDBQuery->getRecord(0, array('ticketCode'), 'tbl_ticket_codes', array('appCode_FK' => $appCode));	
			foreach ($ticketCodeInfoArr As $infoArr)
			{				
				$excludeCodesArray[] = $infoArr['ticketCode'];				
			}

			$ticketCode = generateUniqueCode($excludeCodesArray);			
			$dataArr['ticketCode'] = $ticketCode;

			// Here, get menu code
			$menuInfoArr = $objDBQuery->getRecord(0, array('menuType'), 'tbl_menus', array('menuCode' => $appStreamsInfoArr[0]['menuCode_FK']), '', '', '', '');
			$menuType = $menuInfoArr[0]['menuType'];
			$emailAbbr = '';
			if ($menuType == 'E')
			{
				$dataArr['type'] = 'L';
				$emailAbbr = 'app-buy-live-event-ticket';
				$eventOrStreamTitle = $appStreamsInfoArr[0]['streamTitle'];
				
				$timezoneOffset = $appStreamsInfoArr[0]['timezoneOffset'];
				//$eventStDateTime = getTimestamp(convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['eventStDateTime'], $timezoneOffset), 'm-d-Y H:i:s');
				//$eventEndDateTime = getTimestamp(convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['eventEndDateTime'], $timezoneOffset), 'm-d-Y H:i:s');
				//$timing = getTimestamp(convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['eventStDateTime'], $timezoneOffset), 'd/m/Y');
				//$eventTime = getTimestamp(convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['eventStDateTime'], $timezoneOffset), 'h:i: A');
				$streamEventStDateTimeOn = $appStreamsInfoArr[0]['eventStDateTime'];
				
				list($eStDate, $eStTime) = explode(" ",  $streamEventStDateTimeOn, 2);			
				//list($country, $city) = explode('/', str_replace('_', ' ', $timezoneOffset));
				$timezoneOffset = str_replace('_', ' ', $timezoneOffset);
				
				$timing = "<b>Date:</b> ".$eStDate." | <b>Event Time:</b> $eStTime | <b>Region:</b> $timezoneOffset";
				//$timing = "Start: $eventStDateTime End: $eventEndDateTime";
			
			}
			else if ($menuType == 'D')
			{
				$dataArr['type'] = 'D';
				$eventOrStreamTitle = $appStreamsInfoArr[0]['streamTitle'];
				$timing = $interval;
				
				$emailAbbr = 'app-buy-donate-per-ticket';
				$eventTime = '';
			}
	
			$dataArr['status'] = 'O';
			$ticketId_PK = $objDBQuery->addRecord(0, $dataArr, 'tbl_ticket_codes');
			if ($ticketId_PK)
			{				
				$userInfoArr = $objDBQuery->getRecord(0, array('menuType'), 'tbl_menus', array('menuCode' => $appStreamsInfoArr[0]['menuCode_FK']), '', '', '', '');
				//makeEmailStructure4API('accountCreate', $email, $dataArr['name'], $email, $password, $accountActivationCode);
				
				$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, $emailAbbr);
				$userInfoArr = $objDBQuery->getRecord(0, array('username', 'fname', 'lname', 'email', 'name'), 'tbl_registered_users', array('userCode' => $userCode), '', '', '', '');
				$email = $userInfoArr[0]['email']; 

				$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $userInfoArr[0]['name'], '', '', '', $eventOrStreamTitle, $ticketCode, $timing, $eventTime);

				$isMailSent = sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');
				if ($emailAbbr == 'app-buy-live-event-ticket' && $isMailSent == 1)
				{
					$objDBQuery->updateRecord(0, array('isAccessCodeSent' => 'Y','updatedOn' => date(LONG_MYSQL_DATE_FORMAT)), 'tbl_ticket_codes', array('ticketId_PK' => $ticketId_PK));
				}
				
				$whereArr['ppcId_PK'] = $ppcId_PK;				
				$rtnRes = array('status' => 1, 'msg' => "Ticket Code has been sent your registered email id.", 'data' => array('ticket_code' => $ticketCode));
				
			}
			else
			{
				$rtnRes = array('status' => 0, 'msg' => "Sorry, payment information does not save.");	
			}
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	case 'watchWithTicketCodeForDonatePerView':
		// Insert User Data	
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];
		$ticketCode = strtoupper($_POST['ticketCode']);		
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode))) $rtnRes = array('status' => 0, 'msg' => 'Ticket Code does not match in our system.');
		else if (!$objDBQuery->getRecordCount(0, 'tbl_registered_users', array('userCode' => $userCode))) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_streams', array('streamCode' => $streamGuid))) $rtnRes = array('status' => 0, 'msg' => 'Stream Code does not match with our db.');
		else if ($objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode, 'isUsed ' => 'Y'))) $rtnRes = array('status' => 0, 'msg' => 'You have already used this ticket code. Please verify');
		else if (!$objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode, 'streamCode_FK' => $streamGuid, 'status' => 'O', 'type' => 'D'))) $rtnRes = array('status' => 0, 'msg' => 'Invalid ticket Code for this stream, please check with another stream.');
		else if ($streamGuid != '' && $userCode != '' && $ticketCode != '')
		{		
			$arrSelectDbFlds4Stream = array('subscriptionExpiredFaq', 'subscriptionExpired');
			$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('streamCode' => $streamGuid), '', '', '', '');
			
			$subscriptionExpiredFaq = $appStreamsInfoArr[0]['subscriptionExpiredFaq'];
			$subscriptionExpired = $appStreamsInfoArr[0]['subscriptionExpired'];
			$interval = "24 hour";

			if ($subscriptionExpiredFaq != '' && $subscriptionExpired != '' && $subscriptionExpired != 0)
			{
				$interval = "$subscriptionExpired $subscriptionExpiredFaq";
			}

			$arrDateInfo = getExpiredDate($interval);

			$frmKeyExcludeArr = array('postAction', 'appId', 'streamGuid', 'userCode', 'ticketCode');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['userCode_FK'] = $userCode;
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['streamCode_FK'] = $streamGuid;
			$dataArr['rentedOn'] = $arrDateInfo['readableCurrDt'];
			$dataArr['rentedOnInUnixTime'] = $arrDateInfo['unixtimeCurrnt'];
			$dataArr['ppcExpiredOnInUnixTime'] = $arrDateInfo['unixtimeExtended'];
			$dataArr['ppcExpiredOn'] = $arrDateInfo['readableExtendedDt'];;
			$dataArr['paymentInformation'] = 'Buyed Ticket: '.$ticketCode;
			$dataArr['ticketCode_FK'] = $ticketCode;			
			$dataArr['ipAddress'] = getRealIpAddr();
			$dataArr['createdOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$ppcId_PK = $objDBQuery->addRecord(0, $dataArr, $tblName);

			if ($ppcId_PK)
			{
				$dataArr4Ticket = array('isUsed' => 'Y', 'userCode_FK' =>  $userCode, 'updatedOn' => date(LONG_MYSQL_DATE_FORMAT));
				$objDBQuery->updateRecord(0, $dataArr4Ticket, 'tbl_ticket_codes', array('ticketCode' => $ticketCode, 'appCode_FK' =>  $appCode));
				$whereArr['ppcId_PK'] = $ppcId_PK;
				//$whereArr['streamId_FK'] = $streamId_PK;
				
				$arrFld = array('streamCode_FK AS stream_guid', 'rentedOn AS stream_rent_on_utc', 'rentedOnInUnixTime AS stream_rent_on_in_unix_time', 'ppcExpiredOnInUnixTime AS stream_expired_on_in_unix_time', 'ppcExpiredOn AS stream_expired_on_utc');
				$infoArr = $objDBQuery->getRecord(0, $arrFld, $tblName, $whereArr);
				
				$rtnRes = array('status' => 1, 'msg' => "Stream information has been retrieved successfully.", 'data' => $infoArr[0]);
				
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, payment information does not save.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	case 'watchWithTicketCodeForLiveEvent':
		// Insert User Data	
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];
		$ticketCode = strtoupper($_POST['ticketCode']);	
		
		$isMasterCode = 'N';
		// Here, handling for master code
		if ($objDBQuery->getRecordCount(0, 'tbl_master_codes', array('appCode_FK' => $appCode, 'masterCode' => $ticketCode)) && !$objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode, 'buyerUserCode_FK' => $userCode, 'streamCode_FK' => $streamGuid)))
		{
			$isMasterCode = 'Y';
			$dataArr2['ticketCode'] = $ticketCode;
			//$dataArr2['userCode_FK'] = $userCode;
			$dataArr2['buyerUserCode_FK'] = $userCode;
			$dataArr2['appCode_FK'] = $appCode;
			$dataArr2['streamCode_FK'] = $streamGuid;			
			$dataArr2['amount'] = 0;			
			$dataArr2['status'] = 'O';			
			$dataArr2['type'] = 'L';			
			$dataArr2['isMasterCode'] = 'Y';			
			$dataArr2['buyInformation'] = 'Master Code';
			$dataArr2['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$dataArr2['buyedOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$objDBQuery->addRecord(0, $dataArr2, 'tbl_ticket_codes');		
		}

//else if ($objDBQuery->getRecordCount(0, 'tbl_ticket_codes', $whrCls)) $rtnRes = array('status' => 0, 'msg' => 'You have already used this ticket code. Please verify');

		$whrCls = "appCode_FK ='$appCode' AND ticketCode = '$ticketCode' AND isUsed = 'Y' AND isMasterCode = 'N'";
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_registered_users', array('userCode' => $userCode))) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_streams', array('streamCode' => $streamGuid))) $rtnRes = array('status' => 0, 'msg' => 'Stream Code does not match with our db.');
		else if (!$objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode))) $rtnRes = array('status' => 0, 'msg' => 'Ticket Code does not match in our system.');
		else if (!$objDBQuery->getRecordCount(0, 'tbl_ticket_codes', "appCode_FK ='$appCode' AND ticketCode = '$ticketCode' AND `type`  = 'L' AND ((`status` = 'O' AND streamCode_FK ='$streamGuid') OR (`status` = 'N'))")) $rtnRes = array('status' => 0, 'msg' => 'Invalid ticket Code for this stream, please check with another stream.');
		else if ($streamGuid != '' && $userCode != '' && $ticketCode != '')
		{		
			$arrSelectDbFlds4Stream = array('subscriptionExpiredFaq', 'subscriptionExpired', 'eventStDateTime AS stream_event_st_date_time_on_utc', 'eventEndDateTime AS stream_event_end_date_time_on_utc', 'timezoneOffset');
			$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('streamCode' => $streamGuid), '', '', '', '');
			
			$subscriptionExpiredFaq = $appStreamsInfoArr[0]['subscriptionExpiredFaq'];
			$subscriptionExpired = $appStreamsInfoArr[0]['subscriptionExpired'];
			$interval = "24 hour";

			if ($subscriptionExpiredFaq != '' && $subscriptionExpired != '' && $subscriptionExpired != 0)
			{
				$interval = "$subscriptionExpired $subscriptionExpiredFaq";
			}

			if ($isMasterCode == 'Y') $interval = "4 year"; 
			$arrDateInfo = getExpiredDate($interval);

			$isLiveEventBuyed = 'Y';
			$dataArr4Ticket = array('isUsed' => 'Y', 'userCode_FK' =>  $userCode, 'updatedOn' => date(LONG_MYSQL_DATE_FORMAT));
			if ($objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode, 'status ' => 'N')))
			{
				$isLiveEventBuyed = 'Y';
				$dataArr4Ticket['status'] = 'F';	
				$dataArr4Ticket['buyerUserCode_FK'] = $userCode;	
				$dataArr4Ticket['userCode_FK'] = $userCode;	
				$dataArr4Ticket['streamCode_FK'] = $streamGuid;	
				$dataArr4Ticket['buyedOn'] = date(LONG_MYSQL_DATE_FORMAT);					
			}			
			
			$frmKeyExcludeArr = array('postAction', 'appId', 'streamGuid', 'userCode', 'ticketCode');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['userCode_FK'] = $userCode;
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['streamCode_FK'] = $streamGuid;
			$dataArr['rentedOn'] = $arrDateInfo['readableCurrDt'];
			$dataArr['rentedOnInUnixTime'] = $arrDateInfo['unixtimeCurrnt'];
			$dataArr['paymentInformation'] = 'Buyed Ticket: '.$ticketCode;
			$dataArr['isLiveEventBuyed'] = $isLiveEventBuyed;
			$dataArr['ticketCode_FK'] = $ticketCode;			
			$dataArr['ipAddress'] = getRealIpAddr();
			$dataArr['createdOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$ppcId_PK = $objDBQuery->addRecord(0, $dataArr, $tblName);

			if ($ppcId_PK)
			{
				$objDBQuery->updateRecord(0, $dataArr4Ticket, 'tbl_ticket_codes', array('ticketCode' => $ticketCode, 'appCode_FK' =>  $appCode));
				$whereArr['ppcId_PK'] = $ppcId_PK;
				//$whereArr['streamId_FK'] = $streamId_PK;
				
				$arrFld = array('streamCode_FK AS stream_guid', 'rentedOn AS stream_rent_on_utc', 'rentedOnInUnixTime AS stream_rent_on_in_unix_time', 'ppcExpiredOnInUnixTime AS stream_expired_on_in_unix_time', 'ppcExpiredOn AS stream_expired_on_utc', 'isLiveEventBuyed AS is_live_event_buyed');
				$infoArr = $objDBQuery->getRecord(0, $arrFld, $tblName, $whereArr);

				$timezoneOffset = $appStreamsInfoArr[0]['timezoneOffset'];
				$infoArr[0]['stream_event_st_date_time_on_utc'] = convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['stream_event_st_date_time_on_utc'], $timezoneOffset);
				$infoArr[0]['stream_event_end_date_time_on_utc'] = convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['stream_event_end_date_time_on_utc'], $timezoneOffset);
				unset($infoArr[0]['timezoneOffset']);
				
				$rtnRes = array('status' => 1, 'msg' => "Stream information has been retrieved successfully.", 'data' => $infoArr[0]);
				///stream_expired_on_utc
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, payment information does not save.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	case 'validateGuestTicketCode':		
		$_POST = trimFormValue(0, $_POST);
		$appCode = $_POST['appId'];
		$ticketCode = strtoupper($_POST['ticketCode']);		
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode))) $rtnRes = array('status' => 0, 'msg' => 'Ticket Code does not match in our system.');
		else if ($objDBQuery->getRecordCount(0, 'tbl_ticket_codes', array('appCode_FK' => $appCode, 'ticketCode' => $ticketCode, 'isUsed ' => 'Y'))) $rtnRes = array('status' => 0, 'msg' => 'Already used this ticket code.');
		else if ($appCode != '' && $ticketCode != '')
		{				
			$dataArr4Ticket = array('isUsed' => 'Y', 'updatedOn' => date(LONG_MYSQL_DATE_FORMAT));
			$objDBQuery->updateRecord(0, $dataArr4Ticket, 'tbl_ticket_codes', array('ticketCode' => $ticketCode, 'appCode_FK' =>  $appCode));
			$rtnRes = array('status' => 1, 'msg' => "This is a valid ticket code.", 'data' => array());	
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}