<?php


function the_notice_bar() {

	// narrow content
    $notice_text = get_post_meta( get_the_ID(), CMB_PREFIX . "notice-text", 1 );
    $notice_link = get_post_meta( get_the_ID(), CMB_PREFIX . "notice-link", 1 );
    $notice_color = get_post_meta( get_the_ID(), CMB_PREFIX . "notice-color", 1 );

	if ( !empty( $notice_text ) ) {
		?>
	<div class="notice-bar-container <?php print $notice_color; ?> <?php print md5( $notice_text ); ?>">
		<div class="wrap">
			<i class="fa fa-close">X</i>
			<?php if ( !empty( $notice_link ) ) { ?><a href="<?php print $notice_link ?>"><?php } ?>
			<div class="notice-bar-text">
				<?php print do_shortcode( $notice_text ) ?>
			</div>
			<?php if ( !empty( $notice_link ) ) { ?></a><?php } ?>
		</div>
	</div>
		<?php
	}

}



function notice_metabox( $meta_boxes ) {

    // notice metabox
    $notice_metabox = new_cmb2_box( array(
        'id' => 'notice_metabox',
        'title' => 'Notice Bar',
        'desc' => "An notice bar on the top to indicate local news or bring people into a specific area of the site when there's something you want them to read.",
        'object_types' => array( 'page' ), // post type
        'context' => 'normal',
        'priority' => 'high',
    ));

    $notice_metabox->add_field( array(
        'name' => 'Notice Text',
        'id'   => CMB_PREFIX . 'notice-text',
        'type' => 'text',
    ) );

    $notice_metabox->add_field( array(
        'name' => 'Link',
        'desc' => 'Where should the notice bar link to.',
        'id'   => CMB_PREFIX . 'notice-link',
        'type' => 'text',
    ) );

    $notice_metabox->add_field( array(
        'name' => 'Color',
        'desc' => 'What color should the notice bar be?',
        'id'   => CMB_PREFIX . 'notice-color',
        'type' => 'select',
        'options' => array(
            'teal' => 'Teal',
        	'river' => 'River',
        	'aqua' => 'Aqua',
        	'seafoam' => 'Seafoam',
            'navy' => 'Navy',
            'orange' => 'Orange',
            'lime' => 'Lime',
            'forest' => 'Forest',
            'green' => 'Green',
            'gold' => 'Gold',
            'red' => 'Red',
            'yellow' => 'Yellow',
            'grey' => 'Grey',
            'grey-dark' => 'Dark Grey'
        )
    ) );


}
add_filter( 'cmb2_init', 'notice_metabox' );

