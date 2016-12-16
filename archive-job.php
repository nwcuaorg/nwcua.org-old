<?php
/**
 * The template for displaying Archive pages
 */

get_header(); 

global $wp_query;


// start building args for query_posts
$args = array_merge( $wp_query->query_vars, array(
	'post_type' => 'job',
	'orderby' => 'meta_value',
	'order' => 'ASC',
	'meta_key' => '_p_job_expires',
	'posts_per_page' => 100
) );


// start building meta query for job expiration
$today = mktime( 0, 0, 0 );
$expires_query = array(
	'relation' => 'OR',
	array(
		'key' => '_p_job_expires',
		'value' => 0,
		'compare' => '='
	),
	array(
		'key' => '_p_job_expires',
		'value' => $today,
		'compare' => '>='
	)
);


// add meta_query for job type if set
if ( isset( $_GET['job_type'] ) ) {
	$type_query = array(
		'key' => '_p_job_type',
		'value' => $_GET['job_type'],
		'compare' => '='
	);
}


// if we have a type query
if ( !empty( $type_query ) ) {
	// add it in addition to the expiration query
	$args['meta_query'] = array(
		'relation' => 'AND',
		$expires_query,
		$type_query
	);
} else {
	// othewise just use the expiration query.
	$args['meta_query'] = $expires_query;
}


query_posts( $args );

$job_count = $wp_query->found_posts;

?>
	<div class="large-title bg-grey-light">
		<div class="wrap">
			<div class="large-title-text">
				<h1>Career Center</h1>
			</div>
		</div>
	</div>

	<div id="content" class="wrap groupcontent-two-column" role="main">
		<div class="quarter sidebar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-jobs') ) : ?><!-- no sidebar --><?php endif; ?>
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