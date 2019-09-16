<?php


define("SF_CLIENT_ID", "3MVG9sLbBxQYwWqsFCnCBGsG1EYxC3Wy_e6YOqu8iyVINZ366vFgrjFI3xmLSF6E1moqgrFKAmcXt90WjeurW");
define("SF_CLIENT_SECRET", "888A32DECF4776D2CA7241DF8E220B7A53D19B0EB68C6F3178211D734EBB3DE6");
define("SF_REDIRECT_URI", "https://staging.nwcua.org/sfauth");
define("SF_LOGIN_URI", "https://test.salesforce.com");
define("SF_USER", "james@jpederson.com.staging");
define("SF_PWD", "930YDC5ss2pV6l");


// uses credentials from above, returns security token.
function salesforce_auth() {

	// init the curl resource
	$ch = curl_init();

	// the url we're requesting the token from
	$token_url = SF_LOGIN_URI . "/services/oauth2/token";

	// gather the data we're going to send to Salesforce
	$postData = array(
	    'grant_type' => 'password',
	    'client_id' => SF_CLIENT_ID,
	    'client_secret' => SF_CLIENT_SECRET,
	    'username' => SF_USER,
	    'pw' => SF_PWD,
	);

	// set some curl options
	curl_setopt_array($ch, array(
	    CURLOPT_HEADER => true,
	    CURLOPT_URL => $token_url,
	    CURLOPT_HTTPAUTH => true,
	    CURLAUTH_ANY => true,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => $postData
	));

	// get the response from the curl request
    $output = curl_exec($ch);

    // get the http status code from the response data
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // output the status code
    echo "<br /><br />Status code: ".$status."<br />";

    // based on status code response data, 
    if ( $status != 200 ) {
        die( "Error from " . $token_url . "<br /> call to URL failed with status: " . $status . ", <br />response:<br />" . $output
        . ",<br /> curl_error " . curl_error( $ch ) . ", curl_errno " . curl_errno( $ch ) );
    } else {
        //This would normally be a redirect, but keeping on this page for debugging purposes
        echo "(this is a promising sign!)";
        echo "<br />Session ID: ".$_SESSION['access_token']."<br />";
        echo "URL: ".$_SESSION['instance_url']."<br />";
        echo "Token: ".$_SESSION['access_token']."<br />";
    }
}


// get salesforce events
//function get_salesforce_events() {
	salesforce_auth();
//}

