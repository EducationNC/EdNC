<?php
/**
 * Custom gallery shortcode
 *
 */

remove_shortcode('gallery');
add_shortcode('gallery', 'ednc_gallery');

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
 function ednc_extend_media_manager_gallery_settings() {
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
add_action('print_media_templates', 'ednc_extend_media_manager_gallery_settings');



/**
 * Clean up gallery_shortcode()
 *
 * Re-create the [gallery] shortcode and use thumbnails styling from Bootstrap
 * The number of columns must be a factor of 12.
 *
 * @link http://getbootstrap.com/components/#thumbnails
 */
function roots_gallery($attr) {
  $post = get_post();

  static $instance = 0;
  $instance++;

  if (!empty($attr['ids'])) {
    if (empty($attr['orderby'])) {
      $attr['orderby'] = 'post__in';
    }
    $attr['include'] = $attr['ids'];
  }

  $output = apply_filters('post_gallery', '', $attr);

  if ($output != '') {
    return $output;
  }

  if (isset($attr['orderby'])) {
    $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
    if (!$attr['orderby']) {
      unset($attr['orderby']);
    }
  }

  extract(shortcode_atts(array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID',
    'id'         => $post->ID,
    'itemtag'    => '',
    'icontag'    => '',
    'captiontag' => '',
    'columns'    => 4,
    'size'       => 'thumbnail',
    'include'    => '',
    'exclude'    => '',
    'link'       => ''
  ), $attr));

  $id = intval($id);
  $columns = (12 % $columns == 0) ? $columns: 4;
  $grid = sprintf('col-sm-%1$s col-lg-%1$s', 12/$columns);

  if ($order === 'RAND') {
    $orderby = 'none';
  }

  if (!empty($include)) {
    $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

    $attachments = array();
    foreach ($_attachments as $key => $val) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  } elseif (!empty($exclude)) {
    $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  } else {
    $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
  }

  if (empty($attachments)) {
    return '';
  }

  if (is_feed()) {
    $output = "\n";
    foreach ($attachments as $att_id => $attachment) {
      $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
    }
    return $output;
  }

  $unique = (get_query_var('page')) ? $instance . '-p' . get_query_var('page'): $instance;
  $output = '<div class="gallery gallery-' . $id . '-' . $unique . '">';

  $i = 0;
  foreach ($attachments as $id => $attachment) {
    switch($link) {
      case 'file':
        $image = wp_get_attachment_link($id, $size, false, false);
        break;
      case 'none':
        $image = wp_get_attachment_image($id, $size, false, array('class' => 'thumbnail img-thumbnail'));
        break;
      default:
        $image = wp_get_attachment_link($id, $size, true, false);
        break;
    }
    $output .= ($i % $columns == 0) ? '<div class="row gallery-row">': '';
    $output .= '<div class="' . $grid .'">' . $image;

    if (trim($attachment->post_excerpt)) {
      $output .= '<div class="caption hidden">' . wptexturize($attachment->post_excerpt) . '</div>';
    }

    $output .= '</div>';
    $i++;
    $output .= ($i % $columns == 0) ? '</div>' : '';
  }

  $output .= ($i % $columns != 0 ) ? '</div>' : '';
  $output .= '</div>';

  return $output;
}
if (current_theme_supports('bootstrap-gallery')) {
  remove_shortcode('gallery');
  add_shortcode('gallery', 'roots_gallery');
  add_filter('use_default_gallery_style', '__return_null');
}

/**
 * Add class="thumbnail img-thumbnail" to attachment items
 */
function roots_attachment_link_class($html) {
  $postid = get_the_ID();
  $html = str_replace('<a', '<a class="thumbnail img-thumbnail"', $html);
  return $html;
}
add_filter('wp_get_attachment_link', 'roots_attachment_link_class', 10, 1);
