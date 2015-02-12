<?php
	
// Datenbank
define('MYSQL_HOST',		'');
define('MYSQL_USER',		'');
define('MYSQL_PASSWORD',	'');
define('MYSQL_DATABASE',	'');



// Smarty
define('THEME_NAME', 		'one');	// Verzeichnisname der verwendeten Theme

global $smarty;
$smarty = new Smarty();

$smarty->setTemplateDir(__PATH__.'/themes/'.THEME_NAME.'/');
$smarty->setCompileDir(__PATH__.'/cache/smarty-compiled/');
$smarty->setConfigDir(__PATH__.'/cache/smarty-config/');
$smarty->setCacheDir(__PATH__.'/cache/smary-cache/');

$smarty->assign('theme_dir', __URL__ .'/themes/'. THEME_NAME);
$smarty->assign('url', __URL__);

// Bei Fehlern die untere Zeile aktivieren um die Debug-Konsole zu starten
// $smarty->debugging = true;



// SLIM REST Framework
\Slim\Slim::registerAutoloader();

// Interne API
$api_internal = new \Slim\Slim();
$api_internal->config( 
	array(	'debug' 			=> true,
			'log.level' 		=> \Slim\Log::DEBUG,
			'cookies.lifetime' 	=> '60 minutes'
	)
);
$api_internal->setName('vfn-nrw:registrierung:api_internal');



// Diverses
define('OPTION_LOGFILE',	true);

?>