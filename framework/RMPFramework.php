<?php

define("RMP", "rmpmodule_");

class RMPFramework
{
	private static $dir = RMP_PLUGIN_DIR;
	private static $url = RMP_PLUGIN_URL;
	private static $div_id = 1;
	
	protected $playlist;
	protected $jwconfig;
	protected $description;
	protected $autoplay = 'true';
	protected $type = 'playlist';
	protected $width = '640';
	protected $height = '480';
	
	function __construct() {
		$arguments = func_get_args();

        if(!empty($arguments))
            foreach($arguments[0] as $key => $property)
                if(property_exists($this, $key))
                    $this->{$key} = $property;
        $this->div_id = rand();
	}
	
	/**
	* Entry point for outputting the playlist
	* @return string The entire div containing the playlist
	**/
	public function buildPlaylist() {
		$arrPlaylist = array();
		$out;
		$arrPlaylist = $this->buildPlaylistArray();
			
		if ($this->isImagePlaylist($arrPlaylist)) {
			if ($this->type === 'single') {
				$out = $this->buildInlineImage($arrPlaylist);
			} else {
				$out = $this->buildPopupImage($arrPlaylist);
			}
		} else {
			if ($this->type === 'single') {
				$out = $this->buildInlineVideo();
			} else {
				$out = $this->buildPopupVideo($arrPlaylist);
			}
		}
				
		return $out;
	}
	
	/**
	* Build output for video playlist
	* @param $arrPlaylist The playlist as an array
	* @return $out HTML for the playlist
	**/
	protected function buildPopupVideo($arrPlaylist) {
		$out =  '<div id="rmp-playlist-container">';
		$dimensions = "{'width':'900','height':'480'}";

		foreach ( $arrPlaylist as $item ) {
			$dimensions = $this->generateDimensions($item);
			$out .= '<div class="rmp-showoff">' . 
						'<div class="rmp-image-link">' . 
							'<a rel="' . $dimensions . '" title="' . $item['title'] . '" href="' . plugins_url('player.php', dirname(__FILE__)) . '?file=' . $item['video'] . '&width=640&height=480&description=' . $item['desc'] . '"><img src="' . $item['thumbnail'] . '"></a>' . 
						'</div>' .
						'<div class="rmp-text-link">' .
							'<a rel="' . $dimensions . '" title="' . $item['title'] . '" href="' . plugins_url('player.php', dirname(__FILE__)) . '?file=' . $item['video'] . '&width=640&height=480&description=' . $item['desc'] . '">' . $item['title'] . '</a>' . 
						'</div>' . 
					'</div>';
		}
		$out .= "<div class='clear'></div></div>";
		
		return $out;
	}
	
	/**
	* Build an inline player
	* @return string HTML to show the player inline
	**/
	public function buildInlineVideo() {
		$out =  '<div class="rmp-single-container">' .
					'<div id="rmp-video-player-' . $this->div_id . '">Loading video...</div>' .
					'<div id="rmp-playlist-' . $this->div_id . '" class="rmp-playlist-thumbs"></div>' .
				'</div>';
		$out .= '<script type="text/javascript">';	
		$out .= $this->buildJWCall(true);
		$out .= '</script>';
		return $out;
	}
	
	/**
	* Build the image gallery
	* @param $arrPlaylist The playist as an array
	* @return string HTML to show the gallery
	**/
	protected function buildPopupImage($arrPlaylist) {
		$out = '<div class="rmp-images-container"><ul>';
		
		foreach ( $arrPlaylist as $item ) {
			$out .= '<li><a rel="gallery-' . $this->div_id .'" title="' . $item['title'] . '" href="' . $item['video'] . '">' .
						'<img src="' . $item['thumbnail'] . '">'.
					'</a></li>';
		}
		$out .= '</ul></div><div style="clear:both"></div>';
		$out .= '<script type="text/javascript">' .
					'jQuery("a[rel=gallery-' . $this->div_id . ']").fancybox({' .
						'"transitionIn"	:	"elastic",' .
						'"transitionOut":	"elastic",' .
						'"speedIn"		:	600, ' .
						'"speedOut"		:	200,' . 
						'"overlayShow"	:	true,' .
						'"type"			:	"image",' .
						'"titlePosition":	"over",' .
						'"margin"		:	0,' .
						'"padding"		: 0,' .
						'onComplete		: function() {' .
			    			'jQuery("#fancybox-title").css({"top":"-38px", "bottom":"auto", "font-weight": "bold"});' .
			    			'jQuery("#fancybox-close").css({"top":"-45px"});' .
			    		'}' .
					'});' .
				'</script>';
		return $out;
	}
	
	/**
	* Build the inline image gallery
	* @param $arrPlaylist The playlist as an array
	* @return string HTML to show the inline image gallery
	**/
	protected function buildInlineImage($arrPlaylist) {
		$out = '<div class="rmp-inline-images-container">' .
					'<div id="rmp-main-image-' . $this->div_id . '" class="rmp-main-image"><img id="rmp-big-' . $this->div_id . '"></div>' . 
						'<div id="rmp-image-thumbs-' . $this->div_id . '" class="rmp-playlist-thumbs">' . 
							'<ul>';
		foreach ($arrPlaylist as $item) {
			$out .= '<li>' .
						'<button rel="gallery-' . $this->div_id . '" title="' . $item['title'] . '" href="' . $item['video'] .'">' .
							'<img src="' . $item['thumbnail'] . '">' .
						'</button>' . 
					'</li>';
		}
		$out .= "</ul>" .
				"</div>" .
				"</div>" . 
				"</div>";
		$out .= '<script type="text/javascript">';
		$out .= str_replace( "**player-div**", $this->div_id,  file_get_contents( plugin_dir_url( dirname(__FILE__) ) . 'js/rmp-inline-image.js') );
		$out = str_replace( "**player-width**", $this->width, $out );
		$out = str_replace( "**player-height**", $this->height, $out );
		$out .= '</script>';
		return $out;
	}
	
	/**
	* Build the output for the ajax call
	* @response string HTML to display in the popup window
	**/
	public function generatePopup() {
		$out = 	'<div id="rmp-player-container">'; 
				if ( strlen($this->description) > 0 ) {
					$out .= '<div id="rmp-description">' .
						'<div id="rmp-video-inner">' . $this->description . '</div>' .
					'</div>';
				}
					$out .= '<div id="rmp-video">' .
						'<div id="rmp-video-player-' . $this->div_id . '">Loading the video...</div>' .
						'<div id="rmp-playlist-' . $this->div_id . '" class="rmp-playlist-thumbs"></div>' .
					'</div>' .
				'</div>';
		$out .= '<script type="text/javascript">';	
		$out .= $this->buildJWCall(true);
		$out .= '</script>';
		return $out;
	}
	
	/**
	* Writes out the javascript for the JW Player
	* @return string JS for the JW Embedder
	**/
	public function buildJWCall() {
		$out =  "jwplayer('rmp-video-player-" . $this->div_id . "').setup({" . 
					"flashplayer: '" . $this->getPlayerURL() . "'," . 
					"height: " . $this->height . "," .
					"width: " . $this->width . "," .
					"autoplay: '" . $this->autoplay . "'," .
					$this->jsonPlaylist() .
				"});";
		$out .= str_replace( "**player-div**", $this->div_id,  file_get_contents( plugin_dir_url( dirname(__FILE__) ) . 'js/jwplayer_controls.js') );
		$out = str_replace( "**autoplay**", $this->autoplay, $out );
		$out = str_replace( "**player-width**", $this->width, $out );
		return $out;
	}
	
	/**
	* Outputs a JSON version of the playlist so that playlists work on iOS devices
	* @return string JSON formatted playlist to add to the jwplayer call
	**/
	protected function jsonPlaylist() {
	  	$playlist = array();
	  	$out = '';
	  	if ( $this->isPlaylist($this->playlist) ) {
	  		$playlist = $this->buildPlaylistArray();
	  		$out = "playlist:[";
	  		foreach ($playlist as $item) {
	  			$out .= "{file:\"" . urldecode( html_entity_decode($item['video']) ) . "\"," . 
	  					"title:\"" . $item['title'] . "\"," . 
	  					"description:\"" . $item['desc'] . "\"," . 
	  					"image:\"" . $item['thumbnail'] . "\"},";
	  		}
	  		$out = substr($out, 0, -1);
	  		$out .="]";
	  	} else {
	  		$out = "file: \"" . urldecode( html_entity_decode($this->playlist) ) . "\"";
	  	}
	  	return $out;
	}
	
	/**
	* Generate an array from the playlist
	* @return array  The playlist as an array
	**/
	protected function buildPlaylistArray() {
		//Get the playlist then sanitize the contents
		//Tends to come through with unescaped ampersands which breaks xml
		$playlist_contents = file_get_contents($this->playlist);
		$playlist_contents = preg_replace("#(&(?!amp;))#U",'&amp;', $playlist_contents); 
		
		$doc = new DOMDocument(); 
		$doc->loadXML($playlist_contents); 
		$arrPlaylist = array(); 
		foreach ( $doc->getElementsByTagName('item') as $node ) { 
			$itemRSS = array ( 
						'title' => $node->getElementsByTagName('title')->item(0)->nodeValue, 
						'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue, 
						'video' => $node->getElementsByTagName('content')->item(0)->getAttribute('url'), 
						'thumbnail' => $node->getElementsByTagName('thumbnail')->item(0)->getAttribute('url') 
					); 
			array_push($arrPlaylist, $itemRSS); 
		}
		return $arrPlaylist;
	}
	
	/**
	* Generates the height and width of the popup window
	* @param $item array The current playlist item being checked
	* @return string The dimensions ready to be put into the href
	**/
	protected function generateDimensions($item) {
		//TODO: this function really shouldn't exist.  Fancybox should have some way of resizing to its contents size
		//$.fancybox.resize() currently does nothing, so this function exists
		$width = '900';
		$height = '480';
		if ( strlen($item['desc']) === 0 ) {
			$width = '640';
		}

		if ( $this->isPlaylist($item['video']) ) {
			$height = '550';
		}
		
		return "{'width':'" . $width . "','height':'" . $height . "'}";
	}
	
	/**
	* Check if a playlist file is another playlist
	* @param file The full path of the file
	* @return boolean True if its a playlist, false if not
	**/
	protected function isPlaylist($file) {
		$ext = substr($file, -4, 1);
		if ($ext === '.')
			return false;
		else
			return true;
	}
	
	/**
	* Check the contents of the playlist
	* Mainly a check to see if the entire thing is images
	* If so generate a different call for the popup
	* @return int Images if its all images 
	**/
	protected function isImagePlaylist($arrPlaylist) {
		$images = true;
		$ext;
		foreach ($arrPlaylist as $item) {
			$ext = substr($item['video'], -3, 3);
			if ($ext != 'jpg' and $ext != 'png' and $ext != 'gif') {
				$images = false;
			}
		}
		return $images;
	}
	
	/**
	* Returns the path to the player.swf.
	* @return string The path to the player.swf.
	*/
	public static function getPlayerPath() {
		return RMP_FILES_DIR . "/player/player.swf";
	}
	
	/**
	* Get the complete url to the primary (and execpted) player location.
	* @return string The url to the player.
	*/
	public static function getPlayerURL() {
		return RMP_FILES_URL . "/player/player.swf";
	}

	/**
	* Get the complete path to the JW Embbeder javascript file.
	* @return string The path to the JW Embedder.
	*/
	public static function getEmbedderPath() {
		return RMP_FILES_DIR . "/player/jwplayer.js";
	}
	
	/**
	* Get the complete URL for the JW Embedder javascript file.
	* @return string The complete URL to the JW Embedder.
	*/
	public static function getEmbedderURL() {
		return RMP_FILES_URL . "/player/jwplayer.js";
	}
}

?>