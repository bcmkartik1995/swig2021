<?php	
	$SUBTITLE = 'Manage Streams';
	include("includes/header.php");
	//	$interval = '24 Hour';
	//print_r(getExpiredDate($interval));
	//die;
	$appCode = getValPostORGet('appCode', 'B');
	$menuCode = getValPostORGet('menuCode', 'B');
	$menuName = getValPostORGet('menuName', 'B');
	checkPageAccessPermission($appCode);
	checkPageAccessPermission($menuCode);

	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');	
	$subCatCode_FK = getValPostORGet('subCatCode_FK', 'B');	

	$whereCls = "menuCode_FK = '$menuCode' AND subCatCode = subCatCode_FK AND isDeleted = 'N'";
	if ($keyword) $whereCls .= " AND (streamTitle LIKE '%$keyword%' OR streamUrl LIKE '%$keyword%' OR streamImg LIKE '%$keyword%' OR streamThumbnail LIKE '%$keyword%' OR streamdescription LIKE '%$keyword%' OR staring LIKE '%$keyword%' OR streamTrailerUrl LIKE '%$keyword%' OR streamDuration LIKE '%$keyword%' OR directedBy LIKE '%$keyword%' OR writtenBy LIKE '%$keyword%' OR producedBy LIKE '%$keyword%' OR genre LIKE '%$keyword%' OR language LIKE '%$keyword%' OR rating LIKE '%$keyword%')";	
	if ($status) $whereCls .= " AND status = '$status'";
	if ($subCatCode_FK) $whereCls .= " AND subCatCode_FK = '$subCatCode_FK'";
	$orderBY = "s.isStreamFeatured = 'Y' DESC, s.streamOrder ASC, sub.subCatName ASC, s.createdOn DESC";
	$streamsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_streams s, tbl_subcategories sub', $whereCls, '', '', $orderBY, '');	 
	$_SESSION['SESSION_QRY_STRING_FOR_STREAM'] = "keyword=$keyword&status=$status&appCode=$appCode&menuCode=$menuCode&menuName=$menuName&subCatCode_FK=$subCatCode_FK";
?>
<!-- Start of content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
  function slideout(){
	  setTimeout(function(){
		  $("#response").slideUp("slow", function () {
		  });
		}, 2000);
  }

  function showSort(){
		$(".grab").show();
		$("#response").hide();
	    $(function () {
		    $("#drackId").sortable({ opacity: 0.8, cursor: 'move', update: function() {
            var order = $(this).sortable("serialize") + '&update=update';
            $.post("updateStreamOrder.php", order, function(theResponse){
						    $("#response").html(theResponse);
                $("#response").slideDown('slow');  
                slideout();
						});
		    }         
		  });
		});
	} 
</script>

<script type="text/javascript">


  
</script>
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
			<div class="row main_headlinebox">
				<div class="col-md-6">
					<ul class="breadcrumb_box">
					  <li><a href="view-all-apps.php?<?php echo $_SESSION['SESSION_QRY_STRING']?>">Apps List</a></li>
					  <li><i class="material-icons">keyboard_arrow_right</i><a href="view-all-menus.php?<?php echo $_SESSION['SESSION_QRY_STRING_FOR_MENU']?>"><span>Categories List</span></a></li>
					  <li class="active"><i class="material-icons">keyboard_arrow_right</i><span>Streams List</span></li>
					</ul>
				</div>
				<div class="col-md-6">
					<a href="view-all-menus.php?<?php echo $_SESSION['SESSION_QRY_STRING_FOR_MENU']?>" class="view-all back_icons"><i class="material-icons">keyboard_arrow_left</i>Back</a>
				</div>
			</div>
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                <h3>View All Streams of Menu <span style='color:red'>"<?php echo $menuName?>"</span></h3><a href="add-edit-stream.php?appCode=<?php echo $appCode?>&menuCode=<?php echo $menuCode?>" class="view-all"><i class="material-icons">&#xe02e;</i>Add New Stream</a>
            </div>
			<!-- End of header area-->
			<!-- Start of search panel -->
			<div class="search-panel">
				<form class="form-inline" method="post" action='<?php echo $CUR_PAGE_NAME?>'>
					<div class="form-group">
						<label for="keyword">Keyword</label>
						<input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $keyword?>">
						<input type="hidden" class="form-control" name="appCode" value="<?php echo $appCode?>">
						<input type="hidden" class="form-control" name="menuCode" value="<?php echo $menuCode?>">
					</div>
					<div class="form-group">
						<label for="search">Subcategory</label>
<?php
						$subcateInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_subcategories', array('status' => 'A', 'appCode_FK' => $appCode), '', '', 'subCatName', 'ASC');	 
						makeDropDownFromDB('subCatCode_FK', $subcateInfoArr, 'subCatCode', 'subCatName', $subCatCode_FK, "class='selectpicker' data-size='6'", '', '');
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
						<button type="reset" class="btn btn-sm yellow_btn" onClick="javascript:navigate2('<?php echo $CUR_PAGE_NAME?>?appCode=<?php echo $appCode?>&menuCode=<?php echo $menuCode?>');"><i class="fa fa-repeat "></i>&nbsp;Reset</button>
						<button type="button" class="btn btn-sm btn-success" onclick="showSort()">sort</button>
			            <button type="button" class="btn btn-sm btn-primary" onclick="window.location='export-all-streams.php?menuCode=<?php echo $menuCode?>';">Export</button> 
					</span>
				</form>
			</div>
			
			<!-- End of search panel -->
			<!-- Start of table responsive -->

            <div class="table-responsive">
            	<div id="response" class="alert-success"> </div>
				<table class="table table-striped testimonial_table" id = "tblLocations">
					<thead>
					<tr>
						<th class="grab" style="display: none;"></th>
						<th class="width80">Sr. No.</th>
						<th>Stream Info</th>							
						<th class='width231'>Stream Rent Info</th>						
						<th class="client_img_center streamposter">Stream Preview</th>						
						<th class="text-center width70">Featured</th>
						<th class="text-center width70">Order</th>
						<th class="text-center width50">Status</th>						
						<th class="text-center width231">Action</th>
						
					</tr>
					</thead>
					<tbody id="drackId">

<?php
					if (is_array($streamsInfoArr) && !empty($streamsInfoArr))
					{
						$cTemp	 = 1;
						$tempStreamFeatured = '';
						$numOfRows = count($streamsInfoArr);
						if ($streamsInfoArr[0]['isStreamFeatured'] == 'Y') echo "<tr><td class='featured_other' colspan='10'><span class='view_all_contend2'>Featured Streams:</span></td></tr>";
						$srNo = 1;
						$totalRcdOfIsFeature = array_count_values(array_column($streamsInfoArr, 'isStreamFeatured'))['Y'];
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $streamsInfoArr[$i]['status'];
							$streamUrl = $streamsInfoArr[$i]['streamUrl'];
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
							$frmId4ActionFeatured = "featuredFrmId_".$i;
							$streamImg = $streamsInfoArr[$i]['streamImg'];
							if ($streamImg !='') $streamImgPath = HTTP_PATH."/uploads/stream_images/".$streamImg;
							else $streamImgPath = HTTP_PATH."/images/default_stream_poster.jpg";

							$streamDuration = $streamsInfoArr[$i]['streamDuration'];
							
							if ($streamDuration > 1) $streamDuration = "$streamDuration Mins";
							else if ($streamDuration == 1) $streamDuration = "$streamDuration Min"; 
							else if ($streamDuration == '') $streamDuration = "0 Min";
							else $streamDuration = "0 Min";

							$validityAfterRent = '';
							$subscriptionExpired = $streamsInfoArr[$i]['subscriptionExpired'];
							$subscriptionExpiredFaq = $streamsInfoArr[$i]['subscriptionExpiredFaq'];
							if ($subscriptionExpired > 1)
							{
								$validityAfterRent = "$subscriptionExpired ". $ARR_SUBSCRIPTION_EXPIRED[$subscriptionExpiredFaq].'s';
							}
							else if ($subscriptionExpired == 1)
							{
								$validityAfterRent = "$subscriptionExpired ". $ARR_SUBSCRIPTION_EXPIRED[$subscriptionExpiredFaq];
							}
							else if ($subscriptionExpired == '') $validityAfterRent = "N/A";
							else $validityAfterRent = "N/A";

							$streamTitle = 'N/A';
							if ($streamsInfoArr[$i]['streamTitle'] != '') $streamTitle = $streamsInfoArr[$i]['streamTitle'];
							$isStreamFeatured = $streamsInfoArr[$i]['isStreamFeatured'];	
							$subCatName  = $streamsInfoArr[$i]['subCatName'];	
							
							//if ($isStreamFeatured != $tempStreamFeatured && $isStreamFeatured == 'N' || ($isStreamFeatured == 'N' && ))
							if ($isStreamFeatured != $tempStreamFeatured && $isStreamFeatured == 'N')
							{
								$tempStreamFeatured = $isStreamFeatured;
								echo "<tr><td class='featured_other' colspan='10'><span class='view_all_contend2'>Other Streams:</span></td></tr>";
								$srNo = 1;
							}	
							$isSwichFrmActive = 'N';
							if (($totalRcdOfIsFeature > 1 && $streamsInfoArr[$i]['isStreamFeatured'] == 'Y') || $streamsInfoArr[$i]['isStreamFeatured'] == 'N') $isSwichFrmActive = 'Y';

							$streamOrder = $streamsInfoArr[$i]['streamOrder'];
?>
							
							 <tr id="arrayorder_<?php echo $streamsInfoArr[$i]['streamId_PK']?>">
							 	<td class="grab" style="display: none;">&#9776;</td>
								<td><?php echo $srNo?>.</td>
								<td><span class='diff_color'>Title:</span> <?php echo $streamTitle?><br><span class='diff_color'>Duration:</span> <?php echo $streamDuration?><br><span class='diff_color'>Subcategory:</span> <?php echo $subCatName?></td>
								<!--<td><a href="<?php echo $streamsInfoArr[$i]['streamUrl']?>" class="view_all_contend" target='_blank' title='Stream URL'><?php echo $streamsInfoArr[$i]['streamUrl']?></a></td>-->								
								<td><span class='diff_color'>DonatePerView</span>: <?php echo $ARR_IS_PREMIUM[$streamsInfoArr[$i]['isDontePerView']]?><br><span class='diff_color'>Validity After Rent:</span> <?php echo $validityAfterRent?></td>								
								<td class="client_img_center">
									<span class="table_img">
										<a href="#" data-toggle="modal" data-target="#stream_popupbox" ui-toggle-class="bounce" ui-target="#animate" id='streamPreviewId' onclick="javascript:setStreamURL('<?php echo $streamUrl?>')"><img src="<?php echo $streamImgPath?>" alt="Stream Poster"></a>
									</span>
									<span class="table_text" style='display:none'>Banner</span>
								</td>							
								<td class="text-center">
<?php
								$styleB = "style='cursor:no-drop;'";
								if ($isSwichFrmActive == 'Y')
								{
									$styleB = "";
									
?>
								<form action="controller/stream-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionFeatured?>">
<?php
								}
?>
									<label class="switch-light switch-candy" <?php echo $styleB?> onclick="frmSubmit('<?php echo $frmId4ActionFeatured?>');">
									<label class="switch" <?php echo $styleB?>>
									 
									  <input type="checkbox" <?php if ($streamsInfoArr[$i]['isStreamFeatured'] == 'Y') echo "checked"?> disabled>
									  <span class="slider round" <?php echo $styleB?>></span>
									</label>
									</label>

<?php
								if ($isSwichFrmActive == 'Y')
								{
					
?>
								<input type="hidden" name="enckey" value="<?php echo $streamsInfoArr[$i]['streamCode']?>">
								<input type="hidden" name="appCode" value="<?php echo $appCode?>">
								<input type="hidden" name="menuCode" value="<?php echo $menuCode?>">
								<input type="hidden" name="postAction" value="changeRecordFeatured">
								<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
								</form>
<?php
								}
?>
								</td>
								<td class="text-center">
<?php
									
									$cTemp2 = 1;
									if (@$streamsInfoArr[$i+1]['isStreamFeatured'] == 'N' && $cTemp && $srNo != 1)
									{
										$cTemp = 0;
										$cTemp2 = 0;
									}

									if ($numOfRows - 1 == $i) $cTemp2 = 0;

									if ($srNo != 1)
									{
										$nxtAndPreStreamCode = $streamsInfoArr[$i-1]['streamCode'];										
?>
										<form action="controller/stream-controller.php" method="post" class="form-inline" id="down<?php echo $frmId4ActionStatus?>">
											<input type="image" src='images/up.png' title="Move Up" border='0' align="absmiddle" />
											<input type="hidden" name="enckey" value="<?php echo $streamsInfoArr[$i]['streamCode']?>">
											<input type="hidden" name="appCode" value="<?php echo $appCode?>">
											<input type="hidden" name="menuCode" value="<?php echo $menuCode?>">
											<input type="hidden" name="postAction" value="changeStreamOrder">											
											<input type="hidden" name="nxtAndPreStreamCode" value="<?php echo $nxtAndPreStreamCode?>">
											<input type="hidden" name="nxtAndPreStreamOrder" value="<?php echo (($streamsInfoArr[$i-1]['streamOrder'])+1)?>">
											<input type="hidden" name="currentStreamOrder" value="<?php echo $streamsInfoArr[$i]['streamOrder']-1?>">
											<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
										</form>
<?php
									}

									if ($cTemp2)
									{
									$nxtAndPreStreamCode = $streamsInfoArr[$i+1]['streamCode'];
?>
									<form action="controller/stream-controller.php" method="post" class="form-inline" id="up<?php echo $frmId4ActionStatus?>">
										<input type="image" src='images/down.png' title="Move Down" border='0' align="absmiddle" />
										<input type="hidden" name="enckey" value="<?php echo $streamsInfoArr[$i]['streamCode']?>">
										<input type="hidden" name="appCode" value="<?php echo $appCode?>">
										<input type="hidden" name="menuCode" value="<?php echo $menuCode?>">
										<input type="hidden" name="postAction" value="changeStreamOrder">										
										<input type="hidden" name="nxtAndPreStreamCode" value="<?php echo $nxtAndPreStreamCode?>">
										<input type="hidden" name="nxtAndPreStreamOrder" value="<?php echo (($streamsInfoArr[$i+1]['streamOrder'])-1)?>">
										<input type="hidden" name="currentStreamOrder" value="<?php echo $streamsInfoArr[$i]['streamOrder']+1?>">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>
<?php
									}
?>
								</td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/stream-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this stream?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										
										<input type="hidden" name="enckey" value="<?php echo $streamsInfoArr[$i]['streamCode']?>">
										<input type="hidden" name="appCode" value="<?php echo $appCode?>">
										<input type="hidden" name="menuCode" value="<?php echo $menuCode?>">
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">									
									<!----------------------Here EDIT Form Start------------------>
									<form action="add-edit-stream.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm btn-success"><small><i class="fa fa-pencil"></i>&nbsp;Edit</small></button>
										<input type="hidden" name="enkey" value="<?php echo $streamsInfoArr[$i]['streamCode']?>">
										<input type="hidden" name="appCode" value="<?php echo $appCode?>">
										<input type="hidden" name="menuCode" value="<?php echo $menuCode?>">
									</form>
									<!----------------------Here DELETE Form Start------------------>
									<form action="controller/stream-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionDelete?>">		
										<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionDelete?>', ' Deletion', 'Are you sure you want to permanently delete this stream?');">
											<small><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Delete</small>
										</button>
										<input type="hidden" name="enckey" value="<?php echo $streamsInfoArr[$i]['streamCode']?>">
										<input type="hidden" name="postAction" value="deleteRecordAction">
										<input type="hidden" name="menuCode" value="<?php echo $menuCode?>">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
							 </tr>
<?php
						$srNo++;
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
<?php 
include_once('video-player.php');
?>
</div>
<!-- End of content -->
<!-- Start of footer -->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->
</div>
<!-- <script src="js/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<script>
	function showSort(){
		$(".grab").show();
	    $(function () {
		    $("#tblLocations").sortable({
		        items: 'tr:not(tr:first-child)',
		        cursor: 'pointer',
		        axis: 'y',
		        dropOnEmpty: false,
		        start: function (e, ui) {
		            ui.item.addClass("selected");
		        },
		        stop: function (e, ui) {
		            ui.item.removeClass("selected");
		            $(this).find("tr").each(function (index) {
		                if (index > 0) {
		                    $(this).find("td").eq(2).html(index);
		                }
		            });
		        }
		    });
		});
	}
</script> -->