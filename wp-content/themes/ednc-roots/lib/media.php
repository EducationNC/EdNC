<?php
/**
 * Define image sizes
 */

add_image_size('medium-square', 400, 400, true);
add_image_size('bio-headshot', 380, 425, true);
add_image_size('featured-thumbnail', 295, 295, true);
add_image_size('featured-thumbnail-squat', 264, 112, true);
add_image_size('featured-thumbnail-squat-wide', 564, 239, true);
remove_image_size('guest-author-32');
remove_image_size('guest-author-64');
remove_image_size('guest-author-96');
remove_image_size('guest-author-128');


/**
 * Improve quality of auto-generated thumbnails
 */

function alx_thumbnail_quality( $quality ) {
   return 100;
}
add_filter( 'jpeg_quality', 'alx_thumbnail_quality' );
add_filter( 'wp_editor_set_quality', 'alx_thumbnail_quality' );

/**
 * Enable adding images with custom image sizes in posts through media library
 * http://kucrut.org/insert-image-with-custom-size-into-post/
 */
function ednc_insert_custom_image_sizes( $sizes ) {
  global $_wp_additional_image_sizes;
  if ( empty($_wp_additional_image_sizes) ) {
    return $sizes;
  }

  // foreach ( $_wp_additional_image_sizes as $id => $data ) {
  //   if ( !isset($sizes[$id]) )
  //   $sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
  // }

  // I just want to do this with medium-square size for now
  $sizes['medium-square'] = 'Medium Square';

  return $sizes;
}
add_filter( 'image_size_names_choose', 'ednc_insert_custom_image_sizes' );

/**
 * Scale up images functionality in "Edit image" ...
 * See http://core.trac.wordpress.org/ticket/23713
 * This is slightly changed function of image_resize_dimensions() in wp-icludes/media.php
 */
function my_image_resize_dimensions( $nonsense, $orig_w, $orig_h, $dest_w, $dest_h, $crop = false) {

    if ( $crop ) {
        // crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
        $aspect_ratio = $orig_w / $orig_h;
        $new_w = min($dest_w, $orig_w);
        $new_h = min($dest_h, $orig_h);

        if ( !$new_w ) {
            $new_w = intval($new_h * $aspect_ratio);
        }

        if ( !$new_h ) {
            $new_h = intval($new_w / $aspect_ratio);
        }

        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);

        $s_x = floor( ($orig_w - $crop_w) / 2 );
        $s_y = floor( ($orig_h - $crop_h) / 2 );
    } else {
        // don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
        $crop_w = $orig_w;
        $crop_h = $orig_h;

        $s_x = 0;
        $s_y = 0;

        /* wp_constrain_dimensions() doesn't consider higher values for $dest :( */
        /* So just use that function only for scaling down ... */
        if ($orig_w >= $dest_w && $orig_h >= $dest_h ) {
            list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
        } else {
            $ratio = $dest_w / $orig_w;
            $w = intval( $orig_w  * $ratio );
            $h = intval( $orig_h * $ratio );
            list( $new_w, $new_h ) = array( $w, $h );
        }
    }

    // if the resulting image would be the same size or larger we don't want to resize it
    // Now WE need larger images ...
    //if ( $new_w >= $orig_w && $new_h >= $orig_h )
    if ( $new_w == $orig_w && $new_h == $orig_h )
        return false;

    // the return array matches the parameters to imagecopyresampled()
    // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

}
add_filter( 'image_resize_dimensions', 'my_image_resize_dimensions', 1, 6 );