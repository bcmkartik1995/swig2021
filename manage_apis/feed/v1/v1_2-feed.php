<?php
include_once('../../../web-config.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();
cors();
$appCode = trim(@$_GET['appCode']);
$appDataArr['app'] = array('status' => 1, 'msg' => "Data has been retrieved successfully.");
if ($appCode != '')
{	
	$whereArr = array();
	$arrSelectDbFields = array('appCode', 'appName', 'feedback_email', 'feedback_label', 'status', 'refreshtimeInSec', 'menuLimit', 'createdOn', 'updatedOn', 'isLiveTVSubCatLblShow', 'isOnDemandSubCatLblShow', 'isDonatePerViewSubCatLblShow', 'isDonatePerViewLiveEventSubCatLblShow');	
	$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode, 'status' => 'A'));
	
	if (!empty($appInfoArray) && is_array($appInfoArray))
	{
		// HERE PUSH THE APP_PROFILE 
		$refreshtimeInSec = $appInfoArray[0]['refreshtimeInSec'];
		$appDataArr['app']['configuration']['app_profile'] = array(
			//'hosted_location' => $HOSTED_LOCATION_ARR[HOSTED_LOCATION],
			'app_id' => $appInfoArray[0]['appCode'],								
			'app_api_version' => 'v1.0',								
			'app_name' => $appInfoArray[0]['appName'],								
			'refreshtime_in_sec' => "$refreshtimeInSec",	
			//'preload_linear_img' => stripslashesHtmlChars($preloadLinearImg), 

		);

		$appDataArr['app']['configuration']['app_settings']['setting_guid'] = 'ST';
		$appDataArr['app']['configuration']['app_settings']['generic'] = array(
			'feedback_email' => "".$appInfoArray[0]['feedback_email']."",
			'feedback_label' => "".$appInfoArray[0]['feedback_lebel']."",
		);

		$menuLimit = $appInfoArray[0]['menuLimit'];
		$isLiveTVSubCatLblShow = $appInfoArray[0]['isLiveTVSubCatLblShow'];
		$isOnDemandSubCatLblShow = $appInfoArray[0]['isOnDemandSubCatLblShow'];
		$isDonatePerViewSubCatLblShow = $appInfoArray[0]['isDonatePerViewSubCatLblShow'];
		$isDonatePerViewLiveEventSubCatLblShow = $appInfoArray[0]['isDonatePerViewLiveEventSubCatLblShow'];
		
		// HERE PUSH THE APP_MENU 
		$arrSelectDbFlds4Menu = array('menuCode', 'menuName', 'appCode_FK', 'menuType', 'isDefaultMenu');
		$appMenusInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Menu, 'tbl_menus', array('appCode_FK' => $appCode, 'status' => 'A'), 0, $menuLimit, 'menuOrder ASC, createdOn', 'ASC');
		
		if (!empty($appMenusInfoArr) && is_array($appMenusInfoArr))
		{			
			$numOfRows = count($appMenusInfoArr);
			for ($i = 0; $i < $numOfRows; $i++)
			{
				$menuCode = $appMenusInfoArr[$i]['menuCode'];
				$menuName = $appMenusInfoArr[$i]['menuName'];
				$menuType = $appMenusInfoArr[$i]['menuType'];
				$isDefaultMenu = $appMenusInfoArr[$i]['isDefaultMenu'];
				
				$arrFeaturedStreams = array();
				$appDataArr['app']['menus'][$i] = array(
					'menu_guid' => "$menuCode",
					'menu_name' => "$menuName",
					'menu_type' => $menuType,	
					'is_startup_menu' => $isDefaultMenu,
					'featured_streams' => $arrFeaturedStreams,
				);

				// HERE PUSH THE FEATURED_STREAMS 
				$orderBY = "streamOrder ASC, createdOn";
				$arrSelectDbFlds4Stream = array('streamCode', 'streamTitle', 'streamUrl', 'streamImg', 'streamdescription', 'staring', 'streamTrailerUrl', 'streamDuration', 'directedBy', 'writtenBy', 'producedBy', 'genre', 'language', 'awards', 'rating', 'isPremium', 'status', 'isStreamFeatured', 'createdOn', 'updatedOn', 'linearStreamPlayingMethod', 'linearStreamDaiKey');
				$appFeaturedInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('appCode_FK' => $appCode, 'menuCode_FK' => $menuCode, 'status' => 'A', 'isStreamFeatured' => 'Y'), '', '', $orderBY, 'DESC');
				if (!empty($appFeaturedInfoArr) && is_array($appFeaturedInfoArr))
				{
					$numOfRows4Stream = count($appFeaturedInfoArr);
					for ($l = 0; $l < $numOfRows4Stream; $l++)
					{
						
						$streamCode = $appFeaturedInfoArr[$l]['streamCode'];
						$streamTitle = $appFeaturedInfoArr[$l]['streamTitle'];
						$streamUrl = $appFeaturedInfoArr[$l]['streamUrl'];
						$streamImg = $appFeaturedInfoArr[$l]['streamImg'];						
						$streamDuration = $appFeaturedInfoArr[$l]['streamDuration'];						
						$isStreamFeatured = $appFeaturedInfoArr[$l]['isStreamFeatured'];
						$linearStreamPlayingMethod = $appFeaturedInfoArr[$l]['linearStreamPlayingMethod'];
						$linearStreamDaiKey = $appFeaturedInfoArr[$l]['linearStreamDaiKey'];
						
						$createdOn = getTimestamp($appFeaturedInfoArr[$l]['createdOn'], DATE_ATOM);
						$updatedOn = getTimestamp($appFeaturedInfoArr[$l]['updatedOn'], DATE_ATOM);
						
						if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
						else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 

						if ($menuType == 'L') $streamDuration = 0;
						
						$streamUrl = addHttp($streamUrl);

						$arrFeaturedStreams[] = array(
							'stream_guid' => $streamCode,
							'stream_title' => $streamTitle,
							'stream_url' => $streamUrl,
							'stream_playing_method' => $linearStreamPlayingMethod,
							'stream_dai_key' => "$linearStreamDaiKey",
							'stream_poster' => $streamImgPath,
							'stream_duration' => "$streamDuration",
							'is_stream_featured' => "$isStreamFeatured",
							'stream_created_on' => $createdOn,
							'stream_updated_on' => $updatedOn,
							'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
						);
					}
					$appDataArr['app']['menus'][$i]['featured_streams'] = $arrFeaturedStreams;
				}

				$appDataArr['app']['menus'][$i]['subcategories'] = array();	
				
				// Here Get SubCategory Data				
				$whereCls = "menuCode_FK = '$menuCode' AND subCatCode = subCatCode_FK AND s.status = 'A' AND sub.status = 'A' GROUP BY subCatName";				
				$orderBY4SubCat = "sub.subCatOrder ASC, sub.subCatName ASC";
				$subCatInfoArr = $objDBQuery->getRecord(0, array('subCatCode', 'subCatName'), 'tbl_streams s, tbl_subcategories sub', $whereCls, '', '', $orderBY4SubCat, '');
				if (!empty($subCatInfoArr) && is_array($subCatInfoArr))
				{
					$numOfRows4Subcat = count($subCatInfoArr);
					for ($k = 0; $k < $numOfRows4Subcat; $k++)
					{				
						$subCatCode = $subCatInfoArr[$k]['subCatCode'];
						//$subcatData = array('subcat_guid' => $subCatInfoArr[$k]['subCatCode'], 'subcat_title' => $subCatInfoArr[$k]['subCatName']);
						$subCatName = $subCatInfoArr[$k]['subCatName'];
						
						if (($menuType == 'L' && $isLiveTVSubCatLblShow == 'N') || ($menuType == 'V' && $isOnDemandSubCatLblShow == 'N') || ($menuType == 'D' && $isDonatePerViewSubCatLblShow == 'N') || ($menuType == 'E' && $isDonatePerViewLiveEventSubCatLblShow == 'N')) $subCatName = '';
						

						$appDataArr['app']['menus'][$i]['subcategories'][$k]['subcat_guid'] = $subCatInfoArr[$k]['subCatCode'];
						$appDataArr['app']['menus'][$i]['subcategories'][$k]['subcat_title'] = $subCatName;
						// HERE PUSH THE STREAM 
						$orderBY = "isStreamFeatured = 'Y' DESC, streamOrder ASC, createdOn";
						$arrSelectDbFlds4Stream = array('streamCode', 'streamTitle', 'streamUrl', 'streamImg', 'streamdescription', 'staring', 'streamTrailerUrl', 'streamDuration', 'directedBy', 'writtenBy', 'producedBy', 'genre', 'language', 'awards', 'rating', 'isPremium', 'status', 'isStreamFeatured', 'createdOn', 'updatedOn', 'linearStreamPlayingMethod', 'linearStreamDaiKey');
						$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', array('appCode_FK' => $appCode, 'menuCode_FK' => $menuCode, 'status' => 'A', 'subCatCode_FK' => $subCatCode, 'isShowOnlyUpcomingSection' => 'N'), '', '', $orderBY, 'DESC');
						if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
						{					
							$numOfRows4Stream = count($appStreamsInfoArr);
							for ($j = 0; $j < $numOfRows4Stream; $j++)
							{						
								$streamCode = $appStreamsInfoArr[$j]['streamCode'];
								$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
								$streamUrl = $appStreamsInfoArr[$j]['streamUrl'];
								$linearStreamPlayingMethod = $appStreamsInfoArr[$j]['linearStreamPlayingMethod'];
								$linearStreamDaiKey = $appStreamsInfoArr[$j]['linearStreamDaiKey'];
								$streamImg = $appStreamsInfoArr[$j]['streamImg'];
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
								//$streamDuration = $appStreamsInfoArr[$j]['streamDuration'];
								$isStreamFeatured = $appStreamsInfoArr[$j]['isStreamFeatured'];
								
								$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
								$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);
								
								if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
								else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 

								if ($menuType == 'L') $streamDuration = 0;
								
								$streamUrl = addHttp($streamUrl);

								$appDataArr['app']['menus'][$i]['subcategories'][$k]['streams'][$j] = array(
									'stream_guid' => $streamCode,
									'stream_title' => $streamTitle,
									'stream_url' => "$streamUrl",
									'stream_playing_method' => $linearStreamPlayingMethod,
									'stream_dai_key' => "$linearStreamDaiKey",
									'stream_poster' => $streamImgPath,
									'stream_duration' => "$streamDuration",
									'is_stream_featured' => "$isStreamFeatured",
									'stream_created_on' => $createdOn,
									'stream_updated_on' => $updatedOn,
									'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
								);
								
							}
						}
						else $appDataArr['app']['menus'][$i]['subcategories'][$k]['subcategory']['streams'] = array();
					}
				}
			}
		}
		else 
		{
			$appDataArr['app']['menus'][0] = array(
				'menu_guid' => '',
				'menu_name' => '',
				'menu_type' => '',	
				'is_startup_menu' => '',
			);
			$appDataArr['app']['menus'][0]['streams'] = array();
		}
	}
	else $appDataArr['app'] = array('status' => 0, 'msg' => "App Id does not exists in our system."); 

}
else $appDataArr['app'] = array('status' => 0, 'msg' => "App Id is empty."); 


header("Content-type:application/json; charset=UTF-8");
echo $dataStr = json_encode($appDataArr, JSON_PRETTY_PRINT);