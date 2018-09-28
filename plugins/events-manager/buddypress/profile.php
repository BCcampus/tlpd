<?php
/**
 * Modified from original events manager plugin version: 5.6.6.1
 * events-manager/templates/buddypress/profile.php
 *
 * @author Brad Payne
 * @package tlpd
 * @since 0.9.2
 * @license https://www.gnu.org/licenses/gpl.html GPLv3 or later
 *
 * Original:
 * @author Marcus Sykes
 * @copyright Copyright Marcus Sykes
 */

global $bp, $EM_Notices;
echo $EM_Notices;
if ( user_can( $bp->displayed_user->id, 'edit_events' ) ) {
	?>

	<h4><?php _e( 'My Events', 'events-manager' ); ?></h4>
	<?php
	$args          = [
		'owner'         => $bp->displayed_user->id,
		'format_header' => get_option( 'dbem_bp_events_list_format_header' ),
		'format'        => get_option( 'dbem_bp_events_list_format' ),
		'format_footer' => get_option( 'dbem_bp_events_list_format_footer' ),
		'owner'         => $bp->displayed_user->id,
		'pagination'    => 1,
	];
	$args['limit'] = ! empty( $args['limit'] ) ? $args['limit'] : get_option( 'dbem_events_default_limit' );
	if ( EM_Events::count( $args ) > 0 ) {
		echo EM_Events::output( $args );
	} else {
		?>
		<p><?php _e( 'No Events', 'events-manager' ); ?>.
			<?php if ( get_current_user_id() == $bp->displayed_user->id ) : ?>
				<a href="<?php echo home_url() . '/post-event'; ?>"><?php _e( 'Add Event', 'events-manager' ); ?></a>
			<?php endif; ?>
		</p>
		<?php
	}
}
?>

	<h4><?php _e( "Events I'm Attending", 'events-manager' ); ?></h4>
<?php

$EM_Person   = new EM_Person( $bp->displayed_user->id );
$EM_Bookings = $EM_Person->get_bookings( false, apply_filters( 'em_bp_attending_status', 1 ) );
if ( count( $EM_Bookings->bookings ) > 0 ) {
	$nonce = wp_create_nonce( 'booking_cancel' );

	foreach ( $EM_Bookings as $EM_Booking ) {

		$booking    = $EM_Booking->get_event();
		$event_date = strtotime( $booking->event_start_date, time() );
		$today      = time();

		// separate past and future event_ids
		if ( $today > $event_date ) {
			$past_ids[] = $booking->event_id;
		} elseif ( $today < $event_date ) {
			$future_ids[] = $booking->event_id;
		}
	}
}

// Future Events Only
if ( isset( $future_ids ) && count( $future_ids ) > 0 ) {
	?>

	<table cellpadding="0" cellspacing="0" class="events-table">
		<thead>
		<tr>
			<th class="event-time" width="150">Date/Time</th>
			<th class="event-description" width="*">Upcoming Event</th>
			<?php
			if ( is_user_logged_in() ) {
				echo '<th class="event-delete">Delete this event from my profile</th>';
			}
			?>
			<th class="event-ical" width="*">Add to Calendar</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $EM_Bookings as $EM_Booking ) {
			// skip over if it's not in the future
			if ( ! in_array( $EM_Booking->event_id, $future_ids ) ) {
				continue;
			}
			$EM_Event = $EM_Booking->get_event();
			?>
			<tr>
				<td><?php echo $EM_Event->output( '#_EVENTDATES<br/>#_EVENTTIMES' ); ?></td>
				<td>
				<?php
				echo $EM_Event->output(
					'#_EVENTLINK
                {has_location}<br/><i>#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE</i>{/has_location}'
				);
				?>
					</td>

				<?php
				if ( is_user_logged_in() ) {
					echo '<td>';
					$cancel_link = '';
					if ( ! in_array(
						$EM_Booking->booking_status, [
							2,
							3,
						]
					) && get_option( 'dbem_bookings_user_cancellation' ) && $EM_Event->get_bookings()->has_open_time()
					) {
						$cancel_url  = em_add_get_params(
							$_SERVER['REQUEST_URI'], [
								'action'     => 'booking_cancel',
								'booking_id' => $EM_Booking->booking_id,
								'_wpnonce'   => $nonce,
							]
						);
						$cancel_link = '<a class="em-bookings-cancel" href="' . $cancel_url . '" onclick="if( !confirm(EM.booking_warning_cancel) ){ return false; }">' . __( 'Delete', 'events-manager' ) . '</a>';
					}
					echo apply_filters( 'em_my_bookings_booking_actions', $cancel_link, $EM_Booking );
					echo '</td>';
				}
				?>
				<td><?php echo $EM_Event->output( '#_EVENTICALLINK' ); ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php

} else {
	?>
	<p><?php _e( 'Not attending any events yet.', 'events-manager' ); ?></p>
	<?php
}
?>
	<!-- Past Events Only -->
	<h4><?php _e( "Past Events I've Attended", 'events-manager' ); ?></h4>
<?php
if ( isset( $past_ids ) && count( $past_ids ) > 0 ) {
	?>

	<div class='table-wrap'>
			<table id='dbem-bookings-table' class='widefat post fixed'>
				<thead>
				<tr>
					<th class='event-time' scope='col'><?php _e( 'Date/Time', 'events-manager' ); ?></th>
					<th class='event-description'
						scope='col'><?php _e( 'Event Description', 'events-manager' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$count = 0;

				foreach ( $EM_Bookings

				as $EM_Booking ) {
					// skip over if it's not in the past
					if ( ! in_array( $EM_Booking->event_id, $past_ids ) ) {
						continue;
					}
					$EM_Event = $EM_Booking->get_event();
					$event_id = $past_ids[ $count ];
					?>
				<tr>
					<td><?php echo $EM_Event->output( '#_EVENTDATES<br/>#_EVENTTIMES' ); ?></td>
					<td>
					<?php
					echo $EM_Event->output(
						'#_EVENTLINK
                {has_location}<br/><i>#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE</i>{/has_location}'
					);
					?>
						</td>


					<?php
				}
				?>
				</tbody>
			</table>
	</div>
	<?php
} else {
	_e( 'No past events attended yet.', 'events-manager' );
} ?>
