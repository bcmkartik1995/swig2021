<?php
ini_set("display_errors", "0");
include_once('../swigappmanager.com/includes/classes/DBQuery.php');
//include_once('../swigappmanager.com/smtp_1.php'); 
include_once('../swigappmanager.com/includes/functions/common.php'); 
session_start();
$CUR_PAGE_NAME = basename($_SERVER['SCRIPT_NAME']);
//namespace PHPMailer\PHPMailer;
ini_set("display_errors", "0");

cors();

if (isset($_SESSION['aphro_login']))
{

} else {
	headerRedirect( 'aphrodisiac_login.php' );
}

$objDBQuery = new DBQuery();

if($_REQUEST['keyword'] != "")
{
	$searchKeyword = $_REQUEST['keyword'];
	$whereClause = " ( cust_name like '%".$searchKeyword."%' or cust_email like '%".$searchKeyword."%' ) and type_of_inquiry = 'S'";
}
else {
	$whereClause = "  type_of_inquiry = 'S'";
}

$purchaseInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_bookpurchase_request', $whereClause,'','', 'purchase_date', 'DESC');	 
//$excludeMenuOnPageArr = array( 'sign-up.php' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="keyword" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SwigIt | Aphrodisiac Adventures | Purchase List</title>
    <link rel="icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap-colorpicker.css">
</head>
<style>
.table-striped tr td {
    vertical-align: top !important;
}
.tdStyle {
	border-bottom: #dddddd solid 1px !important;
	border-right: #dddddd solid 1px !important;
}
</style>

<script >
function navigateTo(pageLink)
{	
	//alert("sdf");
	window.location.href = pageLink;
}
</script>
<body>

<!-- Start of main content -->
<div class="app-content box-shadow-z0" role="main">
	<?php include_once("aphro_header.php");?>	
	
<!-- Start of content -->
<div class="app-body" style="padding-top: 7rem !important;">
	<div class="padding">
		
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                <h3>View All Book Purchases</h3>

            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			<div class="search-panel">
				<form class="form-inline" method="post" action='<?php echo $CUR_PAGE_NAME?>'>
					<div class="form-group">
						<label for="keyword">Keyword</label>
						<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $_REQUEST['keyword']?>">
					</div>
					<!--div class="form-group">
						<label for="search">Status</label>
<?php
					//	makeDropDown('status', array_keys($STATUS), array_values($STATUS), $status, "class='selectpicker' data-size='4'", '', '');
?>	
					</div-->
					<span class="button_box">
						<button type="submit" class="btn btn-sm blue_btn"><i class="fa fa-search "></i>&nbsp;Search</button>
						<button type="reset" class="btn btn-sm yellow_btn" 
						onClick="javascript:navigateTo('<?php echo $CUR_PAGE_NAME?>');"><i class="fa fa-repeat "></i>&nbsp;Reset</button>
					</span>
				</form>
			</div>
			
			<!-- End of search panel -->
			<!-- Start of table responsive -->
            <div class="table-responsive">
				<table class="table table-striped testimonial_table" style="border: gray 1px solid">
					
					<tbody>
<?php
					if (is_array($purchaseInfoArr) && !empty($purchaseInfoArr))
					{
						$numOfRows = count($purchaseInfoArr);
?>
					<thead>
					<tr>
						<th colspan="9">Total no. of Records: <?php echo $numOfRows; ?>
						</th>
					</tr>
					<tr>
						<th  style="text-align: left">Sr. No.</th>
						<th style="text-align: left">Customer Name</th>													
						<th  style="text-align: left">Email </th>	
						<th  style="text-align: left">Phone</th>														
						<th style="text-align: left">Shipping Address</th>
						<th  style="text-align: left">Paid Amount</th>
						<th  style="text-align: left">Payment Provider</th>
						<th  style="text-align: left">Payment Reference code</th>													
																	
						<th  style="text-align: left">Purchase date</th>
						
					</tr>
					</thead>
<?php						
						
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
						
?>
							 <tr>
								<td class="tdStyle"><?=$i+1?>.</td>
								<td  class="tdStyle"><?php echo $purchaseInfoArr[$i]['cust_name']?></td>
								<td  class="tdStyle"><?php echo $purchaseInfoArr[$i]['cust_email']?></td>
								<td  class="tdStyle"><?php echo "(".$purchaseInfoArr[$i]['cust_country_code'].") ".$purchaseInfoArr[$i]['cust_phone']?></td>
								<td class="tdStyle"><?php echo $purchaseInfoArr[$i]['cust_address1']."<br>".
								$purchaseInfoArr[$i]['cust_address2']."<br>".
								$purchaseInfoArr[$i]['cust_city']."<br>".
								$purchaseInfoArr[$i]['cust_state']."<br>".
								$purchaseInfoArr[$i]['cust_country']." - ".
								$purchaseInfoArr[$i]['cust_zip'] ?>
								</td>
								<td class="tdStyle"><?php echo $purchaseInfoArr[$i]['book_amount']?></td>
								<td class="tdStyle"><?php echo $purchaseInfoArr[$i]['payment_reference']?></td>
								<td class="tdStyle"><?php echo $purchaseInfoArr[$i]['payment_provider']?></td>
								
								<td class="tdStyle"><?php echo $purchaseInfoArr[$i]['purchase_date']?></td>
								
								
								
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
				//include_once('includes/popup/popup-model-confirmation.php');
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
	//include("includes/footer.php")
?>
<!-- End of footer-->
</div></body>

</html>
<!-- Start of main content -->