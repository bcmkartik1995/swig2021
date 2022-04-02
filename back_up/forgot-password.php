<?php
	$SUBTITLE = "Fogot Password";
	include("includes/meta-header.php");
	$valParamArray = array("username" => array("type" => "text", "msg" => "Username"));

	$_SESSION['formValidation'] = $valParamArray;
	$objDBQuery = new DBQuery();
?>
<!-- End of meta header -->
<div class="login_banner">
<div class="center-block w-xxl w-auto-xs login-box login_box_width">
	<!-- Start of logo -->
	<div class="navbar logo-bottom-margin">
		<div class="center_box">
			<a class="navbar-brand navbar-img-large" href="index.php"><img src="images/logo.jpg" alt="<?php echo ADMIN_PANEL_TITLE?>" width='365' height='80'> 
			</a>
		</div>
	</div>
	<!-- End of logo -->
	<!-- Start of sign form -->
	<div class="p-a-md box-color r box-shadow-z1 text-color mt">
		
		<div class="sign-text">Fogot Password</div>
		<div class="login-text" style='display:none;'>Fogot Password</div>
		<?php
			include_once('includes/flash-msg.php'); 
		?>
		<form name="login-form" method="post" action="controller/admin-users-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
			<!-- Start of password -->
			<div class="md-form-group float-label">
				<input type="text" name="username" id="username" maxlength="20"  class="md-input" value="">
				<label><span class="cla_star">*</span>Username:</label>
				<span id='span_username' class='form_error'><?php showErrorMessage('username'); ?></span>
			</div>
			<!-- Start of sign in button -->
			<button type="submit" class="btn btn-primary btn-block mg-gap">Submit</button>
			<input type="hidden" name="postAction" value="forgotPassword">					
			<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
		</form>
		<div class="border-line"></div>
		<div class="p-v-lg text-center">
			<div class="gap-top"><a href="index.php" class="forgot-link">Back to Log In</a>
			</div>
		</div>
	</div>
	<!-- End of sign form -->
</div>
<div class="app-footer">
	<div class="s text-xs">
		<div class="copyright_center_login">
			<span class="copyright_text">&nbsp;<span class="footer-text"><?=COPY_RIGHT_INFO?></span></span>
		</div>
	</div>
</div>
</div>
<script src="<?php echo HTTP_PATH?>/js/jquery.min.js"></script>
<script src="<?php echo HTTP_PATH?>/js/form-validation.js"></script>
<script src="<?php echo HTTP_PATH?>/js/common.js"></script>
</body>