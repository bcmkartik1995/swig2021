<?php
// the message
$msg = "First line of text Second line of text";

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';


//$to = 'dhara.amish@gmail.com';
$to = 'vkvia6@gmail.com';
$subject = 'Swig app - Subject';

$success = mail($to, $subject, $msg, implode("\r\n", $headers));
print_r("Testing");
if ($success) {
	echo "Sent";
   
}
else
{
	$errorMessage = error_get_last()['message'];
	print_r($errorMessage);
	echo "error";
}
print_r($headers);


?> 
