<?php	
	$SUBTITLE = 'Change Password';
	include("includes/header.php");
?>
<!-- Start of content -->
<div class="app-body" >
<?php
	$userInfoArr = $objDBQuery->getRecord(0, array('userCode', 'password'), 'tbl_users', array('userCode' => $_SESSION['userDetails']['userCode']));
	$valParamArray['oldPassword'] = array("type" => "text", "msg" => "Old Password");
	$valParamArray['password'] = array("type" => "password", "msg" => "New Password", "min" => array("length" => 1, "msg" => "1 char."), "max" => array("length" => 32, "msg" => "32 char."),);
	$valParamArray['cpassword'] = array("type" => "cpassword", "msg" => "Confirm Password", "min" => array("length" => 1, "msg" => "1 char."), "max" => array("length" => 32, "msg" => "32 char."));
	$_SESSION['formValidation'] = $valParamArray;
?>
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
		<?php include_once('includes/profile-setting-menu.php'); ?>
			<!-- Start of tab content -->
			<div class="tab-content clear b-t">
			<div class="tab-pane active" id="user_profile">
				<div class="box-body">
						<form name="update_your_password-form" method="post" action="controller/admin-users-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>Old Password:</label>
							<div class="col-md-10">
								<input class="form-control" type="password" name='oldPassword' id='oldPassword' maxlength="32" value="">
								<span id='span_oldPassword' class='form_error'><?php showErrorMessage('oldPassword'); ?></span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>New Password:</label>
							<div class="col-md-10">
								<input class="form-control" type="password" name='password' id='password' maxlength="32" value="">
								<span id='span_password' class='form_error'><?php showErrorMessage('password'); ?></span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>Confirm Password:</label>
							<div class="col-md-10">
								<input class="form-control" type="password" name='cpassword' id='cpassword' maxlength="32" value="">
								<span id='span_cpassword' class='form_error'><?php showErrorMessage('cpassword'); ?></span>
							</div>							
						</div>
						<div class="form-groups row">
							<div class="col-md-offset-2 col-md-10 btn_space_web">
								<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane-o"></i>&nbsp;Submit</button>
								<button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-repeat"></i>&nbsp;Reset</button>
								<input type="hidden" name="postAction" value="changePassword">					
								<input type="hidden" name="userCode" value="<?php echo $userInfoArr[0]['userCode']?>">					
								<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End of content -->
<!-- Start of footer-->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->
</div>
<!-- Start of main content -->
