<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/setup.php',              // Theme setup
  'lib/assets.php',             // Scripts and stylesheets
  'lib/admin.php',              // WP-Admin customizations
  'lib/custom-post-types.php',  // Custom post types and custom taxonomies
  'lib/acf-fields.php',         // ACF custom fields
  'lib/custom-pub-date.php',    // Add custom field for updated date
  'lib/extras.php',             // Custom functions
  'lib/facebook-auth.php',      // Facebook auth - PRIVATE
  'lib/feeds.php',              // Custom RSS feeds
  'lib/media.php',              // Image and other media functions
  'lib/resize.php',             // Magic image resizer
  'lib/shortcodes.php',         // Shortcodes and UI
  'lib/titles.php',             // Page titles
  'lib/nav.php',                // Clean up nav menus
  'lib/nav-data-dashboard.php', // Data dashboard nav walker
  'lib/nav-widgets.php',        // Widgetize nav menus
  'lib/wrapper.php',            // Theme wrapper class
  'lib/customizer.php',         // Theme customizer
  'lib/widgets/register.php',   // Register widgets
  'lib/social-share-count.php', // Social share counts
  'lib/ajax-data-viz.php'       // AJAX functions for data-viz
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
