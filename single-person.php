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
	<div id="content" class="wrap group content-area content-two-column <?php print $color; ?> content-style" role="main">
		<?php if ( !empty( $post->post_excerpt ) ) { ?>
		<div class="content-header">
			<h2><?php the_excerpt(); ?></h2>
		</div>
		<?php } ?>
		<div class="quarter sidebar right person-sidebar">
			<img src="<?php print get_the_post_thumbnail_url( null, 'square' ) ?>" />
			<h5>Phone:</h5>
			<p class="person-value"><?php show_cmb_value( 'person_phone' ); ?></p>
			<?php if ( has_cmb_value( 'person_phone_tf') ) { ?>
			<h5>Toll Free:</h5>
			<p class="person-value"><?php show_cmb_value( 'person_phone_tf' ); ?></p>
			<?php } ?>
			<?php if ( has_cmb_value( 'person_email') ) { ?>
			<h5>Email:</h5>
			<p class="person-value"><a href="mailto:<?php show_cmb_value( 'person_email' ); ?>"><?php show_cmb_value( 'person_email' ); ?></a></p>
			<?php } ?>
		</div>
		<div class="half right">
			<?php
			the_content();
			the_accordion();
			?>
		</div>
		<div class="quarter sidebar right">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('page-sidebar-about') ) : ?><!-- no sidebar --><?php endif; ?>
		</div>
	</div><!-- #content -->
		<?php
		endwhile;
	endif;
	?>


<?php

get_footer();

?>