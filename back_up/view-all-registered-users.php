<?php	
	$SUBTITLE = "View All Registered Users";
	include("includes/header.php");
	$keyword = getValPostORGet('keyword', 'B');
	$appCode = getValPostORGet('appCode', 'B');
	$accountType = $_SESSION['userDetails']['accountType'];
	
	$whereCls = "1 AND appCode_FK = appCode ";
	if ($accountType != 'S') $appCode = $_SESSION['userDetails']['appCodes'];	
		
	if ($keyword) $whereCls .= " AND (name LIKE '%$keyword%' OR username LIKE '%$keyword%' OR email LIKE '%$keyword%')";
	if ($appCode) $whereCls .= " AND (appCode = '$appCode')";
	
	$registeredInfoArr = $objDBQuery->getRecord(0, "*, ru.createdOn AS createdOnUser ", 'tbl_registered_users ru, tbl_apps', $whereCls .' GROUP BY ru.userCode', '', '', 'username ASC, ru.createdOn', 'DESC');	
	$_SESSION['SESSION_QRY_STRING'] = "keyword=$keyword&appCode=$appCode";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                <h3>View All Registered Users</h3>
            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			<div class="search-panel">
				<form class="form-inline" method="post" action='<?php echo $CUR_PAGE_NAME?>'>
					<div class="form-group">
						<label for="keyword">Keyword</label>
						<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $keyword?>">
					</div>
<?php
					if ($accountType == 'S')
					{
?>
					<div class="form-group">
						<label for="search">App Name</label>
<?php
						$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', '', '', '', 'appName', 'ASC');	 
						makeDropDownFromDB('appCode', $appsInfoArr, 'appCode', 'appName', $appCode, "class='selectpicker' data-size='4'", '', '');
?>	
					</div>
<?php
					}
?>
					<span class="button_box">
						<button type="submit" class="btn btn-sm blue_btn"><i class="fa fa-search "></i>&nbsp;Search</button>
						<button type="reset" class="btn btn-sm yellow_btn" onClick="javascript:navigate2('<?php echo $CUR_PAGE_NAME?>');"><i class="fa fa-repeat "></i>&nbsp;Reset</button>
					</span>
				</form>
			</div>
			
			<!-- End of search panel -->
			<!-- Start of table responsive -->
            <div class="table-responsive">
				<table class="table table-striped blog_pages">
					<thead>
					<tr>
						<th class="width80">Sr. No.</th>
<?php
					if ($accountType == 'S')
					{
?>
						<th>Registered From App</th>
<?php

					}
?>
						<th>Username</th>												
						<th>Name</th>												
						<th>Email</th>			
						<th>Registered On</th>
						<th class="text-center width70">Status</th>
						<th class="text-center width231">Action</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($registeredInfoArr) && !empty($registeredInfoArr))
					{
						$numOfRows = count($registeredInfoArr);
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $registeredInfoArr[$i]['accountStatus'];
							if ($status == 'A')
							{
								$statusCls = 'text-success';
								$statusTxt = $STATUS[$status];								
							}
							else
							{
								$statusCls = 'text-danger';
								$statusTxt = $STATUS[$status];
							}

							$frmId4ActionDelete = "deleteFrmId_".$i;
							$frmId4ActionStatus = "statusFrmId_".$i;
?>
							 <tr>
								<td><?=$i+1?>.</td>
<?php
								if ($accountType == 'S')
								{
?>
								<td><span class="date_format"><?php echo $registeredInfoArr[$i]['appName']?></span> </td>
<?php
								}
?>
								<td><span class="date_format"><?php echo $registeredInfoArr[$i]['username']?></span> </td>
								<td><span class="date_format"><?php echo $registeredInfoArr[$i]['name']?></span> </td>
								<td><span class="date_format"><?php echo $registeredInfoArr[$i]['email']?></span> </td>
								<td><span class="date_format"><?php echo getTimestamp($registeredInfoArr[$i]['createdOnUser'], SHORT_DATE_FORMAT)?></span></td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/registered-users-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this account?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										<input type="hidden" name="enckey" value="<?php echo $registeredInfoArr[$i]['userCode']?>">
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">
									<!----------------------Here DELETE Form Start------------------>
									<form action="controller/registered-users-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionDelete?>">		
										<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionDelete?>', ' Deletion', 'Are you sure you want to permanently delete this user account?');">
											<small><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Delete</small>
										</button>
										<input type="hidden" name="enckey" value="<?php echo $registeredInfoArr[$i]['userCode']?>">
										<input type="hidden" name="postAction" value="deleteRecordAction">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
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
