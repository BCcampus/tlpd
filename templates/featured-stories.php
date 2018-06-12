<?php
/**
 * Early-Years Theme: Featured Stories
 *
 * Modified from original header template in cbox theme
 *
 * @author Brad Payne
 * @package early-years
 * @since 0.9
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 or later
 */
global $post;
$args    = [
    'posts_per_page' => 2,
    'category_name'  => 'Homepage',
    'post_status'    => 'publish',
    'order'          => 'DESC',
    'post__in'       => get_option( 'sticky_posts' ),
];
$myposts = get_posts( $args );

echo '<article class="row">';

foreach ( $myposts as $post ) : setup_postdata( $post );
    $child_theme_uri = get_stylesheet_directory_uri();
    $thumbnail       = ( empty( the_post_thumbnail() ) ) ? "<img src='{$child_theme_uri}/dist/images/new-noteworthy.png' alt='new and noteworthy' />" : the_post_thumbnail( '150' );
    ?>
    <div class="five columns">
        <p>
            <a href="<?php the_permalink(); ?>" rel="bookmark"
               title="<?php the_title_attribute(); ?>"><?php echo $thumbnail; ?></a>
        </p>
        <h4>
            <a href="<?php the_permalink(); ?>" rel="bookmark"
               title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
        </h4>
        <p><?php the_excerpt(); ?>

    </div>

<?php endforeach;

echo '</article>';

wp_reset_postdata(); ?>
