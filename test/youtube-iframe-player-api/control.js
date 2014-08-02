var APITag = document.createElement('script');
APITag.src = 'https://www.youtube.com/iframe_api';
var firstScript = document.getElementsByTagName('script')[0];
firstScript.parentNode.insertBefore(APITag, firstScript);

var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
		height: '630',
		width: '1120',
		videoId: '9Fiwc79_N9c',
		playerVars: {
			rel: 0,
		   controls: 0,
		   showinfo: 0,
		   modestbranding: 1
		},
		events:{
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
	});
	document.getElementById('player').setAttribute('sandbox', 'allow-same-origin allow-scripts');
}

function onPlayerReady(event) {
	event.target.playVideo();
}

var done = false;
function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING && !done) {
		setTimeout(stopVideo, 6000);
		done = true;
	}
}
function stopVideo() {
	player.setPlaybackRate(2);
}
