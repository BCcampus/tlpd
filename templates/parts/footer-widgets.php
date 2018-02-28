<?php
/**
 * Modified from Original in c-box theme version: 1.0.16
 *
 * @author Brad Payne
 * @package pro-d
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
            <picture>
                <source srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/pro-d-logo-small.webp"
                        type="image/webp">
                <source srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/pro-d-logo-small.png">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/pro-d-logo-small.png"
                     alt="BC Provincial Office">
            </picture>
            <p>pro-d aims to support the B.C. sector by hosting, developing and evaluating a professional
                development web portal. <a href="about-us" class="text-blue">Learn more about pro-d</a></p>
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
    <div class="ten columns"></div>
    <div class="widget six columns">
        <h4>Funded by</h4>
        <picture>
            <source srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bc-ministry-logo.webp"
                    type="image/webp">
            <source srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bc-ministry-logo.png">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/bc-ministry-logo.png" width="329"
                 height="78" alt="BC Provincial Office">
        </picture>
    </div>
</div>
<div style="clear:both;"></div>