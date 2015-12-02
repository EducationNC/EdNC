<?php
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.wpembedfb.com
Description: Embed any public Facebook video, photo, album, event, page, profile, or post. Copy the facebook url to a single line on your post, or use shortcode [facebook=url ] more information at <a href="http://www.wpembedfb.com" title="plugin website">www.wpembedfb.com</a>
Author: Miguel Sirvent
Version: 2.0.3
Author URI: http://profiles.wordpress.org/poxtron/
*/


require_once('lib/class-wp-embed-fb-plugin.php');
require_once('lib/class-wef-social-plugins.php');
require_once('lib/class-wp-embed-fb.php');

add_action('init',array('WP_Embed_FB_Plugin','init'));
add_action('admin_notices',array('WP_Embed_FB_Plugin','admin_notices'));
add_action('wp_ajax_close_warning',array('WP_Embed_FB_Plugin','close_warning'));

register_activation_hook(__FILE__, array('WP_Embed_FB_Plugin', 'install') );
register_uninstall_hook(__FILE__, array('WP_Embed_FB_Plugin', 'uninstall') );
register_deactivation_hook(__FILE__, array('WP_Embed_FB_Plugin', 'deactivate'));

add_action('wp_enqueue_scripts', array('WP_Embed_FB_Plugin', 'wp_enqueue_scripts') );

if( get_option('wpemfb_fb_root') === 'true' )
	add_filter('the_content', array('WP_Embed_FB_Plugin','fb_root'),10,1);

add_shortcode('facebook', array('WP_Embed_FB','shortcode') );

wp_embed_register_handler("wpembedfb","/(http|https):\/\/www\.facebook\.com\/([^<\s]*)/",array("WP_Embed_FB","embed_register_handler"));

if(is_admin()){
	require_once('lib/class-wp-embed-fb-admin.php');
	add_action('admin_menu', array('WP_Embed_FB_Admin','add_page'));
	add_action('admin_init', array('WP_Embed_FB_Admin','admin_init'));
	add_action('admin_enqueue_scripts', array('WP_Embed_FB_Admin','admin_enqueue_scripts'), 10,1);
	add_action('in_admin_footer',array('WP_Embed_FB_Admin','in_admin_footer'));
}


