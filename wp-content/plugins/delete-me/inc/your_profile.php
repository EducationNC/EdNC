<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// Enabled?
if ( $this->option['settings']['your_profile_enabled'] == false ) return; // stop executing file

// Does user have the capability?
if ( $profileuser->has_cap( $this->info['cap'] ) == false || ( is_multisite() && is_super_admin() ) ) return; // stop executing file

// User has capability, prepare delete link
$attributes = array();
$attributes['class'] = $this->option['settings']['your_profile_class'];
$attributes['style'] = $this->option['settings']['your_profile_style'];
$attributes['href'] = esc_url( self_admin_url( 'options.php?page=' . $this->info['slug_prefix'] . '_confirmation' ) );

// Remove empty attributes
$attributes = array_filter( $attributes );

// Assemble attributes in key="value" pairs
foreach ( $attributes as $key => $value ) $paired_attributes[] = $key . '="' . $value . '"';

// Output delete link
?>
<table class="form-table">
	<tr>
		<th>&nbsp;</th>
		<td><?php echo '<a ' . implode( ' ', $paired_attributes ) . '>' . $this->option['settings']['your_profile_anchor'] . '</a>'; ?></td>
	</tr>
</table>
