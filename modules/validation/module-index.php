<?php

// Modultemplate festlegen
global $theme_template;
$module_template = 'tpl-validation.html'; // Namen der Template eingeben
if(file_exists(__PATH__ . '/themes/' . THEME_NAME . '/' . $module_template)) { $theme_template = $module_template; }


function initModule($action, $data) {
	
	// Globale Variablen
	global $smarty;
	
	
	// Modultitel
	$smarty->assign('title', 'VfN NRW | Knotenregistrierung | Email Validierung');
	
	
	$result = dbSQL('SELECT ID FROM '. TBL_REQUESTS .' WHERE ValidationHash = "'. $data .'" LIMIT 1');
	if(!$row = $result->fetch_object()) { 
		
		returnError('E0006', 'Validation'); 
	
		$smarty->assign('result', 'Validierung fehlgeschlagen.<br><br>Kein Code gefunden.');
	
	} else {
		
		$request = new db_requests($row->ID);
		$request->validate($data);
		$request->updateDatabase();
		
		$smarty->assign('result', 'Validierung durchgeführt.<br><br>Knoten zur Freischaltung eingetragen.');
		
		$request->sendApprovalRequest();
		
	}
	
		

}

?>