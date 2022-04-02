<?php
ini_set("display_errors", "0");
include_once('../../../web-config.php'); 
include_once('../../../includes/classes/DBQuery.php'); 
include_once('../../../includes/functions/common.php'); 
$objDBQuery = new DBQuery();
cors();
$appDomain = trim(@$_GET['appDomain']);
$streamTitleQury = trim(@$_GET['streamTitle']);
//echo "sd";
//print_r($_REQUEST);
$appDataArr['app'] = array('status' => 1, 'msg' => "Data has been retrieved successfully.");
if ($appDomain != '' && $streamTitleQury != '')
{
	$arrSelectDbFlds4Apps = array('appCode', 'appName');
	$appsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Apps, 'tbl_apps', array('appDomain' => $appDomain), '', '', '', '');
	//echo "iii";
	if (!empty($appsInfoArr) && is_array($appsInfoArr))
	{			
		//echo "cnt=  ".
		$appNumOfRows = count($appsInfoArr);
		if($appNumOfRows > 0)
		{
			//echo "appcode = ".
			$appCode = $appsInfoArr[0]['appCode'];
			$appName = $appsInfoArr[0]['appName'];
			// echo "streamPermalink = '".$streamTitleQury."' AND status = 'A' appCode_FK='".$appCode."'";
			$appStreamsInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_streams', "streamPermalink = '".$streamTitleQury."' AND appCode_FK='".$appCode."'", $offSet, $showItem, 'createdOn', 'DESC');
			
			if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
			{					
				//echo "<br>cnt2= ".
				$numOfRows4Stream = count($appStreamsInfoArr);
				if ( $numOfRows4Stream > 0)
				{						
					$j = 0;
					$streamIdPk =$appStreamsInfoArr[$j]['streamId_PK'];
					$menuCode =$appStreamsInfoArr[$j]['menuCode_FK'];
					$streamCode = $appStreamsInfoArr[$j]['streamCode'];
					$streamTitle = $appStreamsInfoArr[$j]['streamTitle'];
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
					
					$streamShareThisId = $appStreamsInfoArr[$j]['shareThisId'];
					$streamGoogleAnalyticsId = $appStreamsInfoArr[$j]['googleAnalyticsId'];
					$streamType = $appStreamsInfoArr[$j]['stream_type'];
					$streamTicketUsageType = $appStreamsInfoArr[$j]['ticket_usage_type'];
					$streamTimezoneOffset = $appStreamsInfoArr[$j]['timezoneOffset'];

					// $streamStartDateTime = getTimestamp($appStreamsInfoArr[$j]['eventStDateTime'], DATE_ATOM);
					// $streamEndDateTime = getTimestamp($appStreamsInfoArr[$j]['eventEndDateTime'], DATE_ATOM);

					$streamStartDateTime = getTimestamp($appStreamsInfoArr[$j]['eventStDateTime'], 'Y-m-d\TH:i:s+00:00');
					$streamEndDateTime = getTimestamp($appStreamsInfoArr[$j]['eventEndDateTime'], 'Y-m-d\TH:i:s+00:00');
					
					// $createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], DATE_ATOM);
					// $updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], DATE_ATOM);

					$createdOn = getTimestamp($appStreamsInfoArr[$j]['createdOn'], 'Y-m-d\TH:i:s+00:00');
					$updatedOn = getTimestamp($appStreamsInfoArr[$j]['updatedOn'], 'Y-m-d\TH:i:s+00:00');
					
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

					$appStreamLinkInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_stream_links', "streamId_FK = ".$streamIdPk." and active_status = 'A' ", '', '', '', '');
					//print_r($appStreamDatesInfoArr);
					$streamLinkArray = array();
					//echo "st=".$streamType;
		
						if (!empty($appStreamLinkInfoArr) && is_array($appStreamLinkInfoArr))
						{					
							//echo "<br>cntROWS= ".	
							$noOfRowsAppStreamLinks = count($appStreamLinkInfoArr);
							if ( $noOfRowsAppStreamLinks > 0)
							{
								for ($indSL = 0; $indSL < $noOfRowsAppStreamLinks; $indSL++)
								{
									$streamLinkArray[$indSL] = $appStreamLinkInfoArr[$indSL];
								}
							}
						}
				

					$appStreamPaymentOptInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_stream_payment_options', "streamId_FK = ".$streamIdPk." and active_status = 'A' ", '', '', '', '');
					//print_r($appStreamDatesInfoArr);
					$streamPaymentOptArray = array();
					$donation_amount_array = array();
					//echo "st=".$streamType;
					if (!empty($appStreamPaymentOptInfoArr) && is_array($appStreamPaymentOptInfoArr))
					{					
						//echo "<br>cntROWS= ".	
						$noOfRowsPaymentOpt = count($appStreamPaymentOptInfoArr);
						if ( $noOfRowsPaymentOpt > 0)
						{
							
							$streamPaymentOptArray['tbl_stream_payment_options_PK'] = $appStreamPaymentOptInfoArr[0]['tbl_stream_payment_options_PK'];
							$streamPaymentOptArray['streamId_FK'] = $appStreamPaymentOptInfoArr[0]['streamId_FK'];
							$streamPaymentOptArray['payment_gateway'] = $appStreamPaymentOptInfoArr[0]['payment_gateway'];
							$streamPaymentOptArray['payment_type'] = $appStreamPaymentOptInfoArr[0]['payment_type'];
							$streamPaymentOptArray['payment_currency'] = $appStreamPaymentOptInfoArr[0]['payment_currency'];

							if($appStreamPaymentOptInfoArr[0]['payment_type'] == 'F')
								$fixedAmt = (int) $appStreamPaymentOptInfoArr[0]['fixed_ticket_amount'];
							else
								$fixedAmt = null;

							$streamPaymentOptArray['fixed_ticket_amount'] = $fixedAmt;

							$streamPaymentOptArray['minimum_donation_amount'] = $appStreamPaymentOptInfoArr[0]['minimum_donation_amount'];
							$streamDonationAmt1 = (int) $appStreamPaymentOptInfoArr[0]['donation_amount_1'];
							$streamDonationAmt2 = (int) $appStreamPaymentOptInfoArr[0]['donation_amount_2'];
							$streamDonationAmt3 = (int) $appStreamPaymentOptInfoArr[0]['donation_amount_3'];
							$streamDonationAmt4 = (int) $appStreamPaymentOptInfoArr[0]['donation_amount_4'];

							$indAmt = 0;
							if($streamDonationAmt1 > 0)
							{
								$donation_amount_array[$indAmt]["value"] = (string) $streamDonationAmt1;
								$indAmt++;
							}
							if($streamDonationAmt2 > 0)
							{
								$donation_amount_array[$indAmt]["value"] = (string) $streamDonationAmt2;
								$indAmt++;
							}
							if($streamDonationAmt3 > 0)
							{
								$donation_amount_array[$indAmt]["value"] = (string) $streamDonationAmt3;
								$indAmt++;
							}
							if($streamDonationAmt4 > 0)
							{
								$donation_amount_array[$indAmt]["value"] = (string) $streamDonationAmt4;
								$indAmt++;
							}
							


							$streamPaymentOptArray['tbl_stream_payment_options_PK'] = $appStreamPaymentOptInfoArr[0]['donation_amount_2'];
							$streamPaymentOptArray['tbl_stream_payment_options_PK'] = $appStreamPaymentOptInfoArr[0]['donation_amount_3'];
							$streamPaymentOptArray['tbl_stream_payment_options_PK'] = $appStreamPaymentOptInfoArr[0]['donation_amount_4'];
						
							$streamPaymentOptArray['donation_amount'] = $donation_amount_array;
							$streamPaymentOptArray['payment_mode'] = $appStreamPaymentOptInfoArr[0]['payment_mode'];
							$streamPaymentOptArray['stripe_API_pubkey_test'] = $appStreamPaymentOptInfoArr[0]['stripe_API_pubkey_test'];
							//$streamPaymentOptArray['stripe_API_token_test'] = $appStreamPaymentOptInfoArr[0]['stripe_API_token_test'];
							$streamPaymentOptArray['stripe_API_pubkey_live'] = $appStreamPaymentOptInfoArr[0]['stripe_API_pubkey_live'];
							//$streamPaymentOptArray['stripe_API_token_live'] = $appStreamPaymentOptInfoArr[0]['stripe_API_token_live'];
							$streamPaymentOptArray['paypal_clientid_test'] = $appStreamPaymentOptInfoArr[0]['paypal_clientid_test'];
							$streamPaymentOptArray['paypal_clientid_live'] = $appStreamPaymentOptInfoArr[0]['paypal_clientid_live'];
							$streamPaymentOptArray['active_status'] = $appStreamPaymentOptInfoArr[0]['active_status'];

						

						}
					}

					$appStreamDesignInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_stream_design', "streamId_FK = ".$streamIdPk, '', '', '', '');
					//print_r($appStreamDatesInfoArr);
					$streamDesignArray = array();
					//echo "st=".$streamType;
					if (!empty($appStreamDesignInfoArr) && is_array($appStreamDesignInfoArr))
					{					
						//echo "<br>cntROWS= ".	
						$noOfRowsDesign = count($appStreamDesignInfoArr);
						if ( $noOfRowsDesign > 0)
						{
							
							$streamDesignArray = $appStreamDesignInfoArr[0];
							$streamDescTemplate = $appStreamDesignInfoArr[0]['stream_description_template'];
							$imgSrc = "<img width='100%' src='".$appStreamDesignInfoArr[0]['description_image']."'>";

							$streamDescFull = str_replace("<DESCRIPTION>", $streamdescription, $streamDescTemplate);
							$streamDescFull = str_replace("<GENRE>", $streamGenre, $streamDescFull);
							$streamDescFull = str_replace("<CAST>", $streamCast, $streamDescFull);
							$streamDescFull = str_replace("<DIRECTOR>", $streamDirector, $streamDescFull);
							$streamDescFull = str_replace("<PRODUCER>", $streamProducer, $streamDescFull);
							$streamDescFull = str_replace("<EDITOR>", $streamEditor, $streamDescFull);
							$streamDescFull = str_replace("<WRITER>", $streamWriter, $streamDescFull);
							$streamDescFull = str_replace("<LANGUAGE>", $streamLanguage, $streamDescFull);
							$streamDescFull = str_replace("<AWARDS>", $streamAwards, $streamDescFull);
							$streamDescFull = str_replace("<IMG_URL>", $imgSrc, $streamDescFull);
							$streamDescFull = htmlentities($streamDescFull);
							
						}
					}
					

					$appDataArr['app']['all_streams']['latest_streams'][$j] = array(
						'app_code' => $appCode,
						'stream_guid' => $streamCode,
						'menu_guid' => $menuCode,
						'stream_title' => $streamTitle,
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
						'stream_sharethisid' => $streamShareThisId,
						'stream_googleanalyticsid' => $streamGoogleAnalyticsId,
						'stream_type' => $streamType,
						'stream_ticket_usage_type' => $streamTicketUsageType,
						'stream_start_datetime' => $streamStartDateTime,
						'stream_end_datetime' => $streamEndDateTime,
						'stream_timezone' => $streamTimezoneOffset,
						'stream_created_on' => $createdOn,
						'stream_updated_on' => $updatedOn,
						'stream_detail_page_url' => HTTP_PATH.'/feed/v1/stream_detail/'."{$streamCode}/$menuCode/",
						'stream_detail_page_url_v2' => HTTP_PATH.'/feed/v1/stream_detail_v2/'."{$appDomain}/$streamTitleQury/",
						'stream_multiple_dates' => $streamDateTimeArray,
						'stream_links' => $streamLinkArray,
						'stream_payment_options' => $streamPaymentOptArray,
						'stream_design' => $streamDesignArray,
						'stream_desc_full' => $streamDescFull,
					);
					
				}
			}
			else $appDataArr['app']['all_streams']['latest_streams'] = array();
		}
	}

	
	
}
else $appDataArr['app'] = array('status' => 0, 'msg' => "Stream or user  Id is missing."); 

header("Content-type:application/json; charset=UTF-8");
echo $dataStr = json_encode($appDataArr, JSON_PRETTY_PRINT);