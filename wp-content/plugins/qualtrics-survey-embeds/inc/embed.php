<?php 
/**
 * Define the embed handler
 * @param id
 * @param regex to match *.qualtrics.com
 * @param callback function
 */
wp_embed_register_handler( 'qse', '/https\:\/\/(.+?)\.qualtrics\.com\/(.+)/i' , 'qse_embed_handler' );

/**
 * Define the callback for the embed handler
 */
function qse_embed_handler( $matches, $attr, $url, $rawattr ) {
	$width = qse_get_setting( 'qse_settings', 'default', 'width' );
	$pxorpercent = qse_get_setting( 'qse_settings', 'default', 'pxorpercent' );
	$height = qse_get_setting( 'qse_settings', 'default', 'height' );
	$embed = sprintf(
					'<iframe src="https://%1$s.qualtrics.com/%2$s" name="Qualtrics" scrolling="auto" frameborder="no" align="center" height="%5$spx" width="%3$s%4$s"></iframe>',
					esc_attr($matches[1]),
					esc_attr($matches[2]),
					$width,
					$pxorpercent,
					$height
					);
	return apply_filters( 'qse_embed', $embed, $matches, $attr, $url, $rawattr );
}
?>
