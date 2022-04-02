<?php
define("_VC_API_URL", "https://cymtv.com/p2p_api/");
define("_VC_SERVER_ID", "freestyleguest_swigit");
define("_VC_WEBSITE", "https://freestyleguest.swigit.com"); 
function _vc_output($output)
{
	header('Content-Type: application/json');
	echo json_encode($output);
	exit();
}
function _vc_get_response($query)
{
	$resp = file_get_contents(_VC_API_URL."?id="._VC_SERVER_ID."&".$query);
	return json_decode($resp, true);
}
function _vc_get_token($remote_ip)
{
	$json = _vc_get_response("a=token&ip=".rawurlencode($remote_ip));
	if ($json && isset($json['status']) && $json['status'])
	{
		return $json['output'];
	}
	return false;
}

$token = _vc_get_token($_SERVER['REMOTE_ADDR']);
if ($token && isset($_GET['d']) && isset($_GET['u']))
{
	_vc_output(_vc_get_response("a=update&ip=".rawurlencode($_SERVER['REMOTE_ADDR'])."&token=".$token."&d=".$_GET['d']."&origin=".rawurlencode(_VC_WEBSITE)."&u=".$_GET['u']));
	exit();
}
$id = "";
?><script src="https://www.cymtv.com/assets/js/p2p-media-loader-core.min.js"></script><script src="https://www.cymtv.com/assets/js/p2p-media-loader-hlsjs.min.js"></script><script src="https://www.cymtv.com/assets/js/clappr.min.js"></script><script src="https://www.cymtv.com/assets/js/hls.min.js"></script>
<style type="text/css">html,body,#wrap,#main-content,#primary,#content,#player{
height:100%;
}
#content > #content-wrap{
height:100%;
}
#content > #content-wrap > .region{
height:100%;
}
#content-wrap > .region > .block{
height:100%;
}
#primary .block .content{height:100%;}
#primary .content .field {height:100%;}
#primary .field-items {height:100%;}
#primary .field-items .field-item {height:100%;}
#player a.close{
    float: right;
    font-size: 36px;
    color: #fff;
    padding-right: 15px;
}
</style>
<div id="player"><a class="close" href="/">✕</a></div>
<script>
$(document).ready(function(){
var p2p_download = 0;
var p2p_upload = 0;
var client_id = "<?php echo htmlentities($token); ?>";
function record()
{
	if (p2p_download || p2p_upload)
	{
		$.get( window.location.href.split('?')[0] + "?id="+client_id+"&d="+p2p_download+"&u="+p2p_upload, function( data ) {
			if (typeof data['status'] !== "undefined" && data['status'])
			{
				p2p_download -= data['output']['d'];
				p2p_upload -= data['output']['u'];
			}
		});
	}
}
setInterval(function(){
record();
	}, 15000);
	
	const config = {
loader: {
trackerAnnounce: [
			"wss://rendezvoyeur1.cymtv.com:8000/?client_id="+client_id,
			],
rtcConfig: {
iceServers: [
				{ urls: "stun:rendezvoyeur1.cymtv.com:3478?transport=udp" }
				]
			}
		}
	};
	var engine = (typeof p2pml !== "undefined" && p2pml.hlsjs.Engine.isSupported())? new p2pml.hlsjs.Engine(config) : undefined;

	var player = new Clappr.Player({
parentId: "#player",
source: "<?php
$video_url = "https://cdn.cymtv.com:443/ultravisiontv/720p/playlist.m3u8";

$today = gmdate("n/j/Y g:i:s A");
$ip = $_SERVER['REMOTE_ADDR'];
$key = "";
$validminutes = 60;
$str2hash = $ip . $key . $today . $validminutes;
$md5raw = md5($str2hash, true);
$base64hash = base64_encode($md5raw);
$urlsignature = "server_time=" . $today ."&hash_value=" . $base64hash. "&validminutes=$validminutes";
$base64urlsignature = base64_encode($urlsignature);
echo $video_url."?wmsAuthSign=".$base64urlsignature; 
?>",
mute: true,
height:"100%",
width:"100%",
autoPlay: true,
playback: {
hlsjsConfig: {
liveSyncDurationCount: 7,
loader: (typeof p2pml !== "undefined" && p2pml.hlsjs.Engine.isSupported())? engine.createLoaderClass() : Hls.DefaultConfig.loader
			}
		}
	});

(typeof p2pml !== "undefined")? p2pml.hlsjs.initClapprPlayer(player) : Hls.DefaultConfig.initClapprPlayer(player);
if (typeof p2pml !== "undefined")
{
	engine.on(p2pml.core.Events.PieceBytesDownloaded, onBytesDownloaded);
	engine.on(p2pml.core.Events.PieceBytesUploaded, onBytesUploaded);
}
function onBytesDownloaded(method, size) {
	if (method == "p2p") p2p_download += size;
}

function onBytesUploaded(method, size) {
	p2p_upload += size;
}
});
</script>