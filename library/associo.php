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



function cal_link() {
	if ( is_user_logged_in() ) {

		// get current user ID
		$user_id = get_current_user_id();

		// retrieve user information from Associo
		$user_info = json_decode( call_associo_api( 'account/' . $user_id ) );

		// generate an md5 hash of the CAL token, date and user ID (a unique ID).
		// print $user_id; die;
		$guid = md5( CAL_TOKEN . date( 'n/j/Y') . $user_info->email );

		// generate redirect
		$redirect = urlencode( 'http://www.fuzeqna.com/nwcua/ext/kbdetail.aspx?kbid=468' );

		return '<a href="https://www.fuzeqna.com/nwcua/membership/consumer/signon.asp?auth=' . $guid . '&uid=' . $user_info->email . '&email=' . $user_info->email . '&fname=' . $user_info->first_name . '&lname=' . $user_info->last_name . '&redir=' . $redirect . '" class="btn-arrow">Visit CAL</a>';
	} else {
		return "<strong>Please log in to access CAL.</strong>";
	}
}
add_shortcode( 'cal-link', 'cal_link' );



