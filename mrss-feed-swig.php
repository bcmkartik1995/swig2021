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

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
<channel/>
</rss>');

//$xml->title();
//$xml->addAttribute("title", );
$xml->channel->addChild('title', $playlistDataCURL['Title']);
$xml->channel->addChild('PlaylistId', $playlistDataCURL['Id']);
//$xml->channel->addChild('Description', $playlistDataCURL['Description']);
/*
$xml->channel->addChild('ScheduledDate', $channelPlaylistData['ScheduledDate']);
$xml->channel->addChild('PlaylistStatus', $playlistDataCURL['PlaylistStatus']);
$xml->channel->addChild('Description', $playlistDataCURL['Description']);

$xml->channel->addChild('title', $channelPlaylistData['Title']);
$xml->channel->addChild('PlaylistId', $playlistDataCURL['Id']);
$xml->channel->addChild('Description', $playlistDataCURL['Description']);
*/
//$xml->channel->addChild('AssetLocation', $playlistDataCURL['AssetLocation']);

$itemContent =  $playlistDataCURL['PlaylistContent'];

for($i = 0; $i < count($itemContent); $i++)
{
$item = $xml->channel->addChild('item');

$item->title = $itemContent[$i]['Title'];
$item->description  = $itemContent[$i]['Description'];
$item->AssetType  = $itemContent[$i]['AssetType'];
$item->ContentId  = $itemContent[$i]['FK_Content'];
$item->SegmentId  = $itemContent[$i]['FK_Segment'];
$item->Keywords  = $itemContent[$i]['Keywords'];

/* Add <media:video>: */
$node = $item->addChild( 'video', Null, 'http://search.yahoo.com/mrss/' );
$node->addAttribute( 'url', $itemContent[$i]['VideoURL'] );
$node->addAttribute( 'startAt', $itemContent[$i]['Duration'] );
$node->addAttribute( 'VideoStartPoint', $itemContent[$i]['VideoStartPoint'] );
$node->addAttribute( 'VideoEndPoint', $itemContent[$i]['VideoEndPoint'] );
$node->addAttribute( 'StartTime', $itemContent[$i]['StartTime'] );
$node->addAttribute( 'ScheduleDate', $itemContent[$i]['ScheduleDate'] );
$node->addAttribute( 'DayOver', $itemContent[$i]['DayOver'] );


}

Header('Content-type: text/xml');
    print($xml->asXML());

//$excludeMenuOnPageArr = array( 'sign-up.php' );


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
  
  
  
/*
// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://adminapi.swigit.com/api/PlayList/51661");
curl_setopt($ch, CURLOPT_HEADER, false);

// grab URL and pass it to the browser
$res = curl_exec($ch);
//print_r($res);
//echo $res;
// close cURL resource, and free up system resources
curl_close($ch);*/
//    echo "end";
}

?>