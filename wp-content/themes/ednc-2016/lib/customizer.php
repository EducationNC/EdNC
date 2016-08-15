<?php

namespace Roots\Sage\Customizer;

use Roots\Sage\Assets;

/**
 * Add postMessage support
 */
function customize_register($wp_customize) {
  $wp_customize->get_setting('blogname')->transport = 'postMessage';

  // Remove unneeded sections
  $wp_customize->remove_section( 'static_front_page');

  // Site-wide alert settings
  $wp_customize->add_section(
    'header_alert',
    array(
      'title' => 'Site-Wide Alert Settings'
    )
  );
  $wp_customize->add_setting(
    'site_wide_alert_text',
    array()
  );
  $wp_customize->add_control(
    'site_wide_alert_text',
    array(
      'label' => 'Alert text entered here will show up at the top of every page.',
      'section' => 'header_alert',
      'type' => 'text',
      'priority' => 1
    )
  );
}
add_action('customize_register', __NAMESPACE__ . '\\customize_register');

/**
 * Customizer JS
 */
function customize_preview_js() {
  wp_enqueue_script('sage/customizer', Assets\asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
}
add_action('customize_preview_init', __NAMESPACE__ . '\\customize_preview_js');

/**
 * Allow non-administrators to access customizer
 */
 function customize_meta_cap( $caps, $cap, $user_id ) {
   $required_cap = 'edit_posts';
   if ( 'customize' === $cap && user_can( $user_id, $required_cap ) ) {
     $caps = array( $required_cap );
   }
   return $caps;
 }
 add_filter( 'map_meta_cap', __NAMESPACE__ . '\\customize_meta_cap', 10, 3 );
