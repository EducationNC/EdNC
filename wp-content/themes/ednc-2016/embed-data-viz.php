<?php
/**
 * Contains the flash-cards embed template.
 *
 * When a post is embedded in an iframe, this file is used to
 * create the output.
 *
 * @package WordPress
 * @subpackage oEmbed
 * @since 4.4.0
 */

if ( ! headers_sent() ) {
	header( 'X-WP-embed: true' );
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<title><?php echo wp_get_document_title(); ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

	<?php get_template_part('templates/components/social-meta', 'data-viz'); ?>

	<link href="//fonts.googleapis.com/css?family=Lato:300,300italic,400,400italic,700,700italic|Merriweather:300,300italic,400,400italic,700,700italic|Open+Sans+Condensed:300" rel="stylesheet" type="text/css" />
	<?php
	/**
	 * Print scripts or data in the embed template <head> tag.
	 *
	 * @since 4.4.0
	 */
	do_action( 'embed_head' );
	?>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-57754133-4', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body <?php body_class('embed'); ?>>
<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		?>
		<div class="wp-embed-card">
			<?php // get_template_part('templates/layouts/block', 'post'); ?>
		</div>
		<div <?php post_class( 'wp-embed' ); ?>>
			<div class="container-fluid">
	      <?php get_template_part('templates/layouts/content-embed', 'data-viz'); ?>
			</div>

			<nav class="navbar navbar-default">
			  <div class="container-fluid">
			    <div class="navbar-header">
						<?php
						$site_title = sprintf(
							'<a class="navbar-brand" href="%s" target="_blank"><img src="%s" srcset="%s 2x" width="64" height="64" alt="%s" /></a>',
							esc_url( get_the_permalink() ),
							esc_url( get_site_icon_url( 64, admin_url( 'images/w-logo-blue.png' ) ) ),
							esc_url( get_site_icon_url( 128, admin_url( 'images/w-logo-blue.png' ) ) ),
							esc_html( get_bloginfo( 'name' ) )
						);

						/**
						 * Filter the site title HTML in the embed footer.
						 *
						 * @since 4.4.0
						 *
						 * @param string $site_title The site title HTML.
						 */
						echo apply_filters( 'embed_site_title_html', $site_title );
						?>
			    </div>

					<div class="navbar-right">
						<?php // get_template_part('templates/components/social-share', 'embed'); ?>
						<!-- <a href="<?php the_permalink(); ?>" target="_blank">Open <span class="icon-external-link"></span></a> -->
					</div>
			  </div>
			</nav>

			<!-- <div class="wp-embed-footer">
				<div class="wp-embed-site-title">
					<?php
					$site_title = sprintf(
						'<a class="navbar-brand" href="%s" target="_blank"><img src="%s" srcset="%s 2x" width="32" height="32" alt="" class="wp-embed-site-icon"/><span>%s</span></a>',
						esc_url( home_url() ),
						esc_url( get_site_icon_url( 32, admin_url( 'images/w-logo-blue.png' ) ) ),
						esc_url( get_site_icon_url( 64, admin_url( 'images/w-logo-blue.png' ) ) ),
						esc_html( get_bloginfo( 'name' ) )
					);

					/**
					 * Filter the site title HTML in the embed footer.
					 *
					 * @since 4.4.0
					 *
					 * @param string $site_title The site title HTML.
					 */
					echo apply_filters( 'embed_site_title_html', $site_title );
					?>
				</div>

				<div class="wp-embed-meta">
					<a href="<?php the_permalink(); ?>" target="_blank">Open <span class="icon-external-link"></span></a>
				</div>
			</div> -->
		</div>
		<?php
	endwhile;
else :
	?>
	<div class="wp-embed">
		<p class="wp-embed-heading"><?php _e( 'Oops! That embed can&#8217;t be found.' ); ?></p>

		<div class="wp-embed-excerpt">
			<p>
				<?php
				printf(
					/* translators: %s: a link to the embedded site */
					__( 'It looks like nothing was found at this location. Maybe try visiting %s directly?' ),
					'<strong><a href="' . esc_url( home_url() ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></strong>'
				);
				?>
			</p>
		</div>

		<div class="wp-embed-footer">
			<div class="wp-embed-site-title">
				<?php
				$site_title = sprintf(
					'<a href="%s" target="_top"><img src="%s" srcset="%s 2x" width="32" height="32" alt="" class="wp-embed-site-icon"/><span>%s</span></a>',
					esc_url( home_url() ),
					esc_url( get_site_icon_url( 32, admin_url( 'images/w-logo-blue.png' ) ) ),
					esc_url( get_site_icon_url( 64, admin_url( 'images/w-logo-blue.png' ) ) ),
					esc_html( get_bloginfo( 'name' ) )
				);

				/** This filter is documented in wp-includes/embed-template.php */
				echo apply_filters( 'embed_site_title_html', $site_title );
				?>
			</div>
		</div>
	</div>
	<?php
endif;

/**
 * Print scripts or data before the closing body tag in the embed template.
 *
 * @since 4.4.0
 */
do_action( 'embed_footer' );
?>
</body>
</html>
