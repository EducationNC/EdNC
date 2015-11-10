<?php

// Add Pleasant Plugin Notices for plugins that are required by theme
// https://github.com/S2web/Pleasant-Plugin-Notices

if ( is_admin() ){
  include_once get_stylesheet_directory().'/lib/plugin-support.php';
}

function my_theme_dependencies( $plugins ){
  $plugins['advanced-excerpt'] = array(
    'name' => 'Advanced Excerpt'
  );

  $plugins['gravityforms'] = array(
    'name' => 'Gravity Forms',
    'plugin_url' => 'http://www.gravityforms.com/'
  );

  $plugins['polldaddy'] = array(
    'name' => 'Polldaddy Polls & Ratings'
  );

  $plugins['the-events-calendar'] = array(
    'name' => 'The Events Calendar'
  );

  $plugins['events-calendar-pro'] = array(
    'name' => 'The Events Calendar Pro',
    'plugin_url' => 'https://theeventscalendar.com/product/wordpress-events-calendar-pro/'
  );

  $plugins['wp-pagenavi'] = array(
    'name' => 'WP-PageNavi'
  );

  return $plugins;
}
add_filter( 'pleasant_plugin_notices', 'my_theme_dependencies' );
