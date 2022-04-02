<?php
ini_set("display_errors", "0");
include_once('includes/classes/DBQuery.php');
include_once('includes/functions/common.php'); 
session_start();
$CUR_PAGE_NAME = basename($_SERVER['SCRIPT_NAME']);
//namespace PHPMailer\PHPMailer;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php

if (isset($_GET['pageno'])) {
	$pageno = $_GET['pageno'];
} else {
	$pageno = 1;
}
$no_of_records_per_page = 20;
$offset = ($pageno-1) * $no_of_records_per_page;

cors();

if (isset($_SESSION['aphro_login']))
{

} else {
	headerRedirect( 'aphrodisiac_login.php' );
}

$objDBQuery = new DBQuery();
	

$whereClause = "  appCode_FK = 'aphrobook'";

$total_rows = $objDBQuery->getRecordCount(0, 'tbl_track_users', $whereClause, 'ipAddress');
$purchaseInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_track_users', $whereClause, $offset, $no_of_records_per_page, 'addedOn', 'DESC');	 
$numOfRows = count($purchaseInfoArr);

if($numOfRows < $no_of_records_per_page)
	$toRecord = $total_rows;
else
	$toRecord = $offset + $no_of_records_per_page;
//$total_rows = $numOfRows;
$total_pages = ceil($total_rows / $no_of_records_per_page);

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
    <title>SwigIt | Aphrodisiac Adventures | User Analytics</title>
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
<div class="app-body" style="padding-top: 9rem !important;">
	<div class="padding">
		
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                <h3>View User Analytics</h3>

            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			
			
			<!-- End of search panel -->
			<!-- Start of table responsive -->
            <div class="table-responsive">
			<ul class="pagination">
    <li><a href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>
				<table class="table table-striped testimonial_table" style="border: gray 1px solid">
					
					<tbody>
<?php
					if (is_array($purchaseInfoArr) && !empty($purchaseInfoArr))
					{
						
?>
					<thead>
					<tr>
						<th colspan="9">Total no. of Records: <?php echo $total_rows; ?>
						</th>
					</tr>
					<tr>
						<th colspan="9">Current Records: From <?php echo $offset." to ". $toRecord; ?>
						</th>
					</tr>
					<tr>
						<th  style="text-align: left">Sr. No.</th>
						<th style="text-align: left">ipAddress</th>													
						<th  style="text-align: left">Accessed On </th>	
						<th  style="text-align: left">City, Country, Continent</th>														
						 
					</tr>
					</thead>
<?php						
						
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
						
?>
							 <tr>
								<td class="tdStyle"><?=$offset+$i+1?>.</td>
								<td  class="tdStyle"><?php echo $purchaseInfoArr[$i]['ipAddress']?></td>
								<td  class="tdStyle"><?php echo $purchaseInfoArr[$i]['addedOn']?></td>
								<td  class="tdStyle"><?php echo getGeoData($purchaseInfoArr[$i]['ipAddress'])?></td>
								
							
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
				<ul class="pagination">
    <li><a href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>
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

<?php

function getGeoData($ipaddress)
{
	$url = 'http://ipwhois.app/json';
	$collection_name = $ipaddress;
	$request_url = $url . '/' . $collection_name;
	$curl = curl_init($request_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($curl);
	curl_close($curl);
	$manage = json_decode($response, true);

	//echo $response . PHP_EOL;
	//print_r($response);
	return $manage["city"].", ". $manage["country"].", " .$manage['continent'];
}

?>