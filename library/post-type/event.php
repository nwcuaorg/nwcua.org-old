<?php


// set the default timezone.
date_default_timezone_set( 'America/Los_Angeles');



// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'flush_rewrite_rules' );



// let's create the function for the custom type
function event_post_type() { 

	// creating (registering) the custom type 
	register_post_type( 'event', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 
			'labels' => array(
				'name' => __( 'Events', 'ptheme' ), /* This is the Title of the Group */
				'singular_name' => __( 'Event', 'ptheme' ), /* This is the individual type */
				'all_items' => __( 'All Events', 'ptheme' ), /* the all items menu item */
				'add_new' => __( 'Add New', 'ptheme' ), /* The add new menu item */
				'add_new_item' => __( 'Add New Event', 'ptheme' ), /* Add New Display Title */
				'edit' => __( 'Edit', 'ptheme' ), /* Edit Dialog */
				'edit_item' => __( 'Edit Event', 'ptheme' ), /* Edit Display Title */
				'new_item' => __( 'New Event', 'ptheme' ), /* New Display Title */
				'view_item' => __( 'View Event', 'ptheme' ), /* View Display Title */
				'search_items' => __( 'Search Event', 'ptheme' ), /* Search Custom Type Title */ 
				'not_found' =>  __( 'Nothing found in the database.', 'ptheme' ), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __( 'Nothing found in Trash', 'ptheme' ), /* This displays if there is nothing in the trash */
				'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'Manage the events listed on the site.', 'ptheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-calendar-alt', /* the icon for the custom post type menu */
			'rewrite'	=> array( 
				'slug' => 'event', 
				'with_front' => false 
			), /* you can specify its url slug */
			'has_archive' => 'events', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'excerpt' )
		) /* end of options */
	); /* end of register post type */

	// register_taxonomy_for_object_type( 'category', 'event' );	
	
}


// adding the function to the Wordpress init
add_action( 'init', 'event_post_type');



// now let's add custom categories (these act like categories)
register_taxonomy( 'event_cat', 
	array( 'event' ), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true, it acts like categories */
		'labels' => array(
			'name' => __( 'Event Categories', 'ptheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'Event Category', 'ptheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search Event Categories', 'ptheme' ), /* search title for taxomony */
			'all_items' => __( 'All Event Categories', 'ptheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Event Category', 'ptheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Event Category:', 'ptheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Event Category', 'ptheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Event Category', 'ptheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Event Category', 'ptheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Event Category Name', 'ptheme' ) /* name title for taxonomy */
		),
		'show_admin_column' => true, 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 
			'slug' => 'events'
		),
		'capabilities' => array(
			'manage_terms' => 'manage_event_cat',
			'edit_terms' => 'edit_event_cat',
			'delete_terms' => 'delete_event_cat',
			'assign_terms' => 'assign_event_cat',
		)
	)
);



// add capabilities
function add_event_caps() {

    // gets the author role
    $role = get_role( 'administrator' );

    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'read_event' );
    $role->add_cap( 'edit_event' );
    $role->add_cap( 'delete_event' );
    $role->add_cap( 'edit_events' );
    $role->add_cap( 'edit_others_events' );
    $role->add_cap( 'publish_events' );
    $role->add_cap( 'read_private_events' );
    $role->add_cap( 'edit_private_events' );
    $role->add_cap( 'edit_published_events' );

}
add_action( 'admin_init', 'add_event_caps');



function get_day_events( $m, $d, $y ) {

	$args = array(
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'relation' => 'AND',
				array(
					'key' => '_p_event_start',
					'value' => mktime( 0, 0, 0, $m, $d, $y ),
					'compare' => '>='
				),
				array(
					'key' => '_p_event_start',
					'value' => mktime( 23, 59, 59, $m, $d, $y ),
					'compare' => '<='
				)
			),
			array(
				'relation' => 'AND',
				array(
					'key' => '_p_event_end',
					'value' => mktime( 0, 0, 0, $m, $d, $y ),
					'compare' => '>='
				),
				array(
					'key' => '_p_event_end',
					'value' => mktime( 23, 59, 59, $m, $d, $y ),
					'compare' => '<='
				)
			),
			array(
				'relation' => 'AND',
				array(
					'key' => '_p_event_start',
					'value' => mktime( 0, 0, 0, $m, $d, $y ),
					'compare' => '<='
				),
				array(
					'key' => '_p_event_end',
					'value' => mktime( 23, 59, 59, $m, $d, $y ),
					'compare' => '>='
				)
			)
		),
		'post_type' => 'event',
		'orderby' => 'name',
		'posts_per_page' => 100
	);

	$event_query = new WP_Query( $args );
	$events = $event_query->get_posts();

	wp_reset_query();
	
	return $events;

}



function get_month_events( $m, $y, $category='' ) {

	$timestamp_start = mktime( 0, 0, 0, $m, 1, $y );
	$timestamp_end = mktime( 23, 59, 59, $m, date( 't', $timestamp_start ), $y );
	$timestamp_today = time();

	$args = array(
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_p_event_start',
				'value' => $timestamp_today,
				'compare' => '>='
			),
			array(
				'key' => '_p_event_start',
				'value' => $timestamp_start,
				'compare' => '>='
			),
			array(
				'key' => '_p_event_start',
				'value' => $timestamp_end,
				'compare' => '<='
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'event_cat',
				'field'    => 'slug',
				'terms'    => 'exclude',
				'operator' => 'NOT IN',
			),
		),
		'post_type' => 'event',
		'orderby' => 'name',
		'posts_per_page' => 100
	);

	if ( isset( $_GET['event_category'] ) ) {
		if ( $_GET['event_category'] != 0 ) {
			$event_cat = get_term( $_GET['event_category'], 'event_cat' );
			$args[ 'event_cat' ] = $event_cat->slug;
		} else {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_cat',
					'field'    => 'slug',
					'terms'    => 'exclude',
					'operator' => 'NOT IN',
				),
			);
		}
	}

	$event_query = new WP_Query( $args );
	$events = $event_query->get_posts();

	foreach ( $events as $key => $event ) {
		$event_info = array();
		$event_info = get_post_custom( $event->ID );

		foreach ( $event_info as $info_key => $info_item ) {		
			$events[$key]->$info_key = $info_item[0];
		}
	}

	wp_reset_query();
	
	return $events;

}



function get_upcoming_events( $limit, $category=0 ) {


	$timestamp_start = mktime( 0, 0, 0 );

	$args = array(
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_p_event_start',
				'value' => $timestamp_start,
				'compare' => '>='
			)
		),
		'post_type' => 'event',
		'orderby' => 'meta_value_num',
		'meta_key' => '_p_event_start',
		'order' => 'ASC',
		'posts_per_page' => $limit
	);

	if ( !empty( $category ) ) {
		$categories = explode( ',', $category );
		if ( is_string( $categories[0] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_cat',
					'field' => 'slug',
					'terms' => $categories
				)
			);
		} else {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_cat',
					'field' => 'id',
					'terms' => $categories
				)
			);
		}
	} else {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'event_cat',
				'field'    => 'slug',
				'terms'    => 'exclude',
				'operator' => 'NOT IN',
			),
		);
	}

	$event_query = new WP_Query( $args );
	$events = $event_query->get_posts();

	foreach ( $events as $key => $event ) {
		$event_info = array();
		$event_info = get_post_custom( $event->ID );

		foreach ( $event_info as $info_key => $info_item ) {		
			$events[$key]->$info_key = $info_item[0];
		}
	}



	wp_reset_query();
	
	return $events;

}



function get_previous_month( $month, $year ) {
	if ( $month == 1 ) {
		return array( 'month' => 12, 'year' => $year-1 );
	} else {
		return array( 'month' => $month-1, 'year' => $year );
	}
}



function get_next_month( $month, $year ) {
	if ( $month == 12 ) {
		return array( 'month' => 1, 'year' => $year+1 );
	} else {
		return array( 'month' => $month+1, 'year' => $year );
	}
}



// show month events
function show_month_events( $month, $year ) {

	$event_list_url = "/events";

	// let's make an empty calendar
	$calendar = '';

	// get the events for the month.
	$events = get_month_events( $month, $year );

	// show next and previous month links.
	$prev = get_previous_month( $month, $year );
	$prev_ts = mktime( 0, 0, 0, $prev['month'], 1, $prev['year'] );
	$next = get_next_month( $month, $year );
	$next_ts = mktime( 0, 0, 0, $next['month'], 1, $next['year'] );
	$calendar .= '<a data-month="' . $prev['month'] . '" data-year="' . $prev['year'] . '" class="month-nav previous">&laquo; ' . date( "F", $prev_ts ) . '</a>';
	$calendar .= '<a data-month="' . $next['month'] . '" data-year="' . $next['year'] . '" class="month-nav next">' . date( "F", $next_ts ) . ' &raquo;</a>';

	// add month title
	$calendar .= '<h2 class="calendar-month-title">' . date( 'F Y', mktime( 0, 0, 0, $month, 1, $year ) ) . "</h2>";

	// open the table tags
	$calendar .= '<table cellpadding="0" cellspacing="0" class="calendar">';

	// day titles
	$headings = array('Sun<span>day</span>','Mon<span>day</span>','Tue<span>sday</span>','Wed<span>nesday</span>','Thu<span>rsday</span>','Fri<span>day</span>','Sat<span>urday</span>');
	$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings ) . '</td></tr>';

	// days and weeks vars now ...
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	// row for week one
	$calendar .= '<tr class="calendar-row">';

	// print "blank" days until the first of the current week
	for ( $x = 0; $x < $running_day; $x++ ) :
		$calendar .= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	// keep going with days....
	for ( $list_day = 1; $list_day <= $days_in_month; $list_day++ ) :

		// let's check the start and end of the day
		$day_start = mktime( 0, 0, 0, $month, $list_day, $year );
		$day_end = mktime( 23, 59, 59, $month, $list_day, $year );

		// loop through all the events and list them for this day.
		$day_events = '';
		foreach ( $events as $event ) {
			if ( ( $event->_p_event_start > $day_start && $event->_p_event_start < $day_end ) || 
				 ( $event->_p_event_end > $day_start && $event->_p_event_end < $day_end ) || 
				 ( $event->_p_event_start < $day_start && $event->_p_event_end > $day_end ) ) {
				$day_events .= "<div class='event'><div class='event-title'><a href=\"" . ( !empty( $event->_p_event_website ) ? $event->_p_event_website : get_permalink( $event->ID ) ) . "\">" . $event->post_title . "</a></div><div class='event-time'>" . date( "n/j g:i a", $event->_p_event_start ) . " - " . date( "g:i a", $event->_p_event_end ) . "</div><div class='event-description'>" . $event->post_excerpt . "</div></div>";
			}
		}

		// start building out the day.
		$calendar .= '<td class="calendar-day">';

		// add in the day number 
		$calendar.= '<div class="day-number">' . ( !empty( $day_events ) ? "<strong>" : '' ) . $list_day . ( !empty( $day_events ) ? "</strong>" : '' ) . '</div>';

		// start listing events in their own div
		$calendar .= '<div class="day-events">';

		// loop through all the events and list them for this day.
		$calendar .= $day_events;

		// close the day list
		$calendar .= '</div>';
		
		// close the table cell
		$calendar.= '</td>';

		// end row if it's the end of the week.
		if ( $running_day == 6 ) :
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;

	endfor;

	// finish the rest of the days in the week
	if ( $days_in_this_week < 8 ) :
		for ( $x = 1; $x <= ( 8 - $days_in_this_week ); $x++ ) :
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	// end final row
	$calendar.= '</tr>';

	// close the table
	$calendar.= '</table>';

	// add an empty div to populate event list into (for use on mobile).
	$calendar .= '<div class="day-event-list"></div>';
	
	/* all done, return result */
	print $calendar;

}



// get a list of event categories in an array
function get_event_categories() {
    $args = array(
		'orderby'            => 'name',
		'order'              => 'ASC',
		'number'             => null,
		'echo'               => 0,
		'taxonomy'           => 'event_cat',
    );
    return get_categories( $args ); 
}



// filter events by category using a dropdown menu
function filter_by_event_type() {

	wp_dropdown_categories( 
		array(
			'show_option_all' => 'All Event Categories',
			'orderby' => 'NAME', 
			'taxonomy' => 'event_cat',
			'class' => 'event-category',
			'exclude' => '7756',
			'hierarchical' => 1,
			'depth' => 1,
			'selected' => ( isset( $_GET['event_category'] ) ? $_GET['event_category'] : 0 )
		) 
	);

}



// returns a duration from start and end timestamps
function duration( $start, $end ) {
	// get duration in seconds
	$duration_seconds = $end - $start;

	// calculate days, then hours, then minutes
	$days = floor( $duration_seconds / 86400 );
	$hours = floor( ( $duration_seconds - ( $days * 86400 ) ) / 3600 );
	$minutes = floor( ( $duration_seconds - ( $days * 86400 ) - ( $hours * 3600 ) ) / 60 );

	// build a time string
	$time_string_parts = array();
	if ( $days > 0 ) $time_string_parts[] = $days . ' day' . ( $days > 1 ? 's' : '' );
	if ( $hours > 0 ) $time_string_parts[] = $hours . ' hour' . ( $hours > 1 ? 's' : '' );
	if ( $minutes > 0 ) $time_string_parts[]= $minutes . ' minute' . ( $minutes > 1 ? 's' : '' );

	// return it.
	return implode( ", ", $time_string_parts );
}



// set the event columns for the event custom post type
add_filter( 'manage_edit-event_columns', 'edit_event_columns' ) ;
function edit_event_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event' ),
		'start' => __( 'Starts' ),
		'end' => __( 'Ends' ),
		'category' => __( 'Category' ),
	);

	return $columns;
}



// add some post clauses to select more fields when we get events.
add_filter( 'posts_clauses', 'manage_event_clauses', 1, 2 );
function manage_event_clauses( $pieces, $query ) {
	global $wpdb;

	/**
	* We only want our code to run in the main WP query
	* AND if an orderby query variable is designated.
	*/
	if ( $query->get( 'post_type' ) == 'event' && $query->get( 'orderby' ) == 'event_cat' ) {

		// Get the order query variable - ASC or DESC
		$order = strtoupper( $query->get( 'order' ) );

		// Make sure the order setting qualifies. If not, set default as ASC
		if ( $order != 'ASC' ) $order = 'DESC';

		// join category name
		$pieces[ 'join' ] .= " LEFT JOIN $wpdb->term_relationships wp_termrel ON wp_termrel.object_id = {$wpdb->posts}.ID ";
		$pieces[ 'join' ] .= " LEFT JOIN $wpdb->term_taxonomy wp_termtax ON wp_termrel.term_taxonomy_id = wp_termtax.term_id ";
		$pieces[ 'join' ] .= " LEFT JOIN $wpdb->terms wp_terms ON wp_terms.term_id = wp_termtax.term_id ";
		
		//
		$pieces[ 'orderby' ] = "wp_terms.name $order";

	}

	return $pieces;

}



// add content to custom event admin listing columns
add_action( 'manage_event_posts_custom_column', 'manage_event_columns', 10, 2 );
function manage_event_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'duration' column. */
		case 'start' :

			/* Get the post meta. */
			$start = get_post_meta( $post_id, '_p_event_start', true );

			/* If no duration is found, output a default message. */
			if ( empty( $start ) )
				echo __( '-' );

			/* If there is a duration, append 'minutes' to the text string. */
			else
				printf( date( 'n/j/Y @ g:ia', $start ) );

			break;

		/* If displaying the 'duration' column. */
		case 'end' :

			/* Get the post meta. */
			$end = get_post_meta( $post_id, '_p_event_end', true );

			/* If no duration is found, output a default message. */
			if ( empty( $end ) )
				echo __( '-' );

			/* If there is a duration, append 'minutes' to the text string. */
			else
				printf( date( 'n/j/Y @ g:ia', $end) );

			break;

		/* If displaying the 'genre' column. */
		case 'category' :

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'event_cat' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'event_cat' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'event_cat', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Category' );
			}

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}



// event shortcode
function event_shortcode( $event_atts ) {

	// set shortcode defaults
	$a = shortcode_atts( array(
		'limit' => 5,
		'category' => 0,
		'show_excerpt' => 0,
	), $event_atts );


	// get the events
	$events = get_upcoming_events( $a['limit'], $a['category'] );

	// start an empty event.
	$list = '';

	// list the events
	if ( !empty( $events ) ) {
		$list .= '<div class="event-list">';
		foreach ( $events as $event ) {
			$list .= '<div class="event-item">';
			$list .= '<h5><a href="' . get_permalink( $event->ID ) . '">' . $event->post_title . '</a></h5>';
			$list .= '<span class="quiet">' . date( 'F jS, Y', $event->_p_event_start ) . '</span>';
			if ( $a['show_excerpt'] ) {
				$list .= $event->post_excerpt;
			}
			$list .= '</div>';
		}
		$list .= '</div>';
	}

	return $list;

}
add_shortcode( 'event-list', 'event_shortcode' );



// Creating the widget 
class event_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'events_widget', 

			// Widget name will appear in UI
			__('Upcoming Events', 'events_domain'), 

			// Widget description
			array( 'description' => __( 'Display a list of calendar events in the sidebar.', 'events_domain'), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {

		// if admin selected a number of posts, use that. default to 5.
		$limit = ( !empty( $instance['limit'] ) ? $instance['limit'] : 5 );
		
		// use any supplied category, or empty for all.
		$category = ( !empty( $instance['category'] ) ? $instance['category'] : 0 );

		// get the events
		$events = get_upcoming_events( $limit, $category );

		// open the ripon widget
		echo $args['before_widget'];

		// before and after widget arguments are defined by themes
		echo $args['before_title'] . ( !empty( $instance['title'] ) ? $instance['title'] : "Upcoming Events" ) . $args['after_title'];

		// list the events
		if ( !empty( $events ) ) {
			foreach ( $events as $event ) {
				print '<h4><a href="' . get_permalink( $event->ID ) . '">' . $event->post_title . '</a></h4>';
				print date( 'F jS, Y', $event->_p_event_start );
			}
		}

		// close the widget tag
		echo $args['after_widget'];
	}
			

	// Widget Backend 
	public function form( $instance ) {
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'limit' ] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label> 
			<?php 	
			wp_dropdown_categories( array(
				'show_option_all' => 'All Event Categories',
				'orderby' => 'NAME', 
				'taxonomy' => 'post_cat',
				'class' => 'event-category',
				'id' => $this->get_field_id( 'category' ),
				'name' => $this->get_field_name( 'category' ),
				'selected' => $instance['category'],
				'hierarchical' => true
			) );
			?>
		</p>
		<hr>
		<?php
	}
	

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance[ 'title' ] = $new_instance[ 'title' ];
		$instance[ 'limit' ] = $new_instance[ 'limit' ];
		$instance[ 'category' ] = $new_instance[ 'category' ];

		return $instance;
	}

}



// Register and load the widget
function register_events_widget() {
	register_widget( 'event_widget' );
}
add_action( 'widgets_init', 'register_events_widget' );



// enable sortable columns for event post type
add_filter("manage_edit-event_sortable_columns", 'edit_event_sort');
function edit_event_sort($columns) {
	$custom = array(
		'start' 	=> '_p_event_start',
		'end' 		=> '_p_event_end',
		'category'	=> 'event_cat'
	);
	return wp_parse_args($custom, $columns);
}



// add the event data to the RSS feed for event post types.
function rss_event_date() {
	global $post;
	if ( $post->post_type == 'event' ) {
		print "<eventDate>" . date( 'r', get_post_meta( $post->ID, CMB_PREFIX . 'event_start', 1 ) ) . "</eventDate>";
	}
}
add_action( 'rss2_item', 'rss_event_date' );



// hook into feed and sort/limit event post type by event date.
function rss_event_sort( $query ) {
	if ( $query->is_feed && ( !empty( $query->get('event_cat') ) || $query->get('post_type')=='event' ) ) {
		$query->set('orderby','meta_value');
		$query->set('meta_key', CMB_PREFIX . 'event_start');
		$query->set('order','ASC');
		$query->set('posts_per_page','30');
		$query->set('meta_value',mktime());
		$query->set('meta_compare','>=');
	}
	return $query;
}
add_filter( 'pre_get_posts', 'rss_event_sort' );



?>