<?PHP

$t = new Title("Benutzer verwalten");
$t->setAlign("left");
$t->show();

$spc = new Spacer(10);
$spc->show();    

if (($_SESSION['config']->CURRENTUSER->STATUS=="admin") && (! $_SESSION['config']->CURRENTUSER->STATUS=="user")){
   $e = new Error("Kein Zugriff", " Sie haben keine Berechtigung fÃ¼r diesen Bereich!");
}

$userTable = new DbTable($_SESSION['config']->DBCONNECT, 
                      "user", 
					  array( "Vorname", "Nachname", "User", "Email", "Status"), 
					  "Vorname, Nachname, Benutzername, Email, Status",
					  "",
					  " Vorname ASC ",
					  " User != 'Developer' AND  User != 'admin' ");
						  
$userTable->setHeaderEnabled(true);
$userTable->setDeleteInUpdate(true);
$userTable->setToCheck("Vorname, Nachname, User, Email, Status");

// Neuer Eintrag
if (isset($_REQUEST['InsertIntoDBuser']) && $_REQUEST['InsertIntoDBuser'] ==
    "Speichern") {

    if($userTable->doInsert()){
        $userTable->refresh();
    }
    $spc->show();  
} else if (isset($_REQUEST[$userTable->getNewEntryButtonName()])) {

    $userTable->setBorder(0);
    $insMsk = $userTable->getInsertMask();
    $hdnFld = $insMsk->getAttribute(1);
    if ($hdnFld instanceof Hiddenfield) {
        $insMsk->setAttribute(1, new Hiddenfield($userTable->getNewEntryButtonName(), "-"));
    }

    $insMsk->show();
    $spc->show();  
}

$userTable->showUpdateMask();

$spc->show();  

$form = new Form();
$form->add($userTable->getNewEntryButton("Neuen Benutzer anlegen"));
$form->add(new Spacer());
$form->show();    

?>
