<?php

namespace Roots\Sage\Media;

/**
 * Define image sizes
 */

add_image_size('medium-square', 400, 400, true);
add_image_size('bio-headshot', 220, 220, true);
add_image_size('featured-large', 1240, 525, true);
add_image_size('featured-medium', 747, 421, true);

add_action('init', function() {
  remove_image_size('guest-author-32');
  remove_image_size('guest-author-64');
  remove_image_size('guest-author-96');
  remove_image_size('guest-author-128');
});


/**
 * Improve quality of auto-generated thumbnails
 */

function thumbnail_quality( $quality ) {
   return 100;
}
add_filter( 'jpeg_quality', __NAMESPACE__ . '\\thumbnail_quality' );
add_filter( 'wp_editor_set_quality', __NAMESPACE__ . '\\thumbnail_quality' );


/**
 * Enable adding images with custom image sizes in posts through media library
 * http://kucrut.org/insert-image-with-custom-size-into-post/
 */
function insert_custom_image_sizes( $sizes ) {
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
add_filter( 'image_size_names_choose', __NAMESPACE__ . '\\insert_custom_image_sizes' );

/**
 * Scale up images functionality in "Edit image" ...
 * See http://core.trac.wordpress.org/ticket/23713
 * This is slightly changed function of image_resize_dimensions() in wp-icludes/media.php
 */
function image_resize_dimensions( $nonsense, $orig_w, $orig_h, $dest_w, $dest_h, $crop = false) {

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
add_filter( 'image_resize_dimensions', __NAMESPACE__ . '\\image_resize_dimensions', 1, 6 );


/**
 * Add an HTML class to MediaElement.js container elements to aid styling.
 *
 * http://www.cedaro.com/blog/customizing-mediaelement-wordpress/
 *
 * Extends the core _wpmejsSettings object to add a new feature via the
 * MediaElement.js plugin API.
 */
function mejs_add_container_class() {
	if ( ! wp_script_is( 'mediaelement', 'done' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var settings = window._wpmejsSettings || {};
		settings.features = settings.features || mejs.MepDefaults.features;
		settings.features.push( 'exampleclass' );
		MediaElementPlayer.prototype.buildexampleclass = function( player ) {
			player.container.addClass( 'media-embed-container' );
		};
	})();
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', __NAMESPACE__ . '\\mejs_add_container_class' );

// Fix bug where Instagram embeds don't work in core with 'www.'
add_action('init', function() {
  wp_oembed_add_provider('#https?://(www\.)?instagr(\.am|am\.com)/p/.*#i', 'https://api.instagram.com/oembed', true );
});

// Google Maps embed
function wpgm_embed_handler_googlemapsv1( $matches, $attr, $url, $rawattr ) {
	if ( ! empty( $rawattr['width'] ) && ! empty( $rawattr['height'] ) ) {
		$width  = (int) $rawattr['width'];
		$height = (int) $rawattr['height'];
	} else {
		list( $width, $height ) = wp_expand_dimensions( 425, 326, $attr['width'], $attr['height'] );
	}
	return apply_filters( 'embed_googlemapsv1', "<div class='entry-content-asset'><iframe width='{$width}' height='{$height}' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://www.google.com/maps/embed/v1/place?q=" . esc_attr($matches[1]) . "&key=AIzaSyCI7Osh6uj1glo7DmUKY4lRJFVBey4pf1Y'></iframe></div>" );
};
wp_embed_register_handler( 'googlemapsv1', '#https?://www.google.com/maps/place/(.*?)/#i', __NAMESPACE__ . '\\wpgm_embed_handler_googlemapsv1' );


/**
 * Custom gallery shortcode
 *
 */
remove_shortcode('gallery');
add_shortcode('gallery', __NAMESPACE__ . '\\ednc_gallery');

function ednc_gallery($attr) {
  $post = get_post();

  static $instance = 0;
  $instance++;

  if ( ! empty( $attr['ids'] ) ) {
    // 'ids' is explicitly ordered, unless you specify otherwise.
    if ( empty( $attr['orderby'] ) ) {
      $attr['orderby'] = 'post__in';
    }
    $attr['include'] = $attr['ids'];
  }

  /**
   * Filter the default gallery shortcode output.
   *
   * If the filtered output isn't empty, it will be used instead of generating
   * the default gallery template.
   *
   * @since 2.5.0
   *
   * @see gallery_shortcode()
   *
   * @param string $output The gallery output. Default empty.
   * @param array  $attr   Attributes of the gallery shortcode.
   */
  $output = apply_filters( 'post_gallery', '', $attr );
  if ( $output != '' ) {
    return $output;
  }

  $atts = shortcode_atts( array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID',
    'id'         => $post ? $post->ID : 0,
    'itemtag'    => 'dl',
    'icontag'    => 'dt',
    'captiontag' => 'dd',
    'columns'    => 3,
    'size'       => 'thumbnail',
    'include'    => '',
    'exclude'    => '',
    'link'       => '',
    'fullwidth'  => '',
    'collage'    => ''
  ), $attr, 'gallery' );

  $id = intval( $atts['id'] );

  if ( ! empty( $atts['include'] ) ) {
    $_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

    $attachments = array();
    foreach ( $_attachments as $key => $val ) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  } elseif ( ! empty( $atts['exclude'] ) ) {
    $attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
  } else {
    $attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
  }

  if ( empty( $attachments ) ) {
    return '';
  }

  if ( is_feed() ) {
    $output = "\n";
    foreach ( $attachments as $att_id => $attachment ) {
      $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
    }
    return $output;
  }

  $itemtag = tag_escape( $atts['itemtag'] );
  $captiontag = tag_escape( $atts['captiontag'] );
  $icontag = tag_escape( $atts['icontag'] );
  $valid_tags = wp_kses_allowed_html( 'post' );
  if ( ! isset( $valid_tags[ $itemtag ] ) ) {
    $itemtag = 'dl';
  }
  if ( ! isset( $valid_tags[ $captiontag ] ) ) {
    $captiontag = 'dd';
  }
  if ( ! isset( $valid_tags[ $icontag ] ) ) {
    $icontag = 'dt';
  }

  $columns = intval( $atts['columns'] );
  $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
  $float = is_rtl() ? 'right' : 'left';

  $selector = "gallery-{$instance}";

  $fullwidth = $atts['fullwidth'];
  if ($fullwidth == true) {
    $output = "</div><!-- col --></div><!-- row --></div><!-- container --><div class='container-fluid'><div class='row'>";
    $fullwidth = 'fullwidth';
  }

  $collage = $atts['collage'];
  if ($collage == true) {
    $collage = 'collage';
    $output .="<div class='collage-wrapper'>";
  }

  $size_class = sanitize_html_class( $atts['size'] );
  $output .= "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} {$fullwidth} {$collage}'>";

  $i = 0;
  foreach ( $attachments as $id => $attachment ) {

    $attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
    if ( ! empty( $atts['link'] ) && 'post' === $atts['link'] ) {
      $image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
    } elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
      $image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
    } else {
      $image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, array('alt' => $attachment->post_content) );
    }
    $image_meta  = wp_get_attachment_metadata( $id );

    $orientation = '';
    if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
      $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
    }
    $output .= "<div class='gallery-item {$orientation}'>";
    $output .= "
      <div class='gallery-icon'>
        $image_output
      </div>";
    if ( $captiontag && trim($attachment->post_excerpt) ) {
      $output .= "
        <div class='wp-caption-text gallery-caption' id='$selector-$id'>
        " . wptexturize($attachment->post_excerpt) . "
        </div>";
    }
    $output .= "</div>";
		if ( $columns > 0 && ++$i % $columns == 0 && $atts['collage'] == false ) {
			$output .= '<br style="clear: both" />';
		}
  }

	if ( $columns > 0 && $i % $columns !== 0 && $atts['collage'] == false ) {
		$output .= "
			<br style='clear: both' />";
	}

  $output .= "
    </div>\n";

  if ($collage == true) {
    $output .="</div>";
  }

  if ($fullwidth == true) {
    $output .= "</div></div><div class='container'><div class='row'><div class='col-md-7 col-md-push-2point5'>";
  }

  return $output;
}


/*
 * Extend Media Manager Gallery settings
 *
 * Utilizes Backbone templates
 */
 function extend_media_manager_gallery_settings() {
  // define your backbone template;
  // the "tmpl-" prefix is required,
  // and your input field should have a data-setting attribute
  // matching the shortcode name
  ?>
  <script type="text/html" id="tmpl-ednc-custom-gallery-setting">
    <label class="setting">
      <span><?php _e('Full-width?'); ?></span>
      <input type="checkbox" data-setting="fullwidth" />
    </label>
    <label class="setting">
      <span><?php _e('Collage?'); ?></span>
      <input type="checkbox" data-setting="collage" />
    </label>
  </script>

  <script>

    jQuery(document).ready(function(){

      // add your shortcode attribute and its default value to the
      // gallery settings list; $.extend should work as well...
      _.extend(wp.media.gallery.defaults, {
        link: 'file',
        fullwidth: false,
        collage: false
      });

      // merge default gallery settings template with yours
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('ednc-custom-gallery-setting')(view);
        }
      });

    });

  </script>
  <?php
}
add_action('print_media_templates', __NAMESPACE__ . '\\extend_media_manager_gallery_settings');
