<?php
include_once('smtp.php'); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

foreach ($_GET['emails'] as $recepEmail) {
	$emailsent = sendmailUA($recepEmail, 'Ticket code Email', 'TEST - '.$recepEmail.'...');
}
if($emailsent == 1){
	$response = array();
	$response["status"] = true;
	echo json_encode($response);
} else {
	$response = array();
	$response["status"] = false;
	echo json_encode($response);
}



function sendmailUA($recepEmail, $subject, $bodyText)
{
	$host = "smtp.zoho.in";
	$port = 587;
	$EnableSsl = true;
	$usernameSmtp = "no-reply@swigit.com";
	$passwordSmtp = "Dh#239@sw";
	$sender = "no-reply@swigit.com";
	$senderName = "SWIGIT";
	$mail = new PHPMailer(true);

	try {
		// Specify the SMTP settings.
		$mail->isSMTP();
		$mail->setFrom($sender, $senderName);
		$mail->Username   = $usernameSmtp;
		$mail->Password   = $passwordSmtp;
		$mail->Host       = $host;
		$mail->Port       = $port;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'tls';
		
		$mail->addAddress($recepEmail);
		
	  
		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyText;
	 	$mail->CharSet = 'UTF-8';
		$mail->Send();
		return 1;
	} catch (phpmailerException $e) {
		//echo  "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
		$response["status"] = false;
		$response["msg"] = "An error occurred. {$e->errorMessage()}";
		echo json_encode($response);
	} catch (Exception $e) {
		//echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
		$response = array();
		$response["status"] = false;
		$response["msg"] = "Email not sent. {$mail->ErrorInfo}";
		echo json_encode($response);
	}	
}
?> 


