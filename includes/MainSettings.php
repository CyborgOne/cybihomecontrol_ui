<?PHP
if ( $_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->CURRENTUSER->STATUS != "user" ) {
            
   /* ------------------------------------
      BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;
     
    $USERSTATUS = new UserStatus($USR, -1, -1);
    
    $tbl = new Table( array("") );
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute( 0, $USERSTATUS );
    $tbl->addRow( $r );
    
    $tbl->show();
    /* --------------------------------- */


} else {

    $loginForSwitchNeed = getPageConfigParam($_SESSION['config']->DBCONNECT, "loginForSwitchNeed")=="J"?"J":"N";
    $anwesendMotion     = getPageConfigParam($_SESSION['config']->DBCONNECT, "anwesendMotion")=="J"?"J":"N";
    $abwesendAlarm      = getPageConfigParam($_SESSION['config']->DBCONNECT, "abwesendAlarm")=="J"?"J":"N";
    $abwesendSimulation = getPageConfigParam($_SESSION['config']->DBCONNECT, "abwesendSimulation")=="J"?"J":"N";
    $abwesendSimulation = getPageConfigParam($_SESSION['config']->DBCONNECT, "abwesendSimulation")=="J"?"J":"N";
    
    $sensorlogDauer     = getPageConfigParam($_SESSION['config']->DBCONNECT, "sensorlogDauer");
    $motionDauer        = getPageConfigParam($_SESSION['config']->DBCONNECT, "motionDauer");
    $sessionDauer       = getPageConfigParam($_SESSION['config']->DBCONNECT, "sessionDauer");
    

    $configDb  = new DbTable($_SESSION['config']->DBCONNECT,
                            'pageconfig', 
                            array("name", "value") , 
                            "Bezeichnung , Wert",
                            "",
                            "name",
                            "name IN ('pagetitel', 'arduino_url', 'NotifyTargetMail')");
    $configDb->setReadOnlyCols(array("name"));
    if( isset($_REQUEST['DbTableUpdate'.$configDb->TABLENAME]) && $_REQUEST['DbTableUpdate'.$configDb->TABLENAME] == "Speichern" ){
      $configDb->doUpdate();
    }
    
    $updMsk = $configDb->getUpdateAllMask();
    
    // Standard-Update Tabellen-Objekt zum bearbeiten holen
    $updTbl = $updMsk->getAttribute(2);
    
    foreach ($updTbl->ROWS as $row) {
        // Textfeld gegen normalen Text austauschen
        $obj0 = $row->getAttribute(0);
        $txtLabel = new Text($obj0->getText());
        $row->setAttribute(0, $txtLabel);
    
        // Textarea gegen Textfeld des Wertes ändern
        $obj1 = $row->getAttribute(1);
        $txtWert = new Textfield($obj1->getName(), $obj1->getText(), 100);
        $row->setAttribute(1, $txtWert);
    }
     
    
    
    $chbLoginSwitchNeed = new Checkbox("loginForSwitchNeed", 
                                       "Login zum Schalten notwendig?", 
                                       "J", 
                                       $loginForSwitchNeed);
    
    $chbAbwesendAlarm = new Checkbox("abwesendAlarm", 
                                       "Alarmanlage Aktiv?", 
                                       "J", 
                                       $abwesendAlarm);
                                       
    $chbAbwesendSimulation = new Checkbox("abwesendSimulation", 
                                       "Anwesenheits-Simulation aktiv?", 
                                       "J", 
                                       $abwesendSimulation);
                                       
    $chbAbwesendMotion = new Checkbox("abwesendMotion", 
                                       "Kamera Bewegungserkennung?", 
                                       "J", 
                                       $abwesendMotion);

    $chbAnwesendMotion = new Checkbox("anwesendMotion", 
                                       "Kamera Bewegungserkennung?", 
                                       "N", 
                                       $anwesendMotion);

    $txtSessionDauer = new Text("Sekunden bis zur neuen Anmeldung");
    $txfSessionDauer = new Textfield("sessionDauer", $sessionDauer,4,5);
    $divSessionDauer = new Div();
    $divSessionDauer->add($txfSessionDauer);
    $divSessionDauer->add($txtSessionDauer);
                                
    $txtMotionDauer = new Text("Tage die Bewegungs-Bilder behalten");
    $txfMotionDauer = new Textfield("motionDauer", $motionDauer,4,3);
    $divMotionDauer = new Div();
    $divMotionDauer->add($txfMotionDauer);
    $divMotionDauer->add($txtMotionDauer);
                                       
    $txtSensorlogDauer = new Text("Tage die Sensor-Log Daten behalten");
    $txfSensorlogDauer = new Textfield("sensorlogDauer", $sensorlogDauer,4,4);
    $divSensorlogDauer = new Div();
    $divSensorlogDauer->add($txfSensorlogDauer);
    $divSensorlogDauer->add($txtSensorlogDauer);
                                       

    $chbAnwesendMotion->setDisabled(true);                                
    $chbAbwesendAlarm->setDisabled(true);                                
    $chbAbwesendMotion->setDisabled(true);                                
    $chbAbwesendSimulation->setDisabled(true);                                
    
    $leftDiv = new Div();
    $leftDiv->add($chbLoginSwitchNeed);    
    $leftDiv->add(new Spacer(5));
    $leftDiv->add($divSessionDauer);    
    $leftDiv->add(new Spacer(15));
    $leftDiv->add($divMotionDauer);
    $leftDiv->add(new Spacer(5));
    $leftDiv->add($divSensorlogDauer);
    
    
    $tblMain = new Table(array("", ""));

    $rMainT0 = $tblMain->createRow();
    $t1 = new Title("Generelle-Einstellungen");
    $t1->setAlign("left");
    $rMainT0->setAttribute(0,$t1);
    $tblMain->addRow($rMainT0);

    $rMain0 = $tblMain->createRow();
    $rMain0->setAttribute(0,$leftDiv);
    $rMain0->setAttribute(1,$updMsk);
    $tblMain->addRow($rMain0);

    $tblMain->addSpacer(0,15);
    $tblMain->addSpacer(1,25);

    $t2 = new Title("Anwesenheits-Einstellungen");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setAttribute(0,$t2);
    $tblMain->addRow($rMainT1);

    $rMain0 = $tblMain->createRow();
    $rMain0->setAttribute(0,$chbAnwesendMotion);
    $rMain0->setAttribute(1,"");
    $tblMain->addRow($rMain0);

    $tblMain->addSpacer(0,15);
    $tblMain->addSpacer(1,25);

    $t3 = new Title("Abwesenheits-Einstellungen");
    $t3->setAlign("left");
    $rMainT2 = $tblMain->createRow();
    $rMainT2->setAttribute(0,$t3);
    $tblMain->addRow($rMainT2);

    $rMain1 = $tblMain->createRow();
    $rMain1->setAttribute(0,$chbAbwesendAlarm);
    $rMain1->setAttribute(1,$chbAbwesendSimulation);
    $tblMain->addRow($rMain1);

    $rMain2 = $tblMain->createRow();
    $rMain2->setAttribute(0,$chbAbwesendMotion);
    $rMain2->setAttribute(1,"");
    $tblMain->addRow($rMain2);

    $tblMain->addSpacer(0,15);
    $tblMain->addSpacer(1,25);
    
    $rMainOk = $tblMain->createRow();
    $rMainOk->setSpawnAll(true);
    $rMainOk->setAttribute(0,new Button("saveSettings", "Speichern"));
    $tblMain->addRow($rMainOk);

    $tblMain->addSpacer(0,15);
    
    $tblMain->show();

    

}

?>