<?php
ini_set("display_errors", "0");
include_once('../../../../web-config.php'); 
//include_once('smtp.php'); 
include_once('../../../../includes/classes/DBQuery.php'); 
include_once('../../../../includes/functions/common.php'); 
require '../../../../vendor/autoload.php';
//namespace PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$objDBQuery = new DBQuery();

cors();

$whereArr = array();

//responses(0, "wrong");
//print_r($_REQUEST);
//echo "dd";
//$_POST = trimFormValue(0, $_POST);
$ipAdd = getRealIpAddr();
$appName = $_REQUEST['appName'];
$FromEmail = $_REQUEST['from_email'];
$FromName = $_REQUEST['from_name'];
$message = $_REQUEST['email_message'];
		
	if($FromEmail != "")
	{
   // echo "inn";
			//$_POST = trimFormValue(0, $_POST);
			//$appCode = $_REQUEST['appId'];
			//$ticketCode = strtoupper($_REQUEST['ticketCode']);		
					
			
					$bodyText = '<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#EEE" style="color:rgb(0,0,0);border:1px solid rgb(221,221,221)">
				<tbody>
						<tr>
						<td style="font-family:Arial,Helvetica,sans-serif;padding:15px;font-size:12px;line-height:1.3;text-align:justify">
								<b>SWIGIT Contact Us Request From:</b>
								<br>
								<br>
                                Application: '.$appName.' <br>
                                Email Address: '.$FromEmail.' <br>
                                Name: '.$FromName.' <br> 
                                IpAddress: '.$ipAdd.' <br>
                                On Date: '.date('Y-m-d H:i:s').'<Br>
                                Message: '.$message.'

							
								<br><br><br>From,
								<br> SWIGIT Team 
								<br>
							</td>
						</tr>
				</tbody>
				</table>';	
			   
					$sentMail = sendmailUA($FromEmail, "SWIGIT - Contact Us Request", $bodyText);
					//echo "show".$sentMail;
					if($sentMail)
						$rtnRes = array('status' => 1, 'msg' => "Support email has been sent.");	
					else
						$rtnRes = array('status' => 0, 'msg' => "Oops, something went wrong, please check your Email Address and try again.");	
				
			
			
			// Invalid Request Method
		//	header("HTTP/1.0 405 Method Not Allowed");
		//	break;
	}
	else $rtnRes = array('status' => 0, 'msg' => "Oops, something went wrong, please check your Email Address and try again.");
	//responses(0, $rtnRes);



responses(0, $rtnRes);

function sendmailUA($recepEmail, $subject, $bodyText)
{
	
//echo " now inside";
// Replace smtp_password with your Amazon SES SMTP password.

	//$host = 'smtp.zoho.in';
	//$port = 587;
	$EnableSsl = true;
	//$usernameSmtp = 'no-reply@swigit.com';
	//$passwordSmtp = 'Dh\#239\@sw';
	$sender = 'no-reply@swigit.com';
	$senderName = 'SWIGIT Support';

    $usernameSmtp = 'AKIAZ5LKOGSKFDJXUAXX';
    $passwordSmtp = 'BOQvXvWca+gO9OIHbj/CXhSqn3jrIKak3/p0OM5xX6gX';
    $configurationSet = 'ConfigSet';
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
		$mail->addAddress('dhara@swigmedia.com');
		//$mail->addBCC('dhara@swigmedia.com'); 
    	//$mail->addBCC('ivan@swigmedia.com'); 

		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyText;
	 	$mail->CharSet = 'UTF-8';
		$mail->Send();
		
	
	 //  echo $recipient." -Email sent!" , PHP_EOL;
		//die;
		return PHP_EOL;
	} catch (phpmailerException $e) {
		echo  "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
		return 0;
	} catch (Exception $e) {
		echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
		return 0;
	}	
}

?>