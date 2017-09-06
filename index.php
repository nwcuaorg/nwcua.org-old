<?php
/*
Blog Template
*/

if ( !is_search() ) {
	$is_anthem = true;
}

get_header(); 

?>

	
	<div id="primary" class="site-content">
		<?php

		global $wp_query;
		$query_args = $wp_query->query;
		$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
		$query_args['meta_key'] = '_p_priority';
		$query_arts['meta_query'] = array(
			array(
				'key'=>'_p_priority',
				'value'=>'-1',
				'compare'=>'!=',
			),
		);

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
							<span class="quiet">Posted in:</span> 
							<div class="post-category bg-<?php print $color; ?>">
								<?php print get_cat_name( $categories[0]->term_id ); ?>
							</div>
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
			<?php 
		} else {
			?>
		<div id="content" class="wrap content-wide home-list" role="main">
			<?php
			$query_args['posts_per_page'] = 14;
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
			<?php 
		} 
	?>
	</div><!-- #primary -->


<?php 

get_footer();

?>