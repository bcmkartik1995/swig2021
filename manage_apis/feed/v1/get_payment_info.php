<?php
include_once('../../../web-config.php'); 
include_once('../../../smtp.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

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
	case 'sendPaymentInfo':
		// Insert User Data	
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];
		$amount = $_POST['amount'];
		$paymentInformation = $_POST['paymentInformation'];
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_registered_users', array('userCode' => $userCode))) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
		else if (!$objDBQuery->getRecordCount(0, 'tbl_streams', array('streamCode' => $streamGuid))) $rtnRes = array('status' => 0, 'msg' => 'Stream Code does not match with our db.');
		else if ($streamGuid != '' && $userCode != '' && $paymentInformation != '')
		{		
			$arrSelectDbFlds4Stream = array('menuCode_FK', 'subscriptionExpiredFaq', 'subscriptionExpired', 'eventStDateTime AS stream_event_st_date_time_on_utc', 'eventEndDateTime AS stream_event_end_date_time_on_utc', 'timezoneOffset');
			$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('streamCode' => $streamGuid), '', '', '', '');
			
			$subscriptionExpiredFaq = $appStreamsInfoArr[0]['subscriptionExpiredFaq'];
			$subscriptionExpired = $appStreamsInfoArr[0]['subscriptionExpired'];
			$interval = "24 hour";

			if ($subscriptionExpiredFaq != '' && $subscriptionExpired != '' && $subscriptionExpired != 0)
			{
				$interval = "$subscriptionExpired $subscriptionExpiredFaq";
			}
			//echo $interval;

			$arrDateInfo = getExpiredDate($interval);
			//print_r($arrDateInfo);
			//die;
			// Here, get menu code
			$menuInfoArr = $objDBQuery->getRecord(0, array('menuType'), 'tbl_menus', array('menuCode' => $appStreamsInfoArr[0]['menuCode_FK']), '', '', '', '');
			$menuType = $menuInfoArr[0]['menuType'];
			
			$isLiveEventBuyed = 'N';
			if ($menuType == 'E')
			{
				$isLiveEventBuyed = 'Y';
			}

			$frmKeyExcludeArr = array('postAction', 'appId', 'streamGuid', 'userCode');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['userCode_FK'] = $userCode;
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['streamCode_FK'] = $streamGuid;
			$dataArr['rentedOn'] = $arrDateInfo['readableCurrDt'];
			$dataArr['rentedOnInUnixTime'] = $arrDateInfo['unixtimeCurrnt'];
			$dataArr['ppcExpiredOnInUnixTime'] = $arrDateInfo['unixtimeExtended'];
			$dataArr['ppcExpiredOn'] = $arrDateInfo['readableExtendedDt'];;
			$dataArr['paymentInformation'] = $paymentInformation;
			$dataArr['isLiveEventBuyed'] = $isLiveEventBuyed;
			$dataArr['amount'] = $amount;
			$dataArr['ipAddress'] = getRealIpAddr();
			$dataArr['createdOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$ppcId_PK = $objDBQuery->addRecord(0, $dataArr, $tblName);

			if ($ppcId_PK)
			{
				$whereArr['ppcId_PK'] = $ppcId_PK;
				//$whereArr['streamId_FK'] = $streamId_PK;
				
				$arrFld = array('streamCode_FK AS stream_guid', 'rentedOn AS stream_rent_on_utc', 'rentedOnInUnixTime AS stream_rent_on_in_unix_time', 'ppcExpiredOnInUnixTime AS stream_expired_on_in_unix_time', 'ppcExpiredOn AS stream_expired_on_utc', 'isLiveEventBuyed AS is_live_event_buyed');
				$infoArr = $objDBQuery->getRecord(0, $arrFld, $tblName, $whereArr);

				$timezoneOffset = $appStreamsInfoArr[0]['timezoneOffset'];
				$infoArr[0]['stream_event_st_date_time_on_utc'] = convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['stream_event_st_date_time_on_utc'], $timezoneOffset);
				$infoArr[0]['stream_event_end_date_time_on_utc'] = convertDateTimeSpecificTimeZone($appStreamsInfoArr[0]['stream_event_end_date_time_on_utc'], $timezoneOffset);
				unset($infoArr[0]['timezoneOffset']);
	
				
				$rtnRes = array('status' => 1, 'msg' => "Payment has been received successfully.", 'data' => $infoArr[0]);
				
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, payment information does not save.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;
	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
