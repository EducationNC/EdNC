<?php

  // Full-width section shortcode
  function fullwidth_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(

    ), $atts) );

    $output = '<div class="full-width">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('full-width', 'fullwidth_shortcode');

  // Aside shortcode
  function aside_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'align' => 'left'
    ), $atts ) );

    $output = '<div class="aside align'. $align . '">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('aside', 'aside_shortcode');

  // Callout shortcode
  function callout_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts ) );

    $output = '<div class="callout">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('callout', 'callout_shortcode');

  // Row shortcode (to wrap columns)
  function row_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts ) );

    $output = '<div class="row">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('row', 'row_shortcode');

  // Columns shortcode
  function column_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'size' => 'md-6'
    ), $atts ) );

    $output = '<div class="col-' . $size . '">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('column', 'column_shortcode');

  // Email signup form shortcode
  function email_signup_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts) );

    ob_start();
    get_template_part('templates/email-signup');

    return ob_get_clean();
  }
  add_shortcode('email-signup', 'email_signup_shortcode');

  // Donation social share shortcode
  function donate_share_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts) );

    ob_start();
    get_template_part('templates/donate-social-share');

    return ob_get_clean();
  }
  add_shortcode('donate-share', 'donate_share_shortcode');

  // NC STEM Center e-update iframe embed
  function stem_update_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'height' => '8000',
      'url' => ''
    ), $atts) );

    $output = '<iframe src="' . $url . '" width="700" height="' . $height . '"></iframe>';

    return $output;
  }
  add_shortcode('stem-update', 'stem_update_shortcode');
?>
