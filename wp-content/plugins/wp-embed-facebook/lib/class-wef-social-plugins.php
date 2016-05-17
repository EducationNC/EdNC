<?php
/**
 * Class WEF_Social_Plugins
 *
 * Group of static functions to render facebook social plugins.
 *
 */
class WEF_Social_Plugins {
	/**
	 * @param string $href
	 *
	 * @return string
	 */
	static function send_btn( $href ) {
		return '<div class="fb-send" data-href="' . $href . '"></div>';
	}

	/**
	 * @param string $href
	 * @param $options array colorscheme | share | layout | show_faces
	 *
	 * @return string
	 */
	static function like_btn( $href, $options = array() ) {
		$defaults = array(
			'colorscheme' => 'light',//dark
			'share'       => 'false',
			'layout'      => 'standard',//"standard", "button_count", "button" or "box_count".
			'show_faces'  => 'false',
		);
		$options  = wp_parse_args( $options, $defaults );
		$string   = '';
		foreach ( $options as $data => $value ) {
			$string .= " data-" . str_replace( '_', '-', $data ) . "=$value";
		}

		return '<div class="fb-like" data-href="' . $href . '"' . $string . ' data-action="like"></div>';
	}

	/**
	 * @param $href
	 * @param array $options
	 *
	 * @return string
	 */
	static function follow_btn( $href, $options = array() ) {
		$defaults = array(
			'colorscheme' => 'light',//dark
			'layout'      => 'standard',//"standard", "button_count" or "box_count"
			'show_faces'  => 'false',
		);
		$options  = wp_parse_args( $options, $defaults );

		return '<div class="fb-follow" data-href="' . $href . '" data-colorscheme="' . $options['colorscheme'] . '" data-layout="' . $options['layout'] . '" data-show-faces="' . $options['show_faces'] . '" ></div>';
	}

	/**
	 * @param string $href
	 * @param string $layout Can be one of "box_count", "button_count", "button", "link", "icon_link", or "icon".
	 *
	 * @return string
	 */
	static function share_btn( $href, $layout = 'icon_link' ) {
		return '<div class="fb-share-button" data-href="' . $href . '" data-layout="' . $layout . '"></div>';
	}

	/**
	 * @param string $href
	 * @param int $width
	 * @param array $options hide_cover,show_facepile,show_posts,small_header,height
	 *
	 * @return string
	 */
	static function page_plugin( $href, $width, $options = array() ) {
		$defaults = array(
			'hide_cover'    => WP_Embed_FB_Plugin::get_option( 'page_hide_cover' ),
			'show_facepile' => WP_Embed_FB_Plugin::get_option( 'page_show_faces' ),
			'show_posts'    => WP_Embed_FB_Plugin::get_option( 'page_show_posts' ),
			'small_header'  => WP_Embed_FB_Plugin::get_option( 'page_small_header' ),
			'height'        => WP_Embed_FB_Plugin::get_option( 'page_height' ),
		);
		$options  = wp_parse_args( $options, $defaults );

		return '<div class="fb-page" data-href="' . $href . '" data-width="' . $width . '" data-hide-cover="' . $options["hide_cover"] . '" data-show-facepile="' . $options["show_facepile"] . '" data-show-posts="' . $options["show_posts"] . '" date-small-header="' . $options["small_header"] . '" data-height="' . $options["height"] . '"></div>';
	}

	/**
	 * @param string $href
	 * @param int $width
	 *
	 * @return string
	 */
	static function embedded_post( $href, $width ) {
		return '<div class="fb-post" data-href="' . $href . '" data-width="' . $width . '"></div>';
	}

	/**
	 * @param string $href
	 * @param int $width
	 *
	 * @return string
	 */
	static function embedded_video( $href, $width ) {
		return '<div class="fb-video" data-href="' . $href . '" data-width="' . $width . '"></div>';
	}

	/**
	 * @param string $href
	 * @param int $width
	 * @param array $options colorscheme,num_posts,order_by
	 *
	 * @return string
	 */
	static function comments( $href, $width, $options = array() ) {
		$defaults = array(
			'colorscheme' => 'light',//dark
			'num_posts'   => '10',
			'order_by'    => 'social',//Can be "social", "reverse_time", or "time".
		);
		$options  = wp_parse_args( $options, $defaults );

		return '<div class="fb-comments" data-width="' . $width . '" data-href="' . $href . '" data-numposts="' . $options['num_posts'] . '"  data-colorscheme="' . $options['colorscheme'] . '"></div>';
	}

}