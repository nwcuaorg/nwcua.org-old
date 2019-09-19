<?php

// start the session
session_start();


// show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// include wordpress
define('WP_USE_THEMES', false);
require( '../../../../../wp-load.php' );


// include our database class
require( "db.php" );


// include the api helper functions
require( "api.php" );


// get the access token and the instance url from the session.
$access_token = $_SESSION['access_token'];
$instance_url = $_SESSION['instance_url'];


// if we don't have a token or url, redirect to authenticate
if ( empty( $access_token ) || empty( $instance_url ) ) {
    header( "Location: oauth.php" );
    exit;
}


// get the events
$events = get_events( $instance_url, $access_token );
print "<pre>"; print_r( $events ); print "</pre>"; die;



// loop through the events
/*
foreach ( $events as $event ) {

    if ( !empty( $event ) ) {
        //$tz_offset = ( 3600 * ( stristr( $event->start_date, '-07:00' ) ? -1 : 0 ) );
        $tz_offset = 0;
        $timezone = ( stristr( $event->start_date, '-07:00' ) ? 'M' : 'P' );

        // get a previous post if it exists.
        $previous_post = $db->query_one( "SELECT * FROM `nwcua_posts` WHERE `old_id`=" . $event->id . ";" );
        if ( !empty( $previous_post ) ) {
            $update_query = "UPDATE `nwcua_posts` SET 
                `post_date`=\"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\",
                `post_date_gmt`=\"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\",
                `post_name`=\"" . sanitize_title( $event->slug ) . "\",
                `post_title`=\"" . $db->cn->real_escape_string( $event->name ) . "\",
                `post_content`=\"" . $db->cn->real_escape_string( $event->content ) . "\",
                `post_excerpt`=\"" . $db->cn->real_escape_string( $event->content ) . "\",
                `post_status`=\"publish\",
                `comment_status`=\"open\",
                `ping_status`=\"open\",
                `post_type`=\"event\",
                `post_modified`=\"" . date( 'Y-m-d H:i:s' ) . "\",
                `post_modified_gmt`=\"" . date( 'Y-m-d H:i:s' ) . "\"
                WHERE `ID`=" . $previous_post->ID . ";";
            if ( $db->update( $update_query ) ) {
                print 'Existing post updated: ' . $event->name . "\n";
                print 'Time: ' . $event->start_date . "\n";
                set_meta( $previous_post->ID, '_p_event_location_text', $event->location_text );
                set_meta( $previous_post->ID, '_p_event_location_link', $event->location_link );
                set_meta( $previous_post->ID, '_p_event_start', strtotime( $event->start_date )+$tz_offset );
                set_meta( $previous_post->ID, '_p_event_end', strtotime( $event->end_date )+$tz_offset );
                set_meta( $previous_post->ID, '_p_event_timezone', $timezone );
                set_meta( $previous_post->ID, '_p_event_early_date', strtotime( $event->early_price_until ) );
                set_meta( $previous_post->ID, '_p_event_early_price', $event->early_price );
                set_meta( $previous_post->ID, '_p_event_price', $event->price );
                set_meta( $previous_post->ID, '_p_event_late_date', strtotime( $event->late_date ) );
                set_meta( $previous_post->ID, '_p_event_late_price', $event->late_price );
                if ( !empty( $event->event_type ) ) set_term( $previous_post->ID, $event->event_type );
            } else {
                print 'Existing post - failed.' . $event->name . "\n";
            }
            $post_id = $previous_post->ID;
        } else {
            $post_id = $db->insert( "INSERT INTO `nwcua_posts` ( `post_author`, `post_modified`, `post_modified_gmt`, `post_date`, `post_date_gmt`, `post_name`, `post_title`, `post_content`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_type`, `to_ping`, `pinged`, `post_content_filtered`, `old_id` ) VALUES ( 1, \"" . date( 'Y-m-d H:i:s' ) . "\", \"" . date( 'Y-m-d H:i:s' ) . "\", \"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at )-$tz_offset ) . "\", \"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\", \"" . sanitize_title( $event->slug ) . "\", \"" . $db->cn->real_escape_string( $event->name ) . "\", \"" . $db->cn->real_escape_string( $event->content ) . "\", \"" . $db->cn->real_escape_string( $event->content ) . "\", \"publish\", \"open\", \"open\", \"event\", '', '', '', " . $event->id . " );" );
            if ( $post_id ) {
                print 'New event inserted: ' . $event->name . "\n";
                print 'Time: ' . $event->start_date . "\n";
                set_meta( $post_id, '_p_event_location_text', $event->location_text );
                set_meta( $post_id, '_p_event_location_link', $event->location_link );
                set_meta( $post_id, '_p_event_start', strtotime( $event->start_date )+$tz_offset );
                set_meta( $post_id, '_p_event_end', strtotime( $event->end_date )+$tz_offset );
                set_meta( $post_id, '_p_event_timezone', $timezone );
                set_meta( $post_id, '_p_event_early_date', strtotime( $event->early_price_until ) );
                set_meta( $post_id, '_p_event_early_price', $event->early_price );
                set_meta( $post_id, '_p_event_price', $event->price );
                set_meta( $post_id, '_p_event_late_date', strtotime( $event->late_date ) );
                set_meta( $post_id, '_p_event_late_price', $event->late_price );
                if ( !empty( $event->event_type ) ) set_term( $post_id, $event->event_type );
            } else {
                print 'New post - failed.' . $event->name . "\n";
            }
        }
    }
}



$db->update( "UPDATE nwcua_term_taxonomy SET count = (
SELECT COUNT(*) FROM nwcua_term_relationships rel 
    LEFT JOIN nwcua_posts po ON (po.ID = rel.object_id) 
    WHERE 
        rel.term_taxonomy_id = nwcua_term_taxonomy.term_taxonomy_id 
        AND 
        nwcua_term_taxonomy.taxonomy NOT IN ('link_category')
        AND 
        po.post_status IN ('publish', 'future')
);" );
*/


