<?php


if ( function_exists( 'add_theme_support' ) ) {

	// enable featured image
	add_theme_support( 'post-thumbnails' ); 
	
	// set the default thumbnail size
	set_post_thumbnail_size( 500, 500, true );

}

if ( function_exists( 'add_image_size' ) ) {

	// set the default story thumbnail size
	add_image_size( 'story-thumbnail', 500, 600, true );

	// set a size for smaller story images
	add_image_size( 'story-image-small', 500, 500, true );
	add_image_size( 'story-image-large', 1000, 500, true );

}





// function to resize images
function p_image_resize( $url, $width, $height = null, $crop = null ) {

    //validate inputs
    if (!$url OR !$width)
        return false;
    
    if ( stristr( $url, 'http' ) ) {
        $url = str_replace( 'http://' . $_SERVER['HTTP_HOST'], '', $url );
    }

    $rel_path = $url;

    $upload_url = get_bloginfo( 'siteurl' ) . '/wp-content/uploads/';
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/';

    //define path of image
    $img_path = $_SERVER['DOCUMENT_ROOT'] . $url;

    //check if img path exists, and is an image indeed
    if (!file_exists($img_path) OR !getimagesize($img_path))
        return false;

    //get image info
    $info = pathinfo($img_path);
    $ext = $info['extension'];
    list( $orig_w, $orig_h ) = getimagesize( $img_path );

    //get image size after cropping
    $dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
    $dst_w = $dims[4];
    $dst_h = $dims[5];

    //use this to check if cropped image already exists, so we can return that instead
    $suffix = "{$dst_w}x{$dst_h}";
    $dst_rel_path = str_replace('.' . $ext, '', $url);
    $destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

    if (!$dst_h) {
    //can't resize, so return original url
        $img_url = $url;
        $dst_w = $orig_w;
        $dst_h = $orig_h;
    }
    //else check if cache exists
    else if ( file_exists( $destfilename) && getimagesize($destfilename)) {
        
        $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";

    }
    //else, we resize the image and return the new resized image url
    else {

        $resized_img_path = image_resize($img_path, $width, $height, $crop);

        if (!is_wp_error($resized_img_path)) {
            $resized_rel_path = str_replace($upload_dir, '', $resized_img_path);
            $img_url = $upload_url . $resized_rel_path;
        } else {
            return false;
        }
  
    }
    
    $image = $img_url;

    return $image;
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