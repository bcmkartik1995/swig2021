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
					<li class='active' <?php if (in_array($CUR_PAGE_NAME, array('view-all-registered-users.php', 'add-edit-app.php', 'view-all-apps.php', 'view-all-menus.php', 'add-edit-menu.php', 'view-all-streams.php', 'add-edit-stream.php'))) { echo "class='active'";} ?>>
						<a>
							<span class="nav-caret"><i class="fa fa-caret-down"></i></span>
							<span class="nav-icon"><i class="material-icons">screen_rotation</i></span>
							<span class="nav-text">Manage App</span>
						</a>
						<ul class="nav-sub">
							<li <?php if ($CUR_PAGE_NAME == 'view-all-apps.php') { echo "class='active'";} ?>><a href="view-all-apps.php" onclick="location.href='view-all-apps.php'"><span class="nav-text"><i class="material-icons">&#xe02f;</i>&nbsp;View App</span></a></li>

							<li <?php if ($CUR_PAGE_NAME == 'view-all-registered-users.php') { echo "class='active'";} ?>><a href="view-all-registered-users.php" onclick="location.href='view-all-registered-users.php'"><span class="nav-text"><i class="material-icons">contacts</i>&nbsp;View Registered Users</span></a></li>
		
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>
<!-- End of content -->
