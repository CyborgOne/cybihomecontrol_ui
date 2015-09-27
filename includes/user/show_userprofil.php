<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 *
 * erwartet die BenutzerId des anzuzeigenden Benutzers in der Variable $_REQUEST['showUserId']
 */

 
 if(!isset($_REQUEST['showUserId']) || strlen($_REQUEST['showUserId'])<=0){
	$e = new Error("Fehlender Ãbergabewert", "Keine Eingabe in \$_REQUEST['showUserId'] gefunden");
 }

$currPath = dirname($_SERVER['SCRIPT_NAME']);

$currPath = substr($currPath, 1);

if (strlen($currPath)>0){
	$currPath = $currPath."/";
}


//Daten holen
 $userDBTable = new DbTable($_SESSION['config']->DBCONNECT, 
	                 'user', 
					  array("*") , 
					  "", 
					  "",
					  "",
					  "id=" .$_REQUEST['showUserId'] ." ");

$userDBRow = $userDBTable->getRow(1); 





//Daten in Table stecken
$userTable = new Table(array("", ""));
$userTable->setWidth(400);
$userTable->setAlign("left");

$ft = new FontType();
$ft->setFontsize(4);


//User-Name
$userRow = $userTable->createRow();
$userRow->setSpawnAll(true);
$userRow->setAlign("center");

$user = new Text($userDBRow->getNamedAttribute("User"));
$userRow->setAttribute(0, $user);
$user->setFonttype($ft);
$userTable->addRow($userRow);

$userTable->addSpacer(0);

//User-Avatar
$userRow1 = $userTable->createRow();
$userRow1->setSpawnAll(true);
$userRow1->setAlign("center");

$img = new Image($currPath."pics/user/" .$userDBRow->getNamedAttribute("pic") );
$img->setWidth(250);

$userRow1->setAttribute(0, $img);
$userTable->addRow($userRow1);

$userTable->addSpacer(0);


//Name
$userRow2 = $userTable->createRow();

$userFullName = new Text($userDBRow->getNamedAttribute("Vorname") ." " .$userDBRow->getNamedAttribute("Nachname"));

$userRow2->setAttribute(0, "Name:");
$userRow2->setAttribute(1, $userFullName);
$userTable->addRow($userRow2);


//Strasse
$userRow3 = $userTable->createRow();

$userStrasse = new Text($userDBRow->getNamedAttribute("Strasse"));

$userRow3->setAttribute(0, "Strasse:");
$userRow3->setAttribute(1, $userStrasse);
$userTable->addRow($userRow3);

//Plz / Ort
$userRow4 = $userTable->createRow();

$userOrt = new Text($userDBRow->getNamedAttribute("Plz") ." " .$userDBRow->getNamedAttribute("Ort"));

$userRow4->setAttribute(0, "Plz/Ort:");
$userRow4->setAttribute(1, $userOrt);
$userTable->addRow($userRow4);


//Email
if($userDBRow->getNamedAttribute("emailJN")=="J"){
	$userRow5 = $userTable->createRow();
	
	$userEmail = new Text($userDBRow->getNamedAttribute("Email"));
	
	$userRow5->setAttribute(0, "Email:");
	$userRow5->setAttribute(1, $userEmail);
	$userTable->addRow($userRow5);
}


//ICQ
if($userDBRow->getNamedAttribute("IcqJN")=="J"){
	$userRow6 = $userTable->createRow();
	
	$userEmail = new Text($userDBRow->getNamedAttribute("Icq"));
	
	$userRow6->setAttribute(0, "ICQ:");
	$userRow6->setAttribute(1, $userEmail);
	$userTable->addRow($userRow6);
}


//Tel/Handy/Fax
if($userDBRow->getNamedAttribute("telefonJN")=="J"){
  //Telefon
	$userRow7 = $userTable->createRow();
	
	$userEmail = new Text($userDBRow->getNamedAttribute("Telefon"));
	
	$userRow7->setAttribute(0, "Telefon:");
	$userRow7->setAttribute(1, $userEmail);
	$userTable->addRow($userRow7);

  //Handy
	$userRow8 = $userTable->createRow();
	
	$userEmail = new Text($userDBRow->getNamedAttribute("Handy"));
	
	$userRow8->setAttribute(0, "Handy:");
	$userRow8->setAttribute(1, $userEmail);
	$userTable->addRow($userRow8);

  //Fax
	$userRow9 = $userTable->createRow();
	
	$userEmail = new Text($userDBRow->getNamedAttribute("Fax"));
	
	$userRow9->setAttribute(0, "Fax:");
	$userRow9->setAttribute(1, $userEmail);
	$userTable->addRow($userRow9);
}


$userTable->show();


?>