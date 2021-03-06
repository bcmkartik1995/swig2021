<?php	
	$SUBTITLE = 'Manage Apps';
	include("includes/header.php");

	$enkey = getValPostORGet('enkey', 'B');
	if ($_SESSION['userDetails']['accountType'] != 'S' && $enkey == '') checkPageAccessPermission('');

	$arrDBFld = array('appId_PK', 'appCode', 'appName', 'feedback_email', 'feedback_label', 'status', 'isLiveTVSubCatLblShow', 'isOnDemandSubCatLblShow', 'isDonatePerViewSubCatLblShow', 'isDonatePerViewLiveEventSubCatLblShow','app_logo', 'shareId', 'GoogleId', 'mail_smtp_host', 'mail_smtp_port', 'mail_smtp_ssl_enable', 'mail_smtp_username', 'mail_smtp_password');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit App Detail";
         
		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_apps', array('appCode' => $enkey));	
		if(isset($infoArr[0]['app_logo'])){
			$AppImg = $infoArr[0]['app_logo'];
		}else{
			$AppImg = '';
		} 
		$clientImg = $infoArr[0]['clientImg'];
		$backBtnURL = "view-all-apps.php?".$_SESSION['SESSION_QRY_STRING'];
		$idDisabled = 'readonly';
		if ($_SESSION['userDetails']['accountType'] != 'S')  $idDisabled = 'readonly';
	}
	else
	{
		$btnTxt = 'Submit';
		$postAction = 'addAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Add New App";
	    $idDisabled = '';
	    $AppImg = ''; 
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		$clientImg = '';
		$backBtnURL = "view-all-apps.php?".$_SESSION['SESSION_QRY_STRING'];
	}

	$valParamArray = array("appName" => 
					array(
						"type" => "text",
						"msg" => "App Name"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 255, "msg" => "255 char."),
					)
				);

	$_SESSION['formValidation'] = $valParamArray;
?>
<!-- Start of content -->
<div class="app-body" >
	<script src="<?php echo HTTP_PATH?>/js/tinymce/js/tinymce/tinymce.min.js"></script>
      <div class="padding">
		<!-- Start of box-->
		<?php include_once('includes/flash-msg.php'); ?>
        <div class="box add_edit_form">
			<!-- Start of box header -->					
            <div class="box-header dker">
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Apps</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-app-form" enctype="multipart/form-data" method="post" action="controller/app-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>App Name:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="appName" id="appName" maxlength="255" value="<?=$infoArr[0]['appName']?>" <?php echo $idDisabled?>>
							<span id='span_appName' class='form_error'><?php showErrorMessage('appName'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Feedback Label:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="feedback_label" id="feedback_label" maxlength="255" value="<?=$infoArr[0]['feedback_label']?>">
							<span id='span_feedback_label' class='form_error'><?php showErrorMessage('feedback_label'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Feedback Email:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="feedback_email" id="feedback_email" maxlength="255" value="<?=$infoArr[0]['feedback_email']?>">
							<span id='span_feedback_email' class='form_error'><?php showErrorMessage('feedback_email'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Status:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('status', array_keys($STATUS), array_values($STATUS), $infoArr[0]['status'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is LiveTV Subcategory Label Show?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isLiveTVSubCatLblShow', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isLiveTVSubCatLblShow'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is OnDemand Subcategory Label Show?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isOnDemandSubCatLblShow', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isOnDemandSubCatLblShow'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is DonatePerView Subcategory Label Show?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isDonatePerViewSubCatLblShow', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isDonatePerViewSubCatLblShow'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is DonatePerView Live Event Subcategory Label Show?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isDonatePerViewLiveEventSubCatLblShow', array_keys($ARR_IS_PREMIUM), array_values($ARR_IS_PREMIUM), $infoArr[0]['isDonatePerViewLiveEventSubCatLblShow'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
						</div>
					</div>


					<!-- Start of banner -->
					<div class="form-group row">
						<label for="photo_file" class="col-sm-12 col-md-2 form-control-label">App Logo:</label>
						<div class="col-sm-12 col-md-10">
							<!-- Start of photo box -->
<?php
							if ($AppImg != '')
							{
?>
								<div class="photo_box">
									<div class="banner_img_box">
										<img src="<?=HTTP_PATH.'/uploads/app_images/'.$AppImg?>" class="img-responsive" onerror="this.onerror=null;this.src='<?php echo HTTP_PATH?>/images/logo.jpg';">
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
								<input id="uploadBtn" type="file" name="app_logo" class="upload form-control">
							</div>
							<!-- End of upload file -->
							<small class="text-muted text-image"><i class="fa fa-question-circle-o"></i>&nbsp;Extensions: png, jpg, jpeg, gif</small>
						 </div>
					</div>
					<!-- End of banner -->

                    <div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>ShareId:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="shareId" id="usershareId" maxlength="150" value="<?=$infoArr[0]['shareId']?>">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>GoogleId:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="GoogleId" id="userGoogleId" maxlength="150" value="<?=$infoArr[0]['GoogleId']?>">
						</div>
					</div>

					<div style="font-weight: 900;font-size: 16px; margin: 5px 5px 12px 2px;">Smtp Details</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>Host:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="mail_smtp_host" id="mail_smtp_host" maxlength="150" value="<?=$infoArr[0]['mail_smtp_host']?>">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>Port:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="number" name="mail_smtp_port" id="mail_smtp_port" value="<?=$infoArr[0]['mail_smtp_port']?>">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is ssl enable?:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('mail_smtp_ssl_enable', array_keys($ARR_IS_SSL_ENABLE), array_values($ARR_IS_SSL_ENABLE), $infoArr[0]['mail_smtp_ssl_enable'], "class='selectpicker' data-size='4'", '', '', 'N');
?>	
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>Username:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="mail_smtp_username" id="mail_smtp_username" maxlength="150" value="<?=$infoArr[0]['mail_smtp_username']?>">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>Password:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="password" name="mail_smtp_password" id="mail_smtp_password" maxlength="150" value="<?=$infoArr[0]['mail_smtp_password']?>">
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
</div>
<!-- End of content -->
<!-- Start of footer-->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->
</div>
