<?php	
	$SUBTITLE = 'Manage Master Codes';
	include("includes/header.php");

	$keyword = getValPostORGet('keyword', 'B');
	$shortDescription = getValPostORGet('shortDescription', 'B');
	$appCode = getValPostORGet('appCode', 'B');

	$whereCls = "1";
	if ($keyword) $whereCls .= " AND (masterCode  LIKE '%$keyword%' OR shortDescription LIKE '%$keyword%')";
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
	
	$masterCodeInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_master_codes', $whereCls, '', '', 'createdOn', 'ASC');
	$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'] = "keyword=$keyword&status=$status&appCode=$appCode";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>

        <div class="box">
			<!-- Start of header area-->
			
            <div class="box-header dker">
                <h3>View All Master Codes</h3>
					<a href="generate-master-code.php" class="view-all"><i class="material-icons">&#xe02e;</i>Generate New Master Code for Live Event</a>
            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			<div class="search-panel">
				<form class="form-inline" method="post" action='<?php echo $CUR_PAGE_NAME?>'>
					<div class="form-group">
						<label for="keyword">Keyword</label>
						<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $keyword?>">						
					</div>
					<div class="form-group">
						<label for="search">App Name</label>
<?php

					$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', $whereCls4, '', '', 'appName', 'ASC');	 
					makeDropDownFromDB('appCode', $appsInfoArr, 'appCode', 'appName', $appCode, "class='selectpicker' data-size='4'", '', '');
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
						<th>Master Code</th>
						<th>App Name</th>
						<th class="text-center width202">Short Description</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($masterCodeInfoArr) && !empty($masterCodeInfoArr))
					{
						$numOfRows = count($masterCodeInfoArr);
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$appCode = $masterCodeInfoArr[$i]['appCode_FK'];
						    $appNameArr = $objDBQuery->getRecord(0, array('appName'), 'tbl_apps',array('appCode' => $appCode), '', '', 'createdOn', 'ASC');	
?>
							 <tr>
								<td><?=$i+1?>.</td>
								<td><?php echo $masterCodeInfoArr[$i]['masterCode']?></td>
								<td><?php echo $appNameArr[0]['appName'];?></td>
								<td class="text-center"><?php echo $masterCodeInfoArr[$i]['shortDescription'];?></td>
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