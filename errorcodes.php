<?php


function returnError($errorcode, $location) {

	$errorcodes = array(	'E0001'	=> 'Angaben nicht vollständig.',
							'E0002' => 'API Header - Felder fehlen oder sind leer.',
							'E0003'	=> 'Mindestdaten für Operation nicht vorhanden.',
							'E0004' => 'Keine korrekte Email-Adresse.',
							'E0005' => 'Koordinaten wurden nicht im korrekten Format angegeben.',
							'E0006' => 'Validation Hash nicht gefunden.',
							'E0007' => 'Validation Mail konnte nicht gesendet werden.',
							'E0008' => 'Approval Mail konnte nicht gesendet werden.' );
							
	logtoFile('general-errors', '('. $location . ') ' . $errorcode .': '. $errorcodes[$errorcode]);
	
	$output_array = array(  'output'	=> '['. $location .'] ('. $errorcode .') '. $errorcodes[$errorcode],
							'code'		=> $errorcode,
							'message'	=> $errorcodes[$errorcode] );
							
	$_SESSION['errors'][] = $output_array;

	return $output_array;
	
}

?>