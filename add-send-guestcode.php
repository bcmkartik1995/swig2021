<?php
include_once('web-config.php'); 
//include_once('smtp.php'); 
include_once('includes/classes/DBQuery.php'); 
include_once('includes/functions/common.php'); 
require '../swigappmanager.com/vendor/autoload.php';
//namespace PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$objDBQuery = new DBQuery();
ini_set("display_errors", "0");
cors();
$tblName = 'tbl_master_codes';
$arrFld = array('name', 'userCode', 'username', 'appCode_FK AS appId', 'email', 'accountStatus', 'createdOn', 'updatedOn');
$whereArr = array();


//print_r($_REQUEST);

$ipAdd = getRealIpAddr();
$app_code = $_REQUEST['appId'];
$guestEmail = $_REQUEST['emailAddress'];
$postAction = $_REQUEST['postAction'];
//print_r($_REQUEST);

if($postAction == "validateGuest")
{
		
	if($guestEmail != "")
	{
			//$_POST = trimFormValue(0, $_POST);
			$appCode = $_REQUEST['appId'];
			//$ticketCode = strtoupper($_REQUEST['ticketCode']);		
					
			if(!$objDBQuery->getRecordCount(0, 'tbl_guest_directaccess', array('appCode_FK' => $appCode, 'guest_email' => $guestEmail)))
			{
				$arrInsert = array('guest_email' => $guestEmail, 'appCode_FK' => $appCode,'email_sent' => 'Y');
				$objDBQuery->addRecord(0, $arrInsert, 'tbl_guest_directaccess');
							
				$masterInfoArray = $objDBQuery->getRecord(0, array('masterCode'), 'tbl_master_codes', array('appCode_FK' => $appCode));
				if (is_array($masterInfoArray) && !empty($masterInfoArray))
				{
					$mastCode = $masterInfoArray[0]['masterCode'];	
               // echo "dd";
					$bodyText = '<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#EEE" style="color:rgb(0,0,0);border:1px solid rgb(221,221,221)">
				<tbody>
						<tr>
						<td style="font-family:Arial,Helvetica,sans-serif;padding:15px;font-size:12px;line-height:1.3;text-align:justify">
								<b>‘ANGIE: LOST GIRLS’</b>
								<br>
								<br>
							
																
								Sunday, December 20th 2020, at 2:00pm PST <br><BR>
								Your GUEST TICKET CODE: '. $mastCode.'<br><br>
								Please return to this destination 
								<a href="https://freestyleguest.swigit.com/" target="_blank">https://freestyleguest.swigit.com</a>
								 on Sunday, December 20th 2020, at 1:50 pm PST to Watch and Participate in this event. <br><br>
								 								 
								Thank you for caring and being interested in seeing this film. <br>
								More details on the issue and our non profit can be found at www.artists4change.org 
								
								<br><br><br>From,
								<br> FREESTYLE Team 
								<br>
							</td>
						</tr>
				</tbody>
				</table>';	
			   
					$sentMail = sendmailUA($guestEmail, "FREESTYLE - ANGIE: LOST GIRLS - GUEST CODE", $bodyText);
					
					if($sentMail)
						$rtnRes = array('status' => 1, 'msg' => "Ticket Code has been sent to your Email Address.", 'masterCode' => $mastCode);	
					else
						$rtnRes = array('status' => 0, 'msg' => "Oops, something went wrong, please check your Email Address and try again.", 'masterCode' => $mastCode);	
				}
				else $rtnRes = array('status' => 0, 'msg' => 'No code assigned to this Event.');
				
			}
			else $rtnRes = array('status' => 0, 'msg' => ' Guest Email Exists.');
			
			
			// Invalid Request Method
		//	header("HTTP/1.0 405 Method Not Allowed");
		//	break;
	}
	else $rtnRes = array('status' => 0, 'msg' => 'No Guest Email provided.');
	//responses(0, $rtnRes);

}
else $rtnRes = array('status' => 0, 'msg' => 'no post action');
responses(0, $rtnRes);

function sendmailUA($recepEmail, $subject, $bodyText)
{
	

// Replace smtp_password with your Amazon SES SMTP password.

	$host = "email-smtp.us-east-1.amazonaws.com";
	$port = 587;
	$EnableSsl = true;
	$usernameSmtp = "AKIAZ5LKOGSKFDJXUAXX";
	$passwordSmtp = "BOQvXvWca+gO9OIHbj/CXhSqn3jrIKak3/p0OM5xX6gX";
	$sender = "no-reply@swigit.com";
	$senderName = "Freestyle SWIGIT";
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
		//$mail->addBCC('dhara@swigmedia.com'); 
    	//$mail->addBCC('ivan@swigmedia.com'); 

		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyText;
	 	$mail->CharSet = 'UTF-8';
		$mail->Send();
		
	
	  // echo $recipient." -Email sent!" , PHP_EOL;
		//die;
		return 1;
	} catch (phpmailerException $e) {
		echo  "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
		return 0;
	} catch (Exception $e) {
		echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
		return 0;
	}	
}


?>