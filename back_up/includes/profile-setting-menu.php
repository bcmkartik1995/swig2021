 <div class="box-header dker"><h3><i class="fa fa-gear"></i>&nbsp;Profile Settings</h3></a></div>
<div class="box nav-active-border b-info">
	<!-- Start of user list link -->
	<ul class="nav nav-md">
<li class="nav-item inline_table">
    <a class="nav-link <?php if (basename($_SERVER['SCRIPT_NAME']) == 'profile.php') echo 'active';?>" href="profile.php" data-toggle="tab" data-target="#user_profile" onclick="location.href='profile.php'">
	<span class="text-md"><i class="material-icons">&#xe7FD;</i>User Profile</span>
    </a>
</li>
<li class="nav-item inline_table">
    <a class="nav-link <?php if (basename($_SERVER['SCRIPT_NAME']) == 'change-password.php') echo 'active';?>" href="change-password.php" data-toggle="tab" data-target="#forgot_password" onclick="location.href='change-password.php'">
	<span class="text-md"><i class="material-icons">&#xe898;</i>Change Password</span>
    </a>
</li> 
</ul>
<!-- End of user list link -->