<?php
include_once('../web-config.php'); 
include_once('../includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();

include_once('../includes/functions/common.php'); 
include_once('../includes/functions/form-validation.php');

if (isset($_POST['postAction']))  $accessCase = $_POST['postAction'];
else if (isset($_GET['getAction'])) $accessCase = $_GET['getAction'];

// IF FORM TOKEN IS NOT VALID THEN RETURN ON DEFAULT CASE
if (!empty($_POST['formToken']) && $_POST['formToken'] != $_SESSION['prepareToken']) $accessCase = '';
$tblName = 'tbl_apps';
$enckeyDBFldName = 'appCode';
$msgTxt = 'app';
$assetDirName = 'app_images';

switch ($accessCase) 
{
	case 'deleteRecordAction1':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-apps.php?'.$_SESSION['SESSION_QRY_STRING'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName => $enckey))) $msg = "Record does not match with our record.";  
		else if ($enckey)
		{
			$objDBQuery->dropRecord(0, $tblName,array($enckeyDBFldName => $enckey));

			$_SESSION['msgTrue'] = 1;
			$msg = "Record has been permanently deleted successfully.";		
			//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['deleteTestimonial'], getRealIpAddr());
		}	
		$_SESSION['messageSession'] = $msg;
		break;

	case 'changeRecordStatus':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-apps.php?'.$_SESSION['SESSION_QRY_STRING'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName =>$enckey))) $msg = "Record does not match with our record."; 
		else if ($enckey)
		{
			$infoArr = $objDBQuery->getRecord(0, array($enckeyDBFldName, 'status'), $tblName, array($enckeyDBFldName => $enckey));	
			$status = $infoArr[0]['status'];
			
			if (strtolower($status) == strtolower("A")) 
			{
				$updatedId = $objDBQuery->updateRecord(0, array('status' => 'I'), $tblName, array($enckeyDBFldName => $enckey));
			}
			else if (strtolower($status) == strtolower("I"))
			{
				$updatedId = $objDBQuery->updateRecord(0, array('status' => 'A'), $tblName, array($enckeyDBFldName => $enckey));
			}

			if ($updatedId)
			{
				$msg = "Status has been changed succussfully.";
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
			}
			else $msg = "Status does not  change in our record.";
		}	
		$_SESSION['messageSession'] = $msg;
		break;
	
	case 'addAction':	

		$_POST = trimFormValue(0, $_POST);
		
		$headerRedirectUrl = '../add-edit-app.php';
		$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
		$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
		if ($objDBQuery->getRecordCount(0, $tblName, "appName = '".strtolower($dataArr['appName'])."'"))
		{
			viewState($dataArr, 1);
			$_SESSION['messageSession'] = 'Sorry, App Name already exists. Please try another.';	
		}
		else if (allowedFIleExten('app_logo'))
		{
			$_SESSION['messageSession'] = "Please upload app logo format png, jpg or gif";	
			viewState($dataArr, 1);			
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';	
			$fileName =  fileUpload(0, 'app_logo', $assetDirName);
			if ($fileName) $dataArr['app_logo'] = $fileName;
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			$dataArr[$enckeyDBFldName] = randomMD5();
			if ($objDBQuery->addRecord(0, $dataArr, $tblName))
			{				
				$msg = "New $msgTxt has been added successfully.";				
				$_SESSION['msgTrue'] = 1;
				$headerRedirectUrl = '../view-all-apps.php';
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
			}
			else $msg = "Data does not add";	
			$_SESSION['messageSession'] = $msg;	
		}
		else
		{
			viewState($dataArr, 1);
		}
		break;

	case 'updateAction':
		$_POST = trimFormValue(0, $_POST);
		$enkey = $_POST['enkey'];
		$appName = $_POST['appName'];
		$headerRedirectUrl = "../add-edit-app.php?enkey=".$enkey;
		
		if ($objDBQuery->getRecordCount(0, $tblName, "$enckeyDBFldName != '$enkey' AND appName = '".strtolower($appName)."'"))
		{
			
			$_SESSION['messageSession'] = 'Sorry, App Name already exists. Please try another.';	
		}
		else if (allowedFIleExten('app_logo'))
		{
			$_SESSION['messageSession'] = "Please upload app logo format png, jpg or gif";	
			viewState($dataArr, 1);			
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';	
            $infoArr = $objDBQuery->getRecord(0, array('app_logo'), $tblName, "$enckeyDBFldName != '$enkey' AND appName = '".strtolower($appName)."'");
			$fileName =  fileUpload(0, 'streamImg', $assetDirName);
			if ($fileName) 
			{
				$dataArr['streamImg'] = $fileName;				
				unlinkFile(0, $infoArr[0]['streamImg'], $assetDirName);
			}

			$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);

			if ($objDBQuery->updateRecord(0, $dataArr, $tblName, array($enckeyDBFldName => $enkey)))
			{	
					
				$msg = strtoupper($msgTxt)." detail has been updated successfully.";				
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
				$headerRedirectUrl = '../view-all-apps.php?'.$_SESSION['SESSION_QRY_STRING'];
			}
			else $msg = "Data does not update";	
			$_SESSION['messageSession'] = $msg;	
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
