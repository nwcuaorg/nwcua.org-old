<?php
/*
Template Name: Home
*/

get_header(); ?>


	<div id="primary" class="wrap">
		<div class="quick-filter">
			<div class="wrap">
				
			</div>
		</div>
		<div id="content" class="site-content content-wide home-list" role="main">
			<?php
			if ( is_search() ) {
				?><h1>Search Results for <span>'<?php print $_REQUEST["s"]; ?>'</span></h1><?php
			}

			while ( have_posts() ) : the_post();
				?>
				<div class="entry">
					<?php the_post_thumbnail( 'full' ); ?>
					<div class="description">
						<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
						<?php the_excerpt(); ?>
					</div>
				</div>
				<?php
			endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>