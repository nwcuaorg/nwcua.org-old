<?php
/*
Home/catch-all template
*/

get_header(); ?>

	
	<div class="browse-by">
		<div class="wrap">
			<div class="browse-by-filters">
				<form name="category-filter" action="/" method="get">
				<?php 
				$categories = get_categories( 'exclude=1,1238,36,53,48,42,39,1284,1286,35,50,52,43,55,1282,1278,45,51,31,1239,4,49,20,30,1285,56,33,1276,44,1277,1315,34' );
				$col_break = ceil( count( $categories )/4 );
				$cnt = 1;
				foreach ( $categories as $cat ) {
					if ( $cnt==1 || $cnt==$col_break+1 || $cnt==(($col_break*2)+1) || $cnt==(($col_break*3)+1) ) print '<div class="quarter">';
					print '<label><input type="checkbox" name="category[]" value="' . $cat->term_id . '" /> ' . $cat->name . '</label>';
					if ( $cnt==$col_break || $cnt==$col_break*2 || $cnt==$col_break*3 ) print '</div>';
					$cnt++;
				}
				print "</div>";
				?>
				<div class="filter-button">
					<input type="submit" value="Filter" />
				</div>
				</form>
			</div>
			<div class="group">
				<a href="#" class="browse-by-handle">Browse by Category</a>
			</div>
		</div>
	</div>
	<div id="primary" class="wrap">
		<div id="content" class="site-content content-wide home-list" role="main">
			<?php
			if ( is_search() ) {
				?><h1>Search Results for <span>'<?php print $_REQUEST["s"]; ?>'</span></h1><?php
			}

			$query_args = array(
			    'post_type' => array( 'post', 'page' ),
			    'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
			    'meta_key' => '_p_priority'
			);

			if ( isset( $_GET['category'] ) ) {
				$query_args['cat'] = implode( ',', $_GET['category'] );
			}

			query_posts( $query_args );

			if ( isset( $_GET['p'] ) ) {
				$count = 2;
			} else {
				$count = 1;
			}
			while ( have_posts() ) : the_post();
				?>
				<div class="entry priority-<?php show_cmb_value( 'priority' ); ?>">
					<div class="entry-image">
						<a href="<?php the_permalink() ?>">
							<?php
							$thumbnail_id = get_post_thumbnail_id();
							$thumbnail_url = wp_get_attachment_url( $thumbnail_id );
							if ( !empty( $thumbnail_url ) ) {
								?>
							<img src="<?php print p_image_resize( $thumbnail_url, 800, ( $count==1 ? 600 : 500 ), 1, 1 ); ?>" />
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
				$count++;
			endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>