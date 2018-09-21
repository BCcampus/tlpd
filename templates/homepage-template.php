<?php
/**
 * Early-Years Theme: homepage template
 *
 * Modified from original header template in cbox theme
 *
 * @author Brad Payne
 * @package early-years
 * @since 0.9
 * @license https://www.gnu.org/licenses/gpl.html GPLv3 or later
 *
 * Original:
 * @author Bowe Frankema <bowe@presscrew.com>
 * @copyright Copyright (C) 2010-2011 Bowe Frankema
 */

infinity_get_header();

?>
<section class="row-fluid">
	<div class="col-12">
        <h2 class="text-green text-center">Find learning events</h2>
        <p class="text-center">Fill in one or more of the fields below</p>
        <?php echo do_shortcode( '[events_search]' ); ?>
	</div>
</section>

<section class="row-fluid mb-4 justify-content-center align-self-center">
    <h2 class="text-green text-center">New + Noteworthy</h2>
	<div class="col-12">
	<?php infinity_load_template( 'templates/featured-stories.php' ); ?>
	</div>
</section>

<section class="c-map row-fluid">
	<div class="justify-content-center align-self-center col-12">
		<h2 class="text-green text-center">Find learning events near you</h2>
	</div>
    <!-- tabs start -->
    <div id="tabs" class="col-sm">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab"
                   aria-controls="home" aria-selected="true">Upcoming Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="recent-tab" data-toggle="tab" href="#recent" role="tab"
                   aria-controls="profile" aria-selected="false">Recently Posted</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
				<?php
					$events_list = '[events_list scope="after-today" limit="4"]';
					echo do_shortcode( $events_list );
				?>
            </div>
            <div class="tab-pane fade" id="recent" role="tabpanel" aria-labelledby="recent-tab">
				<?php
					// documentation http://wp-events-plugin.com/documentation/event-search-attributes/event-location-grouping-ordering/
					$events_recent = '[events_list orderby="event_date_created" order="DESC" groupby="location_id" groupby_orderby="event_date_created" groupby_order="DESC" limit="4"]';
					echo do_shortcode( $events_recent );
				?>
            </div>
        </div>
    </div>
    <!-- tabs end -->
</section>

<?php
infinity_get_footer();
?>
