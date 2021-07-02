<?php
/*
Blog Template
*/

if ( !is_search() ) {
	$is_anthem = true;
}

get_header(); 

?>

	
		<?php

		global $wp_query;
		$query_args = $wp_query->query;
		$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
		$query_args['meta_key'] = '_p_priority';

		// if it's a search, display the search term.
		if ( is_search() ) {
			$query_args['post_type'] = ( isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : array( 'post', 'page', 'event', 'job' ) );
			$query_args['posts_per_page'] = 40;
			query_posts( $query_args );

			if ( $paged > 0 ) {
				$result_range_start = ( ( $paged - 1 ) * 40 ) + 1;
				$result_range_end = ( $result_range_start + 39 );
				if ( $wp_query->found_posts > $result_range_end ) {
					$result_range = $result_range_start . ' - ' . $result_range_end; 
				} else {
					$result_range = $result_range_start . ' - ' . $wp_query->found_posts;
				}
			} else {
				if ( $wp_query->found_posts > 40 ) {
					$result_range = '1 - 40';
				} else {
					$result_range = '1 - ' . $wp_query->found_posts;
				}
			}

			?>
	<div id="primary" class="site-content">
		<div class="large-title bg-grey-dark">
			<div class="wrap">
				<div class="large-title-icon bg-grey-dark">
					<img src="/wp-content/uploads/2011/12/iconnwcua.png">
				</div>
				<div class="large-title-text">
					<h1>Search: <span>'<?php print htmlspecialchars( $_REQUEST["s"] ); ?>'</h1>
				</div>
			</div>
		</div>
		<div id="content" class="wrap content-wide search-list" role="main">
			<?php include( 'searchform-advanced.php' ); ?>
			<hr />
			<div class="quiet total-results">
				Found <strong><?php echo $wp_query->found_posts; ?></strong> total results. Showing results <strong><?php print $result_range; ?></strong>.
			</div>
			<?php
			if ( have_posts() ) {
				$count = 1;
				while ( have_posts() ) : the_post();
					?>
					<div class="entry priority-<?php show_cmb_value( 'priority' ); ?> <?php print $post->post_type ?>">
						<div class="entry-image">
							<a href="<?php the_permalink() ?>">
							</a>
						</div>
						<div class="description">
							<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
							<?php 

							// strip tags from the excerpt and output it.
							echo wpautop( wp_trim_words( strip_tags( get_the_excerpt() ), 50 ) ); 
							
							$categories = get_the_category();
							if ( !empty( $categories ) ) { 
								$color = get_category_color( $categories[0]->term_id );
								?>
							<span class="quiet">Posted in </span> 
							<div class="post-category bg-<?php print $color; ?>">
								<?php print get_cat_name( $categories[0]->term_id ); ?>
							</div>
							<span class="quiet">on <strong><?php print get_the_date( 'F jS, Y' ); ?></strong></span>
								<?php
							}

							?>
						</div>
					</div>
					<?php
					$count++;
				endwhile;
				?>
				<?php
			} else {
				print "<p>Sadly, your search returned no results. Please try another or navigate using the main menu.</p>";
			}
			?>
		</div><!-- #content -->
		<div class="pagination">
			<?php pagination(); ?>
		</div>
	</div><!-- #primary -->
			<?php 
		} else {

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
		<div id="content" class="wrap content-wide home-list" role="main">
			<?php

			// get the events
			// $events = get_upcoming_events( 3, ( isset( $_GET['category'] ) ? implode( ',', $_GET['category'] ) : 0 ) );
			$events = get_upcoming_events( 3 );

			// additional post query adjustments
			$query_args['post_type'] = array( 'post' );
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

			$query_args['posts_per_page'] = ( !empty( $events ) ? 13 : 14 );
			query_posts( $query_args );
			if ( have_posts() ) {
				$count = 1;
				while ( have_posts() ) : the_post();
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
								$thumbnail_url = wp_get_attachment_url( $thumbnail_id );
								if ( !empty( $thumbnail_url ) ) {
									?>
								<img src="<?php print $thumbnail_url; ?>" />
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
						// print "<h3><a href='https://nwcua.org/wp-content/uploads/2018/03/2018-NWCUA-Events-Handout.pdf' style='text-decoration: underline;'>2018 Events Calendar</a></h3>";
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
		} 
	?>


<?php 

get_footer();

?>