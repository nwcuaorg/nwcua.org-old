

// tab controls
jQuery(document).ready(function($){
	if ( $( '.advocacy-blog' ).length ) {
		var do_filtering = function(){
			var show_idaho = $( '.idaho-filter' ).is(':checked');
			var show_oregon = $( '.oregon-filter' ).is(':checked');
			var show_washington = $( '.washington-filter' ).is(':checked');

			var advocacy_blog = $( '.advocacy-blog' );
			advocacy_blog.find('.cuobsessed-post').each(function(){
				$(this).hide();
				if ( show_washington && $(this).hasClass( 'washington' ) ) {
					$(this).show();
				}
				if ( show_idaho && $(this).hasClass( 'idaho' ) ) {
					$(this).show();
				}
				if ( show_oregon && $(this).hasClass( 'oregon' ) ) {
					$(this).show();
				}
			});

			if ( !show_idaho && !show_oregon && !show_washington ) {
				advocacy_blog.find('.cuobsessed-post').show();
			}
		}

		$( '.idaho-filter' ).change( do_filtering );
		$( '.oregon-filter' ).change( do_filtering );
		$( '.washington-filter' ).change( do_filtering );
	}
});

