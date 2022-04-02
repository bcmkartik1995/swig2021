<?php	
	$SUBTITLE = 'Manage Categories';
	include("includes/header.php");

	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');
	$appCode = getValPostORGet('appCode', 'B');

	$whereCls = "1";
	if ($keyword) $whereCls .= " AND (ticketCode  LIKE '%$keyword%')";
	if ($status)
	{
		if ($status == 'M') $whereCls .= " AND isMasterCode = 'Y'";
		else $whereCls .= " AND status = '$status'"; 
	}
	if ($appCode) $whereCls .= " AND appCode_FK = '$appCode'";

	$whereCls4 = ' 1';
	$accountType = $_SESSION['userDetails']['accountType'];
	
	if ($accountType != 'S')
	{
		$appCodes = $_SESSION['userDetails']['appCodes'];
		$appCodesStr = str_replace(',' , "' OR appCode = '", $appCodes);
		$appCodesStr = "(appCode = '$appCodesStr')";
		$whereCls4 = $appCodesStr;
		
		$appCodesStr = str_replace(',' , "' OR appCode_FK = '", $appCodes);
		$appCodesStr = "(appCode_FK = '$appCodesStr')";
		$whereCls .= " AND ($appCodesStr)";
	}
	
	$ticketCodeInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_ticket_codes', $whereCls, '', '', 'createdOn', 'ASC');
	$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'] = "keyword=$keyword&status=$status&appCode=$appCode";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>

        <div class="box">
			<!-- Start of header area-->
			
            <div class="box-header dker">
                <h3>View All Ticket Codes</h3>
					<a href="generate-ticket-codes.php" class="view-all"><i class="material-icons">&#xe02e;</i>Generate New Ticket Codes for Live Event</a>
            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			<div class="search-panel">
				<form class="form-inline" method="post" action='<?php echo $CUR_PAGE_NAME?>'>
					<div class="form-group">
						<label for="keyword">Ticket Code</label>
						<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $keyword?>">						
					</div>
					<div class="form-group">
						<label for="search">App Name</label>
<?php

					$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', $whereCls4, '', '', 'appName', 'ASC');	 
					makeDropDownFromDB('appCode', $appsInfoArr, 'appCode', 'appName', $appCode, "class='selectpicker' data-size='4'", '', '');
?>	
					</div>
					<div class="form-group">
						<label for="search">Ticket Sell Status</label>
<?php
						makeDropDown('status', array_keys($ARR_TCKT_STATUS), array_values($ARR_TCKT_STATUS), $status, "class='selectpicker' data-size='5'", '', '');
?>	
					</div>
					<span class="button_box">
						<button type="submit" class="btn btn-sm blue_btn"><i class="fa fa-search "></i>&nbsp;Search</button>
						<button type="reset" class="btn btn-sm yellow_btn" onClick="javascript:navigate2('<?php echo $CUR_PAGE_NAME?>');"><i class="fa fa-repeat "></i>&nbsp;Reset</button>
					</span>
				</form>
			</div>
			
			<!-- End of search panel -->
			<!-- Start of table responsive -->
            <div class="table-responsive">
				<table class="table table-striped testimonial_table">
					<thead>
					<tr>
						<th class="width80">Sr. No.</th>
						<th>Ticket Code</th>
						<th class="text-center width202">Ticket Sell Status</th>
						<th class="text-center width202">Ticket Used</th>						
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($ticketCodeInfoArr) && !empty($ticketCodeInfoArr))
					{
						$numOfRows = count($ticketCodeInfoArr);
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $ticketCodeInfoArr[$i]['status'];
							$isUsed = $ticketCodeInfoArr[$i]['isUsed'];
							$isMasterCode = $ticketCodeInfoArr[$i]['isMasterCode'];
							$statusTxt = $ARR_TCKT_STATUS[$status]; 
							$isUsed = $ARR_IS_PREMIUM[$isUsed]; 
							if ($isMasterCode == 'Y') $statusTxt = $ARR_TCKT_STATUS['M'];
?>
							 <tr>
								<td><?=$i+1?>.</td>
								<td><?php echo $ticketCodeInfoArr[$i]['ticketCode']?></td>
								<td class="text-center"><?php echo $statusTxt?></td>
								<td class="text-center"><?php echo $isUsed?></td>
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
			<div class="pagination_box" style='display:none;'>
			<ul class="pagination">
				<li><span class="glyphicon glyphicon-menu-left"></span></li>
				<li class="active"><a href="#">1</a></li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><span class="glyphicon glyphicon-menu-right"></span></li>
			</ul>
			</div>
		</div>
	</div>
</div>
<!-- End of content -->
<!-- Start of footer -->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->
</div>
<!-- Start of main content -->