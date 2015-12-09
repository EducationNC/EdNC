<?php
/**
 * Roots includes
 *
 * The $roots_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/roots/pull/1042
 */
$roots_includes = array(
  'lib/utils.php',            // Utility functions
  'lib/init.php',             // Initial theme setup and constants
  'lib/wrapper.php',          // Theme wrapper class
  'lib/sidebar.php',          // Sidebar class
  'lib/config.php',           // Configuration
  'lib/titles.php',           // Page titles
  'lib/nav.php',              // Custom nav modifications
  'lib/media.php',            // Custom media modifications
  'lib/gallery.php',          // Custom [gallery] modifications
  'lib/comments.php',         // Custom comments modifications
  'lib/scripts.php',          // Scripts and stylesheets
  'lib/custom-post-types.php',// Custom post types
  'lib/admin.php',            // Admin columns for post types
  'lib/acf-fields.php',       // Generated code that registers custom fields by ACF
  'lib/resize.php',           // Resize images on the fly
  'lib/shortcodes.php',       // Shortcodes
  'lib/extras.php',           // Custom functions
  'lib/feeds.php',            // Adding/modifying RSS feeds,
  'lib/custom-pub-date.php',  // Temporary functions for adding pub date to custom field
  'lib/plugin-support-plugins.php',  // Add notices of required plugins for this theme
  'lib/customizer.php',       // Customizer settings
  'lib/social-share-count.php',// Social share count class
  'lib/widgetized-nav.php'    // Widgetized navigation menus
);

foreach ($roots_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'roots'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
