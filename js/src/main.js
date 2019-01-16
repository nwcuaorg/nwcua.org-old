

// onload responsive footer and menu stuff
jQuery(document).ready(function($){

	// select some things we'll use to make things responsive
	var menu = $( 'header nav' ),
		menu_toggle = menu.find( 'button.menu-toggle' ),
		menu_ul = menu.find( '.nav-menu' ),
		fluid_images = $( '.content-area img, .site-content img' );


	// remove height and width from images inside
	fluid_images.removeAttr( 'width' ).removeAttr( 'height' );


	// show/hide menus when they click the toggler
	menu_toggle.click(function(){

		if ( menu_ul.is( ':visible' ) ) {
			menu_ul.hide();
		} else {
			menu_ul.show();
		}

		// when user clicks a link, open submenu if it exists.
		menu_ul.find( 'a' ).click(function(){
			var parent_li = $( this ).parent( 'li' );
			var submenu = $( this ).next( 'ul' );
			if ( !submenu.is( ':visible' ) && parent_li.hasClass( 'menu-item-has-children' ) ) {
				event.preventDefault();
				parent_li.addClass( 'open' );
				submenu.show();
			}
		});

	});

	// fluid width videos that maintain aspect ratio
	$( '.content' ).fitVids({ignore: '.nofitvid'});

	$( 'button[data-url]' ).click(function(){
		window.location.href = $( this ).attr( 'data-url' );
	});

	
	// add lightbox to any link with that class.
	$( '.lightbox-iframe' ).magnificPopup({ 'type': 'iframe' });
	
	// add lightbox to any link with that class.
	$( '.photo-gallery a' ).magnificPopup({ 'type': 'image' });

	// handle heights on microsite pages.
	if ( $( '.microsite-container' ) ) {
		setTimeout( function(){
			$( '.microsite-container .grid-row' ).each(function( index ){
				var height = $( this ).find( '.two-third' ).css( 'height' );
				$( this ).find( '.third' ).css( 'height', height );
			});
		}, 3000);
	}

});


(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-23488192-1', 'auto');
ga('send', 'pageview');

