<?php
/*
Plugin Name: WP Embed Facebook
Plugin URI: http://www.wpembedfb.com
Description: Embed any public Facebook video, photo, album, event, page, comment, profile, or post. Add Facebook comments to all your site, insert facebook social plugins (like, save, send, share, follow, quote, comments) anywhere on your site. View the <a href="http://www.wpembedfb.com/demo-site/" title="plugin website" target="_blank">demo site</a>.
Author: Miguel Sirvent
Version: 2.1.4
Author URI: http://www.wpembedfb.com
Text Domain: wp-embed-facebook
Domain Path: /lang
*/

require_once( plugin_dir_path( __FILE__ ) . 'lib/class-wp-embed-fb-plugin.php' );
WP_Embed_FB_Plugin::hooks();

require_once( WP_Embed_FB_Plugin::path() . 'lib/class-wef-social-plugins.php' );


/** @see WP_Embed_FB_Plugin::install */
register_activation_hook( __FILE__, 'WP_Embed_FB_Plugin::install' );

/** @see WP_Embed_FB_Plugin::uninstall */
register_uninstall_hook( __FILE__, 'WP_Embed_FB_Plugin::uninstall' );

/** @see WP_Embed_FB_Plugin::deactivate */
register_deactivation_hook( __FILE__, 'WP_Embed_FB_Plugin::deactivate' );


require_once(  WP_Embed_FB_Plugin::path() . 'lib/class-wp-embed-fb.php' );

/* Magic here */
require_once(  WP_Embed_FB_Plugin::path() . 'lib/class-wef-magic-embeds.php' );
WEF_Magic_Embeds::hooks();


if ( WP_Embed_FB_Plugin::get_option( 'auto_comments_active' ) === 'true' ) {
	require_once(  WP_Embed_FB_Plugin::path() . 'lib/class-wef-comments.php' );
	WEF_Comments::hooks();
}

if ( is_admin() ) {
	require_once(  WP_Embed_FB_Plugin::path() . 'lib/class-wp-embed-fb-admin.php' );
	WP_Embed_FB_Admin::hooks();

	/** @see WP_Embed_FB_Admin::add_action_link */
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'WP_Embed_FB_Admin::add_action_link' );
}

