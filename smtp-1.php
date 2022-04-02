<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
function sendEmailUsingSES($recipient, $subject, $bodyHtml, $senderName, $sender)
{	
	// This address must be verified with Amazon SES.
	if ($sender == 'no-reply@swig.tv') $sender = 'no-reply@swigit.com';	

	// Replace recipient@example.com with a "To" address. If your account
	//$recipient = 'vkvia6@gmail.com';

	// Replace smtp_username with your Amazon SES SMTP user name.
	$usernameSmtp = 'AKIAZ5LKOGSKFDJXUAXX';

	// Replace smtp_password with your Amazon SES SMTP password.
	$passwordSmtp = 'BOQvXvWca+gO9OIHbj/CXhSqn3jrIKak3/p0OM5xX6gX';

	// Specify a configuration set. If you do not want to use a configuration
	// set, comment or remove the next line.
	$configurationSet = 'ConfigSet';

	// If you're using Amazon SES in a region other than US West (Oregon),
	// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
	// endpoint in the appropriate region.
	$host = 'email-smtp.us-east-1.amazonaws.com';
	$port = 587;

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


		// Specify the message recipients.
		$mail->addAddress($recipient);
		$mail->addBCC('dhara@swigmedia.com');    
		// You can also add CC, BCC, and additional To recipients here.

		// Specify the content of the message.
		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyHtml;
		$mail->CharSet = 'UTF-8';
		$mail->Send();
		return 1;
	} 
	catch (phpmailerException $e)
	{
		echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
		return 0;
	}
	catch (Exception $e) 
	{
		echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
		return 0;
	}
}

?>

