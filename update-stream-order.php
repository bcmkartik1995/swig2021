<?php
include_once('web-config.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();
include_once('includes/functions/common.php');

echo "<pre>";

updateStreamOrder($objDBQuery);