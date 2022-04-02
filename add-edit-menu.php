<?php	
	$SUBTITLE = 'Manage Categories';
	include("includes/header.php");
	$appCode = getValPostORGet('appCode', 'B');
	checkPageAccessPermission($appCode);

	$enkey = getValPostORGet('enkey', 'B');
	$arrDBFld = array('menuId_PK', 'menuCode', 'menuName', 'appCode_FK', 'menuType', 'isDefaultMenu', 'status', 'menuOrder', 'createdOn', 'updatedOn');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit Category Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_menus', array('menuCode' => $enkey));	 
		$clientImg = $infoArr[0]['clientImg'];
		$backBtnURL = "view-all-menus.php?".$_SESSION['SESSION_QRY_STRING_FOR_MENU'];
	}
	else
	{
		$btnTxt = 'Submit';
		$postAction = 'addAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Add New Category";
	
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		$clientImg = '';
		$backBtnURL = "view-all-menus.php?".$_SESSION['SESSION_QRY_STRING_FOR_MENU'];
	}

	$valParamArray = array("menuName" => 
					array(
						"type" => "text",
						"msg" => "Name"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 255, "msg" => "255 char."),
					),
					"menuType" =>array("type" => "dropDown", "msg" => "Type"),
				);

	$_SESSION['formValidation'] = $valParamArray;
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
				  <li><i class="material-icons">keyboard_arrow_right</i><a href="view-all-menus.php?<?php echo $backBtnURL?>"><span>Categories List</span></a></li>				 
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
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Categories</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-menu-form" enctype="multipart/form-data" method="post" action="controller/menu-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Name:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="menuName" id="menuName" maxlength="255" value="<?=$infoArr[0]['menuName']?>">
							<span id='span_menuName' class='form_error'><?php showErrorMessage('menuName'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Type:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('menuType', array_keys($ARR_MENU_TYPE), array_values($ARR_MENU_TYPE), $infoArr[0]['menuType'], "class='selectpicker' data-size='4'", '', '', 'N');
?>	
						<span id='span_menuType' class='form_error'><?php showErrorMessage('menuType'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label">Is Set Default:</label>
						<div class="col-sm-12 col-md-10">
<?php			
							makeDropDown('isDefaultMenu', array_keys($DEFAULT_SELECTION_MENU), array_values($DEFAULT_SELECTION_MENU), $infoArr[0]['isDefaultMenu'], "class='selectpicker' data-size='4'", '', '', 'Y');
?>	
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
							<input type="hidden" name="appCode_FK" value="<?=$appCode?>">	
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
