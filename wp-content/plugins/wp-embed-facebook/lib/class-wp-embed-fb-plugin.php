<?php

/**
 * Main plugin file, stores defauls and utilities used along all the plugin.
 *
 */
class WP_Embed_FB_Plugin {
	const option_name = 'wpemfb_options';
	private static $path = null;
	private static $url = null;
	private static $options = null;
	private static $defaults = null;
	private static $lb_defaults = null;
	private static $has_photon = null;
	private static $wp_timezone = null;

	/**
	 * @var array $link_types Link fields needed for rendering a social plugin
	 */
	static $link_types = array( 'href', 'uri' );

	static function hooks() {
		//Session start when there is a facebook app
		add_action( 'init', __CLASS__ . '::init', 999 );

		//Translation string
		add_action( 'plugins_loaded', __CLASS__ . '::plugins_loaded' );

		//register all scripts and styles
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::wp_enqueue_scripts' );
	}

	static function install() {
		$type = ( get_option( 'wpemfb_theme' ) || get_option( self::option_name ) ) ? 'reactivated' : 'activated';
		self::get_option();

		return self::whois( $type );
	}

	/**
	 * Delete all plugin options on uninstall
	 */
	static function uninstall() {
		if ( is_multisite() ) {
			//TODO deprecated for get_sites in WP v4.6
			$sites = wp_get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site['blog_id'] );
				delete_option( self::option_name );
				delete_post_meta_by_key( '_wef_comment_count' );
			}
			restore_current_blog();
		} else {
			delete_option( self::option_name );
			delete_post_meta_by_key( '_wef_comment_count' );
		}

		return self::whois( 'uninstalled' );
	}

	static function deactivate() {
		return self::whois( 'deactivated' );
	}

	/**
	 * @return array old options to be deleated since 2.1
	 */
	static function old_options() {
		return array(
			'show_posts',
			'close_warning',
			'height',
			'close_warning1',
			'max_width',
			'max_photos',
			'max_posts',
			'app_id',
			'app_secret',
			'proportions',
			'show_like',
			'fb_root',
			'theme',
			'show_follow',
			'video_ratio',
			'video_as_post',
			'raw_video',
			'raw_photo',
			'raw_post',
			'raw_page',
			'enqueue_style',
			'enq_lightbox',
			'enq_wpemfb',
			'enq_fbjs',
			'ev_local_tz',
			'page_height',
			'page_show_faces',
			'page_small_header',
			'page_hide_cover',
			'page_show_posts',
			'sdk_lang',
			'close_warning2',
			'force_app_token',
			'video_download',
			'sdk_version'
		);
	}

	static function get_defaults() {
		if ( self::$defaults === null ) {
			$locale = get_locale();
			if ( strpos( $locale, 'es_' ) !== false ) {
				$locale = 'es_LA';
			}
			$vars           = WEF_Social_Plugins::get_defaults();
			$social_options = array();
			foreach ( $vars as $key => $value ) {
				foreach ( $value as $d_key => $d_value ) {
					if ( ! in_array( $d_key, self::$link_types ) ) {
						$social_options["{$key}_$d_key"] = $d_value;
					}
				}
			}
			self::$defaults = array(
				                  'sdk_lang'                       => array_key_exists( $locale, self::get_fb_locales() ) ? $locale : 'en_US',
				                  'max_width'                      => '450',
				                  'max_photos'                     => '24',
				                  'max_posts'                      => '0',
				                  'app_id'                         => '',
				                  'app_secret'                     => '',
				                  'theme'                          => 'default',
				                  'sdk_version'                    => 'v2.6',
				                  'show_like'                      => 'true',
				                  'fb_root'                        => 'true',
				                  'show_follow'                    => 'true',
				                  'video_ratio'                    => 'false',
				                  'video_as_post'                  => 'false',
				                  'raw_video'                      => 'false',
				                  'raw_photo'                      => 'false',
				                  'raw_post'                       => 'false',
				                  'raw_page'                       => 'false',
				                  'enqueue_style'                  => 'true',
				                  'enq_lightbox'                   => 'true',
				                  'enq_wpemfb'                     => 'true',
				                  'enq_fbjs'                       => 'true',
				                  'ev_local_tz'                    => 'false',
				                  'close_warning2'                 => 'false',
				                  'force_app_token'                => 'true',
				                  'video_download'                 => 'false',
				                  'enq_fbjs_global'                => 'false',
				                  'enq_when_needed'                => 'false',
				                  //Lightbox options
				                  'LB_albumLabel'                  => 'Image %1 of %2',
				                  'LB_alwaysShowNavOnTouchDevices' => 'false',
				                  'LB_showImageNumberLabel'        => 'true',
				                  'LB_wrapAround'                  => 'false',
				                  'LB_disableScrolling'            => 'false',
				                  'LB_fitImagesInViewport'         => 'true',
				                  'LB_maxWidth'                    => '0',
				                  'LB_maxHeight'                   => '0',
				                  'LB_positionFromTop'             => '50',
				                  'LB_resizeDuration'              => '700',
				                  'LB_fadeDuration'                => '500',
				                  'FB_plugins_as_iframe'           => 'false',
				                  'adaptive_fb_plugin'             => 'false',
				                  'quote_plugin_active'            => 'false',
				                  'quote_post_types'               => 'post,page',
				                  'auto_embed_active'              => 'true',//
//				                  'auto_embed_post_types'          => '',//TODO filter embed register handler per post_type
				                  'auto_comments_active'           => 'false',
				                  'auto_comments_post_types'       => 'post',
				                  'comments_count_active'          => 'true',
				                  'comments_open_graph'            => 'true',
//				                  'scrape_open_graph'              => 'true',
				                  'lightbox_att'                   => 'data-lightbox="roadtrip"',
			                  ) + $social_options;
		}

		return apply_filters( 'wpemfb_defaults', self::$defaults );
	}

	static function get_lb_defaults() {
		if ( self::$lb_defaults === null ) {
			$keys              = array(
				'albumLabel',
				'alwaysShowNavOnTouchDevices',
				'showImageNumberLabel',
				'wrapAround',
				'disableScrolling',
				'fitImagesInViewport',
				'maxWidth',
				'maxHeight',
				'positionFromTop',
				'resizeDuration',
				'fadeDuration'
			);
			self::$lb_defaults = array();
			$defaults          = self::get_defaults();
			foreach ( $keys as $key ) {
				self::$lb_defaults[ $key ] = $defaults[ 'LB_' . $key ];
			}
		}

		return self::$lb_defaults;
	}

	/**
	 * session start if necessary
	 */
	static function init() {
		if ( self::has_fb_app() ) {
			if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
				if ( session_id() == '' ) {
					session_start();
				}
			} elseif ( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
		}
	}

	/**
	 * Load translation file
	 */
	static function plugins_loaded() {

		load_plugin_textdomain( 'wp-embed-facebook', false, 'wp-embed-facebook/lang' );

	}

	/**
	 * Enqueue wp embed facebook styles
	 */
	static function wp_enqueue_scripts() {
		wp_register_style( 'wpemfb-default', self::url() . 'templates/default/default.css', array(), false );
		wp_register_style( 'wpemfb-classic', self::url() . 'templates/classic/classic.css', array(), false );
		wp_register_style( 'wpemfb-lightbox', self::url() . 'lib/lightbox2/css/lightbox.css', array(), false );
		wp_register_script( 'wpemfb-lightbox', self::url() . 'lib/lightbox2/js/lightbox.min.js', array( 'jquery' )
		);
		$lb_defaults       = self::get_lb_defaults();
		$options           = self::get_option();
		$translation_array = array();
		foreach ( $lb_defaults as $default_name => $value ) {
			if ( $options[ 'LB_' . $default_name ] !== $value ) {
				$translation_array[ $default_name ] = $options[ 'LB_' . $default_name ];
			}
		}
		if ( ! empty( $translation_array ) ) {
			//TODO use something like wp_add_inline_script('wpemfb-lightbox','new Lightbox(WEF_LB)') for LightBox options
			wp_localize_script( 'wpemfb-lightbox', 'WEF_LB', $translation_array );
		}
		wp_register_script(
			'wpemfb',
			self::url() . 'lib/js/wpembedfb.min.js',
			array( 'jquery' )
		);
		wp_register_script(
			'wpemfb-fbjs',
			self::url() . 'lib/js/fb.min.js',
			array( 'jquery' )
		);
		$translation_array = array(
			'local'   => $options['sdk_lang'],
			'version' => $options['sdk_version'],
			'fb_id'   => $options['app_id'] == '0' ? '' : $options['app_id']
		);
		if ( $options['auto_comments_active'] == 'true' && $options['comments_count_active'] == 'true' ) {
			$translation_array = $translation_array + array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				);
		}
		//TODO use something like wp_add_inline_script('wpemfb-fbjs','new Wpemfb(WEF)') for LightBox options
		wp_localize_script( 'wpemfb-fbjs', 'WEF', $translation_array );
		if ( $options['enq_when_needed'] == 'false' ) {
			if ( $options['enq_lightbox'] == 'true' ) {
				wp_enqueue_script( 'wpemfb-lightbox' );
				wp_enqueue_style( 'wpemfb-lightbox' );
			}
			if ( $options['enq_wpemfb'] == 'true' ) {
				wp_enqueue_script( 'wpemfb' );
			}
			if ( $options['enq_fbjs'] == 'true' ) {
				wp_enqueue_script( 'wpemfb-fbjs' );
			}
		}
		if ( $options['enq_fbjs_global'] == 'true' ) {
			wp_enqueue_script( 'wpemfb-fbjs' );
		}

		if ( ( $options['auto_comments_active'] == 'true' ) && is_single() ) {
			$array          = WP_Embed_FB_Plugin::string_to_array( $options['auto_comments_post_types'] );
			$queried_object = get_queried_object();
			if ( in_array( $queried_object->post_type, $array ) ) {
				wp_enqueue_script( 'wpemfb-fbjs' );
			}
		}
	}

	static function path() {
		if ( self::$path == null ) {
			self::$path = dirname( plugin_dir_path( __FILE__ ) ) . '/';
		}
		return self::$path;
	}

	static function url() {
		if ( self::$url == null ) {
			self::$url = dirname( plugin_dir_url( __FILE__ ) ) . '/';
		}
		return self::$url;
	}

	static function get_option( $option = null ) {
		if ( ! is_array( self::$options ) ) {
			$options  = get_option( self::option_name );
			$defaults = self::get_defaults();
			if ( is_array( $options ) ) {
				if ( $options == $defaults ) {
					self::$options = $options;
				} else {
					//check option array for corruption
					$compare = array();
					foreach ( $defaults as $default_key => $default_value ) {
						$compare[ $default_key ] = isset( $options[ $default_key ] ) ? $options[ $default_key ] : $default_value;
					}
					if ( $compare !== $options ) {
						if ( isset( $option['page_show_faces'] ) ) {
							$compare['page_show-facepile'] = $option['page_show_faces'];
							$compare['page_small-header']  = $option['page_small_header'];
							$compare['page_hide-cover']    = $option['page_hide_cover'];
							if ( $option['page_show_posts'] == 'true' ) {
								$compare['page_tabs'] = 'timeline';
							}
						}
						self::set_options( $compare );
					} else {
						//set cache value
						self::$options = $options;
					}
				}
			} else {
				if ( get_option( 'wpemfb_theme' ) ) {
					//upgrade options
					foreach ( self::old_options() as $old_option ) {
						if ( isset( $defaults[ $old_option ] ) ) {
							$defaults[ $old_option ] = get_option( 'wpemfb_' . $old_option );
						}
						delete_option( 'wpemfb_' . $old_option );
					}
					self::set_options( $defaults );
				} else {
					//new instalation
					//TODO get app id and secret from other plugins Jetpack or WP Social Login or... one day...
					self::set_options( $defaults );
				}
			}
		}
		if ( $option ) {
			return isset( self::$options[ $option ] ) ? self::$options[ $option ] : false;
		} else {
			return self::$options;
		}
	}

	static function set_options( $options ) {
		update_option( self::option_name, $options, true );
		self::$options = $options;
	}

	static function has_fb_app() {
		$app_id     = WP_Embed_FB_Plugin::get_option( 'app_id' );
		$app_secret = WP_Embed_FB_Plugin::get_option( 'app_secret' );
		if ( empty( $app_id ) || empty( $app_secret ) || $app_id === '0' || $app_secret === '0' ) {
			return false;
		} else {
			return true;
		}
	}

	static function has_photon() {
		if ( self::$has_photon === null ) {
			if ( class_exists( 'Jetpack' ) && method_exists( 'Jetpack', 'get_active_modules' ) && in_array( 'photon', Jetpack::get_active_modules() ) ) {
				self::$has_photon = true;
			} else {
				self::$has_photon = false;
			}
		}

		return self::$has_photon;
	}

	static function get_fb_locales() {
		return array(
			'af_ZA' => 'Afrikaans',
			'ak_GH' => 'Akan',
			'am_ET' => 'Amharic',
			'ar_AR' => 'Arabic',
			'as_IN' => 'Assamese',
			'ay_BO' => 'Aymara',
			'az_AZ' => 'Azerbaijani',
			'be_BY' => 'Belarusian',
			'bg_BG' => 'Bulgarian',
			'bn_IN' => 'Bengali',
			'br_FR' => 'Breton',
			'bs_BA' => 'Bosnian',
			'ca_ES' => 'Catalan',
			'cb_IQ' => 'Sorani Kurdish',
			'ck_US' => 'Cherokee',
			'co_FR' => 'Corsican',
			'cs_CZ' => 'Czech',
			'cx_PH' => 'Cebuano',
			'cy_GB' => 'Welsh',
			'da_DK' => 'Danish',
			'de_DE' => 'German',
			'el_GR' => 'Greek',
			'en_GB' => 'English (UK)',
			'en_IN' => 'English (India)',
			'en_PI' => 'English (Pirate)',
			'en_UD' => 'English (Upside Down)',
			'en_US' => 'English (US)',
			'eo_EO' => 'Esperanto',
			'es_CL' => 'Spanish (Chile)',
			'es_CO' => 'Spanish (Colombia)',
			'es_ES' => 'Spanish (Spain)',
			'es_LA' => 'Spanish',
			'es_MX' => 'Spanish (Mexico)',
			'es_VE' => 'Spanish (Venezuela)',
			'et_EE' => 'Estonian',
			'eu_ES' => 'Basque',
			'fa_IR' => 'Persian',
			'fb_LT' => 'Leet Speak',
			'ff_NG' => 'Fulah',
			'fi_FI' => 'Finnish',
			'fo_FO' => 'Faroese',
			'fr_CA' => 'French (Canada)',
			'fr_FR' => 'French (France)',
			'fy_NL' => 'Frisian',
			'ga_IE' => 'Irish',
			'gl_ES' => 'Galician',
			'gn_PY' => 'Guarani',
			'gu_IN' => 'Gujarati',
			'gx_GR' => 'Classical Greek',
			'ha_NG' => 'Hausa',
			'he_IL' => 'Hebrew',
			'hi_IN' => 'Hindi',
			'hr_HR' => 'Croatian',
			'hu_HU' => 'Hungarian',
			'hy_AM' => 'Armenian',
			'id_ID' => 'Indonesian',
			'ig_NG' => 'Igbo',
			'is_IS' => 'Icelandic',
			'it_IT' => 'Italian',
			'ja_JP' => 'Japanese',
			'ja_KS' => 'Japanese (Kansai)',
			'jv_ID' => 'Javanese',
			'ka_GE' => 'Georgian',
			'kk_KZ' => 'Kazakh',
			'km_KH' => 'Khmer',
			'kn_IN' => 'Kannada',
			'ko_KR' => 'Korean',
			'ku_TR' => 'Kurdish (Kurmanji)',
			'la_VA' => 'Latin',
			'lg_UG' => 'Ganda',
			'li_NL' => 'Limburgish',
			'ln_CD' => 'Lingala',
			'lo_LA' => 'Lao',
			'lt_LT' => 'Lithuanian',
			'lv_LV' => 'Latvian',
			'mg_MG' => 'Malagasy',
			'mk_MK' => 'Macedonian',
			'ml_IN' => 'Malayalam',
			'mn_MN' => 'Mongolian',
			'mr_IN' => 'Marathi',
			'ms_MY' => 'Malay',
			'mt_MT' => 'Maltese',
			'my_MM' => 'Burmese',
			'nb_NO' => 'Norwegian (bokmal)',
			'nd_ZW' => 'Ndebele',
			'ne_NP' => 'Nepali',
			'nl_BE' => 'Dutch (België)',
			'nl_NL' => 'Dutch',
			'nn_NO' => 'Norwegian (nynorsk)',
			'ny_MW' => 'Chewa',
			'or_IN' => 'Oriya',
			'pa_IN' => 'Punjabi',
			'pl_PL' => 'Polish',
			'ps_AF' => 'Pashto',
			'pt_BR' => 'Portuguese (Brazil)',
			'pt_PT' => 'Portuguese (Portugal)',
			'qu_PE' => 'Quechua',
			'rm_CH' => 'Romansh',
			'ro_RO' => 'Romanian',
			'ru_RU' => 'Russian',
			'rw_RW' => 'Kinyarwanda',
			'sa_IN' => 'Sanskrit',
			'sc_IT' => 'Sardinian',
			'se_NO' => 'Northern Sámi',
			'si_LK' => 'Sinhala',
			'sk_SK' => 'Slovak',
			'sl_SI' => 'Slovenian',
			'sn_ZW' => 'Shona',
			'so_SO' => 'Somali',
			'sq_AL' => 'Albanian',
			'sr_RS' => 'Serbian',
			'sv_SE' => 'Swedish',
			'sw_KE' => 'Swahili',
			'sy_SY' => 'Syriac',
			'sz_PL' => 'Silesian',
			'ta_IN' => 'Tamil',
			'te_IN' => 'Telugu',
			'tg_TJ' => 'Tajik',
			'th_TH' => 'Thai',
			'tk_TM' => 'Turkmen',
			'tl_PH' => 'Filipino',
			'tl_ST' => 'Klingon',
			'tr_TR' => 'Turkish',
			'tt_RU' => 'Tatar',
			'tz_MA' => 'Tamazight',
			'uk_UA' => 'Ukrainian',
			'ur_PK' => 'Urdu',
			'uz_UZ' => 'Uzbek',
			'vi_VN' => 'Vietnamese',
			'wo_SN' => 'Wolof',
			'xh_ZA' => 'Xhosa',
			'yi_DE' => 'Yiddish',
			'yo_NG' => 'Yoruba',
			'zh_CN' => 'Simplified Chinese (China)',
			'zh_HK' => 'Traditional Chinese (Hong Kong)',
			'zh_TW' => 'Traditional Chinese (Taiwan)',
			'zu_ZA' => 'Zulu',
			'zz_TR' => 'Zazaki',
		);
	}

	static function get_timezone() {
		if ( self::$wp_timezone === null ) {
			$tzstring = get_option( 'timezone_string', '' );
			if ( empty( $tzstring ) ) {
				$current_offset = get_option( 'gmt_offset', 0 );
				if ( 0 == $current_offset ) {
					$tzstring = 'Etc/GMT';
				} else {
					$tzstring = ( $current_offset < 0 ) ? 'Etc/GMT' . $current_offset : 'Etc/GMT+' . $current_offset;
				}
			}
			self::$wp_timezone = $tzstring;
		}

		return self::$wp_timezone;
	}

	static function string_to_array( $string ) {
		$array = explode( ',', $string );

		return array_map( 'trim', $array );
	}

	//("uninstalled","deactivated","activated","reactivated")
	protected static function whois( $install ) {
		$home = home_url();
		$home = esc_url( $home );
		@file_get_contents( "http://www.wpembedfb.com/api/?whois=$install&site_url=$home" );

		return true;
	}

}