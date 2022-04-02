<?php
ini_set("display_errors", "0");
$SUBTITLE = "Login";
include_once('../swigappmanager.com/includes/classes/DBQuery.php');
//include_once('../swigappmanager.com/smtp_1.php'); 
include_once('../swigappmanager.com/includes/functions/common.php'); 
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

$errMsg = "";

//$_SESSION[ 'formValidation' ] = $valParamArray;
$objDBQuery = new DBQuery();
//print_r($_REQUEST);
//echo count($_REQUEST);
if(count($_REQUEST) > 0)
{
if($_REQUEST['username'] == "aphro_user" && $_REQUEST['password'] == "aphro#123")
{
	session_start();
	print_r($_REQUEST);
	//echo "exit";
	//session_re
	$_SESSION['aphro_login'] = "aphro_user";
	headerRedirect( 'purchase_list.php' );
}
else{
	//echo "sdfsdf";
	$errMsg = 1;
}
}
	


if ( isset( $_SESSION['aphro_login'] ) )
	{
	headerRedirect( 'purchase_list.php' );
}

?>
<!-- End of meta header -->

<!-- Start of login page -->
<script >
function login()
{
	//alert("sdf");
	document.login-form.submit();
}
</script>
<div class="login_banner">
<div class="center-block w-xxl w-auto-xs login-box login_box_width">
	<!-- Start of logo -->
	<div class="navbar logo-bottom-margin">
		<div class="center_box navbar-item  h5" style="font-size: 26px; width: 100%">
			APHRODISIAC ADVENTURES
		</div>
	</div>
	<!-- End of logo -->
	<!-- Start of sign form -->
	<div class="p-a-md box-color r box-shadow-z1 text-color mt">
		<div class="sign-text">Admin Login Panel</div>
		<div style="color: red; text-align: center">
		<?php
		 if ( $errMsg == "1")
			echo 'Unauthorized access.';
		
		?>
		</div>
		<div class="login-text" style='display:none;'>Log In</div> 
		<form name="login-form" method="post" onSubmit='javascript:login();' action="aphrodisiac_login.php">
			<!-- Start of email -->
			<div class="md-form-group float-label">
				<input type="text" name="username" id="username" class="md-input" maxlength="20">
				<label for="username"><span class="cla_star">*</span>Username:</label>
				<span id='span_username' class='form_error' >
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