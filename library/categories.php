<?php


function get_category_color( $category_id ) {
	switch ( $category_id ) {
		case 40:
		case 1286:
		case 49:
		case 30:
		case 1285:
			$color='orange'; 
		break;

		case 29:
			$color='river'; 
		break;

		case 1321:
			$color='grey-dark';
		break;

		case 7583:
			$color='grey-light';
		break;

		case 37:
		case 7581:
		case 7586:
		case 7585:
			$color='green';
		break;

		case 32:
		case 48:
			$color='aqua';
		break;

		case 41:
		case 42:
		case 50:
		case 20:
			$color='teal';
		break;

		case 38:
		case 1284:
		case 1275:
		case 1278:
		case 1276:
		case 1277:
		case 47:
		default: 
			$color='lime';
		break; 
	}
	return $color;
}


function get_default_thumbnail( $category_id ) {
	switch ( $category_id ) {
		case 40:
		case 1286:
		case 49:
		case 30:
		case 1285:
			$image=get_bloginfo('template_url') . '/img/category/compliance.jpg'; 
		break;

		case 29:
			$image=get_bloginfo('template_url') . '/img/category/advocacy.jpg'; 
		break;

		case 1321:
			$image=get_bloginfo('template_url') . '/img/category/credituniondifference.jpg'; 
		break;

		case 7583:
			$image=get_bloginfo('template_url') . '/img/category/idaho.jpg'; 
		break;

		case 37:
		case 7581:
		case 7586:
		case 7585:
			//$image='green';
			$image=get_bloginfo('template_url') . '/img/category/financialeducation.jpg'; 
		break;

		case 32:
		case 48:
			$image=get_bloginfo('template_url') . '/img/category/nwcuf.jpg'; 
		break;

		case 41:
		case 42:
		case 50:
		case 20:
			// $image='teal';
			$image=get_bloginfo('template_url') . '/img/category/strategiclink.jpg'; 
		break;

		case 35:
			$image=get_bloginfo('template_url') . '/img/category/maxx.jpg'; 
		break;

		case 38:
		case 1284:
		case 1275:
		case 1278:
		case 1276:
		case 1277:
		case 47:
			$image=get_bloginfo('template_url') . '/img/category/marketing.jpg'; 
		break; 

		default: 
			$image=get_bloginfo('template_url') . '/img/category/nwcua.jpg'; 
		break; 
	}
	return $image;
}


