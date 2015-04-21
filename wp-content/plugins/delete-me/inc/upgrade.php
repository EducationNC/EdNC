<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// Record previous version
$previous_version = $this->option['version'];

// Make option changes
if ( version_compare( $previous_version, '1.2', '>' ) && version_compare( $previous_version, '1.6', '<' ) ) {
	
	$this->option['settings']['your_profile_confirm_warning'] = str_replace( '\n', '<br />', $this->option['settings']['your_profile_js_confirm'] );
	$this->option['settings']['shortcode_js_confirm_warning'] = $this->option['settings']['shortcode_js_confirm'];
	
}
$this->option['version'] = $this->info['version'];
$this->option = $this->sync_arrays( $this->default_option(), $this->option ); // sync old & new option arrays

// Save changes to update option with the newly synced option array
$this->save_option();

// Print admin message
$this->admin_message_class = 'updated';
$this->admin_message_content = 'Plugin ' . $this->info['name'] . ' updated to version ' . $this->info['version'] . ', previously installed version was ' . $previous_version . '. See <a href="' . $this->info['uri'] . 'changelog/">Changelog</a> for details.';
add_action( 'all_admin_notices', array( &$this, 'admin_message' ) );
