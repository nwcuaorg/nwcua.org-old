

// tab controls
jQuery(document).ready(function($){

	$( '.browse-by-handle' ).click(function(e){
		e.preventDefault();
		$( '.browse-by-filters' ).slideToggle( 400 );
	});

});

