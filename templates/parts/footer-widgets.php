<?php
/**
 * Modified from Original in c-box theme version: 1.0.16
 *
 * @author Brad Payne
 * @package tlpd
 * @since 0.9.6
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Original:
 * @author Bowe Frankema <bowe@presscrew.com>
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 */
?>
<?php if ( is_active_sidebar( 'Footer Left' ) || is_active_sidebar( 'Footer Middle' ) || is_active_sidebar( 'Footer Right' ) ) : ?>
	<div class="footer-widgets d-flex flex-row flex-wrap">
		<?php if ( is_active_sidebar( 'Footer Left' ) ) : ?>
			<!-- footer widgets -->
			<div class="col-md-4" id="footer-widget-left">
				<?php
				dynamic_sidebar( 'Footer Left' );
				?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'Footer Middle' ) ) : ?>
			<div class="col-md-4" id="footer-widget-middle">
				<?php
				dynamic_sidebar( 'Footer Middle' );
				?>
			</div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'Footer Right' ) ) : ?>
			<div class="col-md-4" id="footer-widget-right">
				<?php
				dynamic_sidebar( 'Footer Right' );
				?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
