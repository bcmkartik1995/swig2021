<?php
include( "includes/meta-header.php" );
if ($_SESSION['userDetails']['accountType'] == 'S') include( "includes/left-menubar.php" );
else include( "left-menubar-admin.php" );

// SECURE PAGE NAME
$securePageArray = array( 'index.php' );

//if (!in_array(basename($_SERVER['SCRIPT_NAME']), $securePageArray)) {
//if (isset($_SESSION['userDetails'])) {
//$_SESSION['messageSession'] = 'Access not allowed';
//if (!in_array(basename($_SERVER['SCRIPT_NAME']), array('index.php'))) headerRedirect('./');
//}
//}

// CHECK LOGIN USER'S REQUEST IS VALID OR NOT
if (isset( $_SESSION['userDetails']))
{
	$whereClause = array(
		'username' => $_SESSION[ 'userDetails']['username'],
		'password' => $_SESSION[ 'userDetails']['password'],
		'accountType' => $_SESSION[ 'userDetails']['accountType'],
		'accountStatus' => $_SESSION[ 'userDetails']['accountStatus']
	);

	// CHECK USER'S DETAILS ARE VALID OR NOT
	if ( !$objDBQuery->getRecordCount( 0, 'tbl_users', $whereClause ) )headerRedirect( 'logout.php' );
} else headerRedirect( 'index.php' );


// HERE ADMIN ACCESSES SCRIPT NAME
$adminUserAccessArr = array('view-all-apps.php', 'add-edit-app.php', 'view-all-menus.php', 'profile.php', 'change-password.php', 'add-edit-menu.php', 'view-all-streams.php', 'add-edit-stream.php', 'view-all-registered-users.php', 'generate-ticket-codes.php', 'view-all-ticket-codes.php', 'view-all-sub-categories.php', 'add-edit-sub-category.php', 'view-all-master-codes.php', 'generate-master-code.php');

if (isset($_SESSION['userDetails']['accountType']) && $_SESSION['userDetails']['accountType'] == 'A') {
	if (!in_array(basename($_SERVER['SCRIPT_NAME']), $adminUserAccessArr)) headerRedirect('view-all-apps.php');
}

$welcomeName = '';
if ( isset( $_SESSION['userDetails'] ) )$welcomeName = ucfirst( $_SESSION['userDetails' ]['fname']);

//$excludeMenuOnPageArr = array( 'sign-up.php' );
?>
<!-- Start of main content -->
<div class="app-content box-shadow-z0" role="main">
	<!-- Start of header menu -->
	<div class="app-header white box-shadow navbar-md">
		<div class="navbar">
			<!-- Open side - Naviation on mobile -->
			<a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
				<i class="fa fa-bars" aria-hidden="true"></i>
			</a>
		

			<!-- Page title - Bind to $state's title -->
			<div class="navbar-item pull-left h5" id="pageTitle"></div>

			<!-- Start of navbar right -->
				<ul class="nav navbar-nav pull-right">
				<li class="nav-item p-t p-b" style='display:none;'>
					<a class="btn btn-sm btn-primary btn_site" href="<?php echo HTTP_PATH?>" onclick="window.open('<?php echo HTTP_PATH?>');" target="_new" title="Website">
                    	<i class="material-icons">&#xe895;</i> Website
					</a>
				</li>
				<li class="nav-item p-t p-b"><a class="btn admin_color">Hi <span class="black_admin" style='color:#023575'><?php echo $_SESSION['userDetails']['fname']?>!</span></a></li>
				<li class="nav-item dropdown">
					<a class="nav-link clear" data-toggle="dropdown" href="index.php">
						<span class="avatar w-32">
							<img src="images/profile.jpg" alt="admin">
						</span>
					</a>
				
					<div class="dropdown-menu pull-right dropdown-menu-scale">
						<a class="dropdown-item" href="profile.php"><span>Profile Settings</span></a>
						<a class="dropdown-item" href="logout.php">Logout</a>
					</div>
				</li>
			</ul>
			<!-- End of navbar right -->
		</div>
	</div>
	<!-- End of header menu -->