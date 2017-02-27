<?php


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


function update_user_meta_item( $associo_user, $field_label ) {
	if ( !empty( $associo_user->$field_label ) ) {
		update_user_meta( $associo_user->id, $field_label, $associo_user->$field_label );
	}
}


// add a new password encryption schema that includes the username.
add_filter( 'authenticate', 'nwcua_signon', 30, 3 );
function nwcua_signon( $user, $username, $password ) {

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

	// show what we get from associo without allowing the rest to happen
	// print_r( $associo_user ); die;

	// if the response is a valid user object
	if ( isset( $associo_user->username ) ) {

		if ( empty( $user ) ) {

			// build an insert query
			$insert_user = 'INSERT INTO `nwcua_users` ( `ID`, `user_login`, `user_pass`, `user_email`, `user_nicename`, `user_registered`, `display_name` ) VALUES ( ' . $associo_user->id . ', "' . $associo_user->username . '", "' . md5( $associo_user->username ) . '", "' . $associo_user->email . '", "' . $associo_user->username . '", "' . date( "Y-m-d H:i:s", strtotime( $associo_user->created_at ) ) . '", "' . $associo_user->first_name . ' ' . $associo_user->last_name . '" );';

			// insert the user
			$wpdb->query( $insert_user );

			// set new user role.
			wp_update_user( array( 'ID' => $associo_user->id, 'role' => ( $associo_user->member ? 'member' : 'subscriber' ) ) );

			// retrieve our new user
			$user = get_user_by( 'login', $username );

		} else if ( $user->user_login != $associo_user->username ) {

			// build an insert query
			$insert_user = 'UPDATE `nwcua_users` SET `user_login`="' . $associo_user->username . '", `user_nicename`="' . $associo_user->username . '", `user_email`="' . $associo_user->email . '", `display_name`="' . $associo_user->first_name . ' ' . $associo_user->last_name . '" WHERE `ID`=' . $associo_user->id . ';';

			// insert the user
			$wpdb->query( $update_user );

			// set updated user role.
			wp_update_user( array( 'ID' => $associo_user->id, 'role' => ( $associo_user->member ? 'member' : 'subscriber' ) ) );

			// retrieve our new user
			$user = get_user_by( 'login', $username );

		}

		// update some user meta data
		// update_user_meta_item( $associo_user, 'mailing_address_1' );
		
		// one final user retrieval to make sure we have all the fields
		$user = get_user_by( 'login', $username );

		// show final user object before returning, and die so we can inspect it.
		// print_r( $user ); die;

		wp_set_auth_cookie( $user->ID, 1, 1 );

		// return the new user
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



// if the login fails for any reason, redirect the user back
// to the login form with a parameter for the login error.
function failed_login_redirect( $username ) {

    $referrer = $_SERVER["HTTP_REFERER"];
    $redirect_to = ( isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : get_home_url() );

    if ( $referrer && ! strstr($referrer, 'wp-login') && ! strstr($referrer,'wp-admin') ) {
    	$redirect_url = add_query_arg( 'redirect_to', $redirect_to, add_query_arg( 'login-error', 'true', $referrer ) );
        wp_redirect( $redirect_url );
        exit;
    }

}
add_action( 'wp_login_failed', 'failed_login_redirect' );



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
		// $form .= '<p><a href="' . $reset_url . '">Lost/forgotten Password</a></p>';
		$form .= '<p><a href="https://app.nwcua.org/forgot_password">Lost/forgotten Password</a></p>';
	} else {
		$form .= "You are currently logged in, please visit <a href='" . $account_url . "'>your account</a> for more options.";
	}

	return $form;

}
add_shortcode('pure-login-form', 'login_form_shortcode');



// let's create a shortcode that displays a login form on the front-end.
function reset_form_shortcode( $atts, $content = null ) {

    $account_page = get_post( pure_get_option( 'account-page' ) );
    $account_url = get_permalink( $account_page->ID );
 	
 	// they're logged in, what do they need a password reset for?!
	if ( is_user_logged_in() ) return "You are already logged in.";

	// empty form variable.
	$form = '';

    $reset_page = get_post( pure_get_option( 'reset-page' ) );
    $reset_url = get_permalink( $reset_page->ID );

    $action = ( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '' );

    // handle requests
 	if ( isset( $_REQUEST['reset'] ) ) {

	    // the reset request has been sent
 		$form .= 'A password reset email has been sent to the email address we have on record. Please click the link in it to continue the reset process.';

 	} else if ( $action == 'rp' && isset( $_REQUEST['key'] ) && isset( $_REQUEST['login'] ) ) {

 		// if the password doesn't match, display an error.
 		if ( isset( $_REQUEST['mismatch'] ) ) $form .= '<div class="error reset">The two passwords you entered do not match. Please try again.</div>'; 

 		// if they clicked the link to reset, show the password and confirmation password fields.
 		$form .= '<form name="resetpassform" id="resetpassform" action="' . $reset_url . '?action=resetpass" method="post" autocomplete="off">
			<input type="hidden" name="user_login" value="' . $_REQUEST['login'] . '" />
			<p>
				<label for="pass1">New password<br />
				<input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" /></label>
			</p>
			<p>
				<label for="pass2">Confirm new password<br />
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" /></label>
			</p>
			<input type="hidden" name="rp_key" value="' . $_REQUEST['key'] . '" />
			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" value="Reset Password" /></p>
		</form>';

 	} else {

 		// if they're just arriving at this page, display a lost password form.
	 	$form .= '<form name="lostpasswordform" id="lostpasswordform" action="' . get_home_url() . '/wp-login.php?action=lostpassword" method="post">
			<p>
				<label for="user_login">Username or Email:<br>
				<input type="text" name="user_login" id="user_login" class="password-reset" value="" autocomplete="off"></label>
			</p>
			<input type="hidden" name="redirect_to" value="' . $reset_url . '?action=reset" />
			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" value="Get New Password"></p>
		</form>';

	}
 
	return $form;

}
add_shortcode('pure-reset-password', 'reset_form_shortcode');



// function to reset the password to the new one.
function pure_reset_password( $user, $new_pass ) {
	/**
	 * Fires before the user's password is reset.
	 *
	 * @since 1.5.0
	 *
	 * @param object $user     The user.
	 * @param string $new_pass New user password.
	 */
	do_action( 'password_reset', $user, $new_pass );

	wp_set_password( $new_pass, $user->ID );
	update_user_option( $user->ID, 'default_password_nag', false, true );

	$update_credentials = array(
		'password' => $new_pass
	);

	$update_user = call_associo_api( 'account/' . $user->ID, $update_credentials );
	
	print_r( $update_user ); die;

	wp_password_change_notification( $user );

	$login_page = get_post( pure_get_option( 'login-page' ) );
	$login_url = get_permalink( $login_page->ID );

    wp_redirect( $login_url . '?reset=success' );
    exit;

}



// set a handler for reset
function reset_password_handler() {

	// get the reset page from the db
	$reset_page = get_post( pure_get_option( 'reset-page' ) );
	$reset_url = get_permalink( $reset_page->ID );

	// get the login parameter
	$login = ( isset( $_POST['user_login'] ) ? $_POST['user_login'] : '' );

	// grab the action parameter
	$action = ( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '' );

	// handle based on the action
	if ( $action == 'resetpass' && !empty( $login ) ) {

		// check if the passwords match
		if ( $_POST['pass1'] != $_POST['pass2'] ) {

			// if the passwords don't match, redirect with an error parameter
			wp_redirect( $reset_url . '?action=rp&key=' . $_POST['rp_key'] . '&login=' . $_POST['user_login'] . "&mismatch=1" );
			exit;

		}

		// retrieve user data
		$userdata = get_user_by( 'login', $login );

		// set the new password.
		pure_reset_password( $userdata, $_POST['pass1'] );

	}
}
add_action( 'init', 'reset_password_handler', 9995 );



// update the password retrieval message
function pure_retrieve_password_message( $message, $key ){
    
	global $wpdb;
				
	// -> Get the target username or e-mail from the post.
	$user_login = htmlentities ( $_POST['user_login'] );
	
	// -> Get the required variables.
	$user_id = $wpdb -> get_results ( "SELECT ID FROM $wpdb->users WHERE ( user_login='$user_login' OR user_email='$user_login' ) LIMIT 1;" );

	$user_id = $user_id[0]->ID;

	// -> Get the new user's ID.			
	$user_data = new WP_User( $user_id );
			
    // get the message option from our metabox.
 	$message = pure_get_option( 'reset-email' );

	// get the reset page from the db
	$reset_page = get_post( pure_get_option( 'reset-page' ) );
	$reset_url = get_permalink( $reset_page->ID );

    // replace shortcodes in the email message body.
    $message = str_replace( '[password-reset-url]' , $reset_url . "?action=rp&key=$key&login=" . rawurlencode( $user_data->user_login ), $message );
    $message = str_replace( '[user-id]', $user_id, $message );
    $message = str_replace( '[first-name]', $user_data->first_name, $message );
    $message = str_replace( '[last-name]', $user_data->last_name, $message );
    $message = str_replace( '[user-login]', $user_data->user_login, $message );
    $message = str_replace( '[email]', $user_data->user_email, $message );
    $message = str_replace( '[homepage]', get_home_url(), $message );
    $message = str_replace( '[admin-email]', get_option( 'admin_email' ), $message );
    $message = str_replace( '[date]', date( 'n/j/Y' ), $message );
    $message = str_replace( '[time]', date( 'g:i a' ), $message );
	
	// -> Add line breaks to the body.
	$message = nl2br ( $message );
	
	// -> Strip out any slashes in the content.
	$message = stripslashes ( $message );
	
	// -> Return the result.
	return $message;

}
add_filter( 'retrieve_password_message', 'pure_retrieve_password_message', 11, 2 );



function email_mime_type () {
	return 'text/html';
}
add_filter ( 'wp_mail_content_type', 'email_mime_type');		


/*
// send email when user upgraded to member
function user_role_update( $user_id, $new_role ) {
    if ( $new_role == 'member' ) {
        $user_info = get_userdata( $user_id );
        $to = $user_info->user_email;

	    // get the message option from our metabox.
	 	$subject = pure_get_option( 'member-email-subject' );

	    // get the message option from our metabox.
	 	$message = pure_get_option( 'member-email' );

	    // replace shortcodes in the email message body.
	    $message = str_replace( '[user-id]', $user_id, $message );
	    if ( !empty( $user_info->first_name ) ) $message = str_replace( '[first-name]', $user_info->first_name, $message );
	    $message = str_replace( '[last-name]', $user_info->last_name, $message );
	    $message = str_replace( '[user-login]', $user_info->user_login, $message );
	    $message = str_replace( '[email]', $user_info->user_email, $message );
	    $message = str_replace( '[homepage]', get_home_url(), $message );
	    $message = str_replace( '[admin-email]', get_option( 'admin_email' ), $message );
	    $message = str_replace( '[date]', date( 'n/j/Y' ), $message );
	    $message = str_replace( '[time]', date( 'g:i a' ), $message );
		
		// -> Add line breaks to the body.
		$message = nl2br ( $message );
		
		// -> Strip out any slashes in the content.
		$message = stripslashes ( $message );

		// send email
        wp_mail( $to, $subject, $message );
    }
}
add_action( 'set_user_role', 'user_role_update', 10, 2);

*/
?>