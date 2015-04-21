<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// Enabled?
if ( $this->option['settings']['your_profile_enabled'] == false ) return; // stop executing file

// Does user have the capability?
if ( current_user_can( $this->info['cap'] ) == false || ( is_multisite() && is_super_admin() ) ) return; // stop executing file
?>
<div class="wrap">
	<h2><?php echo $this->option['settings']['your_profile_confirm_heading']; ?></h2>
	<form action="<?php echo esc_url( add_query_arg( array( $this->info['trigger'] => $this->user_ID, $this->info['nonce'] => wp_create_nonce( $this->info['nonce'] ) ) ) ); ?>" method="post">
		<p>
			<?php echo str_replace( '%username%', $this->user_login, $this->option['settings']['your_profile_confirm_warning'] ); ?>
		</p>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php echo esc_html( str_replace( '%username%', $this->user_login, $this->option['settings']['your_profile_confirm_button'] ) ); ?>" />
		</p>
	</form>
</div>