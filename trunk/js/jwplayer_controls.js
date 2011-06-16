jwplayer('rmp-video-player-**player-div**').onComplete(
	function (event) {
		if (this.getPlaylist().length == this.getPlaylistItem().index + 1) {
			jwplayer('rmp-video-player-**player-div**').stop();
		} else {
			jwplayer('rmp-video-player-**player-div**').playlistNext();
		}
	}
);

jwplayer("rmp-video-player-**player-div**").play(**autoplay**);

jwplayer('rmp-video-player-**player-div**').onComplete(
	function (event) {
		if (this.getPlaylist().length == this.getPlaylistItem().index + 1) {
			jwplayer('rmp-video-player-**player-div**').stop();
		} else {
			jwplayer('rmp-video-player-**player-div**').playlistNext();
		}
	}
);

jwplayer('rmp-video-player-**player-div**').onPlaylist(
	function (event) {
		var vidPlaylist = jQuery('#rmp-playlist-**player-div**');
		var vidPlaylistItems = '<ul id="rmp-playlist-thumbs-**player-div**">';
		var imageSrc = "";

		for (i = 0; i < event.playlist.length; i++) {
			if (event.playlist[i].image.length > 0) {
				imageSrc = '<img src="' + event.playlist[i].image + '" width="100">';
			}
			vidPlaylistItems += "<li><a class='thumb' onclick='jwplayer(\"rmp-video-player-**player-div**\").playlistItem(" + i + ")'>" + imageSrc + "</a></li>";
		}
		vidPlaylistItems += '</ul>';
		vidPlaylist.html(vidPlaylistItems);
	}
);

jQuery(function () {
	var thumb_div = jQuery('#rmp-playlist-**player-div**')
    var thumb_ul = jQuery('#rmp-playlist-thumbs-**player-div**')
    ulPadding = 15;
	
	jQuery('#rmp-playlist-thumbs-**player-div** li a.thumb').hover(function(){
		jQuery(this).css('cursor','pointer');
	});
	
	thumb_div.css('width', '**player-width**');
	
	var divWidth = thumb_div.width();

	thumb_div.css('overflow', 'hidden');

	thumb_div.mousemove(function (e) {
		if (jQuery('#rmp-playlist-thumbs-**player-div**').find('li:last-child').length) {
			var ulWidth = jQuery('#rmp-playlist-thumbs-**player-div**').find('li:last-child')[0].offsetLeft + jQuery('#rmp-playlist-thumbs-**player-div**').find('li:last-child').outerWidth() + ulPadding;

			var left = (e.pageX - thumb_div.offset().left) * (ulWidth - divWidth) / divWidth;
			thumb_div.scrollLeft(left);
		}
	});
});
