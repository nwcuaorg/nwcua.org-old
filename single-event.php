<?php
/**
 * The Template for displaying all single posts
 */

get_header();

$header_title = has_cmb_value( '_p_large-title' ) ? get_cmb_value( '_p_large-title' ) : get_the_title();
$header_icon = has_cmb_value( '_p_large-title-icon' ) ? get_cmb_value( '_p_large-title-icon' ) : get_bloginfo('template_url') . '/img/icon-events.png';
$header_color = has_cmb_value( '_p_large-title-color' ) ? get_cmb_value( '_p_large-title-color' ) : 'green';

?>
	<div class="large-title bg-<?php print $header_color; ?>">
		<div class="wrap">
			<div class="large-title-icon bg-<?php print $header_color; ?>" style="background-image: url(<?php print $header_icon ?>); background-repeat: no-repeat; background-position: center center;">
			</div>
			<div class="large-title-text">
				<h1><?php print $header_title; ?></h1>
			</div>
		</div>
	</div>

	<?php the_showcase(); ?>

	<div id="primary" class="site-content">

		<div id="content" class="site-content content-wide wrap group" role="main">
		<?php 
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); 
				global $post;
    			$slug = $post->post_name;
				?>
			<div class="quarter right event-info">
				<?php 
				// display credit union name
				if ( has_cmb_value( 'event_start' ) ) {
					print "<h3>" . date( "F jS", get_cmb_value( 'event_start' ) ) . "</h3>";
					print "<p>" . date( "g:i a", get_cmb_value( 'event_start' ) );
					if ( has_cmb_value( 'event_end' ) ) {
						print " - " . date( "g:i a", get_cmb_value( 'event_end' ) );
					}
					print " P" . ( date('I') == 1 ? "S" : "D" ) . "T<br>";
					print date( "g:i a", get_cmb_value( 'event_start' ) );
					if ( has_cmb_value( 'event_end' ) ) {
						print " - " . date( "g:i a", get_cmb_value( 'event_end' ) );
					}
					print " M" . ( date('I') == 1 ? "S" : "D" ) . "T</p>";
				}

				// display the event duration.
				if ( has_cmb_value( 'event_start' ) && has_cmb_value( 'event_end' ) ) {
					print "<p><label>Duration:</label><br>" . duration( get_cmb_value( 'event_start' ), get_cmb_value( 'event_end' ) ) . "</p>";
				}

				// display price
				$early_date = get_cmb_value( 'event_early_date' );
				$early_price = get_cmb_value( 'event_early_price' );
				$regular_price = get_cmb_value( 'event_price' );
				$late_date = get_cmb_value( 'event_late_date' );
				$late_price = get_cmb_value( 'event_late_price' );
				$is_early = ( time() <= $early_date ? 1 : 0);
				$is_late = ( time() >= $late_date ? 1 : 0 );
				$current_price = ( $is_early ? $early_price : ( $is_late ? $late_price : $regular_price ) );
				if ( !empty( $current_price ) ) {
					print "<p><label>Price:</label><br>$" . $current_price . ( $is_early ? ' (early bird price)' : ( $is_late ? ' (late registration price)' : '' ) ) . "</p>";
				}
				print '<p style="padding-top: 20px;"><a href="' . ( has_cmb_value( 'event_registration' ) ? get_cmb_value( 'event_registration' ) : 'https://app.nwcua.org/events/' . $slug . '/registrations/new' ) . '" class="btn-arrow green">Register Now</a></p>';

				// get address values and display them.
				$venue = get_cmb_value( 'event_venue' );
				$address = get_cmb_value( 'event_address' );
				$city = get_cmb_value( 'event_city' );
				$state = get_cmb_value( 'event_state' );
				$zipcode = get_cmb_value( 'event_zipcode' );
				$venue_email = get_cmb_value( 'event_email' );
				if ( !empty( $venue ) && !empty( $address ) && !empty( $city ) && !empty( $state ) && !empty( $zipcode ) ) {
					print "<hr><h5>Venue Info:</h5>";
					print "<p>" . $venue . "<br>" . $address . "<br>" . $city . ", " . $state . " " . $zipcode . "</p>";

					if ( !empty( $venue_email ) ) {
						print "<p><label>Email:</label> <a href=\"mailto:" . $venue_email . "\">" . $venue_email . "</a></p>";
					}

				}

				if ( has_cmb_value( 'event_location_link' ) ) {
					// gmap embed api key: AIzaSyB0FlglKxf0TJtQZJlbrCa5q836iyMRcYE
					?>
				<p><iframe width="100%" height="250" frameborder="0" style="border: 0;"
				src="<?php show_cmb_value( 'event_location_link' ) ?>" allowfullscreen></iframe></p>
					<?php
				}

				// get hotel address values and display them.
				$hotel_name = get_cmb_value( 'event_hotel' );
				$hotel_address = get_cmb_value( 'event_hotel_address' );
				$hotel_city = get_cmb_value( 'event_hotel_city' );
				$hotel_state = get_cmb_value( 'event_hotel_state' );
				$hotel_zipcode = get_cmb_value( 'event_hotel_zipcode' );
				$hotel_email = get_cmb_value( 'event_hotel_email' );
				$hotel_phone = get_cmb_value( 'event_hotel_phone' );
				$hotel_rate = get_cmb_value( 'event_hotel_price' );
				$hotel_website = get_cmb_value( 'event_hotel_website' );
				if ( !empty( $hotel_name ) && !empty( $hotel_address ) && !empty( $hotel_city ) && !empty( $hotel_state ) && !empty( $hotel_zipcode ) ) {
					print "<hr><h5>Hotel Info:</h5>";
					print "<p>" . $hotel_name . "<br>" . $hotel_address . "<br>" . $hotel_city . ", " . $hotel_state . " " . $hotel_zipcode . "</p>";

					if ( !empty( $hotel_phone ) ) {
						print "<p><label>Phone:</label> <a href=\"tel:" . $hotel_phone . "\">" . $hotel_phone . "</a></p>";
					}

					if ( !empty( $hotel_email ) ) {
						print "<p><label>Email:</label> <a href=\"mailto:" . $hotel_email . "\">" . $hotel_email . "</a></p>";
					}

					if ( !empty( $hotel_website ) ) {
						$parse = parse_url( $hotel_website );
						print "<p><label>Website:</label> <a href=\"" . $hotel_website . "\">" . $parse['host'] . "</a></p>";
					}

					if ( !empty( $hotel_rate ) ) {
						print "<p><label>Rate:</label> $" . $hotel_rate . "/night</p>";
					}
				}
				?>
			</div>
			<div class="three-quarter right"><?php the_content(); ?></div>
			<div class="group">
				<?php the_accordion(); ?>
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