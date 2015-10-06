<?php
// File called by class?
if ( isset( $this ) == false || get_class( $this ) != 'plugin_delete_me' ) exit;

// User does not have capability, link will not be shown, content (if any) inside of shortcode serves as alternative
if ( current_user_can( $this->info['cap'] ) == false || ( is_multisite() && is_super_admin() ) ) return; // stop execution of this file

// User has capability, prepare delete link
$atts = shortcode_atts( array(
	'class' => $this->option['settings']['shortcode_class'],							// Settings default
	'style' => $this->option['settings']['shortcode_style'],							// Settings default
	'html' => $this->option['settings']['shortcode_anchor'],							// Settings default
	'js_confirm_warning' => $this->option['settings']['shortcode_js_confirm_warning'],	// Settings default
	'landing_url' => '',																// Empty default
) , $atts );
$attributes = array();
$attributes['class'] = $atts['class'];
$attributes['style'] = $atts['style'];
$attributes['href'] = esc_url( add_query_arg(
	array_filter( // Removes landing_url if empty
		array(
			$this->info['trigger'] => $this->user_ID,
			$this->info['nonce'] => wp_create_nonce( $this->info['nonce'] ),
			$this->info['trigger'] . '_landing_url' => $atts['landing_url'],
		)
	)
) );
if ( $this->option['settings']['shortcode_js_confirm_enabled'] ) $attributes['onclick'] = "if ( ! confirm( '" . esc_html( addcslashes( str_replace( '%username%', $this->user_login, $atts['js_confirm_warning'] ), "'" ) ) . "' ) ) return false;";

// Remove empty attributes
$attributes = array_filter( $attributes );

// Assemble attributes in key="value" pairs
foreach ( $attributes as $key => $value ) $paired_attributes[] = $key . '="' . $value . '"';

// Implode attributes, return longcode as delete link
$longcode = '<a ' . implode( ' ', $paired_attributes ) . '>' . $atts['html'] . '</a>';
