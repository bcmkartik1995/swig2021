<?php
ini_set("display_errors", "1");

include_once('../../../../includes/functions/common.php'); 
require '../../../../vendor/autoload.php';
//namespace PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



cors();

//$toEmail = "dhara.amish@gmail.com";
//$toEmail = "drgulas@gmail.com";
//$toEmail = "dhara@swigmedia.com";
$toEmail = "ivan@swigmedia.com";
		//	echo "ewrwerwr";		
			
    $bodyText = '<div dir="ltr"><br><br>
    <div class="gmail_quote">
        
        <div bgcolor="#e4e4e4" marginheight="0" marginwidth="0" style="width:100%!important;background:#e4e4e4">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#e4e4e4">
                <tbody>
                    <tr>
                        <td bgcolor="#e4e4e4" width="100%">
                            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                                <tbody>
                                    <tr>
                                        <td width="600"><img border="0" src="http://www.swigit.com/images/header.png"
                                                label="Hero image" width="600" id="m_7640133754890689166screenshot">
                                            <table width="600" cellpadding="25" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#456265" width="600"
                                                            style="text-align:center;color:#fff"
                                                            class="m_7640133754890689166promocell">
                                                            <h1>Celebration: Broadway Jewish Composers</h1>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15">
                                            <table width="600" cellpadding="25" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#456265" width="600"
                                                            style="text-align:center;color:#fff"
                                                            class="m_7640133754890689166promocell">
                                                            <h1>SWIGIT COMPLETION LETTER &amp; INSTRUCTIONS</h1>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#85bdad" nowrap><img border="0"
                                                                src="http://images/spacer.gif" width="5" height="1">
                                                        </td>
                                                        <td width="100%" bgcolor="#ffffff">
                                                            <table width="100%" cellpadding="20" cellspacing="0"
                                                                border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td bgcolor="#ffffff"
                                                                            class="m_7640133754890689166contentblock">
                                                                            <u></u>
                                                                            <p> Dear Client, </p>
                                                                            <p> Your Swigit is now ready for your
                                                                                marketing, Social Network Film
                                                                                Distribution and monetization. We are
                                                                                sending you the SWIGIT in three
                                                                                different formats.</p>
                                                                            <p>The first option allows you to paste a
                                                                                "Poster SWIGIT" into e-mails,
                                                                                tweets, social network destination which
                                                                                accepts hyperlinked images. This option
                                                                                is particularly useful in mass e-mails.
                                                                            </p>
                                                                            <p>The second option is a short one line
                                                                                SWIGIT code. You can also copy and paste
                                                                                this code and use it the same way as
                                                                                above. This second option is ideal to
                                                                                post on destinations which do not accept
                                                                                hyperlinked images, but will accept a
                                                                                JPEG image. Twitter, for example will
                                                                                allow you to upload a JPEG image of your
                                                                                poster along with this one line code.
                                                                                The combination of a JPEG image (which
                                                                                you can create from the poster) and this
                                                                                link will accomplish the same as the
                                                                                hyperlinked poster above.</p>
                                                                            <p>The next two option, while quite easy to
                                                                                implement does require some website
                                                                                management skills of the help of your
                                                                                webmaster. Both are ideal options for
                                                                                enabling the website to become a media
                                                                                destination for your content, with all
                                                                                associated functions (including
                                                                                transactions), very quickly and with
                                                                                minimal effort.</p>
                                                                            <p>Please refer to HOW TO SWIGIT for
                                                                                additional instructions.</p>
                                                                            <p></p><u></u>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#ef3101" nowrap><img border="0"
                                                                src="http://images/spacer.gif" width="5" height="1">
                                                        </td>
                                                        <td width="100%" bgcolor="#ffffff">
                                                            <table width="100%" cellpadding="20" cellspacing="0"
                                                                border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td bgcolor="#ffffff"
                                                                            class="m_7640133754890689166contentblock">
                                                                            <h4><strong><u></u>POSTER SWIGIT: If you
                                                                                    click on the poster below, you will
                                                                                    activate the film&#39;s One-Click to
                                                                                    PPV Solution.<u></u></strong></h4>
                                                                            <p style="font-style:italic">Highlight the
                                                                                Poster below, then Copy (CTRL+C or CMD+C
                                                                                on Macs) and Paste (CTRL+V or CMD+V on
                                                                                Macs) the SWIGIT in e-mails and on any
                                                                                other social network destinations which
                                                                                allows hyperlinked images.</p><u></u>
                                                                            <p><a href="https://celeb.swigit.com/"
                                                                                    target="_blank"><img
                                                                                        src="https://www.swigappmanager.com/uploads/stream_images/1620811349_743992b_celebration_portrait.jpg"
                                                                                        height="150px"></a></p>
                                                                            <u></u>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#85bdad" nowrap><img border="0"
                                                                src="http://images/spacer.gif" width="5" height="1">
                                                        </td>
                                                        <td width="100%" bgcolor="#ffffff">
                                                            <table width="100%" cellpadding="20" cellspacing="0"
                                                                border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td bgcolor="#ffffff"
                                                                            class="m_7640133754890689166contentblock">
                                                                            <h4><strong><u></u>SWIGIT Link: "Copy
                                                                                    and Paste"<u></u></strong></h4>
                                                                            <p style="font-style:italic">Just <b>Copy
                                                                                    and Paste</b> anywhere. If possible,
                                                                                add a JPEG of the Poster (or any other
                                                                                image associated with your content)
                                                                                followed by this link.</p><u></u>
                                                                            <p><a href="https://celeb.swigit.com/"
                                                                                    target="_blank">https://celeb.swigit.com/</a>
                                                                            </p><u></u>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#832701" nowrap><img border="0"
                                                                src="http://images/spacer.gif" width="5" height="1">
                                                        </td>
                                                        <td width="100%" bgcolor="#ffffff">
                                                            <table width="100%" cellpadding="20" cellspacing="0"
                                                                border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td bgcolor="#ffffff"
                                                                            class="m_7640133754890689166contentblock">
                                                                            <h4><strong><u></u>SWIGIT EMBED CODE FOR
                                                                                    INCLUSION OF THE CONTENT ON A
                                                                                    WEBPAGE AT A SPECIFIC LOCATION ON
                                                                                    THE PAGE, ALONG WITH ITS&#39; MEDIA
                                                                                    PLAYER:<u></u></strong></h4><u></u>
                                                                            <p>SWIGIT Embed Code when a designated
                                                                                location on the webpage is desired for
                                                                                the content along with its&#39; media
                                                                                player.</p>
                                                                            <p><textarea
                                                                                    id="m_7640133754890689166txtFrameSource"
                                                                                    cols="60" readonly
                                                                                   rows="6"><iframe src="https://celeb.swigit.com/" width="100" height="20"></iframe></textarea>
                                                                            </p>
                                                                            <h4><strong><u></u>SWIGIT EMBED CODE FOR THE
                                                                                    INCLUSION ON A HYPERLINKED SWIGIT
                                                                                    POSTER ON A WEBPAGE:<u></u></strong>
                                                                            </h4>
                                                                            <p>SWIGIT Poster Embed Code</p>
                                                                            <p><textarea
                                                                                    id="m_7640133754890689166TaCopyTxt"
                                                                                    cols="60" rows="6"><a href="https://celeb.swigit.com/" target="_blank"><img id="img1" src="https://www.swigappmanager.com/uploads/stream_images/1620811349_743992b_celebration_portrait.jpg" style="width:100px; border:0; cursor:pointer" /></a></textarea>
                                                                            </p><u></u>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#F7DC6F" nowrap><img border="0"
                                                                src="http://images/spacer.gif" width="5" height="1">
                                                        </td>
                                                        <td width="100%" bgcolor="#ffffff">
                                                            <table width="100%" cellpadding="20" cellspacing="0"
                                                                border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td bgcolor="#ffffff"
                                                                            class="m_7640133754890689166contentblock">
                                                                            <h4><strong><u></u>UNIQUE QR CODE ASSOCIATED
                                                                                    WITH THIS SWIGIT:<u></u></strong>
                                                                            </h4>
                                                                            <p style="font-style:italic">Each SWIGIT has
                                                                                a unique QR Code. The QR code is ideal
                                                                                for placement on print advertising,
                                                                                posters, or products associated with the
                                                                                content. Scanning the QR code will take
                                                                                the person to the event, film or show
                                                                                SWIGIT where your content can be
                                                                                accessed under the transaction terms</p>
                                                                            <p><img src="https://www.swigappmanager.com/images/celeb_qrcode.png" width="120px"
                                                                                    alt="https://celeb.swigit.com/">
                                                                            </p><u></u>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#51dc78" nowrap><img border="0"
                                                                src="http://images/spacer.gif" width="5" height="1">
                                                        </td>
                                                        <td width="100%" bgcolor="#ffffff">
                                                            <table width="100%" cellpadding="20" cellspacing="0"
                                                                border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td bgcolor="#ffffff"
                                                                            class="m_7640133754890689166contentblock">
                                                                            <u></u>
                                                                            <p></p>
                                                                            <center>
                                                                                <h3>We value your business. Please
                                                                                    contact us for any reason.</h3>
                                                                            </center>
                                                                            <p></p>
                                                                            <p></p>
                                                                            <center>
                                                                                <h3>Wishing you much success!.</h3>
                                                                            </center>
                                                                            <p></p>
                                                                            <p> </p>
                                                                            <center>Best,</center>
                                                                            <p></p>
                                                                            <p></p>
                                                                            <center>Your Swig Media Team </center>
                                                                            <p></p>
                                                                            <p></p>
                                                                            <center>Contact: <a
                                                                                    href="mailto:support@swigit.com"
                                                                                    target="_blank">support@swigit.com</a>
                                                                            </center>
                                                                            <p></p><u></u>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table><img border="0" src="http://images/spacer.gif" width="1"
                                                height="15"><br><u></u><u></u>
                                        </td>
                                    </tr>
                                </tbody>
                            </table><img border="0" src="http://images/spacer.gif" width="1" height="25"><br>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>';	

  $sentMail = sendmailUA($toEmail, "Celebration: Broadway Jewish Composers SWIGIT+ LETTER", $bodyText);
    //echo "show".$sentMail;
    if($sentMail)
        echo "sent";	
    else
        echo "not sent";
				
		
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
	$senderName = 'no-reply@swigit.com';

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
		$mail->addAddress($recepEmail);
		//$mail->addBCC('dhara@swigmedia.com'); 
    	//$mail->addBCC('ivan@swigmedia.com'); 

        $attachment = 'SwigIt-HowTo.pdf';
        $mail->AddAttachment($attachment);
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