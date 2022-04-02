<?php
include_once('../../../web-config.php'); 
include_once('../../../smtp.php');
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();
cors();

$tblName = 'tbl_guest_ticket_requests';
$arrFld = array();
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
	case 'sendGuestTicketRequest':		
		$_POST = trimFormValue(0, $_POST);		
		$appCode = $_POST['appId'];		
		$gustEmail = $_POST['gustEmail'];
		$gustName = $_POST['gustName'];
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!filter_var($gustEmail, FILTER_VALIDATE_EMAIL)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_MSG);
		else if ($gustEmail != '' && $gustName != '')
		{			
			$dataArr['ipAddress'] = getRealIpAddr();
			$dataArr['createdOn'] = date(LONG_MYSQL_DATE_FORMAT);
			
			$excludeCodesArray = array();
			$ticketCodeInfoArr = $objDBQuery->getRecord(0, array('ticketCode'), 'tbl_ticket_codes', array('appCode_FK' => $appCode));	
			foreach ($ticketCodeInfoArr As $infoArr)
			{				
				$excludeCodesArray[] = $infoArr['ticketCode'];				
			}

			$ticketCode = generateUniqueCode($excludeCodesArray);			
			$dataArr['ticketCode'] = $ticketCode;
			$dataArr['type'] = 'L';
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['status'] = 'G';
			$emailAbbr = 'app-guest-ticket-code-request';

			if ($objDBQuery->addRecord(0, $dataArr, 'tbl_ticket_codes'))
			{				
				$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, $emailAbbr);
				$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $gustName, '', '', '', '', $ticketCode, '');

				sendEmail($gustEmail, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');

				$frmKeyExcludeArr = array('postAction', 'appId');
				$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);			
				$dataArr['ticketCode_FK'] = $ticketCode;
				$dataArr['appCode_FK'] = $appCode;
				$dataArr['ipAddress'] = getRealIpAddr();
				$dataArr['createdOn'] = date(LONG_MYSQL_DATE_FORMAT);
				$objDBQuery->addRecord(0, $dataArr, $tblName);

											
				$rtnRes = array('status' => 1, 'msg' => "Guest Ticket Code has been sent your register email id '$gustEmail'.", 'data' => array('ticket_code' => $ticketCode));
				
			}
			else
			{
				$rtnRes = array('status' => 0, 'msg' => "Ticket code does not generate.");	
			}
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Gust Name or Email missing.');
		responses(0, $rtnRes);
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
