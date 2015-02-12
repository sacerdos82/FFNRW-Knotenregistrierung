<?php

// Externe Tools
require_once(__PATH__ . '/tools-external/smarty/Smarty.class.php');
require_once(__PATH__ . '/tools-external/Slim/Slim.php');
require_once(__PATH__ . '/tools-external/phpmailer/class.phpmailer.php');
require_once(__PATH__ . '/tools-external/phpmailer/class.smtp.php');


// Allgemeine Funktionen
require_once(__PATH__ . '/tools-internal/logToFile.php');
require_once(__PATH__ . '/tools-internal/cleanInputFromCode.php');
require_once(__PATH__ . '/tools-internal/isValidEmail.php');
require_once(__PATH__ . '/tools-internal/randomString.php');
require_once(__PATH__ . '/tools-internal/isOdd-isEven.php');
require_once(__PATH__ . '/tools-internal/isValidDate.php');
require_once(__PATH__ . '/tools-internal/decodeJSONandValidate.php');


// Datenbank
require_once(__PATH__ . '/database/tablenames.php');
require_once(__PATH__ . '/database/dbSQL.php');


// Datenbank Abstraktion
require_once(__PATH__ . '/database-abstraction/requests.php');


// HTML Generatores
require_once(__PATH__ . '/html-generators/form.php');

?>