<?php


// function to get the events
function get_events( $instance_url, $access_token ) {

    // set up the query
    $query = "SELECT Id, Name, CreatedDate, Active__c, Associo_Product_ID__c, CreatedById, Description__c, EarlyDate__c, EventEnd__c, EventStart__c, Event_Type__c, Family__c, GLAccount__c, LastModifiedById, LateDate__c, LongName__c, OwnerId, ParentProduct__c, Price__c, ProductFamily__c, ShowOnWebsite__c FROM Product__c WHERE ShowOnWebsite__c=true AND Active__c=true AND EventStart__c>" . date('c') . " LIMIT 200";

    // build the URL
    $url = "$instance_url/services/data/v45.0/query?q=" . urlencode($query);

    // set up curl call
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    // run it
    $json_response = curl_exec($curl);
    curl_close($curl);

    // parse the json from the response
    $response = json_decode($json_response, true);

    // return the records the api gave us.
    return $response['records'];
}


// set meta by post id, key, and value
function set_meta( $post_id, $meta_key, $meta_value ) {
    global $db;
    if ( !empty( $post_id ) ) {
        $record_exists = $db->query_one( "SELECT * FROM `nwcua_postmeta` WHERE `post_id`=" . $post_id . " AND `meta_key`=\"" . $meta_key . "\";" );
        if ( !empty( $record_exists ) ) {
            $db->update( "UPDATE `nwcua_postmeta` SET `meta_value`=\"" . $meta_value . "\" WHERE `meta_id`=" . $record_exists->meta_id );
            print 'Postmeta updated (' . $meta_key . '): ' . $meta_value . ".\n";
        } else {
            $db->insert( "INSERT INTO `nwcua_postmeta` ( `post_id`, `meta_key`, `meta_value` ) VALUES ( " . $post_id . ", \"" . $meta_key . "\", \"" . $meta_value . "\" )" );
            print 'Postmeta inserted (' . $meta_key . '): ' . $meta_value . ".\n";
        }
    }
}


// set the taxonomy terms for a post id by slug.
function set_term( $post_id, $slug ) {
    global $db;
    $record_exists = $db->query_one( "SELECT * FROM `nwcua_terms` WHERE `slug`='" . sanitize_title( $slug ) . "';" );
    if ( !empty( $record_exists ) ) {
        $term_id = $record_exists->term_id;
        print 'Term exists: ' . $slug . ".\n";
    } else {
        $term_id = $db->insert( "INSERT INTO `nwcua_terms` ( `slug`, `name`, `term_group` ) VALUES ( \"" . sanitize_title( $slug ) . "\", \"" . $slug . "\", 0 )" );
        print 'Term inserted: ' . $slug . ".\n";
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
            print "Term and taxonomy connected.\n";
        }
    }
}


