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
$accessCase = $requestMethod;

//echo "inn3".$accessCase;

switch($accessCase)
{
	case 'GET':
		// Retrive Users Info	
		//echo $_GET["appDomain"];
		
		if (!empty($_GET["appDomain"]))
		{
			if ($_GET["dataOption"] == "swigits")
			{
				$appInfoArray = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', array('appDomain' => $_GET["appDomain"], 'status' => 'A'));
				if (is_array($appInfoArray) && !empty($appInfoArray))
				{
					$noStreams = true;
					$appDataArr = array();
					//echo "code:".
					$appCode = $appInfoArray[0]['appCode'];
					
					$appDataArr['appDetails'] = $appInfoArray[0];


					$arrSelectDbFlds4Menu = array('menuCode', 'menuName', 'menuType', 'menuOrder');
					$appMenusInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Menu, 'tbl_menus', array('appCode_FK' => $appCode, 'status' => 'A'), '', '', 'menuOrder', 'ASC');
							
					
					if (!empty($appMenusInfoArr) && is_array($appMenusInfoArr))
					{			
					
                        //echo "menu count = ".
                        $numOfRows4Menu = count($appMenusInfoArr);
						$indexV = 0;
						for ($i = 0; $i< $numOfRows4Menu; $i++)
						{
    						$menuName = $appMenusInfoArr[$i]['menuName'];
    						$menuType = $appMenusInfoArr[$i]['menuType'];
    						$menuCode = $appMenusInfoArr[$i]['menuCode'];
    						$menuOrder = $appMenusInfoArr[$i]['menuOrder'];
    
    						
    
    						$whereArr = array();
    						$whereSArr['appCode_FK'] = $appCode;
    						$whereSArr['menuCode_FK'] = $menuCode;
    						$whereSArr['is_swigit'] = 'Y';
    						
    						//echo "ddd";
                         //   print_r($whereSArr);
    						$appStreamsInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_streams', $whereSArr, '', '', 'streamOrder', 'ASC');
			
    						if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
    						{			
    							$appDataArr['swigits'][$indexV]['menu_name'] = $menuName;
    							$appDataArr['swigits'][$indexV]['menu_type'] = $menuType;
    							$appDataArr['swigits'][$indexV]['menu_order'] = $menuOrder;
								$appDataArr['swigits'][$indexV]['menu_code'] = $menuCode;
    							
								
    							$noStreams = false;
    
                                $numOfRows4Stream = count($appStreamsInfoArr);
    					
    							
    							for ($j = 0; $j < $numOfRows4Stream; $j++)
    							{						
    								
    								$streamIdPk =$appStreamsInfoArr[$j]['streamId_PK'];
    								$menuCode =$appStreamsInfoArr[$j]['menuCode_FK'];
    								$streamCode = $appStreamsInfoArr[$j]['streamCode'];
    								$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
									$streamPermalink = $appStreamsInfoArr[$j]['streamPermalink'];
    								$streamUrl = $appStreamsInfoArr[$j]['streamUrl'];
    								$streamImg = $appStreamsInfoArr[$j]['streamImg'];
    								$streamThumbImg = $appStreamsInfoArr[$j]['streamThumbnail'];
    								$streamDuration = $appStreamsInfoArr[$j]['streamDuration'];
    								$streamdescription = $appStreamsInfoArr[$j]['streamdescription'];
    								$streamDirector = $appStreamsInfoArr[$j]['directedBy'];
    								$streamLogo = $appStreamsInfoArr[$j]['stream_logo'];
    								$streamLogoUrl = $appStreamsInfoArr[$j]['stream_logo_url'];
    								$streamWriter = $appStreamsInfoArr[$j]['writtenBy'];
    								$streamProducer = $appStreamsInfoArr[$j]['producedBy'];
    								$streamEditor = $appStreamsInfoArr[$j]['stream_editor'];
    								$streamLanguage = $appStreamsInfoArr[$j]['language'];
    								$streamAwards = $appStreamsInfoArr[$j]['awards'];
    								$streamGenre = $appStreamsInfoArr[$j]['genre'];
    								$streamCast = $appStreamsInfoArr[$j]['staring'];
    								$streamTrailerUrl = $appStreamsInfoArr[$j]['streamTrailerUrl'];
    								
    							
    								
    								$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
    								$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);
    								
    								if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
    								else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 
    
    								if ($streamThumbImg == '') $streamThumbPath = HTTP_PATH.'/images/default_stream_img.png';
    								else $streamThumbPath = HTTP_PATH.'/uploads/stream_images/'.$streamThumbImg; 
    
    
    								
    
    								if ($menuType == 'L') $streamDuration = 0;
    								else $streamUrl = addHttp($streamUrl);
    
    								
    								$appStreamDatesInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_stream_dates', "streamId_FK = ".$streamIdPk." and active_status = 'A' ", '', '', '', '');
    								//print_r($appStreamDatesInfoArr);
    								$streamDateTimeArray = array();
    								//echo "st=".$streamType;
    								if($streamType == 'M')
    								{
    									if (!empty($appStreamDatesInfoArr) && is_array($appStreamDatesInfoArr))
    									{					
    										//echo "<br>cntROWS= ".	
    										$noOfRowsAppStreamDates = count($appStreamDatesInfoArr);
    										if ( $noOfRowsAppStreamDates > 0)
    										{
    											for ($indSD = 0; $indSD < $noOfRowsAppStreamDates; $indSD++)
    											{
    												$streamDateTimeArray[$indSD] = $appStreamDatesInfoArr[$indSD];
    											}
    										}
    									}
    								}
    
    								$appDataArr['swigits'][$indexV]['streams'][$j] = array(
    									'app_code' => $appCode,
    									'stream_guid' => $streamCode,
    									'menu_guid' => $menuCode,
    									'menu_name' => $menuName,
    									'menu_type' => $menuType, 
    									'stream_title' => $streamTitle,
										'stream_permalink' => $streamPermalink,
    									'stream_url' => $streamUrl,
    									'stream_trailer_url' => $streamTrailerUrl,
    									'stream_logo' => $streamLogo,
    									'stream_logo_url' => $streamLogoUrl,
    									'stream_poster' => $streamImgPath,
    									'stream_thumb_image' => $streamThumbPath,
    									'stream_duration' => "$streamDuration",
    									'stream_description' => "$streamdescription",
    									'stream_director' => "$streamDirector",
    									'stream_writer' => $streamWriter,
    									'stream_producer' => $streamProducer,
    									'stream_genre' => $streamGenre,
    									'stream_cast' => $streamCast,
    									'stream_type' => $streamType,
    									'stream_ticket_usage_type' => $streamTicketUsageType,
    									'stream_created_on' => $createdOn,
    									'stream_updated_on' => $updatedOn,
    									'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
    									'stream_detail_page_url_v2' => HTTP_PATH.'/feed/v1/stream_detail_v2/'."{$appDomain}/$streamTitleQury/",
    									'stream_multiple_dates' => $streamDateTimeArray,
    									'stream_desc_full' => $streamDescFull,
    								);
    								
    								
    							}

								$indexV++;
    						}
    						
						
						}

					}

					
					$appDesignInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_app_design', array('appCode_FK' => $appCode), '', '', '', '');
					if (!empty($appDesignInfoArr) && is_array($appDesignInfoArr))
					{
						
						$appDataArr['appDesigns'] = $appDesignInfoArr[0];
					}

					$appBundleInfoArr = $objDBQuery->getRecord(0, '*', $tblSwigitBundle, array('bundle_appcode_FK' => $appCode, 'bundle_active_status' => 'Y'), '', '', 'bundle_order', 'ASC');
							
					//print_r($appMenusInfoArr);
					 //echo "ddkk";
					if (!empty($appBundleInfoArr) && is_array($appBundleInfoArr))
					{			
						$appDataArr['appBundles'] = $appBundleInfoArr;
					}

					if($noStreams)
					{
						$rtnRes = array('status' => 0, 'msg' => "Sorry, Bundle Swigit data not found.");
					}
					else
					{
						$rtnRes = array('status' => 1, 'msg' => "Bundle Swigit data has been retrieved successfully.", 'data' => $appDataArr);
					}
				}	
			}
			else 
			{
				$appInfoArray = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', array('appDomain' => $_GET["appDomain"], 'status' => 'A'));
				if (is_array($appInfoArray) && !empty($appInfoArray))
				{
					$noStreams = true;
					$appDataArr = array();
					$appCode = $appInfoArray[0]['appCode'];
					
					$appDataArr['appDetails'] = $appInfoArray[0];
				}

				$bundleInfoArray = $objDBQuery->getRecord(0, array('*'), 'tbl_swigit_bundle', array('bundle_permalink' => $_GET["dataOption"]));
				if (is_array($bundleInfoArray) && !empty($bundleInfoArray))
				{

					$bundleId = $bundleInfoArray[0]['swigit_bundleId_PK'];
					$bundleSwigitIds = $bundleInfoArray[0]['bundle_swigit_ids'];
					$bundleSwigitArr = explode(",",$bundleSwigitIds);
					$appDataArr['appBundles'] = $bundleInfoArray[0];
					//	print_r($bundleSwigitArr);
					
					if (!empty($bundleSwigitArr) && is_array($bundleSwigitArr))
					{			
					
                        //echo "menu count = ".
                        $numOfRows4Swigits = count($bundleSwigitArr);
						$indexV = 0;
						for ($i = 0; $i< $numOfRows4Swigits; $i++)
						{
    						
    						
    
    						$whereSArr = array();
    						$whereSArr['streamId_PK'] = $bundleSwigitArr[$i];
    					
    						//$whereSArr['is_swigit'] = 'Y';
    						
    						//echo "ddd";
                         //   print_r($whereSArr);
    						$appStreamsInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_streams', $whereSArr, '', '', 'streamOrder', 'ASC');
			
    						if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
    						{			
    								$noStreams = false;
                                   	$numOfRows4Stream = count($appStreamsInfoArr);
    								$j = 0; 				
    								
    								$streamIdPk =$appStreamsInfoArr[$j]['streamId_PK'];
    								$menuCode =$appStreamsInfoArr[$j]['menuCode_FK'];
    								$streamCode = $appStreamsInfoArr[$j]['streamCode'];
    								$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
									$streamPermalink = $appStreamsInfoArr[$j]['streamPermalink'];
    								$streamUrl = $appStreamsInfoArr[$j]['streamUrl'];
    								$streamImg = $appStreamsInfoArr[$j]['streamImg'];
    								$streamThumbImg = $appStreamsInfoArr[$j]['streamThumbnail'];
    								$streamDuration = $appStreamsInfoArr[$j]['streamDuration'];
    								$streamdescription = $appStreamsInfoArr[$j]['streamdescription'];
    								$streamDirector = $appStreamsInfoArr[$j]['directedBy'];
    								$streamLogo = $appStreamsInfoArr[$j]['stream_logo'];
    								$streamLogoUrl = $appStreamsInfoArr[$j]['stream_logo_url'];
    								$streamWriter = $appStreamsInfoArr[$j]['writtenBy'];
    								$streamProducer = $appStreamsInfoArr[$j]['producedBy'];
    								$streamEditor = $appStreamsInfoArr[$j]['stream_editor'];
    								$streamLanguage = $appStreamsInfoArr[$j]['language'];
    								$streamAwards = $appStreamsInfoArr[$j]['awards'];
    								$streamGenre = $appStreamsInfoArr[$j]['genre'];
    								$streamCast = $appStreamsInfoArr[$j]['staring'];
    								$streamTrailerUrl = $appStreamsInfoArr[$j]['streamTrailerUrl'];
    								
    							
    								
    								$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
    								$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);
    								
    								if ($streamImg == '') $streamImgPath = HTTP_PATH.'/images/default_stream_img.png';
    								else $streamImgPath = HTTP_PATH.'/uploads/stream_images/'.$streamImg; 
    
    								if ($streamThumbImg == '') $streamThumbPath = HTTP_PATH.'/images/default_stream_img.png';
    								else $streamThumbPath = HTTP_PATH.'/uploads/stream_images/'.$streamThumbImg; 
    
    
    								
    
    								if ($menuType == 'L') $streamDuration = 0;
    								else $streamUrl = addHttp($streamUrl);
    
    								
    								$appStreamDatesInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_stream_dates', "streamId_FK = ".$streamIdPk." and active_status = 'A' ", '', '', '', '');
    								//print_r($appStreamDatesInfoArr);
    								$streamDateTimeArray = array();
    								//echo "st=".$streamType;
    								if($streamType == 'M')
    								{
    									if (!empty($appStreamDatesInfoArr) && is_array($appStreamDatesInfoArr))
    									{					
    										//echo "<br>cntROWS= ".	
    										$noOfRowsAppStreamDates = count($appStreamDatesInfoArr);
    										if ( $noOfRowsAppStreamDates > 0)
    										{
    											for ($indSD = 0; $indSD < $noOfRowsAppStreamDates; $indSD++)
    											{
    												$streamDateTimeArray[$indSD] = $appStreamDatesInfoArr[$indSD];
    											}
    										}
    									}
    								}
    
    								$appDataArr['swigits']['streams'][$indexV] = array(
    									'app_code' => $appCode,
    									'stream_guid' => $streamCode,
    									'menu_guid' => $menuCode,
    									'menu_name' => $menuName,
    									'menu_type' => $menuType, 
    									'stream_title' => $streamTitle,
										'stream_permalink' => $streamPermalink,
    									'stream_url' => $streamUrl,
    									'stream_trailer_url' => $streamTrailerUrl,
    									'stream_logo' => $streamLogo,
    									'stream_logo_url' => $streamLogoUrl,
    									'stream_poster' => $streamImgPath,
    									'stream_thumb_image' => $streamThumbPath,
    									'stream_duration' => "$streamDuration",
    									'stream_description' => "$streamdescription",
    									'stream_director' => "$streamDirector",
    									'stream_writer' => $streamWriter,
    									'stream_producer' => $streamProducer,
    									'stream_genre' => $streamGenre,
    									'stream_cast' => $streamCast,
    									'stream_type' => $streamType,
    									'stream_ticket_usage_type' => $streamTicketUsageType,
    									'stream_created_on' => $createdOn,
    									'stream_updated_on' => $updatedOn,
    									'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
    									'stream_detail_page_url_v2' => HTTP_PATH.'/feed/v1/stream_detail_v2/'."{$appDomain}/$streamTitleQury/",
    									'stream_multiple_dates' => $streamDateTimeArray,
    									'stream_desc_full' => $streamDescFull,
    								);
    								
    								
    							

								$indexV++;
    						}
    						
						
						}

					}

					
					$appDesignInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_app_design', array('appCode_FK' => $appCode), '', '', '', '');
					if (!empty($appDesignInfoArr) && is_array($appDesignInfoArr))
					{
						$appDataArr['appDesigns'] = $appDesignInfoArr[0];
					}

				}	

				if($noStreams)
				{
					$rtnRes = array('status' => 0, 'msg' => "Sorry, Bundle  data not found.");
				}
				else
				{
					$rtnRes = array('status' => 1, 'msg' => "Bundle  data has been retrieved successfully.", 'data' => $appDataArr);
				}
			}
							
		}


		responses(0, $rtnRes);
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
		
		
}
