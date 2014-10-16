<?php
/**
 * Theme setup stuff
 *
 * @package EducationNC
 */

function ednc_setup() {

	// Make theme available for translation.
	load_theme_textdomain( 'ednc', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
    // add_image_size( 'blog', 825, 425, true );

    // Remove theme support for customization
    remove_theme_support( 'post-formats' );
    remove_theme_support( 'custom-background' );
    remove_theme_support( 'custom-header' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary-left' => 'Primary Menu - Left Side',
        'primary-right' => 'Primary Menu - Right Side',
        'footer' => 'Footer Menu',
        'enology-side' => 'Enology Side Menu'
	) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
add_action( 'after_setup_theme', 'ednc_setup' );


// Enable shortcodes in widgets
add_filter('widget_text', 'do_shortcode');


// Remove height/width attributes on images so they can be responsive
function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );


// Add an options page using Advanced Custom Fields plugin
function my_acf_options_page_settings($settings) {
    $settings['title'] = 'Home Page &amp; Site-Wide Settings';
    $settings['pages'] = array(
        'Home Hero Images',
        'Home Callouts',
        'Footer Information',
        'Social Media Links',
        'Rotating Sidebar',
        'Theme Options'
    );

    return $settings;
}
// add_filter('acf/options_page/settings', 'my_acf_options_page_settings');


// Remove extraneous dashboard widgets
function ednc_clean_up_dashboard() {
	remove_meta_box( 'dashboard_quick_press',   'dashboard', 'side' );      //Quick Press widget
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );      //Recent Drafts
	remove_meta_box( 'dashboard_primary',       'dashboard', 'side' );      //WordPress.com Blog
	remove_meta_box( 'dashboard_secondary',     'dashboard', 'side' );      //Other WordPress News
	remove_meta_box( 'dashboard_incoming_links','dashboard', 'normal' );    //Incoming Links
	remove_meta_box( 'dashboard_plugins',       'dashboard', 'normal' );    //Plugins
    remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' );		//Modern Tribe dashboard widget
}
// add_action('wp_dashboard_setup', 'ednc_clean_up_dashboard', 999);
