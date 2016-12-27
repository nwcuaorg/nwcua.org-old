<?php


// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'flush_rewrite_rules' );



// let's create the function for the custom type
function person_post_type() { 


	// creating (registering) the custom type 
	register_post_type( 'person', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 
			'labels' => array(
				'name' => __( 'People', 'ptheme' ), /* This is the Title of the Group */
				'singular_name' => __( 'Person', 'ptheme' ), /* This is the individual type */
				'all_items' => __( 'All People', 'ptheme' ), /* the all items menu item */
				'add_new' => __( 'Add New', 'ptheme' ), /* The add new menu item */
				'add_new_item' => __( 'Add New Person', 'ptheme' ), /* Add New Display Title */
				'edit' => __( 'Edit', 'ptheme' ), /* Edit Dialog */
				'edit_item' => __( 'Edit Person', 'ptheme' ), /* Edit Display Title */
				'new_item' => __( 'New Person', 'ptheme' ), /* New Display Title */
				'view_item' => __( 'View Person', 'ptheme' ), /* View Display Title */
				'search_items' => __( 'Search People', 'ptheme' ), /* Search Custom Type Title */ 
				'not_found' =>  __( 'Nothing found in the database.', 'ptheme' ), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __( 'Nothing found in Trash', 'ptheme' ), /* This displays if there is nothing in the trash */
				'parent_item_colon' => '',
				'delete_posts' => 'Delete People'
			), /* end of arrays */
			'description' => __( 'Manage the people listed on the site.', 'ptheme' ), /* Custom Type Description */
			'public' => true,
			'show_in_menu' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'show_in_menu' => true, 
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-groups', /* the icon for the custom post type menu */
			'rewrite'	=> array( 
				'slug' => 'bio', 
				'with_front' => false 
			), /* you can specify its url slug */
			'has_archive' => false, /* you can rename the slug here */
			'capability_type' => 'person',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' )
		) /* end of options */
	); /* end of register post type */
	
}


// adding the function to the Wordpress init
add_action( 'init', 'person_post_type');



// add person caps
function add_person_caps() {

    // gets the admin role
    $role = get_role( 'administrator' );

    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'read_person' );
    $role->add_cap( 'edit_person' );
    $role->add_cap( 'delete_person' );
    $role->add_cap( 'edit_person' );
    $role->add_cap( 'edit_others_person' );
    $role->add_cap( 'publish_person' );
    $role->add_cap( 'read_private_person' );
    $role->add_cap( 'edit_private_person' );
    $role->add_cap( 'edit_published_person' );

}
add_action( 'admin_init', 'add_person_caps');



// now let's add custom categories (these act like categories)
register_taxonomy( 'person_cat', 
	array( 'person' ), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true, it acts like categories */
		'labels' => array(
			'name' => __( 'Groups', 'ptheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'Group', 'ptheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search Groups', 'ptheme' ), /* search title for taxomony */
			'all_items' => __( 'All Groups', 'ptheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Group', 'ptheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Group:', 'ptheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Group', 'ptheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Group', 'ptheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Group', 'ptheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Group Name', 'ptheme' ) /* name title for taxonomy */
		),
		'show_admin_column' => true, 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 
			'slug' => 'group'
		)
	)
);




function people_shortcode( $atts = [], $content = null, $tag = '' ) {
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
 
    // override default attributes with user attributes
    $people_atts = shortcode_atts([
                                     'title' => 'WordPress.org',
                                 ], $atts, $tag);
 
    // start output
    $o = '';
 
    // start box
    $o .= '<div class="person-list">';
  
    $the_people_query = new WP_Query( array(
		'post_type' => 'person',
		'tax_query' => array(
			'taxonomy' => 'person_cat',
			'field' => 'slug',
			'terms' => $people_atts['group'],
		),
	) );

    // end box
    $o .= '</div>';
 
    // return output
    return $o;
}
 

function people_shortcodes_init() {
    add_shortcode('people', 'people_shortcode');
}
add_action('init', 'people_shortcodes_init');



?>