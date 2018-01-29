<?php
/**
 * The Template for displaying all single posts
 */

$is_anthem = true;

if ( has_cmb_value( 'brand' ) ) {
	if ( get_cmb_value( 'brand' ) == 'nwcua' ) {
		$is_anthem = false;
	}
}

get_header();



if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		if ( in_category(7850) ) {
			?>
	<div class="large-title bg-navy">
		<div class="wrap">
			<div class="large-title-icon bg-navy">
				<img src="/wp-content/uploads/2011/12/iconnwcua.png">
			</div>
			<div class="large-title-text">
				<h1>Advocacy On The Move</h1>
			</div>
		</div>
	</div>
	<div class="cuobsessed-title">
		<div class="wrap">
			<img src="<?php bloginfo('template_url') ?>/img/cuobsessed.png" class="cuobsessed-logo" alt="CU Obsessed Logo">
			<h2>Updates from your NWCUA Advocacy Team</h2>
		</div>
	</div>
	<div id="primary" class="site-content wrap">
		<div id="content" class="site-content content-narrow content-style" role="main">
			<div class="cuobsessed-post group">
				<?php the_post_thumbnail(); ?>
				<h2><?php the_author(); ?> - <?php the_date() ?></h2>
				<?php the_content(); ?>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->
					<?php 
				} else {
					?>
	<div id="primary" class="site-content wrap">
		<div id="content" class="site-content content-two-column content-style" role="main">
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
						<li><a rel="external" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
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
		</div><!-- #content -->
	</div><!-- #primary -->
		<?php
		}
	}
}
?>
<?php

get_footer();

?>