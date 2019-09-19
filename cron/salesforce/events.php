<?php

session_start();


// get the access token and the instance url from the session.
$access_token = $_SESSION['access_token'];
$instance_url = $_SESSION['instance_url'];


// if we don't have a token or url, redirect to authenticate
if ( empty( $access_token ) || empty( $instance_url ) ) {
    header( "Location: oauth.php" );
    exit;
}


// function to get the events
function get_events( $instance_url, $access_token ) {

    // set up the query
    $query = "SELECT Name, Active__c, Associo_Product_ID__c, CreatedById, Description__c, EarlyDate__c, EventEnd__c, EventStart__c, Event_Type__c, Family__c, GLAccount__c, LastModifiedById, LateDate__c, LongName__c, OwnerId, ParentProduct__c, Price__c, ProductFamily__c, ShowOnWebsite__c from Product__c LIMIT 100";

    // build the URL
    $url = "$instance_url/services/data/v45.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($json_response, true);

    $total_size = $response['totalSize'];

    return $response['records'];
}


// get the events
$events = get_events( $instance_url, $access_token );
print_r( $events ); die;


// set meta by post id, key, and value
function set_meta( $post_id, $meta_key, $meta_value ) {
    global $db;
    if ( !empty( $post_id ) ) {
        $record_exists = $db->query_one( "SELECT * FROM `nwcua_postmeta` WHERE `post_id`=" . $post_id . " AND `meta_key`=\"" . $meta_key . "\";" );
        if ( !empty( $record_exists ) ) {
            $db->update( "UPDATE `nwcua_postmeta` SET `meta_value`=\"" . $meta_value . "\" WHERE `meta_id`=" . $record_exists->meta_id );
            print 'postmeta updated (' . $meta_key . '): ' . $meta_value . ".\n";
        } else {
            $db->insert( "INSERT INTO `nwcua_postmeta` ( `post_id`, `meta_key`, `meta_value` ) VALUES ( " . $post_id . ", \"" . $meta_key . "\", \"" . $meta_value . "\" )" );
            print 'postmeta inserted (' . $meta_key . '): ' . $meta_value . ".\n";
        }
    }
}


// set the taxonomy terms for a post id by slug.
function set_term( $post_id, $slug ) {
    global $db;
    $record_exists = $db->query_one( "SELECT * FROM `nwcua_terms` WHERE `slug`='" . sanitize_title( $slug ) . "';" );
    if ( !empty( $record_exists ) ) {
        $term_id = $record_exists->term_id;
        print 'term exists: ' . $slug . ".\n";
    } else {
        $term_id = $db->insert( "INSERT INTO `nwcua_terms` ( `slug`, `name`, `term_group` ) VALUES ( \"" . sanitize_title( $slug ) . "\", \"" . $slug . "\", 0 )" );
        print 'term inserted: ' . $slug . ".\n";
    }

    $term_taxonomy = $db->query_one( "SELECT * FROM `nwcua_term_taxonomy` WHERE `term_id`=" . $term_id . " AND `taxonomy`='event_cat';" );
    if ( !empty( $term_taxonomy ) ) {   
        $term_taxonomy_id = $term_taxonomy->term_taxonomy_id;
    } else {
        $term_taxonomy_id = $db->insert( "INSERT INTO `nwcua_term_taxonomy` ( `term_id`, `taxonomy`, `description`, `parent`, `count` ) VALUES ( " . $term_id . ", 'event_cat', '', 0, 0 );" );
    }

    $get_term_relationship = $db->query_one( "SELECT * FROM `nwcua_term_relationships` WHERE `object_id`=\"" . $post_id . "\" AND `term_taxonomy_id`=" . $term_taxonomy_id . ";" );
    if ( empty( $get_term_relationship ) ) {
        if ( $db->insert( "INSERT INTO `nwcua_term_relationships` ( `object_id`, `term_taxonomy_id`, `term_order` ) VALUES ( " . $post_id . ", " . $term_taxonomy_id . ", 0 );" ) ) {
            print "term and taxonomy connected.\n";
        }
    }
}



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


