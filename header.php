<?php global $is_anthem; ?><!DOCTYPE html>
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
<link href="<?php bloginfo( "template_url" ) ?>/css/main.css?ver=9" rel="stylesheet" type="text/css">

</head>
<body <?php body_class(); ?>>

<header>

	<div class="wrap">

		<?php if ( $is_anthem ) { ?>
		<div class="logo">
			<a href="/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php bloginfo( "template_url" ) ?>/img/logo-anthem.png" alt="<?php bloginfo( 'name' ); ?>">
			</a>
		</div>
		<div class="slogan">
			News &amp; Info for Northwest Credit Unions
			<span>From Northwest Credit Union Association</span>
		</div>
		<?php } else { ?>
		<div class="logo">
			<a href="/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php bloginfo( "template_url" ) ?>/img/logo.png" alt="<?php bloginfo( 'name' ); ?>">
			</a>
		</div>
		<?php } ?>
	
		<!--
		<div class="tagline">
			Serving Idaho, Oregon, &amp; Washington Credit Unions
		</div>
		-->
		
		<div class="search">
			<?php get_search_form(); ?>
		</div>

		<div class='tools'>
			<!--<a href="/login" class="button account">Login</a>-->
			<?php account_button(); ?>
			<a href="/cart" class="button cart">Shopping Cart</a>
		</div>

	</div>

	<nav role="navigation">
		<button class="menu-toggle"><i class="fa fa-bars"></i></button>
		<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'menu_class' => 'nav-menu' ) ); ?>
	</nav>


</header>

<section class="content">
