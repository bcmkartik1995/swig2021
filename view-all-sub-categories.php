<?php	
	$SUBTITLE = 'Manage Categories';
	include("includes/header.php");

	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');
	$appCode = getValPostORGet('appCode', 'B');
	$statusfilter = $status;
	$appCodefilter = $appCode;

	$whereCls = "1";
	if ($keyword) $whereCls .= " AND (subCatName  LIKE '%$keyword%')";
	if ($status) $whereCls .= " AND status = '$status'";
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

	if(isset($_GET['pagination'])){
        $per_page_record = $_GET['pagination'];  // Number of entries to show in a page.
    } else {
        $per_page_record = 10;  // Number of entries to show in a page.
    }  
        // Look for a GET variable page if not found default is 1.        
    if (isset($_GET["page"])) {    
        $page  = $_GET["page"];    
    }    
    else {    
      $page=1;    
    }    

    $start_from = ($page-1) * $per_page_record; 

    //echo ".....".$start_from;die;
	
	$subcateInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_subcategories', $whereCls, $start_from, $per_page_record, 'subCatOrder ASC, subCatName', 'ASC');	 
	$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'] = "keyword=$keyword&status=$status&appCode=$appCode";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>

        <div class="box">
			<!-- Start of header area-->
			
            <div class="box-header dker">
                <h3>View All Sub Categories</h3>
					<a href="add-edit-sub-category.php" class="view-all"><i class="material-icons">&#xe02e;</i>Add New Sub Category</a>
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
<?php

						$appsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_apps', $whereCls4, '', '', 'appName', 'ASC');	 
						makeDropDownFromDB('appCode', $appsInfoArr, 'appCode', 'appName', $appCode, "class='selectpicker' data-size='4'", '', '');
?>
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
			<div class="pagination-panel">
				<form>
				<label for="search">Records per page</label>
				<select name="pagination" id="RPPapp" class="selectpicker" onchange="this.form.submit();">
					<?php if($per_page_record == 10){?>
					<option value="10" selected>10</option>
				    <?php }else{?>
					<option value="10">10</option>
				    <?php } ?>
				    <?php if($per_page_record == 15){?>
					<option value="15" selected>15</option>
				    <?php }else{?>
					<option value="15">15</option>
				    <?php } ?>
					<?php if($per_page_record == 25){?>
					<option value="25" selected>25</option>
				    <?php }else{?>
					<option value="25">25</option>
				    <?php } ?>
					<?php if($per_page_record == 50){?>
					<option value="50" selected>50</option>
				    <?php }else{?>
					<option value="50">50</option>
				    <?php } ?>
				    <?php if($per_page_record == 100){?>
					<option value="100" selected>100</option>
				    <?php }else{?>
					<option value="100">100</option>
				    <?php } ?>
				</select>
				</form>
			</div>

			<!-- Start of table responsive -->
            <div class="table-responsive">
				<table class="table table-striped testimonial_table">
					<thead>
					<tr>
						<th class="width80">Sr. No.</th>
						<th>Name</th>
						<th>App Name</th>
						<th class="text-center width202">Status</th>
						<th class="text-center width202">Action</th>
					</tr>
					</thead>
					<tbody>
<?php
					if (is_array($subcateInfoArr) && !empty($subcateInfoArr))
					{
						$numOfRows = count($subcateInfoArr);
						$srno = $start_from;
						//danger
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $subcateInfoArr[$i]['status'];
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
							
							$frmId4ActionStatus = "statusFrmId_".$i;
							$appInfoArr = $objDBQuery->getRecord(0, 'appName', 'tbl_apps', array('appCode' => $subcateInfoArr[$i]['appCode_FK']));	
?>
							 <tr>
								<td><?=$srno+1?>.</td>
								<td><?php echo $subcateInfoArr[$i]['subCatName']?></td>
								<td><?php echo $appInfoArr[0]['appName']?></td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/sub-category-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this subcategory?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										<input type="hidden" name="enckey" value="<?php echo $subcateInfoArr[$i]['subCatCode']?>">										
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">
								
									<!----------------------Here EDIT Form Start------------------>
									<form action="add-edit-sub-category.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm btn-success"><small><i class="fa fa-pencil"></i>&nbsp;Edit</small></button>
										<input type="hidden" name="enkey" value="<?php echo $subcateInfoArr[$i]['subCatCode']?>">										
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
				        $UsersCount = $objDBQuery->getRecordCount(0, 'tbl_subcategories', $whereCls, '');     
				        $total_records = $UsersCount;     
				            
				        // Number of pages required.   
				        $total_pages = ceil($total_records / $per_page_record);     
				        $pagLink = "";       
				      
				        if($page>=2){   
				            echo "<a href='view-all-sub-categories.php?page=".($page-1)."&keyword=".$keyword."&status=".$statusfilter."&appCode=".$appCodefilter."&pagination=".$per_page_record."'>  Prev </a>";   
				        }       
				                   
				        for($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++){   
				          if ($i == $page) {   
				              $pagLink .= "<a class = 'active' href='view-all-sub-categories.php?page="  
				                                                .$i."&keyword=".$keyword."&status=".$statusfilter."&appCode=".$appCodefilter."&pagination=".$per_page_record."'>".$i." </a>";   
				          }               
				          else  {   
				              $pagLink .= "<a href='view-all-sub-categories.php?page=".$i."&keyword=".$keyword."&status=".$statusfilter."&appCode=".$appCodefilter."&pagination=".$per_page_record."'>   
				                                                ".$i." </a>";     
				          }   
				        };     
				        echo $pagLink;   
				  
				        if($page<$total_pages){   
				            echo "<a href='view-all-sub-categories.php?page=".($page+1)."&keyword=".$keyword."&status=".$statusfilter."&appCode=".$appCodefilter."&pagination=".$per_page_record."'>  Next </a>";   
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