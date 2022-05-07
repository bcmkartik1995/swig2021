<?php	

	$SUBTITLE = 'Manage users';
	include("includes/header.php");

	$keyword = getValPostORGet('keyword', 'B');
	$status = getValPostORGet('status', 'B');
	$appCode = getValPostORGet('appCode', 'B');

	$whereCls = "1";

	$accountType = $_SESSION['userDetails']['accountType'];	
	
	if ($accountType != 'S'){
		$appCodes = $_SESSION['userDetails']['appCodes'];
		$whereCls .= " AND accountType = 'A' AND appCodes = '".$appCodes."'";
	}

	$per_page_record = 10;  // Number of entries to show in a page.   
        // Look for a GET variable page if not found default is 1.        
    if (isset($_GET["page"])) {    
        $page  = $_GET["page"];    
    }    
    else {    
      $page=1;    
    }    

    $start_from = ($page-1) * $per_page_record;     

	
	$UsersInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_users', $whereCls, $start_from, $per_page_record, 'userId_PK', 'ASC');	

	
	$_SESSION['SESSION_QRY_STRING_FOR_SUB_CATE'] = "keyword=$keyword&status=$status&appCode=$appCode";
?>
<!-- Start of content -->
<div class="app-body">
	<div class="padding">
		<?php include_once('includes/flash-msg.php'); ?>

        <div class="box">
			<!-- Start of header area-->
			
            <div class="box-header dker">
                <h3>View All users</h3>
					<a href="add-edit-users.php" class="view-all"><i class="material-icons">&#xe02e;</i>Add New User</a>
            </div>
			<!-- End of header area-->
	
			<!-- Start of table responsive -->
            <div class="table-responsive">
				<table class="table table-striped testimonial_table">
					<thead>
					<tr>
						<th class="width80">Sr. No.</th>
						<th>Username</th>
						<th>Email</th>
						<th class="text-center width202">Status</th>
						<th class="text-center width202">Action</th>
					</tr>
					</thead>
					<tbody>
                <?php
					if (is_array($UsersInfoArr) && !empty($UsersInfoArr))
					{
						$numOfRows = count($UsersInfoArr);
						//danger
						$srno = $start_from;
						for ($i = 0; $i < $numOfRows; $i++)
						{
							$status = $UsersInfoArr[$i]['accountStatus'];
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
								<td><?php echo $UsersInfoArr[$i]['username']; ?></td>
								<td><?php echo $UsersInfoArr[$i]['email']?></td>
								<td class="text-center">
									<!----------------------Here CHANGE STATUS Form Start------------------>
									<form action="controller/users-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionStatus?>">	
										<btn class="button_status" type="button" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionStatus?>', ' Change Status', 'Are you sure you want to change status this user?');"><i class="fa fa-circle <?php echo $statusCls?> inline" title="<?php echo $statusTxt?>"></i></btn>
										<input type="hidden" name="enckey" value="<?php echo $UsersInfoArr[$i]['userId_PK']?>">										
										<input type="hidden" name="postAction" value="changeRecordStatus">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
									</form>	
								</td>
								<td class="text-center">
								
									<!----------------------Here EDIT Form Start------------------>
									<form action="add-edit-users.php" method="post" class="form-inline">							
										<button type="submit" class="btn btn-sm btn-success"><small><i class="fa fa-pencil"></i>&nbsp;Edit</small></button>
										<input type="hidden" name="enkey" value="<?php echo $UsersInfoArr[$i]['userId_PK']?>">										
									</form>

									<!----------------------Here DELETE Form Start------------------>
									<form action="controller/users-controller.php" method="post" class="form-inline" id="<?php echo $frmId4ActionDelete?>">		
										<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmation_modal" ui-toggle-class="bounce" ui-target="#animate" onClick="javascript:setPopupContent('<?php echo $frmId4ActionDelete?>', ' Deletion', 'Are you sure you want to permanently delete this user?');">
											<small><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Delete</small>
										</button>
										<input type="hidden" name="enckey" value="<?php echo $appsInfoArr[$i]['menuCode']?>">
										<input type="hidden" name="postAction" value="deleteRecordAction">
										<input type="hidden" name="formToken" value="<?php echo $_SESSION['prepareToken']; ?>">
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
		</div>
	</div>
</div>

<div class="padding" style="padding-top: 0px;">
	
	<div class="pagination">    
	      <?php  
	        $UsersCount = $objDBQuery->getRecordCount(0, 'tbl_users', $whereCls, '');     
	        $total_records = $UsersCount;     
	            
	        // Number of pages required.   
	        $total_pages = ceil($total_records / $per_page_record);     
	        $pagLink = "";       
	      
	        if($page>=2){   
	            echo "<a href='users.php?page=".($page-1)."'>  Prev </a>";   
	        }       
	                   
	        for($i = max(1, $page - 5); $i <= min($page + 5, $total_pages); $i++){   
	          if ($i == $page) {   
	              $pagLink .= "<a class = 'active' href='users.php?page="  
	                                                .$i."'>".$i." </a>";   
	          }               
	          else  {   
	              $pagLink .= "<a href='users.php?page=".$i."'>   
	                                                ".$i." </a>";     
	          }   
	        };     
	        echo $pagLink;   
	  
	        if($page<$total_pages){   
	            echo "<a href='users.php?page=".($page+1)."'>  Next </a>";   
	        }   
	  
	      ?>    
	</div> 
</div> 
  
  <!-- 
      <div class="inline">   
      <input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
      placeholder="<?php echo $page."/".$total_pages; ?>" required>   
      <button onClick="go2Page();">Go</button>   
     </div>     -->

<!-- End of content -->
<!-- Start of footer -->
<?php 
	include("includes/footer.php")
?>
<!-- End of footer-->
</div>
<!-- Start of main content -->