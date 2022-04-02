<?php
ini_set("display_errors", "0");
include_once('includes/classes/DBQuery.php');
//include_once('../swigappmanager.com/smtp_1.php'); 
include_once('includes/functions/common.php'); 
require 'vendor/autoload.php';
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



        
       			
$subject = "DANCESTAR TV...'FEEL THE MUSIC'";
$bodyTextUser = '
            
            <div style="width: 100%; text-align: center;">
                <div style="width: 100%; text-align: center;">
                <p class="MsoNormal"><b>
                <span style="font-size:13.5pt;font-family:\'Verdana\',sans-serif">
                    <span style="color:red">COME HOME.</span>
                </span></b>
                </p>
                </div>
                
                <div  style="width: 100%;  text-align: center;">
                    <p class="MsoNormal">
                        <span style="font-size:12.0pt;font-family:\'Verdana\',sans-serif">
                            Introducing the new address for Electronic Music... streaming 24/7 on all connected devices.</span>
                    </p>
                </div>
			
                <div  style="width: 100%;  text-align: center;">
                    <p class="MsoNormal">
                        <span style="font-size:12.0pt;font-family:\'Verdana\',sans-serif"><b>
                            DANCESTAR TV...\'FEEL THE MUSIC\'</b></span>
                        
                    </p>
                        
                    <p class="MsoNormal">
                        <a href="https://dstv.swigit.com/livechannel/dstv" target="_blank" ><span 
                            style="font-size:12.0pt;font-family:\'Arial\',sans-serif">https://dstv.swigit.com/livechannel/dstv</span></a>
                            
                        </p>
                </div>
                <div  style="width: 100%;  text-align: center; ">
                    <div  style="width: 49%;  text-align: center; float: left; ">
                        <p class="MsoNormal">
                            <span style="font-size:12.0pt;font-family:\'Arial\',sans-serif"><b>Click</b></span></p>

                        <p class="">
                            <span>
                                <a href="https://dstv.swigit.com/livechannel/dstv" target="_blank" ><img 
                                    src="https://imagescdn2.swigit.com/LiveChannels/Logo/dstv" width="50%"></a>
                            </span>
                        </p>
                    </div >
                    <div  style="width: 50%;  text-align: center; float: right;  ">
                        <p class="MsoNormal">
                            <span style="font-size:12.0pt;font-family:\'Arial\',sans-serif"><b>Scan</b></span></p>
                        <p class="">
                            <span style="font-size:12.0pt;font-family:\'Arial\',sans-serif">
                                <img border="0" width="116" height="120" style="width:1.2083in;height:1.25in" 
                                
                                src="https://imagescdn2.swigit.com/Images/Clients/dstv/dancestar_qr.png" >    
                            </span>
                        </p>
                        </div>

                </div>
            
            </div>
           ';
				
				 

//if($_REQUEST['EmailAddress'] != "")
//{
//sendmailUA($_REQUEST['EmailAddress'], $subject, $bodyTextUser, 'U');
sendmailUA('dhara.amish@gmail.com', $subject, $bodyTextUser, 'A');
//}
			
$resObject = array(
'insertStatus' => $insertStatus,
'errorMsg' => $errMsg);
//response($resObject);

echo "done";


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
	$senderName = "DANCESTAR SWIGIT";
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
		
		$mail->addAddress("ivan@swigmedia.com");
		
		
			$mail->addBCC('dhara@swigmedia.com'); 
		
	 
	  
		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyText;
	 	$mail->CharSet = 'UTF-8';
		$mail->Send();
		
	
	   echo $recipient." -Email sent!" , PHP_EOL;
		die;
		return 1;
	} catch (phpmailerException $e) {
		echo  "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
		echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}	
}


?>