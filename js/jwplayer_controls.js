var rmpShown_**player-div** = false;

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

jwplayer('rmp-video-player-**player-div**').onPlaylistItem(
	function (event) {
		var playlistItems = jQuery('#rmp-playlist-thumbs-**player-div** li a.rmp-thumb img');
		playlistItems.each(function(index) {
			jQuery(this).removeClass('rmp-current-item');
		});
		var currentlyPlaying = playlistItems.get(this.getPlaylistItem().index);
		jQuery(currentlyPlaying).addClass('rmp-current-item');
		jQuery('#rmp-single-video-title-**player-div**').text(this.getPlaylistItem().title);
	}
);

jwplayer('rmp-video-player-**player-div**').onPlaylist(
	function (event) {
		if (!rmpShown_**player-div**) {
			rmpShown_**player-div** = true;
			var vidPlaylist = jQuery('#rmp-playlist-**player-div**');
			var vidPlaylistItems = '<ul id="rmp-playlist-thumbs-**player-div**">';
			var vidPlayerContainer = jQuery('#rmp-single-video-player-**player-div**');
			var imageSrc = "";
	
			for (i = 0; i < event.playlist.length; i++) {
				if (event.playlist[i].image.length > 0) {
					imageSrc = '<img src="' + event.playlist[i].image + '" width="120">';
				}
				vidPlaylistItems += "<li><a class='rmp-thumb' data-playlist-item='" + i + "'>" + imageSrc + "</a></li>";
			}
			vidPlaylistItems += '</ul>';
			vidPlaylist.html(vidPlaylistItems);
			
			vidPlayerContainer.hide();
			
			var lis = vidPlaylist.find('ul li');
			var liWidth = 0;
	
			for (var i = 0; i < lis.length; i++) {
				var item = lis.get(i);
				liWidth += jQuery(item).outerWidth(true);
			}
			
			vidPlaylist.find('ul').width(liWidth);
			vidPlaylist.jScrollPane({showArrows: true});
		}
	}
);

jQuery(function () {
	jQuery('#rmp-playlist-**player-div**').delegate('.rmp-thumb', 'click', function(e) {
		e.preventDefault();
		
		console.log('**player-div**');
		
		var vidContainer = jQuery('#rmp-single-video-player-**player-div**'),
			vidPlayer = jwplayer("rmp-video-player-**player-div**"),
			playlistItem = jQuery(this).data('playlist-item');
			
		if (vidContainer.is(':hidden')) {
			vidContainer.slideToggle('slow', function() {
				vidPlayer.playlistItem(playlistItem);
				vidPlayer.play();
			});
			
		} else {
			if (vidPlayer.getPlaylistItem().index === playlistItem) {
				vidPlayer.stop();
				vidContainer.slideToggle('slow');
				var currentlyPlaying = jQuery('#rmp-playlist-thumbs-**player-div** li a.rmp-thumb img').get(playlistItem);
				jQuery(currentlyPlaying).removeClass('rmp-current-item');
			} else {
				vidPlayer.playlistItem(playlistItem);
			}
		}
		
		
	});
});
