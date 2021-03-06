<?php	
	$SUBTITLE = 'Manage Categories';
	include("includes/header.php");
	$accountType = $_SESSION['userDetails']['accountType'];

	$enkey = getValPostORGet('enkey', 'B');
	$arrDBFld = array('subCatId_PK', 'subCatCode', 'subCatName', 'subCatOrder', 'status', 'createdOn', 'updatedOn', 'appCode_FK');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit Subcategory Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_subcategories', array('subCatCode' => $enkey));			
		$backBtnURL = "view-all-sub-categories.php?".$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];
	}
	else
	{
		$btnTxt = 'Submit';
		$postAction = 'addAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Add New Subcategory";
	
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		
		$backBtnURL = "view-all-sub-categories.php?".$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];
	}

	$valParamArray = array(
		"subCatName" => array(
			"type" => "text",
			"msg" => "Name"	,
			"min" => array("length" => 1, "msg" => "1 char."),
			"max" => array("length" => 150, "msg" => "150 char."),
		),
	);
	$valParamArray['appCode_FK'] = array("type" => "dropDown", "msg" => "App Name");

	//$valParamArray = array("status" =>array("type" => "dropDown", "msg" => "Status"));
	$_SESSION['formValidation'] = $valParamArray;
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
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Subcategories</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-menu-form" method="post" action="controller/sub-category-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Name:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="subCatName" id="subCatName" maxlength="150" value="<?=$infoArr[0]['subCatName']?>">
							<span id='span_subCatName' class='form_error'><?php showErrorMessage('subCatName'); ?></span>
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
						makeDropDownFromDB('appCode_FK', $appsInfoArr, 'appCode', 'appName', $infoArr[0]['appCode_FK'], "class='selectpicker' data-size='4'", '', '');
?>	
						<span id='span_appCode_FK' class='form_error'><?php showErrorMessage('appCode_FK'); ?></span>
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
						<label class="col-sm-12 col-md-2 form-control-label">Order:</label>
						<div class="col-sm-12 col-md-1">
							<input class="form-control" type="numeric" name="subCatOrder" id="subCatOrder" maxlength="6" value="<?=$infoArr[0]['subCatOrder']?>">
							<span id='span_subCatOrder' class='form_error'><?php showErrorMessage('subCatOrder'); ?></span>
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
								<a href="<?php echo $backBtnURL?>" class="back_btn_link"><button type="button" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left"></i>&nbsp;Back</button></a>
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
