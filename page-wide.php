<?php

/*
Template Name: 1-Column Wide
*/

get_header();

?>

	<?php the_large_title(); ?>

	<?php the_showcase(); ?>
	
	<div id="content" class="wrap group site-content content-wide content-style" role="main">
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
	</div><!-- #content -->

<?php

get_footer();

?>