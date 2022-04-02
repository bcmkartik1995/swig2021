<?php	
	$SUBTITLE = 'Manage Master Codes';
	include("includes/header.php");

	$enkey = getValPostORGet('enkey', 'B');
	$arrDBFld = array('masterId_PK', 'masterCode','shortDescription','createdOn');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit Subcategory Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_subcategories', array('subCatCode' => $enkey));			
		$backBtnURL = "view-all-ticket-codes.php?".$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];
	}
	else
	{
		$btnTxt = 'Generate';
		$postAction = 'generateMasterCodes';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Generate New Master Codes for Live Event";
	
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		
		$backBtnURL = "view-all-master-codes.php?".$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];
	}

	$valParamArray = array(
		"shortDescription" => array(
			"type" => "text",
			"msg" => "Short Description",
		),		
	);

	$valParamArray['appCode'] = array("type" => "dropDown", "msg" => "App Name");
	$_SESSION['formValidation'] = $valParamArray;
	$accountType = $_SESSION['userDetails']['accountType'];
?>
<!-- Start of content -->
<div class="app-body" >
      <div class="padding">
		<!-- Start of box-->
		<?php include_once('includes/flash-msg.php'); ?>
		<div class="row main_headlinebox">
			<div class="col-md-12">
				<a href="<?php echo $backBtnURL?>" class="view-all back_icons"><i class="material-icons">keyboard_arrow_left</i>Back</a>
			</div>
		</div>
        <div class="box add_edit_form">
			<!-- Start of box header -->					
            <div class="box-header dker">
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Master Codes</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-menu-form" method="post" action="controller/master-code-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Short Description:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="shortDescription" id="shortDescription" value="">
							<span id='span_shortDescription' class='form_error'><?php showErrorMessage('shortDescription'); ?></span>
						</div>
					</div>
<?php
					$whereCls = ' 1';
					if ($accountType != 'S')
					{
						$appCodes = $_SESSION['userDetails']['appCodes'];
						$appCodesStr = str_replace(',' , "' OR appCode = '", $appCodes);
						$appCodesStr = "(appCode = '$appCodesStr')";
						$whereCls = $appCodesStr;					
					}
?>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>App Name:</label>
						<div class="col-sm-12 col-md-10">
<?php			
						$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', $whereCls, '', '', 'appName', 'ASC');	 
						makeDropDownFromDB('appCode', $appsInfoArr, 'appCode', 'appName', $appCode, "class='selectpicker' data-size='4'", '', '');
?>	
						<span id='span_appCode' class='form_error'><?php showErrorMessage('appCode'); ?></span>
						</div>
					</div>

					<!-- Start of button -->
					<div class="form-groups row">
						<div class="col-sm-12 col-md-offset-2 col-md-10 btn_space_gap">
							<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane-o"></i>&nbsp;<?php echo $btnTxt?></button>
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
