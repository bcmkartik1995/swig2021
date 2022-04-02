<?php
// This corn execute everyday

ini_set('memory_limit', '-1');
include_once('web-config.php');

/*define('DB_HOST', 'swigtv.cgo3jvgdqurl.us-east-1.rds.amazonaws.com');
define('DB_USER', 'swigtv_master');
define('DB_PASS', 'Ojm84ftyv2igSKiW8Zc8');
define('DB_NAME', 'swig_tv_backend');
*/

define('DB_HOST', 'localhost');
define('DB_USER', 'swigmedi_samgr');
define('DB_PASS', 'zN7j.Sm1RP]-');
define('DB_NAME', 'swigmedi_swigappmanager');

define('TO', 'vijay@fusionitechnologies.com,vkvia6@gmail.com');
//define('TO', 'vijay@fusionitechnologies.com');
define('FILE_NAME', "SWIG_DB_BACKUP_".date('dmY_His'));	

define('DB_BACKUP_DIR','db_backup');
$compression = true;
$backupDirPath = __DIR__ . "/uploads/".DB_BACKUP_DIR;	 
if (!file_exists($backupDirPath)) mkdir($backupDirPath, 0777);		
chmod($backupDirPath, 0777);		

$backupDirPath = $backupDirPath ."/";

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);	

if (!$conn) die('Could not connect: ' .mysqli_connect_error());		

$rtn = backupDbTables(0, $conn); 
$body = "PFA";

# HERE FILE SAVE AND COMPRESSION
if ($compression)	
{		
	$fileName = strtoupper(FILE_NAME).'.sql.gz';
	$filePath = $backupDirPath . $fileName;		

	$zp = gzopen($filePath, "w9");
	gzwrite($zp, $rtn);
	gzclose($zp);
	chmod($filePath, 0777);

	sendEmailAttachment($fileName, $backupDirPath, TO, NO_REPLY_EMAIL, 'Swig App Manager', NO_REPLY_EMAIL, "Swig App Manager - Swig App Manager DB Backup", $body);
} 
else
{		
	$fileName = strtoupper(FILE_NAME).'.sql';
	$filePath = $backupDirPath . $fileName;
	$handle = fopen($filePath, 'w+');
	fwrite($handle, $rtn);
	fclose($handle);
	chmod($filePath, 0777);		
	sendEmailAttachment($fileName, $backupDirPath, TO, NO_REPLY_EMAIL, 'Swig App Manager', NO_REPLY_EMAIL, "Swig App Manager - Swig App Manager DB Backup", $body);
}

//unlink($filePath);

# BACKUP THE WHOLE DB BY DEFAULT ('*') OR A SINGLE TABLE ('TABLENAME') 
function backupDbTables($trace, $conn, $tables = '*')
{
	# HERE GET ALL OF THE TABLES NAME
	if($tables == '*') 
	{
		$tables = array();
		$result = mysqli_query($conn, 'SHOW TABLES');
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
		{
			$tables[] = $row[0];
		}
	} 
	else $tables = is_array($tables) ? $tables : explode(',', $tables);

	$rtn = "";
	if ($trace)
	{
		echo "<pre>All Tables Name:<br>";
		print_r($tables);
	}

	#  HERE CYCLE THROUGH DATA
	foreach($tables as $table)
	{
		$result = mysqli_query($conn, 'SELECT * FROM '.$table);
		$numFields = mysqli_num_fields($result);

		$rtn.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysqli_fetch_row(mysqli_query($conn, 'SHOW CREATE TABLE '.$table)); 			

		$rtn .= "\n\n".$row2[1].";\n\n";

		$rtn .= 'INSERT INTO '.$table." (";
		$cols = mysqli_query($conn, "SHOW COLUMNS FROM $table");
		$count = 0;

		while ($rows = mysqli_fetch_array($cols, MYSQLI_NUM))
		{
			$rtn .= $rows[0];
			$count++;
			if ($count < mysqli_num_rows($cols)) $rtn.= ",";
		}
		$rtn.= ")".' VALUES';			

		for ($i = 0; $i < $numFields; $i++)
		{
			$count = 0;
			while($row = mysqli_fetch_row($result))
			{
				$rtn .= "\n\t(";
				for($j = 0; $j < $numFields; $j++)
				{
					$row[$j] = addslashes($row[$j]);						
					if (isset($row[$j])) $rtn .= '"'.$row[$j].'"' ;
					else $rtn .= '""';

					if ($j < ($numFields - 1)) $rtn .= ',';

				}
				$count++;
				if ($count < mysqli_num_rows($result)) $rtn.= "),";
				else $rtn.= ");";				
			}
		}
	}
	return $rtn.="\n\n\n";
}

function sendEmailAttachment($filename, $path, $mailTo, $fromMail, $fromName, $replyTo, $subject, $message)
{
	$file = $path.$filename;

	$handle = fopen($file, "r");
	$fileSize = filesize($file);
	$content = fread($handle,  $fileSize);
	fclose($handle);

	$encodedContent = chunk_split(base64_encode($content));
	$boundary = md5(time());
	
	//header
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From:".$fromName."<$fromMail>\r\n";
	$headers .= "Reply-To: ".$replyTo."" . "\r\n";
	$headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

	//plain text
	$body = "--$boundary\r\n";
	$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
	$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$body .= chunk_split(base64_encode($message));

	//attachment
	$body .= "--$boundary\r\n";
	$body .="Content-Type: application/octet-stream; name=".$filename."\r\n";
	$body .="Content-Disposition: attachment; filename=".$filename."\r\n";
	$body .="Content-Transfer-Encoding: base64\r\n";
	$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
	$body .= $encodedContent;

	$sentMail = @mail($mailTo, $subject, $body, $headers);
	if($sentMail) 
	{      
		echo 'Email has been sent.';
	}
	else
	{
		die('Could not send mail! Please check your PHP mail configuration.');  
	}
}
?>

