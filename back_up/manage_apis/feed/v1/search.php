<?php
include_once('../../../web-config.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

$appCode = trim(@$_POST['appId']);
$keyword = trim(@$_POST['keyword']);
$postAction = trim(@$_POST['postAction']);

$appSearchDataArr['search_result'] = array('status' => 1, 'msg' => "Search action performed successfully.");
if ($appCode == '') $appSearchDataArr['search_result'] = array('status' => 0, 'msg' => "App Id is empty.");
else if ($keyword == '') $appSearchDataArr['search_result'] = array('status' => 0, 'msg' => "Keyword is empty.");
else if ($postAction == '' || $postAction != 'search') $appSearchDataArr['search_result'] = array('status' => 0, 'msg' => "postAction is empty or postAction value wrong.");
else if ($postAction != '' && $appCode != '' && $keyword != '')
{
	$arrSelectDbFields = array('appCode', 'appName');	
	$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode, 'status' => 'A'));
	
	if (!empty($appInfoArray) && is_array($appInfoArray))
	{
		// HERE PUSH THE APP_MENU 
		$stIndex = '';
		$enIndex = '';
		$streamType = trim(@$_POST['streamType']);
		$maxShowStream = trim(@$_POST['maxShowStream']);

		$whereCls = "s.appCode_FK = '$appCode' AND m.appCode_FK = '$appCode' AND s.menuCode_FK = m.menuCode AND s.status = 'A' AND m.status = 'A'";
		
		if ($keyword != '')
		{
			
			$keyword = addslashes($keyword);
			$whereCls .= "AND (s.streamTitle LIKE '%$keyword%' OR s.streamUrl LIKE '%$keyword%' OR s.streamImg LIKE '%$keyword%' OR s.streamThumbnail LIKE '%$keyword%' OR s.streamdescription LIKE '%$keyword%' OR s.staring LIKE '%$keyword%' OR s.streamTrailerUrl LIKE '%$keyword%' OR s.streamDuration LIKE '%$keyword%' OR s.directedBy LIKE '%$keyword%' OR s.writtenBy LIKE '%$keyword%' OR s.producedBy LIKE '%$keyword%' OR s.genre LIKE '%$keyword%' OR s.language LIKE '%$keyword%' OR s.rating LIKE '%$keyword%')";
		}
		if ($streamType == 'L' || $streamType == 'V') $whereCls .= "AND m.menuType = '$streamType'";
	
		if ($maxShowStream != '' && is_numeric($maxShowStream))
		{
			$stIndex = 0;
			$enIndex = $maxShowStream;
		}

		//$whereCls .= " GROUP BY s.streamCode";

		$arrSelectDbFlds4Stream = array('m.menuType', 'menuCode_FK', 's.streamCode', 's.streamTitle', 's.streamUrl', 's.streamImg', 's.streamDuration','s.createdOn', 's.updatedOn');
		$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams s, tbl_menus m', $whereCls, $stIndex, $enIndex, 's.createdOn', 'DESC');
		
		$appSearchDataArr['search_result']['app_name'] = $appInfoArray[0]['appName'];;
		$appSearchDataArr['search_result']['app_id'] = $appInfoArray[0]['appCode'];
		$appSearchDataArr['search_result']['total_rcd'] = "0";
		
		if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
		{					
			$numOfRows4Stream = count($appStreamsInfoArr);
			$appSearchDataArr['search_result']['total_rcd'] = "$numOfRows4Stream";		
			
			for ($j = 0; $j < $numOfRows4Stream; $j++)
			{						
				$menuCode = $appStreamsInfoArr[$j]['menuCode_FK'];
				$menuType = $appStreamsInfoArr[$j]['menuType'];
				$streamCode = $appStreamsInfoArr[$j]['streamCode'];
				$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
				$streamUrl = $appStreamsInfoArr[$j]['streamUrl'];
				$streamImg = $appStreamsInfoArr[$j]['streamImg'];
				$streamDuration = $appStreamsInfoArr[$j]['streamDuration'];
				$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
				$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);

				
				if ($menuType == 'L') $streamDuration = 0;
				else $streamUrl = addHttp($streamUrl);
				
				if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
				else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 
				$appSearchDataArr['search_result']['streams'][$j] = array(
					'menu_guid' => $menuCode,
					'stream_type' => $menuType,
					'stream_guid' => $streamCode,
					'stream_title' => $streamTitle,
					'stream_duration' => "$streamDuration",
					'stream_url' => $streamUrl,
					'stream_poster' => $streamImgPath,
					'stream_created_on' => $createdOn,
					'stream_updated_on' => $updatedOn,
					'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
				);
				
			}
		}
		else $appSearchDataArr['search_result']['streams'] = array();

	}
	else $appSearchDataArr['search_result'] = array('status' => 0, 'msg' => "App Id does not exists in our system."); 

}
else $appSearchDataArr['search_result'] = array('status' => 0, 'msg' => "App Id/Keyword/postAction is empty."); 


header("Content-type:application/json; charset=UTF-8");
echo $dataStr = json_encode($appSearchDataArr, JSON_PRETTY_PRINT);