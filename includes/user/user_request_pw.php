<?php

if(isset($_REQUEST['requestNewPW4Mail']) && isset($_REQUEST['em4il']) && strlen($_REQUEST['em4il'])>3 && strpos($_REQUEST['em4il'], "@")>0 ){
  
  $txt = new Text("Es wurde ein neues Passwort generiert.\n"
        ."Die Email mit den Zugangsdaten wurde versendet an: ".$_REQUEST['em4il']);

  $_SESSION['config']->CURRENTUSER->needNewPassword($_REQUEST['em4il']);

exit();
}





$txt = new Text("Sie haben ihr Passwort vergessen?\n\n"
      ."Geben Sie einfach im folgenden Eingabefeld ihre Emailadresse ein und bestätigen "
      ."Sie ihre Eingabe mit dem Button *Passwort anfordern* ");


$eMail = new Textfield("em4il","");
$eMail->setTooltip("Geben Sie hier ihre Emailadresse ein mit der Sie sich registriert haben.");

$btn = new Button("requestNewPW4Mail","Passwort anfordern");
$eMail->setTooltip("Bestätigen Sie hier ihre Eingabe.\nEs wird ein neues Passwort generiert und die neuen Zugangsdaten an ihre Email gesendet.");


$table = new Table(array(""));
$r1 = $table->createRow();
$r1->setAttribute(0,$txt);
$table->addRow($r1);
$r2 = $table->createRow();
$r2->setAttribute(0,$eMail);
$table->addRow($r2);
$r3 = $table->createRow();
$r3->setAttribute(0,$btn);
$table->addRow($r3);

$form = new Form($_SERVER['SCRIPT_NAME']);
$form->add($table);
$form->show();
?>