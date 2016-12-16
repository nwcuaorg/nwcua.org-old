function ismaxlength(obj, mlength) {
	if (obj.value.length > mlength) {
		obj.value = obj.value.substring(0, mlength);
	}
}


function moveCaret(event, objThisField, objNextField, objPrevField, nSize) {
	var keynum;
	if(window.event) // IE	
		keynum = event.keyCode;	
	else if(event.which) // Netscape/Firefox/Opera	
		keynum = event.which;
	if (keynum == 37 || keynum == 39 || keynum == 38 || keynum == 40 || keynum == 8) { 
		//left, right, up, down arrows, backspace
		var nCaretPosition = getCaretPosition(objThisField);		
		if (keynum == 39 && nCaretPosition == nSize)
			moveToNextField(objNextField);		   
		if ((keynum == 37 || keynum == 8) && nCaretPosition == 0)			
			moveToPrevField(objPrevField);		   
		return;
	}
	if (keynum == 9) //Tab
		return;
	if (objThisField.value.length >= nSize && objNextField != null)
		moveToNextField(objNextField);
}


function moveToNextField(objNextField) {
	if (objNextField == null)
		return;
	objNextField.focus();
	if (document.selection) { //IE
		oSel = document.selection.createRange ();
		oSel.moveStart ('character', 0);
		oSel.moveEnd ('character', objNextField.value.length);
		oSel.select();							
	} else {
	   objNextField.selectionStart = 0;
       objNextField.selectionEnd = objNextField.value.length;
	}
}


function moveToPrevField(objPrevField) {
	if (objPrevField == null)
		return;
	objPrevField.focus();
	if (document.selection) { //IE		
		oSel = document.selection.createRange ();
		oSel.moveStart ('character', 0);
		oSel.moveEnd ('character', objPrevField.value.length);
		oSel.select ();					
	}
	else
	{
	   objPrevField.selectionStart = 0;
       objPrevField.selectionEnd = objNextField.value.length;
	}
}


function getCaretPosition(objField) {
	var nCaretPosition = 0;
	if (document.selection) { //IE
	   var oSel = document.selection.createRange ();
	   oSel.moveStart ('character', -objField.value.length);
	   nCaretPosition = oSel.text.length;
	}	
	if (objField.selectionStart || objField.selectionStart == '0')
       nCaretPosition = objField.selectionStart;
	return nCaretPosition;
}


function ShowDescriptions(SubDomain,val, brid) {
	myWindow = window.open(SubDomain + '/description.asp?brid=' + brid + '&id=' + val, 'Description', 'location=no,height=180,width=440,resizeable=no,scrollbars=yes,dependent=yes');
	myWindow.focus()
}


function hide_overlay() {
	$(".overlay-container:visible").fadeOut( 400 );
}



$(document).ready(function(){
	fullURL = document.URL
	sAlertStr = ''
	nLoc = fullURL.indexOf('&')
	if (nLoc == -1)
		nLoc = fullURL.length
	if (fullURL.indexOf('zreq=') > 0){
		sRequired = fullURL.substring(fullURL.indexOf('zreq=')+5, nLoc)
		if (sRequired.length > 0){
			sRequired = ',' + sRequired.replace('%20',' ')
			sRequired = sRequired.replace(/,/g,'\n  - ')
			sAlertStr = 'The following item(s) are required: '+sRequired + '\n'
		}
	}
	if (fullURL.indexOf('zmsg=') > 0) {
		sMessage = fullURL.substring(fullURL.indexOf('zmsg=')+5, fullURL.length)
		if (sMessage.length > 0) {
			sMessage = sMessage.replace(/%20/g, ' ')
			sMessage = sMessage.replace(/%0A/g, '\n')
			sAlertStr = sAlertStr + sMessage
		}
	}

	if (sAlertStr.length > 0) {
		$("#profileform .error").html( sAlertStr );
		//alert(sAlertStr)
	}

	$(".subscribe-form button, .main-menu .subscribe-form").click(function(event){
		event.preventDefault();
		if ( $("#subscriber-email-address").length==0 ) {
			$(".overlay-container .overlay-box .overlay-inner").load( "/assets_site/ajax/subscribe-form.htm", function(){			
				$("#subscriber-email-address").val( $(".subscribe-form input[type=text]").val() );
				if ( !is_small_screen() ) {
					var overlay_width=$(window).width()*.8,
						overlay_height=$(window).height()*.8;
					$(".overlay-box")
						.width( overlay_width )
						.height( overlay_height )
						.css( "margin-top", "-"+( Math.round( overlay_height/2 ) )+"px" )
						.css( "margin-left", "-"+( Math.round( overlay_width/2 ) )+"px" );
				} else {
					var overlay_height=$(window).height();
					$(".overlay-box").height( overlay_height );
				}
				$(".overlay-container").fadeIn( 400 );

				$(".overlay-container").click(function(){
					if ( !$(".overlay-container .overlay-box").is(":hover") ) {
						hide_overlay();
					}
				})

				$(document).keyup(function(e) {
					if ( e.keyCode == 27 ) hide_overlay();
				});;

				$(".overlay-container .close").click(function(){
					hide_overlay();
				});
			});
		} else {
			$(".overlay-container").fadeIn( 400 );
		}
		return false;
	});
});