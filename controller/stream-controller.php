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
	    if($_POST['stream_type'] != '' && $_POST['stream_type'] == 'M'){
            $MultiEventdata = $_POST['MultiEvent'];
        } 

        if($_POST['payment_type'] != ''){
            $payment_type = $_POST['payment_type'];
        }

        if(isset($_POST['payment_option']) && $_POST['payment_option'] != ''){
            $payment_options = $_POST['payment_option'];
        } 

        unset($_POST['MultiEvent']);
        unset($_POST['total_chq']);
        unset($_POST['payment_type']);
        unset($_POST['payment_option']);

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
			if ($id = $objDBQuery->addRecord(0, $dataArr, $tblName))
			{				
				//echo "<pre>";print_r($dataArr);die; 
				if($dataArr['stream_type'] != '' && $dataArr['stream_type'] == 'M'){
	                $streamInfoArr = $objDBQuery->getRecord(0, array('streamId_PK'), $tblName, array('streamId_PK' => $id));
	                
	                $objDBQuery->dropRecord(0, 'tbl_stream_dates',array("streamId_FK" => $streamInfoArr[0]['streamId_PK']));
	                if($dataArr['eventStDateTime'] != '' && $dataArr['eventEndDateTime'] != ''){
	                	
	                	$tbl_sstream_dates = array("streamId_FK" => $streamInfoArr[0]['streamId_PK'], "eventStDateTime" => $dataArr['eventStDateTime'], "eventEndDateTime" => $dataArr['eventEndDateTime'], "timezoneOffset" => $dataArr['timezoneOffset'], "active_status" => "A");

	                    $objDBQuery->addRecord(0, $tbl_sstream_dates, 'tbl_stream_dates');
	                }
	                foreach($MultiEventdata as $evt){
	                	if($evt['eventStDateTime'] != '' && $evt['eventEndDateTime'] != ''){

	                		$tbl_stream_dates = array("streamId_FK" => $streamInfoArr[0]['streamId_PK'], "eventStDateTime" => $evt['eventStDateTime'], "eventEndDateTime" => $evt['eventEndDateTime'], "timezoneOffset" => $evt['timezoneOffset'], "active_status" => "A");

	                        $objDBQuery->addRecord(0, $tbl_stream_dates, 'tbl_stream_dates');
	                	}
	                	
	                }
	                
	                $dataArr['eventStDateTime'] = NULL;
	                $dataArr['eventEndDateTime'] = NULL;
				}

				if($payment_type != ''){
					$streamInfoArr = $objDBQuery->getRecord(0, array('streamId_PK','streamCode'), $tblName, array('streamId_PK' => $id));


	                $payment_options['payment_type'] = $payment_type;
	                $payment_options['streamId_FK'] = $id;
	                $payment_options['stream_guid'] = $streamInfoArr[0]['streamCode'];

	                $objDBQuery->addRecord(0, $payment_options, 'tbl_stream_payment_options');
				}



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
        if($_POST['stream_type'] != '' && $_POST['stream_type'] == 'M'){
            $MultiEventdata = $_POST['MultiEvent'];
        } 

        if($_POST['payment_type'] != ''){
            $payment_type = $_POST['payment_type'];
        }

        if(isset($_POST['payment_option']) && $_POST['payment_option'] != ''){
            $payment_options = $_POST['payment_option'];
        } 

        unset($_POST['MultiEvent']);
        unset($_POST['total_chq']);
        unset($_POST['payment_type']);
        unset($_POST['payment_option']);
		$_POST = trimFormValue(0, $_POST);
		$enkey = $_POST['enkey'];
		//$appName = $_POST['appName'];
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
			$infoArr = $objDBQuery->getRecord(0, array('streamImg', 'streamThumbnail','	streamId_PK'), $tblName, array($enckeyDBFldName => $enkey));
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


			if($dataArr['stream_type'] != '' && $dataArr['stream_type'] == 'M'){
                $streamInfoArr = $objDBQuery->getRecord(0, array('streamId_PK'), $tblName, array($enckeyDBFldName => $enkey));
                
                $objDBQuery->dropRecord(0, 'tbl_stream_dates',array("streamId_FK" => $streamInfoArr[0]['streamId_PK']));
                if($dataArr['eventStDateTime'] != '' && $dataArr['eventEndDateTime'] != ''){
                	
                	$tbl_sstream_dates = array("streamId_FK" => $streamInfoArr[0]['streamId_PK'], "eventStDateTime" => $dataArr['eventStDateTime'], "eventEndDateTime" => $dataArr['eventEndDateTime'], "timezoneOffset" => $dataArr['timezoneOffset'], "active_status" => "A");

                    $objDBQuery->addRecord(0, $tbl_sstream_dates, 'tbl_stream_dates');
                }
                foreach($MultiEventdata as $evt){
                	if($evt['eventStDateTime'] != '' && $evt['eventEndDateTime'] != ''){

                		$tbl_stream_dates = array("streamId_FK" => $streamInfoArr[0]['streamId_PK'], "eventStDateTime" => $evt['eventStDateTime'], "eventEndDateTime" => $evt['eventEndDateTime'], "timezoneOffset" => $evt['timezoneOffset'], "active_status" => "A");

                        $objDBQuery->addRecord(0, $tbl_stream_dates, 'tbl_stream_dates');
                	}
                	
                }
                
                $dataArr['eventStDateTime'] = NULL;
                $dataArr['eventEndDateTime'] = NULL;
			}

			if($payment_type != ''){
                $payment_options['payment_type'] = $payment_type;
                $PaymentinfoArr = $objDBQuery->getRecord(0, array('tbl_stream_payment_options_PK'), 'tbl_stream_payment_options', array('streamId_FK' => $infoArr[0]['streamId_PK']));

                if(isset($PaymentinfoArr) && !empty($PaymentinfoArr)){
                	
                    $objDBQuery->updateRecord(0, $payment_options, 'tbl_stream_payment_options', array('tbl_stream_payment_options_PK' => $PaymentinfoArr[0]['tbl_stream_payment_options_PK'])); 
                } else {
                	
                	$payment_options['streamId_FK'] = $infoArr[0]['streamId_PK'];
                	$payment_options['stream_guid'] = $enkey;

                	$objDBQuery->addRecord(0, $payment_options, 'tbl_stream_payment_options');
                }
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
