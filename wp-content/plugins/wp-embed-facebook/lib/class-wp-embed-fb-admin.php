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
		wp_enqueue_style( 'wpemfb-default', WP_Embed_FB_Plugin::get_url() . 'templates/default/default.css', array(), false );
		wp_enqueue_style( 'wpemfb-classic', WP_Embed_FB_Plugin::get_url() . 'templates/classic/classic.css', array(), false );
		wp_enqueue_style( 'wpemfb-lightbox', WP_Embed_FB_Plugin::get_url() . 'lib/lightbox2/css/lightbox.css', array(), false );
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
		if ( WP_Embed_FB_Plugin::get_option( 'close_warning2' ) == 'false' ) :
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
		array_unshift( $links, '<a href="' . admin_url( "options-general.php?page=embedfacebook" ) . '">' . __( "Settings" ) . '</a>' );

		return $links;
	}

	/**
	 * Add template editor style to the embeds.
	 */
	static function admin_init() {
		$theme = WP_Embed_FB_Plugin::get_option( 'theme' );
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
			<h3><?php echo $title ?></h3>
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
	 * @param array $atts Embed attributes
	 * TODO add $help = null
	 */
	static function field( $type, $name = '', $label = '', $args = array(), $atts = array() ) {
		$options    = WP_Embed_FB_Plugin::get_option();
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
					<th<?php echo ( $name == 'video_download' ) ? ' style="width: 60%;"' : '' ?>><label
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
						<!--suppress HtmlFormInputWithoutLabel -->
						<select name="<?php echo $name ?>" <?php echo $attsString ?>>
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
						<!--suppress HtmlFormInputWithoutLabel -->
						<input id="<?php echo $name ?>"
						       type="<?php echo $type ?>"
						       name="<?php echo $name ?>" <?php echo isset( $args['required'] ) ? 'required' : '' ?>
						       value="<?php echo $options[ $name ] ?>" <?php echo $attsString ?>/>
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
	static function savedata() {
		if ( isset( $_POST['app_secret'], $_POST['app_id'] ) ) {
			$options = WP_Embed_FB_Plugin::get_option();
			foreach ( $options as $option => $value ) {
				if ( $value == 'true' || $value == 'false' ) {
					if ( isset( $_POST[ $option ] ) ) {
						$options[ $option ] = 'true';
					} else {
						$options[ $option ] = 'false';
					}
				} else {
					if ( isset( $_POST[ $option ] ) ) {
						$options[ $option ] = sanitize_text_field( $_POST[ $option ] );
					}
				}
			}
			WP_Embed_FB_Plugin::set_options( $options );
		}
	}

	/**
	 * Renders the wp-admin settings page
	 */
	static function wpemfb_page() {
		if ( isset( $_POST['save-data'] ) && wp_verify_nonce( $_POST['save-data'], 'W7ziLKoLoj' ) ) {
			self::savedata();
		}
		if ( isset( $_POST['restore-data'] ) && wp_verify_nonce( $_POST['restore-data'], 'W7ziLKoLojka' ) ) {
			WP_Embed_FB_Plugin::set_options( WP_Embed_FB_Plugin::get_defaults() );
		}
		?>
		<style>
			input[type="text"], input[type="search"], input[type="password"], input[type="email"], input[type="number"], tr, tbody, table, select {
				width: 100%;
			}

			th {
				min-width: 40%;
			}
		</style>
		<div class="wrap">
			<h2>WP Embed Facebook</h2>

			<div class="welcome-panel">
				<div class="welcome-panel-content">
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column settings-col">
							<form id="config-form" action="#" method="post">
								<?php wp_nonce_field( 'W7ziLKoLoj', 'save-data' ); ?>
								<?php
								$has_app = WP_Embed_FB_Plugin::has_fb_app();
								$tabs    = array(
									__( 'General', 'wp-embed-facebook' ),
									__( 'Custom Embeds', 'wp-embed-facebook' ),
									__( 'Geeky Stuff', 'wp-embed-facebook' ),
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
								</h2>
								<section class="sections">
									<?php
									self::section( __( 'For all embeds', 'wp-embed-facebook' ) );
									self::field( 'number', 'max_width', __( 'Maximum width in pixels', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
									self::field( 'select', 'sdk_lang', __( 'Like Buttons Language', 'wp-embed-facebook' ), WP_Embed_FB_Plugin::get_fb_locales() );
									self::section();
									?>

									<?php
									self::section( __( 'Video Social Plugin Settings', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'video_download', sprintf( __( '%sShow download option under video', 'wp-embed-facebook' ), '<img style="display:block;float:left;padding-right:5px;" src="' . WP_Embed_FB_Plugin::get_url() . 'lib/admin/ic_image_settings.png">' ) );
									self::field( 'checkbox', 'video_as_post', __( 'Embed Video as Post', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( 'Page Social Plugin Settings', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'page_show_faces', __( "Show Friend's Faces", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'page_small_header', __( 'Use Small Header', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'page_hide_cover', __( 'Hide Cover Photo', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'page_show_posts', __( 'Show Page Posts', 'wp-embed-facebook' ) );
									self::field( 'number', 'page_height', __( 'Maximum height in pixels', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
									self::section();
									if ( ! $has_app ) :
										?>
										<p>
											<?php _e( 'By default you can only embed public pages, videos, photos and posts.', 'wp-embed-facebook' ) ?>
											<br><?php _e( 'To embed albums, events, profiles and video as HTML5 you will need a Facebook App', 'wp-embed-facebook' ) ?>
										</p>
										<?php
									endif;
									?>
									<p><a href="https://developers.facebook.com/apps"
									      target="_blank"><?php _e( 'Create or view your Facebook Apps', 'wp-embed-facebook' ) ?></a>
									</p>
									<?php
									self::section( __( 'Facebook credentials', 'wp-embed-facebook' ) );
									self::field( 'text', 'app_id', __( 'App ID', 'wp-embed-facebook' ) );
									self::field( 'text', 'app_secret', __( 'App Secret', 'wp-embed-facebook' ) );
									self::section();
									?>

								</section>
								<section class="sections">
									<?php
									echo ! $has_app ? '<div style="display: none">' : '';
									self::section( __( "General", 'wp-embed-facebook' ) );
									/**
									 * Filter available templates
									 * @since 2.0.2
									 */
									$templates = apply_filters( 'wpemfb_admin_theme', array(
										'default' => 'Default',
										'classic' => 'Classic'
									) );
									self::field( 'select', 'theme', 'Template', $templates );
									self::section();
									self::section( __( "Albums", 'wp-embed-facebook' ) );
									self::field( 'number', 'max_photos', __( 'Number of Photos', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
									self::section();
									self::section( __( "Events", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'ev_local_tz', __( 'Use WordPress timezone string to calculate the date', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Pages", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'raw_page', __( 'Enable by default', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'show_like', __( 'Show like button', 'wp-embed-facebook' ) );
									self::field( 'number', 'max_posts', __( 'Number of posts', 'wp-embed-facebook' ), array(), array( 'min' => '0' ) );
									self::section();
									self::section( __( "Photos", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'raw_photo', __( 'Enable by default', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Posts", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'raw_post', __( 'Enable by default', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Videos", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'raw_video', __( 'Enable by default', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'video_ratio', __( 'Force 16:9 ratio', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( "Profiles", 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'show_follow', __( 'Show follow button', 'wp-embed-facebook' ) );
									self::section();
									echo ! $has_app ? '</div><br><p>You need a facebook app to use custom embeds</p>' : '';
									?>

								</section>
								<?php do_action( 'wpemfb_options' ); ?>
								<section class="sections">
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
									self::section( __( 'Enqueue styles and scripts', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'enq_when_needed', __( 'Only when there is an embed present', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'enq_fbjs', __( 'Facebook SDK', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'enqueue_style', __( 'Template Styles', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'enq_wpemfb', __( 'Adaptive social plugins script', 'wp-embed-facebook' ) );
									self::field( 'checkbox', 'enq_lightbox', __( 'Lightbox Script', 'wp-embed-facebook' ) );
									self::section();
									self::section( __( 'Other Options' ) );
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
									<input type="hidden" name="close_warning2" value="true">
								</section>
								<input type="submit" name="submit" class="button button-primary alignright"
								       value="<?php _e( 'Save', 'wp-embed-facebook' ) ?>"/>

								<p><?php sprintf( __( 'All options can be overwritten using the [facebook url ] <a href="%s">shortcode</a>', 'wp-embed-facebook' ), 'http://www.wpembedfb.com/shortcode-attributes-and-examples/' ) ?>
								</p>
								<br>
								<br>
							</form>
							<form method="post"
							      onsubmit="return confirm('<?php _e( 'Restore default values?', 'wp-embed-facebook' ) ?>');">
								<input type="submit" name="restore" class="button alignleft"
								       value="<?php _e( 'Restore defaults', 'wp-embed-facebook' ) ?>"/>
								<br>
								<?php wp_nonce_field( 'W7ziLKoLojka', 'restore-data' ); ?>
								<br>
							</form>
						</div>
						<div class="welcome-panel-column welcome-panel-last">
							<?php ob_start(); ?>
							<h1><?php _e( 'Premium Extension Available', 'wp-embed-facebook' ) ?></h1>
							<br>

							<div class="features-list">
								<p><?php _e( 'Shortcodes for embedding a full event or page.', 'wp-embed-facebook' ) ?></p>

								<p><?php _e( 'Default event template shows admins and address.', 'wp-embed-facebook' ) ?></p>

								<p><?php _e( 'Albums with more that 100 photos.', 'wp-embed-facebook' ) ?></p>

								<p><?php _e( 'One Year Premium Support', 'wp-embed-facebook' ) ?></p>

								<p>
									<a class="button button-red"
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
							<h4><?php _e( "Keep this plugin's core free and accessible to all.", 'wp-embed-facebook' ) ?></h4>

							<p>
								<strong>
									<a href="http://wordpress.org/plugins/wp-embed-facebook"><?php _e( "Rate it", 'wp-embed-facebook' ) ?>
										<br>
										<span style="color: gold;"> &#9733;&#9733;&#9733;&#9733;&#9733; </span>
									</a>
								</strong>
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