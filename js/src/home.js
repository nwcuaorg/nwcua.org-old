

// add prototype property to remove an element based on value
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};


//


// tab controls
jQuery(document).ready(function($){

	if ( $( '.browse-by-handle' ).length ) {

		// browse-by-handle handler
		$( '.browse-by-handle' ).click(function(e){
			e.preventDefault();
			$( '.browse-by-filters' ).slideToggle( 400 );
		});

		// get the current categories
		var category = $.query.get( "category" );

		if ( category.indexOf( '-' ) ) {
			category = category.split('-');
		}

		// on checkbox click
		$( '.browse-by input[type=checkbox]' ).change(function(){
			if ( $(this).is(':checked') ) {
				category.push( $(this).val() );
				location.href = $.query.set( "category", category.join('-') );
			} else {
				category.remove( $(this).val() );
				location.href = $.query.set( "category", category.join('-') );
			}
		});

	}

});

