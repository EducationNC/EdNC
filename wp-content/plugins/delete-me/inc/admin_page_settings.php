<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// Does user have the capability for this menu page?
if ( current_user_can( 'delete_users' ) == false ) return; // stop executing file

// Form nonce
$form_nonce_action = $this->GET['page'] . '_nonce_action';
$form_nonce_name = $this->GET['page'] . '_nonce_name';

// [Save Changes] or [Restore Default Settings]
if ( isset( $this->POST[$form_nonce_name] ) && wp_verify_nonce( $this->POST[$form_nonce_name], $form_nonce_action ) ) {
	
	$default_option = $this->default_option();
	
	if ( isset( $this->GET['restore'] ) ) {
		
		// Restore default settings
		foreach ( $this->wp_roles->role_objects as $role ) $role->remove_cap( $this->info['cap'] );
		$this->option = $default_option;
		$admin_message_content = '<strong>Default settings restored.</strong>';
		
	} else {
		
		// Roles
		settype( $this->POST['roles'], 'array' );
		
		foreach ( $this->wp_roles->role_objects as $role ) {
			
			$checked = isset( $this->POST['roles'][$role->name] ) ? true : false;
			$has_cap = $role->has_cap( $this->info['cap'] ) ? true : false;
			
			if ( $checked == true && $has_cap == false ) {
				
				$role->add_cap( $this->info['cap'] );
				
			} elseif ( $checked == false && $has_cap == true ) {
				
				$role->remove_cap( $this->info['cap'] );
				
			}
			
		}
		
		// Your Profile
		settype( $this->POST['your_profile_class'], 'string' );
		settype( $this->POST['your_profile_style'], 'string' );
		settype( $this->POST['your_profile_anchor'], 'string' );
		settype( $this->POST['your_profile_confirm_heading'], 'string' );
		settype( $this->POST['your_profile_confirm_warning'], 'string' );
		settype( $this->POST['your_profile_confirm_button'], 'string' );
		settype( $this->POST['your_profile_landing_url'], 'string' );
		settype( $this->POST['your_profile_enabled'], 'bool' );
		$this->option['settings']['your_profile_class'] = empty( $this->POST['your_profile_class'] ) ? NULL : $this->POST['your_profile_class'];
		$this->option['settings']['your_profile_style'] = empty( $this->POST['your_profile_style'] ) ? NULL : $this->POST['your_profile_style'];
		$this->option['settings']['your_profile_anchor'] = empty( $this->POST['your_profile_anchor'] ) ? $default_option['settings']['your_profile_anchor'] : $this->POST['your_profile_anchor'];
		$this->option['settings']['your_profile_confirm_heading'] = empty( $this->POST['your_profile_confirm_heading'] ) ? $default_option['settings']['your_profile_confirm_heading'] : $this->POST['your_profile_confirm_heading'];
		$this->option['settings']['your_profile_confirm_warning'] = empty( $this->POST['your_profile_confirm_warning'] ) ? $default_option['settings']['your_profile_confirm_warning'] : $this->POST['your_profile_confirm_warning'];
		$this->option['settings']['your_profile_confirm_button'] = empty( $this->POST['your_profile_confirm_button'] ) ? $default_option['settings']['your_profile_confirm_button'] : $this->POST['your_profile_confirm_button'];
		$this->option['settings']['your_profile_landing_url'] = empty( $this->POST['your_profile_landing_url'] ) ? '' : $this->POST['your_profile_landing_url'];
		$this->option['settings']['your_profile_enabled'] = $this->POST['your_profile_enabled'];
		
		// Shortcode
		settype( $this->POST['shortcode_class'], 'string' );
		settype( $this->POST['shortcode_style'], 'string' );
		settype( $this->POST['shortcode_anchor'], 'string' );
		settype( $this->POST['shortcode_js_confirm_warning'], 'string' );
		settype( $this->POST['shortcode_js_confirm_enabled'], 'bool' );
		settype( $this->POST['shortcode_landing_url'], 'string' );
		$this->option['settings']['shortcode_class'] = empty( $this->POST['shortcode_class'] ) ? NULL : $this->POST['shortcode_class'];
		$this->option['settings']['shortcode_style'] = empty( $this->POST['shortcode_style'] ) ? NULL : $this->POST['shortcode_style'];
		$this->option['settings']['shortcode_anchor'] = empty( $this->POST['shortcode_anchor'] ) ? $default_option['settings']['shortcode_anchor'] : $this->POST['shortcode_anchor'];
		$this->option['settings']['shortcode_js_confirm_warning'] = empty( $this->POST['shortcode_js_confirm_warning'] ) ? $default_option['settings']['shortcode_js_confirm_warning'] : $this->POST['shortcode_js_confirm_warning'];
		$this->option['settings']['shortcode_js_confirm_enabled'] = $this->POST['shortcode_js_confirm_enabled'];
		$this->option['settings']['shortcode_landing_url'] = empty( $this->POST['shortcode_landing_url'] ) ? '' : $this->POST['shortcode_landing_url'];
		
		// Multisite: Delete from Network
		settype( $this->POST['ms_delete_from_network'], 'bool' );
		$this->option['settings']['ms_delete_from_network'] = $this->POST['ms_delete_from_network'];
		
		// Delete Comments
		settype( $this->POST['delete_comments'], 'bool' );
		$this->option['settings']['delete_comments'] = $this->POST['delete_comments'];
		
		// E-mail notification
		settype( $this->POST['email_notification'], 'bool' );
		$this->option['settings']['email_notification'] = $this->POST['email_notification'];
		
		// Admin message content
		$admin_message_content = '<strong>Settings saved.</strong>';
		
	}
	
	// Save Option
	$this->save_option();
	
	// Print admin message
	$this->admin_message_class = 'updated';
	$this->admin_message_content = $admin_message_content;
	$this->admin_message();
	
}
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br/></div>
	<h2><?php echo $this->info['name']; ?> Settings</h2>
	<form action="<?php echo esc_url( remove_query_arg( 'restore' ) ); ?>" method="post">
		<h3>Roles</h3>
		<table class="form-table">
			<tr>
				<th scope="row">Which roles can delete themselves?</th>
				<td>
				<?php
				
				foreach ( $this->wp_roles->role_objects as $role ) {
					
					$disabled = ( $role->name == 'administrator' ) ? ' disabled="disabled"' : '';						
					
					?>
					
					<label>
						<input type="checkbox" name="roles[<?php echo $role->name; ?>]" value="1"<?php echo $role->has_cap( $this->info['cap'] ) ? ' checked="checked"' : ''; echo $disabled; ?> />
						<?php if ( $role->name == 'administrator' ) echo esc_html( 'Super Admin & ' ); echo esc_html( $this->wp_roles->roles[$role->name]['name'] ); ?>
					</label>
					<br />
					
					<?php
					
				}
				
				?>
				<br />
				<div>
					<span class="description">
						Super Admins &amp; Administrators are disabled because they can already delete users in WordPress, no need to complicate that.
						For testing purposes you'll want to use a separate WordPress login with a role other than Super Admin &amp; Administrator so the delete links you configure are visible to you.
					</span>
				</div>
				</td>
			</tr>
		</table>
		<h3>Your Profile</h3>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="your_profile_anchor">Link</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Class &amp; Style are optional. The last box is the clickable content of the link in HTML (e.g. Delete Account &mdash; or &mdash; &lt;img alt=&quot;&quot; src=&quot;http://www.example.com/image.png&quot; width=&quot;100&quot; height=&quot;20&quot; /&gt;).">[?]</a></th>
				<td>
					<code>
						&lt;a
						class="<input type="text" name="your_profile_class" class="code" value="<?php echo esc_attr( $this->option['settings']['your_profile_class'] ); ?>" />"
						style="<input type="text" name="your_profile_style" class="code" value="<?php echo esc_attr( $this->option['settings']['your_profile_style'] ); ?>" />"
						&gt;
						<input type="text" id="your_profile_anchor" name="your_profile_anchor" class="code" value="<?php echo esc_attr( $this->option['settings']['your_profile_anchor'] ); ?>" />
						&lt;/a&gt;
					</code>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="your_profile_confirm_heading">Confirm Heading</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Heading, in HTML, used on confirmation page.">[?]</a></th>
				<td>
					<code>
						&lt;h2&gt;
						<input type="text" id="your_profile_confirm_heading" name="your_profile_confirm_heading" class="code" value="<?php echo esc_attr( $this->option['settings']['your_profile_confirm_heading'] ); ?>" />
						&lt;/h2&gt;
					</code>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="your_profile_confirm_warning">Confirm Warning</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Warning, in HTML, used on confirmation page. Use %username% for Username.">[?]</a></th>
				<td>
					<input type="text" id="your_profile_confirm_warning" name="your_profile_confirm_warning" class="code large-text" value="<?php echo esc_attr( $this->option['settings']['your_profile_confirm_warning'] ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="your_profile_confirm_button">Confirm Button</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Button text used on confirmation page. Use %username% for Username.">[?]</a></th>
				<td>
					<code>
						&lt;input
						type="submit"
						value="<input type="text" id="your_profile_confirm_button" name="your_profile_confirm_button" class="code" value="<?php echo esc_attr( $this->option['settings']['your_profile_confirm_button'] ); ?>" />"
						/&gt;
					</code>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="your_profile_landing_url">Landing URL</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Redirect user here after deletion.">[?]</a></th>
				<td>
					<input type="text" id="your_profile_landing_url" name="your_profile_landing_url" class="code large-text" value="<?php if ( $this->option['settings']['your_profile_landing_url'] != '' ) echo esc_url( $this->option['settings']['your_profile_landing_url'] ); ?>" />
					<code>Leave blank to remain at the same URL after deletion.</code>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="your_profile_enabled">Link Enabled</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Check box to show delete link near the bottom of the Your Profile page, uncheck box to hide delete link.">[?]</a></th>
				<td>
					<input type="checkbox" id="your_profile_enabled" name="your_profile_enabled" value="1"<?php echo ( $this->option['settings']['your_profile_enabled'] == true ) ? ' checked="checked"' : ''; ?> />
				</td>
			</tr>
		</table>
		<h3>Shortcode</h3>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="shortcode_anchor">Link</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Class &amp; Style are optional. The last box is the clickable content of the link in HTML (e.g. Delete Account &mdash; or &mdash; &lt;img alt=&quot;&quot; src=&quot;http://www.example.com/image.png&quot; width=&quot;100&quot; height=&quot;20&quot; /&gt;).">[?]</a></th>
				<td>
					<code>
						&lt;a
						class="<input type="text" name="shortcode_class" class="code" value="<?php echo esc_attr( $this->option['settings']['shortcode_class'] ); ?>" />"
						style="<input type="text" name="shortcode_style" class="code" value="<?php echo esc_attr( $this->option['settings']['shortcode_style'] ); ?>" />"
						&gt;
						<input type="text" id="shortcode_anchor" name="shortcode_anchor" class="code" value="<?php echo esc_attr( $this->option['settings']['shortcode_anchor'] ); ?>" />
						&lt;/a&gt;
					</code>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="shortcode_js_confirm_warning">JS Confirm Warning</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Warning text used for Javascript confirm dialog. Use \n for new lines and %username% for Username.">[?]</a></th>
				<td>
					<input type="text" id="shortcode_js_confirm_warning" name="shortcode_js_confirm_warning" class="code large-text" value="<?php echo esc_attr( $this->option['settings']['shortcode_js_confirm_warning'] ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="shortcode_js_confirm_enabled">JS Confirm Enabled</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Check box to use Javascript confirm dialog, uncheck box for deletion without further confirmation. Disabling this can be useful when setting up your own custom confirmation page. You'd place the shortcode on your custom confirmation page and the user simply clicks the delete link text or image you've configured to confirm deletion.">[?]</a></th>
				<td>
					<input type="checkbox" id="shortcode_js_confirm_enabled" name="shortcode_js_confirm_enabled" value="1"<?php echo ( $this->option['settings']['shortcode_js_confirm_enabled'] == true ) ? ' checked="checked"' : ''; ?> />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="shortcode_landing_url">Landing URL</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Redirect user here after deletion.">[?]</a></th>
				<td>
					<input type="text" id="shortcode_landing_url" name="shortcode_landing_url" class="code large-text" value="<?php if ( $this->option['settings']['shortcode_landing_url'] != '' ) echo esc_url( $this->option['settings']['shortcode_landing_url'] ); ?>" />
					<code>Leave blank to remain at the same URL after deletion.</code>
				</td>
			</tr>
			<tr>
				<th scope="row">Usage <a href="#" onclick="return false;" style="text-decoration: none;" title="Text inside the Shortcode open and close tags is only served to those who cannot delete themselves, everyone else will be shown the delete link. Attributes may be used to override settings, but are not required.">[?]</a></th>
				<td>
					<p>
						<code>[<?php echo $this->info['shortcode']; ?> /]</code><br />
						<code>[<?php echo $this->info['shortcode']; ?>]Text inside Shortcode tags[/<?php echo $this->info['shortcode']; ?>]</code>
					</p>
					<p>
						<code>&lt;?php echo do_shortcode( '[<?php echo $this->info['shortcode']; ?> /]' ); ?&gt;</code><br />
						<code>&lt;?php echo do_shortcode( '[<?php echo $this->info['shortcode']; ?>]Text inside Shortcode tags[/<?php echo $this->info['shortcode']; ?>]' ); ?&gt;</code>
					</p>
					<p>
						<code>Attributes: class, style, html, js_confirm_warning, landing_url</code>
					</p>
				</td>
			</tr>
		</table>
		<h3>Multisite <span class="description">( <?php echo is_multisite() ? 'On' : 'Off - The setting below applies only to WordPress Multisite installations.'; ?> )</span></h3>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="ms_delete_from_network">Delete From Network</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="When a user deletes themselves from this Site, IF THEY DON'T BELONG TO ANY OTHER NETWORK SITES their user will be deleted permanently from the Network.">[?]</a></th>
				<td>
					<input type="checkbox" id="ms_delete_from_network" name="ms_delete_from_network" value="1"<?php echo ( $this->option['settings']['ms_delete_from_network'] == true ) ? ' checked="checked"' : ''; ?> />
				</td>
			</tr>
		</table>
		<h3>Miscellaneous</h3>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="delete_comments">Delete Comments</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Delete all comments by the user when they delete themselves. IF MULTISITE only comments on the current Site are deleted, other Network Sites remain unaffected.">[?]</a></th>
				<td>
					<input type="checkbox" id="delete_comments" name="delete_comments" value="1"<?php echo ( $this->option['settings']['delete_comments'] == true ) ? ' checked="checked"' : ''; ?> />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="email_notification">E-mail Notification</label> <a href="#" onclick="return false;" style="text-decoration: none;" title="Send a text email with deletion details each time a user deletes themselves using <?php echo $this->info['name']; ?>. This will go to the site administrator email (i.e. <?php echo get_option( 'admin_email' ); ?>), the same email address used for new user notification.">[?]</a></th>
				<td>
					<input type="checkbox" id="email_notification" name="email_notification" value="1"<?php echo ( $this->option['settings']['email_notification'] == true ) ? ' checked="checked"' : ''; ?> />
				</td>
			</tr>
		</table>
		<p class="submit">
			<?php wp_nonce_field( $form_nonce_action, $form_nonce_name ); ?>
			<input type="submit" class="button-primary" value="Save Changes" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button-primary" value="Restore Default Settings" onclick="if ( confirm( 'WARNING!\n\nAll changes will be lost.\n\nAre you sure you want to Restore Default Settings?' ) ) { this.form.action='<?php echo esc_url( add_query_arg( array( 'restore' => 'true' ) ) ); ?>'; } else { return false; }" />
		</p>
	</form>
</div>
