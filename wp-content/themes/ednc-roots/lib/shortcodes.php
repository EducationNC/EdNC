<?php
// New

/**
 * Full-bleed quote shortcode
 * UI by Shortcake plugin
 */

  // Register shortcode
  function full_bleed_quote_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'content' => '',
      'cite' => '',
      'bg_color' => 'dark',
      'bg_image_id' => '',
      'parallax' => false
    ), $atts) );

    // If background image is set, get the URL of full sized image
    if ( ! empty($bg_image_id) ) {
      $img = wp_get_attachment_image_src($bg_image_id, 'full');
    }

    ob_start();
    ?>

    </div><!-- col -->
    </div><!-- row -->
    </div><!-- container -->

    <div class="container-fluid full-bleed-quote theme-<?php echo $bg_color; ?> <?php if (! empty($bg_image_id)) { echo 'bg-img'; } ?> <?php if ($parallax == true) { echo 'parallax'; } ?>">
      <div class="row">
        <?php if ( ! empty($bg_image_id) ) { ?>
          <img src="<?php echo $img[0]; ?>" />
        <?php } ?>
        <blockquote class="col-md-7 col-centered">
          <span><?php echo esc_html( $content ); ?></span><br/>
          <?php if ( ! empty( $cite ) ) { ?>
              <cite>&mdash;<?php echo esc_html( $cite ); ?></cite>
          <?php } ?>
        </blockquote>
      </div>
    </div>

    <div class="container">
    <div class="row">
    <div class="col-md-7 col-md-push-2point5">

    <?php
    return ob_get_clean();
  }
  add_shortcode('full-bleed-quote', 'full_bleed_quote_shortcode');

  // Register shortcake UI
  shortcode_ui_register_for_shortcode(
    'full-bleed-quote',
    array(
      // Display label. String. Required.
      'label' => 'Full Bleed Quote',
      // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
      'listItemImage' => 'dashicons-editor-quote',
      // Available shortcode attributes and default values. Required. Array.
      // Attribute model expects 'attr', 'type' and 'label'
      // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
      'attrs' => array(
        array(
          'label' => 'Quote',
          'attr'  => 'content',
          'type'  => 'textarea',
          'description' => 'Note: Do not use quotation marks'
        ),
        array(
          'label'       => 'Cite',
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
        ),
        array(
    			'label'       => 'Background Image (Optional)',
    			'attr'        => 'bg_image_id',
    			'type'        => 'attachment',
    			'libraryType' => array( 'image' ),
    			'addButton'   => 'Select Image',
    			'frameTitle'  => 'Select Image',
    		),
        array(
          'label' => 'Enable quote parallax',
          'attr'  => 'parallax',
          'type'  => 'checkbox'
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
      'floating_text' => ''
    ), $atts) );

    // If background image is set, get the URL of full sized image
    if (isset($image_id)) {
      $img = wp_get_attachment_image_src($image_id, 'full');
    }

    ob_start();
    ?>

    </div><!-- col -->
    </div><!-- row -->
    </div><!-- container -->

    <div class="container-fluid full-bleed-image parallax">
      <div class="row">
        <?php if ( ! empty($image_id) ) { ?>
          <img src="<?php echo $img[0]; ?>" />
        <?php } ?>
        <?php if ( ! empty( $floating_text ) ) { ?>
          <div class="floating-text">
            <?php echo esc_html( $floating_text ); ?>
          </div>
        <?php } ?>
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
          'label'       => 'Floating Text',
          'attr'        => 'floating_text',
          'type'        => 'text',
          'description' => 'Optional',
        )
      )
    )
  );


/**
 * Audio player shortcode
 * UI by Shortcake plugin
 */

  // Register shortcode
  function audio_player_shortcode($atts, $content = null) {
    extract( shortcode_atts( array(
      'audio_id' => '',
      'caption' => '',
      'image_id' => ''
    ), $atts) );

    // If background image is set, get the URL of full sized image
    if (isset($image_id)) {
      $img = wp_get_attachment_image_src($image_id, 'full');
    }

    ob_start();
    ?>

    <div class="audio-player">
      <?php if ( ! empty($image_id) ) { ?>
        <img src="<?php echo $img[0]; ?>" />
      <?php } ?>
      <?php
      // TODO: EMBED AUDIO PLAYER
      ?>
      <?php if ( ! empty( $caption ) ) { ?>
        <div class="caption">
          <?php echo esc_html( $caption ); ?>
        </div>
      <?php } ?>
    </div>

    <?php
    return ob_get_clean();
  }
  add_shortcode('audio-player', 'audio_player_shortcode');

  // Register shortcake UI
  shortcode_ui_register_for_shortcode(
    'audio-player',
    array(
      // Display label. String. Required.
      'label' => 'Audio Player',
      // Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
      'listItemImage' => 'dashicons-format-audio',
      // Available shortcode attributes and default values. Required. Array.
      // Attribute model expects 'attr', 'type' and 'label'
      // Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
      'attrs' => array(
        array(
          'label'       => 'Audio File',
          'attr'        => 'audio_id',
          'type'        => 'attachment',
          'libraryType' => array( 'audio' ),
          'addButton'   => 'Select file',
          'frameTitle'  => 'Select file',
        ),
        array(
          'label' => 'Caption',
          'attr'  => 'caption',
          'type'  => 'text',
        ),
        array(
          'label'       => 'Image (Optional)',
          'attr'        => 'image_id',
          'type'        => 'attachment',
          'libraryType' => array( 'image' ),
          'addButton'   => 'Select Image',
          'frameTitle'  => 'Select Image',
        )
      )
    )
  );


/**
 * B-roll video background shortcode
 * UI by Shortcake plugin
 */


/**
* Map shortcode
* UI by Shortcake plugin
*/



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
