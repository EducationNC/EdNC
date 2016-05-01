<?php
class  WP_Embed_FB {
	/**
	 * @var string Width of the current embed
	 */
	static $width = null;
	/**
	 * @var bool|null if the current embed is in raw format
	 */
	static $raw = null;
	/**
	 * @var string Theme to use on the embed
	 */
	static $theme = null;
	/**
	 * @var int|null Number of posts on the page embed
	 */
	static $num_posts = null;
	/**
	 * @var int|null Number of photos on album
	 */
	static $num_photos = null;
	/**
	 * @var null|Sigami_Facebook
	 */
	private static $fbsdk = null;
	static function get_theme(){
		if(self::$theme){
			return self::$theme;
		} else {
			self::$theme = get_option('wpemfb_theme');
			return self::$theme;
		}
	}
	static function is_raw($type){
		if(self::$raw !== null){
			return self::$raw;
		} else {
			switch($type){
				case 'page':
				case 'photo':
				case 'post':
				case 'video':
					self::$raw = (get_option('wpemfb_raw_'.$type) == 'false') ? false : true;
					break;
				default:
					self::$raw = true;
					break;
			}
			return self::$raw;
		}
	}
	static function get_fbsdk(){
		if( self::$fbsdk && self::$fbsdk instanceof Sigami_Facebook){
			if( get_option('wpemfb_force_app_token','true') == 'true' )
				self::$fbsdk->setAccessToken(get_option('wpemfb_app_id').'|'.get_option('wpemfb_app_secret'));
			return self::$fbsdk;
		} else {
			if(!class_exists('FacebookApiException'))
				require_once "base_facebook.php";
			require_once "class-sigami-facebook.php";
			$config = array();
			$config['appId'] = get_option('wpemfb_app_id','');
			$config['secret'] = get_option('wpemfb_app_secret','');
			//$config['fileUpload'] = false; // optional
			self::$fbsdk = new Sigami_Facebook($config);
			if( get_option('wpemfb_force_app_token','true') == 'true' )
				self::$fbsdk->setAccessToken(get_option('wpemfb_app_id').'|'.get_option('wpemfb_app_secret'));
			return self::$fbsdk;
		}
	}
	/**
	 * Run
	 * @param array $match[2]=url without ' https://www.facebook.com/ '
	 * @param string $url Original url
	 * @param array $atts Attributes for the embed
	 * @return string Embedded content
	 *
	 */
	static function fb_embed($match, $url=null, $atts=null ){
		$juice = $match[2];
		self::set_atts($atts);
		$type_and_id = apply_filters('wpemfb_type_id', self::get_type_and_id($juice,$url), $juice, $url) ;
		if(is_string($type_and_id))
			return $type_and_id;		
		$return = self::print_embed($type_and_id['fb_id'],$type_and_id['type'],$juice);
		self::clear_atts();
		return $return;

	}
	/**
	 * @param string $juice facebook url without https://www.facebook.com
	 * @param string $original Original url to return.
	 *
	 * @return array|string
	 */
	static function get_type_and_id($juice,$original){
		$has_fb_app = WP_Embed_FB_Plugin::has_fb_app();
		if($has_fb_app)
			$fbsdk = self::get_fbsdk();
		$fb_id = null;
		$type = null;
		if (($pos = strpos($juice, "?")) !== FALSE) {
			$vars = array();
			parse_str(parse_url($juice, PHP_URL_QUERY), $vars);
			if(isset($vars['fbid']))
				$fb_id = $vars['fbid'];
			if(isset($vars['id']))
				$fb_id = $vars['id'];
			if(isset($vars['v'])) {
				$fb_id = $vars['v'];
				$type = 'video';
			}
			if(isset($vars['set'])){
				$setArray = explode('.', $vars['set']);
				$fb_id  = $setArray[1];
				$type = 'album';
			}
			$juice = substr($juice, 0, $pos);
		}
		$juiceArray = explode('/',trim($juice,'/'));
		if(!$fb_id){
			$fb_id = end($juiceArray);
		}
		if(in_array('posts',$juiceArray)){
			$type = 'post';
			if($has_fb_app && (self::is_raw('post')) ){
				try{
					/** @noinspection PhpUndefinedVariableInspection */
					$data = $fbsdk->api('/'.$juiceArray[0].'?fields=id');
					$fb_id = $data['id'].'_'.$fb_id;
				} catch(FacebookApiException $e){
					$res = '<p><a href="'.$original.'" target="_blank" rel="nofollow">'.$original.'</a>';
					if(is_super_admin()){
						$error = $e->getResult();
						if(isset($error['error']['code']) && ($error['error']['code'] == '803') ){
							$res .= '<br><span style="color: #4a0e13">'.__('Error: Try embedding this post as a social plugin (only visible to super admins)','wp-embed-facebook').'</span>';
						} else {
							if(isset($error['error']['code']))
								$res .= '<br><span style="color: #4a0e13">'.__('Code').':&nbsp;'.$error['error']['code'].'&nbsp;in type</span>';
							$res .= '<br><span style="color: #4a0e13">'.__('Error').':&nbsp;'.$error['error']['message'].' (only visible to super admins)</span>';
						}
					}
					$res .= '</p>';
					return $res;
				}
			}
		}elseif(in_array('photos',$juiceArray) || in_array('photo.php',$juiceArray) ){
			$type = 'photo';
		}elseif(in_array('events',$juiceArray)){
			$type = 'event';
		}elseif(in_array('videos',$juiceArray)){
			$type = 'video';
		}
		/**
		 * Filter the embed type.
		 *
		 * @since 1.8
		 *
		 * @param string $type the embed type.
		 * @param array $clean url parts of the request.
		 */
		$type = apply_filters('wpemfb_embed_type', $type, $juiceArray);
		if(!$type){
			if($has_fb_app){
				try{
					/** @noinspection PhpUndefinedVariableInspection */
					$metadata = $fbsdk->api('/'.$fb_id.'?metadata=1');
					$type = $metadata['metadata']['type'];
				} catch(FacebookApiException $e){
					$res = '<p><a href="https://www.facebook.com/'.$juice.'" target="_blank" rel="nofollow">https://www.facebook.com/'.$juice.'</a>';
					if(is_super_admin()){
						$error = $e->getResult();
						if(isset($error['error']['code']))
							$res .= '<br><span style="color: #4a0e13">'.__('Code').':&nbsp;'.$error['error']['code'].'&nbsp;'.$type.'</span>';
						$res .= '<br><span style="color: #4a0e13">'.__('Error').':&nbsp;'.$error['error']['message'].' (only visible to super admins)</span>';
					}
					$res .= '</p>';
					return $res;
				}
			} else {
				$type = 'page';
			}
		}
		if(!is_numeric($fb_id)){
			$fb_id_array = explode('-',$fb_id);
			if( !empty($fb_id_array) && is_int(end($fb_id_array)) ){
				$fb_id = end($fb_id_array);
			}
			$fb_id = str_replace(':0','',$fb_id);
		}
		$fb_id = apply_filters('wpemfb_embed_fb_id',$fb_id, $juiceArray);
		return array('type'=>$type,'fb_id'=>$fb_id);
	}
	static function print_embed($fb_id,$type,$juice){
		if(!self::is_raw($type)){
			$fb_data = array('social_plugin'=>true, 'link'=>$juice, 'type'=>$type);
			$template_name = 'social-plugin';
		} else {
			switch($type){
				case 'page' :
					$fb_data = self::fb_api_get($fb_id,$juice,$type);
					if(!self::valid_fb_data($fb_data))
						return $fb_data;
					if(isset($fb_data['is_community_page']) && $fb_data['is_community_page'] == "1"){
						$template_name = 'com-page';
					} else {
						$default = 'page';
						/**
						 * Add a new template for a specific facebook category
						 *
						 * for example a Museum create the new template at your-theme/plugins/wp-embed-facebook/museum.php
						 * then on functions.php of your theme
						 *
						 * add_filter( 'wpemfb_category_template', 'your_function', 10, 2 );
						 *
						 * function your_function( $default, $category ) {
						 *      if($category == 'Museum/art gallery')
						 *          return 'museum';
						 *      else
						 *      return $default;
						 * }
						 *
						 * @updated 2.0
						 * @since 1.0
						 *
						 * @param string $default file full path
						 * @param array $fb_data['category']  data from facebook
						 */
						$template_name = apply_filters('wpemfb_category_template', $default, $fb_data['category']);
					}
					break;
				case 'photo' :
				case 'post':
				case 'video' :
				case 'album' :
				case 'event' :
					$fb_data = self::fb_api_get($fb_id,$juice,$type);
					$template_name = $type;
					break;
				case 'user' :
					$fb_data = self::fb_api_get($fb_id,$juice,'profile');
					$template_name = 'profile';
					break;
				default :
					$fb_data = self::fb_api_get($fb_id,$juice,$type);
					$template_name = $type;
					break;
			}
		}

		if(!self::valid_fb_data($fb_data))
			return print_r($fb_data,true);
		$template = self::locate_template($template_name);
		//get default variables to use on templates
		/** @noinspection PhpUnusedLocalVariableInspection */
		$width = !empty(self::$width) ? self::$width : get_option('wpemfb_max_width');
		/** @noinspection PhpUnusedLocalVariableInspection */
		$prop = get_option('wpemfb_proportions');
		ob_start();
		//show embed post on admin
		if(is_admin() && isset($fb_data['social_plugin'])) : ?>
			<script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/<?php echo get_option('wpemfb_sdk_lang','en_US'); ?>/sdk.js#xfbml=1&version=<?php echo get_option('wpemfb_sdk_version','v2.6') ?>";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
		<?php endif;
		/**
		 * Change the file to include on a certain embed.
		 *
		 * @since 1.8
		 *
		 * @param string $template file full path
		 * @param array $fb_data data from facebook
		 */
		$template = apply_filters('wpemfb_template',$template,$fb_data,$type);
		/** @noinspection PhpIncludeInspection */
		include( $template );
		return preg_replace('/^\s+|\n|\r|\s+$/m', '', ob_get_clean());
	}
	static function valid_fb_data($fb_data){
		if(is_array($fb_data) && ( isset($fb_data['id']) || isset($fb_data['social_plugin']) ) )
			return true;
		return false;
	}
	/**
	 * get data from fb using WP_Embed_FB::$fbsdk->api('/'.$fb_id) :)
	 *
	 * @param int $fb_id Facebook id
	 * @param string $url Facebook url
	 * @type string type of embed
	 * @return array|string
	 */
	static function fb_api_get($fb_id, $url, $type=""){
		if(WP_Embed_FB_Plugin::has_fb_app()){
			$fbsdk = self::get_fbsdk();
			try {
				switch($type){
					case 'album' :
						self::$num_photos = is_int(self::$num_photos) ? self::$num_photos : get_option("wpemfb_max_photos");
						$api_string = $fb_id.'?fields=name,id,from,description,count,photos.fields(name,picture,source,id).limit('.self::$num_photos.')';
						break;
					case 'page' :
						$num_posts = is_int(self::$num_posts) ? self::$num_posts : get_option("wpemfb_max_posts");
						$api_string = $fb_id.'?fields=name,picture,is_community_page,link,id,cover,category,website,likes,genre';
						if(intval($num_posts) > 0)
							$api_string .= ',posts.limit('.$num_posts.'){id,full_picture,type,via,source,parent_id,call_to_action,story,place,child_attachments,icon,created_time,message,description,caption,name,shares,link,picture,object_id,likes.limit(1).summary(true),comments.limit(1).summary(true)}';
						break;
					case 'video' :
						$api_string = $fb_id.'?fields=id,source,picture,from';
						break;
					case 'photo' :
						$api_string = $fb_id.'?fields=id,source,link,likes.limit(1).summary(true),comments.limit(1).summary(true)';
						break;
					case 'event' :
						$api_string = $fb_id.'?fields=id,name,start_time,end_time,owner,place,picture,timezone,cover';
						break;
					case 'post' :
						$api_string = $fb_id.'?fields=from{id,name,likes,link},id,full_picture,type,via,source,parent_id,call_to_action,story,place,child_attachments,icon,created_time,message,description,caption,name,shares,link,picture,object_id,likes.limit(1).summary(true),comments.limit(1).summary(true)';
						break;
					default :
						$api_string = $fb_id;
						break;
				}
				//echo "type";
				/**
				 * Filter the fist fbsdk query
				 *
				 * @since 1.9
				 *
				 * @param string $api_string The fb api request string according to type
				 * @param string $fb_id The id of the object being requested.
				 * @param string $type The detected type of embed
				 *
				 */
				$fb_data = $fbsdk->api('v2.5/'.apply_filters('wpemfb_api_string',$api_string,$fb_id,$type));
				$api_string2 = '';

				/**
				 * Filter the second fbsdk query if necessary
				 *
				 * @since 1.9
				 *
				 * @param string $api_string2 The second request string empty if not necessary
				 * @param array $fb_data The result from the first query
				 * @param string $type The detected type of embed
				 *
				 */
				$api_string2 = apply_filters('wpemfb_2nd_api_string',$api_string2,$fb_data,$type);

				if(!empty($api_string2)){
					$extra_data = $fbsdk->api('/v2.5/'.$api_string2);
					$fb_data = array_merge($fb_data,$extra_data);
				}
				/**
				 * Filter all data received from facebook.
				 *
				 * @since 1.9
				 *
				 * @param array $fb_data the final result
				 * @param string $type The detected type of embed
				 */
				$fb_data = apply_filters('wpemfb_fb_data',$fb_data,$type);

			} catch(FacebookApiException $e) {
				$fb_data = '<p><a href="https://www.facebook.com/'.$url.'" target="_blank" rel="nofollow">https://www.facebook.com/'.$url.'</a>';
				if(is_super_admin()){
					$error = $e->getResult();
					$fb_data .= '<br><span style="color: #4a0e13">'.__('Error').':&nbsp;'.$error['error']['message'].' (only visible to super admins)</span>';
				}
				$fb_data .= '</p>';
			}
		} else {
			$fb_data = '<p><a href="https://www.facebook.com/'.$url.'" target="_blank" rel="nofollow">https://www.facebook.com/'.$url.'</a>';
			if(is_super_admin()){
				$fb_data .= '<br><span style="color: #4a0e13">'.__('A Facebook App is needed to embed this url (only visible to super admins)','wp-embed-facebook').'</span>';
			}
			$fb_data .= '</p>';
		}

		return $fb_data;
	}
	/**
	 * Locate the template inside plugin or theme
	 *
	 * @param string $template_name Template file name
	 * @return string Template location
	 */
	static function locate_template($template_name){
		$theme = self::get_theme();
		$located = locate_template(array('plugins/wp-embed-facebook/'.$theme.'/'.$template_name.'.php'));
		$file = 'templates/'.$theme.'/'.$template_name.'.php';
		if(empty($located)){
			$located =  WP_Embed_FB_Plugin::get_path().$file;
		}
		return $located;
	}
	/**
	 * If a user has a lot of websites registered on fb this function will only link to the first one
	 * @param string $urls separated by spaces
	 * @return string first url
	 */
	static function getwebsite($urls){
		$url = explode(' ',trim($urls));
		return strpos('http://',$url[0]) == false ? 'http://'.$url[0] : $url[0];
	}
	/**
	 * Shortcode function
	 * [facebook='url' width='600' raw='true' social_plugin='true' posts='2'   ] width is optional
	 * @param array $atts [0]=>url ['width']=>embed width ['raw']=>for videos and photos
	 * @return string
	 */
	static function shortcode($atts){
		if(!empty($atts) && isset($atts[0])){
			$clean = trim($atts[0],'=');
			if(is_int($clean)){
				$juice = $clean;
				$clean = "https://www.facebook.com/$juice";
			} else {
				if( strpos($clean,'facebook.com') === false )
					return "<p>".__("This is not a valid facebook url","wp-embed-facebook")." $clean </p>";
				$juice = str_replace(array('https:','http:','//facebook.com/','//m.facebook.com/','//facebook.com/','//www.facebook.com/'),'',$clean);
			}
			$embed = self::fb_embed(array('https','://www.facebook.com/',$juice),$clean,$atts);
			return $embed;
		}
		return '';
	}
	static function embed_register_handler($match, /** @noinspection PhpUnusedParameterInspection */
		$attr=null, $url=null, $atts=null){
		return self::fb_embed($match, $url, $atts);
	}
	static function make_clickable($text) {
		return wpautop(self::rel_nofollow(make_clickable($text)));
	}
	static function rel_nofollow( $text ) {
		$text = stripslashes($text);
		return preg_replace_callback('|<a (.+?)>|i', array(__CLASS__,'nofollow_callback'), $text);
	}
	static function nofollow_callback( $matches ) {
		$text = $matches[1];
		$text = str_replace(array(' rel="nofollow"', " rel='nofollow'"), '', $text);
		return "<a $text rel=\"nofollow\">";
	}
	static function set_atts($atts){
		if(isset($atts['width'])){
			self::$width = $atts['width'];
		}
		if(isset($atts['raw'])){
			if($atts['raw'] == 'true'){
				self::$raw = true;
			} else  {
				self::$raw = false;
			}
		}
		if(isset($atts['social_plugin'])){
			if($atts['social_plugin'] == 'true'){
				self::$raw = false;
			} else  {
				self::$raw = true;
			}
		}
		if(isset($atts['theme'])){
			wp_enqueue_style('wpemfb-'.$atts['theme'], WP_Embed_FB_Plugin::get_url().'templates/'.$atts['theme'].'/'.$atts['theme'].'.css',array(),false);
			self::$theme = $atts['theme'];
		}
		if(isset($atts['posts'])){
			self::$num_posts = intval($atts['posts']);
		}
		if(isset($atts['photos'])){
			self::$num_photos = intval($atts['photos']);
		}
	}
	static function clear_atts(){
		self::$width = self::$raw = self::$num_posts = self::$theme = self::$num_photos = null;
	}
	static function fb_root($content){
		return '<div id="fb-root"></div>'.PHP_EOL.$content;
	}
}

