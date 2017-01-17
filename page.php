<?php

get_header();

?>

	<?php the_large_title(); ?>

	<?php the_showcase(); ?>
	
	<div id="content" class="wrap group site-content content-two-column content-style" role="main">
		<div class="content-header">
			<h2><?php the_excerpt(); ?></h2>
		</div>
		<div class="three-quarter right">
			<?php 
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); 
					global $post;
					the_content();

					if ( members_can_current_user_view_post( $post->ID ) ) {
						the_accordion();
					}
				endwhile;
			endif;
			?>
		</div>
		<div class="quarter sidebar right">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-generic') ) : ?><!-- no sidebar --><?php endif; ?>
		</div>
	</div><!-- #content -->

<?php

get_footer();

?>