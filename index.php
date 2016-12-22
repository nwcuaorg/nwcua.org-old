<?php
/*
Home/catch-all template
*/

get_header(); ?>

	
	<div class="browse-by">
		<div class="wrap">
			<div class="browse-by-filters">
				<form name="category-filter" action="/" method="get">
				<div class="quarter">
				<?php 
				$categories = get_categories( 'exclude=1,1238,36,53,48,42,39,1284,1286,35,50,52,43,55,1282,1278,45,51,31,1239,4,49,30,1285,56,33,1276,44,1277,1315,34,47,7582,20,54' );
				$col_break = ceil( count( $categories )/4 );
				$cnt = 1;
				foreach ( $categories as $cat ) {
					print '<label><input type="checkbox" name="category[]" value="' . $cat->term_id . '" /> ' . $cat->name . '</label>';
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
		$events = get_upcoming_events( 3, 0 );


		// get existing query to work from.
		global $wp_query;
		$query_args = $wp_query->query;


		// set up our query arguments
		$query_args['post_status'] = 'publish';
		$query_args['post_type'] = array( 'post', 'page' );
		$query_args['meta_key'] = '_p_priority';
		$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
		$query_args['posts_per_page'] = ( !empty( $events ) ? 13 : 14 );


		// if there was a category set in the arguments
		if ( isset( $_GET['category'] ) ) {
			$query_args['cat'] = ( isset( $_GET['category'] ) ? implode( ',', $_GET['category'] ) : 0 );
		}


		//query the posts with the supplied arguments
		$home_query = new WP_Query( $query_args );


		?>
		<div id="content" class="wrap content-wide home-list" role="main">
			<?php
			if ( $home_query->have_posts() ) {
				$count = 1;
				while ( $home_query->have_posts() ) : $home_query->the_post();
					?>
					<div class="entry priority-<?php show_cmb_value( 'priority' ); ?>">
						<div class="entry-image">
							<?php edit_post_link( 'Edit' ); ?>
							<a href="<?php the_permalink() ?>">
								<?php
								$thumbnail_id = get_post_thumbnail_id();
								$thumbnail_url = wp_get_attachment_url( $thumbnail_id );
								if ( !empty( $thumbnail_url ) ) {
									?>
								<img src="<?php print p_image_resize( $thumbnail_url, 800, 500, 1, 1 ); ?>" />
									<?php
								}

								//the_post_thumbnail( 'large' ); 

								$categories = get_the_category();
								if ( !empty( $categories ) ) { ?>
								<div class="post-category cat-<?php print $categories[0]->term_id; ?>">
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
						print "<h3>Upcoming Events</h3>";
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