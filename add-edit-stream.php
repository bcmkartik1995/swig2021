<?php	
	$SUBTITLE = 'Manage Streams';
	include("includes/header.php");
	$appCode = getValPostORGet('appCode', 'B');
	$menuCode = getValPostORGet('menuCode', 'B');
	checkPageAccessPermission($appCode);
	checkPageAccessPermission($menuCode);

	$enkey = getValPostORGet('enkey', 'B');
	$arrDBFld = array('streamId_PK','streamCode', 'menuCode_FK', 'appCode_FK', 'streamTitle', 'streamUrl', 'streamImg', 'streamThumbnail', 'streamdescription', 'staring', 'streamTrailerUrl', 'streamDuration', 'directedBy', 'writtenBy', 'producedBy', 'genre', 'language', 'awards', 'rating', 'review', 'isPremium', 'amount', 'subscriptionExpiredFaq', 'subscriptionExpired', 'streamFeedUrl', 'stream_type',
	'streamEntryBy', 'status', 'isStreamFeatured', 'subCatCode_FK', 'dontePerViewSelected', 'isDontePerView', 'timezoneOffset', 'eventEndDateTime', 'eventStDateTime', 'linearStreamPlayingMethod', 'linearStreamDaiKey', 'isShowOnlyUpcomingSection');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit Stream Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_streams', array('streamCode' => $enkey));	

		//echo "<pre>";print_r($infoArr);die; 
		$stream_id = $infoArr[0]['streamId_PK'];
		$stream_type = $infoArr[0]['stream_type'];
		$streamImg = $infoArr[0]['streamImg'];
		$streamThumbnail = $infoArr[0]['streamThumbnail'];
		$backBtnURL = "view-all-streams.php?".$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];

		if($stream_type != '' && $stream_type == 'M'){
			$multiEnvtDBFld = array('stream_date_PK', 'streamId_FK', 'eventStDateTime', 'eventEndDateTime', 'timezoneOffset');
			$MultiEvtinfoArr = $objDBQuery->getRecord(0, $multiEnvtDBFld, 'tbl_stream_dates', array('streamId_FK' => $stream_id));

			//echo "<pre>";print_r($MultiEvtinfoArr);die;
		}
	}
	else
	{
		$btnTxt = 'Submit';
		$postAction = 'addAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Add New Stream";
	
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		$streamImg = '';
		$streamThumbnail = '';
		$backBtnURL = "view-all-streams.php?".$_SESSION['SESSION_QRY_STRING_FOR_STREAM'];

		if ($infoArr[0]['subscriptionExpiredFaq'] == '') $infoArr[0]['subscriptionExpiredFaq'] = 'hour';
		
		if ($infoArr[0]['subscriptionExpired'] == '') $infoArr[0]['subscriptionExpired'] = 24;
		if ($infoArr[0]['dontePerViewSelected'] == '') $infoArr[0]['dontePerViewSelected'] = 1;
		if ($infoArr[0]['timezoneOffset'] == '') $infoArr[0]['timezoneOffset'] = 'America/Los_Angeles';
		
	}

	
	$valParamArray = array(
		"streamTitle" => array(
			"type" => "text",
			"msg" => "Stream Title"	,
			"min" => array("length" => 1, "msg" => "1 char."),
			"max" => array("length" => 255, "msg" => "255 char."),
		),
		"streamUrl" => array(
			"type" => "text",
			"msg" => "Stream URL"	,
			"min" => array("length" => 1, "msg" => "1 char."),
			"max" => array("length" => 500, "msg" => "500 char."),
		),
	);
	
$valParamArray = array("status" =>array("type" => "dropDown", "msg" => "Status"),
"subCatCode_FK" =>array("type" => "dropDown", "msg" => "Subcategory")

);
//$valParamArray[] = array("subCatCode_FK" =>array("type" => "dropDown", "msg" => "Subcategory"));
$_SESSION['formValidation'] = $valParamArray;
	$streamEntryBy = $infoArr[0]['streamEntryBy'];
?>
<!-- Start of content -->
<div class="app-body" >
	<script src="<?php echo HTTP_PATH?>/js/tinymce/js/tinymce/tinymce.min.js"></script>
      <div class="padding">
		<!-- Start of box-->
		<?php include_once('includes/flash-msg.php'); ?>
		<div class="row main_headlinebox">
			<div class="col-md-6">
				<ul class="breadcrumb_box">
				  <li><a href="view-all-apps.php?<?php echo $_SESSION['SESSION_QRY_STRING']?>">Apps List</a></li>
				  <li><i class="material-icons">keyboard_arrow_right</i><a href="view-all-menus.php?<?php echo $_SESSION['SESSION_QRY_STRING_FOR_MENU']?>"><span>Categories List</span></a></li>
				  <li><i class="material-icons">keyboard_arrow_right</i><a href="<?php echo $backBtnURL?>"><span>Streams List</span></a></li>
				  <li class="active"><i class="material-icons">keyboard_arrow_right</i><span><?php echo $pageTitleTxt?></span></li>
				</ul>
			</div>
			<div class="col-md-6">
				<a href="<?php echo $backBtnURL?>" class="view-all back_icons"><i class="material-icons">keyboard_arrow_left</i>Back</a>
			</div>
		</div>
        <div class="box add_edit_form">
			<!-- Start of box header -->					
            <div class="box-header dker">
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Streams</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-stream-form" enctype="multipart/form-data" method="post" action="controller/stream-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Stream Add from:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('streamEntryBy', array_keys($ARR_STREAM_ENTRY_BY), array_values($ARR_STREAM_ENTRY_BY), $streamEntryBy, "class='selectpicker' onChange='javascript:manageStreamEntry(this.value)' data-size='4'", '', '', 'Y');
?>	
						<span id='span_streamEntryBy' class='form_error'><?php showErrorMessage('streamEntryBy'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Subcategory:</label>
						<div class="col-sm-12 col-md-10">
<?php			
						$subcateInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_subcategories', array('status' => 'A', 'appCode_FK' => $appCode), '', '', 'subCatName', 'ASC');	 
						makeDropDownFromDB('subCatCode_FK', $subcateInfoArr, 'subCatCode', 'subCatName', $infoArr[0]['subCatCode_FK'], "class='selectpicker' data-size='6'", '', '');
?>	
						<span id='span_subCatCode_FK' class='form_error'><?php showErrorMessage('subCatCode_FK'); ?></span>
						</div>
					</div>
					<div id="byFeedUrlEntry" style='display:none;'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Feed URL:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="streamFeedUrl" id="span_streamFeedUrl" maxlength="255" value="<?=$infoArr[0]['streamFeedUrl']?>">
							<span id='span_streamFeedUrl' class='form_error'><?php showErrorMessage('streamFeedUrl'); ?></span>
						</div>
					</div>
					</div>
					<div id="byManualEntry">
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Stream Title:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="streamTitle" id="streamTitle" maxlength="255" value="<?=$infoArr[0]['streamTitle']?>">
							<span id='span_streamTitle' class='form_error'><?php showErrorMessage('streamTitle'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Stream URL:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="streamUrl" id="streamUrl" maxlength="255" value="<?=$infoArr[0]['streamUrl']?>" onKeyup="javascript:updateVidUrl(this.value, 'Y');">
							<span id='span_streamUrl' class='form_error'><?php showErrorMessage('streamUrl'); ?></span>
							<!-- Start of Stream url link -->
							<div class="stream_urlink" id='stream_urlinkId'>
								<a href="#" data-toggle="modal" data-target="#stream_popupbox" ui-toggle-class="bounce" ui-target="#animate" id='streamPreviewId' onclick="javascript:setStreamURL4AddStream()"><span >Stream Preview</span></a>
							</div>
							<!-- End of Stream url link -->
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Linear Stream DAI Key:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="linearStreamDaiKey" id="linearStreamDaiKey" maxlength="255" value="<?=$infoArr[0]['linearStreamDaiKey']?>">
							<span id='span_linearStreamDaiKey' class='form_error'><?php showErrorMessage('linearStreamDaiKey'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Linear Playing Method:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('linearStreamPlayingMethod', array_keys($LINEAR_PLAYING_METHOD), array_values($LINEAR_PLAYING_METHOD), $infoArr[0]['linearStreamPlayingMethod'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
						<span id='span_linearStreamPlayingMethod' class='form_error'><?php showErrorMessage('linearStreamPlayingMethod'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Stream Trailer URL:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="streamTrailerUrl" id="streamTrailerUrl" maxlength="255" value="<?=$infoArr[0]['streamTrailerUrl']?>">
							<span id='span_streamTrailerUrl' class='form_error'><?php showErrorMessage('streamTrailerUrl'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Stream Duration in Mins:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="number" name="streamDuration" id="streamDuration" maxlength="6" value="<?=$infoArr[0]['streamDuration']?>">
							<span id='span_streamDuration' class='form_error'><?php showErrorMessage('streamDuration'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Stream Description:</label>
						<div class="col-sm-12 col-md-10">
							<textarea class="form-control" type="text" name="streamdescription" id="streamdescription"><?=$infoArr[0]['streamdescription']?></textarea>
							<span id='span_streamdescription' class='form_error'><?php showErrorMessage('streamUrl'); ?></span>
						</div>
					</div>
					<div class="form-group row" style='display:none;' >
						<label class="col-sm-12 col-md-2 form-control-label">Is Featured?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isStreamFeatured', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isStreamFeatured'], "class='selectpicker' data-size='6'", '', '', 'Y');
?>	
						</div>
					</div>
					<!-- Start of banner -->
					<div class="form-group row">
						<label for="photo_file" class="col-sm-12 col-md-2 form-control-label">Stream Poster:</label>
						<div class="col-sm-12 col-md-10">
							<!-- Start of photo box -->
<?php
							if ($streamImg != '')
							{
?>
								<div class="photo_box">
									<div class="banner_img_box">
										<img src="<?=HTTP_PATH.'/uploads/stream_images/'.$streamImg?>" class="img-responsive" onerror="this.onerror=null;this.src='<?php echo HTTP_PATH?>/images/default_stream_poster.php';">
									</div>
								</div>
<?php
							}
?>
							<!-- End of photo box -->
							<div id="undo" class="col-sm-4 p-a-xs" style="display:none"><a> Undo Delete</a></div>
							<!-- Start of upload file -->
							<input id="photo_delete" name="photo_delete" type="hidden" value="0" class="has-value">
							<input id="uploadFile" placeholder="Choose File" class="form-control" type="text" id="photo_filess">
							<div class="fileUpload btn btn-sm btn-success">
								<span><i class="fa fa-upload"></i> Upload</span>
								<input id="uploadBtn" type="file" name="streamImg" class="upload form-control">
							</div>
							<!-- End of upload file -->
							<small class="text-muted text-image"><i class="fa fa-question-circle-o"></i>&nbsp;Extensions: png, jpg, jpeg, gif</small>
						 </div>
					</div>
					<!-- End of banner -->
					<!-- Start of banner -->
					<div class="form-group row">
						<label for="photo_file" class="col-sm-12 col-md-2 form-control-label">Detail Page Poster:</label>
						<div class="col-sm-12 col-md-10">
							<!-- Start of photo box -->
<?php
							if ($streamThumbnail != '')
							{
?>
								<div class="photo_box">
									<div class="banner_img_box details_poster">
										<img src="<?=HTTP_PATH.'/uploads/stream_images/'.$streamThumbnail?>" class="img-responsive" onerror="this.onerror=null;this.src='<?php echo HTTP_PATH?>/images/default_stream_thumbnail.png';">
									</div>
								</div>
<?php
							}
?>
							<!-- End of photo box -->
							<div id="undo" class="col-sm-4 p-a-xs" style="display:none"><a> Undo Delete</a></div>
							<!-- Start of upload file -->
							<input id="photo_delete" name="photo_delete" type="hidden" value="0" class="has-value">
							<input id="uploadFile2" placeholder="Choose File" class="form-control" type="text" id="photo_filess">
							<div class="fileUpload btn btn-sm btn-success">
								<span><i class="fa fa-upload"></i> Upload</span>
								<input id="uploadBtn2" type="file" name="streamThumbnail" class="upload form-control">
							</div>
							<!-- End of upload file -->
							<small class="text-muted text-image"><i class="fa fa-question-circle-o"></i>&nbsp;Extensions: png, jpg, jpeg, gif</small>
						 </div>
					</div>
					<!-- End of banner -->
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Staring:</label>
						<div class="col-sm-12 col-md-10">
							<textarea class="form-control" type="text" name="staring" id="staring"><?=$infoArr[0]['staring']?></textarea>
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. Johnny Depp, Robert De Niro, Denzel Washington</small>
							<span id='span_staring' class='form_error'><?php showErrorMessage('staring'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Directed by:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="directedBy" id="directedBy" maxlength="255" value="<?=$infoArr[0]['directedBy']?>">
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. Martin Scorsese,  Christopher Nolan, Francis Ford Coppola </small>
							<span id='span_directedBy' class='form_error'><?php showErrorMessage('directedBy'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Written by:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="writtenBy" id="writtenBy" maxlength="255" value="<?=$infoArr[0]['writtenBy']?>">
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. Patrick Ness, Lauren Oliver</small>
							<span id='span_writtenBy' class='form_error'><?php showErrorMessage('writtenBy'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Produced by:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="producedBy" id="producedBy" maxlength="255" value="<?=$infoArr[0]['producedBy']?>">
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. Michael Bay, Christopher Nolan </small>
							<span id='span_producedBy' class='form_error'><?php showErrorMessage('producedBy'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Genre:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="genre" id="genre" maxlength="255" value="<?=$infoArr[0]['genre']?>">
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. Adventure, Comedy</small>
							<span id='span_genre' class='form_error'><?php showErrorMessage('genre'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Language:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="language" id="language" maxlength="255" value="<?=$infoArr[0]['language']?>">
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. English, Hindi</small>
							<span id='span_language' class='form_error'><?php showErrorMessage('language'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Awards:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="awards" id="awards" maxlength="255" value="<?=$infoArr[0]['awards']?>">
							<small class="text-muted text-image text-muted-top"><i class="fa fa-question-circle-o"></i>&nbsp;e.g. Award1, Award2</small>
							<span id='span_awards' class='form_error'><?php showErrorMessage('awards'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Rating:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('rating', array_keys($ARR_RATING), array_values($ARR_RATING), $infoArr[0]['rating'], "class='selectpicker' data-size='6'", '', '', 'N');
?>	
						</div>
					</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Review:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('review', array_keys($ARR_REVIEW), array_values($ARR_REVIEW), $infoArr[0]['review'], "class='selectpicker' data-size='6'", '', '', 'N');
?>	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is Show Only Upcoming Section?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isShowOnlyUpcomingSection', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isShowOnlyUpcomingSection'], "class='selectpicker' data-size='6'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is DonatePerView?</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isDontePerView', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isDontePerView'], "class='selectpicker' data-size='6'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row" style='display:none;'>
						<label class="col-sm-12 col-md-2 form-control-label">Donate Per View Options</label>
						<div class="col-sm-12 col-md-10">
<?php
							foreach ($ARR_DONATE_PER_VIEW_OPTN as $key => $optn)
							{
?>
								<label class='ui-check'>
									<input type="radio" name="dontePerViewSelected" value="<?php echo $key?>" <?php if ($infoArr[0]['dontePerViewSelected'] == $key) echo "checked";?>><i></i> <span for="<?php echo $optn?>"><?php echo $optn?></span>
								</label>
<?php
							}
?>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Stream Type</label>
						<div class="col-sm-3 col-md-2">
<?php			
							makeDropDown('stream_type', array_keys($ARR_STREAM_TYPE), array_values($ARR_STREAM_TYPE), $infoArr[0]['stream_type'], "class='selectpicker' onChange='manageStreamType()' data-size='4'", '', '', 'Y');
?>	
						</div>
						<?php if($infoArr[0]['stream_type'] != '' && $infoArr[0]['stream_type'] == 'M'){ ?>
							<div class="col-sm-3 col-md-2" id="addLiveEvent" style="display: block;">
	                            <button type="button" class="btn btn-sm btn-primary" onclick="AddMoreEventSec()">Add</button>
							</div>
						<?php } else { ?>
							<div class="col-sm-3 col-md-2" id="addLiveEvent" style="display: none;">
	                            <button type="button" class="btn btn-sm btn-primary" onclick="AddMoreEventSec()">Add</button>
							</div>
						<?php } ?>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Live Event Timing:</label>
                        

                        <?php $eventcount = 0;?>

						<?php if($stream_type != '' && $stream_type == 'M') { ?>
							<div id="moreEventSelection">
							<?php foreach ($MultiEvtinfoArr as $eventinfo) { ?>
								<div class="col-sm-12 col-md-10" id="EventSecc<?php echo $eventcount; ?>" style="float: right;margin-top: 10px;">
									<div>
										<div class="time_box">
										  	<div class="time_text">Start Time:</div>
										  	<div class="col_date">								 
											<div class='input-group date' id='datetimepicker'>
												<input type='text' class="form-control" value="<?=$eventinfo['eventStDateTime']?>" name="MultiEvent[<?php echo $eventcount; ?>][eventStDateTime]"/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar">
												</span>
												</span>
											</div>
											</div>
										</div>
										<div class="time_box">
											<div class="time_text">End Time:</div>
											<div class="col_date">				
												<div class='input-group date' id='datetimepicker1'>
													<input type='text' class="form-control" value="<?=$eventinfo['eventEndDateTime']?>" name="MultiEvent[<?php echo $eventcount; ?>][eventEndDateTime]"/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar">
													</span>
													</span>
												</div>
											</div>
										</div>
										<div class='time_box'>
											<div class="time_text time_region">Region:</div>
											
				                               <?php			
				                               if($eventcount == 0){
				                               	   $dropdownID = 'MEtimezoneOffset';
				                               } else {
				                                   $dropdownID = 'MEtimezoneOffset'.$eventcount;	
				                               }
											   makeDropDownCustom($dropdownID, 'MultiEvent['.$eventcount.'][timezoneOffset]', array_values(timezone_identifiers_list()), array_values(timezone_identifiers_list()), $eventinfo['timezoneOffset'], "class='selectpicker' data-size='8' data-live-search='true'", '', '', 'Y');
				                                ?>		
										</div>
								    </div>
								</div>
							<?php $eventcount = $eventcount+1;?>
							<?php } ?>
							</div>
							<?php $divstyle = 'style="display:none"'; ?>
						<?php } else { ?>
							<?php $divstyle = 'style="display:block"'; ?>
						<?php } ?>

						<div class="col-sm-12 col-md-10" id="EventSecc" <?php echo $divstyle;?>>
							<div>
								<div class="time_box">
								  	<div class="time_text">Start Time:</div>
								  	<div class="col_date">								 
									<div class='input-group date' id='datetimepicker'>
										<input type='text' class="form-control" value="<?=$infoArr[0]['eventStDateTime']?>" name="eventStDateTime"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
										</span>
									</div>
									</div>
								</div>
								<div class="time_box">
									<div class="time_text">End Time:</div>
									<div class="col_date">				
										<div class='input-group date' id='datetimepicker1'>
											<input type='text' class="form-control" value="<?=$infoArr[0]['eventEndDateTime']?>" name="eventEndDateTime"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar">
											</span>
											</span>
										</div>
									</div>
								</div>
								<div class='time_box'>
									<div class="time_text time_region">Region:</div>
									
		                               <?php			
									   makeDropDown('timezoneOffset', array_values(timezone_identifiers_list()), array_values(timezone_identifiers_list()), $infoArr[0]['timezoneOffset'], "class='selectpicker' data-size='8' data-live-search='true'", '', '', 'Y');
		                                ?>		
								</div>
						    </div>
						</div>

						<div class="col-sm-12 col-md-12" id="MoreEvent">
							
						</div>
						<input type="hidden" value="<?php echo $eventcount;?>" id="total_chq" name="total_chq">
					</div>
					<div class="form-group row" style='display:none;'>
						<label class="col-sm-12 col-md-2 form-control-label" >Is Premium?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isPremium', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isPremium'], "class='selectpicker' data-size='6'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row" style='display:none;'>
						<label class="col-sm-12 col-md-2 form-control-label">Amount:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="amount" id="amount" maxlength="13" value="<?=$infoArr[0]['amount']?>">
							<span id='span_amount' class='form_error'><?php showErrorMessage('amount'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Validity After Rent:</label>
						<div class="col-sm-12 col-md-10">
						<input class="form-control" style='width: 70px; float: left; margin-right: 10px;' type="text" name="subscriptionExpired" id="subscriptionExpired" maxlength="9" value="<?=$infoArr[0]['subscriptionExpired']?>">
<?php			
							makeDropDown('subscriptionExpiredFaq', array_keys($ARR_SUBSCRIPTION_EXPIRED), array_values($ARR_SUBSCRIPTION_EXPIRED), $infoArr[0]['subscriptionExpiredFaq'], "class='selectpicker' data-size='6' style='width: 100px'", '', '', 'Y');
?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Status:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('status', array_keys($STATUS), array_values($STATUS), $infoArr[0]['status'], "class='selectpicker' data-size='4'", '', '', 'N');
?>	
						<span id='span_status' class='form_error'><?php showErrorMessage('status'); ?></span>
						</div>
					</div>
					<!-- Start of button -->
					<div class="form-groups row">
						<div class="col-sm-12 col-md-offset-2 col-md-10 btn_space_gap">
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane-o"></i>&nbsp;Submit</button>
<?php
							if ($enkey)
							{
?>
								<button type="button" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i>&nbsp;<a href="<?php echo $backBtnURL?>" class="back_btn_link">Back</a></button>
<?php
							}
							else 
							{
?>
								<button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-repeat"></i>&nbsp;Reset</button>
<?php
							}
?>
							<input type="hidden" name="appCode_FK" value="<?=$appCode?>">	
							<input type="hidden" name="menuCode_FK" value="<?=$menuCode?>">	
							<input type="hidden" name="postAction" value="<?=$postAction?>">	
							<input type="hidden" name="enkey" value="<?=$enkey?>">	
							<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
						</div>
					</div>
                </form>
            </div>
			<!-- End of box body-->
        </div>
		<!-- End of box-->
    </div>
<?php 
include_once('video-player.php');
?>
</div>
<!-- End of content -->
<!-- Start of footer-->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>	
	<?php $a=0;?>
manageStreamEntry('<?=$streamEntryBy?>');
function manageStreamEntry(curVal)
{
	if (curVal == 'F')
	{
		$('#byManualEntry').hide();
		$('#byFeedUrlEntry').show();
	}
	else
	{
		$('#byManualEntry').show();
		$('#byFeedUrlEntry').hide();
	}
}

function manageStreamType()
{
	var stream_type = document.getElementById('stream_type');
	var add_button = document.getElementById('addLiveEvent');
	var more_evt_sec = document.getElementById('moreEventSelection');
	var single_evt_sec = document.getElementById('EventSecc');
	var add_more = document.getElementById('MoreEvent');
	if(stream_type.value == 'M'){
        add_button.style.display = 'block'; 
        if(add_more.style.display === 'none'){
			add_more.style.display = 'block';
		} 
	} else {
		if(add_button.style.display !== 'none'){
			add_button.style.display = 'none';
		}
		if (more_evt_sec != null) {
		    more_evt_sec.style.display = 'none';
		}
		if (add_more.style.display != null) {
		    add_more.style.display = 'none';
		}
		single_evt_sec.style.display = 'block';
	}
}

function AddMoreEventSec(){
  var new_chq_no = parseInt($('#total_chq').val());
  var new_end_no = new_chq_no + 1;

  var new_input = '<div class="col-sm-12 col-md-10" style="margin-top: 10px;padding-left: 3px; float:right;"><div class="time_box"> <div class="time_text">Start Time:</div> <div class="col_date"> <div class="input-group date" id="datetimepicker'+ new_chq_no +'"><input type="text" class="form-control" value="" name="MultiEvent['+new_chq_no+'][eventStDateTime]"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>  </div> </div></div> <div class="time_box"><div class="time_text">End Time:</div><div class="col_date"><div class="input-group date" id="datetimepicker'+ new_end_no +'"><input type="text" class="form-control" value="" name="MultiEvent['+new_chq_no+'][eventEndDateTime]"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="time_box" style="display:flex;"><div class="time_text time_region">Region:</div><div id="AMEtimeoffsetsec'+ new_chq_no +'"></div></div></div>';

  //$('#timezoneOffset').find('option').clone().appendTo('#timezoneOffset'+new_chq_no);

  $('#MoreEvent').append(new_input);
  $('select#timezoneOffset').clone().attr({ id: '#timezoneOffset'+new_chq_no, name: 'MultiEvent['+new_chq_no+'][timezoneOffset]'}).appendTo('#AMEtimeoffsetsec'+ new_chq_no);

  //$('#timezoneOffset'+new_chq_no).attr('name', 'MultiEvent['+new_chq_no+'][timezoneOffset]');
  

  new_chq_no = new_chq_no+1;
  new_end_no = new_end_no+1
  $('#total_chq').val(new_chq_no);
}
</script>

