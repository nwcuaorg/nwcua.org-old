<?php
/**
 * The template for displaying Archive pages
 */

get_header(); 

if ( isset( $_REQUEST['event_category'] ) && $_REQUEST['event_category']!=0 ) {
	$category_info = get_term_by( 'id', $_REQUEST['event_category'], 'category' );
	$page_title = $category_info->name;
} else {
	$page_title = "Events Calendar";
}

?>
	<div class="large-title bg-green">
		<div class="wrap">
			<div class="large-title-icon bg-green" style="background-image: url(<?php print get_bloginfo('template_url') . '/img/icon-events.png' ?>); background-repeat: no-repeat; background-position: center center;">
			</div>
			<div class="large-title-text">
				<h1><?php print $page_title; ?></h1>
			</div>
		</div>
	</div>
	
	<div id="content" class="wrap content-wide" role="main">
		<p><strong>Filter by Event Type:</strong> <?php filter_by_event_type(); ?></p>
		<br>
		<?php 

		// get URL parameters and default to current month.
		$month = ( isset( $_REQUEST['mo'] ) ? $_REQUEST['mo'] : date( "n" ) );
		$year = ( isset( $_REQUEST['yr'] ) ? $_REQUEST['yr'] : date( "Y" ) );

		// output month
		show_month_events( $month, $year );

		?>
	</div><!-- #content -->

<?php

get_footer();

?>