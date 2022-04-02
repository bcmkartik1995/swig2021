<?php
include_once('web-config.php'); 
include_once('smtp_1.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();
ini_set("display_errors" , 1);
include_once('includes/functions/common.php'); 

// This cron job is fired every 1 minite 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
require 'vendor/autoload.php';
function sendEmailUsingSES($recipient, $subject, $bodyHtml, $senderName, $sender)
{
// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
if ($sender == 'no-reply@swig.tv') $sender = 'sssameer2012@gmail.com';


// Replace smtp_username with your Amazon SES SMTP user name.
$usernameSmtp = 'AKIAZ5LKOGSKFDJXUAXX';
$passwordSmtp = 'BOQvXvWca+gO9OIHbj/CXhSqn3jrIKak3/p0OM5xX6gX';
$configurationSet = 'ConfigSet';
$host = 'email-smtp.us-east-1.amazonaws.com';
$port = 587;

// The subject line of the email
//$subject = 'Amazon SES test (SMTP interface accessed using PHP)';

// The plain-text body of the email
$bodyText =  "Email Test\r\nThis email was sent through the
    Amazon SES SMTP interface using the PHPMailer class.";

// The HTML-formatted body of the email


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
	

	//$recipient
    $mail->addAddress($recipient);
    //$mail->addBCC('dhara@swigmedia.com');    
  
    $mail->isHTML(true);
    $mail->Subject    = $subject;
	//print_r($bodyHtml);
	//echo "<br>";
    $mail->Body       = $bodyHtml;
   // $mail->AltBody    = $bodyText;
  // $mail->addCustomHeader('MIME-version', "1.0");
  $mail->CharSet = 'UTF-8';
    $mail->Send();
	
   echo $recipient." -Email sent!" , PHP_EOL;
	//die;
	return 1;
} catch (phpmailerException $e) {
    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
}










$subM = 'Cron Check';
$mailB = "
<p style='font-size: 14px; font-family: sans-serif'>
Hello,<br><br>
".date('d-m-Y H:i:s')."

Best,<br>
The Chords2Cure Team";


$email = "dhara@swigmedia.com";
sendEmail($email, $subM, $mailB, 'no-reply@chords2cure.tv', 'no-reply@chords2cure.tv', 'HTML');	
						
echo "Swig Manager Cron Job Fired on ".date('d-m-Y H:i:s')."\n ";

//205, 201
?>