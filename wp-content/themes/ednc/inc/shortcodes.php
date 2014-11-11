<?php

// shortcodes

// Buttons
	function buttons( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => 'radius', /* radius, round */
			'size' => 'medium', /* small, medium, large */
			'color' => 'blue',
			'nice' => 'false',
			'url'  => '',
			'text' => '',
		), $atts ) );

		$output = '<a href="' . $url . '" class="button '. $type . ' ' . $size . ' ' . $color;
		if( $nice == 'true' ){ $output .= ' nice';}
		$output .= '">';
		$output .= $text;
		$output .= '</a>';

		return $output;
	}

	add_shortcode('button', 'buttons');

// Alerts
	function alerts( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => '	', /* warning, success, error */
			'close' => 'false', /* display close link */
			'text' => '',
		), $atts ) );

		$output = '<div class="fade in alert-box '. $type . '">';

		$output .= $text;
		if($close == 'true') {
			$output .= '<a class="close" href="#">×</a></div>';
		}

		return $output;
	}

	add_shortcode('alert', 'alerts');

// Panels
	function panels( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => '	', /* warning, success, error */
			'close' => 'false', /* display close link */
			'text' => '',
		), $atts ) );

		$output = '<div class="panel">';
		$output .= $text;
		$output .= '</div>';

		return $output;
	}

	add_shortcode('panel', 'panels');

// Columns
	function column_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
			'width' => ''
		), $atts ) );

	    // remove orphan </p> from beginning of shortcode content
	    if (substr($content, 0, 4) == '</p>') $content = substr($content, 4);
	    // remove orphan <p> from end of shortcode content
	    if (substr($content, -3, 3) == '<p>') $content = substr($content, 0, -3);

	    return do_shortcode('<div class="columns-'.$width.'">'.$content.'</div>');
	}
	add_shortcode('column', 'column_shortcode');

// Collapsible sections in content
	function collapse_shortcode($atts, $content = null) {
	    return '<div class="collapsible">'.$content.'</div>';
	}
	add_shortcode('collapse', 'collapse_shortcode');

// Full-width highlight sections
	function highlight_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
			'caption' => ''
		), $atts ) );

		$output = '<div class="highlight large-pull-2 columns">';
		$output .= do_shortcode($content);
		$output .= '<div class="caption">' . $caption . '</div>';
		$output .= '</div>';

		return $output;
	}
	add_shortcode('highlight', 'highlight_shortcode');

// iframes
	function iframe_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
			'source' => ''
		), $atts ) );

		$output = '<div class="flex-iframe">';
		$output .= '<iframe src="'.$source.'"></iframe>';
		$output .= '</div>';

		return $output;
	}
	add_shortcode('iframe', 'iframe_shortcode');

// Eflip embeds
	function eflip_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
			'source' => ''
		), $atts ) );

		$output = '<div class="eflip-iframe">';
		$output .= '<iframe src="'.$source.'"></iframe>';
		$output .= '</div>';

		return $output;
	}
	add_shortcode('eflip', 'eflip_shortcode');

// Contact icons
	function contact_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
			'type' => ''
		), $atts ) );

		$output = '<span class="contact-' . $type . '">';
		$output .= $content;
		$output .= '</span>';

		return $output;
	}
	add_shortcode('contact', 'contact_shortcode');

// Aside shortcode
	function aside_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
			'align' => 'left'
		), $atts ) );

		$output = '<div class="aside align-'. $align . '">';
		$output .= $content;
		$output .= '</div>';

		return $output;
	}
	add_shortcode('aside', 'aside_shortcode');
?>
