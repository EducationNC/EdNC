<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */

 // Enable features from Soil when plugin is activated
 // https://roots.io/plugins/soil/
 add_theme_support('soil-clean-up');
 // add_theme_support('soil-nav-walker');
 add_theme_support('soil-nice-search');
 // add_theme_support('soil-jquery-cdn');
 // add_theme_support('soil-relative-urls');

function setup() {
  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage'),
    'footer_navigation' => __('Footer Navigation', 'roots'),
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['video']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Home Page Sections', 'sage'),
    'id'            => 'modular-home',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_single(),
    is_page(),
    is_archive(),
    is_search(),
    is_page_template('template-events.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  if (is_archive('data') || is_singular('data-viz')) {
    wp_enqueue_script('google/charts', '//www.gstatic.com/charts/loader.js', [], null, false);
    wp_enqueue_script('data-viz', Assets\asset_path('scripts/data-viz.js'), ['jquery', 'google/charts'], null, true);
    wp_localize_script( 'data-viz', 'Ajax', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'security' => wp_create_nonce('data-viz-ajax-nonce')
    ));
  }

  wp_enqueue_script('translate', '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', array(), null, true);
  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

/**
 * Assets for embeds
 */
function embed_assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/embed.css'), false, null);
  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);

  if (is_singular('data-viz')) {
    wp_enqueue_script('google/charts', '//www.gstatic.com/charts/loader.js', [], null, false);
    wp_enqueue_script('data-viz', Assets\asset_path('scripts/data-viz.js'), ['jquery', 'google/charts'], null, true);
    wp_localize_script( 'data-viz', 'Ajax', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'security' => wp_create_nonce('data-viz-ajax-nonce')
    ));
  }
}
add_action('enqueue_embed_scripts', __NAMESPACE__ . '\\embed_assets', 100);
remove_action( 'embed_head', 'print_emoji_detection_script' );
remove_action( 'embed_head', 'print_emoji_styles' );

/**
 * Replace default inline embed scripts to remove default share fn code and allow links to open in new tabs
 */
remove_action( 'embed_footer', 'print_embed_scripts' );
add_action( 'embed_footer', __NAMESPACE__ . '\\print_embed_scripts' );
function print_embed_scripts() {
	?>
	<script type="text/javascript">
	 <?php readfile( Assets\asset_path('scripts/wp-embed-template.js') ); ?>
	</script>
	<?php
}
