<?php

// start the session
session_start();


// show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL );


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
print "<pre>";
// print_r( $events );


// loop through the events
foreach ( $events as $event ) {
    if ( !empty( $event ) ) {
        //$tz_offset = ( 3600 * ( stristr( $event['EventStart__c'], '-07:00' ) ? -1 : 0 ) );
        $tz_offset = 0;
        $timezone = ( stristr( $event['EventStart__c'], '-07:00' ) ? 'M' : 'P' );

        $event_id = ( !empty( $event['Associo_Product_ID__c'] ) ? $event['Associo_Product_ID__c'] : $event['Id'] );

        $slug = sanitize_title( $event['Name'] );

        // get a previous post if it exists.
        $previous_post = $db->query_one( "SELECT * FROM `nwcua_posts` WHERE `old_id`='" . $event_id . "';" );
        if ( !empty( $previous_post ) ) {
            $update_query = "UPDATE `nwcua_posts` SET 
                `post_date`=\"" . date( 'Y-m-d H:i:s', strtotime( $event['CreatedDate'] ) ) . "\",
                `post_date_gmt`=\"" . date( 'Y-m-d H:i:s', strtotime( $event['CreatedDate'] ) ) . "\",
                `post_name`=\"" . $slug . "\",
                `post_title`=\"" . $db->cn->real_escape_string( $event['Name'] ) . "\",
                `post_content`=\"" . $db->cn->real_escape_string( $event['Description__c'] ) . "\",
                `post_excerpt`=\"" . $db->cn->real_escape_string( $event['Description__c'] ) . "\",
                `post_status`=\"publish\",
                `comment_status`=\"open\",
                `ping_status`=\"open\",
                `post_type`=\"event\",
                `post_modified`=\"" . date( 'Y-m-d H:i:s' ) . "\",
                `post_modified_gmt`=\"" . date( 'Y-m-d H:i:s' ) . "\"
                WHERE `ID`='" . $previous_post->ID . "';";

            // run the update query, and if it works, add/update the meta information
            if ( $db->update( $update_query ) ) {
                print 'Existing post updated: ' . $event["Name"] . "\n";
                print 'Time: ' . $event['EventStart__c'] . "\n";
                set_meta( $previous_post->ID, '_p_event_start', strtotime( $event['EventStart__c'] )+$tz_offset );
                set_meta( $previous_post->ID, '_p_event_end', strtotime( $event['EventEnd__c'] )+$tz_offset );
                set_meta( $previous_post->ID, '_p_event_timezone', $timezone );
                set_meta( $previous_post->ID, '_p_event_early_date', strtotime( $event['EarlyDate__c'] ) );
                // set_meta( $previous_post->ID, '_p_event_early_price', $event->early_price );
                set_meta( $previous_post->ID, '_p_event_price', $event['Price__c'] );
                set_meta( $previous_post->ID, '_p_event_late_date', strtotime( $event['LateDate__c'] ) );
                // set_meta( $previous_post->ID, '_p_event_late_price', $event->late_price );
                if ( !empty( $event['Event_Type__c'] ) ) set_term( $previous_post->ID, $event['Event_Type__c'] );
            } else {

                // 
                print 'Existing post - failed.' . $event['Name'] . "\n";
            }
            $post_id = $previous_post->ID;

        } else {

            // put together the insert query.
            $insert_query = "INSERT INTO `nwcua_posts` ( `post_author`, `post_modified`, `post_modified_gmt`, `post_date`, `post_date_gmt`, `post_name`, `post_title`, `post_content`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_type`, `to_ping`, `pinged`, `post_content_filtered`, `old_id` ) VALUES ( 1, \"" . date( 'Y-m-d H:i:s' ) . "\", \"" . date( 'Y-m-d H:i:s' ) . "\", \"" . date( 'Y-m-d H:i:s', strtotime( $event['CreatedDate'] )-$tz_offset ) . "\", \"" . date( 'Y-m-d H:i:s', strtotime( $event['CreatedDate'] ) ) . "\", \"" . sanitize_title( $slug ) . "\", \"" . $db->cn->real_escape_string( $event['Name'] ) . "\", \"" . $db->cn->real_escape_string( $event['Description__c'] ) . "\", \"" . $db->cn->real_escape_string( $event['Description__c'] ) . "\", \"publish\", \"open\", \"open\", \"event\", '', '', '', '" . $event_id . "' );";

            // do the actual insert
            $post_id = $db->insert( $insert_query );

            // if it worked, insert all the meta information
            if ( $post_id ) {

                // it worked
                print 'New event inserted: ' . $event['Name'] . "\n";
                print 'Time: ' . $event['EventStart__c'] . "\n";
                set_meta( $post_id, '_p_event_start', strtotime( $event['EventStart__c'] )+$tz_offset );
                set_meta( $post_id, '_p_event_end', strtotime( $event['EventEnd__c'] )+$tz_offset );
                set_meta( $post_id, '_p_event_timezone', $timezone );
                set_meta( $post_id, '_p_event_early_date', strtotime( $event['EarlyDate__c'] ) );
                // set_meta( $post_id, '_p_event_early_price', $event->early_price );
                set_meta( $post_id, '_p_event_price', $event['Price__c'] );
                set_meta( $post_id, '_p_event_late_date', strtotime( $event['LateDate__c'] ) );
                // set_meta( $post_id, '_p_event_late_price', $event->late_price );
                if ( !empty( $event['Event_Type__c'] ) ) set_term( $post_id, $event['Event_Type__c'] );

            } else {

                // the insertion failed, show a failure message.
                print 'New post - failed.' . $event['Name'] . "\n";
                
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


print "</pre>"; die;
