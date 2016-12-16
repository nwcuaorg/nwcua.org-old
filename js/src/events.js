

// tab controls
jQuery(document).ready(function($){

	if ( $( '.day-event-list' ).length ) {

		// clicks of the majors tab
		$( 'table.calendar td' ).click(function(){
			$( '.day-event-list' ).html( $(this).find( '.day-events' ).html() );
		});

	}

	$( 'select.event-category' ).change(function(){
		location.href = $.query.set( "event_category", $(this).val() );
	});

	$( 'a.month-nav' ).click(function(){
		location.href = $.query.set( "mo", $(this).data('month') ).set( "yr", $(this).data('year') );
	});

});

