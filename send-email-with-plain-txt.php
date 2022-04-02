 <?php
// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
$res = mail("vkvia6@gmail.com,vijay@fusionitechnologies.com","My subject",$msg);
if ($res == 1) echo "Email has been sent";
else echo "Email does not send!!"; 
?> 