<?php
/**
 * Events post main metabox
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

if ( class_exists( 'Eventbrite_for_TribeEvents' ) ) {
	?>
	<style type="text/css">
		.eventBritePluginPlug {
			display: none;
		}
	</style>
	<?php
	}
?>
<div id="eventIntro">
	<div id="tribe-events-post-error" class="tribe-events-error error"></div>
	<?php do_action( 'tribe_events_post_errors', $event->ID, true ) ?>

</div>
<div id='eventDetails' class="inside eventForm">
	<?php do_action( 'tribe_events_detail_top', $event->ID, true ) ?>
	<?php wp_nonce_field( Tribe__Events__Main::POSTTYPE, 'ecp_nonce' ); ?>
	<?php do_action( 'tribe_events_eventform_top', $event->ID ); ?>
	<table cellspacing="0" cellpadding="0" id="EventInfo">
		<tr>
			<td colspan="2" class="tribe_sectionheader">
				<div class="tribe_sectionheader" style="">
					<h4><?php _e( 'Time &amp; Date', 'tribe-events-calendar' ); ?></h4></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="eventtable">
					<tr id="recurrence-changed-row">
						<td colspan='2'><?php printf( __( 'You have changed the recurrence rules of this %1$s.  Saving the %1$s will update all future %2$s.  If you did not mean to change all %2$s, then please refresh the page.', 'tribe-events-calendar' ), strtolower( $events_label_singular ), strtolower( $events_label_plural ) ); ?></td>
					</tr>
					<tr>
						<td><?php printf( __( 'All Day %s:', 'tribe-events-calendar' ), $events_label_singular ); ?></td>
						<td>
							<input tabindex="<?php tribe_events_tab_index(); ?>" type="checkbox" id="allDayCheckbox" name="EventAllDay" value="yes" <?php echo $isEventAllDay; ?> />
						</td>
					</tr>
					<tr>
						<td style="width:175px;"><?php _e( 'Start Date &amp; Time:', 'tribe-events-calendar' ); ?></td>
						<td id="tribe-event-datepickers" data-startofweek="<?php echo get_option( 'start_of_week' ); ?>">
							<input autocomplete="off" tabindex="<?php tribe_events_tab_index(); ?>" type="text" class="tribe-datepicker" name="EventStartDate" id="EventStartDate" value="<?php echo esc_attr( $EventStartDate ) ?>" />
							<span class="helper-text hide-if-js"><?php _e( 'YYYY-MM-DD', 'tribe-events-calendar' ) ?></span>
				<span class="timeofdayoptions">
					@
					<select tabindex="<?php tribe_events_tab_index(); ?>" name="EventStartHour">
						<?php echo $startHourOptions; ?>
					</select>
					<select tabindex="<?php tribe_events_tab_index(); ?>" name="EventStartMinute">
						<?php echo $startMinuteOptions; ?>
					</select>
					<?php if ( ! Tribe__Events__View_Helpers::is_24hr_format() ) : ?>
						<select tabindex="<?php tribe_events_tab_index(); ?>" name="EventStartMeridian">
							<?php echo $startMeridianOptions; ?>
						</select>
					<?php endif; ?>
				</span>
						</td>
					</tr>
					<tr>
						<td><?php _e( 'End Date &amp; Time:', 'tribe-events-calendar' ); ?></td>
						<td>
							<input autocomplete="off" type="text" class="tribe-datepicker" name="EventEndDate" id="EventEndDate" value="<?php echo esc_attr( $EventEndDate ); ?>" />
							<span class="helper-text hide-if-js"><?php _e( 'YYYY-MM-DD', 'tribe-events-calendar' ) ?></span>
				<span class="timeofdayoptions">
					@
					<select class="tribeEventsInput" tabindex="<?php tribe_events_tab_index(); ?>" name="EventEndHour">
						<?php echo $endHourOptions; ?>
					</select>
					<select tabindex="<?php tribe_events_tab_index(); ?>" name="EventEndMinute">
						<?php echo $endMinuteOptions; ?>
					</select>
					<?php if ( ! Tribe__Events__View_Helpers::is_24hr_format() ) : ?>
						<select tabindex="<?php tribe_events_tab_index(); ?>" name="EventEndMeridian">
							<?php echo $endMeridianOptions; ?>
						</select>
					<?php endif; ?>
				</span>
						</td>
					</tr>
					<?php do_action( 'tribe_events_date_display', $event->ID, true ) ?>
				</table>
			</td>
		</tr>
	</table>
	<table id="event_venue" class="eventtable">
		<tr>
			<td colspan="2" class="tribe_sectionheader">
				<h4><?php _e( 'Location', 'tribe-events-calendar' ); ?></h4></td>
		</tr>
		<?php do_action( 'tribe_venue_table_top', $event->ID ) ?>
		<?php include( $tribe->pluginPath . 'src/admin-views/venue-meta-box.php' ); ?>
	</table>
	<?php do_action( 'tribe_after_location_details', $event->ID ); ?>
	<table id="event_organizer" class="eventtable">
		<tr>
			<td colspan="2" class="tribe_sectionheader">
				<h4><?php echo tribe_get_organizer_label_singular(); ?></h4></td>
		</tr>
		<?php do_action( 'tribe_organizer_table_top', $event->ID ) ?>
		<?php include( $tribe->pluginPath . 'src/admin-views/organizer-meta-box.php' ); ?>
	</table>

	<table id="event_url" class="eventtable">
		<tr>
			<td colspan="2" class="tribe_sectionheader">
				<h4><?php printf( __( '%s Website', 'tribe-events-calendar' ), $events_label_singular ); ?></h4></td>
		</tr>
		<tr>
			<td style="width:172px;"><?php _e( 'URL:', 'tribe-events-calendar' ); ?></td>
			<td>
				<input tabindex="<?php tribe_events_tab_index(); ?>" type='text' id='EventURL' name='EventURL' size='25' value='<?php echo ( isset( $_EventURL ) ) ? esc_attr( $_EventURL ) : ''; ?>' placeholder='example.com' />
			</td>
		</tr>
		<?php do_action( 'tribe_events_url_table', $event->ID, true ) ?>
	</table>

	<?php do_action( 'tribe_events_details_table_bottom', $event->ID, true ) ?>

	<table id="event_cost" class="eventtable">
		<?php if ( tribe_events_admin_show_cost_field() ) : ?>
			<tr>
				<td colspan="2" class="tribe_sectionheader">
					<h4><?php printf( __( '%s Cost', 'tribe-events-calendar' ), $events_label_singular ); ?></h4></td>
			</tr>
			<tr>
				<td><?php _e( 'Currency Symbol:', 'tribe-events-calendar' ); ?></td>
				<td>
					<input tabindex="<?php tribe_events_tab_index(); ?>" type='text' id='EventCurrencySymbol' name='EventCurrencySymbol' size='2' value='<?php echo ( isset( $_EventCurrencySymbol ) ) ? esc_attr( $_EventCurrencySymbol ) : tribe_get_option( 'defaultCurrencySymbol', '$' ); ?>' />
					<select tabindex="<?php tribe_events_tab_index(); ?>" id="EventCurrencyPosition" name="EventCurrencyPosition">
						<?php
						if ( isset( $_EventCurrencyPosition ) && 'suffix' === $_EventCurrencyPosition ) {
							$suffix = true;
						} elseif ( isset( $_EventCurrencyPosition ) && 'prefix' === $_EventCurrencyPosition ) {
							$suffix = false;
						} elseif ( true === tribe_get_option( 'reverseCurrencyPosition', false ) ) {
							$suffix = true;
						} else {
							$suffix = false;
						}
						?>
						<option value="prefix"> <?php _ex( 'Before cost', 'Currency symbol position', 'tribe-events-calendar' ) ?> </option>
						<option value="suffix"<?php if ( $suffix ) {
							echo ' selected="selected"';
						} ?>><?php _ex( 'After cost', 'Currency symbol position', 'tribe-events-calendar' ) ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Cost:', 'tribe-events-calendar' ); ?></td>
				<td>
					<input tabindex="<?php tribe_events_tab_index(); ?>" type='text' id='EventCost' name='EventCost' size='6' value='<?php echo ( isset( $_EventCost ) ) ? esc_attr( $_EventCost ) : ''; ?>' />
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<small><?php printf( __( 'Enter a 0 for %s that are free or leave blank to hide the field.', 'tribe-events-calendar' ), strtolower( $events_label_plural ) ); ?></small>
				</td>
			</tr>
		<?php endif; ?>
		<?php do_action( 'tribe_events_cost_table', $event->ID, true ) ?>
	</table>

</div>
<?php do_action( 'tribe_events_above_donate', $event->ID, true ) ?>
<?php do_action( 'tribe_events_details_bottom', $event->ID, true ) ?>
