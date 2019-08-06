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
		if ( is_member() ) {
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); 
					global $post;
					the_content();

					the_accordion();
				endwhile;
			endif;
		} else {
			do_member_error();
		}
		?>
	</div><!-- #content -->

<?php

get_footer();

?>