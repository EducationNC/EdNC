<?php
/*
Plugin Name: Polls by OpinionStage
Plugin URI: http://www.opinionstage.com
Description: Adds a highly engaging social polling system to your site. Easily add polls to any post/page or to your sites sidebar.
Version: 9.3.0
Author: OpinionStage.com
Author URI: http://www.opinionstage.com
*/

/* --- Static initializer for Wordpress hooks --- */

define('OPINIONSTAGE_SERVER_BASE', "www.opinionstage.com"); /* Don't include the protocol, added dynamically */
define('OPINIONSTAGE_WIDGET_VERSION', '9.3.0');
define('OPINIONSTAGE_WIDGET_PLUGIN_NAME', 'Polls by OpinionStage');
define('OPINIONSTAGE_WIDGET_API_KEY', 'wp35e8');
define('OPINIONSTAGE_WIDGET_SHORTCODE', 'socialpoll');
define('OPINIONSTAGE_WIDGET_UNIQUE_ID', 'social-polls-by-opinionstage');
define('OPINIONSTAGE_WIDGET_UNIQUE_LOCATION', __FILE__);

require_once(WP_PLUGIN_DIR."/".OPINIONSTAGE_WIDGET_UNIQUE_ID."/opinionstage-functions.php");
require_once(WP_PLUGIN_DIR."/".OPINIONSTAGE_WIDGET_UNIQUE_ID."/opinionstage-widget.php");


/* --- Static initializer for Wordpress hooks --- */

add_shortcode(OPINIONSTAGE_WIDGET_SHORTCODE, 'opinionstage_add_poll');

// Post creation/edit hooks
add_action('admin_footer-post-new.php', 'opinionstage_poll_footer_admin');
add_action('admin_footer-post.php', 'opinionstage_poll_footer_admin');
add_action('admin_footer-page-new.php', 'opinionstage_poll_footer_admin');
add_action('admin_footer-page.php', 'opinionstage_poll_footer_admin');

// Post creation/edit hook for visual editing
add_action('init', 'opinionstage_poll_tinymce_addbuttons');

// Side menu
add_action('admin_menu', 'opinionstage_poll_menu');

add_action('admin_enqueue_scripts', 'opinionstage_load_scripts');

// Insert poll popup 
add_filter('admin_footer_text', 'opinionstage_add_poll_popup');

?>