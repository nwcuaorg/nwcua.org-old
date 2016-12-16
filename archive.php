<?php
/**
 * The template for displaying Archive pages
 */

get_header(); 

?>
	<div class="large-title bg-<?php show_cmb_value( 'large-title-color' ) ?>">
		<div class="wrap">
			<?php if ( has_cmb_value( 'large-title-icon' ) ) { ?>
			<div class="large-title-icon bg-<?php show_cmb_value( 'large-title-color' ) ?>">
				<img src="<?php show_cmb_value( 'large-title-icon' ) ?>">
			</div>
			<?php } ?>
			<div class="large-title-text">
				<h1><?php single_cat_title(); ?></h1>
			</div>
		</div>
	</div>

	<section id="primary" class="content-area wrap group" role="main">

		<?php if ( have_posts() ) : ?>
		<?php
		
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
			endwhile;

		else :
			// If no content, include the "No posts found" template.
			get_template_part( 'content', 'none' );

		endif;
		?>

	</section><!-- #primary -->

<?php

get_footer();

?>