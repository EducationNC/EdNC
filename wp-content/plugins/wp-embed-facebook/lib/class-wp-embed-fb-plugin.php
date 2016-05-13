<?php
class WP_Embed_FB_Plugin {
	/**
	 * @var string Plugin directory
	 */
	private static $path = null;
	/**
	 * @var string Plugin url
	 */
	private static $url = null;
	static function install(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option => $value) {
			$opt = get_option($option);
			if($opt === false)
				update_option($option, $value);
			if(!isset($type))
				$type = $opt==false?"activated":"reactivated";
		}

		/** @noinspection PhpUndefinedVariableInspection */

		return self::whois($type);
	}
	/**
	 * Delete all plugin options on uninstall
	 */
	static function uninstall(){
		$deprecated = array('wpemfb_show_posts'=>'','wpemfb_close_warning'=>'','wpemfb_height'=>'','wpemfb_close_warning1'=>'');
		$defaults = self::getdefaults() + $deprecated;
		if ( is_multisite() ) {
			$sites = wp_get_sites();
			foreach ($sites as $site) {
				switch_to_blog($site['blog_id']);
				foreach ($defaults as $option => $value ) {
					delete_option($option);
				}
			}
			restore_current_blog();
		} else {
			foreach ($defaults as $option => $value ) {
				delete_option($option);
			}
		}
		return self::whois('uninstalled');
	}
	static function deactivate(){
		return self::whois('deactivated');
	}
	/**
	 * @return array plugin defaults
	 */
	static function getdefaults(){
		$locale = get_locale();
		if(strpos($locale,'es_') !== false)
			$locale = 'es_LA';
		return array(
			'wpemfb_max_width' 		    => '450',
			'wpemfb_max_photos' 	    => '24',
			'wpemfb_max_posts' 		    => '0',
			'wpemfb_app_id' 		    => '',
			'wpemfb_app_secret'		    => '',
			'wpemfb_proportions' 	    => 0.36867,
			'wpemfb_show_like'		    => 'true',
			'wpemfb_fb_root'		    => 'true',
			'wpemfb_theme'			    => 'default',
			'wpemfb_show_follow'	    => 'true',
			'wpemfb_video_ratio'	    => 'false',
			'wpemfb_video_as_post'	    => 'false',
			'wpemfb_raw_video'		    => 'false',
			'wpemfb_raw_photo'		    => 'false',
			'wpemfb_raw_post'		    => 'false',
			'wpemfb_raw_page'           => 'false',
			'wpemfb_enqueue_style' 	    => 'true',
			'wpemfb_enq_lightbox'	    => 'true',
			'wpemfb_enq_wpemfb'		    => 'true',
			'wpemfb_enq_fbjs'		    => 'true',
			'wpemfb_ev_local_tz'        => 'false',
			'wpemfb_page_height'        => '500',
			'wpemfb_page_show_faces'    => 'true',
			'wpemfb_page_small_header'  => 'false',
			'wpemfb_page_hide_cover'    => 'false',
			'wpemfb_page_show_posts'    => 'false',
			'wpemfb_sdk_lang'           => array_key_exists( $locale, self::get_fb_locales()) ? $locale : 'en_US',
			'wpemfb_close_warning2'		=> 'false',
			'wpemfb_force_app_token'	=> 'true',
			'wpemfb_video_download'     => 'false',
			'wpemfb_sdk_version'        => 'v2.6',
		);
	}
	//("uninstalled","deactivated","activated","reactivated")
	protected static function whois($install){
		$home = home_url();
		$home = esc_url($home);
		@file_get_contents("http://www.wpembedfb.com/api/?whois=$install&site_url=$home");
		return true;
	}
	/**
	 * session start if necessary
	 */
	static function init(){
		if( self::has_fb_app() ){
			if(version_compare(phpversion(), '5.4.0', '<')) {
				if(session_id() == '')
					session_start();
			} elseif(session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}
	}
	/**
	 * Load translation file
	 */
	static function plugins_loaded(){
		load_plugin_textdomain( 'wp-embed-facebook', false, 'wp-embed-facebook/lang' );
	}
	/**
	 * Enqueue wp embed facebook styles
	 */
	static function wp_enqueue_scripts(){
		if(get_option('wpemfb_enqueue_style') == 'true'){
			wp_register_style('wpemfb-default',self::get_url().'templates/default/default.css',array(),false);
			wp_register_style('wpemfb-classic',self::get_url().'templates/classic/classic.css',array(),false);
//			do_action('wpemfb_register_style');
			$theme = get_option('wpemfb_theme');
			wp_enqueue_style('wpemfb-'.$theme);
			wp_enqueue_style('wpemfb-lightbox', self::get_url().'lib/lightbox2/css/lightbox.min.css',array(),false);
		}
		if(get_option('wpemfb_enq_lightbox') == 'true'){
			wp_enqueue_script(
				'wpemfb-lightbox',
				self::get_url().'lib/lightbox2/js/lightbox.min.js',
				array( 'jquery' )
			);
		}
		if(get_option('wpemfb_enq_wpemfb') == 'true'){
			wp_enqueue_script(
				'wpemfb',
				self::get_url().'lib/js/wpembedfb.js',
				array( 'jquery' )
			);
		}
		if(get_option('wpemfb_enq_fbjs') == 'true'){
			wp_enqueue_script(
				'wpemfb-fbjs',
				self::get_url().'lib/js/fb.js',
				array( 'jquery' )
			);
			$translation_array = array(
				'local' => get_option('wpemfb_sdk_lang','en_US'),
				'version' => get_option('wpemfb_sdk_version','v2.6'),
				'fb_id'=>get_option('wpemfb_app_id') == '0' ? '' : get_option('wpemfb_app_id'));
			wp_localize_script( 'wpemfb-fbjs', 'WEF', $translation_array );
		}
	}
	static function get_path(){
		if(self::$path){
			return self::$path;
		} else {
			self::$path = dirname(plugin_dir_path( __FILE__ )).'/';
			return self::$path;
		}
	}
	static function get_url(){
		if(self::$url){
			return self::$url;
		} else {
			self::$url = dirname(plugin_dir_url( __FILE__ )).'/';
			return self::$url;
		}
	}
	static function admin_notices(){
		if( (get_option('wpemfb_close_warning2','false') == 'false')) :
			?>
			<div class="notice wpemfb_warning is-dismissible">
				<h2>WP Embed Facebook</h2>
				<p>Hey! The last step.</p>
				<p><img style="position:relative; top: 5px;" height="20px" width="auto" src="<?php echo WP_Embed_FB_Plugin::get_url().'lib/admin/ic_setting.png' ?>">&nbsp;Turn on <a id="wef-video-down" href="<?php echo admin_url("options-general.php?page=embedfacebook") ?>">Video Download Option</a> in settings.</p>
				<small>
					<?php
					printf(__('To embed albums, events, profiles and video as HTML5 you will need a <a target="_blank" href="%s">Facebook App</a>','wp-embed-facebook'), 'https://developers.facebook.com/apps')
					?>
				</small>
				<p>
					<?php
						printf(__('This free plugin has taken <strong>thousands of hours</strong> to develop and maintain consider making a <a target="_blank" href="%s">donation</a> or leaving a <a target="_blank" href="%s">review</a> <strong>do not let us loose faith</strong> in humanity.','wp-embed-facebook'), 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26','https://wordpress.org/support/view/plugin-reviews/wp-embed-facebook')
					?>
				</p>

			</div>
			<?php
		endif;
	}
	static function wpemfb_close_warning(){
		if(current_user_can('manage_options'))
			update_option('wpemfb_close_warning2','true');
		die;
	}
	static function wpemfb_video_down(){
		if(current_user_can('manage_options')){
			update_option('wpemfb_close_warning2','true');
			update_option('wpemfb_video_download','true');
		}
		die;
	}
	static function has_fb_app(){
		$app_id = get_option('wpemfb_app_id');
		$app_secret = get_option('wpemfb_app_secret');
		if(empty($app_id) || empty($app_id) || $app_id === '0' || $app_secret === '0' ){
			return false;
		}  else {
			return true;
		}
	}
	static function get_fb_locales(){
		return array(
			'af_ZA'=>'Afrikaans',
			'ak_GH'=>'Akan',
			'am_ET'=>'Amharic',
			'ar_AR'=>'Arabic',
			'as_IN'=>'Assamese',
			'ay_BO'=>'Aymara',
			'az_AZ'=>'Azerbaijani',
			'be_BY'=>'Belarusian',
			'bg_BG'=>'Bulgarian',
			'bn_IN'=>'Bengali',
			'br_FR'=>'Breton',
			'bs_BA'=>'Bosnian',
			'ca_ES'=>'Catalan',
			'cb_IQ'=>'Sorani Kurdish',
			'ck_US'=>'Cherokee',
			'co_FR'=>'Corsican',
			'cs_CZ'=>'Czech',
			'cx_PH'=>'Cebuano',
			'cy_GB'=>'Welsh',
			'da_DK'=>'Danish',
			'de_DE'=>'German',
			'el_GR'=>'Greek',
			'en_GB'=>'English (UK)',
			'en_IN'=>'English (India)',
			'en_PI'=>'English (Pirate)',
			'en_UD'=>'English (Upside Down)',
			'en_US'=>'English (US)',
			'eo_EO'=>'Esperanto',
			'es_CL'=>'Spanish (Chile)',
			'es_CO'=>'Spanish (Colombia)',
			'es_ES'=>'Spanish (Spain)',
			'es_LA'=>'Spanish',
			'es_MX'=>'Spanish (Mexico)',
			'es_VE'=>'Spanish (Venezuela)',
			'et_EE'=>'Estonian',
			'eu_ES'=>'Basque',
			'fa_IR'=>'Persian',
			'fb_LT'=>'Leet Speak',
			'ff_NG'=>'Fulah',
			'fi_FI'=>'Finnish',
			'fo_FO'=>'Faroese',
			'fr_CA'=>'French (Canada)',
			'fr_FR'=>'French (France)',
			'fy_NL'=>'Frisian',
			'ga_IE'=>'Irish',
			'gl_ES'=>'Galician',
			'gn_PY'=>'Guarani',
			'gu_IN'=>'Gujarati',
			'gx_GR'=>'Classical Greek',
			'ha_NG'=>'Hausa',
			'he_IL'=>'Hebrew',
			'hi_IN'=>'Hindi',
			'hr_HR'=>'Croatian',
			'hu_HU'=>'Hungarian',
			'hy_AM'=>'Armenian',
			'id_ID'=>'Indonesian',
			'ig_NG'=>'Igbo',
			'is_IS'=>'Icelandic',
			'it_IT'=>'Italian',
			'ja_JP'=>'Japanese',
			'ja_KS'=>'Japanese (Kansai)',
			'jv_ID'=>'Javanese',
			'ka_GE'=>'Georgian',
			'kk_KZ'=>'Kazakh',
			'km_KH'=>'Khmer',
			'kn_IN'=>'Kannada',
			'ko_KR'=>'Korean',
			'ku_TR'=>'Kurdish (Kurmanji)',
			'la_VA'=>'Latin',
			'lg_UG'=>'Ganda',
			'li_NL'=>'Limburgish',
			'ln_CD'=>'Lingala',
			'lo_LA'=>'Lao',
			'lt_LT'=>'Lithuanian',
			'lv_LV'=>'Latvian',
			'mg_MG'=>'Malagasy',
			'mk_MK'=>'Macedonian',
			'ml_IN'=>'Malayalam',
			'mn_MN'=>'Mongolian',
			'mr_IN'=>'Marathi',
			'ms_MY'=>'Malay',
			'mt_MT'=>'Maltese',
			'my_MM'=>'Burmese',
			'nb_NO'=>'Norwegian (bokmal)',
			'nd_ZW'=>'Ndebele',
			'ne_NP'=>'Nepali',
			'nl_BE'=>'Dutch (België)',
			'nl_NL'=>'Dutch',
			'nn_NO'=>'Norwegian (nynorsk)',
			'ny_MW'=>'Chewa',
			'or_IN'=>'Oriya',
			'pa_IN'=>'Punjabi',
			'pl_PL'=>'Polish',
			'ps_AF'=>'Pashto',
			'pt_BR'=>'Portuguese (Brazil)',
			'pt_PT'=>'Portuguese (Portugal)',
			'qu_PE'=>'Quechua',
			'rm_CH'=>'Romansh',
			'ro_RO'=>'Romanian',
			'ru_RU'=>'Russian',
			'rw_RW'=>'Kinyarwanda',
			'sa_IN'=>'Sanskrit',
			'sc_IT'=>'Sardinian',
			'se_NO'=>'Northern Sámi',
			'si_LK'=>'Sinhala',
			'sk_SK'=>'Slovak',
			'sl_SI'=>'Slovenian',
			'sn_ZW'=>'Shona',
			'so_SO'=>'Somali',
			'sq_AL'=>'Albanian',
			'sr_RS'=>'Serbian',
			'sv_SE'=>'Swedish',
			'sw_KE'=>'Swahili',
			'sy_SY'=>'Syriac',
			'sz_PL'=>'Silesian',
			'ta_IN'=>'Tamil',
			'te_IN'=>'Telugu',
			'tg_TJ'=>'Tajik',
			'th_TH'=>'Thai',
			'tk_TM'=>'Turkmen',
			'tl_PH'=>'Filipino',
			'tl_ST'=>'Klingon',
			'tr_TR'=>'Turkish',
			'tt_RU'=>'Tatar',
			'tz_MA'=>'Tamazight',
			'uk_UA'=>'Ukrainian',
			'ur_PK'=>'Urdu',
			'uz_UZ'=>'Uzbek',
			'vi_VN'=>'Vietnamese',
			'wo_SN'=>'Wolof',
			'xh_ZA'=>'Xhosa',
			'yi_DE'=>'Yiddish',
			'yo_NG'=>'Yoruba',
			'zh_CN'=>'Simplified Chinese (China)',
			'zh_HK'=>'Traditional Chinese (Hong Kong)',
			'zh_TW'=>'Traditional Chinese (Taiwan)',
			'zu_ZA'=>'Zulu',
			'zz_TR'=>'Zazaki',
		);
	}
}