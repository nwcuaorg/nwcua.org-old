<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

$is_anthem = true;

get_header(); 

$color = get_category_color( $cat );
$cat_desc = category_description();

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

	<section id="primary" class="content-area">
		<?php if ( !empty( $cat_desc ) ) { ?>
		<div class="wrap content-wide category-description">
			<?php print $cat_desc; ?>
		</div>
		<?php } ?>
		<div id="content" class="wrap content-wide home-list" role="main">
		<?php
		// get global wp_query
		global $wp_query;
		$query_args = $wp_query->query;


		// set our query arguments
		$query_args['orderby'] = array( 'meta_value_num' => 'DESC', 'date' => 'DESC' );
		$query_args['posts_per_page'] = 14;
		$query_args['meta_key'] = '_p_priority';
		$query_args['meta_query'] = array(
			array(
				'key'=>'_p_priority',
				'value'=>'-1',
				'compare'=>'!=',
			),
		);


		// handle paginating results
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$query_args['paged'] = $paged;


		// run the query
		query_posts( $query_args );


		if ( have_posts() ) { 
		
			// Start the Loop.
			$count = 0;
			while ( have_posts() ) : the_post(); 
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
						<img src="<?php print $thumbnail_url; ?>" />
							<?php
						}

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
				$count++;
			endwhile;


		} else {
			print "<p>No stories have been added to this category yet. Check back soon for more!</p>";
		}
		?>
			
		</div><!-- .home-list -->
		</div><!-- #content -->
		
		<div class="pagination group">
			<?php pagination(); ?>
		</div>
	</section><!-- #primary -->

<?php

get_footer();

?>