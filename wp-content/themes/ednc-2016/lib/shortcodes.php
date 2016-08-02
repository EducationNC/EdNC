<?php

namespace Roots\Sage\Shortcodes;


if (function_exists('shortcode_ui_register_for_shortcode')) :
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
    add_shortcode('intro-text', __NAMESPACE__ . '\\intro_text_shortcode');

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

      <div class="container-fluid full-bleed-text theme-<?php echo $bg_color; ?> print-only">
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
      <div class="col-md-7 col-md-push-2point5 print-only">

      <?php
      return ob_get_clean();
    }
    add_shortcode('full-bleed-text', __NAMESPACE__ . '\\full_bleed_text_shortcode');

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
        $floating_img_alt = get_post_meta($floating_image_id, '_wp_attachment_image_alt', true);
      }

      ob_start();
      ?>

      </div><!-- col -->
      </div><!-- row -->
      </div><!-- container -->

      <?php if (!is_admin()) : ?>

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

              // Happy JS scroll pattern
              var scrollTimeout;  // global for any pending scrollTimeout
        			$(window).scroll(function () {
        				if (scrollTimeout) {
        					// clear the timeout, if one is pending
        					clearTimeout(scrollTimeout);
        					scrollTimeout = null;
        				}
        				scrollTimeout = setTimeout(parallax(img), 10);
        			});
            }
          });
        </script>

      <?php endif; ?>

      <div class="container-fluid full-bleed-image" id="parallax-<?php echo $image_id; ?> print-only">
        <div class="row">
          <div class="image-holder parallax">
            <?php if ( ! empty($image_id) ) { ?>
              <div class="parallax-img hidden-xs" style="background-image:url('<?php echo $img[0]; ?>')"></div>
              <img class="visible-xs-block" src="<?php echo $img_lg[0]; ?>" />
            <?php } ?>
            <?php if ( ! empty( $floating_image_id ) ) { ?>
              <div class="wash"></div>
              <img class="floating-img" src="<?php echo $floating_img[0]; ?>" alt="<?php echo $floating_img_alt; ?>" />
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
      <div class="col-md-7 col-md-push-2point5 print-only">

      <?php
      return ob_get_clean();
    }
    add_shortcode('parallax-image', __NAMESPACE__ . '\\parallax_image_shortcode');

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
          )
        )
      )
    );


  /**
   * Chapters shortcode
   * UI by Shortcake plugin
   */

     // Register shortcode
     function chapter_shortcode($atts, $content = null) {
       extract( shortcode_atts( array(
         'short_name' => '',
       ), $atts) );

       ob_start();
       ?>

       <a name="<?php echo sanitize_title($short_name); ?>" id="<?php echo sanitize_title($short_name); ?>" data-name="<?php echo $short_name; ?>" class="chapter mce-item-anchor"></a>

       <?php
       return ob_get_clean();
     }
     add_shortcode('chapter', __NAMESPACE__ . '\\chapter_shortcode');

     // Register shortcake UI
     shortcode_ui_register_for_shortcode(
       'chapter',
       array(
         // Display label. String. Required.
         'label' => 'Chapter',
         // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
         'listItemImage' => 'dashicons-editor-ol',
         // Available shortcode attributes and default values. Required. Array.
         // Attribute model expects 'attr', 'type' and 'label'
         // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
         'attrs' => array(
           array(
             'label' => 'Short Name',
             'attr'  => 'short_name',
             'type'  => 'text',
             'description' => 'This is the title that will appear in the chapter index',
           )
         )
       )
     );

   /**
    * Tableau Map shortcode
    * UI by Shortcake plugin
    */

     // Register shortcode
     add_shortcode('tableau-map', function($atts, $inner_content = null) {
       extract( shortcode_atts( array(
        // 'content' => '',
        'large' => '',
        'medium' => '',
        'small' => ''
       ), $atts) );

       $allowed_tags = array(
         'script' => array(
           'type' => [],
           'src' => []
         ),
         'div' => array(
           'class' => [],
           'style' => []
         ),
         'noscript' => [],
         'a' => array(
           'href' => []
         ),
         'img' => array(
           'alt' => [],
           'src' => [],
           'style' => []
         ),
         'object' => array(
           'class' => [],
           'width' => [],
           'height' => [],
           'style' => []
         ),
         'param' => array(
           'name' => [],
           'value' => []
         )
       );

       ob_start();
       ?>

       </div><!-- col -->
       </div><!-- row -->
       </div><!-- container -->

       <div class="map-container">
         <?php if (!empty($large)) { ?>
           <div class="map-desktop">
             <?php echo wp_kses(urldecode($large), $allowed_tags); ?>
           </div>
         <?php } else { ?>
           <div class="map-desktop">
             <?php echo wp_kses(urldecode($medium), $allowed_tags); ?>
           </div>
         <?php } ?>

         <div class="map-tablet print-only">
           <?php echo wp_kses(urldecode($medium), $allowed_tags); ?>
         </div>

         <?php if (!empty($small)) { ?>
           <div class="map-mobile">
             <?php echo wp_kses(urldecode($small), $allowed_tags); ?>
           </div>
         <?php } else { ?>
           <div class="map-mobile-scroll">
             <div class="alert alert-warning" role="alert">This map is not optimized for small displays. Please check back on a tablet or computer.</div>
             <?php echo wp_kses(urldecode($medium), $allowed_tags); ?>
           </div>
         <?php } ?>
       </div>

       <div class="container">
       <div class="row">
       <div class="col-md-7 col-md-push-2point5 print-only">

       <?php
       return ob_get_clean();
     });

     // Register shortcake UI
     shortcode_ui_register_for_shortcode(
       'tableau-map',
       array(
         // Display label. String. Required.
         'label' => 'Tableau Map Embed',
         // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
         'listItemImage' => 'dashicons-location-alt',
         // Available shortcode attributes and default values. Required. Array.
         // Attribute model expects 'attr', 'type' and 'label'
         // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
         'attrs' => array(
           array(
             'label'       => 'Large/Desktop (1024px) Embed Code',
             'attr'        => 'large',
             'type'        => 'textarea',
             'description' => 'Optional',
             'encode'      => true
           ),
           array(
             'label'       => 'Medium/Tablet (768px) Embed Code',
             'attr'        => 'medium',
             'type'        => 'textarea',
             'description' => 'Required',
             'encode'      => true
           ),
           array(
             'label'       => 'Small/Mobile (320 px) Embed Code',
             'attr'        => 'small',
             'type'        => 'textarea',
             'description' => 'Optional',
             'encode'      => true
           )
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



endif;

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
  add_shortcode('full-width', __NAMESPACE__ . '\\fullwidth_shortcode');

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
  add_shortcode('aside', __NAMESPACE__ . '\\aside_shortcode');

  // Callout shortcode
  function callout_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts ) );

    $output = '<div class="callout">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('callout', __NAMESPACE__ . '\\callout_shortcode');

  // Row shortcode (to wrap columns)
  function row_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts ) );

    $output = '<div class="row">';
    $output .= do_shortcode($content);
    $output .= '</div>';

    return $output;
  }
  add_shortcode('row', __NAMESPACE__ . '\\row_shortcode');

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
  add_shortcode('column', __NAMESPACE__ . '\\column_shortcode');

  // Email signup form shortcode
  function email_signup_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts) );

    ob_start();
    get_template_part('templates/components/email-signup');

    return ob_get_clean();
  }
  add_shortcode('email-signup', __NAMESPACE__ . '\\email_signup_shortcode');

  // Donation social share shortcode
  function donate_share_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts) );

    ob_start();
    get_template_part('templates/donate-social-share');

    return ob_get_clean();
  }
  add_shortcode('donate-share', __NAMESPACE__ . '\\donate_share_shortcode');

  // NC STEM Center e-update iframe embed
  function stem_update_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'height' => '8000',
      'url' => ''
    ), $atts) );

    $output = '<iframe src="' . $url . '" width="700" height="' . $height . '"></iframe>';

    return $output;
  }
  add_shortcode('stem-update', __NAMESPACE__ . '\\stem_update_shortcode');

  // Colophon
  function colophon_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
    ), $atts) );

    $output = '<span class="colophon"></span>';

    return $output;
  }
  add_shortcode('colophon', __NAMESPACE__ . '\\colophon_shortcode');
