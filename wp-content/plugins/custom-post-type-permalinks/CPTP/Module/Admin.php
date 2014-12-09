<?php



/**
 *
 * Admin Page.
 *
 * @package Custom_Post_Type_Permalinks
 * @since 0.9.4
 *
 * */

class CPTP_Module_Admin extends CPTP_Module {

	public function add_hook() {
		add_action( 'admin_init', array( $this,'settings_api_init'), 30 );
		add_action( 'admin_enqueue_scripts', array( $this,'enqueue_css_js') );
		add_action( 'admin_footer', array( $this,'pointer_js') );
	}

	/**
	 *
	 * Setting Init
	 * @since 0.7
	 *
	 */
	public function settings_api_init() {
		add_settings_section('cptp_setting_section',
			__("Permalink Setting for custom post type",'cptp'),
			array( $this,'setting_section_callback_function'),
			'permalink'
		);

		$post_types = CPTP_Util::get_post_types();
		foreach ($post_types as $post_type):
			if(isset($_POST['submit']) and isset($_POST['_wp_http_referer'])){
				if( strpos($_POST['_wp_http_referer'],'options-permalink.php') !== FALSE ) {

					$structure = trim(esc_attr($_POST[$post_type.'_structure']));#get setting

					#default permalink structure
					if( !$structure )
						$structure = CPTP_DEFAULT_PERMALINK;

					$structure = str_replace('//','/','/'.$structure);# first "/"

					#last "/"
					$lastString = substr(trim(esc_attr($_POST['permalink_structure'])),-1);
					$structure = rtrim($structure,'/');

					if ( $lastString == '/')
						$structure = $structure.'/';

					update_option($post_type.'_structure', $structure );
				}
			}

			add_settings_field($post_type.'_structure',
				$post_type,
				array( $this,'setting_structure_callback_function'),
				'permalink',
				'cptp_setting_section',
				$post_type.'_structure'
			);

			register_setting('permalink',$post_type.'_structure');
		endforeach;

		add_settings_field(
			'no_taxonomy_structure',
			__("Use custom permalink of custom taxonomy archive.",'cptp'),
			array( $this,'setting_no_tax_structure_callback_function'),
			'permalink',
			'cptp_setting_section'
		);

		register_setting('permalink','no_taxonomy_structure');

		if(isset($_POST['submit']) && isset($_POST['_wp_http_referer']) && strpos($_POST['_wp_http_referer'],'options-permalink.php') !== false ) {

			if(!isset($_POST['no_taxonomy_structure'])){
				$set = true;
			}else {
				$set = false;
			}
			update_option('no_taxonomy_structure', $set);
		}
	}

	public function setting_section_callback_function() {
		?>
			<p><?php _e("Setting permalinks of custom post type.",'cptp');?><br />
			<?php _e("The tags you can use is WordPress Structure Tags and '%\"custom_taxonomy_slug\"%'. (e.g. %actors%)",'cptp');?><br />
			<?php _e("%\"custom_taxonomy_slug\"% is replaced the taxonomy's term.'.",'cptp');?></p>

			<p><?php _e("Presence of the trailing '/' is unified into a standard permalink structure setting.",'cptp');?>
			<p><?php _e("If <code>has_archive</code> is true, add permalinks for custom post type archive.",'cptp');?>
			<?php _e("If you don't entered permalink structure, permalink is configured /%postname%/'.",'cptp');?>
			</p>
		<?php
	}

	public function setting_structure_callback_function(  $option  ) {
		$post_type = str_replace('_structure',"" ,$option);
		$pt_object = get_post_type_object($post_type);
		$slug = $pt_object->rewrite['slug'];
		$with_front = $pt_object->rewrite['with_front'];

		$value = get_option($option);
		if( !$value )
			$value = CPTP_DEFAULT_PERMALINK;

		global $wp_rewrite;
		$front = substr( $wp_rewrite->front, 1 );
		if( $front and $with_front ) {
			$slug = $front.$slug;
		}

		echo '<p><code>'.home_url().'/'.$slug.'</code> <input name="'.$option.'" id="'.$option.'" type="text" class="regular-text code" value="' . $value .'" /></p>';
		echo '<p>has_archive: <code>';
		echo $pt_object->has_archive ? "true" : "false";
		echo '</code> / ';
		echo 'with_front: <code>';
		echo $pt_object->rewrite['with_front'] ? "true" : "false";
		echo '</code></p>';

	}

	public function setting_no_tax_structure_callback_function(){
		echo '<input name="no_taxonomy_structure" id="no_taxonomy_structure" type="checkbox" value="1" class="code" ' . checked( false, get_option('no_taxonomy_structure'),false) . ' /> ';
		$txt = __("If you check,The custom taxonomy's permalinks is <code>%s/post_type/taxonomy/term</code>.","cptp");
		printf($txt , home_url());
	}



	/**
	 *
	 * enqueue CSS and JS
	 * @since 0.8.5
	 *
	 */
	public function enqueue_css_js() {
		wp_enqueue_style('wp-pointer');
		wp_enqueue_script('wp-pointer');
	}



	/**
	 *
	 * add js for pointer
	 * @since 0.8.5
	 */
	public function pointer_js() {
		if(!is_network_admin()) {
			$dismissed = explode(',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ));
			if(array_search('cptp_pointer0871', $dismissed) === false){
				$content = __("<h3>Custom Post Type Permalinks</h3><p>From <a href='options-permalink.php'>Permalinks</a>, set a custom permalink for each post type.</p>", "cptp");
			?>
				<script type="text/javascript">
				jQuery(function($) {

					$("#menu-settings .wp-has-submenu").pointer({
						content: "<?php echo $content;?>",
						position: {"edge":"left","align":"center"},
						close: function() {
							$.post('admin-ajax.php', {
								action:'dismiss-wp-pointer',
								pointer: 'cptp_pointer0871'
							})

						}
					}).pointer("open");
				});
				</script>
			<?php
			}
		}
	}
}

