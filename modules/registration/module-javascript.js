// Cookies auslesen
var url 	= $.cookie( 'vfnnrw-registrierung-url' );



$( document ).ready( function() { 
	
	$( '#windowLock' ).hide();

	$( "#form-getCoordinates" ).submit( function ( event ) {
		
		// Absenden des Formulars verhindern
		event.preventDefault();		
		
		// Abfrage auslesen
		var address = $( '#getCoordinates-street' ).val() + ' ' + $( '#getCoordinates-zip' ).val() + ' ' + $( '#getCoordinates-city' ).val()
			
		// Abfrage
		getCoordinates( address );
		
	});
	
	
	$( "#form-registration" ).submit( function ( event ) {
		
		// Absenden des Formulars verhindern
		event.preventDefault();		
			
		// Abfrage
		addRequest();
		
	});

});



function getCoordinates( address ) {
	
	var query = $.ajax({
		
		type: "GET",
		beforeSend: function ( request ) {
			headerNotification_ON( 'Abfrage läuft ...');			
		},
		url: 'http://maps.google.com/maps/api/geocode/json',
		dataType: "json",
		data: {
                    address: address,
                    sensor: "true"
                },
		success: function() {
			headerNotification_OFF();
		}
		
	}).done( function( data ) {
		
		var lat = data.results[0].geometry.location.lat;
		var lng = data.results[0].geometry.location.lng;
		$( '#registration-latitude' ).val( lat.toFixed(7) );
		$( '#registration-longitude' ).val( lng.toFixed(7) );
			
	}).fail( function () { headerNotification( 'Konnte keine Verbindung zu Google herstellen.' ); });
	
}


function addRequest() {
	
	// Felder auslesen
	var hwid = $( 'input[name="registration-hwid"]' ).val();
	var forename = $( 'input[name="registration-forename"]' ).val();
	var surname = $( 'input[name="registration-surname"]' ).val();
	var email = $( 'input[name="registration-email"]' ).val();
	var latitude = $( 'input[name="registration-latitude"]' ).val();
	var longitude = $( 'input[name="registration-longitude"]' ).val();
	if( $( 'input[name="registration-autoLocation"]' ).is( ':checked' ) ) { var autoLocation = '1'; } else { var autoLocation = '0'; }	
	
	if( hwid == '' ) { headerNotification( 'MAC-Adress muss angegeben werden.'); return false; }
	if( forename == '' ) { headerNotification( 'Vorname muss angegeben werden.'); return false; }
	if( email == '' ) { headerNotification( 'Email-Adresse muss angegeben werden.'); return false; }
	
	// Abfrage
	var addRequest = $.ajax({
			
		type: "POST",
		url: url + '/api-internal.php/addRequest',			
		data: {
			"hwid" : hwid,
			"forename" : forename,
			"surname" : surname,
			"email" : email,
			"latitude" : latitude,
			"longitude" : longitude,
			"autoLocation" : autoLocation
		},
		dataType: "json"
		
	}).done( function( data ) {
		
		console.log(data);
		
		if( data.header.error != true ) {
						
			var documentWidthForLock = $( document ).width();
			var documentHeightForLock = $( document ).height();
			
			$( '#windowLock' ).css( 'height', documentHeightForLock );
			$( '#windowLock' ).css( 'width', documentWidthForLock );
			$( '#windowLock' ).show();
			
		} else { headerNotification( data.header.code +' : '+ data.header.message ); }
		
	}).fail( function () { headerNotification( 'Konnte keine Verbindung zur API herstellen.' ); });
	
}