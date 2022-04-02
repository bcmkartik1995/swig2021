<?php
include_once('web-config.php'); 
//include_once('smtp_1.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();
ini_set("display_errors" , 0);
include_once('includes/functions/common.php'); 


$whereArr = array();
$appCode = '3e909131cbb1a1f308183c838bc005d7';
$arrSelectDbFields = array('appCode', 'appName');	
$streamCode = 'd8996cfd878425e08814349b21cf9b19';
$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode, 'status' => 'A'));
if (is_array($appInfoArray) && !empty($appInfoArray))
{	

   //$arrSelectDbFlds4Stream = array('streamCode', 'streamTitle', 'streamUrl', 'eventStDateTime', 'timezoneOffset', 'streamImg', 'streamdescription', 'staring', 'streamTrailerUrl');
	$whereCls = "appCode_FK = '".$appCode."' AND accountStatus = 'A'";
	//$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', $whereCls, '', '', 'eventStDateTime', 'ASC');
	$userInfoArr = $objDBQuery->getRecord(0, array( 'fname', 'lname', 'email', 'userCode'), 'tbl_registered_users', $whereCls, '', '', 'fname', 'ASC');
	
	if (!empty($userInfoArr) && is_array($userInfoArr))
	{
		//$timezoneOffset = $appStreamsInfoArr[$j]['timezoneOffset'];
		
		echo $nunOfRows = count($userInfoArr);
		$srn = 0;
	for ($i = 0; $i < $nunOfRows; $i++)
		{
			$srn++;
			$userCode = $userInfoArr[$i]['userCode'];
			$fname = $userInfoArr[$i]['fname'];
			$lname = $userInfoArr[$i]['lname'];
			$email = $userInfoArr[$i]['email'];
				
				
				$ticketInfoArr = $objDBQuery->getRecord(0, array('ticketCode', 'type', 'buyInformation'), 'tbl_ticket_codes', array('streamCode_FK' => $streamCode, 'buyerUserCode_FK' => $userCode, 'type' => 'L'), '', '', '', '');
				$ticktStr = "";
				
				if (is_array($ticketInfoArr) && !empty($ticketInfoArr))
				{
					$numOfRows2 = count($ticketInfoArr);
					
					for ($j = 0; $j < $numOfRows2; $j++)
					{
						
						$ticketCode = $ticketInfoArr[$j]['ticketCode'];
						$buyInfo = 	$ticketInfoArr[$j]['buyInformation'];

						if($j == 0)
							$ticktStr = $ticketCode;
						else
							$ticktStr .= ", ".$ticketCode;
						
						
					}
				}	
					$memStr = "";
					
					$PaidMemb = strpos($buyInfo, "RIPE");
					$ctcMemb = strpos($buyInfo, "EXTERNAL");
					
					if( $ctcMemb !=  NULL) 
						$memStr = "Google Sheet";
					if( $PaidMemb != NULL )
						$memStr = "From C2CTV Site";
					
					
					echo $srn.": ".$email." (".$fname. " ".$lname.") &nbsp; ". $ticktStr."  &nbsp;  ". $memStr;//." ".$userCode." ".$createdOnDate." - ".$buyInfo;
							//echo $email;
							echo "<br>";
				
				
			}			



		}
		
}



echo "DONE";
?>