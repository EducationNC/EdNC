<?php
if ( !function_exists( 'is_admin' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define( 'PEK_CONTENT_SCHEDULER_VERSION', '2.0.5' );

if ( !class_exists( "Content_Scheduler_Settings" ) ) {

class Content_Scheduler_Settings {

    var $pagehook, $page_id, $settings_field, $options, $original_options;
    var $debug = false;
    
    function __construct() {
        // id specific to our Content Scheduler settings page
        $this->page_id = 'content_scheduler';
        // get_options slug for our plugin
        $this->settings_field = 'ContentScheduler_Options'; // needs to match older versions?
        $this->options = get_option( $this->settings_field );
        $this->original_options = get_option( $this->settings_field ); // to compare in sanitize_plugin_options
        
        add_action( 'admin_init', array( $this, 'admin_init' ), 20 );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );        
    } // end Content_Scheduler_Settings constructork
    
    function admin_init() {
        // callback to run AFTER our settings have been saved
        add_action( 'update_option_ContentScheduler_Options', array( $this, 'update_after_settings_change' ), 10, 2 );

        // register new setting Group
        register_setting( $this->settings_field, $this->settings_field, array( $this, 'sanitize_plugin_options' ) );
        
        // Add SECTIONS to the setting group
        // Expirations Section
        add_settings_section(
            'cs_expiration_settings',
            __('Content Scheduler Expiration Options', 'contentscheduler'),
            array($this, 'draw_overview'),
            'cs_settings_page');

        // Add Fields to the Expirations section
        /*
        == Global Options for Expiration ==
        Radio Buttons: exp-status: 'expire' or 'hold'
        * This allows us to suspend expiring any content, without disabling the plugin.
        */
        add_settings_field(
            'exp-status',
            __('Expiration status', 'contentscheduler'),
            array($this, 'draw_set_expstatus_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
        /*
        Radio Buttons: exp-period: 'weekly,' 'daily,' 'hourly,' 'other'
        Text Field: epx-period-other: integer, number of minutes
        * This allows folks to specify how often wp-cron will check schedules.
        */
        add_settings_field(
            'exp-period',
            __('Expiration frequency (in minutes)', 'contentscheduler'),
            array($this, 'draw_set_expperiod_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
        // Setting for a default expiration time.
        add_settings_field(
            'exp-default',
            __( 'Default expiration', 'contentscheduler' ),
            array( $this, 'draw_set_expdefault_fn' ),
            'cs_settings_page',
            'cs_expiration_settings' );
        /*			
        Button: exp-disable-all
        * This button requires yes / cancel confirmation, and if passes confirmation, will turn off the "enable expiration" flag for all content on the site.
        // Note: We'll come back to this later, but leave it here so we don't forget about it.
        */

        /*
            Change text in the post title, adding string before or after
        */
        add_settings_field(
            'chg-title',
            __('Change post title:', 'contentscheduler'),
            array($this, 'draw_set_chgtitle_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
            
        /*
        == Change Rules ==
        Radio Buttons: chg-status: 'no-change', 'pending', 'draft'
        * This changes the "Status" setting for the Post / Page.
        */
        add_settings_field(
            'chg-status',
            __('Change status to:', 'contentscheduler'),
            array($this, 'draw_set_chgstatus_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
        /*
        Radio Buttons: chg-sticky: 'no-change', 'unstick'
        * This changes the checkbox of "Stickiness" under "Visibility"
        */
        add_settings_field(
            'chg-sticky',
            __('Change stickiness to:', 'contentscheduler'),
            array($this, 'draw_set_chgsticky_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
        /*
        Radio Buttons: chg-cat-method: 'no-change', 'add', 'subtract', 'exact'
        * This changes the categories of Posts, using the Category picker coming up.
        * 'add' will add the post to the selected categories
        * 'subtract' will remove the post from the selected categories
        * 'exact' will make the post be in exactly the selected categories, adding or removing as needed
        */
        add_settings_field(
            'chg-cat-method',
            __('Apply Category changes as:', 'contentscheduler'),
            array($this, 'draw_set_chgcatmethod_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
        /*
        Category Picker Checkboxes: I'm hoping there is some automatic way to generate this.
        * I guess the option would be an array of categories (tags?)
        * If so, let's call it selcats
        */
        add_settings_field(
            'selcats',
            __('Selected Categories:', 'contentscheduler'),
            array($this, 'draw_set_categories_fn'),
            'cs_settings_page',
            'cs_expiration_settings');
        // 3/21/2011 3:05:01 PM -pk
        // Adding ability to add tags to expired content
        // Must check to see if the content supports post_tags first
        add_settings_field(
            'tags-to-add',
            __('Change tag(s):', 'contentscheduler'),
            array($this, 'draw_add_tags_fn'),
            'cs_settings_page',
            'cs_expiration_settings');





        
        // Notifications Section
        add_settings_section(
            'cs_notify_settings',
            __('Content Scheduler Notification Options', 'contentscheduler'),
            array($this, 'draw_overview_not'),
            'cs_settings_page');
        // Add Fields to the Notifications section
        /*
        == Notification ==
        Checkbox: Use notification: 'notify-on'
        */
        add_settings_field(
            'notify-on',
            __('Enable notification:', 'contentscheduler'),
            array($this, 'draw_notify_on_fn'),
            'cs_settings_page',
            'cs_notify_settings');	
        /*
        Checkbox: Notify admin: 'exp-notify-admin'
        */
        add_settings_field(
            'notify-admin',
            __('Notify Site Administrator:', 'contentscheduler'),
            array($this, 'draw_notify_admin_fn'),
            'cs_settings_page',
            'cs_notify_settings');
        /*
        Checkbox: Notify author: 'exp-notify-author'
        */
        add_settings_field(
            'notify-author',
            __('Notify Content Author:', 'contentscheduler'),
            array($this, 'draw_notify_author_fn'),
            'cs_settings_page',
            'cs_notify_settings');





        
        // Display Options
        add_settings_section(
            'cs_display_settings',
            __('Content Scheduler Display Options', 'contentscheduler'),
            array($this, 'draw_overview_disp'),
            'cs_settings_page');
        // Add Fields to the Options section
        /*
        Select Menu: 'min-level': For minimum user role that can see Content Scheduler forms and shortcode output.
        */
        add_settings_field(
            'min-level',
            __('Minimum user role to see Content Scheduler fields and shortcodes:', 'contentscheduler'),
            array($this, 'draw_min_level_fn'),
            'cs_settings_page',
            'cs_display_settings');
        /*
        == Columns option ==
        Checkbox: Show expiration date in column views: 'exp-show-column'
        * This determines whether or not an expiration date column is shown when viewing a list of Posts or Pages.
        */
        add_settings_field(
            'show-columns',
            __('Show expiration in columns:', 'contentscheduler'),
            array($this, 'draw_show_columns_fn'),
            'cs_settings_page',
            'cs_display_settings');
        /*
        == jQuery Option ==
        Checkbox: Show popup calendar for date: 'use-popup'
        * Determines whether or not the popup jQuery calendar will be used when selecting a date.
        * Based on this, we will load / not load the needed scripts and styles.
        */
        add_settings_field(
            'datepicker',
            __('Use datepicker for Date:', 'contentscheduler'),
            array($this, 'draw_show_datepicker_fn'),
            'cs_settings_page',
            'cs_display_settings');
        /*
        == Settings Removal Option ==
        Checkbox: remove all data upon uninstall? (Not deactivation...)
        This affects an action that uninstall.php will take, whether or not to remove OPTIONS and METADATA
        */
        add_settings_field(
            'remove-cs-data',
            __('Remove all Content Scheduler data upon uninstall:', 'contentscheduler'),
            array($this, 'draw_remove_data_fn'),
            'cs_settings_page',
            'cs_display_settings');
        // Version, read-only; Should this be somewhere else?
        add_settings_field(
            'version',
            __('Plugin version:', 'contentscheduler'),
            array($this, 'draw_plugin_version'),
            'cs_settings_page',
            'cs_display_settings');
        // CRON debug
        if( WP_DEBUG === TRUE )
        {
        add_settings_field(
            'crondebug',
            __('CRON Debug:', 'contentscheduler'),
            array($this, 'cs_view_cron_settings'),
            'cs_settings_page',
            'cs_display_settings');
        }
    } // end admin_init
    
    function admin_menu() {
        if ( !current_user_can( 'update_plugins' ) ) {
            return;
        }
        
        // Add submenu to standard Settings panel
        $this->pagehook = $page = add_options_page(
            __( 'Content Scheduler Options Page', 'contentscheduler'),
            __( 'Content Scheduler', 'contentscheduler'),
            'administrator', 
            $this->page_id, 
            array( $this, 'render' )
        );
        
        // Do things on-load
        // add all metaboxes (came from example; does cs need this?)
        // TODO check to see if we need this
        // I think this is if we want to divide our settings groups into movable metaboxes
        // add_action( 'load-' . $this->pagehook, array( $this, 'metaboxes' ) );
        
        // Include JS, CSS, Header just for settings page
        // JS needed for setting page?
        // add_action( "admin_print_scripts-$page", array( $this, 'js_includes' ) );
        // CSS needed for setting page?
        // add_action( "admin_print_styles-$page", array( $this, 'css_includes' ) );
        // Anything extra needed for setting page in HEAD?
        // add_action( "admin_head-$page", array( $this, 'admin_head') );
    } // end admin_menu
    
    // Any extra things needed in <HEAD> for our settings page
    function admin_head() { ?>
        <style>
        .blah { background: #f00; }
        </style>
    <?php }
    
    // Any JS needed for our settings page, get it loaded the WP way
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_script
    function js_includes() {
        wp_enqueue_script( 'postbox' );
    }
    
    // Any CSS needed for our settings page, get it loaded the WP way
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_style
    function css_includes() {
        wp_enqueue_style( 'cs_styles' );
    }
    
    // Sanitize settings array as needed before saving
    function sanitize_plugin_options( $options ) {
        // do whatever you need to each of the items in $options
        // e.g.,
        // $options['blah'] = stripcslashes( $options['blah'] );
        // 1. we need to store [exp-default] instead of the hours / days / weeks
        $expiration_defaults = array(
                                'def-hours' => $options['def-hours'],
                                'def-days' => $options['def-days'],
                                'def-weeks' => $options['def-weeks']
                                );
        $options['exp-default'] = $expiration_defaults;
        unset( $options['def-hours'] );
        unset( $options['def-days'] );
        unset( $options['def-weeks'] );
        return $options;
    }
    
    // Make any changes needed AFTER settings successfully saved
    function update_after_settings_change( $oldvalue, $_newvalue )
    {
        if( $this->debug ) {
            error_log( __FUNCTION__ . " running." );
        }
        // we need to reset our cron schedule if the interval changed
        if( $oldvalue['exp-period'] != $_newvalue['exp-period'] )
        {
            if( $this->debug ) {
                error_log( "exp-period is different, so we'll apply cron_schedules filter" );
                if( has_filter( 'cron_schedules' ) ) {
                    error_log( "cron_schedules is in filters" );
                } else {
                    error_log( "Uh-Oh; cron_schedules is not in filters" );
                }
            }
            // we need to update the cron schedule business
            apply_filters( 'cron_schedules', wp_get_schedules() );
            // now we need to re-schedule the content scheduler cron job
            // deactivate the current job
            if( is_multisite () ) {
                $blog_id = get_current_blog_id();
                // deactivate the current job
                wp_clear_scheduled_hook( 'contentscheduler' . $blog_id );
                // add the new job
                if ( !wp_next_scheduled( 'contentscheduler' . $blog_id ) ) {
                    wp_schedule_event( time(), 'contsched_usertime', 'contentscheduler' . $blog_id );
                }
            } else {
                // deactivate the current job
                wp_clear_scheduled_hook( 'contentscheduler' );
                // add the new job
                if ( !wp_next_scheduled( 'contentscheduler' ) ) {
                    wp_schedule_event( time(), 'contsched_usertime', 'contentscheduler' );
                }
            }
            
            // for debug
            if( $this->debug ) {
                // I want to see the wp_schedules
                $cron_schedule = wp_get_schedules();
                error_log( "=== WP Cron Schedules ===" );
                error_log( print_r( $cron_schedule, true ) );
                // I want to see the cron jobs
                error_log( "=== Cron Option ===" );
                error_log( print_r( _get_cron_array(), true ) );
            }
        }
    }

    // Getters
    protected function get_field_name( $name ) {
        return sprintf( '%s[%s]', $this->settings_field, $name );
    }
    protected function get_field_id( $id ) {
        return sprintf( '%s[%s]', $this->settings_field, $id );
    }
    protected function get_field_value( $kay ) {
        return $this->options[$key];
    }





// ========================================================================
// == Options Field Drawing Functions for add_settings
// == These callbacks draw the form fields in settings areas
// ========================================================================
		// Determine expiration status: are we doing it, or not?
		// exp-status
		function draw_set_expstatus_fn()
		{
			// make array of radio button items
			$items = array(
							array('0', __("Hold", 'contentscheduler'), __("Do nothing upon expiration.", 'contentscheduler') ),
							array('2', __("Delete", 'contentscheduler'), __("Move to trash upon expiration.", 'contentscheduler') ),
							array('1', __("Apply changes", 'contentscheduler'), __("Apply the changes below upon expiration.", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['exp-status'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[exp-status]' type='radio' /> $item[1] &mdash; $item[2]</label><br />";
			} // end foreach
		} // end draw_set_expstatus_fn()
		// 12/30/2010 3:03:11 PM -pk
		// Get the number of minutes they want wp-cron to wait between expiration checks.
		function draw_set_expperiod_fn()
		{
			$input_field = "<input id='exp-period' name='ContentScheduler_Options[exp-period]' size='10' type='text' value='{$this->options['exp-period']}' />";
			printf( __("Wait %s minutes between expiration checks.", 'contentscheduler'), $input_field);
			echo "<br />\n";
		} // end draw_set_expperiod_fn()
		// 4/28/2011 3:47:22 PM -pk
		// Get default expiration time.
		// This will be added to the publish time and used for expiration, if "DEFAULT" (case insensitive) is used in the date field
		function draw_set_expdefault_fn()
		{
			// This is stored as a string
			// does update options or whatever... does it serialize and unserialize? I'm guessing not.
			if( !isset( $this->options['exp-default'] ) )
			{
				// no default is in the database for some reason, so let's call it empty and move on
				$default_hours = '0';
				$default_days = '0';
				$default_weeks = '0';
			}
			else
			{
				// get the saved default and split it up
				$default_expiration_array = $this->options['exp-default'];
				$default_hours = $default_expiration_array['def-hours'];
				$default_days = $default_expiration_array['def-days'];
				$default_weeks = $default_expiration_array['def-weeks'];
			}
			// Spit it all out
			_e( 'For default expirations, add the following amount of time to publication time.', 'contentscheduler' );
			echo "<p>";
			echo "<label for='ContentScheduler_Options[def-hours]'>Hours: <input id='def-hours' name='ContentScheduler_Options[def-hours]' size='4' type='text' value='$default_hours' /></label>\n";
			echo "<label for='ContentScheduler_Options[def-days]'>Days: <input id='def-days' name='ContentScheduler_Options[def-days]' size='4' type='text' value='$default_days' /></label>\n";
			echo "<label for='ContentScheduler_Options[def-weeks]'>Weeks: <input id='def-weeks' name='ContentScheduler_Options[def-weeks]' size='4' type='text' value='$default_weeks' /></label></p>\n";
		} // end draw_set_expdefault_fn()
		
		// Make changes to post title?
		// chg-title
		function draw_set_chgtitle_fn()
		{
		    // make array of radio button items
		    $items = array(
		                    array('0', __("No Change", 'contentscheduler'), __("Do not change title.", 'contentscheduler') ),
		                    array('1', __("Add Before", 'contentscheduler'), __("Add text before current title.", 'contentscheduler') ),
		                    array('2', __("Add After", 'contentscheduler'), __("Add text after current title.", 'contentscheduler') )
		                    );
		    // Step through and spit out each item as radio button
		    foreach( $items as $item )
		    {
		        $checked = ($this->options['chg-title'] == $item[0] ) ? ' checked="checked" ' : '';
		        echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[chg-title]' type='radio' /> $item[1]&mdash; $item[2]</label><br />";
		    } // end foreach
		    // Now we need a field for the string that might get added
		    echo "<input id='title-add' name='ContentScheduler_Options[title-add]' size='40' type='text' value='{$this->options['title-add']}' />";
		} // end draw_set_chgtitle_fn()
		
		// How do we change "Status?"
		// chg-status
		function draw_set_chgstatus_fn()
		{
			// make array of radio button items
			$items = array(
							array('0', __("No Change", 'contentscheduler'), __("Do not change status.", 'contentscheduler') ),
							array('1', __("Pending", 'contentscheduler'), __("Change status to Pending.", 'contentscheduler') ),
							array('2', __("Draft", 'contentscheduler'), __("Change status to Draft.", 'contentscheduler') ),
							array('3', __("Private", 'contentscheduler'), __("Change visibility to Private.", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['chg-status'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[chg-status]' type='radio' /> $item[1] &mdash; $item[2]</label><br />";
			} // end foreach
		} // end draw_set_chgstatus_fn()
		// How do we change "Stickiness" (Stick post to home page)
		// chg-sticky
		function draw_set_chgsticky_fn()
		{
			// make array of radio button items
			$items = array(
							array('0', __("No Change", 'contentscheduler'), __("Do not unstick posts.", 'contentscheduler')),
							array('1', __("Unstick", 'contentscheduler'), __("Unstick posts.", 'contentscheduler'))
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['chg-sticky'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[chg-sticky]' type='radio' /> $item[1] &mdash; $item[2]</label><br />";
			} // end foreach
		} // end draw_set_chgsticky_fn()
		// How do we apply the category changes below?
		// chg-cat-method
		function draw_set_chgcatmethod_fn()
		{
			// make array of radio button items
			$items = array(
							array('0',  __("No Change", 'contentscheduler'),  __("Make no category changes.", 'contentscheduler')),
							array('1',  __("Add selected", 'contentscheduler'),  __("Add posts to selected categories.", 'contentscheduler')),
							array('2',  __("Remove selected", 'contentscheduler'),  __("Remove posts from selected categories.", 'contentscheduler')),
							array('3',  __("Match selected", 'contentscheduler'),  __("Make posts exist only in selected categories.", 'contentscheduler'))
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['chg-cat-method'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[chg-cat-method]' type='radio' /> $item[1] &mdash; $item[2]</label><br />";
			} // end foreach
		} // end draw_set_chgcatmethod_fn()
		// What categories do we have available to change to?
		// chg-categories
		function draw_set_categories_fn()
		{
			// Draw a checkbox for each category
			$categories = get_categories( array('hide_empty' => 0) );
			foreach ( $categories as $category )
			{
				// See if we need a checkbox or not
				if( !empty( $this->options['selcats'] ) )
				{
					$checked = checked( 1, in_array( $category->term_id, $this->options['selcats'] ), false );
				}
				else
				{
					$checked = '';
				}
				$box = "<input name='ContentScheduler_Options[selcats][]' id='$category->category_nicename' type='checkbox' value='$category->term_id' class='' ".$checked." /> $category->name<br />\n";
				echo $box;
			} // end foreach
		} // end draw_set_categories_fn()
		// What tags do we want added to content types that support tags?
		// tags-to-add
		// Be sure to check the content type for post_tags support before attempting to add
		function draw_add_tags_fn()
		{
			/* translators: example list of tags */
			_e( "Comma-delimited list, e.g., '+news, -martial arts, +old content'" );
			echo "<br \>\n<input id='tags-to-add' name='ContentScheduler_Options[tags-to-add]' size='40' type='text' value='{$this->options['tags-to-add']}' /><br />";
			_e( "(leave blank to change no tags.)" );
		} // end draw_add_tags_fn()
		// Notification Settings
		// Notification on or off?
		function draw_notify_on_fn()
		{
			// make array of radio button items
			$items = array(
							array('1', __("Notification on", 'contentscheduler'), __("Notify when expiration date is reached, even if 'Expiration status' is set to 'Hold.'", 'contentscheduler')),
							array('0', __("Notification off", 'contentscheduler'), __("Do not notify.", 'contentscheduler'))
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['notify-on'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[notify-on]' type='radio' /> $item[1] &mdash; $item[2]</label><br />";
			} // end foreach
		} // draw_notify_on_fn()
		// Notify the site admin?
		function draw_notify_admin_fn()
		{
			// make array of radio button items
			$items = array(
							array('1', __("Notify Admin", 'contentscheduler') ),
							array('0', __("Do not notify Admin", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['notify-admin'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[notify-admin]' type='radio' /> $item[1]</label><br />";
			} // end foreach
		} // end draw_notify_admin_fn()
		// Notify the content author?
		function draw_notify_author_fn()
		{
			// make array of radio button items
			$items = array(
							array('1', __("Notify Author", 'contentscheduler') ),
							array('0', __("Do not notify Author", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['notify-author'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[notify-author]' type='radio' /> $item[1]</label><br />";
			} // end foreach
		} // end draw_notify_author_fn
		// Set minimum level to see Content Scheduler fields and shortcodes
		// http://codex.wordpress.org/Roles_and_Capabilities#Roles
		function draw_min_level_fn()
		{
			$items = array(
							array("super_admin", 'level_10'),
							array("administrator", 'level_8'),
							array("editor", 'level_5'),
							array("author", 'level_2'),
							array("contributor", 'level_1'),
							array("subscriber", 'level_0')
							);
			echo "<select id='min-level' name='ContentScheduler_Options[min-level]'>\n";
			foreach( $items as $item )
			{
				$checked = ($this->options['min-level'] == $item[1] ) ? ' selected="selected" ' : ' ';
				echo "<option".$checked." value='$item[1]'>$item[0]</option>\n";
			}
			echo "</select>\n";
		} // end draw_min_level_fn()
		// Show expiration date in columnar lists?
		function draw_show_columns_fn()
		{
			// make array of radio button items
			$items = array(
							array('1', __("Show expiration in columns", 'contentscheduler') ),
							array('0', __("Do not show expiration in columns", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['show-columns'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[show-columns]' type='radio' /> $item[1]</label><br />";
			} // end foreach
		} // end draw_show_columns_fn

		// Use jQuery datepicker for the date field?
		function draw_show_datepicker_fn()
		{
			// make array of radio button items
			$items = array(
							array('1', __("Use datepicker", 'contentscheduler') ),
							array('0', __("Do not use datepicker", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['datepicker'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[datepicker]' type='radio' /> $item[1]</label><br />";
			} // end foreach
		} // end draw_show_datepicker_fn

		// Remove all CS data upon uninstall?
		function draw_remove_data_fn()
		{
			// make array of radio button items
			$items = array(
							array('1', __("Remove all data", 'contentscheduler') ),
							array('0', __("Do not remove data", 'contentscheduler') )
							);
			// Step through and spit out each item as radio button
			foreach( $items as $item )
			{
				$checked = ($this->options['remove-cs-data'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='ContentScheduler_Options[remove-cs-data]' type='radio' /> $item[1]</label><br />";
			} // end foreach
		} // end draw_remove_data_fn()
		// version as read-only?
		function draw_plugin_version()
		{
			echo "<p>" . $this->options['version'] . "</p>\n";
		} // end draw_plugin_version()



// ==========================================================================
// == Callbacks for drawing the top overview area for each settings Group
// ==========================================================================
    function draw_overview()
    {
        // This shows things under the title of Expiration Settings
        echo "<p>";
        _e( 'Indicate whether to process content on expiration, and whether to delete it or make certain changes to it.', 'contentscheduler' );
        echo "</p>\n";
    } // end overview_settings()
    function draw_overview_not()
    {
        // This shows things under the title of Notification Settings
        echo "<p>";
        _e( 'Indicate whether to send notifications about content expiration, who to notify, and when they should be notified.', 'contentscheduler' );
        echo "</p>\n";
    } // end draw_overview_not()
    function draw_overview_disp()
    {
        // This shows things under the title of Display Settings
        echo "<p>";
        _e( 'Control how Content Scheduler custom input areas display in the WordPress admin area. Also indicate if deleting the plugin should remove its options and post metadata.', 'contentscheduler' );
        echo "</p>\n";
    } // end draw_overview_disp()
		



		// Show our Options page in Admin
		// we're renaming this render now, an in content-scheduler-settings.php
		function ContentScheduler_drawoptions_fn()
		{
			?>
			<div class="wrap">
				<?php screen_icon("options-general"); ?>
				<h2>Content Scheduler <?php echo $$this->options['version']; ?></h2>
				<form action="options.php" method="post">
				<?php
				// nonces - hidden fields - auto via the SAPI
				settings_fields('ContentScheduler_Options_Group');
				// spits out fields defined by settings_fields and settings_sections
				do_settings_sections('ContentScheduler_Page_Title');
				?>
					<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'contentscheduler'); ?>" />
					</p>
				</form>
			</div>
			<?php
		} // end sample_form()
		
		
    // Render settings page
    // TODO: Need to go back and add the JS / CSS for metabox handline from example
    function render() {
        global $wp_meta_boxes;
        
        $title = __( 'Content Scheduler', 'contentscheduler' );
        ?>
        <div class="wrap">
            <?php screen_icon( "options-general" ); ?>
            <h2><?php echo esc_html( $title ); ?></h2>
            <form method="post" action="options.php">
                <div class="metabox-holder">
                    <div class="postbox-container" style="width: 99%;">
                    <?php
                        settings_fields( $this->settings_field);
                        do_settings_sections( 'cs_settings_page' );
                    ?>
                    </div><!-- /.postbox-container -->
                </div><!-- /.metabox-holder -->
                <p>
                <input type="submit" class="button button-primary" name="save_options" value="<?php esc_attr_e( 'Save Options' ); ?>" />
                </p>
            </form>
        </div><!-- /.wrap -->
    <?php } // end render()
    


    
    
    

                
                
                
    
    // Now I think we can put our settings drawing callbacks here for each field
    
} // end class Content_Scheduler_Settings

} // endif for Content_Scheduler_Settings existing
