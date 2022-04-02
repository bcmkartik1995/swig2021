<?php	
	$SUBTITLE = 'User Profile';
	include("includes/header.php");
?>
<!-- End of header -->

<!-- Start of content -->
<div class="app-body" >
<?php
	$userInfoArr = $objDBQuery->getRecord(0, array('userCode', 'username', 'email', 'fname', 'lname', 'phone'), 'tbl_users', array('userCode' => $_SESSION['userDetails']['userCode']));

	$valParamArray = array("fname" => 
					array(
						"type" => "text",
						"msg" => "First Name"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 50, "msg" => "50 char."),
					),
					"lname" =>array(
						"type" => "text",
						"msg" => "Last Name"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 50, "msg" => "50 char."),
					),
					"email" =>array("type" => "email", "msg" => "Email Id"),
					"phone" =>array(
						"type" => "text",
						"msg" => "Phone"	,
						"min" => array("length" => 1, "msg" => "1 char."),
						"max" => array("length" => 20, "msg" => "20 char."),
					),
				);

	$_SESSION['formValidation'] = $valParamArray;
?>
	
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
		<?php include_once('includes/profile-setting-menu.php'); ?>
			<!-- Start of tab content -->
			<div class="tab-content clear b-t">
			<div class="tab-pane active" id="user_profile">
				<div class="box-body">
						<form name="update_your_profule-form" method="post" action="controller/admin-users-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>Username:</label>
							<div class="col-md-10">
								<input class="form-control" type="text" disabled value="<?php echo $userInfoArr[0]['username']?>">							
							</div>							
						</div>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>First Name:</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name='fname' id='fname' maxlength="50" value="<?php echo $userInfoArr[0]['fname']?>">
								<span id='span_fname' class='form_error'><?php showErrorMessage('fname'); ?></span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>Last Name:</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name='lname' id='lname' maxlength="50" value="<?php echo $userInfoArr[0]['lname']?>">
								<span id='span_lname' class='form_error'><?php showErrorMessage('lname'); ?></span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>Email Id:</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name='email' id='email' maxlength="100" value="<?php echo $userInfoArr[0]['email']?>">
								<span id='span_email' class='form_error'><?php showErrorMessage('email'); ?></span>
							</div>							
						</div>
						<div class="form-group row">
							<label for="title_ar" class="col-md-2 form-control-label"><span class="cla_star">*</span>Phone:</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name='phone' id='phone' maxlength="20" value="<?php echo $userInfoArr[0]['phone']?>">
								<span id='span_phone' class='form_error'><?php showErrorMessage('phone'); ?></span>
							</div>							
						</div>
						<div class="form-groups row">
							<div class="col-md-offset-2 col-md-10 btn_space_web">
								<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane-o"></i>&nbsp;Submit</button>
								<button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-repeat"></i>&nbsp;Reset</button>
								<input type="hidden" name="postAction" value="updateYourProfile">					
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
<!-- Start of footer -->
<?php 
	include("includes/footer.php");
?>
<!-- End of footer-->
</div>
<!-- Start of main content -->