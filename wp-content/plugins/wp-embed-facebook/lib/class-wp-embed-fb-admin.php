<?php
class WP_Embed_FB_Admin {
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
			wp_enqueue_style( 'wpemfb-admin-css', WP_Embed_FB_Plugin::get_url() . 'lib/admin/admin.css' );
		}
	}
	static function in_admin_footer(){
		global $hook_suffix;
		ob_start();
		if($hook_suffix == 'settings_page_embedfacebook') :
			?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
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
		if(get_option('wpemfb_close_warning') == 'false') :
			?>
			<script type="text/javascript">
				jQuery(document).on( 'click', '.wpemfb_warning .notice-dismiss', function() {
					jQuery.post(ajaxurl, { action: 'close_warning' });
				});
			</script>
			<?php
		endif;
		echo ob_get_clean();
	}
	/**
	 * Add template editor style to the embeds.
	 */
	static function admin_init() {
		$theme = get_option( 'wpemfb_theme' );
		add_editor_style( WP_Embed_FB_Plugin::get_url() . '/templates/' . $theme . '/' . $theme . '.css' );
	}

	/**
	 * Render form sections
	 *
	 * @param string $title
	 */
	static function section( $title = '' ) {
		if ( ! empty( $title ) ) :
			?>
			<h4><?php echo $title ?></h4>
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
	 * @param string $type Type of input field
	 * @param string $name Input name
	 * @param string $label Input Label
	 * @param array $args
	 */
	static function field( $type, $name = '', $label = '', $args = array() ) {
		switch ( $type ) {
			case 'checkbox':
				$checked = ( get_option( $name ) === 'true' ) ? 'checked' : '';
				ob_start();
				?>
				<tr valign="middle">
					<th><?php echo $label ?></th>
					<td>
						<input type="checkbox" id="<?php echo $name ?>"
						       name="<?php echo $name ?>" <?php echo $checked ?> />
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
			case 'select' :
				$option = get_option( $name );
				ob_start();
				?>
				<tr valign="middle">
					<th><?php echo $label ?></th>
					<td>
						<select name="<?php echo $name ?>">
							<?php foreach ( $args as $value => $name ) : ?>
								<option
									value="<?php echo $value ?>" <?php echo $option == $value ? 'selected' : '' ?>><?php echo $name ?></option>
							<?php endforeach; ?>
						</select>
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
						<p><?php echo $name ?></p>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
			default:
				ob_start();
				?>
				<tr valign="middle">
					<th><?php echo $label ?></th>
					<td>
						<input id="<?php echo $name ?>"
						       type="<?php echo $type ?>"
						       name="<?php echo $name ?>" <?php echo isset( $args['required'] ) ? 'required' : '' ?>
						       value="<?php echo get_option( $name ) ?>" size="38"/>
					</td>
				</tr>
				<?php
				ob_end_flush();
				break;
		}
	}

	/**
	 * Saves $_POST values to the database
	 *
	 * @param $option string option name
	 * @param $type string  direct or true/false
	 */
	static function save_option( $option, $type ) {
		if ( $type == 'direct' ) {
			if ( isset( $_POST[ $option ] ) ) {
				update_option( $option, sanitize_text_field( $_POST[ $option ] ) );
			}
		} elseif ( $type == 'bool' ) {
			if ( isset( $_POST[ $option ] ) ) {
				update_option( $option, 'true' );
			} else {
				update_option( $option, 'false' );
			}
		}
	}

	/**
	 * Gets $_POST variables and saves them to the database
	 */
	static function savedata() {
		if ( isset( $_POST['wpemfb_app_secret'], $_POST['wpemfb_app_id'] ) ) {
			$options = WP_Embed_FB_Plugin::getdefaults();

			foreach ( $options as $option => $value ) {
				if ( $value == 'true' || $value == 'false' ) {
					$type = 'bool';
				} else {
					$type = 'direct';
				}
				self::save_option( $option, $type );
			}
			if ( isset( $_POST['wpemfb_max_width'] ) && is_int( $_POST['wpemfb_max_width'] ) ) {
				$prop = get_option( 'wpemfb_proportions' ) * $_POST['wpemfb_max_width'];
				update_option( 'wpemfb_height', $prop );//TODO height is deprecated
			}
		}
		/**
		 * Save extra options, requires coordination with 'wpemfb_options' action
		 *
		 * @since 1.8
		 *
		 */
		do_action( 'wpemfb_admin_save_data' );
	}

	/**
	 * Renders the wp-admin settings page
	 */
	static function wpemfb_page() {
		if ( isset( $_POST['save-data'] ) && wp_verify_nonce( $_POST['save-data'], 'wp-embed-facebook' ) ) {
			self::savedata();
		}
		?>
		<div class="wrap">
			<h2>WP Embed Facebook</h2>

			<div class="welcome-panel">
				<div class="welcome-panel-content">
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column settings-col">
							<form id="config-form" action="#" method="post">
								<?php wp_nonce_field( 'wp-embed-facebook', 'save-data' ); ?>
								<?php
								$tabs = array(
									__( 'General', 'wp-embed-facebook' ),
									__( 'Custom Embeds', 'wp-embed-facebook' ),
									__( 'Geeky Stuff', 'wp-embed-facebook' ),
								);
								$tabs = apply_filters( 'wpemfb_tabs', $tabs );
								?>
								<h2 class="nav-tab-wrapper">
									<?php
									foreach ( $tabs as $tab ) {
										$class = $tabs[0] == $tab ? "nav-tab-active" : "";
										echo "<a class='nav-tab $class' href='#'>$tab</a>";
									}
									?>
								</h2>
								<section class="sections">
									<?php
									self::section( __( 'For all embeds', 'wp-embed-facebook' ) );
									self::field( 'number', 'wpemfb_max_width', __( 'Maximum width in pixels', 'wp-embed-facebook' ) );
									self::field( 'select', 'wpemfb_sdk_lang', __( 'Like Buttons Language', 'wp-embed-facebook' ), WP_Embed_FB_Plugin::get_fb_locales() );
									self::section();
									?>
									<?php
									self::section( __( 'Social Plugin Defaults', 'wp-embed-facebook' ) );
									self::field( 'string', '', '---- ' . __( 'Page Plugin', 'wp-embed-facebook' ) . ' ----' );
									self::field( 'number', 'wpemfb_page_height', __( 'Maximum height in pixels', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_page_show_faces', __( "Show Friend's Faces", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_page_small_header', __( 'Use Small Header', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_page_hide_cover', __( 'Hide Cover Photo', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_page_show_posts', __( 'Show Page Posts', 'wp-embed-facebook' ) );
									self::field( 'string', '', '---- ' . __( 'Videos', 'wp-embed-facebook' ) . ' ----' );
									self::field( 'checkbox', 'wpemfb_video_as_post', __( 'Embed Video as Post', 'wp-embed-facebook' ) );
									self::section();
									if ( ! WP_Embed_FB_Plugin::has_fb_app() ) :
										?>
										<p>
											<?php _e( 'By default you can only embed public pages, videos, photos and posts.', 'wp-embed-facebook' ) ?>
											<br><?php _e( 'To embed embed albums, events, profiles and raw video you will need a Facebook App.', 'wp-embed-facebook' ) ?>
										</p>
										<?php
									endif;
									self::section( __( 'Facebook credentials', 'wp-embed-facebook' ) );
									self::field( 'text', 'wpemfb_app_id', __( 'App ID', 'wp-embed-facebook' ), array( 'required' => 'true' ) );
									self::field( 'text', 'wpemfb_app_secret', __( 'App Secret', 'wp-embed-facebook' ), array( 'required' => 'true' ) );
									self::section();
									?>
									<p><a href="https://developers.facebook.com/apps"
									      target="_blank"><?php _e( 'Your Facebook Apps', 'wp-embed-facebook' ) ?></a>
									</p>
								</section>
								<section class="sections">
									<?php
									self::section( __( "General", 'wp-embed-facebook' ) );
									/**
									 * Filter available templates
									 * @since 2.0.2
									 */
									$templates = apply_filters( 'wpemfb_admin_theme', array(
										'default' => 'Default',
										'classic' => 'Classic'
									) );
									self::field( 'select', 'wpemfb_theme', 'Template to use', $templates );
									self::section();
									self::section( __( "Albums", 'wp-embed-facebook' ) );
									self::field( 'number', 'wpemfb_max_photos', __( 'Number of Photos', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Events", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_ev_local_tz', __( 'Use WordPress timezone string to calculate the date', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Pages", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_raw_page', __( 'Use custom embed by default', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_show_like', __( 'Show like button', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_show_posts', __( 'Show latest posts', 'wp-embed-facebook' ) );
									self::field( 'number', 'wpemfb_max_posts', __( 'Number of posts', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Photo", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_raw_photo', __( 'Use custom embed by default', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Posts", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_raw_post', __( 'Use custom embed by default', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Videos", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_raw_video', __( 'Use custom embed by default', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_video_ratio', __( 'Force 16:9 ratio', 'wp-embed-facebook' ) );
									//TODO update class and delete 'wpemfb_raw_video_fb' option forever
									self::section();
									self::section( __( "Profiles", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_show_follow', __( 'Show follow button', 'wp-embed-facebook' ) );
									self::section();
									?>

								</section>
								<?php do_action( 'wpemfb_options' ); ?>
								<section class="sections">
									<?php
									self::section( __( 'Advanced ', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_enqueue_style', __( 'Enqueue Styles', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_fb_root', __( 'Add fb-root on top of content', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_enq_lightbox', __( 'Enqueue Lightbox script', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_enq_wpemfb', __( 'Enqueue WPEmbedFB script', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'wpemfb_enq_fbjs', __( 'Enqueue Facebook SDK', 'wp-embed-facebook' ) );
									self::section();
									?>
								</section>
								<input type="submit" name="submit" class="button button-primary alignright"
								       value="<?php _e( 'Save', 'wp-embed-facebook' ) ?>"/>

								<p><?php _e( 'All options can be overwritten using the [facebook=url ]', 'wp-embed-facebook' ) ?>
									<a href="http://www.wpembedfb.com/documentation">shortcode</a></p>
								<br>
								<br>
							</form>
						</div>
						<div class="welcome-panel-column welcome-panel-last">
							<?php ob_start(); ?>
							<h3><?php _e( 'Premium Extension Available', 'wp-embed-facebook' ) ?></h3>
							<br>

							<div class="features-list">
								<p><?php _e( 'Embed a Facebook Page with all its details.', 'wp-embed-facebook' ) ?></p>

								<p><?php _e( 'Embed an Event with all its details.', 'wp-embed-facebook' ) ?></p>

								<p><?php _e( 'One Year Premium Support', 'wp-embed-facebook' ) ?></p>

								<p>
									<?php _e( 'Plus new features cooking', 'wp-embed-facebook' ) ?>
									<br>
									<small>
										<?php _e( 'Upcoming events widget, events with maps...', 'wp-embed-facebook' ) ?>
									</small>
								</p>
							</div>
							<h2>
								<?php _e( 'Only $6.99 USD', 'wp-embed-facebook' ) ?>
								<br>
								<small
									style="font-size: 12px; color: rgb(152, 152, 152)"><?php _e( 'Price will change very soon', 'wp-embed-facebook' ) ?></small>
							</h2>
							<br>
							<a class="button button-red"
							   href="http://www.wpembedfb.com/premium"><?php _e( 'Check it out', 'wp-embed-facebook' ) ?></a>
							<br>
							<br>
							<hr>
							<h4><?php _e( "Keep this plugin's core free and accessible to all.", 'wp-embed-facebook' ) ?></h4>

							<p><strong><a href="http://wordpress.org/plugins/wp-embed-facebook">Rate it <span
											style="color: gold;">&#9733;&#9733;&#9733;&#9733;&#9733;</span></strong></a>
							</p>

							<p><strong><a
										href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8Q85GT3Q8Q26"><?php _e( 'Donate', 'wp-embed-facebook' ) ?></a></strong>
							</p>
							<hr>
							<p><a href="http://www.wpembedfb.com">
									<small><?php _e( 'More information', 'wp-embed-facebook' ) ?></small>
								</a></p>
							<?php echo apply_filters( 'wpemfb_admin', ob_get_clean() ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}