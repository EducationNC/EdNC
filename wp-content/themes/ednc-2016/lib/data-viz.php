<?php
function clear_dataviz_transients($post_id, $post) {
  // HALP! I CAN'T GET THIS TO WORK!
  if ( 'data-viz' == $post->post_type )
    return;

  delete_transient( 'viz_' . $post->post_name );
  delete_transient( 'viz_lg_' . $post->post_name );
}
add_action( 'clean_post_cache', 'clear_dataviz_transients', 10, 2 );


function check_dataviz_transients_callback() {
  check_ajax_referer( 'data-viz-ajax-nonce', 'security' );

  $post_id = $_POST['id'];

  // delete_transient( 'viz_' . $post_id );
  // delete_transient( 'viz_lg_' . $post_id );

  $response['viz'] = utf8_encode(get_transient( 'viz_' . $post_id ));
  $response['viz_lg'] = utf8_encode(get_transient( 'viz_lg_' . $post_id ));

  echo json_encode($response);
  die();
}
add_action( 'wp_ajax_check_dataviz_transients', 'check_dataviz_transients_callback' );
add_action( 'wp_ajax_nopriv_check_dataviz_transients', 'check_dataviz_transients_callback' );


function set_dataviz_transients_callback() {
  check_ajax_referer( 'data-viz-ajax-nonce', 'security' );

  $post_id = $_POST['id'];

  $viz = $_POST['viz'];
  $viz_lg = $_POST['viz_lg'];

  set_transient( 'viz_' . $post_id, $viz, 1 * HOUR_IN_SECONDS );
  set_transient( 'viz_lg_' . $post_id, $viz_lg, 1 * HOUR_IN_SECONDS );

  die();
}
add_action( 'wp_ajax_set_dataviz_transients', 'set_dataviz_transients_callback' );
add_action( 'wp_ajax_nopriv_set_dataviz_transients', 'set_dataviz_transients_callback' );


function save_png_callback() {
  check_ajax_referer( 'data-viz-ajax-nonce', 'security' );

  $post_id = $_POST['id'];

  if ( false === ( $saved_png = get_transient( 'png_' . $post_id ) ) ) {
    // Prep the encoded image string
    $png_string = $_POST['png'];
    $png_string =  str_replace(['data:image/png;base64,', ' '], ['', '+'], $png_string);

    // Upload file to uploads directory temporarily
    $temp_upload = wp_upload_bits($post_id . '.png', null, base64_decode($png_string));

    // Move image to subdirectory
    $upload_dir = wp_upload_dir();
    $filename = '/data-viz/' . $post_id . '.png';
    rename($temp_upload['file'], $upload_dir['basedir'] . $filename);

    // Echo path to saved image
    $saved_png = $upload_dir['baseurl'] . $filename;
    set_transient( 'png_' . $post_id, $saved_png, 1 * HOUR_IN_SECONDS );
  }

  echo $saved_png;
  die();
}
add_action( 'wp_ajax_save_png', 'save_png_callback' );
add_action( 'wp_ajax_nopriv_save_png', 'save_png_callback' );
