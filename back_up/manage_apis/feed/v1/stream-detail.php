<?php
include_once('../../../web-config.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();

$userCode = trim(@$_GET['userCode']);
$streamCodeFrmQury = trim(@$_GET['streamCode']);
$menuCodeFrmQury = trim(@$_GET['menuCode']);
$appDataArr['app'] = array('status' => 1, 'msg' => "Data has been retrieved successfully.");
if ($userCode != '' && $menuCodeFrmQury != '' && $streamCodeFrmQury != '')
{
	// HERE PUSH THE APP_MENU 
	$arrSelectDbFlds4Menu = array('menuCode', 'menuName', 'appCode_FK', 'menuType', 'isDefaultMenu');
	$appMenusInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Menu, 'tbl_menus', array('menuCode' => $menuCodeFrmQury, 'status' => 'A'), '', '', '', '');
	
	if (!empty($appMenusInfoArr) && is_array($appMenusInfoArr))
	{			
		$numOfRows = count($appMenusInfoArr);
		for ($i = 0; $i < $numOfRows; $i++)
		{
			$appCode_FK = $appMenusInfoArr[$i]['appCode_FK'];
			$menuCode = $appMenusInfoArr[$i]['menuCode'];
			$menuName = $appMenusInfoArr[$i]['menuName'];
			$menuType = $appMenusInfoArr[$i]['menuType'];
			$isDefaultMenu = $appMenusInfoArr[$i]['isDefaultMenu'];
			
			$appDataArr['app']['all_streams'] = array(
				'menu_guid' => "$menuCode",
				'menu_name' => "$menuName",
				'menu_type' => $menuType,	
				'is_startup_menu' => $isDefaultMenu,
			);

			$arrSelectDbFields = array('latestVideoShowInDetailPage');	
			$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode_FK, 'status' => 'A'));
			$latestVideoShowInDetailPage = $appInfoArray[0]['latestVideoShowInDetailPage'];
			
			if ($latestVideoShowInDetailPage == '' || $latestVideoShowInDetailPage == 0)
			{
				$offSet = '';
				$showItem = '';
			}
			else
			{
				$offSet = 0;
				$showItem = $latestVideoShowInDetailPage;
			}

			// HERE PUSH THE APP_MENU 
			$arrSelectDbFlds4Stream = array('menuCode_FK', 'streamCode', 'streamTitle', 'streamUrl', 'streamImg', 'streamdescription', 'streamDuration', 'status', 'createdOn', 'updatedOn');
			$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', "menuCode_FK = '$menuCode' AND status = 'A' AND streamCode != '$streamCodeFrmQury'", $offSet, $showItem, 'createdOn', 'DESC');
			if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
			{					
				$numOfRows4Stream = count($appStreamsInfoArr);
				for ($j = 0; $j < $numOfRows4Stream; $j++)
				{						
					$streamCode = $appStreamsInfoArr[$j]['streamCode'];
					$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
					$streamUrl = $appStreamsInfoArr[$j]['streamUrl'];
					$streamImg = $appStreamsInfoArr[$j]['streamImg'];
					$streamDuration = $appStreamsInfoArr[$j]['streamDuration'];
					$streamdescription = $appStreamsInfoArr[$j]['streamdescription'];
					
					$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
					$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);
					
					if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
					else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 

					if ($menuType == 'L') $streamDuration = 0;
					else $streamUrl = addHttp($streamUrl);

					$appDataArr['app']['all_streams']['latest_streams'][$j] = array(
						'stream_guid' => $streamCode,
						'stream_title' => $streamTitle,
						'stream_url' => $streamUrl,
						'stream_poster' => $streamImgPath,
						'stream_duration' => "$streamDuration",
						'stream_created_on' => $createdOn,
						'stream_updated_on' => $updatedOn,
						'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
					);
					
				}
			}
			else $appDataArr['app']['all_streams']['latest_streams'] = array();
		}
	}
	else 
	{
		$appDataArr['app']['all_streams'] = array(
			'menu_guid' => '',
			'menu_name' => '',
			'menu_type' => '',	
			'is_startup_menu' => '',
		);
		$appDataArr['app']['all_streams']['latest_streams'] = array();
	}

	// Here, Get Current Item Details
	//$appDataArr['app']['all_streams'][0]['current_page_stream']

	$arrSelectDbFlds4Stream = array('menuCode_FK', 'streamCode', 'streamTitle', 'streamUrl', 'streamImg', 'streamThumbnail', 'streamdescription', 'staring', 'streamTrailerUrl', 'streamDuration', 'directedBy', 'writtenBy', 'producedBy', 'genre', 'language', 'awards', 'rating', 'review', 'isPremium', 'status', 'createdOn', 'updatedOn');
	$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('menuCode_FK' => $menuCodeFrmQury, 'status' => 'A', 'streamCode' => $streamCodeFrmQury), '', '', '', '');
	if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
	{					
		$numOfRows4Stream = count($appStreamsInfoArr);
		for ($j = 0; $j < $numOfRows4Stream; $j++)
		{						
			$streamCode = $appStreamsInfoArr[$j]['streamCode'];
			$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
			$streamUrl = $appStreamsInfoArr[$j]['streamUrl'];
			$streamImg = $appStreamsInfoArr[$j]['streamImg'];
			$streamThumbnail = $appStreamsInfoArr[$j]['streamThumbnail'];
			$streamdescription = $appStreamsInfoArr[$j]['streamdescription'];
			$staring = $appStreamsInfoArr[$j]['staring'];
			$streamTrailerUrl = $appStreamsInfoArr[$j]['streamTrailerUrl'];
			$streamDuration = $appStreamsInfoArr[$j]['streamDuration'];
			$directedBy = $appStreamsInfoArr[$j]['directedBy'];
			$writtenBy = $appStreamsInfoArr[$j]['writtenBy'];
			$producedBy = $appStreamsInfoArr[$j]['producedBy'];
			$genre = $appStreamsInfoArr[$j]['genre'];
			$language = $appStreamsInfoArr[$j]['language'];
			$isPremium = $appStreamsInfoArr[$j]['isPremium'];
			$awards = $appStreamsInfoArr[$j]['awards'];
			$rating = $ARR_RATING[$appStreamsInfoArr[$j]['rating']];
			$review = $ARR_REVIEW[$appStreamsInfoArr[$j]['review']];
			
			$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
			$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);
			
			if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
			else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 
			
			if ($streamThumbnail == '') $streamThumbnailPath = HTTP_PATH.'/images/default_stream_thumbnail.png';
			else $streamThumbnailPath = HTTP_PATH.'/uploads/stream_images/'.$streamThumbnail; 

			if ($menuType == 'L') $streamDuration = 0;
			else $streamUrl = addHttp($streamUrl);

			$appDataArr['app']['all_streams']['current_page_stream'] = array(
				'stream_guid' => $streamCode,
				'stream_title' => $streamTitle,
				'stream_url' => $streamUrl,
				'stream_poster' => $streamImgPath,
				'stream_thumbnail' => $streamThumbnailPath,
				'stream_description' => "$streamdescription",
				'staring' => "$staring",
				'stream_duration' => "$streamDuration",
				'stream_trailerUrl' => "$streamTrailerUrl",
				'directed_by' => "$directedBy",
				'written_by' => "$writtenBy",
				'produced_by' => "$producedBy",
				'genre' => "$genre",
				'language' => "$language",
				'awards' => "$awards",
				'rating' => "$rating",
				'review' => "$review",
				'is_premium' => $isPremium,
				'stream_created_on' => $createdOn,
				'stream_updated_on' => $updatedOn,
			);
			
		}
	}
	else $appDataArr['app']['all_streams']['current_page_stream'] = array();
}
else $appDataArr['app'] = array('status' => 0, 'msg' => "Stream or user  Id is missing."); 


header("Content-type:application/json; charset=UTF-8");
echo $dataStr = json_encode($appDataArr, JSON_PRETTY_PRINT);