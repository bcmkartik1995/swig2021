<?php
include_once('../../web-config.php'); 
include_once('../../smtp.php'); 
include_once('../../includes/classes/DBQuery.php'); 
include_once('../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

cors();

$tblUsers = 'tbl_registered_users';
$arrFld = array('name', 'userCode', 'username', 'appCode_FK AS appId', 'email', "IF(instagram IS NULL,'',instagram) AS instagram", 'accountStatus', 'createdOn', 'updatedOn');
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
	case 'GET':
		// Retrive Users Info			
		if (!empty($_GET["userCode"]))
		{
			$userCode = $_GET["userCode"];	
			$whereArr['userCode'] = $userCode;
			
			if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
			else if ($objDBQuery->getRecordCount(0, $tblUsers, $whereArr))
			{
				$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, $whereArr);
				if (is_array($usersInfoArr)) $rtnRes = array('status' => 1, 'msg' => "user data has been retrieved successfully.", 'data' => $usersInfoArr[0]);
				else $rtnRes = array('status' => 0, 'msg' => "Sorry, no data found.");
			}				
		}
		else
		{
			$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, '', '', '', 'createdOn', 'ASC1');
			if (is_array($usersInfoArr)) $rtnRes = array('status' => 1, 'msg' => 'all users data has been retrieved successfully.', 'totalRecords' => count($usersInfoArr), 'data' => $usersInfoArr);
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, no data found.");		
		}
		responses(0, $rtnRes);
		break;

	case 'changePassword':		
		$_POST = trimFormValue(0, $_POST);
		$appCode = $_POST['appId'];
		$userCode = $_POST['userCode'];
		$oldPassword = $_POST['oldPassword'];
		$newPassword = $_POST['newPassword'];
		$confirmPassword = $_POST['confirmPassword'];	
				
		$whereArr['appCode_FK'] = $appCode;
		$whereArr['userCode'] = $userCode;
		$whereArr['password'] = generatePassword($oldPassword);

		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_PIN_OLD_NOT_FOUND_DB_MSG);	
		else if ($newPassword != $confirmPassword) $rtnRes = array('status' => 0, 'msg' => USER_PIN_CONFIRM_NOT_MATCH);
		else if ($objDBQuery->getRecordCount(0, $tblUsers, $whereArr))
		{			
			$dataArr['password'] = generatePassword($newPassword);
        	
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			
			if ($objDBQuery->updateRecord(0, $dataArr, $tblUsers, $whereArr))
			{
				$whereArr['password'] = generatePassword($newPassword);
            	$whereArr['chngd_password'] = $newPassword;
				$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, $whereArr);
				$rtnRes = array('status' => 1, 'msg' => "Your password has been updated in our system.", 'data' => $usersInfoArr[0]);
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, user password does not reset.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => "Sorry, receiving keys are not valid.");	
		responses(0, $rtnRes);
		break;
		
	case 'forgotPassword':		
		$_POST = trimFormValue(0, $_POST);
		$emailOruserName = $_POST['emailOrusername'];
		
		$appCode = $_POST['appId'];		
		$whereArr['appCode_FK'] = $appCode;		
		if (!filter_var($emailOruserName, FILTER_VALIDATE_EMAIL)) $dbFld = 'username';
		else $dbFld = 'email';

		$whereArr[$dbFld] = $emailOruserName;

		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_USERNAME_MSG);
		else if ($emailOruserName == '') $rtnRes = array('status' => 0, 'msg' => "Please enter value.");
		else if ($objDBQuery->getRecordCount(0, $tblUsers, $whereArr))
		{				
			$password = makeRandNo6Digit();
			$dataArr['password'] = generatePassword($password);
        	$dataArr['chngd_password'] = $password;
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			
			if ($objDBQuery->updateRecord(0, $dataArr, $tblUsers, $whereArr))
			{
				$usersInfoArr = $objDBQuery->getRecord(0, array('email', 'name'), $tblUsers, $whereArr);
				
				//makeEmailStructure4API('forgotPassword', $usersInfoArr[0]['email'], $usersInfoArr[0]['name'], '', $password);
				$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, 'app-forgot-password');
				
				$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $usersInfoArr[0]['name'], '', $password, '');

				sendEmail($usersInfoArr[0]['email'], $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');

				$rtnRes = array('status' => 1, 'msg' => "New password has been sent to your registered email address.");
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, user password does not reset.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => "Sorry, receiving keys are not valid.");	
		responses(0, $rtnRes);
		break;

	case 'validateAccountActivationCode':		
		$_POST = trimFormValue(0, $_POST);
		$accountActivationCode = $_POST['accountActivationCode'];
		$emailOruserName = $_POST['emailOrusername'];
		
		$appCode = $_POST['appId'];		
		$whereArr['appCode_FK'] = $appCode;		
		if (!filter_var($emailOruserName, FILTER_VALIDATE_EMAIL)) $dbFld = 'username';
		else $dbFld = 'email';

		$whereArr[$dbFld] = $emailOruserName;
		
		$whereArr2 = $whereArr;
		$whereArr2['accountActivationCode'] = $accountActivationCode;

		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if ($emailOruserName == '') $rtnRes = array('status' => 0, 'msg' => "Please enter value.");
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_USERNAME_MSG);		
		else if ($objDBQuery->getRecordCount(0, $tblUsers, $whereArr2))
		{			
			$dataArr['accountStatus'] = 'A';
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			
			if ($objDBQuery->updateRecord(0, $dataArr, $tblUsers, $whereArr))
			{
				$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, $whereArr);		

				$rtnRes = array('status' => 1, 'msg' => "Your signup process has been completed successfully.", 'data' => $usersInfoArr[0]);
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, user password does not reset.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => "Sorry, your account activation code does not match in our database. Please generate new account activation code by clicking resend link.");	
		responses(0, $rtnRes);
		break;

	case 'resendAccountActivationCode':		
		$_POST = trimFormValue(0, $_POST);		
		$emailOruserName = $_POST['emailOrusername'];
		
		$appCode = $_POST['appId'];		
		$whereArr['appCode_FK'] = $appCode;		
		if (!filter_var($emailOruserName, FILTER_VALIDATE_EMAIL)) $dbFld = 'username';
		else $dbFld = 'email';

		$whereArr[$dbFld] = $emailOruserName;
		$whereArr['accountStatus'] = 'N';
		//$whereArr['appCode_FK'] = $appCode;	

		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if ($emailOruserName == '') $rtnRes = array('status' => 0, 'msg' => "Please enter value.");
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_USERNAME_MSG);		
		else if ($objDBQuery->getRecordCount(0, $tblUsers, $whereArr))
		{			
			$accountActivationCode = makeRandNo6Digit();
			$dataArr['accountActivationCode'] = $accountActivationCode;
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			
			if ($objDBQuery->updateRecord(0, $dataArr, $tblUsers, $whereArr))
			{
				$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, $whereArr);
				
				//makeEmailStructure4API('resendAccountActivationCode', $usersInfoArr[0]['email'], $usersInfoArr[0]['name'], '', '', $accountActivationCode);	
				$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, 'app-resend-account-activation-code');
				$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $usersInfoArr[0]['name'], '', '', $accountActivationCode);

				sendEmail($usersInfoArr[0]['email'], $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');
				$rtnRes = array('status' => 1, 'msg' => "New account activation code has been sent to your registered email address.");
				
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, account activation code does not reset.");			
		}
		else $rtnRes = array('status' => 0, 'msg' => "Sorry, your record does not match in our database.");	
		responses(0, $rtnRes);
		break;

	case 'checkUserLogin':		
		$_POST = trimFormValue(0, $_POST);
		$email = strtolower(str_replace(' ', '', $_POST['email']));
		$password = $_POST['password'];
		$appCode = $_POST['appId'];
		
		$whereArr['appCode_FK'] = $appCode;		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $dbFld = 'username';
		else $dbFld = 'email';

		$whereArr[$dbFld] = $email;

		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_PIN_NOT_FOUND_DB_MSG);
		else if ($password == '') $rtnRes = array('status' => 0, 'msg' => "Please enter valid password.");
		else if ($objDBQuery->getRecordCount(0, $tblUsers, array('appCode_FK' => $appCode, $dbFld => $email, 'password' => generatePassword($password), 'accountStatus' => 'I')))
		{
			$rtnRes = array('status' => 0, 'msg' => 'Your account has been suspended, please contact to help desk.');
		}
		else if ($objDBQuery->getRecordCount(0, $tblUsers, array('appCode_FK' => $appCode, $dbFld => $email, 'password' => generatePassword($password), 'accountStatus' => 'N')))
		{
			$rtnRes = array('status' => 0, 'msg' => 'You have not completed your sign up process, please complete it or signup with same or different email id');
		}
		else if ($objDBQuery->getRecordCount(0, $tblUsers, array('appCode_FK' => $appCode, $dbFld => $email, 'password' => generatePassword($password))))
		{	
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			
			$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, array('appCode_FK' => $appCode, $dbFld => $email, 'password' => generatePassword($password)));

			$whereArr['userCode'] = $usersInfoArr[0]['userCode'];
			$objDBQuery->updateRecord(0, $dataArr, $tblUsers, $whereArr);
			$rtnRes = array('status' => 1, 'msg' => "User authentication process has been completed successfully.", 'data' => $usersInfoArr[0]);			
		}
		else $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_PIN_NOT_FOUND_DB_MSG);	
		responses(0, $rtnRes);
		break;

	case 'register':
		// Insert User Data	
		$_POST = trimFormValue(0, $_POST);
		$email = strtolower($_POST['email']);
		$username = strtolower(str_replace(array(' '), array(''), $_POST['username']));
		$appCode = $_POST['appId'];
		
		$password = $_POST['password'];
		$confirmPassword = $_POST['confirmPassword'];
		$isBypassEmailVerificationStep = @$_POST['isBypassEmailVerificationStep'];
		if ($isBypassEmailVerificationStep == '') $isBypassEmailVerificationStep = 'N';
		//username = '$username' AND appCode_FK = '$appCode' AND accountStatus != 'N'
		//lower(email) = '$email' AND appCode_FK = '$appCode' AND accountStatus != 'N'
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if ($objDBQuery->getRecordCount(0, $tblUsers, "username = '$username' AND appCode_FK = '$appCode' AND accountStatus != 'N'")) $rtnRes = array('status' => 0, 'msg' => USER_NAME_EXIST_MSG);
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_MSG);
		else if ($objDBQuery->getRecordCount(0, $tblUsers, "lower(email) = '$email' AND appCode_FK = '$appCode' AND accountStatus != 'N'")) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_EXIST_MSG);
		else if ($password == '' || $confirmPassword  == '') $rtnRes = array('status' => 0, 'msg' => "Sorry, password does not receive.");
		else if ($password != $confirmPassword) $rtnRes = array('status' => 0, 'msg' => USER_PIN_CONFIRM_NOT_MATCH);
		else if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$userCode = randomMD5(); //
			$accountActivationCode = makeRandNo6Digit();
			$frmKeyExcludeArr = array('newPinRecovery', 'confirmPassword', 'postAction', 'appId', 'isBypassEmailVerificationStep');		
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['userCode'] = $userCode;
			$dataArr['username'] = $username;
			$dataArr['appCode_FK'] = $appCode;
			list($fname, $lname) = explode(' ', $dataArr['name']); 
			$dataArr['fname'] = $fname;
			$dataArr['lname'] = $lname;			
			//$dataArr['dob'] = mysqlDate($dataArr['dob']);	
			$dataArr['password'] = generatePassword($dataArr['password']);
        	$dataArr['orig_password'] = $_POST['password'];
			$dataArr['accountActivationCode'] = $accountActivationCode;
			$dataArr['createdOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);
			
			if ($isBypassEmailVerificationStep == 'Y')
			{
				$dataArr['accountStatus'] = 'A';
				$accountActivationCode = "Not Applicable";
			}
			
			$where4Deletion = "(username = '$username' OR lower(email) = '$email') AND appCode_FK = '$appCode' AND accountStatus = 'N'";
			if ($objDBQuery->getRecordCount(0, $tblUsers, $where4Deletion)) 
			{
				$objDBQuery->dropRecord(0 ,$tblUsers, $where4Deletion);
			}

			
			if ($objDBQuery->addRecord(0, $dataArr, $tblUsers))
			{				
				//makeEmailStructure4API('accountCreate', $email, $dataArr['name'], $email, $password, $accountActivationCode);
				$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, 'app-signup');
				$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $dataArr['name'], $email, $password, $accountActivationCode);

				sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');
				
				$whereArr['userCode'] = $userCode;
				$usersInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblUsers, $whereArr);
				$rtnRes = array('status' => 1, 'msg' => "New user account has been created successfully.", 'data' => $usersInfoArr[0]);
				
			}
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, user account does not create.");			
		}
		responses(0, $rtnRes);
		break;

	case 'PUT':
		// Update User Data	
		$userCode = trim(@$_GET["userCode"]);	
		$whereArr['userCode'] = $userCode;
		
		if (!$userCode) $rtnRes = array('status' => 0, 'msg' => USER_KEY_NOT_RECEIVE_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
		else if ($objDBQuery->getRecordCount(0, $tblUsers, $whereArr))
		{
			$vars = getContents();
			$password = trim($vars['password']);
			$confirmPassword = trim($vars['confirmPassword']);

			$frmKeyExcludeArr = array('newPinRecovery', 'confirmPassword');			
			$dataArr = prepareKeyValue4Msql(0, $vars, $frmKeyExcludeArr);
			$dataArr['password'] = generatePassword($password);
        	$dataArr['chngd_password'] = trim($vars['password']);
			//$dataArr['dob'] = mysqlDate($dataArr['dob']);
			$email = $dataArr['email'];
			
			$usersInfoArr = $objDBQuery->getRecord(0, array('userCode', 'email', 'gender', 'dob', 'password', 'appCode_FK'), $tblUsers, $whereArr);
			$oldEmail = $usersInfoArr[0]['email'];
			$oldPassword = $usersInfoArr[0]['password'];
			$appCode = $usersInfoArr[0]['appCode_FK'];
			$name = $usersInfoArr[0]['name'];

			if ($password == '' || $confirmPassword  == '') $rtnRes = array('status' => 0, 'msg' => "Sorry, password recovery does not receive.");
			else if ($password != $confirmPassword) $rtnRes = array('status' => 0, 'msg' => USER_PIN_CONFIRM_NOT_MATCH);
			else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $rtnRes = array('status' => 0, 'msg' => "Sorry, email does not valid.");		
			else if ($objDBQuery->getRecordCount(0, $tblUsers, "userCode != '$userCode' AND eamil= '$email'")) $rtnRes = array('status' => 0, 'msg' => USER_EMAIL_EXIST_MSG);		
			else if ($objDBQuery->updateRecord(0, $dataArr, $tblUsers, $whereArr))
			{
				if (strtolower($email) !=  strtolower($oldEmail))
				{
					//makeEmailStructure4API('updateEmail', $oldEmail, 'User', '', '', $email);		
					$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, 'app-update-email');
					$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $name, $email);

					sendEmail($oldEmail, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');
				}

				if (generatePassword($password) !=  $oldPassword)
				{
					//makeEmailStructure4API('changePassword', $email, 'User', '', $password);
					$emailFormatInfoArr = $objDBQuery->getEmailFormat(0, $appCode, 'app-change-password');
					$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $name, '', $password);

					sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');
				}
					
				$rtnRes = array('status' => 1, 'msg' => "user account detail has been updated successfully.");				
			}
			else $rtnRes = array('status' => 1, 'msg' => "user account detail has been updated successfully.");		
		}		
		responses(0, $rtnRes);
		break;

	case 'DELETE':
		$userCode = trim(@$_GET["userCode"]);	
		$whereArr['userCode'] = $userCode;
		
		if (!$userCode) $rtnRes = array('status' => 0, 'msg' => USER_KEY_NOT_RECEIVE_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
		else if ($objDBQuery->dropRecord(0, $tblUsers, $whereArr)) $rtnRes = array('status' => 1, 'msg' => "user account has been deleted successfully.");
		else $rtnRes = array('status' => 0, 'msg' => "user account does not delete.");
		responses(0, $rtnRes);
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
