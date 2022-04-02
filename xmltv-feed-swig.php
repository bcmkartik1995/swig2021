<?php
ini_set("display_errors", "0");
include_once('includes/functions/common.php'); 
session_start();

cors();

//print_r($_REQUEST);
$channelName = $_REQUEST['channelname'];
$clientName = $_REQUEST['client'];


//print_r($playlistDataCURL);

	$channelPlaylist_url = "http://adminapi.swigit.com/api/ChannelPlaylist/GetCurrentInPlaylist?client=".$clientName."&channelname=".$channelName;
	$channelPlaylistCurl = curl_init($channelPlaylist_url);
	curl_setopt($channelPlaylistCurl, CURLOPT_RETURNTRANSFER, true);
	
	$channelPlaylistResponse = curl_exec($channelPlaylistCurl);
	curl_close($channelPlaylistCurl);
	$channelPlaylistData = json_decode($channelPlaylistResponse, true);
    $playlistId = $channelPlaylistData['PlaylistId'];

    $playlistDataCURL = getPlaylistData($playlistId);
    //print_r($playlistDataCURL);
// create a dom document with encoding utf8
$domtree = new DOMDocument('1.0', 'ISO-8859-1');
$implementation = new DOMImplementation();
$domtree->appendChild($implementation->createDocumentType('tv SYSTEM "xmltv.dtd"'));

// create a root element of the xml tree
$tv = $domtree->createElement('tv');

    //create attributes for element
    $generator_info_name = $domtree->createAttribute('generator-info-name');
    $generator_info_name->value = 'www.swigit.com/xmltv';
    //append attribute
    $tv->appendChild($generator_info_name);
    // append element to the doc
    $tv = $domtree->appendChild($tv);

    //add a channel as a child of the root
    $channel = $domtree->createElement('channel');
    $channel_id = $domtree->createAttribute('id');
    $channel_id->value = $channelName;
    $channel->appendChild($channel_id);

    $channel = $tv->appendChild($channel);

        //append children to channel
$chnEle = $domtree->createElement('display-name',$channelName);
$channel->appendChild($chnEle);
//$channel->appendChild($domtree->createElement('lang','en'));

$chtLn = $domtree->createAttribute('lang');
$chtLn->value = 'en';
$chnEle->appendChild($chtLn);


$itemContent =  $playlistDataCURL['PlaylistContent'];

for($i = 0; $i < count($itemContent); $i++)
{
   // $item = $xml->channel->addChild('item');
    $programme = $tv->appendChild($domtree->createElement("programme"));
    $prog_title = $domtree->createAttribute('channel');
    $prog_title->value = $channelName;
    $programme->appendChild($prog_title);

    $endMillisec = (int)$itemContent[$i]['StartTime'] + (int)$itemContent[$i]['Duration'];
    
    $strDt = convertToDatetime($itemContent[$i]['StartTime'] ,$itemContent[$i]['ScheduleDate']);
    $endDt = convertToDatetime($endMillisec ,$itemContent[$i]['ScheduleDate']);

    $prog_strDt = $domtree->createAttribute('start');
    $prog_strDt->value = $strDt;
    $programme->appendChild($prog_strDt);

    $prog_endDt = $domtree->createAttribute('stop');
    $prog_endDt->value = $endDt;
    $programme->appendChild($prog_endDt);

    $scheduledDateFormat = date("YYYYMMDDHHMMSS", $itemContent[$i]['ScheduleDate']." ".$hours.":".$minutes.":".$seconds.".".$extrasec);

    $progTit = $domtree->createElement('title', $itemContent[$i]['Title'] );
    $programme->appendChild($progTit);
    //$progTit->appendChild($chtLn);
        
    $progTitLn = $domtree->createAttribute('lang');
    $progTitLn->value = 'en';
    $progTit->appendChild($progTitLn);

    $programme->appendChild($domtree->createElement('video-url', $itemContent[$i]['VideoURL'] ));

    $asstType = $domtree->createElement('category', $itemContent[$i]['AssetType'] );
    $programme->appendChild($asstType);

    $assLng = $domtree->createAttribute('lang');
    $assLng->value = 'en';
    $asstType->appendChild($assLng);


    $programme->appendChild($domtree->createElement('startAt', $itemContent[$i]['Duration'] ));
    //$programme->appendChild($domtree->createElement('Time', $strDt   ));
    //$programme->appendChild($domtree->createElement('endTime', $endDt   ));
    $programme->appendChild($domtree->createElement('VideoStartPoint', $itemContent[$i]['VideoStartPoint'] ));
    $programme->appendChild($domtree->createElement('VideoEndPoint', $itemContent[$i]['VideoEndPoint'] ));
    $programme->appendChild($domtree->createElement('StartTime', $itemContent[$i]['StartTime'] ));
    $programme->appendChild($domtree->createElement('ScheduleDate', $itemContent[$i]['ScheduleDate'] ));
    $programme->appendChild($domtree->createElement('DayOver', $itemContent[$i]['DayOver'] ));
    $programme->appendChild($domtree->createElement('desc', $itemContent[$i]['Description'] ));
    $programme->appendChild($domtree->createElement('keywords', $itemContent[$i]['Keywords'] ));
    $programme->appendChild($domtree->createElement('Content_id', $itemContent[$i]['FK_Content'] ));
    $programme->appendChild($domtree->createElement('Segment_id', $itemContent[$i]['FK_Segment'] ));
    
    

}
;

 Header('Content-type: text/xml');
 print $domtree->saveXML();




function getPlaylistData($playlistid)
{

	//print_r($channelPlaylistData);

    //$playlistId = 51445;
    $playlist_url = "http://adminapi.swigit.com/api/PlayList/".$playlistid;
	$playlistCurl = curl_init($playlist_url);
	curl_setopt($playlistCurl, CURLOPT_RETURNTRANSFER, true);
	//echo "<br><br>";
	$playlistResponse = curl_exec($playlistCurl);
	curl_close($playlistCurl);
	$playlistData = json_decode($playlistResponse, true);
 //   $playlistId = $playlistData['PlaylistId'];
    return $playlistData;
  
  

}

function convertToDatetime($millisec, $schdDate)
{
    $plStartTime = (int)$millisec; 
    $hours = floor($plStartTime / 3600000); 
    $restMin  = floor($plStartTime / 3600000); 
    $remStTime = (int)$plStartTime - ((int)$hours * 60 * 60 * 1000);
    
    $minutes = (int)floor(($remStTime / 60000)); 
    $remSec = (int)$remStTime - ((int)$minutes * 60000);

    $seconds = (int)floor($remSec / 1000);
    $extrasec = (int)$remSec - (int)$seconds * 1000;

    $strArr = explode("/", $schdDate);

    if($hours < 10)
        $hours = "0".$hours;

    if($minutes < 10)
        $minutes = "0".$minutes;

    if($seconds < 10)
        $seconds = "0".$seconds;

    $strDtRt = $strArr[2].$strArr[0].$strArr[1].$hours.$minutes.$seconds." +0000";
    return  $strDtRt;
}
?>