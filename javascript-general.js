// Cookies auslesen
var url 	= $.cookie( 'vfnnrw-registrierung-url' );



// Allgemeine Rückgaben im Kopfbereich
function headerNotification( value ) { $( '#target-headerNotification' ).slideDown( 200 ).html( value ).delay( 2000 ).slideUp( 500 ); }
function headerNotification_ON( value ) { $( '#target-headerNotification' ).slideDown( 200 ).html( value ); }
function headerNotification_QuickON( value ) { $( '#target-headerNotification' ).show().html( value ); }
function headerNotification_OFF( value ) { $( '#target-headerNotification' ).slideUp( 200 ); }



$( document ).ready( function() { 

	$( '#target-headerNotification' ).hide();

});
