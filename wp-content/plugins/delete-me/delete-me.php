<?php
/*
Plugin Name: Delete Me
Plugin URI: https://wordpress.org/plugins/delete-me/
Description: Allow users with specific WordPress roles to delete themselves from the <code>Your Profile</code> page or anywhere Shortcodes can be used using the Shortcode <code>[plugin_delete_me /]</code>. Settings for this plugin are found on the <code>Settings &rarr; Delete Me</code> subpanel. Multisite and Network Activation supported.
Version: 1.8
Author: Clinton Caldwell
Author URI: https://profiles.wordpress.org/cmc3215/
License: GPL2 http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Copyright (c) 2015 - Clinton Caldwell <clint3215@gmail.com>

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

if ( realpath( __FILE__ ) === realpath( $_SERVER['SCRIPT_FILENAME'] ) ) exit; // Prevent direct access

if ( class_exists( 'plugin_delete_me' ) == false ) :

class plugin_delete_me {
	
	private $wp_roles;
	private $wp_version;
	private $wpdb;
	private $user_ID;
	private $user_login;
	private $user_email;
	
	private $info;
	private $option;
	private $admin_message_class;
	private $admin_message_content;
	
	private $GET;
	private $POST;
	
	// Construct
	public function __construct() {
		
		global $wp_roles, $wp_version, $wpdb, $user_ID, $user_login, $user_email;
		$this->wp_roles = &$wp_roles;
		$this->wp_version = &$wp_version;
		$this->wpdb = &$wpdb;
		$this->user_ID = &$user_ID;
		$this->user_login = &$user_login;
		$this->user_email = &$user_email;
		
		$this->info = array(
			'name' => 'Delete Me',
			'uri' => 'https://wordpress.org/plugins/delete-me/',
			'donate_link' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L5VY6QDSAAZUL',
			'version' => '1.8',
			'wp_version_min' => '3.4',
			'option' => 'plugin_delete_me',
			'shortcode' => 'plugin_delete_me',
			'slug_prefix' => 'plugin_delete_me',
			'cap' => 'plugin_delete_me',
			'trigger' => 'plugin_delete_me',
			'nonce' => 'plugin_delete_me_nonce',
			'dirname' => dirname( __FILE__ )
		);
		
		if ( $this->is_compatible() == false ) {
			
			add_action( ( ( version_compare( $this->wp_version, '3.1', '>=' ) == true ) ? 'all_admin_notices' : 'admin_notices' ), array( &$this, 'incompatible_notice' ) );
			return; // stop execution
			
		}
		
		$this->option = $this->fetch_option();
		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		add_action( 'wp_loaded', array( &$this, 'init' ) );
		
	}
	
	// Is compatible
	private function is_compatible() {
		
		return version_compare( $this->wp_version, $this->info['wp_version_min'], '<' ) ? false : true;
		
	}
	
	// Incompatible notice
	public function incompatible_notice() {
		
		echo '<div class="error">';
		echo '	<p><strong>Plugin incompatible, <em>' . $this->info['name'] . ' (version ' . $this->info['version'] . ')</em> requires WordPress ' . $this->info['wp_version_min'] . ' or higher.</strong></p>';
		echo '</div>';
		
	}
	
	// Activate
	public function activate( $network_wide = false ) {
		
		include_once( $this->info['dirname'] . '/inc/activate.php' );
		
	}
		
	// Init
	public function init() {
		
		// Admin & Front-End
		if ( isset( $this->option['version'] ) ) {
			
			if ( version_compare( $this->option['version'], $this->info['version'], '<' ) ) {
				
				$this->upgrade();
				
			} elseif ( version_compare( $this->option['version'], $this->info['version'], '>' ) ) {
				
				add_action( 'all_admin_notices', array( &$this, 'downgrade_notice' ) );
				return; // stop execution
				
			}
			
		}
		
		$this->GET = $this->striptrim_deep( $_GET );
		if ( isset( $this->GET[$this->info['trigger']] ) ) $this->delete_user();
		add_action( 'wpmu_new_blog', array( &$this, 'wpmu_new_blog' ) );
		
		// Admin only
		if ( is_admin() ) {
			
			$this->POST = $this->striptrim_deep( $_POST );
			$this->admin_init();
			return; // stop execution
			
		}
		
		// Front-End only
		add_action( 'wp', array( &$this, 'add_shortcode' ) );
		
	}
	
	// Upgrade
	private function upgrade() {
		
		include_once( $this->info['dirname'] . '/inc/upgrade.php' );
		
	}
	
	// Downgrade notice
	public function downgrade_notice() {
		
		echo '<div class="error">';
		echo '	<p><strong>Plugin <em>' . $this->info['name'] . '</em> cannot be downgraded. <a href="' . esc_url( $this->info['uri'] ) . '">Visit plugin site</a> for the latest version.</strong></p>';
		echo '</div>';
		
	}
	
	// Delete user
	private function delete_user() {
		
		include_once( $this->info['dirname'] . '/inc/delete_user.php' );
		
	}
		
	// WPMU New blog
	public function wpmu_new_blog( $blog_id ) {
		
		if ( function_exists( 'is_plugin_active_for_network' ) == false ) include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		if ( is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
			
			switch_to_blog( $blog_id );
			$this->activate();
			restore_current_blog();
			
		}
		
	}
	
	// Admin init
	private function admin_init() {
		
		add_action( 'admin_menu', array( &$this, 'add_submenu_pages' ) );
		add_action( 'show_user_profile', array( &$this, 'your_profile' ) );
		add_filter( 'admin_title', array( &$this, 'admin_title' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );
		
	}
	
	// Plugin row meta
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		
		if ( $plugin_file == plugin_basename( __FILE__ ) ) $plugin_meta[] = '<a href="' . esc_url( $this->info['donate_link'] ) . '" title="Donate to this plugin">Donate to this plugin</a>';
		return $plugin_meta;
		
	}
	
	// Add submenu pages
	public function add_submenu_pages() {
		
		add_submenu_page(
			'options-general.php',						// parent menu slug or wordpress filename
			$this->info['name'] . ' Settings',			// page <title>
			$this->info['name'],						// submenu title
			'delete_users',								// capability
			$this->info['slug_prefix'] . '_settings',	// unique page slug (i.e. ?page=slug)
			array( &$this, 'admin_page_settings' )		// function to be called to output the page
		);
		
		add_submenu_page(
			'options.php',									// parent menu slug or wordpress filename
			NULL,											// page <title>
			NULL,											// submenu title
			'read',											// capability
			$this->info['slug_prefix'] . '_confirmation',	// unique page slug (i.e. ?page=slug)
			array( &$this, 'admin_page_confirmation' )		// function to be called to output the page
		);
		
	}
	
	// Admin page settings
	public function admin_page_settings() {
		
		include_once( $this->info['dirname'] . '/inc/admin_page_settings.php' );
		
	}
	
	// Admin page confirmation
	public function admin_page_confirmation() {
		
		include_once( $this->info['dirname'] . '/inc/admin_page_confirmation.php' );
		
	}
	
	// Your profile
	public function your_profile( $profileuser ) {
		
		include_once( $this->info['dirname'] . '/inc/your_profile.php' );
		
	}
	
	// Admin title
	public function admin_title( $admin_title, $title ) {
		
		global $pagenow, $parent_file;
		
		if (
		$pagenow == 'options.php' &&
		$parent_file == 'options.php' &&
		isset( $this->GET['page'] ) &&
		$this->GET['page'] == $this->info['slug_prefix'] . '_confirmation' ) $admin_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), __( 'Profile' ), get_bloginfo( 'name' ) );
		
		return $admin_title;
		
	}
	
	// Add shortcode
	public function add_shortcode() {
		
		add_shortcode( $this->info['shortcode'], array( &$this, 'shortcode' ) );
		
	}
	
	// Shortcode
	public function shortcode( $atts = array(), $content = '', $code = '' ) {
		
		include_once( $this->info['dirname'] . '/inc/shortcode.php' );
		return ( isset( $longcode ) ) ? $longcode : $content;
		
	}
	
	// Default option
	private function default_option() {
		
		return array(
			'settings' => array(
				'your_profile_class' => NULL,
				'your_profile_style' => NULL,
				'your_profile_anchor' => 'Delete Account',
				'your_profile_confirm_heading' => 'Delete Account',
				'your_profile_confirm_warning' => 'WARNING!<br /><br />Are you sure you want to delete user %username%?',
				'your_profile_confirm_button' => 'Confirm Deletion',
				'your_profile_landing_url' => home_url(),
				'your_profile_enabled' => true,
				'shortcode_class' => NULL,
				'shortcode_style' => NULL,
				'shortcode_anchor' => 'Delete Account',
				'shortcode_js_confirm_warning' => 'WARNING!\n\nAre you sure you want to delete user %username%?',
				'shortcode_js_confirm_enabled' => true,
				'shortcode_landing_url' => home_url(),
				'ms_delete_from_network' => true,
				'delete_comments' => false,
				'email_notification' => false,
			),
			'version' => $this->info['version']
		);
		
	}
	
	// Fetch option
	private function fetch_option() {
		
		return get_option( $this->info['option'], array() );
		
	}
	
	// Save option
	private function save_option() {
		
		return update_option( $this->info['option'], $this->option );
		
	}
	
	// Admin message
	public function admin_message() {
		
		if ( is_admin() == false ) return; // stop execution		
		echo '<div class="' . $this->admin_message_class . '">';
		echo '	<p>' . $this->admin_message_content . '</p>';
		echo '</div>';
		
	}
	
	// Sync arrays
	private function sync_arrays( $sync_to, $sync_from ) {
		
		foreach ( $sync_from as $key => $value ) {
			
			if ( array_key_exists( $key, $sync_to ) ) :
				
				if ( is_array( $sync_to[$key] ) && is_array( $sync_from[$key] ) ) {
					
					$sync_to[$key] = $this->sync_arrays( $sync_to[$key], $sync_from[$key] );
					
				} else {
					
					$sync_to[$key] = $value;
					
				}
				
			endif;
			
		}
		
		return $sync_to;
		
	}
	
	// Striptrim deep
	private function striptrim_deep( $value ) {
		
		if ( is_array( $value ) ) {
			
			$value = array_map( array( &$this, 'striptrim_deep' ), $value );
			
		} elseif ( is_object( $value ) ) {
			
			$vars = get_object_vars( $value );			
			foreach ( $vars as $key => $data ) $value->{$key} = $this->striptrim_deep( $data );
			
		} else {
			
			$value = trim( stripslashes( $value ) );
			
		}
		
		return $value;
		
	}
	
}

// Instaniate plugin class
new plugin_delete_me();

endif; // class_exists
