<?php
$SUBTITLE = "Login";
include( "includes/meta-header.php" );
$valParamArray = array( "username" =>
	array(
		"type" => "text",
		"msg" => "Username",
		"min" => array( "length" => 1, "msg" => "1 char." ),
		"max" => array( "length" => 20, "msg" => "20 char." ),
	),
	"password" =>
	array(
		"type" => "text",
		"msg" => "Password",
		"min" => array( "length" => 1, "msg" => "1 char." ),
		"max" => array( "length" => 32, "msg" => "32 char." ),
		//"regex" => array("pattern" => "[]", "msg" => "Maximum length should be 15 char."),
	)
);

$_SESSION[ 'formValidation' ] = $valParamArray;
$objDBQuery = new DBQuery();

if ( isset( $_SESSION[ 'userDetails' ] ) && $objDBQuery->getRecordCount( 0, 'tbl_users', array( 'username' => $_SESSION[ 'userDetails' ][ 'username' ], 'accountStatus' => 'A' ) ) ) {
	headerRedirect( 'dashboard.php' );
}

?>
<!-- End of meta header -->

<!-- Start of login page -->
<div class="login_banner">
<div class="center-block w-xxl w-auto-xs login-box login_box_width">
	<!-- Start of logo -->
	<div class="navbar logo-bottom-margin">
		<div class="center_box">
			<a class="navbar-brand navbar-img-large" href="index.php"><img src="images/logo.jpg" alt="<?php echo ADMIN_PANEL_TITLE?>" width='366' height='80'> 
			</a>
		</div>
	</div>
	<!-- End of logo -->
	<!-- Start of sign form -->
	<div class="p-a-md box-color r box-shadow-z1 text-color mt">
		<div class="sign-text">Admin Login Panel</div>
		<?php
		if ( @$_GET[ 'logout' ] == 1 ) {
			$_SESSION[ 'msgTrue' ] = 1;
			$_SESSION[ 'messageSession' ] = 'You have logged out successfully from the system.';
		} else if ( @$_GET[ 'logout' ] == 2 )$_SESSION[ 'messageSession' ] = 'Unauthorized access.';
		include_once( 'includes/flash-msg.php' );
		?>
		<div class="login-text" style='display:none;'>Log In</div>
		<form name="login-form" method="post" action="controller/admin-users-controller.php" onSubmit='return validation(1, <?php echo json_encode($valParamArray); ?>);'>
			<!-- Start of email -->
			<div class="md-form-group float-label">
				<input type="text" name="username" id="username" class="md-input" maxlength="20">
				<label for="username"><span class="cla_star">*</span>Username:</label>
				<span id='span_username' class='form_error'>
					<?php showErrorMessage('username'); ?>
				</span>
			</div>
			<!-- Start of password -->
			<div class="md-form-group float-label">
				<input type="password" name="password" id="password" class="md-input" maxlength="32">
				<label for="password"><span class="cla_star">*</span>Password:</label>
				<span id='span_password' class='form_error'>
					<?php showErrorMessage('password'); ?>
				</span>
				<input type="hidden" name="postAction" value="userLoginAuthentication">
				<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
			</div>
			<!-- Start of checkin -->
			<div class="m-gap" style='display:none;'>
				<label class="md-check"><input type="checkbox"><i class="primary"></i> Keep me signed in</label>
			</div>
			<!-- Start of sign in button -->
			<button type="submit" class="btn btn-primary btn-block p-x-md">Log In</button>
			<div class="border-line"></div>
		</form>
		<!-- Start of forgot password -->
		<div class="p-v-lg text-center">
			<div class="gap-top"><a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
			</div>
		</div>
	</div>
	<!-- End of sign form -->
</div>
<!-- End of login page -->

<div class="app-footer">
	<div class="s text-xs">
		<div class="copyright_center_login">
			<span class="copyright_text">&nbsp;<span class="footer-text"><?=COPY_RIGHT_INFO?></span></span>
		</div>
	</div>
</div>
</div>
<script src="js/jquery.min.js"></script>
<script src="<?php echo HTTP_PATH?>/js/form-validation.js"></script>
<script src="<?php echo HTTP_PATH?>/js/common.js"></script>
</body>
</html>