<?php
/**
 * The template for displaying Archive pages
 */

get_header(); 

$category = get_the_category(); 
$color = get_category_color( $category[0]->cat_ID );

?>
	<div class="large-title bg-<?php print $color ?>">
		<div class="wrap">
			<div class="large-title-icon bg-<?php print $color ?>">
				<img src="/wp-content/uploads/2011/12/iconnwcua.png">
			</div>
			<div class="large-title-text">
				<h1><?php single_cat_title(); ?></h1>
			</div>
		</div>
	</div>

	<section id="primary" class="wrap content-wide group home-list" role="main">

		<?php
		global $wp_query;
		$query_args = $wp_query->query;
		$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
		$query_args['meta_key'] = '_p_priority';
		$query_args['posts_per_page'] = 14;
		query_posts( $query_args );

		if ( have_posts() ) : 
		
			// Start the Loop.
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
							<img src="<?php print p_image_resize( $thumbnail_url, 800, 500, 1, 1 ); ?>" />
								<?php
							}

							//the_post_thumbnail( 'large' ); 

							$categories = get_the_category();
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
			endwhile;

		else :
			// If no content, include the "No posts found" template.
			get_template_part( 'content', 'none' );

		endif;
		?>
		
		<div class="pagination group">
			<?php pagination(); ?>
		</div>

	</section><!-- #primary -->

<?php

get_footer();

?>