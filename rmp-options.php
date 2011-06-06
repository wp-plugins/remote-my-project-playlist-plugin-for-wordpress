<?php

if (is_admin()) {
	add_action("admin_menu", "rmp_plugin_menu");
	wp_register_style( 'rmp-admin-style', plugins_url('css/rmp-admin.css', __FILE__) );
	wp_enqueue_style( 'rmp-admin-style');
	wp_enqueue_script( 'tabby', plugins_url('js/jquery.textarea.js', __FILE__) );
}

// Build the admin and menu.
function rmp_plugin_menu() {
	add_menu_page("Remote My Project Title", "RMP Playlist", "administrator", "rmp", "rmp_plugin_pages");
	add_submenu_page("rmp", "Remote My Project Playlist Style", "Playlist Style", "administrator", "rmp-playlist-style", "rmp_plugin_pages");
	add_submenu_page("rmp", "Remote My Project Player Style", "Player Style", "administrator", "rmp-player-style", "rmp_plugin_pages");
	add_submenu_page("rmp", "Remote My Project JW Player Update", "Update JW Player", "administrator", "rmp-jw-update", "rmp_plugin_pages");
}

function rmp_plugin_pages() {
	switch ($_GET["page"]) {
		case "rmp-playlist-style" :
			require_once (dirname(__FILE__) . "/admin/rmp-playlist-style.php");
			break;
		case "rmp-player-style" :
			require_once (dirname(__FILE__) . "/admin/rmp-player-style.php");
			break;
		case "rmp-jw-update" :
			require_once (dirname(__FILE__) . "/admin/rmp-jw-update.php");
			break;
	}
}

?>
