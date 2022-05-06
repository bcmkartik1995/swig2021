<?php	
	$SUBTITLE = 'Manage users';
	include("includes/header.php");
	$accountType = $_SESSION['userDetails']['accountType'];

	$enkey = getValPostORGet('enkey', 'B');
	$arrDBFld = array('userId_PK', 'userCode', 'username', 'password', 'appCodes', 'email', 'fname', 'lname', 'phone', 'accountType', 'accountStatus');	
	if ($enkey)
	{
		$btnTxt = 'Submit';
		$postAction = 'updateAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Edit user Detail";

		$infoArr = $objDBQuery->getRecord(0, $arrDBFld, 'tbl_users', array('userId_PK' => $enkey));		
		$backBtnURL = "users.php?".$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];
	}
	else
	{
		$btnTxt = 'Submit';
		$postAction = 'addAction';
		$awsomeIcon = "fa fa-plus";
		$pageTitleTxt = "Add New user";
	
		foreach ($arrDBFld AS $dbFldName)
		{
			$infoArr[0][$dbFldName] = @$_SESSION['session_'.$dbFldName];
			unset($_SESSION['session_'.$dbFldName]);
		}
		
		$backBtnURL = "users.php?".$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'];
	}

	$valParamArray = array(
		"email" => array(
			"type" => "text",
			"msg" => "Email"	,
			"min" => array("length" => 1, "msg" => "1 char."),
			"max" => array("length" => 150, "msg" => "150 char."),
		),
		"username" => array(
			"type" => "text",
			"msg" => "Email"	,
			"min" => array("length" => 1, "msg" => "1 char."),
			"max" => array("length" => 150, "msg" => "150 char."),
		),
		"password" => array(
			"type" => "text",
			"msg" => "Password"	,
			"min" => array("length" => 1, "msg" => "1 char.")
		),
	);
	$valParamArray['appCode_FK'] = array("type" => "dropDown", "msg" => "App Name");

	$valParamArray = array("status" =>array("type" => "dropDown", "msg" => "Status"));
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
                <h3><?=$pageTitleTxt?></h3><a href="<?php echo $backBtnURL?>" class="view-all"><i class="material-icons">&#xe02f;</i>View All Users</a>
            </div>
			<!-- End of box header -->
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-user-form" method="post" action="controller/users-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Email:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="email" name="email" id="userEmail" maxlength="150" value="<?=$infoArr[0]['email']?>">
							<span id='span_subCatName' class='form_error'><?php showErrorMessage('email'); ?></span>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Username:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="username" id="userUserName" maxlength="150" value="<?=$infoArr[0]['username']?>">
							<span id='span_subCatName' class='form_error'><?php showErrorMessage('username'); ?></span>
						</div>
					</div>

					<div class="form-group row">
						<?php if ($enkey){ ?>
							<label class="col-sm-12 col-md-2 form-control-label">Password:</label>
						<?php } else { ?>
							<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Password:</label>
						<?php } ?>	
						
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="password" name="password" id="userPassword">
							<span id='span_subCatName' class='form_error'><?php showErrorMessage('password'); ?></span>
						</div>
					</div>

					<?php if ($accountType == 'S'){?>
                        <?php 
						    if ($enkey)
								{
						?>
						            <div class="form-group row">
										<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Account Type:</label>
										<div class="col-sm-12 col-md-10">
										    <?php if($infoArr[0]['accountType'] == 'S'){?>
												<input type="radio" name="accountType" value="S" style="-webkit-appearance: auto;" checked>
											<?php } else { ?>
												<input type="radio" name="accountType" value="S" style="-webkit-appearance: auto;">
											<?php } ?>
											<label for="sa">Super Admin</label><br>
											<?php if($infoArr[0]['accountType'] == 'A'){?>
												<input type="radio" name="accountType" value="A" style="-webkit-appearance: auto;" checked>
											<?php } else { ?>
												<input type="radio" name="accountType" value="A" style="-webkit-appearance: auto;">
											<?php } ?>
											
											<label for="admin">Admin</label><br>
										</div>
									</div>   
						<?php			
								}
								else
								{
						?>
									<div class="form-group row">
										<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star">*</span>Account Type:</label>
										<div class="col-sm-12 col-md-10">
											<input type="radio" name="accountType" value="S" style="-webkit-appearance: auto;" checked>
											<label for="sa">Super Admin</label><br>
											<input type="radio" name="accountType" value="A" style="-webkit-appearance: auto;">
											<label for="admin">Admin</label><br>
										</div>
									</div>
						<?php
								}
						?> 
					<?php } ?>

					

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
							<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>App Name:</label>
							<div class="col-sm-12 col-md-10">
					<?php			
							$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', $whereCls, '', '', 'appName', 'ASC');	 
							makeDropDownFromDB('appCodes', $appsInfoArr, 'appCode', 'appName', $infoArr[0]['appCodes'], "class='selectpicker' data-size='4'", '', '');
					?>	
							<span id='span_appCode_FK' class='form_error'><?php showErrorMessage('appCode_FK'); ?></span>
							</div>
						</div>	


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 form-control-label">Status:</label>
							<div class="col-sm-12 col-md-10">
					<?php			
								makeDropDown('accountStatus', array_keys($STATUS), array_values($STATUS), $infoArr[0]['accountStatus'], "class='selectpicker' data-size='4'", '', '', 'Y');
					?>	
						</div>
					</div>


					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>ShareId:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="shareId" id="usershareId" maxlength="150" value="">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>GoogleId:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="GoogleId" id="userGoogleId" maxlength="150" value="">
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
							<input type="hidden" name="userCode" value="<?=$infoArr[0]['userCode']?>">	
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
