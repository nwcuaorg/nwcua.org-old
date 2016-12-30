<?php


// include WordPress
define('WP_USE_THEMES', false);
require( '../../../../wp-load.php' );


// get all the events from the API
$events = get_associo_events();


// display events in test before anything else happen
// print_r( $events ); die;


// database object
class db {
	public $cn='';
	public $result='';
	public $show_errors=true;

	function db() {
		$this->cn=mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
	}

	function query( $query ) {
		$select=$this->cn->query( $query );
		if ( !empty( $select ) ) {
			while ( $rowselect=$select->fetch_object() ) {
				$results[]=$rowselect;
			}
		}
		if ( !empty( $results ) ) {
			return $results;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function query_one( $query ) {
		$select=$this->cn->query( $query );
		if ( !empty( $select ) ) {
			while ( $rowselect=$select->fetch_object() ) {
				$results[]=$rowselect;
			}
		}
		if ( !empty( $results ) ) {
			return $results[0];
		} else {
			$this->handle_error();
			return false;
		}
	}

	function update( $query ) {
		$update=$this->cn->query( $query );
		if ( $update ) {
			return true;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function insert( $query ) {
		$update=$this->cn->query( $query );
		if ( $update ) {
			return $this->cn->insert_id;
		} else {
			$this->handle_error();
			return false;
		}
	}

	function handle_error() {
		if ( !empty( $this->cn->error ) && $this->show_errors ) {
			print $this->cn->error;
			die;
		}
	}

}
$db = new db;



// set meta
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
foreach ( $events as $event ) {

	if ( !empty( $event ) ) {
		// get a previous post if it exists.
		$previous_post = $db->query_one( "SELECT * FROM `nwcua_posts` WHERE `old_id`=" . $event->id . ";" );
		if ( !empty( $previous_post ) ) {
			$update_query = "UPDATE `nwcua_posts` SET 
				`post_date`=\"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\",
				`post_date_gmt`=\"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\",
				`post_name`=\"" . sanitize_title( $event->name ) . "\",
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
	    		print 'existing post - updated.' . "\n";
	    		set_meta( $previous_post->ID, '_p_event_location_text', $event->location_text );
	    		set_meta( $previous_post->ID, '_p_event_location_link', $event->location_link );
	    		set_meta( $previous_post->ID, '_p_event_start', strtotime( $event->start_date ) );
	    		set_meta( $previous_post->ID, '_p_event_end', strtotime( $event->end_date ) );
	    		set_meta( $previous_post->ID, '_p_event_early_date', strtotime( $event->early_price_until ) );
	    		set_meta( $previous_post->ID, '_p_event_early_price', $event->early_price );
	    		set_meta( $previous_post->ID, '_p_event_late_date', strtotime( $event->late_date ) );
	    		set_meta( $previous_post->ID, '_p_event_late_price', $event->late_price );
	    		if ( !empty( $event->event_type ) ) set_term( $previous_post->ID, $event->event_type );
			} else {
				print 'existing post - failed.' . "\n";
			}
			$post_id = $previous_post->ID;
		} else {
			$post_id = $db->insert( "INSERT INTO `nwcua_posts` ( `post_author`, `post_modified`, `post_modified_gmt`, `post_date`, `post_date_gmt`, `post_name`, `post_title`, `post_content`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_type`, `to_ping`, `pinged`, `post_content_filtered`, `old_id` ) VALUES ( 1, \"" . date( 'Y-m-d H:i:s' ) . "\", \"" . date( 'Y-m-d H:i:s' ) . "\", \"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\", \"" . date( 'Y-m-d H:i:s', strtotime( $event->created_at ) ) . "\", \"" . sanitize_title( $event->name ) . "\", \"" . $db->cn->real_escape_string( $event->name ) . "\", \"" . $db->cn->real_escape_string( $event->content ) . "\", \"" . $db->cn->real_escape_string( $event->content ) . "\", \"publish\", \"open\", \"open\", \"event\", '', '', '', " . $event->id . " );" );
			if ( $post_id ) {
				print 'new event - inserted.' . "\n";
	    		set_meta( $post_id, '_p_event_location_text', $event->location_text );
	    		set_meta( $post_id, '_p_event_location_link', $event->location_link );
	    		set_meta( $post_id, '_p_event_start', strtotime( $event->start_date ) );
	    		set_meta( $post_id, '_p_event_end', strtotime( $event->end_date ) );
	    		set_meta( $post_id, '_p_event_early_date', strtotime( $event->early_price_until ) );
	    		set_meta( $post_id, '_p_event_early_price', $event->early_price );
	    		set_meta( $post_id, '_p_event_late_date', strtotime( $event->late_date ) );
	    		set_meta( $post_id, '_p_event_late_price', $event->late_price );
	    		if ( !empty( $event->event_type ) ) set_term( $post_id, $event->event_type );
			} else {
				print 'new post - failed.' . "\n";
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



?>