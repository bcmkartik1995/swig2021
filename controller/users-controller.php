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

switch ($accessCase) 
{
	case 'addAction':
	
		$_POST = trimFormValue(0, $_POST);
		unset($_POST['shareId']);
		unset($_POST['GoogleId']);
		$headerRedirectUrl = '../users.php';
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$appCodes = $_POST['appCodes'];

        if (!$_POST['accountType']) $msg = "Please enter all required fields.";
		else if (!$username || !$email || !$password) $msg = "Please enter all required fields.";
		else if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(username) = '$username'")) $msg = "Same username exists, please try with another.";
		else if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(email) = '$email'")) $msg = "Same email exists, please try with another.";
		else if ($username && $email)
		{
			if($_POST['accountType'] == 'A' && !$appCodes){
				$msg = "Please enter all required fields.";
			} else {
                $frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'Password', 'userCode');
				$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
				unset($dataArr['enkey']);
				$dataArr['userCode'] = randomMD5();
				$dataArr['password'] = generatePassword($password);
				 
				if ($objDBQuery->addRecord(0, $dataArr, 'tbl_users'))
				{					
					makeEmailStructure('addAdminAccount', $dataArr['email'], $dataArr['fname'], $dataArr['username'], $password);
					$msg = "New admin account has been created successfully.";
					$_SESSION['msgTrue'] = 1;
				}
				else $msg = "New admin account does not create."; 
			}
		}
		$_SESSION['messageSession'] = $msg;
		break;

	case 'updateAction':
		$_POST = trimFormValue(0, $_POST);
		unset($_POST['shareId']);
		unset($_POST['GoogleId']);
		$headerRedirectUrl = '../users.php';
		$userCode = $_POST['userCode'];
		$username = $_POST['username'];
		$newEmail = $_POST['email'];
		$password = $_POST['password'];

		if (!$_POST['accountType'] || !$username || !$newEmail){
			$msg = "Please enter all required fields.";
	    } 
		else if($_POST['accountType'] == 'A' && !$_POST['appCodes']){
			$msg = "Please enter all required fields.";
		} else {

            if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(email) = '$newEmail' AND userCode != '$userCode'"))
			{
				$msg = "Same email exists, please try with another.";
			}
			else if ($objDBQuery->getRecordCount(0, 'tbl_users', "LOWER(username) = '$username' AND userCode != '$userCode'"))
			{
				$msg = "Same username exists, please try with another.";
			}
			else if ($userCode)
			{
				$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'userCode');
				$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
				 
				unset($dataArr['enkey']);
				if($password != ''){
					$dataArr['password'] = generatePassword($password);
				} else {
					unset($dataArr['password']);
				}
				if ($objDBQuery->updateRecord(0, $dataArr, 'tbl_users', array('userCode' => $userCode)))
				{				
					$msg = "Admin account detail has been updated successfully.";
				    $_SESSION['msgTrue'] = 1;				
				} else {
					$msg = "Admin account detail not updated.";
				    $_SESSION['msgTrue'] = 1;
				}
			}
		}
		$_SESSION['messageSession'] = $msg;
		break;
	default: 
		$_SESSION['messageSession'] = 'Invalid request type';
		$headerRedirectUrl = '../';

		break;
}

unset($objDBQuery);

if (isset($_SESSION['formValidation'])) unset($_SESSION['formValidation']);

if (isset($headerRedirectUrl)) headerRedirect($headerRedirectUrl);