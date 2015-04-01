<?php
/**
 * Customizer settings
 */

function ednc_customizer_settings($wp_customize) {
  /**
   * Site-wide alert settings
   */
  $wp_customize->add_section(
    'header_alert',
    array(
      'title' => 'Site-Wide Alert Settings'
    )
  );

  $wp_customize->add_setting(
    'site_wide_alert_text',
    array()
  );

  $wp_customize->add_control(
    'site_wide_alert_text',
    array(
      'label' => 'Alert text entered here will show up at the top of every page.',
      'section' => 'header_alert',
      'type' => 'text',
      'priority' => 1
    )
  );

  /**
   * Front page settings
   */
  $wp_customize->add_section(
    'front_page_settings',
    array(
      'title' => 'Front Page Settings'
    )
  );

  // Gallery post ID
  $wp_customize->add_setting(
    'gallery_id',
    array()
  );

  $wp_customize->add_control(
    'gallery_id',
    array(
      'label' => 'Enter the ID of the gallery post that will show at the bottom of home page',
      'section' => 'front_page_settings',
      'type' => 'text',
      'priority' => 1
    )
  );

  // Poll shortcode
  // $wp_customize->add_setting(
  //   'poll_shortcode',
  //   array()
  // );
  //
  // $wp_customize->add_control(
  //   'poll_shortcode',
  //   array(
  //     'label' => 'Enter the shortcode for the poll at bottom of home page',
  //     'section' => 'front_page_settings',
  //     'type' => 'text',
  //     'priority' => 1
  //   )
  // );

  // Number of news posts to show
  $wp_customize->add_setting(
    'news_post_num',
    array(
      'sanitize_callback' => 'ednc_sanitize_integer'
    )
  );

  $wp_customize->add_control(
    'news_post_num',
    array(
      'label' => 'Enter the total number of news posts to display',
      'section' => 'front_page_settings',
      'type' => 'number',
      'priority' => 2
    )
  );

  // Custom category spot
  // $wp_customize->add_setting(
  //   'theme_spot_category',
  //   array()
  // );
  //
  // $wp_customize->add_control(new Category_Dropdown_Custom_Control(
  //     $wp_customize,
  //     'theme_spot_category',
  //     array(
  //       'label' => 'Optional: Choose a category to stick to the beginning of the news posts',
  //       'section' => 'front_page_settings',
  //       'priority' => 3
  //     )
  //   )
  // );

  /**
   * Remove unneeded sections
   */
  $wp_customize->remove_section( 'title_tagline');
  $wp_customize->remove_section( 'nav');
  $wp_customize->remove_section( 'static_front_page');

}
add_action('customize_register', 'ednc_customizer_settings');

function ednc_sanitize_integer($input) {
  if (is_numeric($input)) {
    return intval($input);
  }
}


/**
 * A class to create a dropdown for all categories in your wordpress site
 */
if ( ! class_exists( 'WP_Customize_Control' ) )
  return NULL;

class Category_Dropdown_Custom_Control extends WP_Customize_Control {
  private $cats = false;

  public function __construct($manager, $id, $args = array(), $options = array()) {
    $this->cats = get_categories($options);

    parent::__construct( $manager, $id, $args );
  }

  /**
   * Render the content of the category dropdown
   *
   * @return HTML
   */
  public function render_content() {
    if(!empty($this->cats)) {
      ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <select <?php $this->link(); ?>>
            <option>None</option>
             <?php
              foreach ( $this->cats as $cat ) {
                printf('<option value="%s" %s>%s</option>', $cat->term_id, selected($this->value(), $cat->term_id, false), $cat->name);
              }
             ?>
          </select>
        </label>
      <?php
    }
  }
}
