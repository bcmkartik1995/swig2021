<?php	
	$SUBTITLE = 'Manage Social Links';
	include("includes/header.php");
	$enkey = getValPostORGet('enkey', 'B');
	$arrDBFld = array('socialLinkId_PK', 'socialLinkCode', 'socialLinkName', 'socialLinkUrl', 'paddingForNormalSrn', 'hoverColor', 'awesomeFontClsName', 'status', 'rank');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit Social Link Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_social_links', array('socialLinkCode' => $enkey));	 
		$clientImg = $infoArr[0]['clientImg'];
		$backBtnURL = "view-all-social-links.php?".$_SESSION['SESSION_QRY_STRING'];
	}
	else
	{
		$btnTxt = 'Submit';
		$postAction = 'addAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Add New Social Link";
	
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		$clientImg = '';
		$backBtnURL = "view-all-social-links.php?".$_SESSION['SESSION_QRY_STRING'];
	}

	$valParamArray = array("socialLinkName" => 
					array(
						"type" => "text",
						"msg" => "Name"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 150, "msg" => "150 char."),
					),
					"socialLinkUrl" =>array(
						"type" => "text",
						"msg" => "URL"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 255, "msg" => "255 char."),
					),
					"paddingForNormalSrn" =>array(
						"type" => "text",
						"msg" => "Padding"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 50, "msg" => "50 char."),
					),
					"hoverColor" =>array(
						"type" => "text",
						"msg" => "Hover Color"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 10, "msg" => "10 char."),
					),
					"awesomeFontClsName" =>array(
						"type" => "text",
						"msg" => "Awesome Font Class Name"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 50, "msg" => "50 char."),
					),
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
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Social Links</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-page-form" enctype="multipart/form-data" method="post" action="controller/social-link-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Name:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="socialLinkName" id="socialLinkName" maxlength="150" value="<?=$infoArr[0]['socialLinkName']?>">
							<span id='span_socialLinkName' class='form_error'><?php showErrorMessage('socialLinkName'); ?></span>
						</div>
					</div>
					 <div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>URL:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" type="text" name="socialLinkUrl" id="socialLinkUrl" maxlength="255" value="<?=$infoArr[0]['socialLinkUrl']?>">
							<span id='span_socialLinkUrl' class='form_error'><?php showErrorMessage('socialLinkUrl'); ?></span>
						</div>
					</div>
					 <div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Hover Color:</label>
						<div class="col-sm-12 col-md-10">
							<!--<input class="form-control" type="text" name="hoverColor" id="hoverColor" maxlength="10" value="<?=$infoArr[0]['hoverColor']?>">-->
							<div id="colorpicker_box" class="input-group colorpicker-component">
								<input type="text" class="form-control" name="hoverColor" id="hoverColor" maxlength="10" value="<?=$infoArr[0]['hoverColor']?>"/>
								<span class="input-group-addon"><i></i></span>
							</div>
							<span id='span_hoverColor' class='form_error'><?php showErrorMessage('hoverColor'); ?></span>
						</div>
					</div>
					 <div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Padding:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control padding_inputbox" type="text" name="paddingForNormalSrn" id="paddingForNormalSrn" maxlength="50" value="<?=$infoArr[0]['paddingForNormalSrn']?>"><small class="text-muted text-image"><i class="fa fa-question-circle-o"></i>&nbsp;[ Padding: Top Right Bottom Left ]</small>
							<span id='span_paddingForNormalSrn' class='form_error'><?php showErrorMessage('paddingForNormalSrn'); ?></span>
						</div>
					</div>
					 <div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Awesome Font Class Name:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control padding_inputbox" type="text" name="awesomeFontClsName" id="awesomeFontClsName" maxlength="50" value="<?=$infoArr[0]['awesomeFontClsName']?>">
							<span id='span_awesomeFontClsName' class='form_error'><?php showErrorMessage('awesomeFontClsName'); ?></span>
						</div>
					</div>
					 <div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Order:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control order_areabox" type="text" name="rank" id="rank" maxlength="4" value="<?=$infoArr[0]['rank']?>">
							<span id='span_rank' class='form_error'><?php showErrorMessage('rank'); ?></span>
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
