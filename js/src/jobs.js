

// let's create a new selector to handle case insensitive 'contains' selector
jQuery.extend( jQuery.expr[":"], {
	"icontains": function( elem, i, match, array ) {
		return ( elem.textContent || elem.innerText || "" ).toLowerCase().indexOf( ( match[3] || "" ).toLowerCase() ) >= 0;
	}
});


// onload responsive footer and menu stuff
jQuery(document).ready(function($){

	if ( $('.entry-job') ) {
	    var job_count = $('.job-count strong');
	    var job_count_val = job_count.text();

		$("#job-search").on("keyup", function() {

			// store the search term
		    var value = $(this).val();

		    // if the value isn't empty, hide the job count
			if ( value == '' ) {
				job_count.html( job_count_val );
			} else {
				job_count.html( '&nbsp;' );
			}

			// if the job entry doesn't contain the value, hide it
		    $('.entry-job:not(:icontains('+value+'))').each(function( index ){
	            $(this).hide();
		    });

		    // if it does, make sure it shows the job
			$(".entry-job:icontains("+value+")").each(function( index ){
            	$(this).show();
		    });
		});​
	}

});

