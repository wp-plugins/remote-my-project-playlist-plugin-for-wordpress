=== Remote My Project Playlist Plugin for WordPress ===
Contributors: Hollywood Tools LLC, jbmcmichael
Tags: Remote My Project, JW Player, Video, Flash, RTMP, Playlists, Audio, Image, HTML5, iPad, iPhone, iPod
Requires at least: 2.8.6
Tested up to: 3.3.1
Stable tag: 0.6.2

This plugin is provided by Hollywood Tools LLC.  It enables you to configure and embed a Remote My Project Playlist for use on your WordPress website.

== Description ==

The Remote My Project Playlist Plugin for WordPress makes it extremely easy to deliver your Remote My Project public videos through your WordPress website. This plugin has been developed by Hollywood Tools LLC., the creator of the Remote My Project, and allows for easy embedding of a Remote My Project Playlist in your WordPress posts.

<strong>Key Features</strong>

* Shortcode system to allow easy embedding of playlists
* Can display video or image playlists

== Installation ==

1. Log into your WordPress administration page and select Add New from the Plugins menu
2. Enter Remote My Project in the Search Box, then click Search Plugins
3. Click Install Now under the plug-in
4. Click on Plugins from the Plugin menu and make sure that the plug-in is activated
5. Install JW Player from the Remote My Project Admin in the Update JW Player Section

== Usage ==

This plugin only works with feeds from Remote My Project.

To see a list of working examples and documentation go to [Remote My Project](http://remotemyproject.com/wordpress-plugin/)

To see a live example of the plugin in action go to [L2 Digital](http://l2digital.com/work/)  


1. To use this you will need to create a Channel at Remote My Project.
2. Right click on the mRSS Feed link you want to show on your site and select Copy Link Address
3. In a Post or a Page enter the shortcode for the Remote My Project Plugin - [rmp-video playlist=""]
4. Between the quotes, past the Channel link you copied from Remote My Project
5. Save and Publish your Post or Page, you're done!

There are a few options you can set with the player:

type - either "playlist" (default) or "single"  
1. "playlist" will show thumbnails of all files in the playlist, and show the video in a popup window   
2. "single" shows a single video player, with all files in the playlist as a set of scrollable thumbnails below the video  

width - the width of the video player in single mode, 640px by default  
height - the height of the player in single mode, 480px by default  
autoplay - either "true" (default) or "false", sets if videos start playing once they are loaded  

Example of a full shortcode  
[rmp-video playlist="http://remotemyproject.net/rss/index/1" type="single" width="800" height="480" autoplay="false"]

== Requirements ==

* WordPress 2.8.6 or higher
* PHP 5.0 or higher
* The wp-content/uploads directory needs to be writable by the plugin.  This is likely already the case as WordPress stores your media and various other uploads here.
* JW Player v5.3 or higher.  This can be downloaded directly from the options page.

== Changelog ==

= 0.6.2 =
ENHANCEMENT Added width to the rmp-single player to aid in styling

= 0.6.1 =
BUG Fixed a bug with multiple shortcodes on a page

= 0.6 =
ENHANCEMENT Completely changed the inline video player.  It is now a cleaner scrollbar with popin video player.  No changes were made to shortcodes

= 0.5.4 =
BUG Fixed a dangling close div

= 0.5.3 =
BUG Fixed a problem with pathing on multisites

= 0.5.2 =
BUG Fixed a problem with improper escaping of special html strings

= 0.5.1 =
BUG Fixed a small issue with the image gallery not wrapping text properly

= 0.5 =
* FEATURE Added image galleries.  Can be a playlist-like layout, or an inline viewer

= 0.4.1 =
* BUG Fixed a rendering error in IE

= 0.4 =
* FEATURE Inline player mode.  Allows you to show your video feed inline, no popup, and still use playlists 
* FEATURE If you have a playlist of all images you will get just an image gallery.  In this mode autoplay, width, and height are ignored
* ENHANCEMENT If your video has no description the popup player shrinks to fit the content


= 0.3 =
* FEATURE Options pages added
* FEATURE You can upload a licensed version of JWPlayer or automatically grab the latest version from the options page
* FEATURE Users can now change the style of the playlist and player pop up box

= 0.2 =
* Complete rewrite
* More modular code to allow for easier updating
* Playlists now show images for other files in the list, and allow you to directly jump to them
* Playlist changes mean that playlists will now work in iOS

= 0.1 =
* Initial release