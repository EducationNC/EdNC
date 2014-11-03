<?php
/**
 * EducationNC functions includes
 *
 * Borrowed from Roots
 * https://github.com/roots/roots/blob/master/functions.php
 *
 * @package EducationNC
 */

$nomacorc_includes = array(
    'inc/setup.php',                // Theme setup stuff
    'inc/scripts.php',              // Scripts and styles
    'inc/custom-post-types.php',    // Custom post types
    // 'inc/widgets.php',              // Custom widgets and areas
    'inc/tinymce.php',              // Customizations to Advanced TinyMCE Editor Plugin
    // 'inc/shortcodes.php',           // Custom shortcodes
    'inc/extras.php',               // Custom functions specific for this theme
    'inc/resize.php'                // Resizes images on the fly (TimThumb replacement)
);

foreach ($nomacorc_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'ednc'), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);
