<?PHP

if (($_SESSION['config']->CURRENTUSER->STATUS=="admin") && (! $_SESSION['config']->CURRENTUSER->STATUS=="user")){
   $e = new Error("Kein Zugriff", " Sie haben keine Berechtigung fÃ¼r diesen Bereich!");
}

$userTable = new DbTable($_SESSION['config']->DBCONNECT, 
                      "user", 
					  array( "Vorname", "Nachname", "Geburtstag", "Plz", "Ort", "Status", "Email"), 
					  "Vorname, Nachname, Geburtstag, Plz, Ort, Email",
					  "",
					  " Vorname ASC ",
					  " User != 'Developer' AND  User != 'Superuser' ");
						  
$userTable->setHeaderEnabled(true);
$cnt = 0;
foreach($userTable->ROWS as $row){
	$id = $row->getNamedAttribute("rowid");
	$tt = getUserprofilAsTooltipText($id);
	$row->setToolTip($tt);
    
	$cnt++;
}

$userTable->show();
	
?>
