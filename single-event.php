<?php
/**
 * The Template for displaying all single posts
 */

get_header();

?>
	<div class="large-title bg-lime">
		<div class="wrap">
			<div class="large-title-icon bg-green" style="background-image: url(<?php print get_bloginfo('template_url') . '/img/icon-events.png' ?>); background-repeat: no-repeat; background-position: center center;">
			</div>
			<div class="large-title-text">
				<h1><?php the_title(); ?></h1>
			</div>
		</div>
	</div>

	<?php the_showcase(); ?>

	<div id="primary" class="site-content">

		<div id="content" class="site-content content-wide wrap group" role="main">
		<?php 
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); 
				?>
			<div class="two-fifth right event-info">
				<?php 
				// display credit union name
				if ( has_cmb_value( 'event_start' ) ) {
					print "<h3>" . date( "F jS", get_cmb_value( 'event_start' ) ) . "</h3>";
					print "<p>" . date( "g:i a", get_cmb_value( 'event_start' ) );
					if ( has_cmb_value( 'event_end' ) ) {
						print " - " . date( "g:i a", get_cmb_value( 'event_end' ) );
					}
					print " P" . ( date('I') == 1 ? "S" : "D" ) . "T<br>";
					print date( "g:i a", get_cmb_value( 'event_start' )+3600 );
					if ( has_cmb_value( 'event_end' ) ) {
						print " - " . date( "g:i a", get_cmb_value( 'event_end' )+3600 );
					}
					print " M" . ( date('I') == 1 ? "S" : "D" ) . "T</p>";
				}

				// display the event duration.
				if ( has_cmb_value( 'event_start' ) && has_cmb_value( 'event_end' ) ) {
					print "<p><label>Duration:</label><br>" . duration( get_cmb_value( 'event_start' ), get_cmb_value( 'event_end' ) ) . "</p>";
				}

				// display price
				$early_date = get_cmb_value( 'event_early_date' );
				$early_price = get_cmb_value( 'event_price_early' );
				$regular_price = get_cmb_value( 'event_price' );
				$is_early = ( time() < $early_date );
				$current_price = ( $is_early ? $early_price : $regular_price );
				if ( $current_price != '0' ) {
					print "<p><label>Price:</label><br>$" . $current_price . ( $is_early ? ' (early registration price)' : '' ) . "</p>";
					//print '<p class="text-center"><a href="/event-registration/?event_price=' . $current_price . '&event_name=' . get_the_title() . '&event_qty=1" class="button">Register Now</a></p>';
				}

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

					// gmap embed api key: AIzaSyB0FlglKxf0TJtQZJlbrCa5q836iyMRcYE
					?>
				<p><iframe width="100%" height="250" frameborder="0" style="border: 0;"
				src="https://www.google.com/maps/embed/v1/place?key=AIzaSyB0FlglKxf0TJtQZJlbrCa5q836iyMRcYE
				&q=<?php print urlencode( $address . ", " . $city . ", "  . $state . ", " . $zipcode ) ?>" allowfullscreen></iframe></p>
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
			<div class="three-fifth"><?php the_content(); ?></div>
				<?php
			endwhile;
		endif;
		 ?>
		</div><!-- #content -->

	</div><!-- #primary -->
<?php

get_footer();

?>