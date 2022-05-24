<?php 

    include_once('./web-config.php'); 
    include_once('includes/classes/DBQuery.php');
    $objDBQuery = new DBQuery();

    include_once('includes/functions/common.php'); 
    //file name 
    $filename = 'streams_'.date('Ymd').'.csv'; 
    header("Content-Description: File Transfer"); 
    header("Content-Disposition: attachment; filename=$filename"); 
    header("Content-Type: application/csv; ");

    // get data 
    $menuCode = $_GET['menuCode'];
    $whereCls = "menuCode_FK = '$menuCode' AND subCatCode = subCatCode_FK AND isDeleted = 'N'";
    $orderBY = "s.isStreamFeatured = 'Y' DESC, s.streamOrder ASC, sub.subCatName ASC, s.createdOn DESC";
    $streamsInfoArr = $objDBQuery->getRecord(0, array('*'), 'tbl_streams s, tbl_subcategories sub', $whereCls, '', '', $orderBY, '');

    //file creation 
    $file = fopen('php://output', 'w');

    
    // generate dynamic header based on system languages
    // .......... START ............. 
    $header = array("id","streamTitle","streamDuration","subCategory");

    // .......... END ............. 

    fputcsv($file, $header);
    $delimiter = ","; 
    foreach ($streamsInfoArr as $key=>$line){

         $lineData = array($line["streamId_PK"],$line["streamTitle"],$line["streamDuration"],$line["subCatName"]);


        fputcsv($file, $lineData, $delimiter); 
    }

    // fclose($file); 
    // exit; 
?>