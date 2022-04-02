<?php	
	$SUBTITLE = 'Manage Apps';
	include("includes/header.php");

	$enkey = getValPostORGet('enkey', 'B');
	if ($_SESSION['userDetails']['accountType'] != 'S' && $enkey == '') checkPageAccessPermission('');

	$arrDBFld = array('appId_PK', 'appCode', 'appName', 'feedback_email', 'feedback_label', 'status', 'isLiveTVSubCatLblShow', 'isOnDemandSubCatLblShow', 'isDonatePerViewSubCatLblShow', 'isDonatePerViewLiveEventSubCatLblShow');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit App Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_apps', array('appCode' => $enkey));	 
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
