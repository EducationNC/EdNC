<?php
// Functions used in data-viz AJAX

add_action( 'wp_ajax_save_png', 'save_png_callback' );
add_action( 'wp_ajax_nopriv_save_png', 'save_png_callback' );

function save_png_callback() {
  check_ajax_referer( 'data-viz-ajax-nonce', 'security' );

  // Prep the encoded image string
  $post_id = $_POST['id'];
  $png_string = $_POST['png'];
  $png_string =  str_replace(['data:image/png;base64,', ' '], ['', '+'], $png_string);

  // Upload file to uploads directory temporarily
  $temp_upload = wp_upload_bits($post_id . '.png', null, base64_decode($png_string));

  // Move image to subdirectory
  $upload_dir = wp_upload_dir();
  $filename = '/data-viz/' . $post_id . '.png';
  rename($temp_upload['file'], $upload_dir['basedir'] . $filename);

  // Echo path to saved image
  echo $upload_dir['baseurl'] . $filename;
  die();
}
