<?php
/**
 * Customizer settings
 */

function ednc_customizer_settings($wp_customize) {
  /**
   * Front page settings
   */
  $wp_customize->add_section(
    'front_page_settings',
    array(
      'title' => 'Front Page Settings'
    )
  );

  $wp_customize->add_setting(
    'news_post_num',
    array(
      'sanitize_callback' => 'ednc_sanitize_integer'
    )
  );

  $wp_customize->add_control(
    'news_post_num',
    array(
      'label' => 'Enter the number of news posts to display',
      'section' => 'front_page_settings',
      'type' => 'number',
      'priority' => 1
    )
  );
}
add_action('customize_register', 'ednc_customizer_settings');

function ednc_sanitize_integer($input) {
  if (is_numeric($input)) {
    return intval($input);
  }
}
