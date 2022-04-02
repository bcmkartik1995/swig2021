<?php
include_once('../../../web-config.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

$tblName = 'tbl_stream_events';
$arrFld = array('streamCode_FK AS stream_guid', 'userCode_FK AS userCode', 'appCode_FK AS appId', 'streamDuration AS stream_duration');
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
	case 'saveStreamDuration':		
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];		
		$streamDuration = $_POST['streamDuration'];
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if ($streamGuid != '' && $userCode != '' && $streamDuration != '')
		{
			$frmKeyExcludeArr = array('postAction', 'appId', 'streamGuid', 'userCode');
			$dataArr = prepareKeyValue4Msql(0, $_POST, $frmKeyExcludeArr);
			$dataArr['userCode_FK'] = $userCode;
			$dataArr['appCode_FK'] = $appCode;
			$dataArr['streamCode_FK'] = $streamGuid;			
			$wherCls = array('appCode_FK' => $appCode, 'userCode_FK' => $userCode, 'streamCode_FK' => $streamGuid);
			
			if ($objDBQuery->getRecordCount(0, $tblName, $wherCls))
			{				
				$objDBQuery->updateRecord(0, $dataArr, $tblName, $wherCls);
				$streamEventInfo = $objDBQuery->getRecord(0, $arrFld, $tblName, $wherCls);
				$rtnRes = array('status' => 1, 'msg' => "Stream event has been updated.", 'data' => $streamEventInfo[0]);
			}
			else if ($objDBQuery->addRecord(0, $dataArr, $tblName))
			{			
				$streamEventInfo = $objDBQuery->getRecord(0, $arrFld, $tblName, $wherCls);
				$rtnRes = array('status' => 1, 'msg' => "Stream event has been added.", 'data' => $streamEventInfo[0]);
				
			}
			else
			{
				$rtnRes = array('status' => 0, 'msg' => "Sorry, stream event does not save.");	
			}
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	case 'deleteStreamDuration':		
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];		
		$wherCls = array('appCode_FK' => $appCode, 'userCode_FK' => $userCode, 'streamCode_FK' => $streamGuid);
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblName, $wherCls)) $rtnRes = array('status' => 0, 'msg' => 'Stream guid not found.');
		else if ($streamGuid != '' && $userCode != '')
		{					
			
			$objDBQuery->dropRecord(0, $tblName, $wherCls);
			$rtnRes = array('status' => 1, 'msg' => "Stream duration has been deleted.", 'data' => array());				
			
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	case 'getStreamDuration':		
		$_POST = trimFormValue(0, $_POST);
		$streamGuid = strtolower($_POST['streamGuid']);
		$userCode = strtolower($_POST['userCode']);
		$appCode = $_POST['appId'];		
		$wherCls = array('appCode_FK' => $appCode, 'userCode_FK' => $userCode, 'streamCode_FK' => $streamGuid);
		
		if (!$objDBQuery->getRecordCount(0, 'tbl_apps', array('appCode' => $appCode))) $rtnRes = array('status' => 0, 'msg' => APP_ID_NOT_EXIST_MSG);
		else if (!$objDBQuery->getRecordCount(0, $tblName, $wherCls)) $rtnRes = array('status' => 0, 'msg' => 'Stream guid not found.');
		else if ($streamGuid != '' && $userCode != '')
		{					
			$streamEventInfo = $objDBQuery->getRecord(0, $arrFld, $tblName, $wherCls);
			$rtnRes = array('status' => 1, 'msg' => "Stream duration has been retrieved.", 'data' => $streamEventInfo[0]);				
			
		}
		else $rtnRes = array('status' => 0, 'msg' => 'Data does not receive.');
		responses(0, $rtnRes);
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
