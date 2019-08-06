<?php

get_header();

?>

	<?php the_large_title(); ?>

	<?php the_showcase(); ?>

	<?php 
	if ( have_posts() ) :
		while ( have_posts() ) : the_post(); 
			global $post;
			?>
	<div id="content" class="wrap group site-content content-two-column content-style" role="main">
		<?php
		if ( is_member() ) {
			if ( !empty( $post->post_excerpt ) ) { ?>
			<div class="content-header">
				<h2><?php the_excerpt(); ?></h2>
			</div>
			<div class="content-top">
				<?php print apply_filters( 'the_content', get_cmb_value( 'top_content' ) ); ?>
			</div>
			<?php } ?>
			<div class="three-quarter right">
				<?php
				the_content();

				the_accordion();
				?>
			</div>
			<div class="quarter sidebar right">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-generic') ) : ?><!-- no sidebar --><?php endif; ?>
			</div>
			<?php
		} else {
			do_member_error();
		}
		?>
	</div><!-- #content -->
		<?php
		endwhile;
	endif;
	?>

<?php

get_footer();

?>