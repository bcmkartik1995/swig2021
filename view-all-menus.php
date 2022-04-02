<?php	
	$SUBTITLE = 'Manage Categories';
	include("includes/header.php");
	$appName = getValPostORGet('appName', 'B');
	$appCode = getValPostORGet('appCode', 'B');
	checkPageAccessPermission($appCode);

	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');
	$menuType = getValPostORGet('menuType', 'B');

	$whereCls = "appCode_FK = '$appCode'";
	if ($keyword) $whereCls .= " AND (menuName  LIKE '%$keyword%')";
	if ($menuType) $whereCls .= " AND menuType = '$menuType'";
	if ($status) $whereCls .= " AND status = '$status'";
	
	$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_menus', $whereCls, '', '', 'menuOrder', 'ASC');	 
	$_SESSION['SESSION_QRY_STRING_FOR_MENU'] = "keyword=$keyword&status=$status&menuType=$menuType&appCode=$appCode&appName=$appName";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
		<div class="row main_headlinebox">
				<div class="col-md-6">
					<ul class="breadcrumb_box">
					  <li><a href="view-all-apps.php?<?php echo $_SESSION['SESSION_QRY_STRING']?>">Apps List</a></li>
					  <li class="active"><i class="material-icons">keyboard_arrow_right</i><span>Categories List</span></li>
					</ol>
				</div>
				<div class="col-md-6">
					<a href="view-all-apps.php?<?php echo $_SESSION['SESSION_QRY_STRING']?>" class="view-all back_icons"><i class="material-icons">keyboard_arrow_left</i>Back</a>
				</div>
			</div>
        <div class="box">
			<!-- Start of header area-->
			
            <div class="box-header dker">
                <h3>View All Categories of App <span style='color:red'>"<?php echo $appName?>"</span></h3>
<?php
				if ($_SESSION['userDetails']['accountType'] == 'S')
				{
?>
					<a href="add-edit-menu.php?appCode=<?php echo $appCode?>" class="view-all"><i class="material-icons">&#xe02e;</i>Add New Category</a>
<?php
				}
?>
            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			<div class="search-panel">
				<form class="form-inline" method="post" action='<?php echo $CUR_PAGE_NAME?>'>
					<div class="form-group">
						<label for="keyword">Keyword</label>
						<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $keyword?>">
						<input type="hidden" class="form-control" name="appCode" value="<?php echo $appCode?>">
					</div>
					<div class="form-group">
						<label for="search">Type</label>
<?php
						makeDropDown('menuType', array_keys($ARR_MENU_TYPE), array_values($ARR_MENU_TYPE), $menuType, "class='selectpicker' data-size='4'", '', '');
?>	
					</div>
					<div class="form-group">
						<label for="search">Status</label>
<?php
						makeDropDown('status', array_keys($STATUS), array_values($STATUS), $status, "class='selectpicker' data-size='4'", '', '');
?>	
					</div>
					<span class="button_box">
						<button type="submit" class="btn btn-sm blue_btn"><i class="fa fa-search "></i>&nbsp;Search</button>
						<button type="reset" class="btn btn-sm yellow_btn" onClick="javascript:navigate2('<?php echo $CUR_PAGE_NAME?>?appCode=<?php echo $appCode?>');"><i class="fa fa-repeat "></i>&nbsp;Reset</button>
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
						<th class="text-center">Type</th>	
						<th class="text-center">Default Category</th>
						<th class="text-center" style='display:none;'>Order</th>																				
						<th class="text-center width50">Status</th>
						<th class="text-center width202">Action</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($appsInfoArr) && !empty($appsInfoArr))
					{
						$numOfRows = count($appsInfoArr);
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $appsInfoArr[$i]['status'];
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

							if ($appsInfoArr[$i]['menuType'] == 'L') $menuTypeImg = 'live_stream_icon.png';
							else if ($appsInfoArr[$i]['menuType'] == 'E') $menuTypeImg = 'premium_live.png';
							else if ($appsInfoArr[$i]['menuType'] == 'D') $menuTypeImg = 'permium_vod.png';
							else $menuTypeImg = 'vod_btn.png';
							
							$upImageHide = '';
							$downImageHide = '';
							if ($i == 0) $upImageHide = "style=display:none;";
							if ($numOfRows -1 == $i) $downImageHide = "style=display:none;";
?>
							 <tr>
								<td><?=$i+1?>.</td>
								<td><?php echo $appsInfoArr[$i]['menuName']?></td>
								<td class="text-center"><img src="images/<?php echo $menuTypeImg?>" alt="<?php echo $ARR_MENU_TYPE[$appsInfoArr[$i]['menuType']]?>" title="<?php echo $ARR_MENU_TYPE[$appsInfoArr[$i]['menuType']]?>"></td>
								<td class="text-center"><?php echo $DEFAULT_SELECTION_MENU[$appsInfoArr[$i]['isDefaultMenu']]?></td>	
								<td class="text-center" style='display:none;'>
									<span class="up_down">
										<a href ="#" <?php echo $upImageHide?>><img src="images/up.png" alt="Menu Up" title="Move Up"></a>
										<a href ="#" <?php echo $downImageHide?>><img src="images/down.png" alt="Menu Down" title="Move Down"></a>
									</span>									
								</td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/menu-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this category?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										<input type="hidden" name="enckey" value="<?php echo $appsInfoArr[$i]['menuCode']?>">
										<input type="hidden" name="appCode" value="<?php echo $appCode?>">
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">
									<!----------------------Here Stream Form Start------------------>
									<form action="view-all-streams.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm blue_btn"><small><i class="material-icons">slideshow</i>&nbsp;Manage Streams</small></button>
										<input type="hidden" name="menuCode" value="<?php echo $appsInfoArr[$i]['menuCode']?>">
										<input type="hidden" name="menuName" value="<?php echo $appsInfoArr[$i]['menuName']?>">
										<input type="hidden" name="appCode" value="<?php echo $appCode?>">
									</form>									
									<!----------------------Here EDIT Form Start------------------>
									<form action="add-edit-menu.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm btn-success"><small><i class="fa fa-pencil"></i>&nbsp;Edit</small></button>
										<input type="hidden" name="enkey" value="<?php echo $appsInfoArr[$i]['menuCode']?>">
										<input type="hidden" name="appCode" value="<?php echo $appCode?>">
									</form>
<?php
									if ($_SESSION['userDetails']['accountType'] == 'S')
									{
?>
									<!----------------------Here DELETE Form Start------------------>
									<form action="controller/menu-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionDelete?>">		
										<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionDelete?>', ' Deletion', 'Are you sure you want to permanently delete this category name?');">
											<small><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Delete</small>
										</button>
										<input type="hidden" name="enckey" value="<?php echo $appsInfoArr[$i]['menuCode']?>">
										<input type="hidden" name="postAction" value="deleteRecordAction">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
<?php
									}
?>
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