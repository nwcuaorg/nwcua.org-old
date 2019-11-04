<?php


// get the request URI and remove the query string
$request = str_replace( "?" . $_SERVER['QUERY_STRING'], '',  $_SERVER['REQUEST_URI'] );

// check if this is an auth request.
if ( substr( $request, 0, 5 ) == '/auth' ) {
	$_SESSION['sf_user'] = $_REQUEST;
	print_r( $_SESSION ); print session_id(); die;
	wp_redirect( 'https://nwcua.leagueinfosight.com/admin/client/is/frontend/nwcua_sso.php?' . http_build_query( $_REQUEST ) );
	exit;
}

// handle logout requests
if ( substr( $request, 0, 7 ) == '/logout' ) {
	// unset( $_SESSION['sf_user'] );
	session_destroy();
	wp_redirect( '/' );
	exit;
}



// temporary jobs update code
if ( substr( $request, 0, 11 ) == '/jobsupdate' ) {
	$posts = get_posts(array(
		'post_type' => 'job',
		'numberposts' => -1,
		'post_status' => array( 'publish', 'pending' )
	));

	foreach ( $posts as $a_post ) {
		print "Found job: " . $a_post->post_title . "<br>";
		$user_info = get_user_by( 'id', $a_post->post_author );
		print "Retrieved user: " . $user_info->user_email . "<br>";
		update_post_meta( $a_post->ID, CMB_PREFIX . 'job_creator', $user_info->user_email );
		print "Set job creator email.<br>";
	} 

	die;
}



// display the my account/login links based on user state.
function account_button() {

	// set up a global for the current user info
	global $current_user;

	// get the referer
	$referer = ( isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

	// if the user is logged in.
	if ( isset( $_SESSION['sf_user'] ) ) {
		?><a href="https://nwcua.force.com/s/my-account" class='account button'>My Account</a><?php
	} else {
		?><a href="https://nwcua.force.com/s/redirect-with-url-params?url=<?php print $referer ?>" class='account button'>Log In</a><?php 
	}
	print_r( $_SESSION ); print session_id(); die;

}



// membership check - boolean function, that checks to see if there were previous access roles and adds the appropriate new meta.
function is_member() {

	global $post;

	// get old member roles meta data
	$roles = get_post_meta( $post->ID, '_members_access_role' );

	// check for new member
	$new_roles = get_cmb_value( 'members-only' );

	// if the old member roles are set, and the new ones aren't, let's automatically fix up the new 'members-only' meta data.
	if ( !empty( $roles ) && empty( $new_roles ) ) {
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



// member error
function do_member_error() {
	?>
	<div class="three-quarter">
		<h3>A membership is required to view this content.</h3>
	</div>
	<?php
}


