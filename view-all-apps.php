<?php	
	$SUBTITLE = 'Manage Apps';
	include("includes/header.php");
	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');
	
	$assignedApp = $_SESSION['userDetails']['appCodes'];
	$accountType = $_SESSION['userDetails']['accountType'];
	
	$whereCls = "(appCode = '$assignedApp')";
	if ($accountType == 'S') $whereCls = 1;
	
	if ($keyword) $whereCls .= " AND (appName  LIKE '%$keyword%')";
	if ($status) $whereCls .= " AND status = '$status'";

	$per_page_record = 10;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
    if (isset($_GET["page"])) {    
        $page  = $_GET["page"];    
    }    
    else {    
      $page=1;    
    }    

    $start_from = ($page-1) * $per_page_record;     

	
	$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', $whereCls, $start_from, $per_page_record, 'appName', 'ASC');	 
	$_SESSION['SESSION_QRY_STRING'] = "keyword=$keyword&status=$status";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>
        <div class="box">
			<!-- Start of header area-->
            <div class="box-header dker">
                <h3>View All Apps</h3>
<?php
				if ($accountType == 'S')
				{
?>
					<a href="add-edit-app.php" class="view-all"><i class="material-icons">&#xe02e;</i>Add New App</a>
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
						<th>App Name</th>													
						<th class="width350">App Id</th>													
						<th class="text-center width50">Status</th>
						<th class="text-center width250">Action</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($appsInfoArr) && !empty($appsInfoArr))
					{
						$numOfRows = count($appsInfoArr);
						//danger
						$srno = $start_from;
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
?>
							 <tr>
								<td><?=$srno+1?>.</td>
								<td><?php echo $appsInfoArr[$i]['appName']?></td>
								<td class="width350"><a href="feed/v1/<?php echo $appsInfoArr[$i]['appCode']?>" class="view_all_contend" target='_blank' title='View Feed'><?php echo $appsInfoArr[$i]['appCode']?></a></td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/app-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this app?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										<input type="hidden" name="enckey" value="<?php echo $appsInfoArr[$i]['appCode']?>">
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">
									<!----------------------Here Menus Form Start------------------>
									<form action="view-all-menus.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm blue_btn"><small><i class="fa fa-bars"></i>&nbsp;Manage Categories</small></button>
										<input type="hidden" name="appCode" value="<?php echo $appsInfoArr[$i]['appCode']?>">
										<input type="hidden" name="appName" value="<?php echo $appsInfoArr[$i]['appName']?>">
									</form>										
									<!----------------------Here EDIT Form Start------------------>
									<form action="add-edit-app.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm btn-success"><small><i class="fa fa-pencil"></i>&nbsp;Edit</small></button>
										<input type="hidden" name="enkey" value="<?php echo $appsInfoArr[$i]['appCode']?>">
									</form>
								</td>
							 </tr>
<?php
                        $srno = $srno+1;
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

            <div class="padding" style="padding-top: 0px;">
	
				<div class="pagination">    
				      <?php  
				        $UsersCount = $objDBQuery->getRecordCount(0, 'tbl_apps', $whereCls, '');     
				        $total_records = $UsersCount;     
				            
				        // Number of pages required.   
				        $total_pages = ceil($total_records / $per_page_record);     
				        $pagLink = "";       
				      
				        if($page>=2){   
				            echo "<a href='view-all-apps.php?page=".($page-1)."'>  Prev </a>";   
				        }       
				                   
				        for($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++){   
				          if ($i == $page) {   
				              $pagLink .= "<a class = 'active' href='view-all-apps.php?page="  
				                                                .$i."'>".$i." </a>";   
				          }               
				          else  {   
				              $pagLink .= "<a href='view-all-apps.php?page=".$i."'>   
				                                                ".$i." </a>";     
				          }   
				        };     
				        echo $pagLink;   
				  
				        if($page<$total_pages){   
				            echo "<a href='view-all-apps.php?page=".($page+1)."'>  Next </a>";   
				        }   
				  
				      ?>    
				</div> 
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