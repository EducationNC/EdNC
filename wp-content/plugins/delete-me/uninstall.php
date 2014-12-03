<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;
$plugin_cap = $plugin_option = 'plugin_' . str_replace( '-', '_', basename( dirname( __FILE__ ) ) );
global $wpdb, $wp_roles;

// Remove capability and delete option
if ( is_multisite() ) {
	
	$blog_ids = $wpdb->get_col( "SELECT `blog_id` FROM " . $wpdb->blogs );
	
	foreach ( $blog_ids as $blog_id ) {
		
		switch_to_blog( $blog_id );
		foreach ( $wp_roles->role_objects as $role ) $role->remove_cap( $plugin_cap );		
		delete_option( $plugin_option );
		restore_current_blog();
		
	}
		
} else {
	
	foreach ( $wp_roles->role_objects as $role ) $role->remove_cap( $plugin_cap );
	delete_option( $plugin_option );
		
}
