<?php
$CUR_PAGE_NAME = basename($_SERVER['SCRIPT_NAME']);
include_once('./web-config.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();

include_once('includes/functions/common.php'); 
include_once('includes/functions/form-validation.php');

/**  
*	Use this value in each form as hidden field(formToken)
*	and check on controller page that request is valid or not
**/
$_SESSION['prepareToken'] = randomMD5();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="keyword" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo ADMIN_PANEL_TITLE?><?php echo @$SUBTITLE?></title>
    <link rel="icon" href="<?php echo HTTP_PATH?>/images/favicon.ico">
    <link rel="stylesheet" href="<?php echo HTTP_PATH?>/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo HTTP_PATH?>/css/bootstrap-select.css">
	<link rel="stylesheet" href="<?php echo HTTP_PATH?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo HTTP_PATH?>/css/style.css">
	<link rel="stylesheet" href="<?php echo HTTP_PATH?>/css/bootstrap-colorpicker.css">
</head>
<body>
