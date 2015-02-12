<?php

// Modultemplate festlegen
global $theme_template;
$module_template = 'tpl-registration.html'; // Namen der Template eingeben
if(file_exists(__PATH__ . '/themes/' . THEME_NAME . '/' . $module_template)) { $theme_template = $module_template; }


function initModule($action, $data) {
	
	// Globale Variablen
	global $smarty;
	
	
	// Modultitel
	$smarty->assign('title', 'VfN NRW | Knotenregistrierung');
	
	
	// Formular Registrierung
	$formRegistration = new html\form('registration', '', '', 'Registrieren');
	$formRegistration->addField('text', 'hwid', 'MAC-Adresse des Gerätes', '', 'required', 
								'Die eindeutige Hardware-ID des Gerätes. Du findest Sie oft auf der Rückseite im Format "AB:CD:12:34:EF:GH". Sie wird dir nach dem Neustart auch angezeigt, wenn das Gerät nicht online gehen kann. Es sind die letzen 12 Zeichen des Netzwerknames (SSID).');
	$formRegistration->addField('text', 'forename', 'Vorname', '', 'required',
								'Wir benötigen deinen Vorname, damit wir wissen, wie wir dich Ansprechen sollen.');
	$formRegistration->addField('text', 'surname', 'Nachname', '', '',
								'Ein Nachname wäre nett, ist aber nicht notwenig.');
	$formRegistration->addField('email', 'email', 'Email-Adresse', '', 'required',
								'Sie ist für die Registrierung auf jeden Fall erforderlich. An diese Adresse senden wir dir gleich einen Aktivierungs-Link. Dein Knoten kann erst freigeschaltet werden, wenn du die Anmeldung bestätigt hast. Bitte beachte, dass wir die Registrungen manuell prüfen. Daher kann die Freischaltung nicht unmittelbar erfolgen. Außerdem behalten wir uns vor Knoten, die mit einer sog. "Wegwerf"-Adresse registriert wurden nicht freizuschalten. Wir müssen dich über die angegeben Adresse in jedem Fall erreichen können.');							
	$formRegistration->addField('text', 'latitude', 'Breitengrad');
	$formRegistration->addField('text', 'longitude', 'Längengrad', '', '',
								'Die Angabe von Breiten- und Längengrad brauchen wir für die Eintragung auf unsere Karte. Du kannst die Koordinaten anhand deiner Adresse mit dem kleinen Formular an der rechts neben dem eigentlichen Registrierungsformular ermitteln. Wir speichern nur die Koordinaten. Nicht deine Adresse. Lässt du die Felder leer wird dein Knoten nicht auf der Karte angezeigt.');
	$formRegistration->addCheckbox('autoLocation', 'Automatische Standortermittlung erlauben.', 'checked', 
								   'Durch diese Funktion versuchen wir durch die anderen WLAN-Netze in deiner Umgebung eine exakte Position für deinen Konten zu ermitteln.');
	$smarty->assign('formRegistration', $formRegistration->draw());
	
	
	// Formular Koordinaten ermitteln
	$formGetCoordinates = new html\form('getCoordinates', '', '', 'Koordinaten ermitteln und eintragen');
	$formGetCoordinates->addField('text', 'street', 'Straße und Hausnummer');
	$formGetCoordinates->addField('text', 'zip', 'Postleitzahl');
	$formGetCoordinates->addField('text', 'city', 'Ort');
	$smarty->assign('formGetCoordinates', $formGetCoordinates->draw());
	

}

?>