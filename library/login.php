<?php


// get the request URI and remove the query string
$request = str_replace( "?" . $_SERVER['QUERY_STRING'], '',  $_SERVER['REQUEST_URI'] );


// redirect if they go to the account page.
if ( $request == '/account/' && !is_user_logged_in() ) {
	header( 'Location: /account/login/?redirect_to=' . urlencode( 'https://app.nwcua.org/account/' ) );
	exit;
} else if ( $request == '/account/' && is_user_logged_in() ) {
	header( 'Location: https://app.nwcua.org/account/' );
}


// logout and redirect if that's the request
if ( $request == '/logout' || $request == '/logout/' ) {
	wp_logout();
	wp_redirect( '/' );
	exit;
}


if ( $request == '/api/auth/generate_auth_cookie/' ) {

	$auth_attempt = associo_authenticate( $_REQUEST['username'], $_REQUEST['password'] );
	
	if ( !$auth_attempt ) {

		print '{"status":"error","error":"Invalid username and\/or password."}';
		die;

	} else {

		$response = array(
			'user' => $auth_attempt->data
		);
		print json_encode( $response );
		die;
	}

}






// add a custom stylesheet so we can customize the login page a bit.
function nwcua_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/login.css' );
}
add_action( 'login_enqueue_scripts', 'nwcua_login_stylesheet' );



// display the my account/login links based on user state.
function account_button() {

	// set up a global for the current user info
	global $current_user;

	// get the account page link.
    $account_page = get_post( pure_get_option( 'account-page' ) );
    $account_url = get_permalink( $account_page->ID );

	// get the account page link.
    $login_page = get_post( pure_get_option( 'login-page' ) );
    $login_url = get_permalink( $login_page->ID );

	// get the referer
	$referer = ( isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

	if ( stristr( $referer, '/log-in/' ) ) {
		$referer = get_home_url();
	}

	// if the user is logged in.
	if ( is_user_logged_in() ) {
		?>
		<a href="https://app.nwcua.org/account/" class='account button'>My Account</a>
		<?php 
	} else { 
		?>
		<a href="<?php print $login_url ?>?redirect_to=<?php print $referer ?>" class='account button'>Log In</a>
		<?php 
	}

}



// hide the admin toolbar for all users except administrators
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if ( !current_user_can( 'administrator' ) && !is_admin() ) {
		show_admin_bar( false );
	}
}



function cal_link() {
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		print "<!--" . $user_id . "-->";
		$user_info = json_decode( call_associo_api( 'account/' . $user_id ) );
		print "<!--" . print_r( $user_info, 1 ) . "-->";
		return '<a href="https://www.fuzeqna.com/nwcua/membership/consumer/signon.asp?auth=97d85146cf44699ffeb5c8a4691490de&Cookieexpdate=18+Apr+2018&uid=' . $user_info->email . '&email=' . $user_info->email . '&fname=' . $user_info->first_name . '&lname=' . $user_info->last_name . '&redir=http://www.fuzeqna.com/nwcua/consumer/kbdetail.asp?kbid=468&ao=t&fredir=http://www.fuzeqna.com/nwcua/consumer/kbdetail.asp?kbid=468&ao=t" class="btn-arrow">Visit CAL</a>';
	} else {
		return "<strong>Please log in to access CAL.</strong>";
	}
}
add_shortcode( 'cal-link', 'cal_link' );



function update_user_meta_item( $associo_user, $field_label ) {
	if ( !empty( $associo_user->$field_label ) ) {
		update_user_meta( $associo_user->id, $field_label, $associo_user->$field_label );
	}
}


// add a new password encryption schema that includes the username.
add_filter( 'wp_authenticate', 'nwcua_authenticate', 0, 3 );
function nwcua_authenticate( $username, $password ) {

	global $wpdb;

	// capture redirect_to parameter
	$redirect_to = ( isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '/' );

	$auth_attempt = associo_authenticate( $username, $password );

	// if the response is a valid user object
	if ( !$auth_attempt ) {

		// no user found
		wp_redirect( '/account/login/?login-error=true&redirect_to=' . $redirect_to );

	} else {

		// retrieve our new user
		$user = $auth_attempt;

		// set a secure user auth token.
		wp_set_auth_cookie( $user->ID, 1, 1 );

		// set a non-secure user auth token.
		wp_set_auth_cookie( $user->ID, 1, 0 );

		// redirect the user
		wp_redirect( 'https://app.nwcua.org/api/account/token?token=' . $auth_attempt->token . '&redirect=' . $redirect_to );

		// don't return anything, just exit after redirecting.
		exit;
	}
}



// function to authenticate a user against Associo's APIs.
function associo_authenticate( $username, $password ) {

	// get wp user
	$user = get_user_by( 'login', $username );

	// set up a credential array in 
	$credentials = array(
		"username" => $username,
		"password" => $password
	);

	// get response from associo api
	$associo_user = json_decode( call_associo_api( 'account/authenticate/', $credentials ) );

	// show what we get from associo without allowing the rest to happen
	// print_r( $associo_user ); die;

	// if the response is a valid user object
	if ( isset( $associo_user->username ) ) {

		if ( empty( $user ) ) {

			// build an insert query
			$insert_user = 'INSERT INTO `nwcua_users` ( `ID`, `user_login`, `user_pass`, `user_email`, `user_nicename`, `user_registered`, `display_name` ) VALUES ( ' . $associo_user->id . ', "' . $associo_user->username . '", "' . md5( $password ) . '", "' . $associo_user->email . '", "' . $associo_user->username . '", "' . date( "Y-m-d H:i:s", strtotime( $associo_user->created_at ) ) . '", "' . $associo_user->first_name . ' ' . $associo_user->last_name . '" );';

			// insert the user
			$wpdb->query( $insert_user );

			// set new user role.
			wp_update_user( array( 'ID' => $associo_user->id, 'role' => ( $associo_user->member ? 'member' : 'subscriber' ) ) );

		} else if ( $user->user_login != $associo_user->username ) {

			// build an insert query
			$insert_user = 'UPDATE `nwcua_users` SET `user_login`="' . $associo_user->username . '", `user_pass`="' . md5( $password ) . '", user_nicename`="' . $associo_user->username . '", `user_email`="' . $associo_user->email . '", `display_name`="' . $associo_user->first_name . ' ' . $associo_user->last_name . '" WHERE `ID`=' . $associo_user->id . ';';

			// insert the user
			$wpdb->query( $update_user );

			// set updated user role.
			wp_update_user( array( 'ID' => $associo_user->id, 'role' => ( $associo_user->member ? 'member' : 'subscriber' ) ) );

		}

		// retrieve our new user
		$user = get_user_by( 'login', $username );

		$user->token = $associo_user->token;

		return $user;

	} else {

		// no user found
		return false;

	}
}




/*
// when the user authenticates on NWCUA`, also authenticate them on InfoSight
function infosight_authenticate() {

	// let's redirect to infosight's login endpoint so that it can authenticate us on there as well.
    header( "Location: http://fl.leagueinfosight.com/Security__Login_6169.htm?email=" . $_POST['log'] . "&password=" . urlencode( $_POST['pwd'] ) . "&action=login&return_to=" . urlencode( $_POST['redirect_to'] ) );
    exit;

}
add_action('wp_login', 'infosight_authenticate');
*/



// check for empty username and password when a user is authenticating
// by default, WP doesn't even treat this as a login attempt, and redirects
// the user back to the admin login, which we'd like to avoid.
function empty_credential_error( $user, $username, $password ) {

    if ( is_a( $user, 'WP_User' ) ) return $user;

    if ( empty($username) || empty($password) ) {
        $error = new WP_Error();
        $user  = new WP_Error( 'authentication_failed', __('Neither the username nor password can be empty.' ));

        return $error;
    }

}
add_filter( 'authenticate', 'empty_credential_error', 30, 3 );



// let's create a shortcode that displays a login form on the front-end.
function login_form_shortcode( $atts, $content = null ) {
 	
 	$form = '';

 	if ( isset( $_REQUEST['login-error'] ) ) {
 		$form = '<div class="login-error">The credentials you entered do not match our records.</div>';
 	}

	if ( isset( $_REQUEST['redirect_to'] ) ) {
		$redirect = $_REQUEST['redirect_to'];
	} else {
		$redirect = get_home_url();
	}
 
    $account_page = get_post( pure_get_option( 'account-page' ) );
    $account_url = get_permalink( $account_page->ID );
 
    $reset_page = get_post( pure_get_option( 'reset-page' ) );
    $reset_url = get_permalink( $reset_page->ID );

	if ( !is_user_logged_in() ) {
		$form .= wp_login_form( array('echo' => false, 'redirect' => $redirect, 'value_remember' => 1 ) );
		$form .= '<p><a href="https://app.nwcua.org/forgot_password">Lost/forgotten Password</a></p>';
	} else {
		$form .= "You are currently logged in, please visit <a href='" . $account_url . "'>your account</a> for more options.";
	}

	return $form;

}
add_shortcode('pure-login-form', 'login_form_shortcode');



function email_mime_type () {
	return 'text/html';
}
add_filter ( 'wp_mail_content_type', 'email_mime_type');		



function current_url_shortcode() {
	return add_query_arg( '_', false );
}
add_shortcode('current-url', 'current_url_shortcode');



?>