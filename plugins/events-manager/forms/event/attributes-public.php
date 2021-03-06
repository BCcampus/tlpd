<?php
/**
 * Modified from original events manager plugin version: 5.6.6.1
 * @author Brad Payne
 * @package tlpd
 * @since 0.9
 * @license https://www.gnu.org/licenses/gpl.html GPLv3 or later
 *
 * Original:
 * @author Marcus Sykes
 * @copyright Copyright Marcus Sykes
 */

global $EM_Event;
/**
 * @var $EM_Event EM_Event
 */
$attributes          = em_get_attributes();
$has_deprecated      = false;
$required_attributes = [ 'Registration Fee' ];

if ( count( $attributes['names'] ) > 0 ) : ?>
	<?php foreach ( $attributes['names'] as $name ) : ?>
		<div class="event-attributes">
			<label for="em_attributes[<?php echo $name; ?>]"><?php echo $name; ?><?php echo ( in_array( $name, $required_attributes, true ) ) ? '<i>*</i>' : ''; ?></label>
			<?php
			switch ( $name ) {
				case 'Registration Fee':
					echo '<p class="margin-up"><i>Enter the registration fee ($CAD). Add notes as required; e.g., "Free", "No fee for internal staff"</i></p>';
					break;
				case 'Facilitator(s) Bio':
					echo '<p class="margin-up"><i>Maximum 100 words</i></p>';
					break;
				case 'Event Hosts':
					echo '<p class="margin-up"><i>Who is organizing the event?</i></p>';
					break;
				case 'Online':
					echo '<p class="margin-up"><i>Is this offering available online or provide opportunities for online participation?</i></p>';
					break;
				default:
					echo '';
			}
			?>
			<?php if ( count( $attributes['values'][ $name ] ) > 1 ) : ?>
				<select name="em_attributes[<?php echo $name; ?>]">
					<?php foreach ( $attributes['values'][ $name ] as $attribute_val ) : ?>
						<?php if ( is_array( $EM_Event->event_attributes ) && array_key_exists( $name, $EM_Event->event_attributes ) && $EM_Event->event_attributes[ $name ] === $attribute_val ) : ?>
							<option selected="selected"><?php echo $attribute_val; ?></option>
						<?php else : ?>
							<option><?php echo $attribute_val; ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			<?php else : ?>
				<?php $default_value = ( ! empty( $attributes['values'][ $name ][0] ) ) ? $attributes['values'][ $name ][0] : ''; ?>
				<input type="text" name="em_attributes[<?php echo $name; ?>]" value="<?php echo array_key_exists( $name, $EM_Event->event_attributes ) ? esc_attr( $EM_Event->event_attributes[ $name ], ENT_QUOTES ) : ''; ?>" value="<?php echo $default_value; ?>"/>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
