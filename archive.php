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
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php the_excerpt(); ?>
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