<?php

/* ===

$result = dbSQL('SELECT ID, name FROM '. TBL_USERS .' WHERE ID = [u]"'. $id .'"[u]');
$row = $result->fetch_object();

dbSQL('INSERT INTO '. TBL_USERS .' (name) VALUES [u] ("'. $name .'") [u])', 'ALL';
				
=== */


function dbSQL($query, $levelOfProtection = 'NONE') {
	
	global $database;
	
	
	// zu filternde Ausdrücke
	$filter_sql_expressions = array('select', 'insert', 'SELECT', 'INSERT', 'Select', 'Insert');
	
	
	// == Abfrage bereinigen ==
	$dividedQuery = explode('[u]', $query);
	
	$i = 0;
	$alert = false;
	
	while ($i <= sizeof($dividedQuery)-1) {
		if (isOdd($i)) {
			
			switch ($levelOfProtection) {
				
				case 'BASIC':
				
					foreach ($filter_sql_expressions as $expression) {
						if (preg_match("/" . $expression . "/i", $dividedQuery[$i])) {
							$alert = true;
							logToFile('mysql-potential-attacts', getenv('REMOTE_ADDR') . ' - ' . $dividedQuery[$i]);
							$_SESSION['errors'][] = returnError('E0003', 'Database');
						}
					}
					
					break;
					
				
				case 'HTML': $dividedQuery[$i] = cleanInputFromCode($dividedQuery[$i]);	break;
					
				
				case 'ALL':
				
					foreach ($filter_sql_expressions as $expression) {
						if (preg_match("/" . $expression . "/i", $dividedQuery[$i])) {
							$alert = true;
							logToFile('mysql-potential-attacts', getenv('REMOTE_ADDR') . ' - ' . $dividedQuery[$i]);
							$_SESSION['errors'][] = returnError('E0003', 'Database');
						}
					}
					
					$dividedQuery[$i] = cleanInputFromCode($dividedQuery[$i]);
					
					break;
						
			}

		}
	
	$i++;
	
	}
	// === # Abfrage bereinigen ===
	
	
	// Abfrage ohne Escape-Strings erstellen
	$cleanQuery = implode(' ', $dividedQuery);
	
	
	// Abfrage ausführen wenn kein Fehler entdeckt wurde
	if(!$alert) {
		
		$result = $database->query($cleanQuery);
		$_SESSION['db_insertID'] = $database->insert_id;
		
		// bei Abfragefehler
		if(!$result) { logToFile('mysql-errors', 'Query Error' . $database->error); }
		
	}

	if(isset($result)) { return $result;	} else { return false; }
	
}

?>