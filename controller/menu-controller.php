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
$tblName = 'tbl_menus';
$enckeyDBFldName = 'menuCode';
$msgTxt = 'category';

switch ($accessCase) 
{
	case 'deleteRecordAction':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-menus.php?'.$_SESSION['SESSION_QRY_STRING_FOR_MENU'];
		
		if (!$enckey) $msg = "Please enter all required fields.";		
		else if (!$objDBQuery->getRecordCount(0, $tblName, array($enckeyDBFldName => $enckey))) $msg = "Record does not match with our record.";  
		else if ($enckey)
		{
			$objDBQuery->dropRecord(0, $tblName, array($enckeyDBFldName => $enckey));
			
			$streamsInfoArr = $objDBQuery->getRecord(0, array('streamImg'), 'tbl_streams', array('menuCode_FK' => $enckey));
			if (!empty($streamsInfoArr))
			{
				for ($i = 0; $i < count($streamsInfoArr); $i++)
				{
					$objDBQuery->dropRecord(0, 'tbl_streams' ,array('streamId_PK' => $streamsInfoArr[$i]['streamId_PK']));
				}

				unlinkFile(0, $$streamsInfoArr[$i]['streamImg'], 'stream_images');
			}

			$_SESSION['msgTrue'] = 1;
			$msg = "Record has been permanently deleted successfully.";		
			//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['deleteTestimonial'], getRealIpAddr());
		}	
		$_SESSION['messageSession'] = $msg;
		break;

	case 'changeRecordStatus':	
		$_POST = trimFormValue(0, $_POST);
		$enckey = $_POST['enckey'];
		$headerRedirectUrl = '../view-all-menus.php?'.$_SESSION['SESSION_QRY_STRING_FOR_MENU'];
		
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
		$appCode_FK = $_POST['appCode_FK'];
		$headerRedirectUrl = '../add-edit-menu.php?'.$_SESSION['SESSION_QRY_STRING_FOR_MENU'];;
		$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
		$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
		if ($objDBQuery->getRecordCount(0, $tblName, "appCode_FK = '$appCode_FK' AND menuName = '".strtolower($dataArr['menuName'])."'"))
		{
			viewState($dataArr, 1);
			$_SESSION['messageSession'] = 'Sorry, Menu Name already exists. Please try another.';	
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';	
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);			
			$dataArr[$enckeyDBFldName] = randomMD5();
			if ($objDBQuery->addRecord(0, $dataArr, $tblName))
			{				
				$msg = "New $msgTxt has been added successfully.";				
				$_SESSION['msgTrue'] = 1;
				$headerRedirectUrl = '../view-all-menus.php?'.$_SESSION['SESSION_QRY_STRING_FOR_MENU'];;
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
		$menuName = $_POST['menuName'];
		$appCode_FK = $_POST['appCode_FK'];
		
		$headerRedirectUrl = "../add-edit-menu.php?enkey=".$enkey.'&'.$_SESSION['SESSION_QRY_STRING_FOR_MENU'];
		
		if ($objDBQuery->getRecordCount(0, $tblName, "appCode_FK = '$appCode_FK' AND $enckeyDBFldName != '$enkey' AND menuName = '".strtolower($menuName)."'"))
		{
			
			$_SESSION['messageSession'] = 'Sorry, Menu Name already exists. Please try another.';	
		}
		else if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';						
			$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey', 'photo_delete');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['updatedOn'] = date(LONG_MYSQL_DATE_FORMAT);

			if ($objDBQuery->updateRecord(0, $dataArr, $tblName, array($enckeyDBFldName => $enkey)))
			{	
					
				$msg = ucfirst($msgTxt)." detail has been updated successfully.";				
				$_SESSION['msgTrue'] = 1;
				//$objDBQuery->trackActivity(0, randomMD5(), $ARR_ACTIVITES['changeStatusApp'], getRealIpAddr());
				$headerRedirectUrl = '../view-all-menus.php?'.$_SESSION['SESSION_QRY_STRING_FOR_MENU'];
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
