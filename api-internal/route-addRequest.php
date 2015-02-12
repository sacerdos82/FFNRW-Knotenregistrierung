<?php

$api_internal->post('/addRequest', function() use ($api_internal) {

	// Globale Variablen einbinden
	global $apiResponseHeader;


	// Prüfen ob alle für die Operation benötigen Daten vorhanden und nicht leer sind.
	API_checkRequiredFields(array('hwid', 'forename', 'email'));


	// erforderliche Variablen laden
	$hwid				= $api_internal->request->post('hwid');
	$forename			= $api_internal->request->post('forename');
	$email				= $api_internal->request->post('email');
	
	
	// optionale Variablen laden
	if( $api_internal->request->post('surname') != null ) { $surname = $api_internal->request->post('surname'); } else { $surname = ''; }
	if( $api_internal->request->post('latitude') != null ) { $latitude = $api_internal->request->post('latitude'); } else { $latitude = ''; }
	if( $api_internal->request->post('longitude') != null ) { $longitude = $api_internal->request->post('longitude'); } else { $longitude = ''; }
	if( $api_internal->request->post('autoLocation') != null ) { $autoLoction = $api_internal->request->post('autoLocation'); } else { $autoLoction = '0'; }
	
	$latitude = floatval($latitude);
	$longitude = floatval($longitude);
	
	// Anfrage anlegen
	$request = new db_requests();
	
	$request->setHWID($hwid);
	$request->setForename($forename);
	$request->setSurname($surname);
	$request->setEmail($email);
	$request->setLatitude($latitude);
	$request->setLongitude($longitude);

	if($autoLoction == '1') { $request->setAutoLocationON(); } else { $request->setAutoLocationOFF(); }
		
	$request->addToDatabase();
	
	
	if(isset($_SESSION['errors'])) {
		
		$codes = '';
		$messages = '';
		foreach($_SESSION['errors'] as $error) {
			
			$codes .= $error['code'] .' ';
			$messages .= $error['message'] .' ';
			
		}
		
		$apiResponseHeader['error'] = true;
		$apiResponseHeader['code'] = $codes;
		$apiResponseHeader['message'] = $messages;
		
		API_Response(200, '');
		$api_internal->stop();
		
	} else {
		
		$request->sendValidationMail();
		
		$apiResponseHeader['status'] = true;
		$apiResponseHeader['code'] = 'S0001';
		$apiResponseHeader['message'] = 'Operation ausgeführt';
	
		API_Response(200, '');
		
	}
	
});

?>