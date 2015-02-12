<?php

class db_requests {
	
	private $ID;
	private $time;
	private $hwid;
	private $forename;
	private $surname;
	private $email;
	private $latitude;
	private $longitude;
	private $autoLocation;
	private $validationHash;
	private $validated;
	private $approved;
	private $comment;
	
	
	// Variablen die keine Entsprechung in der Datenbank haben
	private $exists;
	
	
	public function __construct($ID = '') { 
		
		$this->exists = true;
		
		if($ID != '') {			
			
			$result = dbSQL('SELECT * FROM '. TBL_REQUESTS .' WHERE ID = [u] "'. $ID . '" [u]', 'ALL');				
			
			
			// Fehlermeldung, wenn keine Abfrage zustande kam
			if(!$row = $result->fetch_object()) { $this->exists = false; return false; }
			
			
			// Daten in Klasse laden
			$this->ID 						= $row->ID;
			$this->time						= $row->Time;
			$this->hwid						= $row->HWID;
			$this->forename					= $row->Forename;
			$this->surname					= $row->Surname;
			$this->email					= $row->Email;
			$this->latitude					= floatval($row->Latitude);
			$this->longitude				= floatval($row->Longitude);
			$this->autoLocation				= $row->AutoLocation;
			$this->validationHash			= $row->ValidationHash;
			$this->validated				= $row->Validated;
			$this->approved					= $row->Approved;
			$this->comment					= $row->Comment;
			
		}
		
	}


	// Rückgabe, wenn kein Mitglied gefunden wurde
	public function exists() { return $this->exists; }
	
	
	// Variablen setzen
	public function setHWID($value)				{ $this->hwid = $value; }
	public function setForename($value)			{ $this->forename = $value; }
	public function setSurname($value)			{ $this->surname = $value; }
	public function setEmail($value)			{ $this->email = $value; }
	public function setLatitude($value)			{ $this->latitude = $value; }
	public function setLongitude($value)		{ $this->longitude = $value; }
	
	public function setAutoLocationON() 		{ $this->autoLocation = '1'; }
	public function setAutoLocationOFF() 		{ $this->autoLocation = '0'; }
	
	public function setValidationHash()			{ $this->validationHash = md5(time() . $this->email); }
	public function validate($hash) 			{ if($hash == $this->validationHash) { $this->validated = date('Y-m-d H:i:s'); $this->validationHash = ''; } else { return false; } }
	
	public function approve()					{ $this->approved = date('Y-m-d H:i:s'); }
	
	public function setComment($value) 			{ $this->comment = $value; }
	
	
	// Variablen auslesen
	public function getID() 					{ return $this->ID; }
	public function getTheTime() 				{ return $this->time; }
	public function getHWID()					{ return $this->hwid; }
	public function getForename()				{ return $this->forename; }
	public function getSurname()				{ return $this->surname; }
	public function getEmail()					{ return $this->email; }
	public function getLatitude()				{ return $this->latitude; }
	public function getLongitude()				{ return $this->longitude; }
	public function getAutoLocation()			{ return $this->autoLocation; }
	public function getValidationHash()			{ return $this->validationHash; }
	public function getValidated()				{ return $this->validated; }
	public function getApproved()				{ return $this->approved; }
	public function getComment()				{ return $this->comment; }
	
	
	// Datensatz erstmals schreiben
	public function addToDatabase() {
	
		// Prüfen ob unbedingt erforderliche Daten vorhanden sind
		if(	$this->hwid		 		== '' || 
			$this->forename 		== '' ||
			$this->email	 		== ''  ) { returnError('E0003', 'Registration-Add'); return false; }

		
		// Prüfen ob Email korrekt formatiert ist 
		if(!isValidEmail($this->email)) { returnError('E0004', 'Registration-Add'); return false; }
	
		
		// Prüfen ob Koordinaten richtig gesetzt sind.
		if( $this->latitude != '' && $this->longitude != '') {
			
			if(!is_float($this->latitude) || !is_float($this->longitude)) { returnError('E0005', 'Registration-Add'); return false; }
			
		}
		
		
		// Opt In Hash
		$this->setValidationHash();
		
		
		// Daten schreiben
		dbSQL(	'INSERT INTO '. TBL_REQUESTS .' ('
					. 'Time, '
					. 'HWID, '
					. 'Forename, '
					. 'Surname, '
					. 'Email, '
					. 'Latitude, '
					. 'Longitude, '
					. 'AutoLocation, '
					. 'ValidationHash, '
					. 'Validated, '
					. 'Approved, '
					. 'comment'
				. ') VALUES [u] ('
					. '"' . date('Y-m-d H:i:s') 				. '", '
					. '"' . $this->hwid							. '", '
					. '"' . $this->forename						. '", '
					. '"' . $this->surname 						. '", '
					. '"' . $this->email 						. '", '
					. '"' . $this->latitude						. '", '
					. '"' . $this->longitude					. '", '
					. '"' . $this->autoLocation					. '", '
					. '"' . $this->validationHash				. '", '
					. '"' . '0000-00-00 00:00:00'				. '", '
					. '"' . '0000-00-00 00:00:00'				. '", '
					. '"' . $this->comment 						. '"'
				. ') [u]'
				, 'ALL'	
		);
		
		$this->ID = $_SESSION['db_insertID'];
		
		// Aktivität loggen
		logToFile('registration', 'Node "'. $this->hwid .'" was registred by Email "' . $this->email .'"');
	
	}
	
	
	// Daten in Datenbank aktualisieren
	public function updateDatabase() {
		
		// Prüfen ob unbedingt erforderliche Daten vorhanden sind
		if(	$this->hwid		 		== '' || 
			$this->forename 		== '' ||
			$this->email	 		== ''  ) { returnError('E0003', 'Registration-Modify'); return false; }

		
		// Prüfen ob Email korrekt formatiert ist 
		if(!isValidEmail($this->email)) { returnError('E0004', 'Registration-Modify'); return false; }
		
	
		// Prüfen ob Koordinaten richtig gesetzt sind.
		if( $this->latitude != '' && $this->longitude != '') {
			
			if(!is_float($this->latitude) || !is_float($this->longitude)) { returnError('E0005', 'Registration-Modify'); return false; }
			
		} else { $this->latitude = '0'; $this->longitude = '0'; }
		
		
		// Daten schreiben
		dbSQL(	'UPDATE '. TBL_REQUESTS .' SET [u]'
					. 'Time = ' 								. '"' . $this->time			 					. '", '
					. 'HWID = '									. '"' . $this->hwid			 					. '", '
					. 'Forename = '								. '"' . $this->forename 						. '", '
					. 'Surname = ' 								. '"' . $this->surname							. '", '
					. 'Email = '								. '"' . $this->email							. '", '
					. 'Latitude = '								. '"' . $this->latitude							. '", '
					. 'Longitude = '							. '"' . $this->longitude						. '", '
					. 'AutoLocation = '							. '"' . $this->autoLocation						. '", '
					. 'ValidationHash = '						. '"' . $this->validationHash					. '", '
					. 'Validated = '							. '"' . $this->validated						. '", '
					. 'Approved = '								. '"' . $this->approved							. '", '
					. 'comment = '								. '"' . $this->comment 							. '"'
				. ' [u] WHERE ID = "' . $this->ID . '"'
				, 'ALL'	
		);
		
	}
	
	
	public function sendValidationMail() {
		
		$mail = new PHPMailer;
	
		$mail->isSMTP();
		$mail->Host 		= '';
		$mail->SMTPAuth		= true;
		$mail->Username 	= '';
		$mail->Password 	= '';
		$mail->SMTPSecure 	= 'tls';
		$mail->Port 		= 587;
		$mail->CharSet 		= "UTF-8";
		
		$mail->From 		= '';
		$mail->FromName 	= '';
	
		$mail->addAddress($this->email, $this->forename .' '. $this->surname);
		
		$mail->isHTML(false);
		
		$mail->Subject 	= 'Knotenfreischaltung | Bitte bestätige deine Email-Adresse';
		
		$message =	 	'Hallo '. $this->forename .',' ."\n"
						.	"\n"
						.	'wir freuen uns, dass du dich am Freifunk-Projekt mit einem Knoten beteiligen willst.' ."\n"
						.	"\n"
						.	'Um sicher zu stellen, dass wir dich im Notfall erreichen können, möchten wir dich bitten, deine Emailadresse mit einem Klick auf den folgenden Link zu bestätigen:' ."\n"
						.	"\n"
						.	__URL__ .'/?m=validation&d='. $this->validationHash ."\n"
						.	"\n"
						.	'Vielen Dank.' ."\n"
						.	"\n"
						.	'Sobald deine Bestätigung eingegangen ist wird ein Administrator mit der Freischaltung deines Knotens beauftragt.' ."\n"
						.	'Das passiert im Augenblick noch manuell. Hab daher bitte ein wenig Geduld und lass deinen Knoten eingeschaltet.' ."\n"
						. 	"\n"
						.	'Wenn du noch Fragen hast oder etwas nicht funktioniert schreib uns bitte eine Email an info@freifunk-nrw.de' ."\n"
						.	"\n"
						.	'Viel Spaß mit deinem neuen Knoten :)' ."\n";
												
		$mail->Body   	= $message;
		
		
		if(!$mail->send()) {
		
			returnError('E0007', 'Registration-Add');
			logToFile('registration-mail-errors', 'Mail für ID '. $this->ID .' konnte nicht gesendet werden: '. $mail->ErrorInfo);
			return false;
		
		} else { return true; }
		
	}
	
	
	public function sendApprovalRequest() {
		
		$mail = new PHPMailer;
	
		$mail->isSMTP();
		$mail->Host 		= '';
		$mail->SMTPAuth		= true;
		$mail->Username 	= '';
		$mail->Password 	= '';
		$mail->SMTPSecure 	= 'tls';
		$mail->Port 		= 587;
		$mail->CharSet 		= "UTF-8";
		
		$mail->From 		= '';
		$mail->FromName 	= '';
	
		$mail->addAddress('', '');
		
		$mail->isHTML(false);
		
		$mail->Subject 	= 'Knotenfreischaltung | HWID '. $this->hwid .' freischalten';
		
		$message =	 		'Die Email Adresse für Anfrage '. $this->ID .' wurde bestätigt' ."\n"
						.	"\n"
						.	'Bitte den Knoten mit der HWID '. $this->hwid .' prüfen und freischalten';
																		
		$mail->Body   	= $message;
		
		
		if(!$mail->send()) {
		
			returnError('E0008', 'Registration-Add');
			logToFile('registration-mail-errors', 'Approval Mail für ID '. $this->ID .' konnte nicht gesendet werden: '. $mail->ErrorInfo);
			return false;
		
		} else { return true; }
		
	}

	
}
	
?>