

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


// tab controls
jQuery(document).ready(function($){

	if ( $( '.browse-by-handle' ).length ) {

		// browse-by-handle handler
		$( '.browse-by-handle' ).click(function(e){
			e.preventDefault();
			$( '.browse-by-filters' ).slideToggle( 400 );
		});

		$( '.browse-by input[type=submit]' ).click(function(e){
			e.preventDefault();

			var category = [];

			// loop through checkboxes and populate our array
			$( '.browse-by input[type=checkbox]' ).each(function(){
				if ( $(this).is(':checked') ) {
					category.push( $(this).val() );
				}
				if ( category.length > 0 ) {
					location.href = $.query.set( "category", category.join('-') );
				} else {
					location.href = $.query.set( "category", 0 );
				}
			});

		});

	}

});

