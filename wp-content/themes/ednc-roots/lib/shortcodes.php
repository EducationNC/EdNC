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

  // Email signup form shortcode
  function email_signup_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(

    ), $atts) );

    $output = get_template_part('templates/email-signup');

    return $output;
  }
  add_shortcode('email-signup', 'email_signup_shortcode');
?>
