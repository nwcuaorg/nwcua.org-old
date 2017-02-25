<?php


// let's not include the default bbPress stylesheet, so we can set all our own styles and be super cool with one less resource request against the server.
add_action( 'wp_print_styles', 'deregister_bbpress_styles', 15 );
function deregister_bbpress_styles() {
	wp_deregister_style( 'bbp-default' );
}


?>