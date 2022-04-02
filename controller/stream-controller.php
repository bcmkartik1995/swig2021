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
$tblName = 'tbl_streams';
$enckeyDBFldName = 'streamCode';
$msgTxt = 'stream';
$assetDirName = 'stream_images';

switch ($accessCase) 
{
	case 'deleteRecordAction':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-streams.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName => $enckey))) $msg = "Record does not match with our record.";  
		else if ($enckey)
		{
			

			$menuCode = $_POST['menuCode'];
			updateStreamOrder($objDBQuery, $menuCode);
			$arrDBFld = array('streamImg', 'streamThumbnail');
			$infoArr = $objDBQuery->getRecord(0, $arrDBFld, $tblName, array($enckeyDBFldName => $enckey));
			//$objDBQuery->dropRecord(0, $tblName,array($enckeyDBFldName => $enckey));
			$objDBQuery->updateRecord(0, array('status' => 'I', 'isDeleted' => 'Y'), $tblName,array($enckeyDBFldName => $enckey));

			//unlinkFile(0, $infoArr[0]['streamImg'], $assetDirName);
			//unlinkFile(0, $infoArr[0]['streamThumbnail'], $assetDirName);
			
			$_SESSION['msgTrue'] = 1;
			$msg = "Record has been permanently deleted successfully.";		
			//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['deleteTestimonial'], getRealIpAddr());
		}	
		$_SESSION['messageSession'] = $msg;
		break;

	case 'changeRecordFeatured':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-streams.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName =>$enckey))) $msg = "Record does not match with our record."; 
		else if ($enckey)
		{
			$infoArr = $objDBQuery->getRecord(0, array($enckeyDBFldName, 'isStreamFeatured'), $tblName, array($enckeyDBFldName => $enckey));	
			$status = $infoArr[0]['isStreamFeatured'];
			
			if (strtolower($status) == strtolower("Y")) 
			{
				$updatedId = $objDBQuery->updateRecord(0, array('isStreamFeatured' => 'N'), $tblName, array($enckeyDBFldName => $enckey));
			}
			else if (strtolower($status) == strtolower("N"))
			{
				$updatedId = $objDBQuery->updateRecord(0, array('isStreamFeatured' => 'Y'), $tblName, array($enckeyDBFldName => $enckey));
			}

			if ($updatedId)
			{
				$menuCode = $_POST['menuCode'];
				updateStreamOrder($objDBQuery, $menuCode);
				$msg = "Stream status has been changed succussfully.";
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
			}
			else $msg = "Status does not  change in our record.";
		}	
		$_SESSION['messageSession'] = $msg;
		break;

	case 'changeStreamOrder':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-streams.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName =>$enckey))) $msg = "Record does not match with our record."; 
		else if ($enckey)
		{
			$updatedId = $objDBQuery->updateRecord(0, array('streamOrder' => $_POST['currentStreamOrder']), $tblName, array($enckeyDBFldName => $enckey));
			$objDBQuery->updateRecord(0, array('streamOrder' => $_POST['nxtAndPreStreamOrder']), $tblName, array($enckeyDBFldName => $_POST['nxtAndPreStreamCode']));
			if ($updatedId)
			{
				$menuCode = $_POST['menuCode'];
				updateStreamOrder($objDBQuery, $menuCode);
				$msg = "Stream order has been changed succussfully.";
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
			}
			else $msg = "Status does not  change in our record.";
		}	
		$_SESSION['messageSession'] = $msg;
		break;

	case 'changeRecordStatus': 	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-streams.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];
		
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
		
		$headerRedirectUrl = '../add-edit-stream.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];;
		$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
		$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
		if (allowedFIleExten('streamImg'))
		{
			$_SESSION['messageSession'] = "Please upload stream Poster format png, jpg or gif";	
			viewState($dataArr, 1);			
		}
		else if (allowedFIleExten('streamThumbnail'))
		{
			$_SESSION['messageSession'] = "Please upload stream Thumbnail format png, jpg or gif";	
			viewState($dataArr, 1);			
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';	
			$fileName =  fileUpload(0, 'streamImg', $assetDirName);
			if ($fileName) $dataArr['streamImg'] = $fileName;
			
			$fileName =  fileUpload(0, 'streamThumbnail', $assetDirName);
			if ($fileName) $dataArr['streamThumbnail'] = $fileName;
			
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);	
			if(!array_key_exists($dataArr['dontePerViewSelected']))  $dataArr['dontePerViewSelected'] = 1;
			$dataArr[$enckeyDBFldName] = randomMD5();
			if ($objDBQuery->addRecord(0, $dataArr, $tblName))
			{				
				$menuCode = $_POST['menuCode'];
				updateStreamOrder($objDBQuery, $menuCode);
				$msg = "New $msgTxt has been added successfully.";				
				$_SESSION['msgTrue'] = 1;
				$headerRedirectUrl = '../view-all-streams.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
			}
			else
			{
				$msg = "Data does not add";	
				viewState($dataArr, 1);	
			}
			$_SESSION['messageSession'] = $msg;	
		}
		else
		{
			//print_r($_SESSION['formValidation']);
			$_SESSION['messageSession'] = "Please upload stream Poster format png, jpg or gif";	
			viewState($dataArr, 1);			
		}
		break;

	case 'updateAction':
		$_POST = trimFormValue(0, $_POST);
		$enkey = $_POST['enkey'];
		$appName = $_POST['appName'];
		$headerRedirectUrl = "../add-edit-stream.php?enkey=".$enkey.'&'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];
		
		if (allowedFIleExten('streamImg'))
		{
			$_SESSION['messageSession'] = "Please upload Stream Poster format png, jpg or gif";	
		}
		else if (allowedFIleExten('streamThumbnail'))
		{
			$_SESSION['messageSession'] = "Please upload Stream Thumbnail format png, jpg or gif";	
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';						
			$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);
			if($dataArr['dontePerViewSelected'] == '')  $dataArr['dontePerViewSelected'] = 1;
			$infoArr = $objDBQuery->getRecord(0, array('streamImg', 'streamThumbnail'), $tblName, array($enckeyDBFldName => $enkey));
			$fileName =  fileUpload(0, 'streamImg', $assetDirName);
			if ($fileName) 
			{
				$dataArr['streamImg'] = $fileName;				
				unlinkFile(0, $infoArr[0]['streamImg'], $assetDirName);
			}
			
			$fileName =  fileUpload(0, 'streamThumbnail', $assetDirName);
			if ($fileName) 
			{
				$dataArr['streamThumbnail'] = $fileName;				
				unlinkFile(0, $infoArr[0]['streamThumbnail'], $assetDirName);
			}

			if ($objDBQuery->updateRecord(0, $dataArr, $tblName, array($enckeyDBFldName => $enkey)))
			{	
					
				$msg = ucfirst($msgTxt)." detail has been updated successfully.";				
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
				$headerRedirectUrl = '../view-all-streams.php?'.$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];
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
