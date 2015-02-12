<?php

// ===========
// INTERNE API
// ===========
 
	
// Error Reportig konfigurieren
ini_set('error_reporting', -1);
ini_set('display_errors', 1);


// Zeitzone setzen
date_default_timezone_set('Europe/Berlin'); 


// CSV Line Ending Fix
ini_set('auto_detect_line_endings', true);



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



// CORS
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: X-Foo");
header("Access-Control-Max-Age: 3600");
	
	
// API Funktionen einbinden
require_once(__PATH__ . '/api-internal-includes.php');


// API Respose Header definieren
$apiResponseHeader = array(	'error'		=> false,
							'status'	=> false,
							'code'		=> '',
							'message'	=> '');


// API ausführen
$api_internal->run();


// Fehler ausgeben
if(isset($_SESSION['errors'])) {
	
	// foreach($_SESSION['errors'] as $error) { echo $error . '<br>'; }
	unset($_SESSION['errors']);

}

?>