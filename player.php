<?php

//Include core plugin files.
include_once (dirname (__FILE__) . "/framework/RMPFramework.php");

$playlist = $_GET["file"];
$description = $_GET["description"];

$rmpPlayer = new RMPFramework(array('playlist' => $playlist, 'description' => $description));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>L2 Video Player</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.js"></script>
<script type="text/javascript" src="<?php echo RMP_FILES_URL ?>"></script>
<link rel = "stylesheet" href="css/rmp-player-style.css" type="text/css" />
</head>
<body>
<div id="rmp-description">
	<div id="rmp-video-inner"></div>
</div>
<div id="rmp-video">
	<div id="rmp-video-player">Loading the video...</div>
	<div id="rmp-playlist" class="rmp-playlist-thumbs"></div>
</div>
	
<script type="text/javascript">
	var urlParams = {};
	(function () {
	    var e,
	        a = /\+/g,  // Regex for replacing addition symbol with a space
	        r = /([^&=]+)=?([^&]*)/g,
	        d = function (s) { return decodeURIComponent(s.replace(a, " ")); },
	        q = window.location.search.substring(1);
	
	    while (e = r.exec(q))
	       urlParams[d(e[1])] = d(e[2]);
	})();
	
	<?php
		echo $rmpPlayer->buildJWCall();
	?>
	
	jwplayer('rmp-video-player').onComplete(
		function (event) {
			if (this.getPlaylist().length == this.getPlaylistItem().index + 1) {
				jwplayer('rmp-video-player').stop();
			} else {
				jwplayer('rmp-video-player').playlistNext();
			}
		}
	);
	
	jwplayer("rmp-video-player").play();
	
	jwplayer('rmp-video-player').onComplete(
		function (event) {
			if (this.getPlaylist().length == this.getPlaylistItem().index + 1) {
				jwplayer('rmp-video-player').stop();
			} else {
				jwplayer('rmp-video-player').playlistNext();
			}
		}
	);
	
	jwplayer('rmp-video-player').onPlaylist(
		function (event) {
			var vidPlaylist = $('#rmp-playlist');
			var vidPlaylistItems = '<ul class="rmp-playlist-thumbs">';
			var imageSrc = "";

			for (i = 0; i < event.playlist.length; i++) {
				if (event.playlist[i].image.length > 0) {
					imageSrc = '<img src="' + event.playlist[i].image + '" width="100">';
				}
				vidPlaylistItems += "<li><a href='#' onclick='jwplayer(\"rmp-video-player\").playlistItem(" + i + ")'>" + imageSrc + "</a></li>";
			}
			vidPlaylistItems += "</ul>";
			vidPlaylist.html(vidPlaylistItems);
		}
	);
	
	$(function () {
		var div = $('div.rmp-playlist-thumbs'),
        ul = $('ul.rmp-playlist-thumbs'),
        ulPadding = 15;

		var divWidth = div.width();

		div.css({ overflow: 'hidden' });

		div.mousemove(function (e) {
			if ($('ul.rmp-playlist-thumbs').find('li:last-child').length) {
				var ulWidth = $('ul.rmp-playlist-thumbs').find('li:last-child')[0].offsetLeft + $('ul.rmp-playlist-thumbs').find('li:last-child').outerWidth() + ulPadding;

				var left = (e.pageX - div.offset().left) * (ulWidth - divWidth) / divWidth;
				div.scrollLeft(left);
			}
		});
	});

</script>
</body>
</html>