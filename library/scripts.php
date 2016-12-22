<?php


// include the main.js script in the header on the front-end.
function p_scripts() {
	wp_enqueue_script( 'p-main-js', get_stylesheet_directory_uri().'/js/main.js?ver=3', array( 'jquery' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'p_scripts' );




function add_taxonomies_to_pages() {

	// Add tag metabox to page
	register_taxonomy_for_object_type('post_tag', 'page'); 
	// Add category metabox to page
	register_taxonomy_for_object_type('category', 'page');  
	
}
add_action( 'init', 'add_taxonomies_to_pages' );



?>