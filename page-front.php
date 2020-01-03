<?php
/*
Template Name: Front Page
*/

get_header();


// get the current user, and their category preferences.
$the_current_user = get_current_user_id();
$user_categories = get_user_meta( $the_current_user, 'categories', 1 );


// if we have a category parameter
if ( isset( $_REQUEST['category'] ) ) {

	// split it into an array of ids
	$current_categories = explode( '-', $_REQUEST['category'] );

	// also, update the user preference with the string from the url
	if ( !empty( $the_current_user ) ) update_user_meta( $the_current_user, 'categories', $_REQUEST['category'] );

	// set a session variable to store current session category preferences
	$_SESSION['user_categories'] = $_REQUEST['category'];

} else if ( !empty( $user_categories ) ) {

	// use the user categories if we don't have a request
	$current_categories = explode( '-', $user_categories );

} else if ( isset( $_SESSION['user_categories'] ) ) {

	// use the session variable if we don't have a request and user isn't logged in.
	$current_categories = explode( '-', $_SESSION['user_categories'] );

}

?>

	<div class="browse-by">
		<div class="wrap">
			<div class="browse-by-filters">
				<form name="category-filter" action="/" method="get">
				<div class="quarter">
				<?php 
				$categories = get_categories( 'exclude=1,4,20,30,31,33,34,36,38,39,42,43,44,45,47,48,49,50,51,53,54,56,1238,1276,1277,1278,1282,1284,1285,1286,1315,7571,7580,7581,7582,7583,7585,7586,7588,7589,7590,7592,7657,7666,7677,7850,7851,7852,7853,7900' );
				$col_break = ceil( count( $categories )/4 );
				$cnt = 1;
				foreach ( $categories as $cat ) {
					print '<label><input type="checkbox" name="category[]" value="' . $cat->term_id . '" '. ( isset( $current_categories ) ? ( in_array( $cat->term_id, $current_categories ) ? 'checked ' : '' ) : '' ) . '/> ' . $cat->name . '</label>';
					if ( $cnt==$col_break || $cnt==$col_break*2 || $cnt==$col_break*3 ) print '</div><div class="quarter">';
					$cnt++;
				}
				print "</div>";
				?>
				<div class="filter-button quarter right">
					<input type="submit" value="Filter" />
				</div>
				</form>
			</div>
			<div class="group">
				<a href="#" class="browse-by-handle">Browse by Category</a>
			</div>
		</div>
	</div>
	<div id="primary" class="site-content">
		<?php

		// if it's a search, display the search term.
		if ( is_search() ) {
			?><h1>Search Results for <span>'<?php print $_REQUEST["s"]; ?>'</span></h1><?php
		}


		// get the events
		// $events = get_upcoming_events( 3, ( isset( $_GET['category'] ) ? implode( ',', $_GET['category'] ) : 0 ) );
		$events = get_upcoming_events( 3 );


		// get existing query to work from.
		global $wp_query;
		$query_args = $wp_query->query;


		// set up our query arguments
		$query_args['post_status'] = 'publish';
		$query_args['post_type'] = array( 'post', 'page' );
		$query_args['meta_key'] = '_p_priority';
		$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
		$query_args['posts_per_page'] = ( !empty( $events ) ? 13 : 14 );
		$query_args['category__not_in'] = array( '7850', '7851', '7852', '7853', '7589', '7590', '7900' );
		$query_args['meta_query'] = array(
			array(
				'key'=>'_p_priority',
				'value'=>'-1',
				'compare'=>'!='
			),
		);


		// if there was a category set in the arguments
		if ( isset( $current_categories ) ) {
			$query_args['cat'] = implode( ',', $current_categories );
		}


		// handle paginating results
		$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
		$query_args['paged'] = $paged;


		//query the posts with the supplied arguments
		$wp_query = new WP_Query( $query_args );


		?>
		<div id="content" class="wrap content-wide home-list" role="main">
			<?php
			if ( $wp_query->have_posts() ) {
				$count = 1;
				while ( $wp_query->have_posts() ) : $wp_query->the_post();
					global $post;
					?>
					<div class="entry priority-<?php show_cmb_value( 'priority' ); ?>">
						<div class="entry-image">
							<?php edit_post_link( 'Edit' ); ?>
							<a href="<?php the_permalink() ?>">
								<?php
								// get categories
								$categories = get_the_category();

								// get thumbnail url
								$thumbnail_id = get_post_thumbnail_id();
								//$thumbnail_url = wp_get_attachment_url( $thumbnail_id );
								$thumbnail_url = get_the_post_thumbnail_url( $post, 'large' );
								// $thumbnail_info = get_the_post_thumbnail( $post, 'large' );
								// print_r( $thumbnail_info ); die;

								$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );


								if ( !empty( $thumbnail_url ) ) {
									?>
								<img src="<?php print $thumbnail_url; ?>" alt="<?php print $image_alt; ?>" />
									<?php
								} else {
									?>
								<img src="<?php print get_default_thumbnail( $categories[0]->term_id ); ?>" />
									<?php
								}

								if ( !empty( $categories ) ) { 
									$color = get_category_color( $categories[0]->term_id );
									?>
								<div class="post-category bg-<?php print $color; ?>">
									<?php print get_cat_name( $categories[0]->term_id ); ?>
								</div>
									<?php
								}

								?>
							</a>
						</div>
						<div class="description">
							<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
							<?php the_excerpt(); ?>
						</div>
					</div>
					<?php
					if ( $count == 1 && !empty( $events ) ) {						

						print "<div class='entry'>";
						print "<div class='description home-events'>";
						// print "<h3><a href='https://nwcua.org/wp-content/uploads/2019/06/NWCUA_eventhandout_0319_Digital-1.pdf?v=1' style='text-decoration: underline;'>2019 Events</a></h3>";
						print "<h3><a href='/events/'>Upcoming Events</a></h3>";
						// list the events
						print "<div class='event-list'>";
						foreach ( $events as $event ) {
							print '<h4><a href="' . get_permalink( $event->ID ) . '">' . $event->post_title . '</a></h4>';
							print "<span class='date'>" . date( 'n/j/Y g:ia', $event->_p_event_start ) . "</span>";
						}
						print "</div>";
						print "</div>";
						print "</div>";
						$count++;

					}
					$count++;
				endwhile;
			} else {
				print "<p>Sadly, there is no content to show for these categories. Please try another.</p>";
			}
			?>
		</div><!-- #content -->
		
		<div class="pagination group">
			<?php pagination(); ?>
		</div>
	</div><!-- #primary -->

<?php 

get_footer();

?>