<?php


function call_associo_api( $endpoint, $data='' ) {
	
	// encode data array as JSON
	$data = json_encode( $data );

	// set endpoint, method, and headers 
	// $ch = curl_init( 'http://nwcua.ditest.us/api/' . $endpoint );
	$ch = curl_init( 'https://app.nwcua.org/api/' . $endpoint );
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization: Token token="' . ASSOCIO_TOKEN . '"',
		'Content-Type: application/json'
	) );


	// set the data being posted to the server
	if ( !empty( $data ) ) curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );


	// execute the curl call.
	$result = curl_exec( $ch );


	// return the response
	return $result;

}


function get_associo_events() {
	return json_decode( call_associo_api( 'events' ) );
}


function get_associo_event_tags() {
	return json_decode( call_associo_api( 'event_tags' ) );
}


?>