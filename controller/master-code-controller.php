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
$tblName = 'tbl_master_codes';
$enckeyDBFldName = 'masterId_PK';
$msgTxt = 'Short Description';

switch ($accessCase) 
{

	case 'generateMasterCodes':		
		$_POST = trimFormValue(0, $_POST);
		$headerRedirectUrl = '../generate-master-code.php?'.$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];;
		$frmKeyExcludeArr = array('submitBtn', 'formToken', 'postAction', 'enkey');
		$dataArrs = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
		$shortDescription = $dataArrs['shortDescription'];

		if (!validateForm($_SESSION['formValidation']))
		{
			$msg = '';
			$appCode = $dataArrs['appCode'];
			

			$excludeCodesArray = array();
			$masterCodeInfoArr = $objDBQuery->getRecord(0, array('masterCode'), 'tbl_master_codes', array('appCode_FK' => $appCode));	
			foreach ($masterCodeInfoArr As $infoArr)
			{				
				$excludeCodesArray[] = $infoArr['masterCode'];				
			}

			$ticketCodeInfoArr = $objDBQuery->getRecord(0, array('ticketCode'), 'tbl_ticket_codes', array('appCode_FK' => $appCode));	
			foreach ($ticketCodeInfoArr As $infoArr)
			{				
				$excludeCodesArray[] = $infoArr['ticketCode'];				
			}
			
			$masterCode = generateUniqueCode($excludeCodesArray);
			$excludeCodesArray[] = $masterCode;	
			
			$dataArr = array();
			$dataArr['masterCode'] = $masterCode;
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['shortDescription'] = $shortDescription;
			
			$objDBQuery->addRecord(0, $dataArr, $tblName);
			
			$msg = "New Master code has been generated successfully.";				
			$_SESSION['msgTrue'] = 1;
			$headerRedirectUrl = '../view-all-master-codes.php?'.$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];	
			$_SESSION['messageSession'] = $msg;	
		}
		else
		{
			viewState($dataArr, 1);
			$_SESSION['messageSession'] = "Please enter fields.";	
		}
		//print_r(validateForm($_SESSION['formValidation']));
		
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
