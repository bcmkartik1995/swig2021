 <!-- .Start modal -->
 <div id="stream_popupbox" class="modal fade modal-popup" data-backdrop="true" >
	<div class="modal-dialog" id="animate">
		<div class="modal-content">
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick='videoPause();'>&times;</span></button><h5 class="modal-title">Stream Preview</h5></div>
			<div class="modal-body text-center p-lg" id="videoDivId">				
				<video controls crossorigin playsinline width="418" height="250" controls> </video>
			</div>
			<div class="modal-footer">				
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style='display:none;'><i class="material-icons">&#xe5CD;</i>Close</button>
				<input type="hidden" id="confirmation_modal_frmId" value="">
				<input type="hidden" id="streamUrl4Model" value="">
			</div>
		</div>
	</div>
 </div>
  <!-- .End modal -->
<link rel="stylesheet" href="https://cdn.plyr.io/3.5.6/plyr.css" />
<script src="https://cdn.plyr.io/3.5.6/plyr.polyfilled.js"></script>
  <script src="https://cdn.rawgit.com/video-dev/hls.js/18bb552/dist/hls.min.js"></script>
  <script>
  var videobuttonClicked = document.getElementById("streamUrl4Model");
	videobuttonClicked.addEventListener('click', () => {
	//const source = 'http://storegalapagospex.teleosmedia.com/vod/galapagospex/PromoScratchesinHistory061419/playlist.m3u8';
	const source = $('#streamUrl4Model').val();
	const video = document.querySelector('video');
	
	// For more options see: https://github.com/sampotts/plyr/#options
	// captions.update is required for captions to work with hls.js
	const player = new Plyr(video);
	
	if (!Hls.isSupported()) {
		video.src = source;
	} else {
		// For more Hls.js options, see https://github.com/dailymotion/hls.js
		const hls = new Hls();
		hls.loadSource(source);
		hls.attachMedia(video);
		window.hls = hls;
	}
	
	// Expose player so it can be used from the console
	window.player = player;
});

function setStreamURL(strStreamURL)
{
	//alert(strStreamURL);
	$('#streamUrl4Model').val(strStreamURL);
	$('#streamUrl4Model').click();
}

function setStreamURL4AddStream(strStreamURL)
{
	//alert(strStreamURL);
	var strStreamURL = $('#streamUrl').val();
	$('#streamUrl4Model').val(strStreamURL);
	$('#streamUrl4Model').click();
}

function updateVidUrl(strVidUrl, isLinkHideShow)
{
	var strVidUrl = strVidUrl.replace(/^\s+|\s+$/g,"");	
	if (strVidUrl == '' && isLinkHideShow == 'Y')
	{
		$('#stream_urlinkId').hide();
	}
	else if (isLinkHideShow == 'Y')
	{
		//$('#streamUrl4Model').val(strVidUrl);
		$('#stream_urlinkId').show();
	} 
}

function videoPause()
{
	player.destroy();
	//alert('hi');
	$('.plyr__control').click(); 
}
</script>