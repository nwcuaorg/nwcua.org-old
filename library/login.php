<?php


// get the request URI and remove the query string
$request = str_replace( "?" . $_SERVER['QUERY_STRING'], '',  $_SERVER['REQUEST_URI'] );

if ( substr( $request, 0, 5 ) == '/auth' ) {
	$_SESSION['sf_user'] = $_REQUEST;
	print "<div style='padding:10px;font-size:15px;line-height:22px;font-family:Arial;color:#666;'>User successfully authenticated in WordPress.<br><br>Eventually, this endpoint will redirect to InfoSight to authenticate there, and then redirect back to the url passed along with the login request in the 'redirect_url' parameter. <a href='" . $_REQUEST['redirect_url'] . "'>Click here</a> to manually go there for now.<br><br>InfoSight, this is the query string you can expect to receive from us when we redirect users:<br><br>";
	print http_build_query( $_REQUEST ); 
	print "<br><br>Please provide us with a URL to which we should send this query string. Thanks!</div>";
	die;
	// wp_redirect( 'https://' )
}

/*
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
	wp_redirect( 'https://app.nwcua.org/logout' );
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
*/



// display the my account/login links based on user state.
function account_button() {

	// set up a global for the current user info
	global $current_user;

	// get the referer
	$referer = ( isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

	// if the user is logged in.
	if ( isset( $_SESSION['sf_user'] ) ) {
		?><a href="https://staging-nwcua.cs14.force.com/s/" class='account button'>My Account</a><?php
	} else {
		?><a href="https://staging-nwcua.cs14.force.com/s/redirect-with-url-params?url=<?php print $referer ?>" class='account button'>Log In</a><?php 
	}

}


/*
// hide the admin toolbar for all users except administrators
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if ( !current_user_can( 'administrator' ) && !is_admin() ) {
		show_admin_bar( false );
	}
}


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

	global $wpdb;

	// get wp user
	$user = get_user_by( 'login', $username );

	// set up a credential array in 
	$credentials = array(
		"username" => $username,
		"password" => $password
	);

	// get response from associo api
	$associo_user = json_decode( call_associo_api( 'account/authenticate/', $credentials ) );

	// debug WP user
	// print_r( $user );

	// show what we get from associo without allowing the rest to happen
	// print_r( $associo_user );

	// die so we can see output instead of redirecting.
	// die;


	// if the response is a valid user object
	if ( isset( $associo_user->username ) ) {


		// if the user IDs of the WP and Associo users don't match, let's delete the old WP user.
		if ( isset( $user->ID ) ) {
			if ( $associo_user->id != $user->ID ) {

				// build a delete query
				$delete_user = 'DELETE FROM `nwcua_users` WHERE ID=' . $user->ID . ';';

				// delete the old user
				$wpdb->query( $delete_user );

				// build a delete query
				$delete_user_meta = 'DELETE FROM `nwcua_usermeta` WHERE user_id=' . $user->ID . ';';

				// delete the old user
				$wpdb->query( $delete_user_meta );

				// select the associo user ID from WP to see if we have a user for the correct ID
				$user = get_user_by( 'id', $associo_user->id );
			}
		}


		// let's see if the user is empty
		if ( empty( $user ) ) {

			// build an insert query
			$insert_user = 'INSERT INTO `nwcua_users` ( `ID`, `user_login`, `user_pass`, `user_email`, `user_nicename`, `user_registered`, `display_name` ) VALUES ( ' . $associo_user->id . ', "' . $associo_user->username . '", "' . md5( $password ) . '", "' . $associo_user->email . '", "' . $associo_user->username . '", "' . date( "Y-m-d H:i:s", strtotime( $associo_user->created_at ) ) . '", "' . $associo_user->first_name . ' ' . $associo_user->last_name . '" );';

			// insert the user
			$wpdb->query( $insert_user );

			// get the new user so we can check roles.
			$user = get_user_by( 'id', $associo_user->id );

			// adjust the roles
			if ( $associo_user->member ) {
				$user->set_role( 'member' );
			}

			// adjust the trial role if applicable.
			if ( $associo_user->memberships[0]->type_id == 74 ) {
				$user->set_role( 'trial' );
			}

			// adjust the regulator role if applicable.
			if ( $associo_user->memberships[0]->type_id == 38 || $associo_user->memberships[0]->type_id == 76 ) {
				$user->set_role( 'regulator_compliance_access' );
			}

			// get the new user so we can check roles.
			$user = get_user_by( 'id', $associo_user->id );

		} else if ( $user->user_login != $associo_user->username ) {

			// build an update query
			$update_user = 'UPDATE `nwcua_users` SET 
				user_login="' . $associo_user->username . '", 
				user_pass="' . md5( $password ) . '", 
				user_nicename`="' . $associo_user->username . '", 
				user_email="' . $associo_user->email . '", 
				display_name="' . $associo_user->first_name . ' ' . $associo_user->last_name . '" 
				WHERE ID=' . $associo_user->id . ';';

			// update the user
			$wpdb->query( $update_user );

			// adjust the roles
			if ( $associo_user->member ) {
				$user->set_role( 'member' );
			}

			// adjust the trial role if applicable.
			if ( $associo_user->memberships[0]->type_id == 74 ) {
				$user->set_role( 'trial' );
			}

			// adjust the regulator role if applicable.
			if ( $associo_user->memberships[0]->type_id == 38 || $associo_user->memberships[0]->type_id == 76 ) {
				$user->set_role( 'regulator_compliance_access' );
			}
			

			// get the user
			$user = get_user_by( 'login', $associo->username );

		}

		if ( !in_array( 'member', $user->roles ) ) {

			// adjust the roles
			if ( $associo_user->member ) {
				$user->set_role( 'member' );
			}

			// adjust the trial role if applicable.
			if ( $associo_user->memberships[0]->type_id == 74 ) {
				$user->set_role( 'trial' );
			}

			// adjust the regulator role if applicable.
			if ( $associo_user->memberships[0]->type_id == 38 || $associo_user->memberships[0]->type_id == 76 ) {
				$user->set_role( 'regulator_compliance_access' );
			}

			$user = get_user_by( 'id', $user->ID );

		}

		if ( !in_array( 'trial', $user->roles ) ) {

			// adjust the trial role if applicable.
			if ( $associo_user->memberships[0]->type_id == 74 ) {
				$user->set_role( 'trial' );
			}

			$user = get_user_by( 'id', $user->ID );

		}

		if ( !in_array( 'regulator_compliance_access', $user->roles ) ) {

			// adjust the regulator role if applicable.
			if ( $associo_user->memberships[0]->type_id == 38 || $associo_user->memberships[0]->type_id == 76 ) {
				$user->set_role( 'regulator_compliance_access' );
			}

			$user = get_user_by( 'id', $user->ID );

		}

		// set the user's Associo token before returning
		$user->token = $associo_user->token;
		$user->associo_user = $associo_user;

		// return the user object
		return $user;

	} else {

		// no user found
		return false;

	}
}



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
		$form .= wp_login_form( array('label_username' => 'Username', 'echo' => false, 'redirect' => $redirect, 'value_remember' => 1 ) );
		$form .= '<p><a href="https://app.nwcua.org/forgot_password">Lost/forgotten Password</a></p>';
	} else {
		$form .= "You are currently logged in, please visit <a href='https://app.nwcua.org/account/'>your account</a> for more options. To log out completely, <a href='/logout'>click here</a>.";
	}

	return $form;

}
add_shortcode('pure-login-form', 'login_form_shortcode');



function email_mime_type () {
	return 'text/html';
}
add_filter ( 'wp_mail_content_type', 'email_mime_type');		



function current_url_shortcode() {
	return get_home_url() . add_query_arg( '_', false );
}
add_shortcode('current-url', 'current_url_shortcode');



function current_url_encoded_shortcode() {
	return urlencode( get_home_url() . add_query_arg( '_', false ) );
}
add_shortcode('current-url-encoded', 'current_url_encoded_shortcode');
*/



function is_member() {

	global $post;
	$roles = get_post_meta( $post->ID, '_members_access_role' );
	if ( !empty( $roles ) ) {
		update_post_meta( $post->ID, CMB_PREFIX . 'members-only', 'on' );
	}

	// see if there is a member's only value
	if ( has_cmb_value( 'members-only' )  ) {

		// if the content requires membership
		if ( get_cmb_value( 'members-only' ) == 'on' ) {

			if ( isset( $_SESSION['sf_user'] ) ) {

				// get the user
				$user = $_SESSION['sf_user'];

				// see if the user is an admin
				//if ( in_array( 'administrator', $user->roles ) ) return true;

				// see if the user is an editor
				if ( !empty( $user['membershiptype'] ) ) return true;

			}

			// they don't have any of the required roles, they can't access it.
			return false;

		} else {

			// members only checkbox exists and is unchecked, they can access
			return true;
		}

	} else {

		// there's no value available for the member's only checkbox, they can access.
		return true;
	}

}


function do_member_error() {
	?>
	<div class="three-quarter">
		<h3>A membership is required to view this content.</h3>
	</div>
	<?php
}

