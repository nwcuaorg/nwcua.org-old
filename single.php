<?php
/**
 * The Template for displaying all single posts
 */

$is_anthem = true;

get_header();

?>
	<div id="primary" class="site-content wrap">
		<div id="content" class="site-content content-two-column content-style" role="main">
			<?php 
			if ( have_posts() ) :
				while ( have_posts() ) : the_post(); 
					?>
			<div class="content-header">
				<h1><?php the_title(); ?></h1>
				<h2><?php the_excerpt(); ?></h2>
			</div>
			<div class="three-quarter right">
				<?php the_content(); ?>
			</div>
			<div class="quarter sidebar right">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-blog') ) : ?><!-- no sidebar --><?php endif; ?>
			</div>
					<?php
				endwhile;
			endif;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php

get_footer();

?>