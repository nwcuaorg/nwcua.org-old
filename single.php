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
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post(); 
				?>
			<div class="content-header">
				<h1><?php the_title(); ?></h1>
				<h2><?php the_excerpt(); ?></h2>
			</div>
			<div class="three-quarter right">
				<?php 
				the_content(); 

				$orig_post = $post;
				global $post;
				$categories = wp_get_post_categories( $post->ID );

				if ( $categories ) {

					$args=array(
						'category__in' => $categories,
						'post__not_in' => array( $post->ID ),
						'posts_per_page' => 5, // Number of related posts to display.
						'ignore_sticky_posts' => 1
					);
					$my_query = new wp_query( $args );

					if ( $my_query->have_posts() ) {
						?>
				<hr />
				<div class="relatedposts">
					<h3>Related Posts</h3>
					<ul>
					<?php
					while( $my_query->have_posts() ) {
						$my_query->the_post();
						?>
						<li><a rel="external" href="<? the_permalink()?>"><?php the_title(); ?></a></li>
						<?php
					}
					?>
					</ul>
				</div>
						<?php
					}
				}
				$post = $orig_post;
				wp_reset_query();
				?>
			</div>
			<div class="quarter sidebar right">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-blog') ) : ?><!-- no sidebar --><?php endif; ?>
			</div>
				<?php
			}
		}
		?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php

get_footer();

?>