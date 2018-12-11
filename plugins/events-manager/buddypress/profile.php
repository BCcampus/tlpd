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

