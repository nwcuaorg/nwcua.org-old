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
					<?php print wpautop( $content ); ?>
					<?php if ( $accordion_location == 'bottom' ) { ?></div><?php } ?>
				</div>
			</div>
				<?php
				$count++;
			}
		}
		product_accordion();
		partner_accordion();
		?>
		</div>
		<?php
	}
}



function product_accordion() {
	if ( has_cmb_value( 'prod_accordion_title' ) && has_cmb_value( 'prod_accordion_products' ) ) {
		?>
			<div class="accordion-box open bg-<?php show_cmb_value( 'prod_accordion_color' ) ?>">
				<div class="accordion-box-title">
					<div class="wrap">
						<?php if ( has_cmb_value( 'prod_accordion_icon' ) ) { ?>
						<span class="accordion-box-title-icon"><img src="<?php show_cmb_value( 'prod_accordion_icon' ) ?>"></span>
						<?php } ?>
						<h4><?php show_cmb_value( 'prod_accordion_title' ) ?></h4>
					</div>
				</div>
				<div class="accordion-box-content">
					<div class="wrap">
						<div class="products">
							<div class="product-list">
								<?php the_product_list( get_cmb_value( 'prod_accordion_products' ) ); ?>
							</div>
							<button class="product-nav previous">Previous</button>
							<button class="product-nav next">Next</button>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
}



function partner_accordion() {
	if ( has_cmb_value( 'part_accordion_title' ) && has_cmb_value( 'part_accordion_partners' ) ) {
		?>
			<div class="accordion-box open bg-<?php show_cmb_value( 'part_accordion_color' ) ?>">
				<div class="accordion-box-title">
					<div class="wrap">
						<?php if ( has_cmb_value( 'part_accordion_icon' ) ) { ?>
						<span class="accordion-box-title-icon"><img src="<?php show_cmb_value( 'part_accordion_icon' ) ?>"></span>
						<?php } ?>
						<h4><?php show_cmb_value( 'part_accordion_title' ) ?></h4>
					</div>
				</div>
				<div class="accordion-box-content">
					<div class="wrap partner-logos group">
					<?php the_partner_list( get_cmb_value( 'part_accordion_partners' ) ); ?>
					</div>
				</div>
			</div>
		<?php
	}
}



function has_partner_or_product_accordion() {
	if (( has_cmb_value( 'prod_accordion_title' ) && has_cmb_value( 'prod_accordion_products' ) ) ||
		( has_cmb_value( 'part_accordion_title' ) && has_cmb_value( 'part_accordion_partners' ) )) return true;
		else return false;
}


?>