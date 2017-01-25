<?php


function the_accordion() {

	$accordion_location = get_cmb_value( 'accordion_location' );

	// get the slides
	$boxes = get_post_meta( get_the_ID(), CMB_PREFIX . "accordion", 1 );

	if ( !empty( $boxes ) ) {
		?>
		<div class="accordion">
		<?php
		$count = 0;
		foreach ( $boxes as $key => $box ) {
			if ( !empty( $box["title"] ) && !empty( $box["content"] ) ) {

				// store the title and subtitle
				$title = ( isset( $box["title"] ) ? $box["title"] : '' );
				$icon = ( isset( $box["icon"] ) ? $box["icon"] : '' );
				$color = ( isset( $box["color"] ) ? $box["color"] : 'teal' );
				$state = ( isset( $box["state"] ) ? $box["state"] : 'closed' );
				$content = ( isset( $box["content"] ) ? $box["content"] : '' );

				?>
			<div class="accordion-box<?php print ( $state == 'open' ? ' open' : '' ); ?> bg-<?php print $color ?>">
				<div class="accordion-box-title">
					<?php if ( $accordion_location == 'bottom' ) { ?><div class="wrap"><?php } ?>
					<?php if ( !empty( $icon ) ) { ?>
					<span class="accordion-box-title-icon"><img src="<?php print $icon ?>"></span>
					<?php } ?>
					<h4><?php print $title ?></h4>
					<?php if ( $accordion_location == 'bottom' ) { ?></div><?php } ?>
				</div>
				<div class="accordion-box-content">
					<?php if ( $accordion_location == 'bottom' ) { ?><div class="wrap"><?php } ?>
					<?php print wpautop( do_shortcode( $content ) ); ?>
					<?php if ( $accordion_location == 'bottom' ) { ?></div><?php } ?>
				</div>
			</div>
				<?php
				$count++;
			}
		}
		?>
		</div>
		<?php
	}
}


?>