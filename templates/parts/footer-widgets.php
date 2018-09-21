<?php
/**
 * Modified from Original in c-box theme version: 1.0.16
 *
 * @author Brad Payne
 * @package early-years
 * @since 0.9.6
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Original:
 * @author Bowe Frankema <bowe@presscrew.com>
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 */
?>
<?php if ( is_active_sidebar( 'Footer Left' ) || is_active_sidebar( 'Footer Middle' ) || is_active_sidebar( 'Footer Right' ) ) : ?>
    <div class="footer-widgets d-flex flex-row flex-wrap no-gutters">
        <div class="col-md">
            <p>The Teaching Learning Professional Development (TLPD) web portal is an initiative developed by BCcampus with the B.C. post-secondary educator community.
                The web portal aims to support post-secondary educators in promoting and finding teaching and learning professional development opportunities and support the building of community connections.</p>
        </div>
		<?php if ( is_active_sidebar( 'Footer Left' ) ) : ?>
            <!-- footer widgets -->
            <div class="col-md-3" id="footer-widget-left">
				<?php
				dynamic_sidebar( 'Footer Left' );
				?>
            </div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'Footer Middle' ) ) : ?>
            <div class="col-md-3" id="footer-widget-middle">
				<?php
				dynamic_sidebar( 'Footer Middle' );
				?>
            </div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'Footer Right' ) ) : ?>
            <div class="col-md-3" id="footer-widget-right">
				<?php
				dynamic_sidebar( 'Footer Right' );
				?>
            </div>
		<?php endif; ?>
    </div>
<?php endif; ?>
<div class="d-flex flex-row flex-wrap no-gutters">
    <div class="col-sm-9"></div>
    <div class="widget col-sm-3">
        <picture>
            <source srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bcc15-logo.png">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bcc15-logo.png" width="240" height="200" alt="BCcampus logo">
        </picture>
    </div>
</div>
<div style="clear:both;"></div>
