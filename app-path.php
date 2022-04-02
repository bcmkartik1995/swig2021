<?php
if (empty($_SERVER['SERVER_NAME']))  
{
	$_SERVER['SERVER_NAME'] = '10.1.1.556';
}
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '10.1.1.5')
{
	define('HTTP_PATH', 'http://10.1.1.5/vijay/swig');
	define('HARD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/vijay/swig');
	//ini_set('display_errors', 0);
} 
else
{
	//define('HTTP_PATH', 'http://ec2-3-80-207-244.compute-1.amazonaws.com');
	define('HTTP_PATH', 'https://www.swigappmanager.com');	
	define('HARD_PATH', $_SERVER['DOCUMENT_ROOT'] . '');
	//define('HTTP_PATH', 'http://fusioniprojects.com/swig');
	//define('HARD_PATH', $_SERVER['DOCUMENT_ROOT'] . 'swig');
	ini_set('display_errors', 0);
}
?>