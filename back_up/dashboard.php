<?php
	$SUBTITLE = "Dashboard";
	include("includes/header.php")
?>
<!-- Start of left menu bar-->
<?php
	include("includes/left-menubar.php")
?>

<!-- Start of header -->

<!-- End of header -->

<!-- Start of content -->
<div class="app-body">
	
	<!-- Start of inner content-->
	<div class="padding p-b-0">
	<?php include_once('includes/flash-msg.php'); ?>
		<!-- Start of headline-->
		<div class="margin welcome_box">
			<h5 class="m-b-0 _300 text-center h5_line_height"><span class="text-primary"><?php echo DASHBOARD_WELCOME_TXT?></span></h5>
		</div>
		<!-- End of headline-->
		
	</div>
	<!-- End of inner content-->
</div>
<!-- End of content -->
<!-- Start of footer-->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->