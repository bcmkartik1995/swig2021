<?php
ini_set("display_errors", "0");
include_once('../swigappmanager.com/includes/classes/DBQuery.php');
//include_once('../swigappmanager.com/smtp_1.php'); 
include_once('../swigappmanager.com/includes/functions/common.php'); 
require '../swigappmanager.com/vendor/autoload.php';
//namespace PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
ini_set("display_errors", "0");

$objDBQuery = new DBQuery();
cors();
//echo "innnooooo";

$cust_data = $_REQUEST;
//print_r($cust_data);
$whereArr = array();

//$app_code = $_REQUEST['appCode'];
//print_r($_REQUEST);

$custArrayInsert = array(
'book_amount' => $_REQUEST['payment_amount'], 
'cust_name' => $_REQUEST['Name'],
'cust_email' => $_REQUEST['EmailAddress'], 
'cust_address1' => $_REQUEST['AddressLine1'],
'cust_country' => $_REQUEST['country_name'], 
'cust_state' => $_REQUEST['state_name'],
'cust_city' => $_REQUEST['City'], 
'payment_provider' => $_REQUEST['payment_type'], 
'payment_reference' => $_REQUEST['payment_reference'],
'cust_zip' => $_REQUEST['zip_code'],
'cust_address2' => $_REQUEST['AddressLine2'], 
'cust_country_code' => $_REQUEST['country_phone_code'],
'cust_phone' => $_REQUEST['Phone'],
'type_of_inquiry' => 'S'    // SINGLE BOOK PURCHASE
);

$insertStatus = false;
 	
try {
 $objDBQuery->addRecord(0, $custArrayInsert, 'tbl_bookpurchase_request');
 $insertStatus = true;
  $errMsg = "";
}
catch(Exception $e) {
  $errMsg = $e->getMessage();
  $insertStatus = false;
}


        
       			
$subject = "Aphrodisiacs Adventures Purchase Confirmation";
$bodyTextUser = '
				<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#EEE" style="color:rgb(0,0,0);border:1px solid rgb(221,221,221)">
				<tbody>
						<tr>
						<td style="font-family:Arial,Helvetica,sans-serif;padding:15px;font-size:12px;line-height:1.3;text-align:justify">
								<b>‘Aphrodisiac Adventures’</b>
								<br>
								<br>
								Thank you for buying this everlasting book. 
								<br>
								The book will be shipped to the following address soon.
								<br><br>
								
								Shipping Address:<br>'.
								$_REQUEST['AddressLine1'].'<br>'.
								$_REQUEST['AddressLine2'].'<br>
								City: '.$_REQUEST['City'].'<br>
								State: '.$_REQUEST['state_name'].'<br>
								Country: '.	$_REQUEST['country_name'].'<br>
								Zip Code: '.$_REQUEST['zip_code'].'<br>
								Phone No: '.$_REQUEST['Phone'].'<br>
								Country Code: '.$_REQUEST['country_phone_code'].'<br><br>
								
								Payment Done From: '. $_REQUEST['payment_type'].'<br>
								Amount Paid: $'.$_REQUEST['payment_amount'].'<br><Br>
								
								Enjoy Reading, Have Fun and Start Your Journey to “Health, Long Life & Pleasures”! 
								<br>

								<br><br><br>Your Aphrodisiacs Expert,
								<br> Lillian Zeltser
								<br>
								<a href="www.aphrodisiacsexpert.com" >www.aphrodisiacsexpert.com</a>

						</td>
						</tr>
				</tbody>
				</table>';
				
				 

				
$bodyTextAuthor = '<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#EEE" style="color:rgb(0,0,0);border:1px solid rgb(221,221,221)">
				<tbody>
						<tr>
						<td style="font-family:Arial,Helvetica,sans-serif;padding:15px;font-size:12px;line-height:1.3;text-align:justify">
								<b>‘Aphrodisiac Adventures’</b>
								<br>
								<br>
								Purchase Request from:
								<br>
																
								Customer Name: '.$_REQUEST['Name'].'<br>
								Customer Email: '. $_REQUEST['EmailAddress'].'<br><br>
								Shipping Address:<br>'.
								$_REQUEST['AddressLine1'].'<br>'.
								$_REQUEST['AddressLine2'].'<br>
								City: '.$_REQUEST['City'].'<br>
								State: '.$_REQUEST['state_name'].'<br>
								Country: '.	$_REQUEST['country_name'].'<br>
								Zip Code: '.$_REQUEST['zip_code'].'<br>
								Phone No: '.$_REQUEST['Phone'].'<br>
								Country Code: '.$_REQUEST['country_phone_code'].'<br><br>
								
								Payment Done From: ' .$_REQUEST['payment_type'].'<br>
								Amount Paid: $'.$_REQUEST['payment_amount'].'
								
								<br><br><br>From,
								<br> SWIGIT Admin
								<br>
							</td>
						</tr>
				</tbody>
				</table>';				
	

sendmailUA($_REQUEST['EmailAddress'], $subject, $bodyTextUser, 'U');
sendmailUA('mail@aphrodisiacsexpert.com', $subject, $bodyTextAuthor, 'A');

			
$resObject = array(
'insertStatus' => $insertStatus,
'errorMsg' => $errMsg);
response($resObject);

//echo "done";


function response($resObject){
 //$response['event_datetime'] = $eventStDateTime;
 //$response['event_timezone'] = $timezoneOffset;
 
 $json_response = json_encode($resObject);
 echo $json_response;
}


function sendmailUA($recepEmail, $subject, $bodyText, $emailType)
{
	$host = "smtp.zoho.in";
	$port = 587;
	$EnableSsl = true;
	$usernameSmtp = "no-reply@swigit.com";
	$passwordSmtp = "Dh#239@sw";
	$sender = "no-reply@swigit.com";
	$senderName = "Aphrodisiacs Expert";
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
		
		if($emailType == 'U')
		{
			$mail->addBCC('dhara@swigmedia.com'); 
		}
		if($emailType == 'A')
		{
			$mail->addBCC('ivan@swigmedia.com'); 
			//	 $mail->addBCC('ivan@swigmedia.com');
		}
	 
	  
		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyText;
	 	$mail->CharSet = 'UTF-8';
		$mail->Send();
		
	
	  // echo $recipient." -Email sent!" , PHP_EOL;
		//die;
		//return 1;
	} catch (phpmailerException $e) {
		echo  "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
		echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}	
}


?>