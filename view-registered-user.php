<?php 
    $SUBTITLE = "View All Registered Users";
	include("includes/header.php");
    $query = "SELECT tbl_ticket_codes.ticketCode, tbl_ticket_codes.amount, tbl_ticket_codes.buyInformation, tbl_apps.appName,tbl_streams.streamTitle  FROM tbl_ticket_codes INNER JOIN tbl_apps on tbl_apps.appCode = tbl_ticket_codes.appCode_FK 
        INNER JOIN tbl_streams on tbl_streams.streamCode = tbl_ticket_codes.streamCode_FK where tbl_ticket_codes.buyerUserCode_FK = '".$_POST['userCode']."'";

    $resultArray = $objDBQuery->customsqlquery($query);

    $query2 = "SELECT name,email FROM tbl_registered_users where userCode = '".$_POST['userCode']."'";
    $UserArray = $objDBQuery->customsqlquery($query2);
    
?>

				  
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                
                <div class="row main_headlinebox">
					<div class="col-md-12">
						<h3 style="padding-top: 10px;">View Registered Users</h3>
						<a href="view-all-registered-users.php" class="view-all back_icons"><i class="material-icons">keyboard_arrow_left</i>Back</a>
					</div>
				</div>
            </div>

			<!-- End of header area-->
			
			<!-- Start of box body -->
            <div class="box-body">
				<form class="form-box" name="add-edit-user-form" method="post" action="">
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>Name:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="email" name="email" id="userEmail" maxlength="150" value="<?php echo $UserArray[0]['name'];?>" disabled>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 form-control-label"><span class="cla_star"></span>Email:</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control input_smallbox" type="text" name="username" id="userUserName" maxlength="150" value="<?php echo $UserArray[0]['email'];?>" disabled>
						</div>
					</div>
                </form>
            </div>
			<!-- End of box body-->


			<!-- Start of table responsive -->
            <div class="table-responsive">
				<table class="table table-striped blog_pages">
					<thead>
					<tr>
						<th>Ticket Code</th>												
						<th>Amount</th>												
						<th>Buy Information</th>			
						<th>App Name</th>			
						<th>Stream Title</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($resultArray) && !empty($resultArray))
					{
						$numOfRows = count($resultArray);
						for ($i = 0; $i < $numOfRows; $i++)
						{

?>
							 <tr>
								<td><span class="date_format"><?php echo $resultArray[$i]['ticketCode']?></span> </td>
								<td><span class="date_format"><?php echo $resultArray[$i]['amount']?></span> </td>
								<td><span class="date_format"><?php echo $resultArray[$i]['buyInformation']?></span> </td>
								<td><span class="date_format"><?php echo $resultArray[$i]['appName']?></span> </td>
								<td><span class="date_format"><?php echo $resultArray[$i]['streamTitle']?></span> </td>
							 </tr>
<?php
						}
				}
				else
				{
?>
						<tr><td colspan="10"><span class="no_rdc_found_msg">No Record Found</span></td></tr>				
<?php
				}
				include_once('includes/popup/popup-model-confirmation.php');
?>	
                    </tbody>
                </table>
			</div>
            <!-- End of table responsive --> 
		</div>
	</div>
</div>
<!-- End of content -->
<!-- Start of footer -->
<?php 
	include("includes/footer.php")
?>
