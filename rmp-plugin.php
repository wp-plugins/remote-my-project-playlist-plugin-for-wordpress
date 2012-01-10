<?php
/*
Plugin Name: Remote My Project Plugin for WordPress
Plugin URI: http://www.remotemyproject.com
Description: Plugin for displaying channels from Remote My Project
Author: JB McMichael
Version: 0.6
Author URI: http://www.remotemyproject.com

Copyright 2011 Hollywood Tools Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $wp_version;

define("RMP_JW_PLAYER_GA_VARS", "?utm_source=WordPress&utm_medium=Product&utm_campaign=WordPress");
define("RMP_FILE_PERMISSIONS", 'For tips on how to make sure this folder is writable please refer to <a href="http://codex.wordpress.org/Changing_File_Permissions">http://codex.wordpress.org/Changing_File_Permissions</a>.');

// Check for WP2.8 installation
if (!defined ('IS_WP28')) {
	define('IS_WP28', version_compare($wp_version, '2.8', '>=') );
}

// This works only in WP2.8 or higher
if (IS_WP28 == FALSE) {
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, the Remote My Project Plugin for WordPress works only under WordPress 2.8 or higher.') . '</strong></p></div>\';'));
	return;
}

// The plugin is only compatible with PHP 5.0 or higher
if (version_compare(phpversion(), "5.0", '<')) {
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, the Remote My Project Plugin for WordPress only works with PHP Version 5 or higher.') . '</strong></p></div>\';'));
	return;
}

wp_enqueue_script( 'jquery' );

//Include core plugin files.
include_once (dirname (__FILE__) . "/framework/RMPFramework.php");
include_once (dirname (__FILE__) . "/rmp-options.php");

register_activation_hook(__FILE__, "rmp_activation");

//Define the plugin directory and url for file access.
$uploads = wp_upload_dir();
if (isset($uploads["error"]) && !empty($uploads["error"])) {
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="fade updated"><p><strong>There was a problem completing activation of the Remote My Project Plugin for WordPress.  Please note that the Remote My Project Plugin for WordPress requires that the WordPress uploads directory exists and is writable.  ' . RMP_FILE_PERMISSIONS . '</strong></p></div>\';'));
	return;
}
$isHttps = is_ssl();
$pluginURL = $isHttps ? str_replace("http://", "https://", WP_PLUGIN_URL) : WP_PLUGIN_URL;
$uploadsURL = $isHttps ? str_replace("http://", "https://", $uploads["baseurl"]) : $uploads["baseurl"];
define("RMP_PLUGIN_DIR", WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define("RMP_PLUGIN_URL", $pluginURL . "/" . plugin_basename(dirname(__FILE__)));
define("RMP_FILES_DIR", $uploads["basedir"] . "/" . plugin_basename(dirname(__FILE__)));
define("RMP_FILES_URL", $uploadsURL . "/" . plugin_basename(dirname(__FILE__)));

if (!@is_dir(RMP_FILES_DIR)) {
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="fade updated"><p><strong>' . __('Activation of the Remote My Project Plugin for WordPress could not complete successfully.  The following directories could not be created automatically: </p><ul><li>- ' . RMP_FILES_DIR . '</li><li>- ' . RMP_FILES_DIR . '/configs</li><li>- ' . RMP_FILES_DIR . '/player</li></ul><p>Please ensure these directories are writable.  ' . RMP_FILE_PERMISSIONS) . '</strong></p></div>\';'));
} else if (!file_exists(RMPFramework::getPlayerPath())) {
	// Error if the player doesn't exist
	add_action('admin_notices', "rmp_install_notices");
}

function rmp_activation() {
	clearstatcache();
	if (!@is_dir(RMP_FILES_DIR)) {
		if (!@mkdir(RMP_FILES_DIR, 0755, true)) {
			add_action('admin_notices', create_function('', 'echo \'<div id="message" class="fade updated"><p><strong>' . __('There was a problem completing activation of the plugin.  The wp-content/uploads/rmp-wordpress-plugin directory could not be created.  Please ensure the WordPress uploads directory is writable.  ' . RMP_FILE_PERMISSIONS) . '</strong></p></div>\';'));
			return;
		}
		chmod(RMP_FILES_DIR, 0755);
		if (!@mkdir(RMP_FILES_DIR . "/player", 0755)) {
			add_action('admin_notices', create_function('', 'echo \'<div id="message" class="fade updated"><p><strong>' . __('There was a problem completing activation of the plugin.  The wp-content/uploads/rmp-wordpress-plugin/player directory could not be created.  Please ensure the WordPress uploads directory is writable.  ' . RMP_FILE_PERMISSIONS) . '</strong></p></div>\';'));
			return;
		}
	}
}

function rmp_install_notices() {
	?>
	<div id="message" class="fade updated">
		<form name="<?php echo RMP_KEY . "install"; ?>" method="post" action="admin.php?page=rmp-jw-update">
			<p>
			<strong><?php echo "To complete installation of the Remote My Project Plugin for WordPress you need to install JWPlayer, please click install.  "; ?></strong>
			<input class="button-secondary" type="submit" name="Install" value="Install Latest JW Player" />
			</p>
		</form>
	</div>
<?php }

add_shortcode( 'rmp-video', 'rmp_video_func' );

function rmp_video_func( $atts ) {
	extract( shortcode_atts( array(
		'playlist' => '',
		'config' => '',
		'type' => 'playlist',
		'autoplay' => 'true',
		'width' => '640',
		'height' => '480'
	), $atts ) );
	$rmp = new RMPFramework(array('playlist' => $playlist, 'jwconfig' => $config, 'type' => $type, 'autoplay' => $autoplay, 'width' => $width, 'height' => $height));
	$out = $rmp->buildPlaylist();
	return $out;
}

if (!is_admin()) {
    wp_register_script( 'fancybox', plugins_url('fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__) );
    wp_enqueue_script( 'fancybox' );
    wp_register_script( 'easing', plugins_url('fancybox/jquery.easing-1.3.pack.js', __FILE__) );
    wp_enqueue_script( 'easing' );
    wp_register_script( 'jscrollpane', plugins_url('js/jquery.jscrollpane.min.js', __FILE__) );
    wp_enqueue_script( 'jscrollpane' );
    wp_register_style( 'fancybox', plugins_url('fancybox/jquery.fancybox-1.3.4.css', __FILE__) );
	wp_enqueue_style( 'fancybox');
	wp_register_style( 'jscrollpane', plugins_url('css/jquery.jscrollpane.css', __FILE__) );
	wp_enqueue_style( 'jscrollpane');
	wp_register_style( 'rmp-playlist-style', plugins_url('css/rmp-playlist-style.css', __FILE__) );
	wp_enqueue_style( 'rmp-playlist-style');
	wp_register_style( 'rmp-player-style', plugins_url('css/rmp-player-style.css', __FILE__) );
	wp_enqueue_style( 'rmp-player-style');
	wp_enqueue_script("rmp-embedder", RMPFramework::getEmbedderURL());
	// embed the javascript file that makes the AJAX request
	wp_enqueue_script( 'rmp-ajax-request', plugin_dir_url( __FILE__ ) . 'js/rmp-scripts.js', array( 'jquery' ) );
}

// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
wp_localize_script( 'rmp-ajax-request', 'RMPAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
add_action( 'wp_ajax_nopriv_rmp-load-player', 'rmp_load_player' );
add_action( 'wp_ajax_rmp-load-player', 'rmp_load_player' );
add_action( 'wp_ajax_nopriv_rmp-load-player-js', 'rmp_load_player_js' );
add_action( 'wp_ajax_rmp-load-player-js', 'rmp_load_player_js' );
add_action( 'wp_ajax_rmp-verify-player', 'rmp_verify_player' );

function rmp_verify_player() {
	$response = false;
	if ($_POST["version"] != "null") {
		$response = true;
		update_option(RMP_KEY . "version", $_POST["version"]);
	}
	echo (int) $response;
	exit;
}
 
function rmp_load_player() {
	$playlist = $_POST['file'];
	$description = $_POST['description'];
	
 	$rmpPopup = new RMPFramework(array('playlist' => $playlist, 'description' => $description));

    $response = $rmpPopup->generatePopup();

    header( "Content-Type: text/html" );
    echo $response;

    exit;
}

function rmp_load_player_js() {
	$playlist = $_POST['file'];
	$description = $_POST['description'];

	$rmpJS = new RMPFramework(array('playlist' => $playlist, 'description' => $description));
	$response = $rmpJS->buildJWCall();
	
	header( "Content-Type: text/javascript" );
	echo $response;
	
	exit;
}
?>