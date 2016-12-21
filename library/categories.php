<?php


function get_category_color( $category_id ) {
	switch ( $category_id ) {
		case 20:
			$color='orange'; 
		break;
		case 29:
			$color='river'; 
		break;
		default: 
			$color='lime';
		break; 
	}
	return $color;
}


?>