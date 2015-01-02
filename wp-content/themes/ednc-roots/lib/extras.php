<?php
/**
 * Define image sizes
 */



/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Manage output of wp_title()
 */
function roots_wp_title($title) {
  if (is_feed()) {
    return $title;
  }

  $title .= get_bloginfo('name');

  return $title;
}
add_filter('wp_title', 'roots_wp_title', 10);

// Load CSS to TinyMCE editor
function add_mce_css( $mce_css ) {
  if ( ! empty( $mce_css ) )
  $mce_css .= ',';

  $mce_css .= get_template_directory_uri() . '/assets/public/css/editor-style.css';

  return $mce_css;
}
add_filter( 'mce_css', 'add_mce_css' );
