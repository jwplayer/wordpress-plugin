<?php
/*
Plugin Name: JW Player Plugin
Plugin URI: http://www.jwplayer.com/
Description: This plugin allows you to easily upload and embed videos using the JW Player. The embedded video links can be signed, making it harder for viewers to steal your content.
Author: JW Player
Version: 0.10.1 beta
*/

define( 'JWPLAYER_PLUGIN_DIR', dirname( __FILE__ ) );

require_once( JWPLAYER_PLUGIN_DIR . '/include/jwplayer-api.class.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/admin.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/ajax.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/api.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/import.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/login.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/media.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/proxy.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/settings.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/shortcode.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/validation.php' );
require_once( JWPLAYER_PLUGIN_DIR . '/include/utils.php' );

// Default settings
define( 'JWPLAYER_PLUGIN_VERSION', '0.1' );
define( 'JWPLAYER_PLAYER', 'ALJ3XQCI' );
define( 'JWPLAYER_DASHBOARD', 'https://dashboard.jwplayer.com/' );
define( 'JWPLAYER_TIMEOUT', '0' );
define( 'JWPLAYER_CONTENT_MASK', 'content.jwplatform.com' );
define( 'JWPLAYER_NR_VIDEOS', '5' );
define( 'JWPLAYER_CUSTOM_SHORTCODE_OPTIONS', serialize( array( 'content', 'excerpt', 'strip' ) ) );
define( 'JWPLAYER_SHOW_WIDGET', false );
define( 'JWPLAYER_CUSTOM_SHORTCODE_PARSER', false );
define( 'JWPLAYER_CUSTOM_SHORTCODE_FILTER', 'content' );

$JWPLAYER_MEDIA_MIME_TYPES = array(
	'video/mp4',
	'video/flv',
	'video/webm',
	'audio/acc',
	'audio/mpeg',
	'audio/ogg',
);
define( 'JWPLAYER_MEDIA_MIME_TYPES', serialize( $JWPLAYER_MEDIA_MIME_TYPES ) );

$JWPLAYER_SOURCE_FORMAT_EXTENSIONS = array(
	'aac' => array( 'aac', 'm4a', 'f4a' ),
	'flv' => array( 'flv' ),
	'm3u8' => array( 'm3u', 'm3u8' ),
	'mp3' => array( 'mp3' ),
	'mp4' => array( 'mp4', 'm4v', 'f4v', 'mov' ),
	'rtmp' => array( 'rtmp', 'rtmpt', 'rtmpe', 'rtmpte' ),
	'smil' => array( 'smil' ),
	'vorbis' => array( 'ogg', 'oga' ),
	'webm' => array( 'webm' ),
);
define( 'JWPLAYER_SOURCE_FORMAT_EXTENSIONS', serialize( $JWPLAYER_SOURCE_FORMAT_EXTENSIONS ) );

/*
FitVids.js is not compatible with the JW Player 6 because it breaks the way the player
is embedded in the page. If you enable fitVids, the player will briefly show and
disappear immediately after. Patching fitVids would be the best solution, but because
fitVids is included with so many themes and plugins, it would take a lot of time
before all of them were updated too. As a solution, this plugin disables the fitVids,
by redeclaring the function before a player embed. If you want to disable that because
you've update the fitVids lib yourself, you can change the setting below to false.
*/
define( 'JWPLAYER_DISABLE_FITVIDS', true );

// Determine if we are using vip or regular wp
$jwplayer_which_env = null;
if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
	$jwplayer_which_env = 'wpvip';
} else {
	$jwplayer_which_env = 'wp';
}

// Execute when the plugin is enabled
function jwplayer_add_options() {
	// Add (but do not override) the settings
	add_option( 'jwplayer_player', JWPLAYER_PLAYER );
	add_option( 'jwplayer_timeout', JWPLAYER_TIMEOUT );
	add_option( 'jwplayer_content_mask', JWPLAYER_CONTENT_MASK );
	add_option( 'jwplayer_nr_videos', JWPLAYER_NR_VIDEOS );
	add_option( 'jwplayer_show_widget', JWPLAYER_SHOW_WIDGET );
	add_option( 'jwplayer_custom_shortcode_parser', JWPLAYER_CUSTOM_SHORTCODE_PARSER );
	add_option( 'jwplayer_shortcode_category_filter', JWPLAYER_CUSTOM_SHORTCODE_FILTER );
	add_option( 'jwplayer_shortcode_search_filter', JWPLAYER_CUSTOM_SHORTCODE_FILTER );
	add_option( 'jwplayer_shortcode_tag_filter', JWPLAYER_CUSTOM_SHORTCODE_FILTER );
	add_option( 'jwplayer_shortcode_home_filter', JWPLAYER_CUSTOM_SHORTCODE_FILTER );
}

if ( 'wpvip' == $jwplayer_which_env ) {
	if ( ! get_option( 'jwplayer_player' ) ) {
			jwplayer_add_options();
	}
} else if ( 'wp' == $jwplayer_which_env ) {
	register_activation_hook( __FILE__, 'jwplayer_add_options' );
}

// Initialize the JW Player Admin
jwplayer_admin_init();

// Initialize the login and logout pages:
jwplayer_login_init();

// Initialize the media pages:
jwplayer_media_init();

// Initialize the JW Player shortcode.
jwplayer_shortcode_init();

// Check for old plugin settings.
if ( 'wp' == $jwplayer_which_env ) {
	add_action( 'admin_menu', 'jwplayer_import_check_and_init' );
}
