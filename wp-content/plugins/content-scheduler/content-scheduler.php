<?php
/*
Plugin Name: Content Scheduler
Plugin URI: http://paulekaiser.com/wordpress-plugins/content-scheduler/
Description: Set Posts and Pages to automatically expire. Upon expiration, delete, change categories, status, or unstick posts. Also notify admin and author of expiration.
Version: 2.0.5
Author: Paul Kaiser
Author URI: http://paulekaiser.com
License: GPL2
*/
/*  Copyright 2014  Paul Kaiser  (email : paul.kaiser@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// avoid direct calls to this file, because now WP core and framework have been used
if ( !function_exists('is_admin') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

require_once "includes/DateUtilities.php";


// assign some constants if they didn't already get taken care of
define( 'PEK_CONTENT_SCHEDULER_VERSION', '2.0.5' );
define( 'PEK_CONTENT_SCHEDULER_DIR', plugin_dir_path( __FILE__ ) );
define( 'PEK_CONTENT_SCHEDULER_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );





// Define our plugin's wrapper class
if ( !class_exists( "ContentScheduler" ) ) {
	class ContentScheduler {
	    var $settings, $options_page, $options;
	    var $debug = false;
	    
		function __construct() {
            $this->options = get_option('ContentScheduler_Options');

		    if ( is_admin() ) {
		        // Handle all things that are Dashboard-only
                // Load settings page
                if ( !class_exists( "Content_Scheduler_Settings" ) ) {
                    require( PEK_CONTENT_SCHEDULER_DIR . 'content-scheduler-settings.php' );
                }
                $this->settings = new Content_Scheduler_Settings();
                // Add any JavaScript and CSS needed just for my plugin
                // for the post editing screen
                add_action( "admin_enqueue_scripts", array( $this, 'cs_edit_scripts' ) );
                add_action( "admin_enqueue_scripts", array( $this, 'cs_edit_styles' ) );
                // Adding Custom boxes to Write panels (for Post, Page, and Custom Post Types)
                add_action('add_meta_boxes', array($this, 'ContentScheduler_add_custom_box_fn'));
                add_action('save_post', array($this, 'ContentScheduler_save_postdata_fn'));
                // Add column to Post / Page lists
                add_action ( 'manage_posts_custom_column', array( $this, 'cs_show_expdate' ) );
                add_action ( 'manage_pages_custom_column', array( $this, 'cs_show_expdate' ) );
                // Showing custom columns values in list views
                add_filter ('manage_posts_columns', array( $this, 'cs_add_expdate_column' ) );
                add_filter ('manage_pages_columns', array( $this, 'cs_add_expdate_column' ) );
            }
            
			add_action( 'init', array( $this, 'content_scheduler_init' ) );

			// admin_init and admin_menu only appear when we are in admin
			// could be in page edit, etc.
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( 'admin_footer', array( $this, 'admin_footer' ) );
            
			// add a cron action for expiration check
            if( is_multisite () ) {
                // we need to add our action hook for just the current site, using blogID in the name
                $blog_id = get_current_blog_id();
                add_action( 'contentscheduler' . $blog_id, array( $this, 'answer_expiration_event' ) );
                // TODO for notifications later
                // add_action ('contentschedulernotify' . $blog_id, array( $this, 'answer_notification_event' ) );
            } else {
                // it's okay to just use normal action hook
    			add_action ('contentscheduler', array( $this, 'answer_expiration_event') );            
                // TODO for notifications later
                // add_action ('contentschedulernotify', array( $this, 'answer_notification_event' ) );
            }

			// Shortcodes
			add_shortcode('cs_expiration', array( $this, 'handle_shortcode' ) );

			// Filters
			add_filter('cron_schedules', array( $this, 'add_cs_cron_fn' ) );

			register_activation_hook( __FILE__, array($this, 'run_on_activate') );
			register_deactivation_hook( __FILE__, array($this, 'run_on_deactivate') );
		} // end ContentScheduler Constructor




        // ======================================================================
        // Plugin Activation / Deactivation work
        // ======================================================================
        /*
            Propagates pfunction to all blogs within our multisite setup.
            http://shibashake.com/wordpress-theme/write-a-plugin-for-wordpress-multi-site		
            If not multisite, then we just run pfunction for our single blog.
        */
        function network_propagate($pfunction, $networkwide) {
            global $wpdb;            

            if (function_exists('is_multisite') && is_multisite()) {
                // at this time, we want to disallow network activation
                if ( $network_wide ) {
                    deactivate_plugins( plugin_basename( __FILE__ ), TRUE, TRUE );
                    header( 'Location: ' . network_admin_url( 'plugins.php?deactivate=true' ) );
                }
                // check if it is a network activation - if so, run the activation function 
                // for each blog id
                /*
                // Note, we do not want network activation to be a thing for CS right now
                if ($networkwide) {
                    $old_blog = $wpdb->blogid;
                    // Get all blog ids
                    $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                    foreach ($blogids as $blog_id) {
                        switch_to_blog($blog_id);
                        call_user_func($pfunction, $networkwide);
                    }
                    switch_to_blog($old_blog);
                    return;
                }	
                */
            } 
            call_user_func($pfunction, $networkwide);
        }

        function run_on_activate( $network_wide )
        {
            $this->network_propagate( array( $this, '_activate' ), $networkwide );
        }
    
        // TODO: Still need to review what we do during activation
        function _activate() {
            /*
                1. Set some default options, with database migration if needed
            */

            // Build an array of each option and its default setting
            // exp-default is supposed to be a serialized array of hours, days, weeks
            $expiration_default = array( 'exp-hours' => '0', 'exp-days' => '0', 'exp-weeks' => '0' );
            $arr_defaults = array
            (
                "version" => PEK_CONTENT_SCHEDULER_VERSION,
                "exp-status" => "1",
                "exp-period" => "60",
                "chg-status" => "2",
                "chg-sticky" => "0",
                "chg-cat-method" => "0",
                "selcats" => "",
                "tags-to-add" => "",
                "notify-on" => "0",
                "notify-admin" => "0",
                "notify-author" => "0",
                "notify-expire" => "0",
                "min-level" => "level_1",
                "datepicker" => "1",
                "show-columns" => "0",
                "remove-cs-data" => "0",
                "exp-default" => $expiration_default,
                "chg-title" => "0",
                "title-add" => ""
            );

            // Some database migration from older versions of Content Scheduler
            if( is_array( $this->options ) )
            {
                // If version is older than 2.0, we need to change the way we store expiration date metadata
                if( !isset( $this->options['version'] ) || $this->options['version'] < '2.0.0' )
                {
                    include 'includes/update-postmeta-expiration-values.php';
                }
                // If version newer than 0.9.7, we need to alter the name of our postmeta variables if there are earlier version settings in options
                if( !isset( $this->options['version'] ) || $this->options['version'] < '0.9.7' )
                {
                    // we do need to change existing postmeta variable names in the database
                    include 'includes/update-postmeta-names.php';
                }
                // If version newer than 0.9.8, we need to alter the name of our user_level values
                if( !isset( $this->options['version'] ) || $this->options['version'] < '0.9.8' )
                {
                    // we do need to change existing user-level access values in the database
                    include 'includes/update-minlevel-options.php';
                }
                // We need to check the "version" and, if it is less than 0.9.5 or non-existent, we need to convert english string values to numbers
                if( !isset( $this->options['version'] ) || $this->options['version'] < '0.9.5' )
                {
                    // we want to change options from english strings to numbers - this happened from 0.9.4 to 0.9.5
                    include 'includes/update-values-numbers.php';
                }
                // We need to update the version string to our current version
                $this->options['version'] = PEK_CONTENT_SCHEDULER_VERSION;
            }
            // make sure we have added any updated options
            if( $this->options ) {
                // $new_options = array_replace( $arr_defaults, $this->options ); // array_replace only in PHP 5.3+
                foreach( $this->options as $key => $val ) {
                    $arr_defaults[$key] = $val;
                }
                $new_options = $arr_defaults;
            } else {
                $new_options = $arr_defaults;
            }
            $this->options = $new_options;
            update_option('ContentScheduler_Options', $new_options);

            /*
                2. Register our expiration and notification events into wp-cron schedule
            */
            if( is_multisite () ) {
                $blog_id = get_current_blog_id();
                if ( !wp_next_scheduled( 'contentscheduler' . $blog_id ) ) {
                    wp_schedule_event( time(), 'contsched_usertime', 'contentscheduler' . $blog_id );
                }
                /*
                // TODO later for notifications
                if ( !wp_next_scheduled( 'contentschedulernotify' . $blog_id ) ) {
                    wp_schedule_event( time(), 'contsched_usertime', 'contentschedulernotify' . $blog_id );
                }
                */
            } else {
                if ( !wp_next_scheduled( 'contentscheduler' ) ) {
                    wp_schedule_event( time(), 'contsched_usertime', 'contentscheduler' );
                }
                /*
                // TODO for notifications later
                if ( !wp_next_scheduled( 'contentschedulernotify' ) ) {
                    wp_schedule_event( time(), 'contentschedulernotify' );
                }
                */
            }
        } // end activate_function

        function run_on_deactivate( $network_wide ) {
            $this->network_propagate( array( $this, '_deactivate' ), $networkwide );
        } // end run_on_activate()

        // TODO: Still need to review what we do during deactivation
        function _deactivate() {
            /*
                1. Clear our expiration and notification events into wp-cron schedule
            */
            if( is_multisite () ) {
                $blog_id = get_current_blog_id();
                wp_clear_scheduled_hook( 'contentscheduler' . $blog_id );
                /*
                // TODO later for notifications
                wp_clear_scheduled_hook( 'contentschedulernotify' . $blog_id );
                */
            } else {
                wp_clear_scheduled_hook( 'contentscheduler' );
                /*
                // TODO later for notifications
                wp_clear_scheduled_hook( 'contentschedulernotify' );
                */
            }
        } // end deactivate_function()




        // ======================================================================
        // Init / Admin Init callbacks
        // Note: there will be other init / admin_inits in Settings class
        // ======================================================================
        // init
        function content_scheduler_init() {
            // load language translation files
            $plugin_dir = basename(dirname(__FILE__)) . '/lang';
            load_plugin_textdomain( 'contentscheduler', PEK_CONTENT_SCHEDULER_DIR . 'lang', basename( dirname( __FILE__ ) ) .'/lang' );
        }
        function admin_init() {
        }
        function admin_menu() {
        }
        function admin_head() {
            global $pagenow;            
            // for inline scripts in head, etc.
            // only use on new post and edit post pages
            if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow ) {
                return;
            }
            // only add if datepicker is enabled
            if( $this->options['datepicker'] == '1' ) {
            ?>
            <script type="text/javascript">
            jQuery(function(){
                jQuery( '#cs-expire-date' ).datetimepicker()
                });
            </script>
	        <?php
	        } // endif
        }
        function admin_footer() {
        }
        





        // ========================================================================
        // == JavaScript and CSS Enqueueing?
        // ========================================================================
        // enqueue the jQuery UI things we need for using the datepicker
        function cs_edit_scripts( $hook ) {
            // do we want the datepicker?
            if( $this->options['datepicker'] == '1' ) {
                // only use on new post and edit post pages
                if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
                    return;
                }
                // enqueue javascripts here if needed
                wp_enqueue_script(
                    'datetimepicker', 
                    plugins_url() . "/content-scheduler/js/jquery-ui-timepicker-addon.min.js", 
                    array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider' ), 
                    '1.6.0', 
                    true );
            } // endif checking for datepicker option
        } // end cs_edit_scripts()

        function cs_edit_styles( $hook ) {
            // do we want the datepicker?
            if( $this->options['datepicker'] == '1' ) {
                // post.php and post-new.php
                if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
                    return;
                }
                // enqueue styles here if needed
                wp_enqueue_style( 'jquery-ui-datepicker', plugins_url() . "/content-scheduler/js/jquery-ui.min.css", array(), '1.11.2', 'all' );
                wp_enqueue_style( 'datetimepicker', plugins_url() . "/content-scheduler/js/jquery-ui-timepicker-addon.css", array( 'jquery-ui-datepicker' ), '1.6.0', 'all' );
            } // endif checking for datepicker option
        } // end cs_edit_styles()





        // =================================================================
        // == Functions for using Custom Controls / Panels
        // == in the Post / Page / Link writing panels
        // == e.g., a custom field for an expiration date, etc.
        // =================================================================
        // Adds a box to the main column on the Post, Page, and Custom Type edit screens
        // We'll rig so it only shows if user is min-level or above
        // a. Add the box
        function ContentScheduler_add_custom_box_fn()
        {
            global $current_user;
            // What is minimum level required to see CS?
            $min_level = $this->options['min-level'];
        
            // What is current user's level?
            get_currentuserinfo();
        
            // 3.3 changed this for the better
            $allcaps = $current_user->allcaps;
        
            if( 1 != $allcaps[$min_level] )
            {
                return; // not authorized to see CS
            }
            // else - continue
            // Add the box to Post write panels
            add_meta_box( 'ContentScheduler_sectionid', 
                            __( 'Content Scheduler', 
                            'contentscheduler' ), 
                            array($this, 'ContentScheduler_custom_box_fn'), 
                            'post' );
            // Add the box to Page write panels
            add_meta_box( 'ContentScheduler_sectionid', 
                            __( 'Content Scheduler', 
                            'contentscheduler' ), 
                            array($this, 'ContentScheduler_custom_box_fn'), 
                            'page' );
            // Get a list of all custom post types
            // From: http://codex.wordpress.org/Function_Reference/get_post_types
            $args = array(
                'public'   => true,
                '_builtin' => false
            ); 
            $output = 'names'; // names or objects
            $operator = 'and'; // 'and' or 'or'
            $post_types = get_post_types( $args, $output, $operator );
            // Step through each public custom type and add the content scheduler box
            foreach ($post_types  as $post_type )
            {
                // echo '<p>'. $post_type. '</p>';
                add_meta_box( 'ContentScheduler_sectionid',
                                __( 'Content Scheduler',
                                'contentscheduler' ),
                                array( $this, 'ContentScheduler_custom_box_fn'),
                                $post_type );
            }
        }

        // b. Print / draw the box callback
        function ContentScheduler_custom_box_fn()
        {
            // need $post in global scope so we can get id?
            global $post;
            // Use nonce for verification
            wp_nonce_field( 'content_scheduler_values', 'ContentScheduler_noncename' );
            // Get the current value, if there is one
            $the_data = get_post_meta( $post->ID, '_cs-enable-schedule', true );
            $the_data = ( empty( $the_data ) ? 'Disable' : $the_data );
            // Checkbox for scheduling this Post / Page, or ignoring
            $items = array( "Disable", "Enable");
            foreach( $items as $item)
            {
                $checked = ( $the_data == $item ) ? ' checked="checked" ' : '';
                echo "<label><input ".$checked." value='$item' name='_cs-enable-schedule' id='cs-enable-schedule' type='radio' /> $item</label>  ";
            } // end foreach
            echo "<br />\n<br />\n";
            // Field for datetime of expiration
            // TODO datetime conversion
            // should be unix timestamp at this point, in UTC
            // for display, we need to convert this to local time and then format
            
            // datestring is the original human-readable form
            // $datestring = ( get_post_meta( $post->ID, '_cs-expire-date', true) );
            // timestamp should just be a unix timestamp
            $timestamp = ( get_post_meta( $post->ID, '_cs-expire-date', true) );
            if( !empty( $timestamp ) ) {
                // we need to convert that into human readable so we can put it into our field
                $datestring = DateUtilities::getReadableDateFromTimestamp( $timestamp );
            } else {
                $datestring = '';
            }
            // Should we check for format of the date string? (not doing that presently)
            echo '<label for="cs-expire-date">' . __("Expiration date and hour", 'contentscheduler' ) . '</label><br />';
            echo '<input type="text" id="cs-expire-date" name="_cs-expire-date" value="'.$datestring.'" size="25" />';
            echo '<br />Input date and time in any valid Date and Time format.';
        }

        // c. Save data from the box callback
        function ContentScheduler_save_postdata_fn( $post_id )
        {
            // verify this came from our screen and with proper authorization,
            // because save_post can be triggered at other times
            if( !empty( $_POST['ContentScheduler_noncename'] ) )
            {
                if ( !wp_verify_nonce( $_POST['ContentScheduler_noncename'], 'content_scheduler_values' ))
                {
                    return $post_id;
                }
            }
            else
            {
                return $post_id;
            }
            // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
            // to do anything
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            {
                return $post_id;
            }
            // Check permissions, whether we're editing a Page or a Post
            if ( 'page' == $_POST['post_type'] )
            {
                if ( !current_user_can( 'edit_page', $post_id ) )
                return $post_id;
            }
            else
            {
                if ( !current_user_can( 'edit_post', $post_id ) )
                return $post_id;
            }
            
            // OK, we're authenticated: we need to find and save the data
            // First, let's make sure we'll do date operations in the right timezone for this blog
            // $this->setup_timezone();
            // Checkbox for "enable scheduling"
            $enabled = ( empty( $_POST['_cs-enable-schedule'] ) ? 'Disable' : $_POST['_cs-enable-schedule'] );
            // Value should be either 'Enable' or 'Disable'; otherwise something is screwy
            if( $enabled != 'Enable' AND $enabled != 'Disable' )
            {
                // $enabled is something we don't expect
                // let's make it empty
                $enabled = 'Disable';
                // Now we're done with this function?
                return false;
            }
            // Textbox for "expiration date"
            $dateString = $_POST['_cs-expire-date'];            
            $offsetHours = 0;
            // if it is empty then set it to tomorrow
            // we just want to pass an offset into getTimestampFromReadableDate since that is where our DateTime is made
            if( empty( $dateString ) ) {
                // set it to now + 24 hours
                $offsetHours = 24;
            }
            // TODO handle datemath if field reads "default"
            if( trim( strtolower( $dateString ) ) == 'default' )
            {
                // get the default value from the database
                $default_expiration_array = $this->options['exp-default'];
                if( !empty( $default_expiration_array ) )
                {
                    $default_hours = $default_expiration_array['def-hours'];
                    $default_days = $default_expiration_array['def-days'];
                    $default_weeks = $default_expiration_array['def-weeks'];
                }
                else
                {
                    $default_hours = '0';
                    $default_days = '0';
                    $default_weeks = '0';
                }
            
                // we need to move weeks into days and days into hours
                $default_hours += ( $default_weeks * 7 + $default_days ) * 24 * 60 * 60;
                
                // if it is valid, get the published or scheduled datetime, add the default to it, and set it as the $date
                if ( !empty( $_POST['save'] ) )
                {
                    if( $_POST['save'] == 'Update' )
                    {
                        $publish_date = $_POST['aa'] . '-' . $_POST['mm'] . '-' . $_POST['jj'] . ' ' . $_POST['hh'] . ':' . $_POST['mn'] . ':' . $_POST['ss'];
                    }
                    else
                    {
                        $publish_date = $_POST['post_date'];
                    }
                    // convert publish_date string into unix timestamp
                    $publish_date = DateUtilities::getTimestampFromReadableDate( $publish_date );
                }
                else
                {
                    $publish_date = time(); // current unix timestamp
                    // no conversion from string needed
                }
                
                // time to add our default
                // we need $publish_date to be in unix timestamp format, like time()
                $expiration_date = $publish_date + $default_hours * 60 * 60;
                $_POST['_cs-expire-date'] = $expiration_date;
            }
            else
            {
                $expiration_date = DateUtilities::getTimestampFromReadableDate( $dateString, $offsetHours );
            }
            // We probably need to store the date differently,
            // and handle timezone situation
            update_post_meta( $post_id, '_cs-enable-schedule', $enabled );
            update_post_meta( $post_id, '_cs-expire-date', $expiration_date );
            return true;
        }





        // =======================================================================
        // == SCHEDULING FUNCTIONS
        // =======================================================================
        // Specify a custom interval for wp-cron checking
        function add_cs_cron_fn($array)
        {
            // we need to re-fetch options
            // had to add this when updating cron after saving period options
            $this->options = get_option('ContentScheduler_Options');
            if( $this->debug ) {
                error_log( __FUNCTION__ . " running" );
            }
            // Normally, we'll set interval to like 3600 (one hour)
            // For testing, we can set it to like 120 (2 min)
            // 1. Check options for desired interval.
            if( ! empty( $this->options['exp-period'] ) )
            {
                // we have a value, use it
                $period = $this->options['exp-period'];
            }
            else
            {
                // set our default of 1 minute
                $period = 1;
            }
            // We actually have to specify the interval in seconds
            $period = $period*60;
            // 2. use that for 'interval' below.
            $array['contsched_usertime'] = array(
                'interval' => $period,
                'display' => __('CS User Configured')
            );
            return $array;
        }





        // =======================================================
        // == Show CRON Settings
        // == Mostly for debug in Setting screen
        // =======================================================
        function cs_view_cron_settings()
        {
            // store all scheduled cron jobs in an array
            $cron = _get_cron_array();
            // get all registered cron recurrence options (hourly, etc.)
            $schedules = wp_get_schedules();
            $date_format = 'M j, Y @ G:i';
    ?>
    <div clas="wrap" id="cron-gui">
    <h2>Cron Events Scheduled</h2>
    <table class="widefat fixed">
        <thead>
        <tr>
            <th scope="col">Next Run (GMT/UTC)</th>
            <th scope="col">Schedule</th>
            <th scope="col">Hook Name</th>
        </tr>
        </thead>
        <tbody>
    <?php
            foreach( $cron as $timestamp => $cronhooks )
            {
                foreach( (array) $cronhooks as $hook => $events )
                {
                    foreach( (array) $events as $event )
                    {
    ?>
            <tr>
                <td>
                    <?php echo date_i18n( $date_format, wp_next_scheduled( $hook ) ); ?>
                </td>
                <td>
                    <?php 
                    if( $event['schedule'] )
                    {
                        echo $schedules[$event['schedule']]['display'];
                    }
                    else
                    {
                    ?>
                    One-time
                    <?php
                    }
    ?>
                </td>
                <td><?php echo $hook; ?></td>
            </tr>
    <?php
                    }
                }
            }
    ?>
        </tbody>
    </table>
    <h3>More Debug Info:</h3>
    <p><strong>NOTE: </strong>You will see <em>either</em> a Timezone String <em>or</em> a GMT Offset -- not both.</p>
    <ul>
        <li>PHP Version on this server: <?php echo phpversion(); ?></li>
        <li>WordPress core version: <?php bloginfo( 'version' ); ?></li>
        <li>WordPress Timezone String: <?php echo get_option('timezone_string'); ?></li>
        <li>WordPress GMT Offset: <?php echo get_option('gmt_offset'); ?></li>
        <li>WordPress Date Format: <?php echo get_option('date_format'); ?></li>
        <li>WordPress Time Format: <?php echo get_option('time_format'); ?></li>
    </ul>
    </div>
    <?php
        } // end cs_view_cron_settings()





        // =======================================================
        // == WP-CRON RESPONDERS
        // =======================================================
        // Respond to a call from wp-cron checking for expired Posts / Pages
        function answer_expiration_event()
        {
            // Do we need to process expirations?
            if( $this->options['exp-status'] != '0' )
            {				
                // We need to process expirations
                $this->process_expirations();
            }

        }
        // Respond to a call from wp-cron checking for valid notification rules
        // NOTE: This is separate from expiration so we could use CS to just notify of aged posts
        function answer_notification_event()
        {
            error_log( __FILE__ . " :: " . __FUNCTION__ );
            error_log( 'called, but we are not doing anything in here yet' );
        }

        // ==========================================================
        // Process Expirations
        // ==========================================================
        function process_expirations()
        {
            // Check database for posts meeting expiration criteria
            // Hand them off to appropriate functions
            include 'includes/process-expirations.php';
        } // end process_expirations()

        // ====================
        // Do whatever we need to do to expired POSTS
        function process_post($postid)
        {
            include "includes/process-post.php";
        } // end process_post()
        // ====================
        // Do whatever we need to do to expired PAGES
        function process_page($postid)
        {
            include "includes/process-page.php";
        } // end process_page()
        // ====================
        // Do whatever we need to do to expired CUSTOM POST TYPES
        function process_custom($postid)
        {
            // for now, we are just going to proceed with process_post
            include "includes/process-post.php";
        } // end process_custom()
        /*
            @var posts_to_notify    Array of post ID's triggering notification
            @var why_notify         String indicating why we're calling notification
        */
        function do_notifications( $posts_to_notify, $why_notify='expired' ) {
            include "includes/send-notifications.php";
        }





        // ================================================================
        // == Conditionally Add Expiration date to Column views
        // ================================================================
        // a. add our column to the table
        function cs_add_expdate_column ($columns) {
            global $current_user;
            // Check to see if we really want to add our column
            if( $this->options['show-columns'] == '1' )
            {
                // Check to see if current user has permissions to see
                // What is minimum level required to see CS?
                // must declare $current_user as global
                $min_level = $this->options['min-level'];
                // What is current user's level?
                get_currentuserinfo();
        
                $allcaps = $current_user->allcaps;
                if( 1 != $allcaps[$min_level] )
                {
                    return $columns; // not authorized to see CS, so we don't add our expiration column
                }
                // we're just adding our own item to the already existing $columns array
                $columns['cs-exp-date'] = __('Expires at:', 'contentscheduler');
            }
            return $columns;
        } // end cs_add_expdate_column()
    
        // b. print / draw our column in the table, for each item
        function cs_show_expdate ($column_name) {
                global $wpdb, $post, $current_user;
                // Check to see if we really want to add our column
                if( $this->options['show-columns'] == '1' ) {
                    // Check to see if current user has permissions to see
                    // What is minimum level required to see CS?
                    // must declare $current_user as global
                    $min_level = $this->options['min-level'];
                    // What is current user's level?
                    get_currentuserinfo();
                    $allcaps = $current_user->allcaps;
                    if( 1 != $allcaps[$min_level] )
                    {
                        return; // not authorized to see CS
                    }
                    // else - continue
                    $id = $post->ID;
                    if ($column_name === 'cs-exp-date')
                    {
                        // get the expiration value for this post
                        $query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = \"_cs-expire-date\" AND post_id=$id";
                        // get the single returned value (can do this better?)
                        // $ed = $wpdb->get_var($query);
                        $timestamp = $wpdb->get_var($query);
                        if( !empty( $timestamp ) ) {
                            // convert
                            $ed = DateUtilities::getReadableDateFromTimestamp( $timestamp );
                            if( empty( $ed ) ) {
                                $ed = "Date misunderstood";
                            }
                        } else {
                            $ed = "No date set";
                        }
                        // determine whether expiration is enabled or disabled
                        if( get_post_meta( $post->ID, '_cs-enable-schedule', true) != 'Enable' )
                        {
                            $ed .= "<br />\n";
                            $ed .= __( '(Expiration Disabled)', 'contentscheduler' );
                        } // end if
                        echo $ed;
                    } // end if
                } // end if
            } // end cs_show_expdate()





        // ==================================================================
        // == SHORTCODES
        // ==================================================================
        // By request, ability to show the expiration date / time in the post itself.
        // Do I need to make this ability conditional? That is:
        // (a) show shortcodes to anyone viewing the content
        // (b) show shortcodes to certain user role and above
        // (c) do not show shortcodes to anyone
        // For now, I am just going to add the shortcode handler, with no options (0.9.2)
        // === TEMPLATE TAG NOTE ===
        // We'll add a template tag that will also call this function for output.
        // [cs_expiration]
        function handle_shortcode( $attributes )
        {
            global $post;
            global $current_user;
            // Check to see if we have rights to see stuff
            $min_level = $this->options['min-level'];
            get_currentuserinfo();
            $allcaps = $current_user->allcaps;
            if( 1 != $allcaps[$min_level] )
            {
                return; // not authorized to see CS
            }
            // else - continue
            // get the expiration timestamp
            $timestamp = get_post_meta( $post->ID, '_cs-expire-date', true );
            if ( empty( $timestamp ) )
            {
                return false;
            } else {
                $expirationdt = DateUtilities::getReadableDateFromTimestamp( $timestamp );
            }

            $return_string = sprintf( __("Expires: %s", 'contentscheduler'), $expirationdt );
            return $return_string;
        }
} // end ContentScheduler Class
} // End IF Class ContentScheduler




global $pk_ContentScheduler;
if (class_exists("ContentScheduler")) {
	$pk_ContentScheduler = new ContentScheduler();
}

// ========================================================================
// == TEMPLATE TAG
// == For displaying the expiration date / time of the current post.
// == Must be used within the loop
// We could do away with this and just advise people to use do_shortcode instead
function cont_sched_show_expiration( $args = '' )
{
	// $args should be empty, fyi
	if( !isset( $pk_ContentScheduler ) )
	{
		echo "<!-- Content Scheduler template tag unable to generate output -->\n";
	}
	else
	{
		$output = $pk_ContentScheduler->handle_shortcode();
		echo $output;	
	}
}
?>