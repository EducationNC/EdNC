<?php
// New

/**
 * Intro text shortcode
 * UI by Shortcake plugin
 */

  // Register shortcode
  function intro_text_shortcode($atts, $inner_content = null) {
    extract( shortcode_atts( array(
      // 'content' => '',
    ), $atts) );

    ob_start();
    ?>

    <div class="article-intro-text">
      <?php echo apply_filters( 'the_content', $inner_content ); ?>
    </div>

    <?php
    return ob_get_clean();
  }
  add_shortcode('intro-text', 'intro_text_shortcode');

  // Register shortcake UI
  shortcode_ui_register_for_shortcode(
    'intro-text',
    array(
      // Display label. String. Required.
      'label' => 'Intro Text',
      // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
      'listItemImage' => 'dashicons-text',
      // Inner content. Optional.
      'inner_content' => array(
				'label'        => 'Intro text'
			),
      // Available shortcode attributes and default values. Required. Array.
      // Attribute model expects 'attr', 'type' and 'label'
      // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
      // 'attrs' => array(
      //   array(
      //     'label' => 'Intro text',
      //     'attr'  => 'content',
      //     'type'  => 'textarea',
      //   )
      // )
    )
  );


/**
 * Full-bleed text shortcode
 * UI by Shortcake plugin
 */

  // Register shortcode
  function full_bleed_text_shortcode($atts, $inner_content = null) {
    extract( shortcode_atts( array(
      // 'content' => '',
      'cite' => '',
      'bg_color' => 'dark'
    ), $atts) );

    ob_start();
    ?>

    </div><!-- col -->
    </div><!-- row -->
    </div><!-- container -->

    <div class="container-fluid full-bleed-text theme-<?php echo $bg_color; ?>">
      <div class="row">
        <div class="col-md-7 col-centered content">
          <?php echo apply_filters('the_content', $inner_content); ?>
          <?php if ( ! empty( $cite ) ) { ?>
              <cite>&mdash;<?php echo esc_html( $cite ); ?></cite>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="container">
    <div class="row">
    <div class="col-md-7 col-md-push-2point5">

    <?php
    return ob_get_clean();
  }
  add_shortcode('full-bleed-text', 'full_bleed_text_shortcode');

  // Register shortcake UI
  shortcode_ui_register_for_shortcode(
    'full-bleed-text',
    array(
      // Display label. String. Required.
      'label' => 'Full Bleed Text',
      // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
      'listItemImage' => 'dashicons-editor-quote',
      // Inner content. Optional.
      'inner_content' => array(
				'label'        => 'Content'
			),
      // Available shortcode attributes and default values. Required. Array.
      // Attribute model expects 'attr', 'type' and 'label'
      // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
      'attrs' => array(
        array(
          'label'       => 'Quotation Citation',
          'attr'        => 'cite',
          'type'        => 'text',
          'placeholder' => 'Firstname Lastname',
          'description' => 'Optional',
        ),
        array(
          'label' => 'Background Color',
          'attr'  => 'bg_color',
          'type'  => 'select',
          'options' => array(
            'dark' => 'Dark',
            'light' => 'Light'
          )
        )
      )
    )
  );

/**
 * Parallax Image shortcode
 * UI by Shortcake plugin
 */

  // Register shortcode
  function parallax_image_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'image_id' => '',
      'caption' => '',
      'floating_text' => '',
      'floating_image_id' => ''
    ), $atts) );

    // If background image is set, get the URL of full sized image
    if (isset($image_id)) {
      $img = wp_get_attachment_image_src($image_id, 'full');
      $img_lg = wp_get_attachment_image_src($image_id, 'large');
    }

    // If floating image is set, get the URL of full sized image
    if (isset($floating_image_id)) {
      $floating_img = wp_get_attachment_image_src($floating_image_id, 'full');
    }

    ob_start();
    ?>

    </div><!-- col -->
    </div><!-- row -->
    </div><!-- container -->

    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/public/js/imagesloaded.pkgd.min.js"></script>

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        var ismobileorIE = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|MSIE|Trident|Edge/i.test(navigator.userAgent);

        // only do parallax if this is not mobile
        if (!ismobileorIE) {
          var img = $('#parallax-<?php echo $image_id; ?> .parallax-img');

          // Set up CSS for devices that support parallax
          img.css({'top': '-50%', 'position':'absolute'});

          // Do it on init
  	      parallax(img);

          // Happy JS scroll pattern is jittery, so I'm >:(
          // var scrollTimeout;  // global for any pending scrollTimeout
    			// $(window).scroll(function () {
    			// 	if (scrollTimeout) {
    			// 		// clear the timeout, if one is pending
    			// 		clearTimeout(scrollTimeout);
    			// 		scrollTimeout = null;
    			// 	}
    			// 	scrollTimeout = setTimeout(parallax, 10);
    			// });

          // Not happy scroll pattern, but it works smoothly at least
          $(window).scroll(function(){
            parallax(img);
          });
        }
      });
    </script>

    <div class="container-fluid full-bleed-image" id="parallax-<?php echo $image_id; ?>">
      <div class="row">
        <div class="image-holder parallax">
          <?php if ( ! empty($image_id) ) { ?>
            <div class="parallax-img hidden-xs" style="background-image:url('<?php echo $img[0]; ?>')"></div>
            <img class="visible-xs-block" src="<?php echo $img_lg[0]; ?>" />
          <?php } ?>
          <?php if ( ! empty( $floating_image_id ) ) { ?>
            <div class="wash"></div>
            <img class="floating-img" src="<?php echo $floating_img[0]; ?>" />
          <?php } elseif ( ! empty( $floating_text ) ) { ?>
            <div class="wash"></div>
            <div class="floating-text">
              <?php echo esc_html( $floating_text ); ?>
            </div>
          <?php } ?>
        </div>
        <?php if ( ! empty( $caption ) ) { ?>
          <div class="caption">
            <?php echo esc_html( $caption ); ?>
          </div>
        <?php } ?>
      </div>
    </div>

    <div class="container">
    <div class="row">
    <div class="col-md-7 col-md-push-2point5">

    <?php
    return ob_get_clean();
  }
  add_shortcode('parallax-image', 'parallax_image_shortcode');

  // Register shortcake UI
  shortcode_ui_register_for_shortcode(
    'parallax-image',
    array(
      // Display label. String. Required.
      'label' => 'Parallax Image',
      // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
      'listItemImage' => 'dashicons-image-flip-vertical',
      // Available shortcode attributes and default values. Required. Array.
      // Attribute model expects 'attr', 'type' and 'label'
      // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
      'attrs' => array(
        array(
          'label'       => 'Image',
          'attr'        => 'image_id',
          'type'        => 'attachment',
          'libraryType' => array( 'image' ),
          'addButton'   => 'Select Image',
          'frameTitle'  => 'Select Image',
        ),
        array(
          'label' => 'Caption',
          'attr'  => 'caption',
          'type'  => 'text',
        ),
        array(
          'label'       => 'Floating Image Overlay',
          'attr'        => 'floating_image_id',
          'type'        => 'attachment',
          'libraryType' => array( 'image' ),
          'addButton'   => 'Select Image',
          'frameTitle'  => 'Select Image',
          'description' => 'Optional'
        ),
        array(
          'label'       => 'Floating Text',
          'attr'        => 'floating_text',
          'type'        => 'text',
          'description' => 'Optional (will only appear if no floating image overlay is set)',
        ),
      )
    )
  );


/**
 * Columns shortcode
 * UI by Shortcake plugin
 */




/**
* NC STEM Center E-Update iframe embed shortcode
* UI by Shortcake plugin
*/





// Old / 2014-15 site
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
