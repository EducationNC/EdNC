<?php

/**
 * Where all the embedding happens.
 *
 * @uses WP_Embed_FB
 * @uses WEF_Social_Plugins
 * @uses WP_Embed_FB_Plugin
 */
class  WEF_Magic_Embeds extends WP_Embed_FB_Plugin {
	static function hooks() {

		/** @see WEF_Magic_Embeds::plugins_loaded */
		if ( self::get_option( 'auto_embed_active' ) == 'true' ) {
			add_filter( 'plugins_loaded', __CLASS__ . '::plugins_loaded' );
		}

		/** @see WEF_Magic_Embeds::the_content */
		add_filter( 'the_content', __CLASS__ . '::the_content' );

		/** @see WP_Embed_FB::shortcode */
		add_shortcode( 'facebook', 'WP_Embed_FB::shortcode' );

		/** @see WEF_Social_Plugins::shortcode */
		add_shortcode( 'fb_plugin', 'WEF_Social_Plugins::shortcode' );

		//TODO add content filter and option to force embed when it fails for weirb reasons
		//TODO do some magic with [facebook] JetPack shortcode.

//		PLUGIN ACTIONS AND FILTERS
		/** @see WEF_Social_Plugins::shortcode */
		add_filter( 'wef_sp_defaults', __CLASS__ . '::wef_sp_defaults', 10, 2 );
		add_filter( 'wef_sp_shortcode_filter', __CLASS__ . '::wef_sp_shortcode_filter',10,4 );
		add_action( 'wef_sp_shortcode_action', __CLASS__ . '::wef_sp_shortcode_action' );
		//wef_sp_embed
	}

	/**
	 * Adds fb_foot to top and quote plugin
	 *
	 * @param string $the_content Post content
	 *
	 * @return string
	 */
	static function the_content( $the_content ) {
		if ( self::get_option( 'fb_root' ) === 'true' ) {
			$the_content = '<div id="fb-root"></div>' . PHP_EOL . $the_content;
		}
		if ( is_single() && ( self::get_option( 'quote_plugin_active' ) === 'true' ) ) {
			$array = self::string_to_array( self::get_option( 'quote_post_types' ) );
			if ( in_array( $GLOBALS['post']->post_type, $array ) ) {
				$the_content .= WEF_Social_Plugins::get( 'quote' );
			}
		}

		return $the_content;
	}

	/**
	 * Adds Embed register handler
	 */
	static function plugins_loaded() {
		wp_embed_register_handler( "wpembedfb", "/(http|https):\/\/www\.facebook\.com\/([^<\s]*)/", 'WP_Embed_FB::embed_register_handler' );
	}

	static function wef_sp_defaults( $defaults, $type ) {
		$options  = self::get_option();
		foreach ( $defaults as $key => $value ) {
			if ( in_array( $key, self::$link_types ) ) {
				$defaults[ $key ] = home_url( '/?p=' . get_queried_object_id() );
			} else {
				$defaults[ $key ] = $options["{$type}_$key"];
			}
		}

		return $defaults;
	}

	static function wef_sp_shortcode_filter($ret,$type,$atts,$defaults) {
		if ( isset( $defaults[ $type ]['width'] ) && $type != 'comments' && $type != 'page' ) {
			$default_width = $defaults[ $type ]['width'];
			if ( isset( $atts['adaptive'] ) ) {
				if ( $atts['adaptive'] == 'true' ) {
					$ret .= self::add_adaptive( $default_width, $atts );
				}
			} elseif ( self::get_option( 'adaptive_fb_plugin' ) == 'true' ) {
				$ret .= self::add_adaptive( $default_width, $atts );
			}
		}
		if ( isset( $atts['debug'] ) ) {
			$atts_raw = $atts;
			$debug           = '';
			$atts_raw_string = '';
			unset( $atts_raw['debug'] );
			foreach ( $atts_raw as $key => $value ) {
				$atts_raw_string .= "$key=$value ";
			}
			$debug .= '<br><pre>';
			$debug .= '<strong>';
			$debug .= __( 'Shortcode used:', 'wp-embed-facebook' ) . "<br>";
			$debug .= '</strong>';
			$debug .= esc_html( htmlentities( "[fb_plugin $type $atts_raw_string]" ) );
			$debug .= '<br>';
			$debug .= '<strong>';
			$debug .= __( 'Final code:', 'wp-embed-facebook' ) . "<br>";
			$debug .= '</strong>';
			$debug .= esc_html( htmlentities( $ret, ENT_QUOTES ) );
			$debug .= '<br>';
			$debug .= '<strong>';
			$debug .= __( 'More information:', 'wp-embed-facebook' );
			$debug .= '</strong>';
			$debug .= WEF_Social_Plugins::get_links( $type );
			$debug .= '</pre>';
			$ret .= $debug;
		}
		return $ret;
	}
	static function wef_sp_shortcode_action() {
		if ( ( self::get_option( 'enq_when_needed' ) == 'true' ) && ( self::get_option( 'enq_fbjs' ) == 'true' ) ) {
			wp_enqueue_script( 'wpemfb-fbjs' );
		}
	}

	private static function add_adaptive( $default_width, $atts ) {
		$width = isset( $atts['width'] ) ? $atts['width'] : $default_width;
		wp_enqueue_script( 'wpemfb' );
		$ret = '';
		$ret .= '<div class="wef-measure"';
		if ( ! empty( $width ) ) {
			$ret .= ' style="max-width: ' . $width . 'px;"';
		}
		$ret .= '></div>';
		return $ret;
	}

}