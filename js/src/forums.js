

jQuery(document).ready(function($){

	var $forums = $('#bbpress-forums');


	if ( $forums.length ) {

		var pagination_divs = $forums.find('.bbp-pagination');
		if ( pagination_divs.length > 1 ) {
			pagination_divs.first().hide();
		}

		$forums.find('.bbp-breadcrumb-home').html( '<span class="dashicons dashicons-admin-home"></span>' );

	}

});

