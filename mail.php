<?php

$to = "sssameer2012@gmail.com,vijay@fusionitechnologies.com";

$subject = "HTML email4";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <vkvia6@gmail.com>' . "\r\n";
$headers .= 'Cc: vkvia6@gmail.com' . "\r\n";

$res = mail($to,$subject,$message);

print_r($res);


function sendEmail($to, $subject, $body, $fromName, $from, $format = '')
{
	$headers = '';
	$url = HTTP_PATH . '/images/mail-header.jpg';

	if($format=='HTML')
	{
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	}

	$headers .= "From: $fromName <$from>" . "\n";
	$headers .= "Cc: " . "\n";
	$headers .= "Bcc: " . "\n";
	//<img src='{$url}'>
	$body = "<center>
				<table width='100%' cellpadding='0' cellspacing='0' bgcolor='#EEE' style='color: #000000; text-align:left; border: 1px solid #ddd;'>
				<tr>
					<td style='padding:15px 15px 15px 15px; font-size: 12px; color: #000000; line-height:1.3; text-align:justify; font-family: Arial,Helvetica,sans-serif;'>" . $body . "<td>
				</tr>
				</table>
			</center>";

	if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '10.1.1.5')
	{
		$str = "<font face='arial' size='2'><b>To Email:</b> $to<br><br><b>Subject:</b> $subject<br><br><b>From:</b> $fromName<br><br><b>From Email:</b> $from<br><br>$body</font>";
		$mailDir = HARD_PATH . '/mail';

		$fp = fopen($mailDir . '/mail_' . date('U') . '_' . rand(10000, 99999) . '.html', 'w');
		fwrite($fp, $str);
		fclose($fp); 
	} 
	else
	{
		$success = mail($to, $subject, $body, $headers, '-f ' . NO_REPLY_EMAIL);
		return $success;
	}
}


?>
