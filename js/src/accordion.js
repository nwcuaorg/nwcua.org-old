

// onload responsive footer and menu stuff
jQuery(document).ready(function($){

	$( '.accordion-box-title' ).click(function(){
		// $( this ).parent( '.accordion-box' ).children( '.accordion-box-content' ).slideToggle( 600 );
		$( this ).parent( '.accordion-box' ).toggleClass('open');
	});

	$( ".site-content .expandable.handle").click(function(){
		$(this).toggleClass( "expanded" );
		$(this).next(".expandable.block").slideToggle( 600 );
	});

	setTimeout( function() {
		var expand = $.query.get( "expand" );
		if ( expand ) {
			var accordion = $( '.accordion-box:nth-child('+expand+')' );
			if ( accordion.children( '.accordion-box-content' ).is(':hidden') ) {
				accordion.children( '.accordion-box-content' ).slideDown( 600 );
				accordion.addClass('open');
			}
			$('html, body').animate({
				scrollTop: accordion.offset().top - 20
			}, 600 );
		}

		var expand_old = $.query.get( "expand-old" );
		if ( expand_old ) {
			var old_accordions = $( '.site-content .expandable.handle' );
			var aid = 1;
			if ( old_accordions.length ) {
				old_accordions.each(function(){
					if ( aid == expand_old ) {
						var old_accordion = $(this);
						old_accordion.addClass( 'expanded' );
						old_accordion.next( '.expandable.block' ).slideDown( 600 ).addClass( 'expanded' );
						$('html, body').animate({
							scrollTop: old_accordion.offset().top - 20
						}, 600 );
					}
					aid = aid + 1;
				});				
			}
		}
	}, 1500 );

});

