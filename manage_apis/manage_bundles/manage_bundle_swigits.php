<?php
include_once('../../web-config.php'); 
include_once('../../smtp.php'); 
include_once('../../includes/classes/DBQuery.php'); 
include_once('../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

cors();

$tblSwigitBundle = 'tbl_swigit_bundle';
$arrFld = array('bundle_name', 'bundle_permalink',  'bundle_appcode_FK AS appId', 'bundle_category_FK', 'bundle_logo', 'bundle_price', 'availability_start_date', 'availability_end_date', 'validity_duration', );
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
		
		if (!empty($_GET["bundlePermalink"]))
		{
			
			$bundleInfoArray = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', array('bundle_permalink' => $_GET["bundlePermalink"], 'status' => 'A'));
			if (is_array($appInfoArray) && !empty($appInfoArray))
			{
				$appCode = $appInfoArray[0]['appCode'];
				
				$whereBArr['bundle_appcode_FK'] = $appCode;
				$whereBArr['bundle_active_status'] = 'Y';
				
				if (!$objDBQuery->getRecordCount(0, $tblSwigitBundle,$whereBArr)) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
				else if ($objDBQuery->getRecordCount(0, $tblSwigitBundle, $whereBArr))
				{
					$bundleInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblSwigitBundle, $whereBArr);
					if (is_array($bundleInfoArr)) $rtnRes = array('status' => 1, 'msg' => "Bundle data has been retrieved successfully.", 'data' => $bundleInfoArr[0]);
					else $rtnRes = array('status' => 0, 'msg' => "Sorry, no Bundle data found.");
				}	
			}

			
						
		}

		if (!empty($_GET["bundlePermalink"]))
		{
			//$userCode = $_GET["Code"];	
			$whereArr['bundle_permalink'] = $_GET["bundlePermalink"];
			$whereArr['bundle_active_status'] = 'Y';
			
			if (!$objDBQuery->getRecordCount(0, $tblSwigitBundle, $whereArr)) $rtnRes = array('status' => 0, 'msg' => USER_KEY_MSG);
			else if ($objDBQuery->getRecordCount(0, $tblSwigitBundle, $whereArr))
			{
				$bundleInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblSwigitBundle, $whereArr);
				if (is_array($bundleInfoArr)) $rtnRes = array('status' => 1, 'msg' => "Bundle data has been retrieved successfully.", 'data' => $bundleInfoArr[0]);
				else $rtnRes = array('status' => 0, 'msg' => "Sorry, no data found.");
			}				
		}
		else
		{
			$bundleInfoArr = $objDBQuery->getRecord(0, $arrFld, $tblSwigitBundle, '', '', '', 'createdOn', 'ASC1');
			if (is_array($bundleInfoArr)) $rtnRes = array('status' => 1, 'msg' => 'all Bundle data has been retrieved successfully.', 'totalRecords' => count($bundleInfoArr), 'data' => $bundleInfoArr);
			else $rtnRes = array('status' => 0, 'msg' => "Sorry, no data found.");		
		}
		responses(0, $rtnRes);
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
