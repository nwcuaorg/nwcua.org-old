<?php 

/*
Template Name: Microsite
*/

global $is_anthem; 

?><!DOCTYPE html>
<!--[if IE 7]><html class="ie ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />

<title><?php wp_title( '|', true, 'right' ); ?> Northwest Credit Union Association</title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
<link href="<?php bloginfo( "template_url" ) ?>/css/src/micro-unprefixed.css?v=1" rel="stylesheet" type="text/css">

</head>
<body <?php body_class(); ?>>

<div class="microsite-container">
	<header>

		<div class="wrap microsite-header">
			<img src="<?php show_cmb_value( 'microsite_header' ) ?>" />
		</div>

	</header>

	<section class="content">
		<div class="grid-row">
			<div class="two-third">
				<?php print apply_filters( 'the_content', get_cmb_value( 'microsite_content_one' ) ); ?>
			</div>
			<div class="third bg-<?php show_cmb_value( 'microsite_color_one' ) ?>">
				<img src="<?php show_cmb_value( 'microsite_image_one' ) ?>">
			</div>
		</div>
		<div class="grid-row">
			<div class="two-third">
				<?php print apply_filters( 'the_content', get_cmb_value( 'microsite_content_two' ) ); ?>
			</div>
			<div class="third bg-<?php show_cmb_value( 'microsite_color_two' ) ?>">
				<img src="<?php show_cmb_value( 'microsite_image_two' ) ?>">
			</div>
		</div>
		<div class="grid-row">
			<div class="two-third">
				<?php print apply_filters( 'the_content', get_cmb_value( 'microsite_content_three' ) ); ?>
			</div>
			<div class="third bg-<?php show_cmb_value( 'microsite_color_three' ) ?>">
				<img src="<?php show_cmb_value( 'microsite_image_three' ) ?>">
			</div>
		</div>
		<div class="micro-subfooter" style="background-image: url(<?php show_cmb_value( 'microsite_subfooter_bg' ) ?>);">
			<?php show_cmb_value( 'microsite_subfooter_content' ) ?>
		</div>
		<div class="micro-footer bg-orange text-white">
			<a href="<?php show_cmb_value( 'microsite_footer_link' ); ?>" target="_blank"><img src="<?php show_cmb_value( 'microsite_footer_image' ) ?>" class="imageright" /></a>
			<div class="content">
				<?php show_cmb_value( 'microsite_footer_content' ) ?>
			</div>
		</div>
		<div class="colophon">
			<?php show_cmb_value( 'microsite_colophon_content' ) ?>
		</div>
	</section>
		
	<footer class="footer">
		
	</footer><!-- #colophon -->

</div>
<?php wp_footer(); ?>
</body>
</html>