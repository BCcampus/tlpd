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
    <div class="footer-widgets row">
        <div class="seven columns">
<!--            <picture>-->
<!--                <source srcset="--><?php //echo get_stylesheet_directory_uri(); ?><!--/dist/images/tlpd-logo-small.webp"-->
<!--                        type="image/webp">-->
<!--                <source srcset="--><?php //echo get_stylesheet_directory_uri(); ?><!--/dist/images/tlpd-logo-small.png">-->
<!--                <img src="--><?php //echo get_stylesheet_directory_uri(); ?><!--/dist/images/tlpd-logo-small.png"-->
<!--                     alt="BC Provincial Office for the Teaching and Learning Professional Development">-->
<!--            </picture>-->
            <p>The Teaching Learning Professional Development (TLPD) web portal is an initiative developed by BCcampus with the B.C. postsecondary educator community.
                The web portal aims to support post-secondary educators in promoting and finding teaching and learning professional development opportunities and support the building of community connections.</p>
        </div>
		<?php if ( is_active_sidebar( 'Footer Left' ) ) : ?>
            <!-- footer widgets -->
            <div class="three columns" id="footer-widget-left">
				<?php
				dynamic_sidebar( 'Footer Left' );
				?>
            </div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'Footer Middle' ) ) : ?>
            <div class="three columns" id="footer-widget-middle">
				<?php
				dynamic_sidebar( 'Footer Middle' );
				?>
            </div>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'Footer Right' ) ) : ?>
            <div class="three columns" id="footer-widget-right">
				<?php
				dynamic_sidebar( 'Footer Right' );
				?>
            </div>
		<?php endif; ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="eight columns"></div>
    <div class="widget four columns">
        <picture>
            <source srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bccampus-logo.png">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bc-ministry-logo.png"width="249" height="96" alt="BCcampus logo">
        </picture>
    </div>
</div>
<div style="clear:both;"></div>