<?php
	
// Error Reportig konfigurieren
ini_set('error_reporting', -1);
ini_set('display_errors', 1);


// CSV Line Ending Fix
ini_set('auto_detect_line_endings', true);


// Zeitzone setzen -.-
date_default_timezone_set('Europe/Berlin'); 


// Session starten
session_start();


// Dateien einbinden & Konfiguration laden (nur oberste Installationsebene)
require_once('constants.php');
require_once('includes.php');
require_once('configuration.php');
require_once('errorcodes.php');


// Verbindung herstellen (erfolgt an dieser Stelle um nicht unnötig viele Verbindungen zu öffnen)
$database = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
if($database->connect_errno) { logToFile('mysql-errors', 'Connection Error ' . $database_mysql->connect_errno); } // Verbindungsfehler Protokollieren
$database->query("SET NAMES 'utf8'");


// URL Cookies für Javascript setzen
if(!isset($_COOKIE['vfnnrw-registrierung-url']) || $_COOKIE['vfnnrw-registrierung-url'] != __URL__) { setcookie('vfnnrw-registrierung-url', __URL__); }


// GET Variablen übernehmen und von potentiellen Code reinigen
if(isset($_GET['m'])) { $get_module = cleanInputFromCode($_GET['m']); } else { $get_module = 'registration'; }
if(isset($_GET['a'])) { $get_action = cleanInputFromCode($_GET['a']); } else { $get_action = ''; }
if(isset($_GET['d'])) { $get_data 	= cleanInputFromCode($_GET['d']); } else { $get_data = ''; }


// Modul initialisieren
if(file_exists(__PATH__ . '/modules/' . $get_module . '/module-index.php')) {
	
	define('MODULE_URL', 	__URL__ . '/modules/' . $get_module);
	require_once(__PATH__ . '/modules/' . $get_module . '/module-index.php');
		
	
	// Prüfen ob das Modul Javascript enthält
	if(file_exists(__PATH__ . '/modules/' . $get_module . '/module-javascript.js')) { 
		$smarty->assign('module_javascript', '<script src="'. __URL__ .'/modules/' . $get_module . '/module-javascript.js"></script>');
	} else {
		$smarty->assign('module_javascript', '');
	}
	
	initModule($get_action, $get_data);
	
} else {
	
	logToFile('callsToNonExistingModules', $get_module);
	$smarty->assign('title', '404: Nicht gefunden');
	$theme_template = 'tpl-404.html';
	
}


// Ausgabe über Template
$smarty->display($theme_template);


// Fehler ausgeben
if(isset($_SESSION['errors'])) {
	
	// foreach($_SESSION['errors'] as $error) { echo $error['message'] . '<br>'; }
	unset($_SESSION['errors']);

}

?>