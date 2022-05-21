<!-- Start of left menu bar-->
<div id="aside" class="app-aside modal fade md nav-expand">
	<div class="left navside white dk">
		<div class="navbar navbar-md no-radius">
			<!-- brand -->
			<a class="navbar-brand" href="index.php">
				<img src="images/admin_logo.png" alt="SWIG">
			</a>
			<!-- / brand -->
		</div>
		<div flex class="hide-scroll">
			<nav class="scroll nav-active-info">
				<ul class="nav">
					<li <?php if (in_array($CUR_PAGE_NAME, array('dashboard.php'))) { echo "class='active1'";} ?>>
						<a href="dashboard.php" onclick="location.href='dashboard.php'">
							<span class="nav-icon"><i class="fa fa-id-card"></i></span>
							<span class="nav-text">Dashboard</span>
						</a>
					</li>
					<li <?php if (in_array($CUR_PAGE_NAME, array('users.php'))) { echo "class='custom_active'";} ?>>
						<a href="users.php" onclick="location.href='users.php'">
							<span class="nav-icon"><i class="material-icons">contacts</i></span>
							<span class="nav-text">Users</span>
						</a>
					</li>
					<li class='active' <?php if (in_array($CUR_PAGE_NAME, array('view-all-sub-categories.php', 'view-all-registered-users.php', 'add-edit-app.php', 'view-all-apps.php', 'view-all-menus.php', 'add-edit-menu.php', 'view-all-streams.php', 'add-edit-stream.php', 'view-all-ticket-codes.php', ''))) { echo "class='active'";} ?>>
						<a>
							<span class="nav-caret"><i class="fa fa-caret-down"></i></span>
							<span class="nav-icon"><i class="material-icons">screen_rotation</i></span>
							<span class="nav-text">Manage Apps</span>
						</a>
						<ul class="nav-sub">
							<li <?php if ($CUR_PAGE_NAME == 'add-edit-app.php') { echo "class='active'";} ?>><a href="add-edit-app.php" 
							onclick="location.href='add-edit-app.php'"><span class="nav-text"><i class="material-icons">&#xe02e;</i>Add New App</span></a></li>	
							<li <?php if ($CUR_PAGE_NAME == 'view-all-apps.php') { echo "class='active'";} ?>><a href="view-all-apps.php" onclick="location.href='view-all-apps.php'"><span class="nav-text"><i class="material-icons">&#xe02f;</i>&nbsp;View All Apps</span></a></li>

							<li <?php if ($CUR_PAGE_NAME == 'view-all-sub-categories.php') { echo "class='active'";} ?>><a href="view-all-sub-categories.php" onclick="location.href='view-all-sub-categories.php'"><span class="nav-text"><i class="material-icons">device_hub</i>&nbsp;View Subcategories</span></a></li>
							<li <?php if ($CUR_PAGE_NAME == 'view-all-ticket-codes.php') { echo "class='active'";} ?>><a href="view-all-ticket-codes.php" onclick="location.href='view-all-ticket-codes.php'"><span class="nav-text"><i class="material-icons">pages</i>&nbsp;View All Ticket Codes</span></a></li>
							<li <?php if ($CUR_PAGE_NAME == 'view-all-master-codes.php') { echo "class='active'";} ?>><a href="view-all-master-codes.php" onclick="location.href='view-all-master-codes.php'"><span class="nav-text"><i class="material-icons">pages</i>&nbsp;View All Master Codes</span></a></li>
							<li <?php if ($CUR_PAGE_NAME == 'view-all-registered-users.php') { echo "class='active'";} ?>><a href="view-all-registered-users.php" onclick="location.href='view-all-registered-users.php'"><span class="nav-text"><i class="material-icons">contacts</i>&nbsp;View Registered Users</span></a></li>		
						</ul>
					</li>
					<li <?php if (in_array($CUR_PAGE_NAME, array('view-all-users.php', 'add-edit-user.php'))) { echo "class='active'";} ?> style='display:none;'>
						<a>
							<span class="nav-caret"><i class="fa fa-caret-down"></i></span>
							<span class="nav-icon"><i class="material-icons">screen_rotation</i></span>
							<span class="nav-text">Manage Admin Users</span>
						</a>
						<ul class="nav-sub">
							<li <?php if ($CUR_PAGE_NAME == 'add-edit-user.php') { echo "class='active'";} ?>><a href="add-edit-user.php" onclick="location.href='add-edit-user.php'"><span class="nav-text"><i class="material-icons">&#xe02e;</i>Add New Admin User</span></a></li>
							<li <?php if ($CUR_PAGE_NAME == 'view-all-apps.php') { echo "class='active'";} ?>><a href="view-all-users.php" onclick="location.href='view-all-users.php'"><span class="nav-text"><i class="material-icons">&#xe02f;</i>&nbsp;View All Admin Users</span></a></li>		
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>
<!-- End of content -->
