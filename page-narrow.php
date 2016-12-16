<?php
/*
Template Name: 1-Column Narrow
*/

get_header();

?>

	<?php the_large_title(); ?>

	<?php the_showcase(); ?>
	
	<div id="content" class="wrap group content-narrow" role="main">
		<?php 
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); 
				the_content();
			endwhile;
		endif;
		?>
	</div><!-- #content -->

	<?php if ( has_partner_or_product_accordion() ) { ?>
	<div class="group">
		<?php the_accordion(); ?>
	</div>
	<?php } ?>

<?php

get_footer();

?>