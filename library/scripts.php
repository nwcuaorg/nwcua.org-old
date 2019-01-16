<?php



// include the main.js script in the header on the front-end.
function p_scripts() {

	// add a new javascript enqueue for our main theme stylesheet.
	wp_enqueue_script( 'p-main-js', get_stylesheet_directory_uri().'/js/main.js?ver=12', array( 'jquery' ), false, true );

	// load dashicons on the front end.
	wp_enqueue_style( 'dashicons' );
	
}
add_action( 'wp_enqueue_scripts', 'p_scripts' );



// add page features to enable post tags and categories.
function add_page_features() {

	// add tag metabox to page
	register_taxonomy_for_object_type('post_tag', 'page'); 

	// add category metabox to page
	register_taxonomy_for_object_type('category', 'page');  
	
	// add support for excerpts in pages
	add_post_type_support( 'page', 'excerpt' );
	
}
add_action( 'init', 'add_page_features' );



?>