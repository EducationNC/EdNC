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
	/**
	 * @var string Plugin slug
	 */
	private static $slug = null;
	/**
	 * Save default values to data base
	 */
	static function install(){
		$defaults = self::getdefaults();
		foreach ($defaults as $option => $value) {
			$opt = get_option($option);
			if($opt === false)
				update_option($option, $value);
			if(!isset($type))
				$type = $opt==false?"activated":"reactivated";
		}
		return self::whois($type);
	}
	/**
	 * Delete all plugin options on uninstall
	 */
	static function uninstall(){
		$defaults = self::getdefaults();
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
		return array(
			'wpemfb_max_width' 		    => '450',
			'wpemfb_max_photos' 	    => '24',
			'wpemfb_max_posts' 		    => '2',
			'wpemfb_show_posts' 	    => 'false',
			'wpemfb_app_id' 		    => '0',
			'wpemfb_app_secret'		    => '0',
			'wpemfb_proportions' 	    => 0.36867,
			'wpemfb_height'			    => '221.202',
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
			'wpemfb_sdk_lang'           => array_key_exists(get_locale(), self::get_fb_locales()) ? get_locale() : 'en_US',
			'wpemfb_close_warning'		=> 'false',
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
	 * load translations and facebook sdk
	 */
	static function init(){
		if(version_compare(phpversion(), '5.4.0', '<')) {
			if(session_id() == '')
				session_start();
		} elseif(session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		load_plugin_textdomain( 'wp-embed-facebook', false, self::get_slug() . '/lang' );
	}
	/**
	 * Enqueue wp embed facebook styles
	 */
	static function wp_enqueue_scripts(){
		if(get_option('wpemfb_enqueue_style') == 'true'){
			$theme = get_option('wpemfb_theme');
			wp_enqueue_style('wpemfb-'.$theme, self::get_url().'templates/'.$theme.'/'.$theme.'.css',array(),false);
			wp_enqueue_style('wpemfb-lightbox', self::get_url().'lib/lightbox2/css/lightbox.css',array(),false);
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
			$translation_array = array( 'local' => get_option('wpemfb_sdk_lang'), 'fb_id'=>get_option('wpemfb_app_id') == '0' ? '' : get_option('wpemfb_app_id'));
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
	static function get_slug(){
		if(self::$slug){
			return self::$slug;
		} else {
			self::$slug = dirname(dirname(plugin_basename(__FILE__)));
			return self::$slug;
		}
	}
	static function fb_root($content){
		return '<div id="fb-root"></div>'.PHP_EOL.$content;
	}
	static function admin_notices(){
		if(!self::has_fb_app()){
			if(get_option('wpemfb_close_warning') == 'false') :
				?>
				<div class="error notice wpemfb_warning is-dismissible">
				<p><?php _e('Setup Facebook App Id and Secret to access custom embeds.','wp-embed-facebook') ?>
					<a href="<?php echo admin_url("options-general.php?page=embedfacebook") ?>">
						<?php _e('Settings.','wp-embed-facebook') ?>
					</a>
				</p>
				</div>
				<?php
			endif;
		} else {
			//TODO rate and buy notice.
		}
	}
	static function close_warning(){
		if(current_user_can('manage_options'))
			update_option('wpemfb_close_warning','true');
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