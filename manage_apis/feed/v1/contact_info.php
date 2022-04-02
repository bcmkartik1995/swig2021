<?php
include_once('../../../web-config.php');
include_once('../../../smtp.php');
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

$tblName = 'tbl_contacts';
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
	case 'sendContactInfo':		
		$_POST = trimFormValue(0, $_POST);
		$appCode = $_POST['appId'];
		$fullName = $_POST['fullName'];		
		$email = $_POST['email'];
		$message = $_POST['message'];
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_MSG);
		else if ($fullName != '' && $email != '' && $message != '')
		{			
			$frmKeyExcludeArr = array('postAction', 'appId');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['ipAddress'] = getRealIpAddr();

			if ($objDBQuery->addRecord(0, $dataArr, $tblName))
			{				
				//$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $emailAbbr);
				$appInfoArr = $objDBQuery->getRecord($traceArr[0], array('appNoreplyEmail', 'appFromEmailTxt', 'appEmailSignature', 'inquiryEmail', 'inquirySubject'), 'tbl_apps', "appCode = '$appCode'");
				
				$strBody = 'Hello Admin';
				$strBody .= "<br><br><b>Name:</b>&nbsp;".$fullName;
				$strBody .= "<br><br><b>Email:</b>&nbsp;".$email;
				$strBody .= "<br><br><b>Message:</b><br>".nl2br($message);
				$strBody .= "<br><br>".$appInfoArr[0]['appEmailSignature'];

				$emailSubject = $appInfoArr[0]['inquirySubject'];
				$to = $appInfoArr[0]['inquiryEmail'];
				
				sendEmail($to, $emailSubject, $strBody, $appInfoArr[0]['appFromEmailTxt'], $appInfoArr[0]['appNoreplyEmail'], 'HTML');
											
				$rtnRes = array('status' => 1, 'msg' => "Thank you for contacting us. You are very important to us, all information received will always remain confidential.", 'data' => array());
				
			}
			else
			{
				$rtnRes = array('status' => 0, 'msg' => "Email does not send.");	
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
