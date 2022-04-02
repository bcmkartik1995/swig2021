<?php	
	$SUBTITLE = 'Manage Admin Users';
	include("includes/header.php");
	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');

	$whereCls = 1;
	if ($keyword) $whereCls .= " AND (socialLinkName  LIKE '%$keyword%' OR socialLinkUrl LIKE '%$keyword%' OR awesomeFontClsName LIKE '%$keyword%')";
	if ($status) $whereCls .= " AND status = '$status'";
	
	$socialLinksInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_social_links', $whereCls, '', '', 'rank ASC, addedOn', 'DESC');	 
	$_SESSION['SESSION_QRY_STRING'] = "keyword=$keyword&status=$status";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                <h3>View All Admin Users</h3><a href="add-edit-social-link.php" class="view-all"><i class="material-icons">&#xe02e;</i>Add New Admin User</a>
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
						<label for="search">Status</label>
<?php
						makeDropDown('status', array_keys($STATUS), array_values($STATUS), $status, "class='selectpicker' data-size='4'", '', '');
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
						<th>Name</th>
						<th>URL</th>
						<th class="text-center">Order</th>						
						<th class="text-center width50">Status</th>
						<th class="text-center width200">Action</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($socialLinksInfoArr) && !empty($socialLinksInfoArr))
					{
						$numOfRows = count($socialLinksInfoArr);
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $socialLinksInfoArr[$i]['status'];
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

							$frmId4ActionDelete = "socialLinkDeleteFrmId_".$i;
							$frmId4ActionStatus = "socialLinkStatusFrmId_".$i;
?>
							 <tr>
								<td><?=$i+1?>.</td>
								<td><?php echo $socialLinksInfoArr[$i]['socialLinkName']?></td>								
								<td><a href="<?php echo $socialLinksInfoArr[$i]['socialLinkUrl']?>" class="view_all_contend" target='_blank'><?php echo $socialLinksInfoArr[$i]['socialLinkUrl']?></a></td>
								<td align="center"><?php echo $socialLinksInfoArr[$i]['rank'];?></td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/social-link-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this social link?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										<input type="hidden" name="enckey" value="<?php echo $socialLinksInfoArr[$i]['socialLinkCode']?>">
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">
									<!----------------------Here EDIT Form Start------------------>
									<form action="add-edit-social-link.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm btn-success"><small><i class="fa fa-pencil"></i>&nbsp;Edit</small></button>
										<input type="hidden" name="enkey" value="<?php echo $socialLinksInfoArr[$i]['socialLinkCode']?>">

									</form>
									<!----------------------Here DELETE Form Start------------------>
									<form action="controller/social-link-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionDelete?>">		
										<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionDelete?>', ' Deletion', 'Are you sure you want to permanently delete this social link?');">
											<small><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Delete</small>
										</button>
										<input type="hidden" name="enckey" value="<?php echo $socialLinksInfoArr[$i]['socialLinkCode']?>">
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
<!-- End of footer-->
</div>
<!-- Start of main content -->