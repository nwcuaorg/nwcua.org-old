<?php



// calculator shortcode
function post_list_func( $atts ) {
    
    // get the attributes
    $a = shortcode_atts( array(
        'cat' => '',
        'tag' => '',
        'posts_per_page' => 6
    ), $atts );

    // get posts from the database
	$the_query = new WP_Query( $a );

	// The Loop
	if ( $the_query->have_posts() ) {
		$post_list_code = '<div class="home-list-small">';
		$post_list_code .= '<ul>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			global $post;
			$post_list_code .= '<div class="entry priority-' . get_cmb_value( 'priority' ) . '">';
			$post_list_code .= '<div class="entry-image">';
			$post_list_code .= get_edit_post_link( 'Edit' );
			$post_list_code .= '<a href="' . get_the_permalink() . '">';

			$categories = get_the_category();

			// get thumbnail url
			$thumbnail_id = get_post_thumbnail_id();
			//$thumbnail_url = wp_get_attachment_url( $thumbnail_id );
			$thumbnail_url = get_the_post_thumbnail_url( $post, 'large' );
			// $thumbnail_info = get_the_post_thumbnail( $post, 'large' );
			// print_r( $thumbnail_info ); die;

			$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

			if ( !empty( $thumbnail_url ) ) {
				$post_list_code .= '<img src="' . $thumbnail_url . '" alt="' . $image_alt . '" />';
			} else {
				$post_list_code .= '<img src="' . get_default_thumbnail( $categories[0]->term_id ) . '" alt="' . $image_alt . '" />';
			}

			if ( !empty( $categories ) ) { 
				$color = get_category_color( $categories[0]->term_id );
				$post_list_code .= '<div class="post-category bg-' . $color . '">';
				$post_list_code .= get_cat_name( $categories[0]->term_id );
				$post_list_code .= '</div>';
			}
			$post_list_code .= '</a></div><div class="description">';
			$post_list_code .= '<h3><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
			$post_list_code .= get_the_excerpt();
			$post_list_code .= '</div></div>';
		}
		$post_list_code .= '</ul>';
		$post_list_code .= '</div>';
		/* Restore original Post Data */
		wp_reset_postdata();
	} else {
		// no posts found
	}

    // get the post list together
    // $post_list_code = '';


    // return the post list code.
    return $post_list_code;
}
add_shortcode( 'post-list', 'post_list_func' );



