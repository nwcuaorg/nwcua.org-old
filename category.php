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

get_header(); 

?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content content-wide home-list" role="main">

			<?php
			if ( have_posts() ) {
				$count = 1;

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

			} else {
				print "<p>Sadly, there is no content to show for this categories. Please try another.</p>";
			}
			?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php

get_footer();

?>