<?php
/**
 * Scripts and Styles
 *
 * @package EducationNC
 */

/************* ENQUEUE CSS *****************/

function ednc_styles() {
    wp_enqueue_style( 'ednc-style', get_template_directory_uri() . '/assets/public/css/app.css' );
    wp_enqueue_style( 'ednc-hotfixes', get_template_directory_uri() . '/style.css' );
}
add_action('wp_enqueue_scripts', 'ednc_styles');    // FRONT END ONLY

function ednc_admin_styles() {
    wp_enqueue_style( 'ednc-admin-style', get_template_directory_uri() . '/assets/public/css/admin.css' );
}
add_action('admin_enqueue_scripts', 'ednc_admin_styles');

/************* ENQUEUE JS *************************/

// pull jquery from google's CDN. If it's not available, grab the local copy. Code from wp.tutsplus.com :-)
$url = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'; // the URL to check against
$test_url = @fopen($url,'r'); // test parameters
if( $test_url !== false ) { // test if the URL exists
    function load_external_jQuery() { // load external file
        wp_deregister_script( 'jquery' ); // deregisters the default WordPress jQuery
        wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array(), '1.11.1', false); // enqueue the external file
    }
    add_action('wp_enqueue_scripts', 'load_external_jQuery'); // initiate the function
} else {
    function load_local_jQuery() {
        wp_deregister_script('jquery'); // initiate the function
        wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/public/js/jquery.min.js', array(), '1.11.1', false); // register the local file
    }
    add_action('wp_enqueue_scripts', 'load_local_jQuery'); // initiate the function
}

// pull jQuery UI from Google's CDN. If it's not available, grab the local copy.
$ui_url = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js';
$test_ui_url = @fopen($ui_url,'r');
if ($test_ui_url !== false) {
    function load_external_jQueryUI() {
        wp_enqueue_script('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js', array('jquery'), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_external_jQueryUI');
} else {
    function load_local_jQueryUI() {
        wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/assets/public/js/jquery-ui.min.js', array('jquery'), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_local_jQueryUI');
}

// pull Angular from Google's CDN. If it's not available, grab the local copy.
$ng_url = 'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js';
$test_ng_url = @fopen($ng_url,'r');
if ($test_ng_url !== false) {
    function load_external_angular() {
        wp_enqueue_script('angular-core', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js', array(), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_external_angular');
} else {
    function load_local_angular() {
        wp_enqueue_script('angular-core', get_template_directory_uri() . '/assets/public/js/angular.min.js', array(), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_local_angular');
}

// pull Angular Route from Google's CDN. If it's not available, grab the local copy.
$ngrt_url = 'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-route.min.js';
$test_ngrt_url = @fopen($ngrt_url,'r');
if ($test_ngrt_url !== false) {
    function load_external_angular_route() {
        wp_enqueue_script('angular-route', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-route.min.js', array(), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_external_angular_route');
} else {
    function load_local_angular_route() {
        wp_enqueue_script('angular-route', get_template_directory_uri() . '/assets/public/js/angular-route.min.js', array(), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_local_angular_route');
}

// pull Angular Resource from Google's CDN. If it's not available, grab the local copy.
$ngrs_url = 'http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-resource.min.js';
$test_ngrs_url = @fopen($ngrs_url,'r');
if ($test_ngrs_url !== false) {
    function load_external_angular_resource() {
        wp_enqueue_script('angular-resource', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-resource.min.js', array(), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_external_angular_resource');
} else {
    function load_local_angular_resource() {
        wp_enqueue_script('angular-resource', get_template_directory_uri() . '/assets/public/js/angular-resource.min.js', array(), '', true);
    }
    add_action('wp_enqueue_scripts', 'load_local_angular_resource');
}


function ednc_scripts() {
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/app/bower_components/modernizr/modernizr.js', array(), '', true);
    wp_enqueue_script( 'foundation', get_template_directory_uri() . '/assets/app/bower_components/foundation/js/foundation/foundation.js', array(), '', true);
    wp_enqueue_script( 'ednc-scripts', get_template_directory_uri() . '/assets/app/js/scripts.js', array('jquery', 'jquery-ui'), '', true );
    wp_enqueue_script( 'ednc-angular', get_template_directory_uri() . '/assets/app/js/app.js', array('angular-core'), '', true );

    wp_localize_script( 'ednc-angular', 'WPAPI', array('api_url' => esc_url_raw(get_json_url()), 'api_nonce' => wp_create_nonce('wp_json'), 'template_url' => get_bloginfo('template_directory')) );
}

add_action('wp_enqueue_scripts', 'ednc_scripts');

// Browser Update script for old browsers from https://browser-update.org/
function ednc_browser_update() {
    echo '<script type="text/javascript">
    var $buoop = {vs:{i:8,f:15,o:12.1,s:6}};
    $buoop.ol = window.onload;
    window.onload=function(){
        try {if ($buoop.ol) $buoop.ol();}catch (e) {}
        var e = document.createElement("script");
        e.setAttribute("type", "text/javascript");
        e.setAttribute("src", "//browser-update.org/update.js");
        document.body.appendChild(e);
    }
    </script>';
}

// add_action('wp_footer', 'ednc_browser_update');

// Remove ver at the end of css and js files
// https://wordpress.org/support/topic/get-rid-of-ver-on-the-end-of-cssjs-files
function remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
