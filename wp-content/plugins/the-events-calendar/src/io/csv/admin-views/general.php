<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require 'header.php';
?>
<div id="modern-tribe-info">
	<h2><?php esc_html_e( 'The Events Calendar: Import', 'the-events-calendar' ); ?></h2>

	<h3><?php esc_html_e( 'Instructions', 'the-events-calendar' ); ?></h3>
	<p>
		<?php printf( __( 'To import events, first select a %sDefault Import Event Status%s below to assign to your imported events.', 'the-events-calendar' ), '<strong>', '</strong>' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'Once your setting is saved, move to the applicable Import tab to select import specific criteria.', 'the-events-calendar' ); ?>
	</p>
</div>

<div class="tribe-settings-form">
	<form method="POST">
		<div class="tribe-settings-form-wrap">
			<h3><?php esc_html_e( 'Import Settings', 'the-events-calendar' ); ?></h3>
			<p>
				<?php
				esc_html_e( 'Default imported event status:', 'the-events-calendar' );

				$import_statuses = array(
					'publish' => __( 'Published', 'the-events-calendar' ),
					'pending' => __( 'Pending', 'the-events-calendar' ),
					'draft'   => __( 'Draft', 'the-events-calendar' ),
				);
				?>
				<select name="imported_post_status">
					<?php
					foreach ( $import_statuses as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, Tribe__Events__Main::getOption( 'imported_post_status', 'publish' ) ) . '>
						' . $value . '
					</option>';
					}
					?>
				</select>
			</p>

			<?php wp_nonce_field( 'tribe-import-general-settings', 'tribe-import-general-settings' ); ?>
			<p>
				<input type="submit" name="tribe-events-importexport-general-settings-submit" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'the-events-calendar' ); ?>"/>
			</p>
		</div>
	</form>
</div>

<?php
require 'footer.php';
