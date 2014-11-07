<?php
/**
 * Customizations to Advanced TinyMCE Editor Plugin
 *
 * @package EducationNC
 */

// Adds styles to TinyMCE
function add_tiny_mce_before_init( $init_array ) {
    // Add styles to the dropdown
    $style_formats = array(
        array(
            'title' => 'Main Heading',
            'block' => 'h2'
        ),
        array(
            'title' => 'Sub Heading',
            'block' => 'h3'
        ),
        array(
            'title' => 'Plain Text',
            'block' => 'p'
        )
    );
    $init_array['style_formats'] = json_encode($style_formats);

    // Add custom class to the iframe based on page template usage
    // $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
    // $template = get_page_template_slug($post_id);
    // $init_array['body_class'] .= ' ' . $template;

    return $init_array;
}
add_filter( 'tiny_mce_before_init', 'add_tiny_mce_before_init' );

// Load CSS to TinyMCE editor
function add_mce_css( $mce_css ) {
    if ( ! empty( $mce_css ) )
        $mce_css .= ',';

    $mce_css .= get_template_directory_uri() . '/assets/public/css/editor.css';

    return $mce_css;
}
add_filter( 'mce_css', 'add_mce_css' );
