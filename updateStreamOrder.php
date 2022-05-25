<?php
//echo "<pre>";print_r($_POST);die;
    include_once('./web-config.php'); 
    include_once('includes/classes/DBQuery.php');
    $objDBQuery = new DBQuery();

    include_once('includes/functions/common.php'); 

    $array = $_POST['arrayorder'];
 
    if ($_POST['update'] == "update"){
      
        $count = 1;
        foreach ($array as $idval) {
            $updatedId = $objDBQuery->updateRecord(0, array('streamOrder' =>  $count), 'tbl_streams', array('streamId_PK' => $idval));
            
            $count ++; 
        }
        echo 'All saved! refresh the page to see the changes';
    }
?>