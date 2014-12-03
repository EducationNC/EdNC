<?php
/*
Plugin Name: Google Language Translator
Plugin URI: http://www.studio88design.com/plugins/google-language-translator
Version: 4.0.9
Description: The MOST SIMPLE Google Translator plugin.  This plugin adds Google Translator to your website by using a single shortcode, [google-translator]. Settings include: layout style, hide/show specific languages, hide/show Google toolbar, and hide/show Google branding. Add the shortcode to pages, posts, and widgets.
Author: Rob Myrick
Author URI: http://www.wp-studio.net/
*/

include( plugin_dir_path( __FILE__ ) . 'widget.php');

class google_language_translator {
  
  public $languages_array = array (
    'af' => 'Afrikaans',
    'sq' => 'Albanian',
    'ar' => 'Arabic',
    'hy' => 'Armenian',
    'az' => 'Azerbaijani',
    'eu' => 'Basque',
    'be' => 'Belarusian',
    'bn' => 'Bengali',
    'bs' => 'Bosnian',
    'bg' => 'Bulgarian',
    'ca' => 'Catalan',
    'ceb' => 'Cebuano',
    'zh-CN' => 'Chinese(Simplified)',
    'zh-TW' => 'Chinese(Traditional)',
    'hr' => 'Croatian',
    'cs' => 'Czech',
    'da' => 'Danish',
    'nl' => 'Dutch',
    'en' => 'English',
    'eo' => 'Esperanto',
    'et' => 'Estonian',
    'tl' => 'Filipino',
    'fi' => 'Finnish',
    'fr' => 'French',
    'gl' => 'Galician',
    'ka' => 'Georgian',
    'de' => 'German',
    'el' => 'Greek',
    'gu' => 'Gujarati',
    'ht' => 'Haitian',
    'ha' => 'Hausa',
    'iw' => 'Hebrew',
    'hi' => 'Hindi',
    'hmn' => 'Hmong',
    'hu' => 'Hungarian',
    'is' => 'Icelandic',
    'ig' => 'Igbo',
    'id' => 'Indonesian',
    'ga' => 'Irish',
    'it' => 'Italian',
    'ja' => 'Japanese',
    'jw' => 'Javanese',
    'kn' => 'Kannada',
    'km' => 'Khmer',
    'ko' => 'Korean',
    'lo' => 'Lao',
    'la' => 'Latin',
    'lv' => 'Latvian',
    'lt' => 'Lithuanian',
    'mk' => 'Macedonian',
    'ms' => 'Malay',
    'mt' => 'Maltese',
    'mi' => 'Maori',
    'mr' => 'Marathi',
    'mn' => 'Mongolian',
    'ne' => 'Nepali',
    'no' => 'Norwegian',
    'fa' => 'Persian',
    'pl' => 'Polish',
    'pt' => 'Portuguese',
    'pa' => 'Punjabi',
    'ro' => 'Romanian',
    'ru' => 'Russian',
    'sr' => 'Serbian',
    'sk' => 'Slovak',
    'sl' => 'Slovenian',
    'so' => 'Somali',
    'es' => 'Spanish',
    'sw' => 'Swahili',
    'sv' => 'Swedish',
    'ta' => 'Tamil',
    'te' => 'Telugu',
    'th' => 'Thai',
    'tr' => 'Turkish',
    'uk' => 'Ukranian',
    'ur' => 'Urdu',
    'vi' => 'Vietnamese',
    'cy' => 'Welsh',
    'yi' => 'Yiddish',
    'yo' => 'Yoruba',
    'zu' => 'Zulu'
  );
  
  public function __construct() {
    register_activation_hook( __FILE__, array( &$this, 'glt_activate' ));
    register_uninstall_hook( __FILE__, 'glt_deactivate' );
    add_action( 'admin_menu', array( &$this, 'add_my_admin_menus')); 
    add_action('admin_init',array(&$this, 'initialize_settings')); 
    add_action('wp_head',array(&$this, 'load_css'));
    add_action('wp_footer',array(&$this, 'footer_script'));
    add_shortcode( 'google-translator',array(&$this, 'google_translator_shortcode'));
    add_shortcode( 'glt', array(&$this, 'google_translator_menu_language'));
    add_filter('widget_text','do_shortcode');
    add_filter('walker_nav_menu_start_el', array(&$this,'menu_shortcodes') , 10 , 2);
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'glt_settings_link') );
  
    if (!is_admin()) {
      add_action('init',array(&$this, 'flags'));
    }
  }
  
  public function glt_activate() {
    add_option('googlelanguagetranslator_active', 1);   
    add_option('googlelanguagetranslator_language','en');
    add_option('googlelanguagetranslator_language_option','all');
    add_option('googlelanguagetranslator_flags','show_flags');
    add_option('language_display_settings',array ('en' => 1));
    add_option('flag_display_settings',array ('flag-en' => 1)); 
    add_option('googlelanguagetranslator_translatebox','yes'); 
    add_option('googlelanguagetranslator_display','Vertical'); 
    add_option('googlelanguagetranslator_toolbar','Yes'); 
    add_option('googlelanguagetranslator_showbranding','Yes'); 
    add_option('googlelanguagetranslator_flags_alignment','flags_left');   
    add_option('googlelanguagetranslator_analytics', 0);  
    add_option('googlelanguagetranslator_analytics_id','');
    add_option('googlelanguagetranslator_css','');
    add_option('googlelanguagetranslator_manage_translations',0);
    add_option('googlelanguagetranslator_multilanguage',0);
    add_option('googlelanguagetranslator_floating_widget','yes');
    add_option('googlelanguagetranslator_flag_size','18');
    add_option('googlelanguagetranslator_flags_order','');
  } 
  
  public function glt_deactivate() {
    delete_option('googlelanguagetranslator_active', 1);   
    delete_option('googlelanguagetranslator_language','en');
    delete_option('googlelanguagetranslator_language_option','all');
    delete_option('googlelanguagetranslator_flags','show_flags');
    delete_option('language_display_settings',array ('en' => 1));
    delete_option('flag_display_settings',array ('flag-en' => 1)); 
    delete_option('googlelanguagetranslator_translatebox','yes'); 
    delete_option('googlelanguagetranslator_display','Vertical'); 
    delete_option('googlelanguagetranslator_toolbar','Yes'); 
    delete_option('googlelanguagetranslator_showbranding','Yes'); 
    delete_option('googlelanguagetranslator_flags_alignment','flags_left');   
    delete_option('googlelanguagetranslator_analytics',1);  
    delete_option('googlelanguagetranslator_analytics_id','');
    delete_option('googlelanguagetranslator_css','');
    delete_option('googlelanguagetranslator_manage_translations',0);
    delete_option('googlelanguagetranslator_multilanguage',0);
    delete_option('googlelanguagetranslator_floating_widget','yes');
    delete_option('googlelanguagetranslator_flag_size','18');
    delete_option('googlelanguagetranslator_flags_order','');
  }
  
  public function glt_settings_link ( $links ) {
    $settings_link = array(
      '<a href="' . admin_url( 'options-general.php?page=google_language_translator' ) . '">Settings</a>',
    );
   return array_merge( $links, $settings_link );
  }
  
  public function add_my_admin_menus(){
    $p = add_options_page('Google Language Translator', 'Google Language Translator', 'manage_options', 'google_language_translator', array(&$this, 'page_layout_cb'));

    add_action( 'load-' . $p, array(&$this, 'load_admin_js' ));
  }

  public function load_admin_js(){
    add_action( 'admin_enqueue_scripts', array(&$this, 'enqueue_admin_js' ));
    add_action('admin_footer',array(&$this, 'footer_script'));
  }

  public function enqueue_admin_js(){
    wp_enqueue_script( 'my-admin-script', plugins_url('js/admin.js',__FILE__), array('jquery'));
    wp_enqueue_script( 'my-flag-script', plugins_url('js/flags.js',__FILE__), array('jquery'));
		
    if (get_option ('googlelanguagetranslator_floating_widget') == 'yes') {
      wp_enqueue_script( 'my-toolbar-script', plugins_url('js/toolbar.js',__FILE__), array('jquery'));
      wp_enqueue_script( 'my-load-toolbar-script', plugins_url('js/load-toolbar.js',__FILE__), array('jquery'));
      wp_register_style( 'toolbar.css', plugins_url('css/toolbar.css', __FILE__) );
      wp_enqueue_style( 'toolbar.css' );
    }
		
    wp_enqueue_script( 'jquery-ui.js', plugins_url('js/jquery-ui.js',__FILE__), array('jquery'));
    wp_enqueue_script( 'jquery-ui-sortable.js', plugins_url('js/jquery-ui-sortable.js',__FILE__), array('jquery'));
    wp_enqueue_script( 'jquery-ui-widget.js', plugins_url('js/jquery-ui-widget.js',__FILE__), array('jquery'));
    wp_enqueue_script( 'jquery-ui-mouse.js', plugins_url('js/jquery-ui-mouse.js',__FILE__), array('jquery'));
    wp_enqueue_script( 'load_sortable_flags', plugins_url('js/load-sortable-flags.js',__FILE__), array('jquery'));
    wp_register_style( 'jquery-ui.css', plugins_url('css/jquery-ui.css',__FILE__) );
    wp_register_style( 'style.css', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'style.css' );
    wp_enqueue_style( 'jquery-ui.css' );
  }
  
  public function flags() {
    wp_enqueue_script( 'flags', plugins_url('js/flags.js',__FILE__), array('jquery'));
	 
    if (get_option ('googlelanguagetranslator_floating_widget') == 'yes') {
      wp_enqueue_script( 'my-toolbar-script', plugins_url('js/toolbar.js',__FILE__), array('jquery'));
      wp_enqueue_script( 'my-load-toolbar-script', plugins_url('js/load-toolbar.js',__FILE__), array('jquery'));
      wp_register_style( 'toolbar.css', plugins_url('css/toolbar.css', __FILE__) );
      wp_enqueue_style( 'toolbar.css' );
    }
	 
    wp_register_style( 'style.css', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'style.css' );	
  }
  
  public function load_css() {
    include( plugin_dir_path( __FILE__ ) . '/css/style.php');
  }

  public function google_translator_shortcode() {
	
    if (get_option('googlelanguagetranslator_display')=='Vertical'){
        return $this->googlelanguagetranslator_vertical();
    }
    elseif(get_option('googlelanguagetranslator_display')=='Horizontal'){
        return $this->googlelanguagetranslator_horizontal();
    }
  }

  public function googlelanguagetranslator_included_languages() {
    if ( get_option('googlelanguagetranslator_language_option')=='specific') { 
	  $get_language_choices = get_option ('language_display_settings');
	  
	  foreach ($get_language_choices as $key=>$value) {
	    if($value == 1) {
		  $items[] = $key;
	    }
	  }
	  
	  $comma_separated = implode(",",array_values($items));
	  
	  if ( get_option('googlelanguagetranslator_display') == 'Vertical') {
	     $lang = ", includedLanguages:'".$comma_separated."'";
	       return $lang;
	  } elseif ( get_option('googlelanguagetranslator_display') == 'Horizontal') {
	     $lang = ", includedLanguages:'".$comma_separated."'";
	       return $lang;
      } 
    }
  }

  public function analytics() {
    if ( get_option('googlelanguagetranslator_analytics') == 1 ) {
	  $analytics_id = get_option('googlelanguagetranslator_analytics_id');
	  $analytics = "gaTrack: true, gaId: '".$analytics_id."'";

          if (!empty ($analytics_id) ):
	    return ', '.$analytics;
          endif;
    }
  }

  public function menu_shortcodes( $item_output,$item ) { 
    if ( !empty($item->description)) {
      $output = do_shortcode($item->description);
  
      if ( $output != $item->description )
        $item_output = $output;     
      }
    return $item_output;
  } 

  public function google_translator_menu_language($atts, $content = '') {
    extract(shortcode_atts(array(
      "language" => 'Spanish',
      "label" => 'Spanish'
    ), $atts));

    $default_language = get_option('googlelanguagetranslator_language'); 
    $language_code = array_search($language,$this->languages_array);
	
    return "<a class='nturl notranslate ".$language_code." single-language flag' title='".$language."'>".$label."</a>";
  }

  public function footer_script() {
	
    global $shortcode_started;
	 
    if (!$shortcode_started):
      //echo 'shortcode not loaded';
    else:
      //echo 'true';
    endif;
	
    $default_language = get_option('googlelanguagetranslator_language');
    $language_choices = $this->googlelanguagetranslator_included_languages();
    $get_language_option = get_option('googlelanguagetranslator_language_option');
    $get_flag_choices = get_option ('flag_display_settings');
    $floating_widget = get_option ('googlelanguagetranslator_floating_widget');
    $is_active = get_option ( 'googlelanguagetranslator_active' );
    $is_multilanguage = get_option('googlelanguagetranslator_multilanguage');
    $auto_display = ', autoDisplay: false';
    $str = ''; ?>

    <script>
      jQuery(document).ready(function($) {
        $("a.nturl").on("click",function() {
	  default_lang = "<?php echo get_option('googlelanguagetranslator_language'); ?>";
	  lang_prefix = $(this).attr("class").split(" ")[2];
			  
	  if (lang_prefix == default_lang) {			   
	    load_default();
	  } else {
	    load_selected_language();
	  }
			 
	  function load_default() {
	    doGoogleLanguageTranslator(default_lang + "|" + default_lang);
	  }
			 
	  function load_selected_language() {
	    doGoogleLanguageTranslator(default_lang + "|" + lang_prefix);
	  }
        });
		  
        $("a.flag").on("click",function() {
          default_lang = "<?php echo get_option('googlelanguagetranslator_language'); ?>";
	  lang_prefix = $(this).attr("class").split(" ")[2];
			  
	  if (lang_prefix == default_lang) {			   
	    load_default();
	  } else {
	    load_selected_language();
          }
			 
	  function load_default() {
	    doGoogleLanguageTranslator(default_lang + "|" + default_lang);
	  }
			 
	  function load_selected_language() {
	    doGoogleLanguageTranslator(default_lang + "|" + lang_prefix);
	  }
        });

        if ($ ("body > #google_language_translator").length == 0) {
          $("#glt-footer").html("<div id='google_language_translator'></div>");
        }
      });
    </script>

    <?php

    if( $is_active == 1) {
	    
      foreach ($get_flag_choices as $flag_choice_key) {}
	  
	if ($floating_widget=='yes' && $get_language_option != 'specific') {
	  $str.='<div id="glt-translate-trigger"><span class="notranslate">Translate &raquo;</span></div>';
          $str.='<div id="glt-toolbar"></div>';
	} //endif $floating_widget

    if ($shortcode_started != 'true') {  
        $str.='<div id="flags" style="display:none">';
	$str.='<ul id="sortable" class="ui-sortable">';	  
	  
	  if ((empty($new_languages_array_string)) || ($new_languages_array_count != $get_flag_choices_count)) {
		    foreach ($this->languages_array as $key=>$value) {
		      $language_code = $key;
		      $language_name = $value;
		      $language_name_flag = $language_name;
	            if ($flag_choice_key == '1') {
                 if ( isset ( $get_flag_choices['flag-'.$language_code.''] ) ) {
				  if ( $language_name == 'English' && $english_flag_choice == 'canadian_flag') {
					$language_name_flag = 'Canada';
				  }
				  if ( $language_name == "English" && $english_flag_choice == 'us_flag') {
                    $language_name_flag = 'United-States';
				  }
				  if ( $language_name == 'Spanish' && $spanish_flag_choice == 'mexican_flag') {
				    $language_name_flag = 'Mexico';
				  }
				  if ( $language_name == 'Portuguese' && $portuguese_flag_choice == 'brazilian_flag') {
					$language_name_flag = 'Brazil';
				  }
				  
				  if ($lang_attribute == 'yes') {
			        $str.='<li id="'.$language_name.'"><a title="'.$language_name.'" class="notranslate flag '.$language_code.'"></a></li>';
				  } else {
					$str.="<li id='".$language_name."'><a title='".$language_name."' class='notranslate flag ".$language_code."'></a></li>";
				  }
			     }
		        } //$key
	         }//foreach
	    } else {
		     foreach ($new_languages_array_codes as $value) {
		       $language_name = $value;
		       $language_code = array_search ($language_name,$this->languages_array);
		       $language_name_flag = $language_name;
			     if ($flag_choice_key == '1') {
			      if (in_array($language_name,$this->languages_array)) {
                   if ( isset ( $get_flag_choices['flag-'.$language_code.''] ) ) {
				    if ( $language_name == 'English' && $english_flag_choice == 'canadian_flag') {
					  $language_name_flag = 'Canada';
				    }
				    if ( $language_name == "English" && $english_flag_choice == 'us_flag') {
                      $language_name_flag = 'United-States';
				    }
				    if ( $language_name == 'Spanish' && $spanish_flag_choice == 'mexican_flag') {
				      $language_name_flag = 'Mexico';
				    }
				    if ( $language_name == 'Portuguese' && $portuguese_flag_choice == 'brazilian_flag') {
					  $language_name_flag = 'Brazil';
				    }
					
				    if ($lang_attribute == 'yes') {
			          $str.='<li id="'.$language_name.'"><a title="'.$language_name.'" class="notranslate flag '.$language_code.'"></a></li>';
				    } else {
					  $str.="<li id='".$language_name."'><a title='".$language_name."' class='notranslate flag ".$language_code."'></a></li>";
				    }
		           } //isset
			      } //in_array
		         }//flag_choice_key
	         }//foreach
	    }//endif
      $str.='</ul>';
      $str.='</div>';
	  }
	}

    $language_choices = $this->googlelanguagetranslator_included_languages();
    $layout = get_option('googlelanguagetranslator_display');
    $is_multilanguage = get_option('googlelanguagetranslator_multilanguage');
    $horizontal_layout = ', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL';
    $auto_display = ', autoDisplay: false';
    $default_language = get_option('googlelanguagetranslator_language');

        if ($is_multilanguage == 1) {
          $multilanguagePage = ', multilanguagePage:true';
		  $str.="<div id='glt-footer'></div><script type='text/javascript'>";   
          $str.="function GoogleLanguageTranslatorInit() { new google.translate.TranslateElement({pageLanguage: '".$default_language."'".$language_choices . ($layout=='Horizontal' ? $horizontal_layout : '') .  $auto_display . $multilanguagePage . $this->analytics()."}, 'google_language_translator');}</script>";
          $str.="<script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=GoogleLanguageTranslatorInit'></script>";
        echo $str;

	} elseif ($is_multilanguage == 0) {		  
		  $str.="<div id='glt-footer'></div><script type='text/javascript'>";     
          $str.="function GoogleLanguageTranslatorInit() { new google.translate.TranslateElement({pageLanguage: '".$default_language."'".$language_choices . ($layout=='Horizontal' ? $horizontal_layout : '') . $auto_display . $this->analytics()."}, 'google_language_translator');}</script>";
		  $str.="<script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=GoogleLanguageTranslatorInit'></script>";
	    echo $str;
	} 
  }
  
  public function googlelanguagetranslator_vertical() {
	
	global $shortcode_started;
	
	$shortcode_started = 'true';
	$get_flag_choices = get_option ('flag_display_settings');
	$new_languages_array_string = get_option('googlelanguagetranslator_flags_order');
	$new_languages_array = explode(",",$new_languages_array_string);
	$new_languages_array_codes = array_values($new_languages_array);
	$new_languages_array_count = count($new_languages_array);
	$get_flag_choices_count = count($get_flag_choices);
	$get_language_choices = get_option ('language_display_settings');
	$flag_width = get_option('googlelanguagetranslator_flag_size');
	$default_language_code = get_option('googlelanguagetranslator_language');
	$is_active = get_option ( 'googlelanguagetranslator_active' );
	$get_language_option = get_option('googlelanguagetranslator_language_option');
	$language_choices = $this->googlelanguagetranslator_included_languages();
	$floating_widget = get_option ('googlelanguagetranslator_floating_widget');
	$str = '';

	if( $is_active == 1){
	  
	  foreach ($get_flag_choices as $flag_choice_key) {}
	  
	    $str.='<div id="flags" class="size'.$flag_width.'">';
	    $str.='<ul id="sortable" class="ui-sortable" style="float:left">';
	  
	    if ((empty($new_languages_array_string)) || ($new_languages_array_count != $get_flag_choices_count)) {
		    foreach ($this->languages_array as $key=>$value) {
		      $language_code = $key;
		      $language_name = $value;
		      $language_name_flag = $language_name;
	                if ($flag_choice_key == '1') {
                          if ( isset ( $get_flag_choices['flag-'.$language_code.''] ) ) {				  
			    $str.="<li id='".$language_name."'><a title='".$language_name."' class='notranslate flag ".$language_code."'></a></li>";
		           }
	                 } //$key
	             }//foreach
	    } else {
		     foreach ($new_languages_array_codes as $value) {
		       $language_name = $value;
		       $language_code = array_search ($language_name,$this->languages_array);
		       $language_name_flag = $language_name;
			  if ($flag_choice_key == '1') {
			    if (in_array($language_name,$this->languages_array)) {
                              if ( isset ( $get_flag_choices['flag-'.$language_code.''] ) ) {
			        $str.="<li id='".$language_name."'><a title='".$language_name."' class='notranslate flag ".$language_code."'></a></li>";
		              } //isset
			    } //in_array
		          }//flag_choice_key
	             }//foreach
	   }//endif
      $str.='</ul>';
      $str.='</div>';
      $str.='<div id="google_language_translator"></div>';
        return $str;		
      } //End is_active
  } // End glt_vertical
  
  public function googlelanguagetranslator_horizontal(){
    $shortcode_started = true;
    $get_flag_choices = get_option ('flag_display_settings');
    $new_languages_array_string = get_option('googlelanguagetranslator_flags_order');
    $new_languages_array = explode(",",$new_languages_array_string);
    $new_languages_array_codes = array_values($new_languages_array);
    $new_languages_array_count = count($new_languages_array);
    $get_flag_choices_count = count($get_flag_choices);
    $get_language_choices = get_option ('language_display_settings');
    $flag_width = get_option('googlelanguagetranslator_flag_size');
    $default_language_code = get_option('googlelanguagetranslator_language');	
    $is_active = get_option ( 'googlelanguagetranslator_active' );
    $get_language_option = get_option('googlelanguagetranslator_language_option');
    $language_choices = $this->googlelanguagetranslator_included_languages();
    $floating_widget = get_option ('googlelanguagetranslator_floating_widget');
    $str = '';
  
    if( $is_active == 1) {
	  foreach ($get_flag_choices as $flag_choice_key) {}
	 
	    $str.='<div id="flags" class="size'.$flag_width.'">';
	    $str.='<ul id="sortable" class="ui-sortable" style="float:left">';
	  
	    if ((empty($new_languages_array_string)) || ($new_languages_array_count != $get_flag_choices_count)) {
	      foreach ($this->languages_array as $key=>$value) {
		$language_code = $key;
		$language_name = $value;
		$language_name_flag = $language_name;
			  
	        if ($flag_choice_key == '1') {
                  if ( isset ( $get_flag_choices['flag-'.$language_code.''] ) ) {				  
		    $str.="<li id='".$language_name."'><a title='".$language_name."' class='notranslate flag ".$language_code."'></a></li>";
	           }
		} //$key
	      }//foreach

	    } else {

	      foreach ($new_languages_array_codes as $value) {
		$language_name = $value;
	        $language_code = array_search ($language_name,$this->languages_array);
	        $language_name_flag = $language_name;
	          if ($flag_choice_key == '1') {
		    if (in_array($language_name,$this->languages_array)) {
                      if ( isset ( $get_flag_choices['flag-'.$language_code.''] ) ) {
			$str.="<li id='".$language_name."'><a title='".$language_name."' class='notranslate flag ".$language_code."'></a></li>";
		      } //isset
		    } //in_array
		  }//flag_choice_key
	       }//foreach
	    }//endif
      $str.='</ul>';
      $str.='</div>';
      $str.='<div id="google_language_translator"></div>';
        return $str;	
    }
  } // End glt_horizontal
 
  public function initialize_settings() {    
    add_settings_section('glt_settings','Settings','','google_language_translator'); 
  
    $settings_name_array = array (
'googlelanguagetranslator_active','googlelanguagetranslator_language','googlelanguagetranslator_language_option','language_display_settings','googlelanguagetranslator_flags','flag_display_settings','googlelanguagetranslator_translatebox','googlelanguagetranslator_display','googlelanguagetranslator_toolbar','googlelanguagetranslator_showbranding','googlelanguagetranslator_flags_alignment','googlelanguagetranslator_analytics','googlelanguagetranslator_analytics_id','googlelanguagetranslator_css','googlelanguagetranslator_manage_translations','googlelanguagetranslator_multilanguage','googlelanguagetranslator_floating_widget','googlelanguagetranslator_flag_size','googlelanguagetranslator_flags_order','googlelanguagetranslator_exclude_translation'
    );
	
    $settings_callback_array = array (  'googlelanguagetranslator_active_cb','googlelanguagetranslator_language_cb','googlelanguagetranslator_language_option_cb','language_display_settings_cb','googlelanguagetranslator_flags_cb','flag_display_settings_cb','googlelanguagetranslator_translatebox_cb','googlelanguagetranslator_display_cb','googlelanguagetranslator_toolbar_cb','googlelanguagetranslator_showbranding_cb','googlelanguagetranslator_flags_alignment_cb','googlelanguagetranslator_analytics_cb','googlelanguagetranslator_analytics_id_cb','googlelanguagetranslator_css_cb','googlelanguagetranslator_manage_translations_cb','googlelanguagetranslator_multilanguage_cb','googlelanguagetranslator_floating_widget_cb','googlelanguagetranslator_flag_size_cb','googlelanguagetranslator_flags_order_cb','googlelanguagetranslator_exclude_translation_cb'
    );
	
    foreach ($settings_name_array as $setting) {
      add_settings_field( $setting,'',$setting.'_cb','google_language_translator','glt_settings');
      register_setting( 'google_language_translator',$setting); 
    }
  }
  
  public function googlelanguagetranslator_active_cb() {  
    $option_name = 'googlelanguagetranslator_active' ;
    $new_value = 1;
      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.'');
	  		
	  $html = '<input type="checkbox" name="googlelanguagetranslator_active" id="googlelanguagetranslator_active" value="1" '.checked(1,$options,false).'/> &nbsp; Check this box to activate';
	  echo $html;
	}
  
  public function googlelanguagetranslator_language_cb() {
	  
	$option_name = 'googlelanguagetranslator_language';
    $new_value = 'en';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

      <select name="googlelanguagetranslator_language" id="googlelanguagetranslator_language">

	  <?php
	
        foreach ($this->languages_array as $key => $value) {
		  $language_code = $key;
		  $language_name = $value; ?>
		    <option value="<?php echo $language_code; ?>" <?php if($options==''.$language_code.''){echo "selected";}?>><?php echo $language_name; ?></option>
	      <?php } ?>
      </select>
    <?php     
    } 
  
    public function googlelanguagetranslator_language_option_cb() {
	
	$option_name = 'googlelanguagetranslator_language_option' ;
    $new_value = 'all';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

    <input type="radio" name="googlelanguagetranslator_language_option" id="googlelanguagetranslator_language_option" value="all" <?php if($options=='all'){echo "checked";}?>/> All Languages<br/>
	<input type="radio" name="googlelanguagetranslator_language_option" id="googlelanguagetranslator_language_option" value="specific" <?php if($options=='specific'){echo "checked";}?>/> Specific Languages
    <?php 
	}
  
    public function language_display_settings_cb() {
	  $default_language_code = get_option('googlelanguagetranslator_language');
	  $option_name = 'language_display_settings';
      $new_value = array(''.$default_language_code.'' => 1);
	  
	  if ( get_option( $option_name ) == false ) {
        // The option does not exist, so we update it.
        update_option( $option_name, $new_value );
	  } 
	  
	  $get_language_choices = get_option (''.$option_name.'');
	  
	  foreach ($this->languages_array as $key => $value) {
		$language_code = $key;
		$language_name = $value;
		$language_code_array[] = $key;
		
		if (!isset($get_language_choices[''.$language_code.''])) {
		  $get_language_choices[''.$language_code.''] = 0;
		}
		
		$items[] = $get_language_choices[''.$language_code.''];
		$language_codes = $language_code_array;
		$item_count = count($items); 

		if ($item_count == 1 || $item_count == 22 || $item_count == 43 || $item_count == 64) { ?>
          <div class="languages" style="width:25%; float:left">
	    <?php } ?>
		  <div><input type="checkbox" name="language_display_settings[<?php echo $language_code; ?>]" value="1"<?php checked( 1,$get_language_choices[''.$language_code.'']); ?>/><?php echo $language_name; ?></div>
        <?php 
		if ($item_count == 21 || $item_count == 42 || $item_count == 63 || $item_count == 81) { ?>
          </div>
        <?php } 
	  } ?>
     <div class="clear"></div>
    <?php
	}
  
    public function googlelanguagetranslator_flags_cb() { 
	
	  $option_name = 'googlelanguagetranslator_flags' ;
      $new_value = 'show_flags';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

      <input type="radio" name="googlelanguagetranslator_flags" id="googlelanguagetranslator_flags" value="show_flags" <?php if($options=='show_flags'){echo "checked";}?>/> Yes, show flag images<br/>
	  <input type="radio" name="googlelanguagetranslator_flags" id="googlelanguagetranslator_flags" value="hide_flags" <?php if($options=='hide_flags'){echo "checked";}?>/> No, hide flag images
    <?php 
	}  

    public function flag_display_settings_cb() {
	  $default_language_code = get_option('googlelanguagetranslator_language');
	  $option_name = 'flag_display_settings';
      $new_value = array('flag-'.$default_language_code.'' => 1);
	  
	  if ( get_option( $option_name ) == false ) {
        // The option does not exist, so we update it.
        update_option( $option_name, $new_value );
	  } 
	  
	  $get_flag_choices = get_option (''.$option_name.'');
	  
	  foreach ($this->languages_array as $key => $value) {
		$language_code = $key;
		$language_name = $value;
		$language_code_array[] = $key;
		
		if (!isset($get_flag_choices['flag-'.$language_code.''])) {
		  $get_flag_choices['flag-'.$language_code.''] = 0;
		}
		
		$items[] = $get_flag_choices['flag-'.$language_code.''];
		$language_codes = $language_code_array;
		$item_count = count($items); 

		if ($item_count == 1 || $item_count == 22 || $item_count == 43 || $item_count == 64) { ?>
          <div class="flagdisplay" style="width:25%; float:left">
	    <?php } ?>
		  <div><input type="checkbox" name="flag_display_settings[flag-<?php echo $language_code; ?>]" value="1"<?php checked( 1,$get_flag_choices['flag-'.$language_code.'']); ?>/><?php echo $language_name; ?></div>
        <?php 
		if ($item_count == 21 || $item_count == 42 || $item_count == 63 || $item_count == 81) { ?>
          </div>
        <?php } 
	  } ?>
     <div class="clear"></div>
    <?php
	}
  
    public function googlelanguagetranslator_floating_widget_cb() {
	
	$option_name = 'googlelanguagetranslator_floating_widget' ;
    $new_value = 'yes';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

          <select name="googlelanguagetranslator_floating_widget" id="googlelanguagetranslator_floating_widget" style="width:170px">
		      <option value="yes" <?php if($options=='yes'){echo "selected";}?>>Yes, show widget</option>
			  <option value="no" <?php if($options=='no'){echo "selected";}?>>No, hide widget</option>
		  </select>
  <?php }
  
  public function googlelanguagetranslator_translatebox_cb() {
	
    $option_name = 'googlelanguagetranslator_translatebox' ;
    $new_value = 'yes';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

          <select name="googlelanguagetranslator_translatebox" id="googlelanguagetranslator_translatebox" style="width:190px">
		      <option value="yes" <?php if($options=='yes'){echo "selected";}?>>Yes, show language box</option>
			  <option value="no" <?php if($options=='no'){echo "selected";}?>>No, hide language box</option>
		  </select>
  <?php }
  
  public function googlelanguagetranslator_display_cb() {
	
	$option_name = 'googlelanguagetranslator_display' ;
    $new_value = 'Vertical';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

          <select name="googlelanguagetranslator_display" id="googlelanguagetranslator_display" style="width:170px;">
             <option value="Vertical" <?php if(get_option('googlelanguagetranslator_display')=='Vertical'){echo "selected";}?>>Vertical</option>
             <option value="Horizontal" <?php if(get_option('googlelanguagetranslator_display')=='Horizontal'){echo "selected";}?>>Horizontal</option>
          </select>  
  <?php }
  
  public function googlelanguagetranslator_toolbar_cb() {
	
	$option_name = 'googlelanguagetranslator_toolbar' ;
    $new_value = 'Yes';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

          <select name="googlelanguagetranslator_toolbar" id="googlelanguagetranslator_toolbar" style="width:170px;">
             <option value="Yes" <?php if(get_option('googlelanguagetranslator_toolbar')=='Yes'){echo "selected";}?>>Yes</option>
             <option value="No" <?php if(get_option('googlelanguagetranslator_toolbar')=='No'){echo "selected";}?>>No</option>
          </select>
  <?php }
  
  public function googlelanguagetranslator_showbranding_cb() {
	
	$option_name = 'googlelanguagetranslator_showbranding' ;
    $new_value = 'Yes';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

          <select name="googlelanguagetranslator_showbranding" id="googlelanguagetranslator_showbranding" style="width:170px;">
             <option value="Yes" <?php if(get_option('googlelanguagetranslator_showbranding')=='Yes'){echo "selected";}?>>Yes</option>
             <option value="No" <?php if(get_option('googlelanguagetranslator_showbranding')=='No'){echo "selected";}?>>No</option>
          </select> 
  <?php }
  
  public function googlelanguagetranslator_flags_alignment_cb() {
	
	$option_name = 'googlelanguagetranslator_flags_alignment' ;
    $new_value = 'flags_left';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, 'flags_left' );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

      <input type="radio" name="googlelanguagetranslator_flags_alignment" id="flags_left" value="flags_left" <?php if($options=='flags_left'){echo "checked";}?>/> Align Left<br/>
      <input type="radio" name="googlelanguagetranslator_flags_alignment" id="flags_right" value="flags_right" <?php if($options=='flags_right'){echo "checked";}?>/> Align Right
  <?php }
  
  public function googlelanguagetranslator_analytics_cb() {
	
	$option_name = 'googlelanguagetranslator_analytics' ;
    $new_value = 0;

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.'');

    $html = '<input type="checkbox" name="googlelanguagetranslator_analytics" id="googlelanguagetranslator_analytics" value="1" '.checked(1,$options,false).'/> &nbsp; Activate Google Analytics tracking?';
    echo $html;
  }
  
  public function googlelanguagetranslator_analytics_id_cb() {
	
	$option_name = 'googlelanguagetranslator_analytics_id' ;
    $new_value = '';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.'');

    $html = '<input type="text" name="googlelanguagetranslator_analytics_id" id="googlelanguagetranslator_analytics_id" value="'.$options.'" />';
    echo $html;
  }
  
  public function googlelanguagetranslator_flag_size_cb() {
	
	$option_name = 'googlelanguagetranslator_flag_size' ;
    $new_value = '18';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); ?>

          <select name="googlelanguagetranslator_flag_size" id="googlelanguagetranslator_flag_size" style="width:110px;">
             <option value="16" <?php if($options=='16'){echo "selected";}?>>16px</option>
			 <option value="18" <?php if($options=='18'){echo "selected";}?>>18px</option>
             <option value="20" <?php if($options=='20'){echo "selected";}?>>20px</option>
			 <option value="22" <?php if($options=='22'){echo "selected";}?>>22px</option>
             <option value="24" <?php if($options=='24'){echo "selected";}?>>24px</option>
          </select> 
  <?php }
  
  public function googlelanguagetranslator_flags_order_cb() {
	$option_name = 'googlelanguagetranslator_flags_order';
	$new_value = '';
	
	if ( get_option ( $option_name ) === false ) {
	  
	  // The option does not exist, so we update it.
	  update_option( $option_name, $new_value );
	}
	
	$options = get_option ( ''.$option_name.'' ); ?>

    <input type="hidden" id="order" name="googlelanguagetranslator_flags_order" value="<?php print_r(get_option('googlelanguagetranslator_flags_order')); ?>" />
   <?php
  }
  
  public function googlelanguagetranslator_css_cb() {
	 
    $option_name = 'googlelanguagetranslator_css' ;
    $new_value = '';

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.'');
    
	  $html = '<textarea style="width:100%; height:200px" name="googlelanguagetranslator_css" id="googlelanguagetranslator_css">'.$options.'</textarea>';
    echo $html;
 }
  
  public function googlelanguagetranslator_manage_translations_cb() { 
     $option_name = 'googlelanguagetranslator_manage_translations' ;
     $new_value = 0;

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.'');

    $html = '<input type="checkbox" name="googlelanguagetranslator_manage_translations" id="googlelanguagetranslator_manage_translations" value="1" '.checked(1,$options,false).'/> &nbsp; Turn on translation management?';
    echo $html;
  }
  
  public function googlelanguagetranslator_multilanguage_cb() {
	
	$option_name = 'googlelanguagetranslator_multilanguage' ;
    $new_value = 0;

      if ( get_option( $option_name ) === false ) {

      // The option does not exist, so we update it.
      update_option( $option_name, $new_value );
	  } 
	  
	  $options = get_option (''.$option_name.''); 

      $html = '<input type="checkbox" name="googlelanguagetranslator_multilanguage" id="googlelanguagetranslator_multilanguage" value="1" '.checked(1,$options,false).'/> &nbsp; Turn on multilanguage mode?';
      echo $html; 
  }
  
  public function googlelanguagetranslator_exclude_translation_cb() {
	
	$option_name = 'googlelanguagetranslator_exclude_translation';
	$new_value = '';
	
	if (get_option($option_name) === false ) {
	  // The option does not exist, so we update it.
	  update_option( $option_name, $new_value );
	}
	
	$options = get_option (''.$option_name.'');
	
	$html = '<input type="text" name="'.$option_name.'" id="'.$option_name.'" value="'.$options.'" />';
	
	echo $html;
  }
   
  public function page_layout_cb() { 
    include( plugin_dir_path( __FILE__ ) . '/css/style.php'); ?>
        <?php add_thickbox(); ?>
        <div class="wrap">
	      <div id="icon-options-general" class="icon32"></div>
	        <h2><span class="notranslate">Google Language Translator</span></h2>
		      <form action="<?php echo admin_url('options.php'); ?>" method="post">
	          <div class="metabox-holder has-right-sidebar" style="float:left; width:65%">
                <div class="postbox" style="width: 100%">
                  <h3 class="notranslate">Settings</h3>
                  
			      <?php settings_fields('google_language_translator'); ?>
                    <table style="border-collapse:separate" width="100%" border="0" cellspacing="8" cellpadding="0" class="form-table">
                      <tr>
						<td style="width:60%" class="notranslate">Plugin Status:</td>
				        <td class="notranslate"><?php $this->googlelanguagetranslator_active_cb(); ?></td>
                      </tr>
					  
					  <tr class="notranslate">
				        <td>Choose the original language of your website</td>
						<td><?php $this->googlelanguagetranslator_language_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
						<td>What translation languages will display in the language box?<br/>("All Languages" option <strong><u>must</u></strong> be chosen to show flags.)</td>
						<td><?php $this->googlelanguagetranslator_language_option_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate languages">
						<td colspan="2"><?php $this->language_display_settings_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
				        <td class="choose_flags_intro">Show flag images?<br/>(Display up to 81 flags above the translator)</td>
						<td class="choose_flags_intro"><?php $this->googlelanguagetranslator_flags_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate choose_flags">
				        <td class="choose_flags">Choose the flags you want to display:</td>
				        <td></td>
			          </tr>
					  
					  <tr class="notranslate">
						<td colspan="2" class="choose_flags"><?php $this->flag_display_settings_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
						<td>Show floating translation widget? <strong style="color:red">(New!)</strong><br/>
						  <span>("All Languages" option <strong><u>must</u></strong> be chosen to show widget.)</span>
						</td>
						<td><?php $this->googlelanguagetranslator_floating_widget_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
				        <td>Show translate box?</td>
						<td><?php $this->googlelanguagetranslator_translatebox_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
                        <td>Layout option:</td>
						<td><?php $this->googlelanguagetranslator_display_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
                        <td>Show Google Toolbar?</td>
						<td><?php $this->googlelanguagetranslator_toolbar_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
				        <td>Show Google Branding? &nbsp;<a href="https://developers.google.com/translate/v2/attribution" target="_blank">Learn more</a></td>
						<td><?php $this->googlelanguagetranslator_showbranding_cb(); ?></td>
					  </tr>
					  
					  <tr class="alignment notranslate">
				        <td class="flagdisplay">Align the translator left or right?</td>
						<td class="flagdisplay"><?php $this->googlelanguagetranslator_flags_alignment_cb(); ?></td>
					  </tr>
					  
					  <tr class="manage_translations notranslate">
						<td>Turn on translation management? &nbsp;<a href="#TB_inline?width=200&height=300&inlineId=translation-management-description" title="What is Translation Management?" class="thickbox">Learn more</a><div id="translation-management-description" style="display:none"><p>Translation management is an interface that allows you to manage specific words and phrases on your website.  The interface is linked directly to your Google Account, and no special subscriptions are necessary to setup this feature.</p><p>If translation management setting is checked, users who browse your website can hover their mouse over specific words and phrases, then send translation suggestions directly to your Google Translate account!  You can then "approve" those suggestions, and Google will automatically display them when translations are requested by your users.</p><p>Translation management requires that you insert a custom meta tag into the <code>head</code> section of your website.</p><p><a href="http://translate.google.com/manager/website/settings" target="_blank">Click here to to get the required meta tag</a></p></div></td>
						<td><?php $this->googlelanguagetranslator_manage_translations_cb(); ?></td>
					  </tr>

                      <tr class="multilanguage notranslate">
						<td>Multilanguage Page option? &nbsp;<a href="#TB_inline?width=200&height=150&inlineId=multilanguage-page-description" title="What is the Multi-Language Page Option?" class="thickbox">Learn more</a><div id="multilanguage-page-description" style="display:none"><p>If checked, Google will automatically convert text written in multiple languages, into the single language requested by your user.</p><p>In most cases, this setting is not recommended, although for certain websites it might be necessary.</p></div></td>
						<td><?php $this->googlelanguagetranslator_multilanguage_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
						<td>Google Analytics:</td>
						<td><?php $this->googlelanguagetranslator_analytics_cb(); ?></td>
					  </tr>
					  
					  <tr class="analytics notranslate">
						<td>Google Analytics ID (Ex. 'UA-11117410-2')</td>
						<td><?php $this->googlelanguagetranslator_analytics_id_cb(); ?></td>
					  </tr>
					  
					  <tr class="notranslate">
						<td>Full widget usage in pages/posts/sidebar:</td>
						<td><code>[google-translator]</code></td>
                                          </tr>
				  </table>
					  
				  <table style="border-collapse:separate" width="100%" border="0" cellspacing="8" cellpadding="0" class="form-table">
					  <tr class="notranslate">
						<td style="width:40%">Full widget usage in header/footer or page template:</td>
						<td style="width:60%"><code>&lt;?php echo do_shortcode('[google-translator]'); ?&gt;</code></td>
					  </tr>

                      <tr class="notranslate">
						<td>Single language usage in<br/>nav menu/pages/posts</td>
						<td><code>[glt language="Spanish" label="Español"]</code></td>
                      </tr>

                      <tr class="notranslate">
						<td colspan="2">
<a href="#TB_inline?width=200&height=450&inlineId=single-language-shortcode-description" title="How to place a single language in your Wordpress menu" class="thickbox">How to place a single language in your Wordpress menu</a><div id="single-language-shortcode-description" style="display:none"><p>For menu usage, you need to create a new menu, or use an existing menu, by navigating to "Appearance > Menus".</p><p>First you will need to enable "descriptions" for your menu items, which can be found in a tab labeled "Screen Options" in the upper-right area of the page.</p><p>Once descriptions are enabled, follow these steps:<br/><ol><li>Create a new menu item using "Link" as the menu item type.</li><li>Use <code style="border:none">#</code> for the URL</li><li>Enter a navigation label of your choice. This label does not appear on your website - it is meant only to help you identify the menu item.</li><li>Place the following shortcode into the "description" field, and modify it to display the language and navigation label of your choice:</p></li></ol>
<p><code>[glt language="Spanish" label="Español"]</code></p>
</div></td>
                      </tr>
					  
					  <tr class="notranslate">
						<td><?php submit_button(); ?></td>
						<td></td>
					  </tr>
			      </table>	  
            
		  </div> <!-- .postbox -->
		  </div> <!-- .metbox-holder -->
		  
		  <div class="metabox-holder" style="float:right; clear:right; width:33%">
		    <div class="postbox">
		      <h3 class="notranslate">Preview</h3>
			    <table style="width:100%">
		          <tr>
					<td style="box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; padding:15px 15px; margin:0px"><span style="color:red; font-weight:bold" class="notranslate">(New!)</span><span class="notranslate"> Drag &amp; drop flags to change their position.<br/><br/>(Note: flag order resets when flags are added/removed)</span><br/><br/><?php echo do_shortcode('[google-translator]'); ?><p class="hello"><span class="notranslate">Translated text:</span> &nbsp; <span>Hello</span></p></td>
				  </tr>
				  
				  <tr>
					<td><?php if (isset ($_POST['googlelanguagetranslator_flags_order']) ) { echo $_POST['googlelanguagetranslator_flags_order']; } ?></td>
				  </tr>
	
	
		        </table>
		    </div> <!-- .postbox -->
	      </div> <!-- .metabox-holder -->
				
		  <div id="glt_advanced_settings" class="metabox-holder notranslate" style="float: right; width: 33%;">
          <div class="postbox">
            <h3>Advanced Settings</h3>
			<div class="inside">
			    <table style="border-collapse:separate" width="100%" border="0" cellspacing="8" cellpadding="0" class="form-table">
				      <tr class="notranslate">
				        <td class="advanced">Select flag size:</td>
						<td class="advanced"><?php $this->googlelanguagetranslator_flag_size_cb(); ?></td>
                      </tr>
			    </table>
			 </div>
          </div>
	   </div>
		  
				
	   <div class="metabox-holder notranslate" style="float: right; width: 33%;">
          <div class="postbox">
            <h3>Add CSS Styles</h3>
			<div class="inside">
			  <p>You can apply any necessary CSS styles below:</p>
			      <?php $this->googlelanguagetranslator_css_cb(); ?>
			 </div>
          </div>
	   </div>
	  <?php $this->googlelanguagetranslator_flags_order_cb(); ?>
	</form>
		  
		<div class="metabox-holder notranslate" style="float: right; width: 33%;">
          <div class="postbox">
            <h3>GLT Premium 4.0.1 is Here! $15</h3>
			<div class="inside"><a href="http://www.wp-studio.net/" target="_blank"><img style="background:#444; border-radius:3px; -webkit-border-radius:3px; -moz-border-radius:3px" src="<?php echo plugins_url('google-language-translator/images/logo.png'); ?>"></a><br />
              <ul id="features" style="margin-left:15px">
                <li style="list-style:square outside"><span style="color:red; font-weight:bold">New!</span> Manual Translation Module!</li>
                <li style="list-style:square outside"><span style="color:red; font-weight:bold">New!</span> Exclude any specific area of your website from translation, directly from your settings panel</li>
	        <li style="list-style:square outside">81 Languages</li>
		<li style="list-style:square outside">jQuery-powered language switcher<br/>(No Adobe Flash required)</li>
		<li style="list-style:square outside">Add single languages to your menus/pages/posts</li>
		<li style="list-style:square outside">Show/hide images or text for each language</li>
		<li style="list-style:square outside">Language switcher loads inline with page content</li>
		<li style="list-style:square outside">Custom flag choices for English, Spanish and Portuguese</li>
		<li style="list-style:square outside">User-friendly URLs, hide or show <code>lang</code> attribute</li>
		<li style="list-style:square outside">Drag/drop flags to re-arrange their order</li>
	        <li style="list-style:square outside">Full access to our support forum</li>
	        <li style="list-style:square outside">FREE access to all future updates</li>
	      </ul>
           </div>
        </div>
      </div>	  
		  
	    <div class="metabox-holder notranslate" style="float: right; width: 33%;">
          <div class="postbox">
            <h3>Please Consider A Donation</h3>
              <div class="inside">If you like this plugin and find it useful, help keep this plugin actively developed by clicking the donate button <br /><br />
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                  <input type="hidden" name="cmd" value="_donations">
                  <input type="hidden" name="business" value="robertmyrick@hotmail.com">
                  <input type="hidden" name="lc" value="US">
                  <input type="hidden" name="item_name" value="Support Studio 88 Design and help us bring you more Wordpress goodies!  Any donation is kindly appreciated.  Thank you!">
                  <input type="hidden" name="no_note" value="0">
                  <input type="hidden" name="currency_code" value="USD">
                  <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                <br />
               <br />
             </div>
          </div>
	   </div>	  
</div> <!-- .wrap -->
<?php 
  }
}
$google_language_translator = new google_language_translator();