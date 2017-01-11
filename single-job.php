<?php
/**
 * The Template for displaying all single posts
 */

get_header();

?>
	<div id="primary" class="site-content">

		<?php 
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); 
				?>
		<div class="large-title bg-green">
			<div class="wrap">
				<div class="large-title-icon bg-green">
					<img src="/wp-content/uploads/2011/12/iconnwcua.png">
				</div>
				<div class="large-title-text">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</div>
		<div id="content" class="wrap group content-two-column" role="main">
			<div class="quarter sidebar">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('page-sidebar-jobssidebar') ) : ?><!-- no sidebar --><?php endif; ?>
			</div>
			<div class="three-quarter">
				<div class="third right job-info">
					<?php
					// display credit union name
					print ( has_cmb_value( 'job_company' ) ? "<p><label>Credit Union:</label><br> " . get_cmb_value( 'job_company' ) . "</p>" : '' );

					// display region
					print ( has_cmb_value( 'job_region' ) ? "<p><label>Region:</label> " . get_cmb_value( 'job_region' ) . "</p>" : '' ) ;

					// display job type
					print ( has_cmb_value( 'job_type' ) ? "<p><label>Type:</label> " . get_cmb_value( 'job_type' ) . "</p>" : '' );
					?>
					<?php if ( has_cmb_value( 'job_contact_name' ) ) { ?>
					<p>
					<?php print ( has_cmb_value( 'job_contact_name' ) ? "<label>Contact:</label> " . get_cmb_value( 'job_contact_name' ) . '<br>' : '' ); ?>
					<?php print ( has_cmb_value( 'job_contact_email' ) ? '<label>Email:</label> <a href="' . get_cmb_value( 'job_contact_email' ) . '">' . get_cmb_value( 'job_contact_email' ) . '</a><br>' : '' ); ?>
					<?php print ( has_cmb_value( 'job_contact_phone' ) ? '<label>Phone:</label> ' . get_cmb_value( 'job_contact_phone' ) . '<br>' : '' ); ?>
					<?php print ( has_cmb_value( 'job_contact_fax' ) ? "<label>Fax:</label> " . get_cmb_value( 'job_contact_fax' ) . "<br>" : '' ); ?>
					</p>
					<?php } ?>

					<?php
					// display job type
					print ( has_cmb_value( 'job_expires' ) ? "<p><label>Closing:</label> " . date( "n/j/Y", strtotime( get_cmb_value( 'job_expires' ) ) ) . "</p>" : '' );
					?>
				</div>
				<p><strong>Job Description:</strong></p>
				<?php the_content(); ?>
				<br>
					<?php
					if ( has_cmb_value( 'job_education' ) ) { 
						print "<p><strong>Education/Experience Required:</strong></p>";
						print apply_filters( 'the_content', get_cmb_value( 'job_education' ) ) . "<br>";
					}

					if ( has_cmb_value( 'job_comments' ) ) { 
						print "<p><strong>Additional Comments:</strong></p>";
						print apply_filters( 'the_content', get_cmb_value( 'job_comments' ) ) . "<br>";
					}

					edit_job_form();

					?>
	
			</div>
		</div><!-- #content -->
				<?php
			endwhile;
		endif;
		?>

	</div><!-- #primary -->
<?php

get_footer();

?>