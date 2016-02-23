<?php

use Roots\Sage\Assets;

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

  set_transient( 'viz_' . $post_id, $viz, 1 * MINUTE_IN_SECONDS );
  set_transient( 'viz_lg_' . $post_id, $viz_lg, 1 * MINUTE_IN_SECONDS );

  die();
}
add_action( 'wp_ajax_set_dataviz_transients', 'set_dataviz_transients_callback' );
add_action( 'wp_ajax_nopriv_set_dataviz_transients', 'set_dataviz_transients_callback' );


function save_png_callback() {
  check_ajax_referer( 'data-viz-ajax-nonce', 'security' );

  $post_id = $_POST['id'];
  $post_title = $_POST['title'];
  $data_source = $_POST['source'];

  if ( false === ( $saved_png = get_transient( 'png_' . $post_id ) ) ) {
    /**
     * Plain image
     */
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
    set_transient( 'png_' . $post_id, $saved_png, 1 * MINUTE_IN_SECONDS );

    /**
     * Annotated image with title, EdNC logo, and source
     */
    // Set up  objects
    $image = new Imagick($upload_dir['basedir'] . $filename);
    $title = new ImagickDraw();
    $logo = new Imagick(realpath(str_replace(get_bloginfo('url'), get_home_path(), get_site_icon_url(64))));
    $source = new ImagickDraw();

    // Add whitespace to top and bottom of image
    $image->borderImage('white', 0, 64);

    // Settings
    $title->setFillColor('black');  // Black text
    $title->setFont('../wp-content/themes/ednc-2016/dist/fonts/Lato.ttf');
    $title->setFontSize(30);
    $title->setGravity(1);  // NORTHWEST
    $source->setFillColor('#44474D');
    $source->setFont('../wp-content/themes/ednc-2016/dist/fonts/Lato.ttf');
    $source->setFontSize(15);

    // Make sure source text line wraps
    function wordWrapAnnotation($image, $draw, $text, $maxWidth) {
      $text = trim($text);

      $words = preg_split('%\s%', $text, -1, PREG_SPLIT_NO_EMPTY);
      $lines = array();
      $i = 0;
      $lineHeight = 0;

      while (count($words) > 0) {
        $metrics = $image->queryFontMetrics($draw, implode(' ', array_slice($words, 0, ++$i)));
        $lineHeight = max($metrics['textHeight'], $lineHeight);

        // check if we have found the word that exceeds the line width
        if ($metrics['textWidth'] > $maxWidth or count($words) < $i) {
          // handle case where a single word is longer than the allowed line width (just add this as a word on its own line?)
          if ($i == 1)
            $i++;

          $lines[] = implode(' ', array_slice($words, 0, --$i));
          $words = array_slice($words, $i);
          $i = 0;
        }
      }

      return array($lines, $lineHeight);
    }
    list($lines, $lineHeight) = wordWrapAnnotation($image, $source, 'Source: ' . $data_source, 1100);
    for ($i = 0; $i < count($lines); $i++) {
      $image->annotateImage($source, 74, 706 + $i*$lineHeight, 0, $lines[$i]);
    }

    // Generate new image and save it
    $image->annotateImage($title, 10, 10, 0, $post_title);
    $image->compositeImage($logo, Imagick::COMPOSITE_DEFAULT, 0, 694);
    $filename_ednc = '/data-viz/' . $post_id . '-ednc.png';
    $success = $image->writeImage($upload_dir['basedir'] . $filename_ednc);
  }
  
  echo $saved_png;
  die();
}
add_action( 'wp_ajax_save_png', 'save_png_callback' );
add_action( 'wp_ajax_nopriv_save_png', 'save_png_callback' );
