<?php
/**
 * Teaching and Learning Professional Development Theme: Header Content
 *
 * Modified from original header template in cbox theme
 * @author Brad Payne
 * @package tlpd
 * @since 0.9.5
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 or later
 */
?>
<div class="top-wrap d-flex flex-row flex-wrap no-gutters <?php do_action( 'top_wrap_class' ); ?>">
	<?php
	// Load Top Menu only if it's enabled
	if ( current_theme_supports( 'infinity-top-menu-setup' ) ) :
		infinity_get_template_part( 'templates/parts/top-menu', 'header' );
	endif;
	?>
	<!-- header -->
	<header id="header" role="banner">
		<div id="logo-menu-wrap">
			<div class="col-sm-4 px-0">
			<?php
			do_action( 'open_header' );
			?>
			<?php
			$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
			?>
			<<?php echo $heading_tag; ?> id="icext" class="icext-feature icext-header-logo tlpd-header-logo">
			<a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'name' ); ?> - <?php bloginfo( 'description' ); ?>">
				<picture>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/tlpd-logo.svg" alt="Teaching and Learning Professional Development">
				</picture>
			</a>
		</<?php echo $heading_tag; ?>>
		</div>
		<div class="col-sm-8 px-0">
		<?php
		// Load Main Menu only if it's enabled
		if ( current_theme_supports( 'infinity-main-menu-setup' ) ) :
			infinity_get_template_part( 'templates/parts/main-menu', 'header' );
		endif;
		do_action( 'close_header' );
		?>
		</div>
</div>
</header><!-- end header -->
<?php
// Load Sub Menu only if it's enabled
if ( current_theme_supports( 'infinity-sub-menu-setup' ) ) :
	infinity_get_template_part( 'templates/parts/sub-menu', 'header' );
endif;
?>
</div><!-- end top wrap -->
