<?php
/*
|--------------------------------------------------------------------------
| Load Composer Dependencies
|--------------------------------------------------------------------------
|
|
|
|
*/
if ( file_exists( $composer = __DIR__ . '/vendor/autoload.php' ) ) {
	require_once( $composer );
}

/*
|--------------------------------------------------------------------------
| Asynchronous loading js
|--------------------------------------------------------------------------
|
| to improve speed of page load
|
|
*/
add_filter( /**
	* @param $tag
	* @param $handle
	* @param $src
	*
	* @return string
	*/
	'script_loader_tag', function ( $tag, $handle, $src ) {
		$defer = [
			'jquery-migrate',
			'jquery-ui-position',
			'jquery-ui-draggable',
			'jquery-ui-resizable',
			'jquery-ui-mouse',
			'jquery-ui-menu',
			'jquery-ui-sortable',
			'jquery-ui-datepicker',
			'jquery-ui-autocomplete',
			'jquery-ui-dialog',
			'jquery-ui-button',
			'bp-confirm',
			'bp-jquery-query',
			'events-manager',
			'jquery-mobilemenu',
			'jquery-fitvids',
		];

		$async = [
			'bp-jquery-cookie',
			'dtheme-ajax-js',
			'wp-a11y',
			'bp-widget-members',
			'groups_widget_groups_list-js',
			'joyride',
		];

		if ( in_array( $handle, $defer ) ) {
			return "<script defer type='text/javascript' src='{$src}'></script>" . "\n";
		}

		if ( in_array( $handle, $async ) ) {
			return "<script async type='text/javascript' src='{$src}'></script>" . "\n";
		}

		return $tag;
	}, 10, 3
);

/*
|--------------------------------------------------------------------------
| Scripts and Styles
|--------------------------------------------------------------------------
|
| early years look, feel, functionality
|
|
*/

/**
 * need our stylesheet to fire later than the rest
 * in order for: now your base are belong to us
 * infinity theme behaves differently than you would expect parent themes to act
 */
add_action(
	'wp_enqueue_scripts', function () {
		wp_enqueue_style( 'tlpd', get_stylesheet_directory_uri() . '/dist/styles/main.css', [ '@:dynamic' ], '', 'screen' );
	}, 11
);

/**
 * back end, front end parity
 */
add_editor_style( get_stylesheet_directory_uri() . '/dist/styles/main.css' );

/**
 * Load our scripts
 */
add_action(
	'wp_enqueue_scripts', function () {
		$template_dir = get_stylesheet_directory_uri();

		// toss Events Manager scripts and their dependencies
		wp_dequeue_script( 'events-manager' );
		remove_action( 'close_body', 'cbox_theme_flex_slider_script' );

		wp_enqueue_script( 'jquery-ui-draggable' );

		$script_deps = [
			'jquery'                 => 'jquery',
			'jquery-ui-core'         => 'jquery-ui-core',
			'jquery-ui-widget'       => 'jquery-ui-widget',
			'jquery-ui-position'     => 'jquery-ui-position',
			'jquery-ui-sortable'     => 'jquery-ui-sortable',
			'jquery-ui-datepicker'   => 'jquery-ui-datepicker',
			'jquery-ui-autocomplete' => 'jquery-ui-autocomplete',
			'jquery-ui-dialog'       => 'jquery-ui-dialog',
		];
		wp_enqueue_script( 'events-manager', $template_dir . '/dist/scripts/events-manager.js', array_values( $script_deps ), isset( $EM_VERSION ) );
		wp_enqueue_script( 'tinyscrollbar', $template_dir . '/dist/scripts/jquery.tinyscrollbar.min.js', [ 'jquery' ], '1.0', true );

		wp_enqueue_script( 'bootstrap-script', $template_dir . '/dist/scripts/bootstrap.bundle.js', [ 'jquery' ], null, true );
		wp_enqueue_style( 'bootstrap-style', $template_dir . '/dist/styles/bootstrap.min.css' );

		if ( is_front_page() ) {
			wp_enqueue_script( 'jquery-tabs', $template_dir . '/dist/scripts/tabs.js', [ 'jquery' ], null, true );
			wp_enqueue_script( 'jquery-ui-tabs' );
		}

		if ( is_singular( 'event' ) ) {
			wp_enqueue_style( 'banner', $template_dir . '/dist/styles/event.css' );
		}

		if ( is_page( 'edit-events' ) || is_page( 'post-event' ) ) {
			wp_enqueue_style( 'media-manager', $template_dir . '/dist/styles/media.css' );
			wp_enqueue_style( 'select2-style', $template_dir . '/dist/styles/select2.min.css' );

			wp_enqueue_script( 'select2', $template_dir . '/dist/scripts/select2.min.js', [], null, true );
			wp_enqueue_script( 'select-multiple', $template_dir . '/dist/scripts/select-multiple.js', [ 'select2' ], null, true );
		}
	}, 10
);

/*
|--------------------------------------------------------------------------
| Admin Styles
|--------------------------------------------------------------------------
|
| for admin pages only
|
|
*/

add_action(
	'admin_enqueue_scripts', function () {
		wp_enqueue_style( 'tlpd_admin_css', get_stylesheet_directory_uri() . '/dist/styles/admin.css', false, false, 'screen' );
	}
);

// remove from parent theme
remove_action( 'wp_head', 'infinity_custom_favicon' );

/*
|--------------------------------------------------------------------------
| Common
|--------------------------------------------------------------------------
|
|
*/
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 100, 100 );

/*
|--------------------------------------------------------------------------
| Maps
|--------------------------------------------------------------------------
|
| Hijacks files from events-manager plugin
|
|
*/
if ( function_exists( 'em_content' ) ) {
	remove_filter( 'the_content', 'em_content' );
}

if ( function_exists( 'em_content' ) ) {
	remove_filter( 'init', 'em_init_actions' );
}
include( get_stylesheet_directory() . '/tlpd-actions.php' );
include( get_stylesheet_directory() . '/tlpd-events.php' );

/*
|--------------------------------------------------------------------------
| Events Manager
|--------------------------------------------------------------------------
|
| Creates a new scope for the events manager short code, and then registers it with events manager.
| It will only lists events with a date greater than today's.
|
|
*/

/**
 *
 * @param $conditions
 * @param $args
 *
 * @return mixed
 */
function tlpd_em_scope_conditions( $conditions, $args ) {
	if ( ! empty( $args['scope'] ) && $args['scope'] == 'after-today' ) {
		$current_date        = date( 'Y-m-d', current_time( 'timestamp' ) );
		$conditions['scope'] = " (event_start_date > CAST('$current_date' AS DATE))";
	}

	return $conditions;
}

add_filter( 'em_events_build_sql_conditions', 'tlpd_em_scope_conditions', 1, 2 );


/**
 *
 * @param $scopes
 *
 * @return array
 */
function tlpd_em_scopes( $scopes ) {
	$my_scopes = [
		'after-today' => 'After Today',
	];

	return $scopes + $my_scopes;
}

add_filter( 'em_get_scopes', 'tlpd_em_scopes', 1, 1 );

/*
|--------------------------------------------------------------------------
| Login customization
|--------------------------------------------------------------------------
|
|
|
|
*/

/**
 * Custom stylesheet enqueued at login page
 */
add_action(
	'login_enqueue_scripts', function () {
		wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/dist/styles/login.css' );
	}
);

/**
 * Link logo image to our home_url instead of WordPress.org
 *
 * @return string|void
 */
add_filter(
	'login_headerurl', function () {
		return home_url();
	}
);

/**
 * Give the image our sites name
 *
 * @return string|void
 */
add_filter(
	'login_headertitle', function () {
		return get_bloginfo( 'name' );
	}
);

/**
 * Add custom text to login form
 *
 * @param $message
 *
 * @return string
 */
function tlpd_login_message( $message ) {
	if ( empty( $message ) ) {
		$imgdir = get_stylesheet_directory_uri();
		$html   = '<p class="login-logo"><picture><source srcset="' . $imgdir . '/dist/images/tlpd-logo-login.png"><img src="' . $imgdir . '/dist/images/tlpd-logo-login.png" width="249" height="111" alt="Teaching and Learning and Professional Developemnt"></picture></p>';
		$html  .= '<p class="logintext">Log in To Your TLPD Account</p>';
		echo $html;
	} else {
		return $message;
	}
}

add_filter( 'login_message', 'tlpd_login_message' );

/**
 * Adds Sign Up button and Forgot lost password link
 */
function tlpd_login_form() {
	$html  = '<p class="signuptext">New to TLPD?</p><p><a class ="button button-primary button-large signup" href="' . home_url() . '/sign-up" title="Sign Up">Sign Up</a></p>';
	$html .= '&nbsp; &#45; &nbsp;<a class ="forgot" href="' . wp_lostpassword_url() . '" title="Lost Password">Forgot Password?</a>';

	echo $html;
}

add_action( 'login_form', 'tlpd_login_form' );

/*
|--------------------------------------------------------------------------
| Excerpt
|--------------------------------------------------------------------------
|
| Filter the read more ...
|
|
*/

/**
 * @param $more
 *
 * @return string
 */
function tlpd_read_more( $more ) {
	global $post;

	return ' <a href="' . get_the_permalink( $post->ID ) . '">...[Read full article]</a>';
}

add_filter( 'excerpt_more', 'tlpd_read_more' );


/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function tlpd_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'tlpd_excerpt_length', 999 );

/*
|--------------------------------------------------------------------------
| Labels/Localization
|--------------------------------------------------------------------------
|
| Addin' sum canadiana to this here 'merican plugin
|
|
*/

function tlpd_get_provinces() {
	$provinces = [
		'Alberta',
		'British Columbia',
		'Manitoba',
		'New Brunswick',
		'Newfoundland',
		'Northwest Territories',
		'Nova Scotia',
		'Nunavut',
		'Ontario',
		'Prince Edward Island',
		'Quebec',
		'Saskatchewan',
		'Yukon',
	];

	return $provinces;
}


/**
 * Runs once to set up defaults
 * increase variable $tlpd_version to ensure it runs again
 */
function tlpd_run_once() {

	// change tlpd_version value to run it again
	$tlpd_version       = 7.25;
	$current_version    = get_option( 'tlpd_version', 0 );
	$img_max_dimension  = 1000;
	$img_min_dimension  = 50;
	$img_max_size       = 8388608;
	$default_no         = [
		'dbem_css_search',
		'dbem_events_form_reshow',
		'dbem_events_anonymous_submissions',
		'dbem_cp_events_comments',
		'dbem_search_form_countries',
		'dbem_locations_page_search_form',
		'dbem_bookings_anonymous',
		'dbem_bookings_approval',
		'dbem_bookings_double',
		'dbem_bookings_login_form',
		'dbem_search_form_geo',
		'dbem_events_page_search_form',
		'dbem_rsvp_enabled',
	];
	$default_yes        = [
		'dbem_recurrence_enabled',
		'dbem_categories_enabled',
		'dbem_attributes_enabled',
		'dbem_cp_events_custom_fields',
		'dbem_locations_enabled',
		'dbem_require_location',
		'dbem_events_form_editor',
		'dbem_cp_events_formats',
		'dbem_gmap_is_active',
		'dbem_cp_events_formats',
		'dbem_bookings_approval_reserved',
		'dbem_bookings_user_cancellation',
		'dbem_bookings_approval_overbooking',
	];
	$default_attributes = '#_ATT{Facilitator Name(s)}
#_ATT{Facilitator(s) Bio}
#_ATT{Event Hosts}
#_ATT{Target Audience}{Administrators|Consultants|Ed Developers|Ed Technologists & Media Developers|Faculty & Instructors|Instructional Designers|Information Technology staff|Librarians|Students|Teaching & Learning Staff|Everyone}
#_ATT{Target Audience - Other}
#_ATT{Event is open to external}{|Yes|No}
#_ATT{Prerequisite(s)}
#_ATT{Registration Fee}
#_ATT{Website}
#_ATT{Registration Contact Phone Number}
#_ATT{Registration Contact Email}';

	$single_event_format = '<div class="single-event-map">#_LOCATIONMAP</div>
<p>
	<strong>Date/Time</strong><br/>
	#_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
<p>
	<strong>Location</strong><br/>
	#_LOCATIONLINK
</p>
<p><strong>Add to My Calendar</strong><br>#_EVENTICALLINK</p>
{has_location}

{/has_location}
<br style="clear:both" />
#_EVENTNOTES
<p>
	<strong>Categories</strong>
	#_CATEGORIES
</p>
{has_bookings}
#_BOOKINGFORM
{/has_bookings}';

	$success_message = '<p><strong>Congratulations! You have successfully submitted your training event.</strong></p> <p><strong>Go to the <a href="' . get_site_url() . '">' . 'homepage</a> and use the search or map feature to find your event.</strong></p>';

	$loc_balloon_format = '<strong>#_LOCATIONNAME</strong><address>#_LOCATIONADDRESS<br>#_LOCATIONTOWN</address>
#_LOCATIONNEXTEVENTS';

	$format_event_list_header = '<table cellpadding="0" cellspacing="0" class="events-table" >
    <thead>
        <tr>
			<th class="event-time" width="150">Date/Time</th>
			<th class="event-description" width="*">Event</th>
			<th class="event-capacity" width="*">Location</th>
		</tr>
   	</thead>
    <tbody>';

	$format_event_list = '<tr>
			<td>#_EVENTDATES<br/>#_EVENTTIMES</td>
            <td>#_EVENTLINK</td>
			<td>{has_location}#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE{/has_location}</td>
        </tr>';

	$format_event_list_footer = '</tbody></table>';

	if ( $current_version < $tlpd_version ) {

		update_option( 'dbem_placeholders_custom', $default_attributes );
		update_option( 'dbem_image_max_width', $img_max_dimension );
		update_option( 'dbem_image_max_height', $img_max_dimension );
		update_option( 'dbem_image_min_width', $img_min_dimension );
		update_option( 'dbem_image_min_height', $img_min_dimension );
		update_option( 'dbem_image_max_size', $img_max_size );
		update_option( 'dbem_events_form_result_success', $success_message );
		update_option( 'dbem_events_form_result_success_updated', $success_message );
		update_option( 'dbem_map_text_format', $loc_balloon_format );
		update_option( 'dbem_event_list_item_format', $format_event_list );
		update_option( 'dbem_event_list_item_format_header', $format_event_list_header );
		update_option( 'dbem_event_list_item_format_footer', $format_event_list_footer );
		update_option( 'dbem_single_event_format', $single_event_format );
		update_option( 'dbem_location_event_list_limit', 20 );

		foreach ( $default_no as $no ) {
			update_option( $no, 0 );
		}

		foreach ( $default_yes as $yes ) {
			update_option( $yes, 1 );
		}
		/**
		 * Changes to search for labels
		 */
		update_option( 'dbem_search_form_state_label', 'Province' );
		update_option( 'dbem_search_form_text_label', 'Search by Topic, Keyword or Location' );
		update_option( 'dbem_search_form_dates_label', 'Search by Start Date' );
		update_option( 'dbem_search_form_category_label', 'Search by Category' );
		update_option( 'dbem_search_form_town_label', 'City/Community/Town' );
		update_option( 'dbem_search_form_dates_separator', 'End Date' );

		/**
		 * All events will be in Canada
		 */
		update_option( 'dbem_location_default_country', 'CA' );

		/**
		 * Most events will be in British Columbia
		 */
		update_option( 'tlpd_location_default_province', 'British Columbia' );

		/**
		 * Set BC as the default province on the search form
		 */
		update_option( 'dbem_search_form_states_label', 'British Columbia' );

		/**
		 * Booking submit button text
		 */
		update_option( 'dbem_bookings_submit_button', 'Plan to attend' );

		/**
		 * Booking submit success
		 */
		update_option( 'dbem_booking_feedback', 'Event added! Click on myTLPD (top right of your screen) to find this saved event.' );

		/**
		 * Manage bookings link text
		 */
		update_option( '	dbem_bookings_form_msg_bookings_link', 'My Profile Page' );

		/**
		 * Make the map responsive width
		 */
		update_option( 'dbem_map_default_width', '100%' );

		/**
		 * Control all the things map-ish
		 */
		update_option( 'dbem_map_default_height', '300px' );

		/**
		 * Update option to current version
		 */
		update_option( 'tlpd_version', $tlpd_version );

	}
}

add_action( 'wp_loaded', 'tlpd_run_once' );

/**
 * Changing state to province and other customizations
 *
 * @param $translated
 * @param $original
 * @param $domain
 *
 * @return mixed
 */
function tlpd_terminology_modify( $translated, $original, $domain ) {

	if ( 'events-manager' == $domain ) {
		$modify = [
			'State/County:'                                                                  => 'Province:',
			'Details'                                                                        => 'Event Description and Objectives',
			'Category:'                                                                      => 'Category',
			'Submit %s'                                                                      => 'Post %s',
			'You must log in to view and manage your events.'                                => 'You are using this site in the role as a Learner. Learners may search for, share, and print events. Only Organizers may post and edit events.',
			'You are currently viewing your public page, this is what other users will see.' => '',
		];
	}

	if ( 'buddypress' == $domain ) {
		$modify = [
			'Register'                                                                                                                  => 'Sign Up',
			'Email Address'                                                                                                             => 'Work Email Address',
			'Registering for this site is easy. Just fill in the fields below, and we\'ll get a new account set up for you in no time.' => 'Register as a Contributor to post professional learning events.',
		];
	}

	if ( isset( $modify[ $original ] ) ) {
		$translated = $modify[ $original ];
	}

	return $translated;
}

add_filter( 'gettext', 'tlpd_terminology_modify', 11, 3 );

/**
 * Howdy message needs a higher priority and different logic
 * than @see tlpd_terminology_modify()
 *
 * @param $translated_text
 * @param $text
 * @param $domain
 *
 * @return mixed
 */
function tlpd_howdy_message( $translated_text, $text, $domain ) {
	$new_message = str_replace( 'Howdy,', '', $text );

	return $new_message;
}

add_filter( 'gettext', 'tlpd_howdy_message', 10, 3 );

/**
 *
 * @param int $post_id
 * @param array $data
 *
 * @return array
 */
function tlpd_event_output( $post_id = 0 ) {
	// get the data
	$data = get_post_custom( $post_id );

	// return the design
	return $data;
}

/**
 * intercepts output, finds post id#s, uses them to get slugs
 * insert those slugs into the <li> as classes
 *
 * @param string $input
 *
 * @return mixed|string
 */
function tlpd_event_etc_output( $input = '' ) {
	$output = $input;
	preg_match_all( '/<li class="category-(\d+)">/', $input, $output_array );
	foreach ( $output_array[1] as $index => $post_id ) {
		$cats       = wp_get_object_terms( $post_id, 'event-categories' );
		$cat_output = $space = '';
		foreach ( $cats as $cat ) {
			$c           = get_category( $cat );
			$cat_output .= $space . 'cat_' . str_replace( '-', '_', $c->slug );
			$space       = ' ';
		}
		$new_classes = "<li class=\"$cat_output\">";
		$output      = str_replace( $output_array[0][ $index ], $new_classes, $output );
	}
	// remove pagination links
	$output = preg_replace( '/<strong><span class=\"page-numbers(.*)<\/span>/i', '', $output );

	return $output;
}


/**
 * use it for two uses -- the Ajax response and the post info
 *
 * @param int $post_id
 * @param bool $ajax
 */
function et_fetch( $post_id = - 1, $ajax = true ) {
	if ( $ajax == true ) {
		$output = tlpd_event_output( $post_id );
		echo json_encode( $output ); //encode into JSON format and output
		die(); //stop "0" from being output
	}
}

add_action( 'wp_ajax_nopriv_cyop_lookup', 'et_fetch' );
add_action( 'wp_ajax_cyop_lookup', 'et_fetch' );

/**
 * remove links/menus from the admin bar,
 * if you are not an admin
 */
function tlpd_admin_bar_render() {
	global $wp_admin_bar;

	// check if the admin panel is attempting to be displayed
	if ( ! is_admin() ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'search' );
		$wp_admin_bar->remove_node( 'comments' );
		$wp_admin_bar->remove_node( 'edit' );
		$wp_admin_bar->remove_node( 'edit-profile' );
		$wp_admin_bar->remove_node( 'logout' );
		$wp_admin_bar->remove_node( 'new-content' );
		$wp_admin_bar->remove_node( 'updates' );
		$wp_admin_bar->remove_node( 'my-blogs' );
		$wp_admin_bar->remove_node( 'customize' );
		$wp_admin_bar->remove_node( 'site-name' );
		$wp_admin_bar->remove_node( 'my-account-buddypress' );
		$wp_admin_bar->remove_node( 'bp-notifications' );
		$wp_admin_bar->remove_node( 'itsec_admin_bar_menu' );

		// add my profile link
		$profileurl = tlpd_get_my_bookings_url();
		$wp_admin_bar->add_node(
			[
				'id'     => 'my_profile',
				'title'  => 'myTLPD',
				'href'   => $profileurl,
				'parent' => 'user-actions',
				'meta'   => [
					'class' => 'my-profile-page',
				],
			]
		);

		//add logout link after my profile link, and redirect to homepage after logout
		$logouturl = wp_logout_url( home_url() );
		$wp_admin_bar->add_node(
			[
				'id'     => 'logout',
				'title'  => 'Logout',
				'href'   => $logouturl,
				'parent' => 'user-actions',
				'meta'   => [
					'class' => 'my-logout-link',
				],
			]
		);

		// maintain a way for admins to access the dashboard
		if ( current_user_can( 'activate_plugins' ) ) {
			$url = get_admin_url();
			$wp_admin_bar->add_node(
				[
					'id'    => 'tlpd_dashboard',
					'title' => 'Dashboard',
					'href'  => $url,
					'meta'  => [
						'class' => 'my-toolbar-page',
					],
				]
			);
		}
	}
}

add_action( 'wp_before_admin_bar_render', 'tlpd_admin_bar_render' );

/**
 * Remove BP sidebar menu items
 */
function tlpd_bp_nav() {
	global $bp;
	bp_core_remove_nav_item( 'activity' );
	bp_core_remove_nav_item( 'forums' );
	bp_core_remove_nav_item( 'groups' );
	bp_core_remove_nav_item( 'friends' );
	bp_core_remove_nav_item( 'messages' );
	bp_core_remove_nav_item( 'notifications' );
	//subnav
	bp_core_remove_subnav_item( 'events', 'attending' );
	bp_core_remove_subnav_item( 'events', 'my-bookings' );
	bp_core_remove_subnav_item( 'events', 'my-events' );

}
// Commenting out 2018-12-13 to restore BP functionality
//add_action( 'bp_setup_nav', 'tlpd_bp_nav', 1000 );


// Filter wp_nav_menu() to add tooltips to links in header menu
add_filter(
	'wp_nav_menu_items', function ( $nav, $args ) {
		if ( $args->theme_location == 'main-menu' ) {
			if ( is_user_logged_in() ) {
				$nav  = '<li class="home"><a href=' . home_url() . '/events>Events</a></li>';
				$nav .= '<li class="home"><a href=' . home_url() . '/post-event>Add New</a></li>';
				$nav .= '<li class="home"><a href=' . home_url() . '/edit-events>Edit Events</a></li>';
				$nav .= '<li class="home"><a href="' . tlpd_get_my_bookings_url() . '">' . __( '<i>my</i> Events' ) . '</a></li>';
			} else {
				$nav = '<li class="home"><a href=' . home_url() . '/events>Events</a></li>';
			}
		}

		return $nav;
	}, 10, 2
);

/**
 * Add favicon, theme color, PWA manifest
 */
add_action(
	'wp_head', function () {
		$manifest = tlpd_get_manifest_path();
		echo '<meta name="theme-color" content="#bee7fa"/>' . "\n";
		echo '<link rel="shortcut icon" type="image/x-icon" href="' . get_stylesheet_directory_uri() . '/dist/images/favicon.ico" />' . "\n";
		echo '<link rel="manifest" href="' . $manifest . '">';

	}
);


/**
 * Validating that required attribute fields are not empty
 */
function tlpd_validate_attributes() {
	global $EM_Event;

	// bail early if not an object
	if ( ! is_object( $EM_Event ) ) {
		return false;
	}

	if ( empty( $EM_Event->event_attributes['Registration Fee'] ) ) {
		$EM_Event->add_error( sprintf( __( '%s is required.', 'tlpd' ), __( 'Registration Fee', 'tlpd' ) ) );
	}

	if ( ! empty( $EM_Event->event_attributes['Website'] ) && false === tlpd_maybe_url( $EM_Event->event_attributes['Website'] ) ) {
		$EM_Event->add_error( sprintf( __( '%s is not a valid URL.', 'tlpd' ), __( 'Website', 'tlpd' ) ) );
	}

	return $EM_Event;

}

add_action( 'em_event_validate', 'tlpd_validate_attributes' );

/**
 * Makes profile fields descriptions into modals,
 * content of modals are in tlpd/templates/*-modal.php
 */
function tlpd_profile_field_modals() {

	// check xprofile is activated
	if ( bp_is_active( 'xprofile' ) ) {

		$bp_field_name = bp_get_the_profile_field_name();

		// replace content of $field_description to enable use of modals
		switch ( $bp_field_name ) {

			case 'Agreement Terms:':
				$field_description = '<a href="#terms" data-toggle="modal">Terms of Use</a>';

				return $field_description;
				break;

			case 'Position/Role':
				$field_description = '<a href="#role" data-toggle="modal">What’s the difference between Learner and Organizer?</a>';

				return $field_description;
				break;
		}
	}
}

add_filter( 'bp_get_the_profile_field_description', 'tlpd_profile_field_modals' );

/**
 * Setting a higher default for bookings capacity
 *
 * @return int
 */
function tlpd_set_default_spaces() {
	$default = 100;

	return $default;
}

add_filter( 'em_ticket_get_spaces', 'tlpd_set_default_spaces' );

/**
 * URL to member profile
 */
function tlpd_get_my_bookings_url() {
	global $bp;
	if ( ! empty( $bp->events->link ) ) {
		//get member url
		return $bp->events->link;
	} else {
		return '#';
	}
}

/**
 * Fires when there is an update to the web theme version
 *
 */
function tlpd_maybe_update_editor_role() {
	$theme           = wp_get_theme();
	$current_version = $theme->get( 'Version' );
	$last_version    = get_option( 'tlpd_theme_version' );
	if ( version_compare( $current_version, $last_version ) > 0 ) {
		tlpd_wpcodex_set_capabilities();
	}
}

add_action( 'init', 'tlpd_maybe_update_editor_role' );

/**
 * Remove capabilities from editors.
 * will leave the ability to
 * read
 * delete_posts
 * edit_posts
 * upload_files
 * edit_published_pages
 * edit_others_pages
 *
 * Call the function when your plugin/theme is activated.
 */
function tlpd_wpcodex_set_capabilities() {

	// Get the role object.
	$editor = get_role( 'editor' );

	// A list of capabilities to remove from editors.
	$caps = [
		'delete_others_pages',
		'delete_others_posts',
		'delete_pages',
		'delete_private_pages',
		'delete_private_posts',
		'delete_published_pages',
		'delete_published_posts',
		'edit_others_posts',
		'edit_published_posts',
		'edit_pages',
		'edit_private_pages',
		'edit_private_posts',
		'manage_categories',
		'manage_links',
		'moderate_comments',
		'publish_pages',
		'publish_posts',
		'read_private_pages',
		'read_private_posts',
		'unfiltered_html',
	];

	foreach ( $caps as $cap ) {

		// Remove the capability.
		$editor->remove_cap( $cap );
	}
}

/**
 * counts and displays number of events
 * @see http://wp-events-plugin.com/documentation/advanced-usage/
 *
 */
function tlpd_display_count_events() {
	if ( class_exists( 'EM_Events' ) ) {
		$results = EM_Events::get(
			[
				'scope' => 'future',
				'array' => '',
			]
		);
	}

	if ( is_array( isset( $results ) ) ) {
		$num = count( $results );
	} else {
		$num = '';
	}

	echo $num;
}

/**
 * Allow users to upload webp
 */
add_filter(
	'upload_mimes', function ( $mime_types ) {
		$mime_types['webp'] = 'image/webp';

		return $mime_types;
	}
);

/*
|--------------------------------------------------------------------------
| PWA
|--------------------------------------------------------------------------
|
| all functions required for pwa
|
|
*/

define( 'TLPD_MANIFEST_ARG', 'manifest_json' );

/**
 *
 */
add_filter(
	'query_vars', function ( $vars ) {
		$vars[] = TLPD_MANIFEST_ARG;

		return $vars;
	}
);

/**
 * @return string
 */
function tlpd_get_manifest_path() {
	return add_query_arg( TLPD_MANIFEST_ARG, '1', site_url() );
}

/**
 *
 */
add_action(
	'template_redirect', function () {
		global $wp_query;
		if ( $wp_query->get( TLPD_MANIFEST_ARG ) ) {
			$theme_color = '#006338';
			$lang_dir    = ( is_rtl() ) ? 'rtl' : 'ltr';

			$manifest = [
				'start_url'        => get_bloginfo( 'wpurl' ),
				'short_name'       => 'TLPD',
				'name'             => get_bloginfo( 'name' ),
				'description'      => get_bloginfo( 'description' ),
				'display'          => 'standalone',
				'background_color' => $theme_color,
				'theme_color'      => $theme_color,
				'dir'              => $lang_dir,
				'lang'             => get_bloginfo( 'language' ),
				'orientation'      => 'portrait-primary',
				'icons'            => [
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-48.png',
						'sizes' => '48x48',
						'type'  => 'image/png',
					],
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-72.png',
						'sizes' => '72x72',
						'type'  => 'image/png',
					],
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-96.png',
						'sizes' => '96x96',
						'type'  => 'image/png',
					],
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-144.png',
						'sizes' => '144x144',
						'type'  => 'image/png',
					],
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-168.png',
						'sizes' => '168x168',
						'type'  => 'image/png',
					],
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-192.png',
						'sizes' => '192x192',
						'type'  => 'image/png',
					],
					[
						'src'   => get_stylesheet_directory_uri() . '/dist/images/pwa/tlpd-512.png',
						'sizes' => '512x512',
						'type'  => 'image/png',
					],
				],
			];

			wp_send_json( $manifest );
		}
	}, 2
);

/**
 * Add image size for homepage new + noteworthy
 * Images will be cropped to the specified dimensions using center positions.
 *
 */
add_action(
	'after_setup_theme', function () {
		add_image_size( 'featured-size', 340, 135, true );
	}
);

/**
 * Attempts to make a valid url from a string such as: url.ca
 *
 * @param $url
 *
 * @return bool|false|string
 */
function tlpd_maybe_url( $url ) {
	if ( is_null( $url ) ) {
		return false;
	}

	$parts = wp_parse_url( $url );

	// tries to ameliorate 'url.ca' as input to '//url.ca'
	if ( ! isset( $parts['scheme'] ) && ! isset( $parts['host'] ) && isset( $parts['path'] ) ) {
		if ( false !== strpos( $parts['path'], '.' ) ) {
			$url = '//' . $parts['path'];
		}
	}

	$valid = wp_http_validate_url( $url );

	return $valid;
}
