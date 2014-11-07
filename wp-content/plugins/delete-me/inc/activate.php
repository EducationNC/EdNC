<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// Activate
if ( $network_wide ) {
	
	// Network activation
	// Add option defaults if not found
	$blog_ids = $this->wpdb->get_col( "SELECT `blog_id` FROM " . $this->wpdb->blogs );
		
	foreach ( $blog_ids as $blog_id ) {		
		
		switch_to_blog( $blog_id );		
		if ( count( $this->fetch_option() ) == 0 ) add_option( $this->info['option'], $this->default_option() );		
		restore_current_blog();
		
	}
	
} else {
	
	// Site activation
	// Add option defaults if not found
	if ( count( $this->fetch_option() ) == 0 ) add_option( $this->info['option'], $this->default_option() );
	
}
