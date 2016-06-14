<?php

/* Canva Media Class
 * Author: Ash Durham (http://durham.net.au)
 */

class Canva_Media {

    private $api_path = "https://api.canva.com/v1/api.js";

    function __construct() {
        global $wp_version;

        add_action( 'admin_init',                       array($this, 'global_variables') );
        add_action( 'init',                             array($this, 'load_textdomain'), 1 );
        add_action( 'plugins_loaded',                   array($this, 'plugins_loaded') );

        add_action( 'admin_enqueue_scripts',            array($this, 'all_admin_enqueue') );
        add_action( 'admin_menu',                       array($this, 'add_config_page') );
        add_action( 'wp_ajax_canva_uploader_action',    array($this, "canva_uploader_action") );

        if ($wp_version < 3.5) {
                if ( basename($_SERVER['PHP_SELF']) != "media-upload.php" ) return;
        } else {
                if ( basename($_SERVER['PHP_SELF']) != "media-upload.php" && basename($_SERVER['PHP_SELF']) != "post.php" && basename($_SERVER['PHP_SELF']) != "post-new.php") return;
        }

        register_activation_hook( CANVA_PATH,   array($this,"activate") );
        register_deactivation_hook( CANVA_PATH, array($this,"deactivate") );

        add_action( 'admin_enqueue_scripts',    array($this, 'canva_admin_enqueue') );
        add_action( 'admin_head',               array($this, 'admin_head'), 1 );
        add_action( 'media_buttons_context',    array($this, "media_button") );

    }

    /*
     * Activate the Canva Plugin
     */

    function activate() {
        // Do some checks
        if ( !function_exists("curl_init") && !ini_get("allow_url_fopen") ) {
            deactivate_plugins( CANVA_BASENAME );
            wp_die( __( '<b>cURL</b> or <b>allow_url_fopen</b> needs to be enabled. Please consult your server Administrator.', 'canva' ) );
        }
    }

    /*
     * Deactivate the Canva Plugin
     */

    function deactivate() {

    }

    /*
     * Load Translations
     */

    function load_textdomain() {
        load_plugin_textdomain( 'canva', false, dirname( CANVA_BASENAME ) . '/languages/' );
    }

    /*
     * Load Settings variables
     */

    function global_variables() {
        register_setting( 'canva-settings', 'canva_design_type' );
        register_setting( 'canva-settings', 'canva_api_key' );

        add_option('canva_design_type', 'blogGraphic');
        add_option('canva_api_key', '');

    }

    /*
     * Run when plugins are loaded
     */

    function plugins_loaded() {

    }

    /*
     * Enqueue required scripts
     */

    function all_admin_enqueue() {
        wp_register_style( 'canva-wp', plugins_url('css/canva-wp.css', dirname(__FILE__)), false, '1.0.2');
        wp_enqueue_style( 'canva-wp' );
    }

    function canva_admin_enqueue($hook) {
        if ($this->script_required($hook)) {

            wp_register_script( 'canva_func', plugins_url( '/js/func.js', dirname(__FILE__) ), array('jquery'), '1.0.2');
            wp_enqueue_script( 'canva_func' );
            wp_localize_script( 'canva_func', 'canva_ajax',
            array( 'url' => admin_url( 'admin-ajax.php' ), 'ajaxnonce' => wp_create_nonce( 'C4nv4' ) ) );

        }
    }

    /*
     * Enqueue required scripts
     */

    function admin_head() {

        echo '<script type="text/javascript">
        window.canvadesignCallback = function (url, design_id) {
            if (typeof(url) == "undefined") url = $("#canvasrc").val();
            if (typeof(design_id) == "undefined") design_id = 0;
            var filename = jQuery("#canvanewfilenameid").val();
            var post_id = jQuery("#post_ID").val();
            jQuery("body").prepend("<div id=\'canvamask\'><div class=\'canva embed loading animation\'><img class=\'canva-embed loading-gif\' src=\''.plugins_url('images/canva_logo_loading.gif', dirname(__FILE__)).'\' alt=\'loading canva...\' /></div></div>");
            jQuery.post(canva_ajax.url, {action: "canva_uploader_action", ajaxnonce : canva_ajax.ajaxnonce, canvaimageurl:url, canvadesignid:design_id, canvanewfilename:filename, post_id:post_id}, function(result) {
                jQuery("#canvamask").fadeOut(500, function() {
                    jQuery(this).remove();
                });
                window.send_to_editor(result);
            });
        };
        </script>';

    }

    /*
     *  Ajax handler
     */

    function canva_uploader_action() {
       if ( $_POST['canvaimageurl'] ) {
           $imageurl = $_POST['canvaimageurl'];
           $imageurl = stripslashes($imageurl);
           $designId = $_POST['canvadesignid'];
           $uploads = wp_upload_dir();
           $post_id = isset($_POST['post_id'])? (int) $_POST['post_id'] : 0;
           $newfilename = $_POST['canvanewfilename'] . ".png";

           $filename = wp_unique_filename( $uploads['path'], $newfilename, $unique_filename_callback = null );
           $fullpathfilename = $uploads['path'] . "/" . $filename;

           try {
                   $image_string = file_get_contents($imageurl, false);
                   $fileSaved = file_put_contents($uploads['path'] . "/" . $filename, $image_string);
                   if ( !$fileSaved ) {
                           throw new Exception("The file cannot be saved.");
                   }

                   $attachment = array(
                            'post_mime_type' => 'image/png',
                            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                            'post_content' => '',
                            'post_status' => 'inherit',
                            'guid' => $uploads['url'] . "/" . $filename
                   );
                   $attach_id = wp_insert_attachment( $attachment, $fullpathfilename, $post_id );
                   if ( !$attach_id ) {
                           throw new Exception("Failed to save record into database.");
                   }
                   require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                   $attach_data = wp_generate_attachment_metadata( $attach_id, $fullpathfilename );
                   wp_update_attachment_metadata( $attach_id,  $attach_data );
                   update_post_meta($attach_id, 'canva_designId', $designId);
                   echo wp_get_attachment_image( $attach_id, 'full' );

           } catch (Exception $e) {
                   $error = '<div id="message" class="error"><p>' . $e->getMessage() . '</p></div>';
           }

       }
       die;
    }

    /*
     * Add Canva button next to Media on HTML Editor in edit screen
     */

    function media_button() {
        $design_type = get_option('canva_design_type');
        $api_key = (get_option("canva_api_key") !== "") ? ' data-apikey="'.get_option("canva_api_key").'"' : ' data-apikey="DBzS0qZ5pjEtYtdwoYVEeKbj"';
        $context = '<span href="https://www.canva.com/" class="canva-design-button" data-type="'.$design_type.'"'.$api_key.' data-url-callback="canvadesignCallback" data-filename="canvanewfilenameid" data-thumbnail="canvaimagethumbnail" data-input="canvaimageurl" data-label="Design in Canva">Design in Canva</span>';
        $context .= '<script>
            (function(c,a,n){var w=c.createElement(a),s=c.getElementsByTagName(a)[0];w.src=n;
            s.parentNode.insertBefore(w,s);})(document,"script","'.$this->api_path.'");
            </script>';

        $context .= '<div class="describe" style="visibility:hidden;height:0;">
                <input id="canvasrc" type="text" name="canvaimageurl">
                <img id="canvaimagethumbnail" />
                <input type="text" name="canvanewfilename" style="width:200px" id="canvanewfilenameid">
                <input type="button" id="action-download" />
                </div>';

        return $context;
    }

    /*
     * Determine if scripts are to be included
     */

    function script_required($hook) {
        $pages_req = array('post.php', 'post-new.php', 'edit.php');

        if (in_array($hook, $pages_req)) return true;
        return false;
    }

    /*
     * Adding a config page to the admin
     */

    function add_config_page() {
        add_menu_page('Canva','Canva', 'manage_options', 'canva_menu', array($this, 'canva_menu_page'));
    }

    function canva_menu_page() {
        include CANVA_PATH.'/screens/config.php';
    }
}

new Canva_Media();