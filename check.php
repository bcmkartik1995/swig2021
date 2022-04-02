<!DOCTYPE html>
<head>

</head>
<body>
<p><a href="#" id="videolink1">Change video</a></p>
<video id="videoclip" controls="controls" title="Video title">
  <source id="mp4video" src="http://storegalapagospex.teleosmedia.com/vod/galapagospex/PromoScratchesinHistory061419/playlist.m3u8" type="application/x-mpegURL" />
 </video>
</body>
</html>
<script type="text/javascript">
var videocontainer = document.getElementById('videoclip');
var videosource = document.getElementById('mp4video');
var newmp4 = 'http://storegalapagospex.teleosmedia.com/vod/galapagospex/9/playlist.m3u8';
var newposter = 'images/video-cover.jpg';
 
var videobutton = document.getElementById("videolink1");
 
videobutton.addEventListener("click", function(event) {
    videocontainer.pause();
    videosource.setAttribute('src', newmp4);
    videocontainer.load();
    //videocontainer.setAttribute('poster', newposter); //Changes video poster image
    videocontainer.play();
}, false);
 
</script>

