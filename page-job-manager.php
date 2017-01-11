<?php

/*
Template Name: Job Manager
*/

get_header();


// global query object
global $wp_query;


// start building args for query_posts
$args = array(
	'post_type' => 'job',
	'orderby' => 'meta_value',
	'order' => 'ASC',
	'meta_key' => '_p_job_expires',
	'posts_per_page' => 1000,
	'author' => get_current_user_id(),
);


// query the posts
query_posts( $args );


// get job count
$job_count = $wp_query->found_posts;

?>
	<div class="large-title bg-green">
		<div class="wrap">
			<div class="large-title-icon bg-green">
				<img src="/wp-content/uploads/2011/12/iconnwcua.png">
			</div>
			<div class="large-title-text">
				<h1>Job Manager</h1>
			</div>
		</div>
	</div>

	<div id="content" class="wrap group content-two-column" role="main">
		<div class="quarter sidebar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('page-sidebar-jobssidebar') ) : ?><!-- no sidebar --><?php endif; ?>
		</div>
		<div class="three-quarter">
			<div class="job-search"><input type="text" id="job-search" value="" placeholder="Search Jobs"></div>
			<div class="job-count"><strong>Showing <?php print $job_count; ?> Job<?php print ( $job_count == 1 ? '' : 's' ) ?></strong></div>
			<?php 

			if ( have_posts() ) : 
				// Start the Loop.
				while ( have_posts() ) : the_post(); 
					?>
			<div class="entry-job group">
				<div class="two-third no-margin">
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<?php the_excerpt(); ?>
				</div>
				<div class="third job-info">
					<?php
					print ( has_cmb_value( 'job_company' ) ? "<p><label>Credit Union:</label><br> " . get_cmb_value( 'job_company' ) . "</p>" : '' );
					?>
					<?php
					print ( has_cmb_value( 'job_region' ) ? "<p><label>Region:</label><br> " . get_cmb_value( 'job_region' ) . "</p>" : '' );
					?>
				</div>
			</div>
					<?php
				endwhile;
			
			else :
			
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );
			
			endif;

			?>
		</div>
	</div><!-- #content -->

	</section><!-- #primary -->

<?php

get_footer();

?>