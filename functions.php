<?php


error_reporting( E_ALL & ~E_NOTICE & ~E_STRICT );
ini_set( "display_errors", 1 );


// set a custom field prefix
define( "CMB_PREFIX", "_p_" );


// include the login library
include( "library/login.php" );


// include the content type
include( "library/post-type/job.php" );
include( "library/post-type/event.php" );


// include some theme-related things
include( "library/menus.php" );
include( "library/scripts.php" );
include( "library/categories.php" );


// an extra image manipulation function
include( "library/images.php" );


// include our metaboxes library
include( "library/metabox.php" );
include( "library/metabox-theme.php" );


// include quote metaboxes/functions
include( "library/title.php" );
include( "library/showcase.php" );
include( "library/accordion.php" );



// [anchor] shortcode
function p_anchor( $atts, $content = null, $code = "" ) {
    return '<a name="'.$content.'"></a>';
}
add_shortcode('anchor' , 'p_anchor' );


// enable oembed and shortcodes in text widgets
add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );


// pagination
function pagination($prev = '&laquo;', $next = '&raquo;') {
    global $wp_query, $wp_rewrite;

    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

    $pagination = array(
        'base' => @add_query_arg('paged','%#%'),
        'format' => '',
        'total' => 20,
        'current' => $current,
        'prev_text' => __($prev),
        'next_text' => __($next),
        'type' => 'plain'
	);

    if ( $wp_rewrite->using_permalinks() ) $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

    if ( !empty($wp_query->query_vars['s']) ) $pagination['add_args'] = array( 's' => get_query_var( 's' ) );

    echo paginate_links( $pagination );
}



?>