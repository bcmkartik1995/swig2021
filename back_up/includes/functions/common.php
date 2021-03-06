<?php
function generatePassword($value)
{
	return MD5(TOKEN_SALT . $value);
}

function replaceDataInList($string, $list)
{
	$find = array_keys($list);
	$replace = array_values($list);
	return str_ireplace($find, $replace, $string);
}

function randomMD5()
{
	return MD5(TOKEN_SALT . time() . mt_rand());
}

function trimFormValue($trace, $array)
{
	$rtnArr = array_map('trim', $array);
	if ($trace)
	{
		echo "<pre><-------------Input array value-------------><br>";
		print_r($array);
		echo "<-------------Output array value-------------><br>";
		print_r($rtnArr);		
		echo "</pre>";
		die;
	}
	return $rtnArr;
}

function prepareKeyValue4Msql($trace, $array, $keyExcludeArr)
{
	$rtnArr = array();
	
	foreach ($array AS $key => $value)
	{
		if (!in_array($key, $keyExcludeArr)) $rtnArr[$key] = $value;
	}
	
	if ($trace)
	{
		echo "<pre><-------------Input array value-------------><br>";
		print_r($array);
		echo "<-------------Output array value-------------><br>";
		print_r($rtnArr);		
		echo "</pre>";
		die;
	}
	return $rtnArr;

}

function checkTimeFormat($timeFormat)
{
	$timeFormat = trim($timeFormat);

	if (strstr($timeFormat, ':'))
	{
		list($hr,$min) = @explode(":", $timeFormat);

		if (@is_numeric($hr) && @is_numeric($min)) 
		{
			if (strlen($hr) == 2 && strlen($min) == 2) 
			{
				return 1;
			}
		}
	}

	return 0;
}

function makeRandNo6Digit()
{
	return rand(100000, 999999);
}

function unixtime64($str)
{
   date_default_timezone_set("UTC");
   $dateTime = new DateTime($str);
   return $dateTime->format("U");
}

function mysqlDate($value)
{
	if ($value) {
		if(MYSQL_DATE_CONVERSION_STYLE == 'EU') list($dd, $mm, $yy) = explode(DATE_FORMAT_SPLITTER, $value);
		else if(MYSQL_DATE_CONVERSION_STYLE == 'US') list($mm, $dd, $yy) = explode(DATE_FORMAT_SPLITTER, $value);
		return "$yy-$mm-$dd"; // Obtain the final date
	}
}

function getRealIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{				
		// Check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} 
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{   
		// Check if ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} 
	else
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}


function headerRedirect($url)
{
	ob_start();
	header('location:'.$url);
	exit;
}

function viewState($viewStateArray, $mode)
{
	if ($mode) 
	{
		foreach ($viewStateArray as $key => $value) 
		{
			$_SESSION['session_' . $key] = $value;
		}
	} 
	else 
	{
		foreach ($viewStateArray as $key => $value) 
		{
			unset($_SESSION['session_' . $key]);
		}
	}
}

function getTimestamp($value, $dateFormat)
{
	if ($value)
	{
		return @date($dateFormat,strtotime($value));
	}
}

function allowedFIleExten($indexName, $arrAllowedExtension = array('png', 'jpg', 'jpeg' , 'gif'))
{
	$rten = 0;
	if (!empty($_FILES[$indexName]['name']))
	{
		$fileName = trim($_FILES[$indexName]['name']);	
		$arrPathInfo = pathinfo($fileName);
		$fileExten = strtolower($arrPathInfo['extension']);
		if (!in_array($fileExten, $arrAllowedExtension)) $rten = 1;
	}
	return $rten;
}

function fileUpload($trace, $indexName, $dirLocation, $strConcatSym = '_')
{
	$newFileName = '';
	$filePath = HARD_PATH . "/uploads/".$dirLocation;	

	if (!empty($_FILES[$indexName]['name']))
	{
		$newFileName = time(). $strConcatSym .substr(randomMD5(), 1, 7). $strConcatSym .str_replace(array(' ',  '-', '__'), array('_', '_', '_'),  $_FILES[$indexName]['name']);
		if (move_uploaded_file($_FILES[$indexName]['tmp_name'], $filePath .'/'. $newFileName)) chmod($filePath . '/'.$newFileName, 0777);		
	}

	if ($trace)
	{
		print_r($_FILES);
		echo "New File Name: $newFileName<br>File Path: $filePath/".$newFileName;
		die;
	}
	return $newFileName;
}

function unlinkFile($trace, $fileName, $dirLocation)
{	
	if ($trace)
	{
		echo "File Name: $fileName<br>File Path: $filePath/".$fileName;
		die;
	}

	if ($fileName)
	{
		$filePath = HARD_PATH . "/uploads/".$dirLocation;
		@chmod($filePath . '/'.$fileName, 0777);		
		@unlink($filePath . '/'.$fileName);		
	}
}

function showSessionMessage()
{
	if (isset($_SESSION['messageSession'])) 
	{
		echo $_SESSION['messageSession'];
		unset($_SESSION['messageSession']);
		unset($_SESSION['msgTrue']);
	}
}
//sendEmail('', '', '', '', '', '');
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
		if (php_uname("n") == 'ip-172-31-48-252')
		{
			//include_once('../smtp.php');
			//print_r(error_get_last());
			sendEmailUsingSES($to, $subject, $body, $fromName, $from);	
			//echo "error_get_last";			
			//die;
			
		}
		else 
		{
			$success = mail($to, $subject, $body, $headers, '-f ' . NO_REPLY_EMAIL);
		}
		return $success;
	}
}

function sendEmailUsingSES1($recipient, $subject, $bodyHtml, $senderName, $sender = 'sssameer2012@gmail.com')
{
	// is still in the sandbox, this address must be verified.
	//$recipient = 'vijay@fusionitechnolgies.com';
	//$recipient = 'vijay.desh@hotmail.com';

	$usernameSmtp = 'AKIAZ5LKOGSKFDJXUAXX';
	$passwordSmtp = 'BOQvXvWca+gO9OIHbj/CXhSqn3jrIKak3/p0OM5xX6gX';
	$host = 'email-smtp.us-east-1.amazonaws.com';
	$port = 587;

	$sender = 'sssameer2012@gmail.com';	
	//$subject = 'Amazon SES test (SMTP interface accessed using PHP)';

	// The plain-text body of the email
	$bodyText =  "";

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
	  //  $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

		// Specify the message recipients.
		$mail->addAddress($recipient);
		//$mail->addAddress('vkvia6@gmail.com');
		// You can also add CC, BCC, and additional To recipients here.

		// Specify the content of the message.
		$mail->isHTML(true);
		$mail->Subject    = $subject;
		$mail->Body       = $bodyHtml;
		//$mail->AltBody    = $bodyText;
		$mail->Send();
		return true;
	} catch (phpmailerException $e) {
		echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
		echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}
}

function prepareEmailFormat($traceArr, $emailFormatInfoArr, $objDBQuery, $appCode, $fname = '', $emailORUsername = '', $password = '', $accountActivationCode)
{
	$emailBody = $emailFormatInfoArr[0]['body'];
	$emailSubject = $emailFormatInfoArr[0]['subject'];
	$appInfoArr = $objDBQuery->getRecord($traceArr[0], array('appNoreplyEmail', 'appFromEmailTxt', 'appEmailSignature'), 'tbl_apps', "appCode = '$appCode'");
	
	$appNoreplyEmail  = $appInfoArr[0]['appNoreplyEmail'];
	$appFromEmailTxt = $appInfoArr[0]['appFromEmailTxt'];
	$appEmailSignature = $appInfoArr[0]['appEmailSignature'];

	$arrData = array();
	$arrData['[FNAME]'] = $fname;
	$arrData['[EMAIL_ID]'] = $emailORUsername;
	$arrData['[PASSWORD]'] = $password;
	$arrData['[EMAIL_SIGNATURE]'] = $appEmailSignature;
	$arrData['[ACCOUNT_ACTIVATION_CODE]'] = $accountActivationCode;

	$strRtnBody = str_replace(array_keys($arrData), array_values($arrData), $emailBody);
	$arrRtn = array('strBody' => $strRtnBody, 'emailSubject'=> $emailSubject, 'appNoreplyEmail' => $appNoreplyEmail, 'appFromEmailTxt' => $appFromEmailTxt);
	
	if ($traceArr[1])
	{
		echo "<pre>";
		
		print_r($emailFormatInfoArr);
		print_r($appInfoArr);
		print_r($arrRtn);
		die;
	}
	return $arrRtn;

}

function makeEmailStructure4API($accessCase, $to, $fname = "user", $username = '', $password = '', $newEmail = '')
{
	switch ($accessCase) 
	{				
		case 'accountCreate':
				$accountActivationCode = $newEmail;
				$body = "Hello ".$fname.",<br><br>Your account has been created successfully.<br><br>
				Please note your login information for future use:<br><br>Email Address: ".$username."<br>Password: ".$password."<br>Account Activation Code: ".$accountActivationCode."<br><br>
				Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Account Information', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;	

		case 'resendAccountActivationCode':
				$accountActivationCode = $newEmail;
				$body = "Hello ".$fname.",<br><br>Your new account activation code has been generated successfully.<br><br>
				Account activation code is: <b>".$accountActivationCode."</b><br><br>
				Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your New Account Activation Code', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;

		case 'changePassword':
				$body = "Hello ".$fname.",<br><br>Your profile password has been changed in our record.<br><br>
				Please note your new login information for future use:<br><br>Email Address: ".$username."<br>Password: ".$password."<br><br>
				<br><br>Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Profile Password Changed', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;		
				
		case 'forgotPassword':
				$body = "Hello ".$fname.",<br><br>Your password has been changed in our record.<br><br>
				Please note your new password for future use:<br><br>Password: ".$password."<br><br>
				Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Password Changed', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;

		case 'updateEmail':
				$body = "Hello ".$fname.",<br><br>Your email address has been changed in our record.<br><br>
				Your new email address is ".$newEmail."<br><br>
				Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Email Address Changed', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;
	}

}

function makeEmailStructure($accessCase, $to, $fname, $username = '', $password = '', $newEmail = '')
{
	switch ($accessCase) 
	{
		case 'accountPasswordReset':
				$body = "Hello ".$fname.",<br><br>Your password has been reset in our record.<br><br>
				Please note your new login information for future use:<br><br>Username: ".$username."<br>Password: ".$password."<br><br>
				<a href='".HTTP_PATH."/swig' target='_blank'>Click here to login</a><br><br>Thanks,<br>".SIGNATURE;

				sendEmail($to, 'Your Account Password Reset', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;	

		case 'forgotPassword':
				$body = "Hello ".$fname.",<br><br>Your password has been reset in our record.<br><br>
				Please note your new login information for future use:<br><br>Username: ".$username."<br>Password: ".$password."<br><br>
				<a href='".HTTP_PATH."/swig' target='_blank'>Click here to login</a><br><br>Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Account Password Reset', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;
				
		case 'changePassword':
				$body = "Hello ".$fname.",<br><br>Your profile password has been changed in our record.<br><br>
				Please note your new login information for future use:<br><br>Username: ".$username."<br>Password: ".$password."<br><br>
				<a href='".HTTP_PATH."/swig' target='_blank'>Click here to login</a><br><br>Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Profile Password Changed', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;		

		case 'updateYourEmail':
				$body = "Hello ".$fname.",<br><br>Your email address has been changed in our record.<br><br>
				Your new email address is ".$_SESSION['userDetails']['email']."<br><br>
				<a href='".HTTP_PATH."/swig' target='_blank'>Click here to login</a><br><br>Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Email Address Changed', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;		

		case 'updateAdminEmail':
				$body = "Hello ".$fname.",<br><br>Your email address has been changed in our record.<br><br>
				Your new email address is ".$newEmail."<br><br>
				<a href='".HTTP_PATH."/swig' target='_blank'>Click here to login</a><br><br>Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Email Address Changed', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;		

		case 'addAdminAccount':
				$body = "Hello ".$fname.",<br><br>Your account has been created successfully.<br><br>
				Please note your login information for future use:<br><br>Username: ".$username."<br>Password: ".$password."<br><br>
				<a href='".HTTP_PATH."/swig' target='_blank'>Click here to login</a><br><br>Thanks,<br>".SIGNATURE;
				
				sendEmail($to, 'Your Account Information', $body, FROM_NAME, NO_REPLY_EMAIL, 'HTML');
				break;
	}

}

# Search value in multidimentional array
function inArrayMulti($findValue, $arrayName, $strict = false)
{
	foreach ($arrayName as $item) 
	{
		if (($strict ? $item === $findValue : $item == $findValue) || (is_array($item) && inArrayMulti($findValue, $item, $strict)))
		{
			return true;
		}
	}
	return false;
}

function getValPostORGet($indexName, $method = 'P')
{
	$rtenVal = '';
	if (!empty($_POST[$indexName]) && ($method == "P" || $method == "B")) $rtenVal = trim($_POST[$indexName]);
	else if (!empty($_GET[$indexName]) && ($method == "G" || $method == "B")) $rtenVal = trim($_GET[$indexName]);
	return $rtenVal;
}

function responses($trace, $arrData)
{
	if ($trace)
	{
		echo "<pre><-------------Input array value-------------><br>";
		print_r($arrData);
		die;
	}

	header('Content-Type: application/json');
	echo json_encode($arrData);
}

function getContents()
{
	parse_str(file_get_contents("php://input"), $vars);
	return $vars;
}

function statusClsActive($frmVal, $statusVal)
{
	$rtnStr = "";
	if (strtolower($frmVal) == strtolower($statusVal)) $rtnStr = "class='media_active'";
	return $rtnStr;
}

function checkBxSeleted($ckBxVal, $selectedVal)
{
	$rtnStr = "";
	if (strtolower($ckBxVal) == strtolower($selectedVal)) $rtnStr = "checked";
	echo $rtnStr;
}

function makeURLSlug($str)
{
	$str = str_replace(array(' ', '/','_', '--', '"', '"', ',', '.'), array('-', '-', '-', '-', '-', '-', '-', '-'), strtolower(trim($str)));
	return $str;
}

function makeURLSlug4Blog($str)
{
	$str = str_replace(array(' ', '/','_', '--', '"', '"', ',', '.', '?'), array('-', '-', '-', '-', '-', '-', '-', '-', ''), strtolower(trim($str)));
	$str = str_replace(array('--'), array('-'), $str);
	return $str;
}

function limitText($text, $limit)
{
  	if (str_word_count($text, 0) > $limit)
   	{
	   $words = str_word_count($text, 2);
	   $pos = array_keys($words);
	   $text = trim(stripslashes(substr($text, 0, $pos[$limit]))) . '...<span class="pull-right read_service_more">Read More</span></p>';
   	}
   	return $text;
 }

 function limitPageContent($text, $limit, $isAddBrTag = 'Y')
 {
	$fullTxt = trim(stripslashes($text));

	$isShowMoreBtn = 'N';

	$fullTxtLen = strlen($fullTxt);
	
	if ($fullTxtLen > $limit)
	{
		$slashedTxt = substr($fullTxt, 0, $limit);

		$lastCharOfSlashedTxt = substr(trim($slashedTxt), -1);	

		if ($lastCharOfSlashedTxt === '.') $slashedTxt = trim($slashedTxt) . '..';
		else  $slashedTxt .= '...';
		$isShowMoreBtn = 'Y';
	}
	else 
	{
		$slashedTxt = $fullTxt;
	}
	if ($isAddBrTag == 'Y') $slashedTxt = nl2br($slashedTxt);

   	return array('slashedTxt' => $slashedTxt, 'isShowMoreBtn' => $isShowMoreBtn, 'fullTxt' => nl2br($fullTxt));
 }

 function makeDropDownFromDB($dropDownName, $optionListArray, $optionValueDbFld, $optionTextDbFld, $selectedOptionValue, $mode = '', $style = '', $event = '')
{
	$str  = "<select name = '$dropDownName' id = '$dropDownName' $style $event $mode>";
	$str .= "<option value=''>Please Select</option>";

	if (is_array($optionListArray))
	{
		$numOfRows = count($optionListArray);
		for ($i = 0; $i < $numOfRows; $i++) {

			if ($optionListArray[$i][$optionValueDbFld] == $selectedOptionValue)
			{
				$str .= "<option value='" . $optionListArray[$i][$optionValueDbFld] . "' selected>" . htmlspecialchars($optionListArray[$i][$optionTextDbFld]) . "</option>";
			} 
			else
			{
				$str .= "<option value='" . $optionListArray[$i][$optionValueDbFld] . "'>" . htmlspecialchars($optionListArray[$i][$optionTextDbFld]) . "</option>";
			}
		}
	}

	$str .= "</select>";
	echo $str;
}

function makeDropDown($dropDownName, $optionValueArray, $optionTextArray, $selectedOptionValue, $mode = '', $style = '', $event = '', $hideShowPlzSelect = 'N')
{
	$str  = "<select name = '$dropDownName' id = '$dropDownName' $style $event $mode>";
	if ($hideShowPlzSelect != 'Y') $str .= "<option value=''>Please Select</option>";

	if(is_array($optionValueArray)) {
		$numOfRows = count($optionValueArray);

		for ($i = 0; $i < $numOfRows; $i++)
		{
			if ($optionValueArray[$i] == $selectedOptionValue) 
			{
				$str .= "<option value='" . $optionValueArray[$i] . "' selected>" . htmlspecialchars($optionTextArray[$i]) . "</option>";
			} 
			else 
			{
				$str .= "<option value='" . $optionValueArray[$i] . "'>" . htmlspecialchars($optionTextArray[$i]) . "</option>";
			}
		}
	}

	$str .= "</select>";
	echo $str;
}

//This function returns True if login: swig_tv_fus and password: d80871df367c0c38a2ee1955485a361a are provided
//Otherwise it returns False
function checkApiAccess()
{
	//echo  phpinfo();
	//die;
	$valid_passwords = array ("mario" => "carbonell");
	$valid_users = array_keys($valid_passwords);

	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];

	$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

	if (!$validated) {
		header("WWW-Authenticate: Basic realm=\"My Realm\"");
		header('status: 401 Unauthorized');
	  die ("Not authorized");
	}

	// If arrives here, is a valid user.
	echo "<p>Welcome $user.</p>";
	echo "<p>Congratulation, you are into the system.</p>";
} 

function stripslashesHtmlChars($str)
{
	return  stripslashes(htmlspecialchars(trim($str)));
}

function checkPageAccessPermission($codeStr)
{
	if ($codeStr == '')
	{
		$_SESSION['messageSession'] = UNAUTHORIZED_MSG;	
		headerRedirect('dashboard.php');
	}
}

function require_auth() 
{
	$AUTH_USER = 'admin';
	$AUTH_PASS = 'admin';
	header('Cache-Control: no-cache, must-revalidate, max-age=0');
	$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
	$is_not_authenticated = (!$has_supplied_credentials || $_SERVER['PHP_AUTH_USER'] != $AUTH_USER || $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS);
	
	if ($is_not_authenticated)
	{
		header('HTTP/1.1 401 Authorization Required');
		header('WWW-Authenticate: Basic realm="Access denied"');
		
		echo "<pre>";
		print_r($_SERVER);
		exit;
	}
}

function addHttp($url)
{     
	$url = trim($url);
	// Search the pattern 
	if (!preg_match("~^(?:f|ht)tps?://~i", $url))
	{ 

		// If not exist then add http 
		if ($url != '') $url = "http://" . ltrim($url, '//'); 
	} 

	// Return the URL 
	return $url; 
}

function updateStreamOrder($objDBQuery, $menuCode = '')
{
	$where = 1;
	if ($menuCode != '') $where = "menuCode_FK = '$menuCode'";
	$menuCodeInfoArr = $objDBQuery->getRecord(0, array('menuCode_FK'), 'tbl_streams', "$where GROUP BY menuCode_FK");	
	if (is_array($menuCodeInfoArr))
	{
		$orderBY = "isStreamFeatured = 'Y' DESC, streamOrder ASC, createdOn DESC";
		for ($i = 0; $i < count($menuCodeInfoArr); $i++)
		{
			$menuCode_FK = $menuCodeInfoArr[$i]['menuCode_FK'];
			$streamInfoArr = $objDBQuery->getRecord(0, array('menuCode_FK', 'streamId_PK', 'streamTitle', 'createdOn', 'streamOrder'), 'tbl_streams', " menuCode_FK = '$menuCode_FK'", '', '', $orderBY, '');
			
			$cnt = 1;
			for ($j = 0; $j < count($streamInfoArr); $j++)
			{
				$enckey = $streamInfoArr[$j]['streamId_PK'];

				$objDBQuery->updateRecord(0, array('streamOrder' => $cnt), 'tbl_streams', array('streamId_PK' => $enckey));
				$cnt++;	

			}
		}

	}
	echo 'Done';
}





