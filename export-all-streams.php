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

    //echo "<pre>";print_r($streamsInfoArr);die;
    //file creation 
    $file = fopen('php://output', 'w');

    
    // generate dynamic header based on system languages
    // .......... START ............. 
    $header = array("streamTitle","streamPermalink","streamUrl","stream_logo","stream_logo_url","linearStreamDaiKey","linearStreamPlayingMethod", "streamImgPath","streamThumbnail","description","staring","streamTrailerUrl","duration","director","writter","editor","producer","genre","language","awards","rating","review","streamOrder","status");

    // .......... END ............. 

    fputcsv($file, $header);
    $delimiter = ","; 
    foreach ($streamsInfoArr as $key=>$line){

        if ($line["streamImg"] !='') $streamImgPath = HTTP_PATH."/uploads/stream_images/".$line["streamImg"];
        else $streamImgPath = HTTP_PATH."/images/default_stream_poster.jpg";

         $lineData = array($line["streamTitle"],$line["streamPermalink"],$line["streamUrl"],$line["stream_logo"],$line["stream_logo_url"], $line["linearStreamDaiKey"],$line["linearStreamPlayingMethod"],$streamImgPath, $line["streamThumbnail"], $line["streamdescription"], $line["staring"],$line["streamTrailerUrl"],$line["streamDuration"],$line["directedBy"],$line["writtenBy"],$line["stream_editor"],$line["producedBy"],$line["genre"],$line["language"],$line["awards"],$line["rating"],$line["review"],$line["streamOrder"],$line["status"]);


        fputcsv($file, $lineData, $delimiter); 
    }

    // fclose($file); 
    // exit; 
?>