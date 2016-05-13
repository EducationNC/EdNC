<?php
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.wpembedfb.com
Description: Embed any public Facebook video, photo, album, event, page, profile, or post. Copy the facebook url to a single line on your post, or use shortcode [facebook url ] more information at <a href="http://www.wpembedfb.com" title="plugin website">www.wpembedfb.com</a>
Author: Miguel Sirvent
Version: 2.0.9.1
Author URI: http://www.wpembedfb.com
Text Domain: wp-embed-facebook
Domain Path: /lang
*/


require_once('lib/class-wp-embed-fb-plugin.php');
require_once('lib/class-wef-social-plugins.php');
require_once('lib/class-wp-embed-fb.php');

//Session start when there is a facebook app
add_action('init',array('WP_Embed_FB_Plugin','init'),999);
//Translation string
add_action('plugins_loaded',array('WP_Embed_FB_Plugin','plugins_loaded'));
//Donate or review notice
add_action('admin_notices',array('WP_Embed_FB_Plugin','admin_notices'));
add_action('wp_ajax_wpemfb_close_warning',array('WP_Embed_FB_Plugin','wpemfb_close_warning'));
add_action('wp_ajax_wpemfb_video_down',array('WP_Embed_FB_Plugin','wpemfb_video_down'));

register_activation_hook(__FILE__, array('WP_Embed_FB_Plugin', 'install') );
register_uninstall_hook(__FILE__, array('WP_Embed_FB_Plugin', 'uninstall') );
register_deactivation_hook(__FILE__, array('WP_Embed_FB_Plugin', 'deactivate'));

add_action('wp_enqueue_scripts', array('WP_Embed_FB_Plugin', 'wp_enqueue_scripts') );

if( get_option('wpemfb_fb_root','true') === 'true' )
	add_filter('the_content', array('WP_Embed_FB','fb_root'),10,1);

add_shortcode('facebook', array('WP_Embed_FB','shortcode') );

wp_embed_register_handler("wpembedfb","/(http|https):\/\/www\.facebook\.com\/([^<\s]*)/",array("WP_Embed_FB","embed_register_handler"));

if(is_admin()){
	require_once('lib/class-wp-embed-fb-admin.php');
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( 'WP_Embed_FB_Admin', 'add_action_link' ), 10 );
	add_action('admin_menu', array('WP_Embed_FB_Admin','add_page'));
	add_action('admin_init', array('WP_Embed_FB_Admin','admin_init'));
	add_action('admin_enqueue_scripts', array('WP_Embed_FB_Admin','admin_enqueue_scripts'), 10,1);
	add_action('in_admin_footer',array('WP_Embed_FB_Admin','in_admin_footer'));
}


