<?php

// start a session
if ( session_id() == '' ) session_start(); 


// set a custom field prefix
define( "CMB_PREFIX", "_p_" );


// include the content type
include( "library/post-type/job.php" );
include( "library/post-type/event.php" );
include( "library/post-type/person.php" );


// include some theme-related things
include( "library/menus.php" );
include( "library/scripts.php" );
include( "library/categories.php" );
include( "library/svgs.php" );
include( "library/forums.php" );
include( "library/post-list.php" );
include( "library/notice.php" );


// an extra image manipulation function
include( "library/images.php" );


// include our metaboxes library
include( "library/metabox.php" );
include( "library/metabox-theme.php" );


// include quote metaboxes/functions
include( "library/title.php" );
include( "library/showcase.php" );
include( "library/accordion.php" );

// include the login library
// include( "library/associo.php" );
// include( "library/salesforce.php" );
include( "library/login.php" );

// [anchor] shortcode
function p_anchor( $atts, $content = null, $code = "" ) {
    return '<a name="'.$content.'"></a>';
}
add_shortcode('anchor' , 'p_anchor' );


// enable oembed and shortcodes in text widgets (only if the object exists)
if ( isset( $wp_embed ) ) {
    add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
    add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );
}


// exclude some categories.
function exclude_posts_from_recentPostWidget_by_cat() {
    $exclude = array( 'cat' => '-7850, -7851, -7852, -7853' );
    return $exclude;
}
add_filter('widget_posts_args','exclude_posts_from_recentPostWidget_by_cat');


// pagination
function pagination( $prev = '&laquo;', $next = '&raquo;' ) {
    global $wp_query, $wp_rewrite;

    $posts_per_page = ( isset( $wp_query->query_vars['posts_per_page'] ) ? $wp_query->query_vars['posts_per_page'] : 14 );

    $total = ceil( $wp_query->found_posts / $posts_per_page );

    $current = ( $wp_query->query_vars['paged'] > 1 ? $wp_query->query_vars['paged'] : 1 );

    $pagination = array(
        'base' => @add_query_arg('paged','%#%'),
        'format' => '',
        'total' => $total,
        'current' => $current,
        'prev_text' => __($prev),
        'next_text' => __($next),
        'type' => 'plain'
    );

    if ( $wp_rewrite->using_permalinks() ) $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

    if ( !empty($wp_query->query_vars['s']) ) $pagination['add_args'] = array( 's' => get_query_var( 's' ) );

    echo paginate_links( $pagination );
}

