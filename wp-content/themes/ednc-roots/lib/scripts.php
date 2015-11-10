<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.11.1.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr.min.js
 * 3. /theme/assets/js/scripts.js (in footer)
 *
 * Google Analytics is loaded after enqueued scripts if:
 * - An ID has been defined in config.php
 * - You're not logged in as an administrator
 */
function roots_scripts() {
  /**
   * The build task in Grunt renames production assets with a hash
   * Read the asset names from assets-manifest.json
   */
  if (WP_ENV === 'development') {
    $assets = array(
      'css'          => '/assets/public/css/main.css',
      'js'           => '/assets/public/js/scripts.js',
      'grid_rotator' => '/assets/public/js/jquery.gridrotator.min.js',
      'modernizr'    => '/assets/app/vendor/modernizr/modernizr.js',
      'jquery'       => '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js',
      'translate'    => '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'
    );

  } else {
    $get_assets = file_get_contents(get_template_directory() . '/assets/manifest.json');
    $assets     = json_decode($get_assets, true);
    $assets     = array(
      'css'          => '/assets/public/css/main.min.css?' . $assets['assets/public/css/main.min.css']['hash'],
      'js'           => '/assets/public/js/scripts.min.js?' . $assets['assets/public/js/scripts.min.js']['hash'],
      'grid_rotator' => '/assets/public/js/jquery.gridrotator.min.js',
      'modernizr'    => '/assets/public/js/modernizr.custom.min.js',
      'jquery'       => '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js',
      'translate'    => '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'
    );
  }

  wp_enqueue_style('roots_css', get_template_directory_uri() . $assets['css'], false, null);

  /**
   * jQuery is loaded using the same method from HTML5 Boilerplate:
   * Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
   * It's kept in the header instead of footer to avoid conflicts with plugins.
   */
  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', $assets['jquery'], array(), null, false);
    add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('translate', $assets['translate'], array(), null, true);
  wp_enqueue_script('modernizr', get_template_directory_uri() . $assets['modernizr'], array(), null, false);
  wp_enqueue_script('jquery');
  wp_enqueue_script('roots_js', get_template_directory_uri() . $assets['js'], array(), null, true);

  // Enqueue grid rotator plugin on the homepage only
  if (is_home()) {
    wp_enqueue_script('grid_rotator', get_template_directory_uri() . $assets['grid_rotator'], array('jquery'), null, true);
  }

  // Enqueue Antenna scripts on single posts only
  if (is_single('post')) {
    wp_enqueue_script('antenna', '//www.antenna.is/static/engage.js', array(), null, true);
  }
}
add_action('wp_enqueue_scripts', 'roots_scripts', 100);

// Add admin scripts and styles
function ednc_admin_scripts() {
  wp_enqueue_style('ednc_admin_css', get_template_directory_uri() . '/assets/public/css/admin.css');
  wp_enqueue_script('ednc_admin_js', get_template_directory_uri() . '/assets/public/js/admin.js');
}
add_action('admin_enqueue_scripts', 'ednc_admin_scripts');

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/vendor/jquery/dist/jquery.min.js?1.11.1"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
add_action('wp_head', 'roots_jquery_local_fallback');

/**
 * Manage google fonts of load_google_font()
 * set GOOGLE_FONTS constant in config.php
 */
function load_google_fonts() {
  if( ! defined( 'GOOGLE_FONTS' ) ) return;

  echo '<link href="//fonts.googleapis.com/css?family=' . GOOGLE_FONTS . '" rel="stylesheet" type="text/css" />'."\n";
}
add_action( 'wp_head', 'load_google_fonts' , 1);
