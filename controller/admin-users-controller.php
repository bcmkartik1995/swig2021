<?php
include_once('../web-config.php'); 
include_once('../smtp.php'); 

include_once('../includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();

include_once('../includes/functions/common.php'); 
include_once('../includes/functions/form-validation.php');

if (isset($_POST['postAction']))  $accessCase = $_POST['postAction'];
else if (isset($_GET['getAction'])) $accessCase = $_GET['getAction'];

// IF FORM TOKEN IS NOT VALID THEN RETURN ON DEFAULT CASE
if (!empty($_POST['formToken']) && $_POST['formToken'] != $_SESSION['prepareToken'])
{
	$accessCase = '';
}
//echo $accessCase; 
//die;
switch ($accessCase) 
{
	case 'addAdminAccount':
		$_POST = trimFormValue(0, $_POST);
		$headerRedirectUrl = 'view-user-accounts.php';
		$username = $_POST['username'];
		$email = $_POST['email'];

		if (!$username || !$email) $msg = "Please enter all required fields.";
		else if ($objDBQuery->getRecordCount(0, 'tbl_user', "LOWER(username) = '$username'")) $msg = "Same username exists, please try with another.";
		else if ($objDBQuery->getRecordCount(0, 'tbl_user', "LOWER(email) = '$email'")) $msg = "Same email exists, please try with another.";
		else if ($username && $email)
		{
			$password = $_POST['oldPassword'];
			$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'oldPassword', 'userCode');
			 $dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			 
			 $dataArr['userCode'] = randomMD5();
			 $dataArr['password'] = generatePassword($password);
			 
			if ($objDBQuery->addRecord(0, $dataArr, 'tbl_user'))
			{					
				makeEmailStructure('addAdminAccount', $dataArr['email'], $dataArr['fname'], $dataArr['username'], $password);
				$msg = "New admin account has been created successfully.";
				$_SESSION['msgTrue'] = 1;
			}
			else $msg = "New admin account does not create.";
		}
		$_SESSION['messageSession'] = $msg;
		break;

	case 'updateAdminAccount':
		$_POST = trimFormValue(0, $_POST);
		$headerRedirectUrl = 'view-user-accounts.php';
		$userCode = $_POST['userCode'];
		$newEmail = $_POST['email'];

		if (!$userCode) $msg = "Please enter all required fields.";
		else if ($objDBQuery->getRecordCount(0, 'tbl_user', "LOWER(email) = '$newEmail' AND userCode != '$userCode'"))
		{
			$msg = "Same email exists, please try with another.";
		}
		else if ($userCode)
		{
			$oldEmail = $objDBQuery->getRecord(0, array('email'), 'tbl_user', array('userCode' => $userCode))[0]['email'];
			$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'userCode', 'username');
			 $dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			 
			if ($objDBQuery->updateRecord(0, $dataArr, 'tbl_user', array('userCode' => $userCode)))
			{				
				if ($oldEmail != $newEmail) makeEmailStructure('updateAdminEmail', $oldEmail, $dataArr['fname'], '', '', $newEmail);				
			}
			$msg = "Admin account detail has been updated successfully.";
			$_SESSION['msgTrue'] = 1;
			
		}
		$_SESSION['messageSession'] = $msg;
		break;

	case 'userResetPassword':	
		$_POST = trimFormValue(0, $_POST);
		$userCode = $_POST['userCode'];
		$decryptStr = base64_decode($_POST['encryptStr']);
		$headerRedirectUrl = "view-user-accounts.php?$decryptStr";

		
		if (!$userCode) $_SESSION['messageSession'] = "Please enter all required fields.";
		else if ($_SESSION['userDetails']['accountType'] != "S") $headerRedirectUrl = 'logout.php?logout=2';   
		else if (!$objDBQuery->getRecordCount(0, 'tbl_user', array('userCode' =>$userCode))) 
		{
			$_SESSION['messageSession'] = "Admin account detail does not match with our record.";   
		}
		else if ($userCode)
		{
			$password = rand(100000, 999999);			
			$hashedPassword = generatePassword($password);
			if ($objDBQuery->updateRecord(0,array('password' => $hashedPassword), 'tbl_user', array('userCode' => $userCode)))
			{				
				$userAccInfoArr = $objDBQuery->getRecord(0, array('username', 'fname', 'email'), 'tbl_user', array('userCode' => $userCode));				
				makeEmailStructure('accountPasswordReset', $userAccInfoArr[0]['email'], $userAccInfoArr[0]['fname'], $userAccInfoArr[0]['username'], $password);

				$_SESSION['messageSession'] = "New password has been generated successfully.";	
				$_SESSION['msgTrue'] = 1;
			}
			else $_SESSION['messageSession'] = "Admin account password  does not  change in our record.";					
			
		}	
		break;

	case 'dropUserAccount':	
		$_POST = trimFormValue(0, $_POST);
		$userCode = $_POST['userCode'];
		$decryptStr = base64_decode($_POST['encryptStr']);
		$headerRedirectUrl = "view-user-accounts.php?$decryptStr";
		
		if (!$userCode) $_SESSION['messageSession'] = "Please enter all required fields.";
		else if ($_SESSION['userDetails']['accountType'] != "S") $headerRedirectUrl = 'logout.php?logout=2';   
		else if (!$objDBQuery->getRecordCount(0, 'tbl_user', array('userCode' =>$userCode))) 
		{
			$_SESSION['messageSession'] = "Admin account detail does not match with our record.";   
		}
		else if ($userCode)
		{
			$objDBQuery->dropRecord(0,'tbl_user',array('userCode' => $_POST['userCode']));
			$_SESSION['msgTrue'] = 1;
			$_SESSION['messageSession'] = "Admin account has been permanently deleted successfully.";			
		}	
		break;

	case 'userChangeAccountStatus':	
		$_POST = trimFormValue(0, $_POST);
		$userCode = $_POST['userCode'];
		$decryptStr = base64_decode($_POST['encryptStr']);
		$headerRedirectUrl = "view-user-accounts.php?$decryptStr";
		
		if (!$userCode) $msg = "Please enter all required fields.";
		else if ($_SESSION['userDetails']['accountType'] != "S") $headerRedirectUrl = './logout.php';   
		else if (!$objDBQuery->getRecordCount(0, 'tbl_user', array('userCode' =>$userCode))) $msg = "Admin account detail does not match with our record."; 
		else if ($userCode)
		{
			$userAccInfoArr = $objDBQuery->getRecord(0, array('userCode', 'accountStatus'), 'tbl_user', array('userCode' => $userCode));	
			$accountStatus = $userAccInfoArr[0]['accountStatus'];
			
			if (strtolower($accountStatus) == strtolower("A")) 
			{
				$updatedId = $objDBQuery->updateRecord(0, array('accountStatus' => 'I'), 'tbl_user', array('userCode' => $userCode));
			}
			else if (strtolower($accountStatus) == strtolower("I"))
			{
				$updatedId = $objDBQuery->updateRecord(0, array('accountStatus' => 'A'), 'tbl_user', array('userCode' => $userCode));
			}

			if ($updatedId)
			{
				$msg = "Admin account status has been changed succussfully.";
				$_SESSION['msgTrue'] = 1;
			}
			else $msg = "Admin account status does not  change in our record.";
		}	
		 $_SESSION['messageSession'] = $msg;
		break;

	case 'changePassword':
			$_POST = trimFormValue(0, $_POST);				
			$headerRedirectUrl = '../change-password.php';

			if (!validateForm($_SESSION['formValidation']))
			{
				$msg = '';
				$oldPassword = $_POST['oldPassword'];
				$hashedPassword = generatePassword($oldPassword);	
				$userCode = $_POST['userCode'];
				if (!$objDBQuery->getRecordCount(0, 'tbl_users', "password = '$hashedPassword' AND userCode = '$userCode'"))
				{
					$msg = "Sorry, password does not match with our record.";					
				}
				else if ($userCode)
				{			
					$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'userCode', 'username', 'oldPassword', 'cpassword', 'password');
					//$dataArr = prepareKeyValue4Msql(1, $_POST, $frmKeyExcludeArr);
					$newPassword = $_POST['password'];
					$dataArr['lastUpdatedOn'] = date(LONG_MYSQL_DATE_FORMAT);
					$dataArr['password'] = generatePassword($newPassword);
					$oldEmail = $objDBQuery->getRecord(0, array('email'), 'tbl_users', array('userCode' => $userCode))[0]['email'];
					if ($objDBQuery->updateRecord(0, $dataArr, 'tbl_users', array('userCode' => $userCode)))
					{				
						$userAccInfoArr = $objDBQuery->getRecord(0, array('userCode', 'username', 'password', 'fname', 'lname', 'email', 'accountType', 'accountStatus'), 'tbl_users', array('userCode' => $userCode));				
						$_SESSION['userDetails'] = $userAccInfoArr[0];						

						if ($newPassword != $oldPassword)
						{
							makeEmailStructure('changePassword', $userAccInfoArr[0]['email'], $userAccInfoArr[0]['fname'], $userAccInfoArr[0]['username'], $newPassword);
						}
						$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changePassword'], getRealIpAddr());	
						$msg = "Your password has been changed successfully.";				
						$_SESSION['msgTrue'] = 1;
					}
					else $msg = "Data does not update";
				}
				$_SESSION['messageSession'] = $msg;	
			}	
			break;

	case 'updateYourProfile':
			$_POST = trimFormValue(0, $_POST);				
			$headerRedirectUrl = '../profile.php';

			if (!validateForm($_SESSION['formValidation']))
			{
				$msg = '';
				$email = strtolower($_POST['email']);
				$phone = $_POST['phone'];
				$userCode = $_POST['userCode'];
				if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(email) = '$email' AND userCode != '$userCode'"))
				{
					$msg = "Same email exists, please try with another.";
				}
				else if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(phone) = '$phone' AND userCode != '$userCode'"))
				{
					$msg = "Same phone exists, please try with another.";
				}
				else if ($userCode)
				{			
					$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'userCode', 'username');
					$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
					$dataArr['lastUpdatedOn'] = date(LONG_MYSQL_DATE_FORMAT);
					$oldEmail = $objDBQuery->getRecord(0, array('email'), 'tbl_users', array('userCode' => $userCode))[0]['email'];
					if ($objDBQuery->updateRecord(0, $dataArr, 'tbl_users', array('userCode' => $userCode)))
					{				
						$userAccInfoArr = $objDBQuery->getRecord(0, array('userCode', 'username', 'password', 'fname', 'lname', 'email', 'accountType', 'accountStatus'), 'tbl_users', array('userCode' => $userCode));				
						$_SESSION['userDetails'] = $userAccInfoArr[0];
						
						if (strtolower($oldEmail) != $email)
						{							
							makeEmailStructure('updateYourEmail', $oldEmail, $userAccInfoArr[0]['fname'], $userAccInfoArr[0]['username']);					
						}
						$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['updateYourProfile'], getRealIpAddr());	
						$msg = "Your profile detail has been updated successfully.";				
						$_SESSION['msgTrue'] = 1;
					}
					else $msg = "Data does not update";
				}
				$_SESSION['messageSession'] = $msg;	
			}	
			break;

	case 'forgotPassword':
			$_POST = trimFormValue(0, $_POST);			
			$username = strtolower($_POST['username']); 
			$headerRedirectUrl = '../forgot-password.php';

			if (!validateForm($_SESSION['formValidation']))
			{
				if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(username) = '$username'"))
				{
					$accountInfo = $objDBQuery->getRecord(0, array('userCode','username','fname','email'), 'tbl_users', "LOWER(username) = '$username'");	
					
					$password = rand(100000,999999);
					$encPassword = generatePassword($password);
					$updatedId = $objDBQuery->updateRecord(0, array('password' => $encPassword), 'tbl_users',array('userCode' => $accountInfo[0]['userCode']));
					if ($updatedId) 
					{
						makeEmailStructure('forgotPassword', $accountInfo[0]['email'], $accountInfo[0]['fname'], $accountInfo[0]['username'], $password);
						$msg = 'New password has been sent to your registered email address.';						
						$_SESSION['msgTrue'] = 1;
					}
					else $msg = 'There is a error. Please try again.';				
				} 
				else $msg = 'Sorry, username does not match with our record.';
				$_SESSION['messageSession'] = $msg;
			}
			break;

	case 'userLoginAuthentication':	
		$_POST = trimFormValue(0, $_POST);
		$username = $objDBQuery->escapeSpecialCharForSql($_POST['username']);
		$password = $objDBQuery->escapeSpecialCharForSql($_POST['password']);
		$headerRedirectUrl = '../';

		if (!validateForm($_SESSION['formValidation']))
		{
			$checkLoginInfo = $objDBQuery->getRecord(0, array('password'), 'tbl_users', array('username' => $username));
			$hashedPassword = generatePassword($password);	

			if (empty($checkLoginInfo)) $_SESSION['messageSession'] = 'Invalid Username';
			else if ($checkLoginInfo[0]['password'] != $hashedPassword) $_SESSION['messageSession'] = 'Invalid Password';	
			else if (!empty($checkLoginInfo)) 
			{
				$accountDetails = $objDBQuery->getRecord(0, array('userCode', 'username', 'password', 'fname', 'lname', 'email', 'accountType', 'accountStatus', 'appCodes'), 'tbl_users', array('username' => $username, 'password' => $hashedPassword));	
				
				if (empty($accountDetails)) $_SESSION['messageSession'] = 'Technical Problem';
				else if ($accountDetails[0]['accountStatus'] == 'A')
				{
					$_SESSION['userDetails'] = $accountDetails[0];
					$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['login'], getRealIpAddr());
					$headerRedirectUrl = '../view-all-apps.php';
				}
				else if ($accountDetails[0]['accountStatus'] == 'I') $_SESSION['messageSession'] = 'Your account has been suspended.';
				else $_SESSION['messageSession'] = "Condition does not match."; 
			}
		}
		break;

	// Don't remove this case
	default: 
		$_SESSION['messageSession'] = 'Invalid request type';
		$headerRedirectUrl = '../';

		break;
}

unset($objDBQuery);

if (isset($_SESSION['formValidation'])) unset($_SESSION['formValidation']);

if (isset($headerRedirectUrl)) headerRedirect($headerRedirectUrl);