<?php


if ( function_exists( 'add_theme_support' ) ) {

	// enable featured image
	add_theme_support( 'post-thumbnails' ); 
	
	// set the default thumbnail size
	set_post_thumbnail_size( 800, 600, true );

}

if ( function_exists( 'add_image_size' ) ) {

	// set the default story thumbnail size
	add_image_size( 'large', 800, 600, true );

}



// returns a boolean indicating if the path provided leads to an image.
function p_is_image( $img_path ) {

    // valid image extensions
    $valid_extensions = array( 'jpg', 'png', 'gif' );

    // get file info
    $info = pathinfo( $img_path );

    // return a test of the extension against our array.
    return in_array( $info['extension'], $valid_extensions );
    
}


?>