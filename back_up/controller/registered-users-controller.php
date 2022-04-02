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
if (!empty($_POST['formToken']) && $_POST['formToken'] != $_SESSION['prepareToken']) $accessCase = '';
$tblName = 'tbl_registered_users';
$enckeyDBFldName = 'userCode';
$msgTxt = 'menu';
$assetDirName = 'stream_images';

switch ($accessCase) 
{
	case 'deleteRecordAction':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-registered-users.php?'.$_SESSION['SESSION_QRY_STRING'];
		
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
		$headerRedirectUrl = '../view-all-registered-users.php?'.$_SESSION['SESSION_QRY_STRING'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName =>$enckey))) $msg = "Record does not match with our record."; 
		else if ($enckey)
		{
			$infoArr = $objDBQuery->getRecord(0, array($enckeyDBFldName, 'accountStatus'), $tblName, array($enckeyDBFldName => $enckey));	
			$status = $infoArr[0]['accountStatus'];
			
			if (strtolower($status) == strtolower("A")) 
			{
				$updatedId = $objDBQuery->updateRecord(0, array('accountStatus' => 'I'), $tblName, array($enckeyDBFldName => $enckey));
			}
			else if (strtolower($status) == strtolower("I"))
			{
				$updatedId = $objDBQuery->updateRecord(0, array('accountStatus' => 'A'), $tblName, array($enckeyDBFldName => $enckey));
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
		
		$headerRedirectUrl = '../add-edit-stream.php?'.$_SESSION['SESSION_QRY_STRING'];;
		$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
		$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
		if (allowedFIleExten('streamImg'))
		{
			echo $_SESSION['messageSession'] = "Please upload stream Poster format png, jpg or gif";	
			viewState($dataArr, 1);
			die;
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';	
			$fileName =  fileUpload(0, 'streamImg', $assetDirName);
			if ($fileName) $dataArr['streamImg'] = $fileName;
			
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			$dataArr[$enckeyDBFldName] = randomMD5();
			if ($objDBQuery->addRecord(0, $dataArr, $tblName))
			{				
				$msg = "New $msgTxt has been added successfully.";				
				$_SESSION['msgTrue'] = 1;
				$headerRedirectUrl = '../view-all-registered-users.php?'.$_SESSION['SESSION_QRY_STRING'];;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
			}
			else $msg = "Data does not add";	
			$_SESSION['messageSession'] = $msg;	
		}
		else
		{
			print_r($_SESSION['formValidation']);
			echo $_SESSION['messageSession'] = "Please upload stream Poster format png, jpg or gif";	
			viewState($dataArr, 1);
			die;
		}
		break;

	case 'updateAction':
		$_POST = trimFormValue(0, $_POST);
		$enkey = $_POST['enkey'];
		$appName = $_POST['appName'];
		$headerRedirectUrl = "../add-edit-stream.php?enkey=".$enkey.'&'.$_SESSION['SESSION_QRY_STRING'];
		
		if (allowedFIleExten('streamImg'))
		{
			$_SESSION['messageSession'] = "Please upload banner image format png, jpg or gif";	
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';						
			$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);
			$infoArr = $objDBQuery->getRecord(0, array('streamImg'), $tblName, array($enckeyDBFldName => $enkey));
			$fileName =  fileUpload(0, 'streamImg', $assetDirName);
			if ($fileName) 
			{
				$dataArr['streamImg'] = $fileName;				
				unlinkFile(0, $infoArr[0]['streamImg'], $assetDirName);
			}

			if ($objDBQuery->updateRecord(0, $dataArr, $tblName, array($enckeyDBFldName => $enkey)))
			{	
					
				$msg = strtoupper($msgTxt)." detail has been updated successfully.";				
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
				$headerRedirectUrl = '../view-all-registered-users.php?'.$_SESSION['SESSION_QRY_STRING'];
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
