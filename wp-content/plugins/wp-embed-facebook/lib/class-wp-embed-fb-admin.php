<?php

class WP_Embed_FB_Admin extends WP_Embed_FB_Plugin {

	static function hooks() {
		//Donate or review notice
		add_action( 'admin_notices', __CLASS__ . '::admin_notices' );
		add_action( 'wp_ajax_wpemfb_close_warning', __CLASS__ . '::wpemfb_close_warning' );
		add_action( 'wp_ajax_wpemfb_video_down', __CLASS__ . '::wpemfb_video_down' );

		//settings page
		add_action( 'admin_menu', __CLASS__ . '::add_page' );
		add_action( 'in_admin_footer', __CLASS__ . '::in_admin_footer' );

		//editor style
		add_action( 'admin_init', __CLASS__ . '::admin_init' );

		//register styles and scripts
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
	}

	static function admin_notices() {
		if ( ( self::get_option( 'close_warning2' ) == 'false' ) ) :
			?>
			<div class="notice wpemfb_warning is-dismissible">
				<h2>WP Embed Facebook</h2>

				<p>Hey! The last step.</p>

				<p><img style="position:relative; top: 5px;" height="20px" width="auto"
				        src="<?php echo self::url() . 'lib/admin/ic_setting.png' ?>">&nbsp;Turn on <a
						id="wef-video-down" href="<?php echo admin_url( "options-general.php?page=embedfacebook" ) ?>">Video
						Download Option</a> in settings.</p>
				<small>
					<?php
					printf( __( 'To embed albums, events, profiles and video as HTML5 you will need a <a target="_blank" href="%s">Facebook App</a>', 'wp-embed-facebook' ), 'https://developers.facebook.com/apps' )
					?>
				</small>
				<p>
					<?php
					printf( __( 'This free plugin has taken <strong>thousands of hours</strong> to develop and maintain consider making a <a target="_blank" href="%s">donation</a> or leaving a <a target="_blank" href="%s">review</a> <strong>do not let us loose faith</strong> in humanity.', 'wp-embed-facebook' ), 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26', 'https://wordpress.org/support/view/plugin-reviews/wp-embed-facebook' )
					?>
				</p>

			</div>
			<?php
		endif;
	}

	static function wpemfb_close_warning() {
		if ( current_user_can( 'manage_options' ) ) {
			$options                   = self::get_option();
			$options['close_warning2'] = 'true';
			self::set_options( $options );
		}
		die;
	}

	static function wpemfb_video_down() {
		if ( current_user_can( 'manage_options' ) ) {
			$options                   = self::get_option();
			$options['close_warning2'] = 'true';
			$options['video_download'] = 'true';
			self::set_options( $options );
		}
		die;
	}

	/**
	 * Add WP Embed Facebook page to Settings
	 */
	static function add_page() {
		add_options_page( 'EmbedFacebook', 'Embed Facebook', 'manage_options', 'embedfacebook', array(
			__CLASS__,
			'wpemfb_page'
		) );
	}

	/**
	 * Enqueue WP Embed Facebook js and css to admin page
	 *
	 * @param string $hook_suffix current page
	 */
	static function admin_enqueue_scripts( $hook_suffix ) {
		if ( $hook_suffix == 'settings_page_embedfacebook' ) {
			wp_enqueue_style( 'wpemfb-admin-css', self::url() . 'lib/admin/admin.css' );
		}
		wp_enqueue_style( 'wpemfb-default', self::url() . 'templates/default/default.css', array(), false );
		wp_enqueue_style( 'wpemfb-classic', self::url() . 'templates/classic/classic.css', array(), false );
		wp_enqueue_style( 'wpemfb-lightbox', self::url() . 'lib/lightbox2/css/lightbox.css', array(), false );
	}

	static function in_admin_footer() {
		global $hook_suffix;
		ob_start();
		if ( $hook_suffix == 'settings_page_embedfacebook' ) :
			?>
			<script type="text/javascript">
				jQuery(document).ready(function () {
					var sections = jQuery('section');
					sections.first().show();
					jQuery(".nav-tab-wrapper a").on('click', function (event) {
						event.preventDefault();
						sections.hide();
						jQuery.each(jQuery(".nav-tab-wrapper a"), function (key, value) {
							jQuery(value).removeClass("nav-tab-active");
						});
						sections.eq(jQuery(this).index()).show();
						jQuery(this).addClass('nav-tab-active')
					});
				});
			</script>
			<?php
		endif;
		if ( self::get_option( 'close_warning2' ) == 'false' ) :
			?>
			<script type="text/javascript">
				jQuery(document).on('click', '.wpemfb_warning .notice-dismiss', function () {
					jQuery.post(ajaxurl, {action: 'wpemfb_close_warning'});
				});
				jQuery(document).on('click', '#wef-video-down', function (e) {
					e.preventDefault();
					jQuery.post(ajaxurl, {action: 'wpemfb_video_down'}, function () {
						window.location = "<?php echo admin_url("options-general.php?page=embedfacebook"); ?>"
					});

				});
			</script>
			<?php
		endif;
		echo ob_get_clean();
	}

	static function add_action_link( $links ) {
		array_unshift( $links, '<a title="WP Embed Facebook Settings" href="' . admin_url( "options-general.php?page=embedfacebook" ) . '">' . __( "Settings" ) . '</a>' );

		return $links;
	}

	/**
	 * Add template editor style to the embeds.
	 */
	static function admin_init() {
		add_editor_style( self::url() . '/templates/default/default.css' );
		add_editor_style( self::url() . '/templates/classic/classic.css' );
	}

	/**
	 * Render form sections
	 *
	 * @param string|bool $title
	 */
	static function section( $title = '' ) {
		if ( $title ) :
			if ( is_string( $title ) )
				echo "<h3>$title</h3>"
			?>
			<table>
			<tbody>
			<?php
		else :
			?>
			</tbody>
			</table>
			<?php
		endif;
	}

	/**
	 * Render form fields
	 *
	 * @param string $type  Type of input field
	 * @param string $name  Input name
	 * @param string $label Input Label
	 * @param array  $args
	 * @param array  $atts  Embed attributes
	 */
	static function field( $type, $name = '', $label = '', $args = array(), $atts = array() ) {
		/** @since 2.1.1 */
		$options    = apply_filters('wpemfb_field_options',self::get_option());
		$attsString = '';
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $att => $val ) {
				$attsString .= $att . '="' . $val . '" ';
			}
		}
		switch ( $type ) {
			case 'checkbox':
				$checked = ( $options[ $name ] === 'true' ) ? 'checked' : '';
				ob_start();
				?>
				<tr valign="middle">
					<th<?php //echo ( $name == 'video_download' ) ? ' style="width: 60%;"' : ''
					?>><label
							for="<?php echo $name ?>"><?php echo $label ?></label></th>
					<td>
						<input type="checkbox" id="<?php echo $name ?>"
						       name="<?php echo $name ?>" <?php echo $checked ?> <?php echo $attsString ?>/>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
			case 'select' :
				$option = $options[ $name ];
				ob_start();
				?>
				<tr valign="middle">
					<th><label for="<?php echo $name ?>"><?php echo $label ?></label></th>
					<td>
						<select name="<?php echo $name ?>" <?php echo $attsString ?>>
							<?php
								foreach ( $args as $value => $name ) :
									if ( is_numeric( $value ) ) {
										$value = $name;
									}
							?>
								<option
									value="<?php echo $value ?>" <?php echo $option == $value ? 'selected' : '' ?>><?php echo $name ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
			case 'number' :
				ob_start();
				?>
				<tr valign="middle">
					<th><label for="<?php echo $name ?>"><?php echo $label ?></label></th>
					<td>
						<input id="<?php echo $name ?>"
						       type="<?php echo $type ?>"
						       name="<?php echo $name ?>" <?php echo isset( $args['required'] ) ? 'required' : '' ?>
						       value="<?php echo $options[ $name ] ?>" <?php echo $attsString ?>/>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
			case 'string' :
				ob_start();
				?>
				<tr valign="middle">
					<th><?php echo $label ?></th>
					<td>
						<?php echo $name ?>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
			default:
				ob_start();
				?>
				<tr valign="middle">
					<th><label for="<?php echo $name ?>"><?php echo $label ?></label></th>
					<td>
						<input id="<?php echo $name ?>"
						       type="<?php echo $type ?>"
						       name="<?php echo $name ?>" <?php echo isset( $args['required'] ) ? 'required' : '' ?>
						       value="<?php echo esc_attr($options[ $name ]) ?>" <?php echo $attsString ?>
						       class="regular-text"/>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
		}
	}

	/**
	 * Gets $_POST variables and saves them to the database
	 */
	private static function save_data() {
		$options = self::get_option();
		foreach ( $options as $option => $value ) {
			if ( $value == 'true' || $value == 'false' ) {
				if ( isset( $_POST[ $option ] ) ) {
					$options[ $option ] = 'true';
				} else {
					$options[ $option ] = 'false';
				}
			} else {
				if ( isset( $_POST[ $option ] ) ) {
					$sanitized = sanitize_text_field( $_POST[ $option ] );
					$options[ $option ] = stripslashes($sanitized);
				}
			}
		}
		/** @since 2.1.1 */
		do_action('wpemfb_save_data');

		self::set_options( $options );
	}

	/**
	 * Renders the wp-admin settings page
	 */
	static function wpemfb_page() {
		if ( isset( $_POST['save-data'] ) && wp_verify_nonce( $_POST['save-data'], 'W7ziLKoLoj' ) ) {
			self::save_data();
		}
		if ( isset( $_POST['restore-data'] ) && wp_verify_nonce( $_POST['restore-data'], 'W7ziLKoLojka' ) ) {
			self::set_options( self::get_defaults() );
		}
		?>
		<div class="wrap">
			<h2>WP Embed Facebook</h2>

			<div class="wef-content">
				<form id="config-form" action="#" method="post">
					<?php wp_nonce_field( 'W7ziLKoLoj', 'save-data' ); ?>
					<?php
					$has_app = self::has_fb_app();
					$tabs    = array(
						__( 'Magic Embeds', 'wp-embed-facebook' ),
						__( 'Social Plugins', 'wp-embed-facebook' ),
						__( 'Custom Embeds', 'wp-embed-facebook' ),
						__( 'Lightbox', 'wp-embed-facebook' ),
						__( 'Advanced', 'wp-embed-facebook' ),
					);
					$tabs    = apply_filters( 'wpemfb_tabs', $tabs );
					?>
					<h2 class="nav-tab-wrapper">
						<?php
						foreach ( $tabs as $tab ) {
							$class = $tabs[0] == $tab ? "nav-tab-active" : "";
							echo "<a class='nav-tab $class' href='#'>$tab</a>";
						}
						?>
					</h2><br>
					<section id="magic_embeds" class="sections">
						<?php
						self::section( true );
						self::field( 'string',
							sprintf( __( 'Auto embeds understand the url you are entering and return a social plugin or a custom embed. <br>They can be activated by <a href="https://codex.wordpress.org/Embeds" title="WordPress Embeds" target="_blank">pasting the url on the editor</a> or by the [facebook url ] <a href="%s" title="[facebook] Shortcode attributes and examples" target="_blank">shortcode</a>.', 'wp-embed-facebook' ), 'http://www.wpembedfb.com/shortcode-attributes-and-examples/' ),
							'<h3>' . __( 'Auto Embeds', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'auto_embed_active', __( 'Auto embed url\'s on editor ', 'wp-embed-facebook' ) );
						self::field( 'number', 'max_width', __( 'Maximum width in pixels', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
						self::field( 'checkbox', 'video_as_post', __( 'Embed video as post', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'video_download', sprintf( __( '%sDownload link under video', 'wp-embed-facebook' ), '<img style="display:block;float:left;padding-right:5px;" width="25px" height="auto" src="' . self::url() . 'lib/admin/ic_image_settings.png">' ) );

						self::field( 'string', sprintf( __( 'The quote plugin lets people select text on your page and add it to their share.<br><a href="%s" target="_blank" title="WP Embed Facebook">Demo</a>', 'wp-embed-facebook' ), 'http://www.wpembedfb.com/demo-site/social-plugins/quote-plugin/' ), '<h3>' . __( 'Quote Plugin', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'quote_plugin_active', __( 'Active', 'wp-embed-facebook' ) );
						self::field( 'text', 'quote_post_types', __( 'Post types', 'wp-embed-facebook' ) );
						self::field( 'string', __( 'Coma separated post types i.e. post,page,attachment', 'wp-embed-facebook' ) );

						self::field( 'string', 'Replace WP comments for FB comments on selected post types', '<h3>' . __( 'Comments', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'auto_comments_active', __( 'Active', 'wp-embed-facebook' ) );
						self::field( 'text', 'auto_comments_post_types', __( 'Post types', 'wp-embed-facebook' ) );
						self::field( 'string', __( 'Coma separated post types i.e. post,page,attachment', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'comments_count_active', __( 'Sync comment count', 'wp-embed-facebook' ) );
						self::field( 'string', '<small>Comments count get stored on _wef_comments_count post meta.<br>You can refresh the comment count by updating the post</small>' );

						self::field( 'checkbox', 'comments_open_graph', __( 'Add open graph meta', 'wp-embed-facebook' ) );
						self::field( 'string', __( 'Needed to moderate comments', 'wp-embed-facebook' ) . '<br><small>' . sprintf( __( 'Disable this if you already have another plugin adding <a title="Moderation Setup Instructions" target="_blank" href="%s">the fb:app_id meta</a>', 'wp-embed-facebook' ), 'https://developers.facebook.com/docs/plugins/comments/#moderation-setup-instructions' ) . '</small>' );
						$comment_notes = __( 'To enable comments moderation setup your App ID', 'wp-embed-facebook' );
						$comment_notes .= '<br>';
						$comment_notes .= '<small>';
						$comment_notes .= sprintf( __( 'If you cant see the "Moderate comment" link above each comment you will need to <a title="Sharing Debugger" target="_blank" href="%s">scrape the url</a>', 'wp-embed-facebook' ), 'https://developers.facebook.com/tools/debug/sharing/' );
						$comment_notes .= '<br>';
						$comment_notes .= 'An automatic solution for this will be available on future releases<br>';
						$comment_notes .= '</small><br>';
						self::field( 'string', $comment_notes, __( 'Notes:', 'wp-embed-facebook' ) );

						self::field( 'string', '', '<h3>' . __( 'Facebook settings', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'select', 'sdk_lang', __( 'Social Plugins Language', 'wp-embed-facebook' ), self::get_fb_locales() );
						self::field( 'string',
							sprintf(
								__( 'Creating a Facebook app is easy view the <a href="%s" target="_blank" title="WP Embed FB documentation">step by step guide</a> or view <a href="%s" target="_blank" title="Facebook Apps">your apps</a>.'
									, 'wp-embed-facebook'
								),
								'http://www.wpembedfb.com/blog/creating-a-facebook-app-the-step-by-step-guide/',
								'https://developers.facebook.com/apps'
							),
							'' );
						self::field( 'text', 'app_id', __( 'App ID', 'wp-embed-facebook' ) );
						self::field( 'string', 'Needed for comments moderation and custom embeds' );
						self::field( 'text', 'app_secret', __( 'App Secret', 'wp-embed-facebook' ) );
						self::field( 'string', 'Needed for custom embeds' );
						//TODO auto scrape fb share using fb api on updated posts, filtered by post_type
						//						self::field( 'checkbox', 'scrape_open_graph', __( 'Scrape FB share data', 'wp-embed-facebook' ) );
						//						self::field( 'string', __( '<small>This will update the contents of the share every time you update a plublished post<br>You need a FB App for this.</small>', 'wp-embed-facebook' ), '<h4>Notes:</h4>' );

						self::section();
						?>
					</section>

					<section id="social_plugins" class="sections">
						<p>
							<?php printf( __( '<a title="Facebook Social Plugins" href="%s" rel="nofollow" target="_blank">Social plugins</a> are pieces of code that Facebook developers created for us mortals.', 'wp-embed-facebook' ), 'https://developers.facebook.com/docs/plugins/' ) ?>
							<br>
							<strong><?php _e( 'Example:', 'wp-embed-facebook' ) ?></strong>
							<br>
							<?php _e( 'Embed a like button for the curent page:', 'wp-embed-facebook' ) ?>
							<br>
							[fb_plugin like share=true layout=button_count]&nbsp;
							<?php _e( 'add debug=1 to debug the result.', 'wp-embed-facebook' ) ?>

						</p>
						<?php
						$vars = get_class_vars( 'WEF_Social_Plugins' );
						self::section( true );

						self::field( 'string', '[fb_plugin  page href=]', '<h3>' . __( 'Page plugin', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'page' ) . '</h3>' );
						self::field( 'number', 'page_width', 'width', array(), array(
							'min' => '220',
							'max' => '500'
						) );
						self::field( 'number', 'page_height', 'height', array(), array( 'min' => '70' ) );
						self::field( 'text', 'page_tabs', 'tabs' );
						self::field( 'string', __( 'Comma separated tabs i.e. timeline,events,messages' ) );
						self::field( 'checkbox', 'page_hide-cover', 'hide-cover' );
						self::field( 'checkbox', 'page_show-facepile', 'show-facepile' );
						self::field( 'checkbox', 'page_hide-cta', 'hide-cta' );
						self::field( 'checkbox', 'page_small-header', 'small-header' );
						self::field( 'checkbox', 'page_adapt-container-width', 'adapt-container-width' );

						self::field( 'string', '[fb_plugin post href=]', '<h3>' . __( 'Post plugin', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'post' ) . '</h3>' );
						self::field( 'number', 'post_width', 'width', array(), array(
							'min' => '350',
							'max' => '750'
						) );
						self::field( 'checkbox', 'post_show-text', 'show-text' );

						self::field( 'string', '[fb_plugin video href=]', '<h3>' . __( 'Video', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'video' ) . '</h3>' );
						self::field( 'checkbox', 'video_allowfullscreen', 'allowfullscreen' );
						self::field( 'checkbox', 'video_autoplay', 'autoplay' );
						self::field( 'checkbox', 'video_show-text', 'show-text' );
						self::field( 'checkbox', 'video_show-captions', 'show-captions' );
						self::field( 'number', 'video_width', 'width', array(), array( 'min' => '220' ) );

						self::field( 'string', '[fb_plugin comment href=]', '<h3>' . __( 'Single comment', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'comment' ) . '</h3>' );
						self::field( 'number', 'comment_width', 'width', array(), array( 'min' => '220' ) );
						self::field( 'checkbox', 'comment_include-parent', 'include-parent' );

						self::field( 'string', '[fb_plugin comments]<br><small>' . __( 'Activate them on all your posts on the "Magic embeds" section', 'wp-embed-facebook' ) . '</small>', '<h3>' . __( 'Comments plugin', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'comments' ) . '</h3>' );
						self::field( 'select', 'comments_colorscheme', 'colorscheme', $vars['comments']['colorscheme'] );
						self::field( 'checkbox', 'comments_mobile', 'mobile' );
						self::field( 'number', 'comments_num_posts', 'num_posts', array(), array( 'min' => '1' ) );
						self::field( 'select', 'comments_order_by', 'order_by', $vars['comments']['order_by'] );
						self::field( 'text', 'comments_width', 'width' );

						self::field( 'string', '[fb_plugin quote]<br><small>' . __( 'Activate it on all your posts on the "Magic embeds" section', 'wp-embed-facebook' ) . '</small>', '<h3>' . __( 'Quote plugin', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'quote' ) . '</h3>' );
						self::field( 'select', 'quote_layout', 'layout', $vars['quote']['layout'] );

						self::field( 'string', '[fb_plugin save]', '<h3>' . __( 'Save button', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'save' ) . '</h3>' );
						self::field( 'select', 'save_size', 'size', $vars['save']['size'] );

						self::field( 'string', '[fb_plugin like]', '<h3>' . __( 'Like button', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'like' ) . '</h3>' );
						self::field( 'select', 'like_action', 'action', $vars['like']['action'] );
						self::field( 'select', 'like_colorscheme', 'colorscheme', $vars['like']['colorscheme'] );
						self::field( 'checkbox', 'like_kid-directed-site', 'kid-directed-site' );
						self::field( 'select', 'like_layout', 'layout', $vars['like']['layout'] );
						self::field( 'checkbox', 'like_share', 'share' );
						self::field( 'checkbox', 'like_show-faces', 'show-faces' );
						self::field( 'number', 'like_width', 'width', array(), array( 'min' => '225' ) );

						self::field( 'string', '[fb_plugin send]', '<h3>' . __( 'Send button', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'send' ) . '</h3>' );
						self::field( 'select', 'send_colorscheme', 'colorscheme', $vars['send']['colorscheme'] );
						self::field( 'checkbox', 'send_kid-directed-site', 'kid-directed-site' );

						self::field( 'string', '[fb_plugin share]', '<h3>' . __( 'Share button', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'share' ) . '</h3>' );
						self::field( 'select', 'share_layout', 'layout', $vars['share']['layout'] );
						self::field( 'checkbox', 'share_mobile_iframe', 'mobile_iframe' );

						self::field( 'string', '[fb_plugin follow href=]', '<h3>' . __( 'Follow button', 'wp-embed-facebook' ). '<br>' . WEF_Social_Plugins::get_links( 'follow' ) . '</h3>' );
						self::field( 'select', 'follow_colorscheme', 'colorscheme', $vars['follow']['colorscheme'] );
						self::field( 'checkbox', 'follow_kid-directed-site', 'kid-directed-site' );
						self::field( 'select', 'follow_layout', 'layout', $vars['follow']['layout'] );
						self::field( 'checkbox', 'follow_show-faces', 'show-faces' );
						self::field( 'number', 'follow_width', 'width', array(), array(
							'min' => '225',
							'max' => '450'
						) );

						self::field( 'string', 'Make the embed smaller according to screen size', '<h3>' . __( 'Adaptive view', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'adaptive_fb_plugin', 'active' );
						self::section();

						?>
					</section>

					<section id="custom_embeds" class="sections">
						<?php if ( ! $has_app ) : ?>
						<div style="display: none">
							<?php endif; ?>
							<?php
							self::section( __( "General", 'wp-embed-facebook' ) );
							/**
							 * Filter available templates
							 *
							 * @since 2.0.2
							 */
							$templates = apply_filters( 'wpemfb_admin_theme', array(
								'default' => 'Default',
								'classic' => 'Classic'
							) );
							self::field( 'select', 'theme', 'Template', $templates );

							self::field( 'string', '', '<h3>' . __( 'Albums', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'number', 'max_photos', __( 'Number of Photos', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );

							self::field( 'string', '', '<h3>' . __( 'Events', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'checkbox', 'ev_local_tz', __( 'Use WP time to calculate the date', 'wp-embed-facebook' ) );

							self::field( 'string', '', '<h3>' . __( 'Pages', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'checkbox', 'raw_page', __( 'Use by default on "Auto Embeds"', 'wp-embed-facebook' ) );
							self::field( 'checkbox', 'show_like', __( 'Show like button', 'wp-embed-facebook' ) );
							self::field( 'number', 'max_posts', __( 'Number of posts', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );

							self::field( 'string', '', '<h3>' . __( 'Photos', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'checkbox', 'raw_photo', __( 'Use by default on "Auto Embeds"', 'wp-embed-facebook' ) );

							self::field( 'string', '', '<h3>' . __( 'Posts', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'checkbox', 'raw_post', __( 'Use by default on "Auto Embeds"', 'wp-embed-facebook' ) );

							self::field( 'string', '', '<h3>' . __( 'Videos', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'checkbox', 'raw_video', __( 'Use by default on "Auto Embeds"', 'wp-embed-facebook' ) );
							self::field( 'checkbox', 'video_ratio', __( 'Force 16:9 ratio', 'wp-embed-facebook' ) );

							self::field( 'string', '', '<h3>' . __( 'Profiles', 'wp-embed-facebook' ) . '</h3>' );
							self::field( 'checkbox', 'show_follow', __( 'Show follow button', 'wp-embed-facebook' ) );

							self::section();
							?>
							<p>
								<?php _e( 'Custom embeds can be accessed using the [facebook url] or [embed] shortcodes also by activating "Auto Embeds" on Magic Embeds section.', 'wp-embed-facebook' ) ?>
								<br>
								<strong><?php _e( 'Example:', 'wp-embed-facebook' ) ?></strong>
								<br>
								Page custom embed<br>
								[facebook https://www.facebook.com/sydneyoperahouse/ social_plugin=false posts=2]
								<br>
								<?php printf( __( '<a href="%s" title="WP Embed Facebook Shortcode" target="_blank">Read More</a>', 'wp-embed-facebook' ), 'http://www.wpembedfb.com/shortcode-attributes-and-examples/' ) ?>
							</p>
							<?php
							if ( ! $has_app ) :
							?>
						</div>
						<p>
							<?php _e( 'By default you can only embed public pages, videos, photos and posts.', 'wp-embed-facebook' ) ?>
							<br><?php _e( 'To embed albums, events, profiles and video as HTML5 you will need to setup a Facebook App on Magic Embeds section', 'wp-embed-facebook' ) ?>
						</p>
					<?php
					endif;
					?>
					</section>

					<section id="lightbox" class="sections">
						<h4>Lightbox is only active on custom embeds.</h4>
						<?php if ( ! $has_app ) : ?>
						<div style="display: none">
							<?php endif; ?>
							<?php
							self::section( __( "Lightbox Options", 'wp-embed-facebook' ) );
							self::field( 'checkbox', 'LB_showImageNumberLabel', __( 'Show Image Number Label', 'wp-embed-facebook' ) );
							self::field( 'text', 'LB_albumLabel', __( 'Album Label', 'wp-embed-facebook' ) );
							self::field( 'number', 'LB_fadeDuration', __( 'Fade Duration', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
							self::field( 'number', 'LB_resizeDuration', __( 'Resize Duration', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
							self::field( 'number', 'LB_positionFromTop', __( 'Position From Top', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
							self::field( 'number', 'LB_maxHeight', __( 'Max Height', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
							self::field( 'number', 'LB_maxWidth', __( 'Max Width', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
							self::field( 'checkbox', 'LB_alwaysShowNavOnTouchDevices', __( 'Always Show Nav On TouchDevices', 'wp-embed-facebook' ) );
							self::field( 'checkbox', 'LB_fitImagesInViewport', __( 'Fit Images In Viewport', 'wp-embed-facebook' ) );
							self::field( 'checkbox', 'LB_disableScrolling', __( 'Disable Scrolling', 'wp-embed-facebook' ) );
							self::field( 'checkbox', 'LB_wrapAround', __( 'Loop Through Album', 'wp-embed-facebook' ) );

							self::section();
							if ( ! $has_app ) :
							?>
						</div>
						<p>
							<?php _e( 'By default you can only embed public pages, videos, photos and posts.', 'wp-embed-facebook' ) ?>
							<br><?php _e( 'To embed albums, events, profiles and video as HTML5 you will need to setup a Facebook App on Magic Embeds section', 'wp-embed-facebook' ) ?>
						</p>
					<?php
					endif;
					?>
					</section>

					<?php do_action( 'wpemfb_options' ); ?>

					<section id="advanced" class="sections">
						<p>Beware altering this options without the proper knowledge could make the world disappear.</p>
						<?php
						self::section( true );
						self::field( 'string', '', '<h3>' . __( 'Enqueue styles and scripts', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'enq_when_needed', __( 'Only when there is an embed present', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'enq_fbjs', __( 'Facebook SDK', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'enqueue_style', __( 'Template Styles', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'enq_wpemfb', __( 'Adaptive social plugins script', 'wp-embed-facebook' ) );

						self::field( 'string', __('',''), '<h3>' . __( 'Lightbox', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'enq_lightbox', __( 'Enqueue script', 'wp-embed-facebook' ) );
						self::field( 'text', 'lightbox_att', __( 'Attribute', 'wp-embed-facebook' ) );

						self::field( 'string', '', '<h3>' . __( 'Other Options', 'wp-embed-facebook' ) . '</h3>' );
						self::field( 'checkbox', 'fb_root', __( 'Add fb-root on top of content', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'enq_fbjs_global', __( 'Force Facebook SDK script on all site', 'wp-embed-facebook' ) );
						self::field( 'checkbox', 'force_app_token', __( 'Force app token', 'wp-embed-facebook' ) );
						$versions = array(
							'v2.0' => '2.0',
							'v2.1' => '2.1',
							'v2.2' => '2.2',
							'v2.3' => '2.3',
							'v2.4' => '2.4',
							'v2.5' => '2.5',
							'v2.6' => '2.6',
						);
						self::field( 'select', 'sdk_version', 'Facebook SDK Version', $versions );

						self::section();
						?>
					</section>

					<input type="hidden" name="close_warning2" value="true">
					<input type="submit" name="submit" class="button button-primary"
					       value="<?php _e( 'Save all settings', 'wp-embed-facebook' ) ?>"/>
				</form>
				<br>

				<form method="post"
				      onsubmit="return confirm('<?php _e( 'Restore default values?', 'wp-embed-facebook' ) ?>');"
				      style="text-align: right">
					<input type="submit" name="restore" class="button"
					       value="<?php _e( 'Restore defaults', 'wp-embed-facebook' ) ?>"/>
					<br>
					<?php wp_nonce_field( 'W7ziLKoLojka', 'restore-data' ); ?>
					<br>
				</form>
			</div>
			<div class="wef-sidebar">
				<?php ob_start(); ?>
				<h1><?php _e( 'Premium Extension Available', 'wp-embed-facebook' ) ?></h1>
				<br>

				<div class="features-list">
					<p><?php _e( 'Shortcodes for embedding a full event or page.', 'wp-embed-facebook' ) ?></p>

					<p><?php _e( 'Default event template shows admins and address.', 'wp-embed-facebook' ) ?></p>

					<p><?php _e( 'Albums with more that 100 photos.', 'wp-embed-facebook' ) ?></p>

					<p><?php _e( 'One Year Premium Support', 'wp-embed-facebook' ) ?></p>

					<p>
						<a class="button button-red" title="Premium extension" target="_blank"
						   href="http://www.wpembedfb.com/premium"><?php _e( 'Check it out', 'wp-embed-facebook' ) ?></a>
					</p>

					<p>
						<?php _e( 'Plus new features cooking', 'wp-embed-facebook' ) ?>
						<br>
						<small>
							<?php _e( 'Embed personal data, shortcode creator, widgets, special templates for albums and pages', 'wp-embed-facebook' ) ?>
						</small>
					</p>
				</div>
				<hr>
				<h4><?php _e( "This free plugin has taken thousands of hours to maintain and develop", 'wp-embed-facebook' ) ?></h4>

				<p>
					<strong>
						<a href="http://wordpress.org/plugins/wp-embed-facebook" title="wordpress.org"
						   target="_blank"><?php _e( "Rate it", 'wp-embed-facebook' ) ?>
							<br>
							<span style="color: gold;"> &#9733;&#9733;&#9733;&#9733;&#9733; </span>
						</a>
					</strong>
				</p>

				<p><strong><a target="_blank" title="paypal"
				              href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26">👾<?php _e( 'Donate', 'wp-embed-facebook' ) ?>
							👾</a></strong>
				</p>
				<hr>
				<p><a href="http://www.wpembedfb.com" title="plugin website" target="_blank">
						<small><?php _e( 'More information', 'wp-embed-facebook' ) ?></small>
					</a></p>
				<?php echo apply_filters( 'wpemfb_admin', ob_get_clean() ); ?>

			</div>
		</div>
		<?php
	}
}
