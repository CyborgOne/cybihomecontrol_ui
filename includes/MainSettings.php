<?PHP
    function getRowByName($rows, $name) {
        foreach ($rows as $row) {
            $idX = $row->getNamedAttribute("name");

            if ($name == $idX) {
                return $row;
            }
        }
        return false;
    }

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
    

    /*
     * Basis-Einstellungen
     */
    
    $configDb  = new DbTable($_SESSION['config']->DBCONNECT,
                            'pageconfig', 
                            array("*"));
    $configDb->setReadOnlyCols(array("name"));
    
    if(isset($_REQUEST['DbTableUpdatepageconfig']) && !isset($_REQUEST['loginForSwitchNeed'])){
        $_REQUEST['loginForSwitchNeed'] = 'N';
    }
        
    if(isset($_REQUEST['DbTableUpdatepageconfig']) && !isset($_REQUEST['anwesendMotion'])){
        $_REQUEST['anwesendMotion'] = 'N';
    }
        
    if(isset($_REQUEST['DbTableUpdatepageconfig']) && !isset($_REQUEST['abwesendAlarm'])){
        $_REQUEST['abwesendAlarm'] = 'N';
    }
        
    if(isset($_REQUEST['DbTableUpdatepageconfig']) && !isset($_REQUEST['abwesendSimulation'])){
        $_REQUEST['abwesendSimulation'] = 'N';
    }
        
    if(isset($_REQUEST['DbTableUpdatepageconfig']) && !isset($_REQUEST['abwesendMotion'])){
        $_REQUEST['abwesendMotion'] = 'N';
    }
    
    if( isset($_REQUEST['DbTableUpdate'.$configDb->TABLENAME]) && $_REQUEST['DbTableUpdate'.$configDb->TABLENAME] == "Speichern" ){
      $configDb->doUpdate();
    }
    
    $loginForSwitchNeedRow = getRowByName($configDb->ROWS, "loginForSwitchNeed");
    $loginForSwitchNeedName = 'value'.$loginForSwitchNeedRow->getNamedAttribute('id');
    $loginForSwitchNeed = $loginForSwitchNeedRow->getNamedAttribute('value')=="J"?"J":"N";
    
    $anwesendMotionRow     = getRowByName($configDb->ROWS, "anwesendMotion");
    $anwesendMotionName = 'value'.$anwesendMotionRow->getNamedAttribute('id');
    $anwesendMotion = $anwesendMotionRow->getNamedAttribute('value')=="J"?"J":"N";
    
    $abwesendAlarmRow      = getRowByName($configDb->ROWS, "abwesendAlarm");
    $abwesendAlarmName = 'value'.$abwesendAlarmRow->getNamedAttribute('id');
    $abwesendAlarm = $abwesendAlarmRow->getNamedAttribute('value')=="J"?"J":"N";
    
    $abwesendSimulationRow = getRowByName($configDb->ROWS, "abwesendSimulation");
    $abwesendSimulationName = 'value'.$abwesendSimulationRow->getNamedAttribute('id');
    $abwesendSimulation = $abwesendSimulationRow->getNamedAttribute('value')=="J"?"J":"N";
    
    $abwesendMotionRow =  getRowByName($configDb->ROWS, "abwesendMotion");
    $abwesendMotionName = 'value'.$abwesendMotionRow->getNamedAttribute('id');
    $abwesendMotion = $abwesendMotionRow->getNamedAttribute('value')=="J"?"J":"N";
    
    $sensorlogDauerRow     = getRowByName($configDb->ROWS, "sensorlogDauer");
    $sensorlogDauerName = 'value'.$sensorlogDauerRow->getNamedAttribute('id');
    $sensorlogDauer = $sensorlogDauerRow->getNamedAttribute('value');
    
    $motionDauerRow        = getRowByName($configDb->ROWS, "motionDauer");
    $motionDauerName = 'value'.$motionDauerRow->getNamedAttribute('id');
    $motionDauer = $motionDauerRow->getNamedAttribute('value');
    
    $sessionDauerRow       = getRowByName($configDb->ROWS, "sessionDauer");
    $sessionDauerName = 'value'.$sessionDauerRow->getNamedAttribute('id');
    $sessionDauer = $sessionDauerRow->getNamedAttribute('value');
    
    $arduinoUrlRow         = getRowByName($configDb->ROWS, "arduino_url");
    $arduinoUrlName = 'value'.$arduinoUrlRow->getNamedAttribute('id');
    $arduinoUrl = $arduinoUrlRow->getNamedAttribute('value');
    
    $notifyTargetMailRow   = getRowByName($configDb->ROWS, "NotifyTargetMail");
    $notifyTargetMailName = 'value'.$notifyTargetMailRow->getNamedAttribute('id');
    $notifyTargetMail = $notifyTargetMailRow->getNamedAttribute('value');
    
    $pagetitelRow          = getRowByName($configDb->ROWS, "pagetitel");
    $pagetitelName = 'value'.$pagetitelRow->getNamedAttribute('id');
    $pagetitel = $pagetitelRow->getNamedAttribute('value');
    
    
    
    
    $chbLoginSwitchNeed = new Checkbox($loginForSwitchNeedName, 
                                       "Login zum Schalten notwendig?", 
                                       "J", 
                                       $loginForSwitchNeed);
    
    $chbAbwesendAlarm = new Checkbox($abwesendAlarmName, 
                                       "Alarmanlage Aktiv?", 
                                       "J", 
                                       $abwesendAlarm);
                                       
    $chbAbwesendSimulation = new Checkbox($abwesendSimulationName, 
                                       "Anwesenheits-Simulation aktiv?", 
                                       "J", 
                                       $abwesendSimulation);
                                       
    $chbAbwesendMotion = new Checkbox($abwesendMotion, 
                                       "Kamera Bewegungserkennung?", 
                                       "J", 
                                       $abwesendMotion);

    $chbAnwesendMotion = new Checkbox($anwesendMotionName, 
                                       "Kamera Bewegungserkennung?", 
                                       "N", 
                                       $anwesendMotion);

    $txtSessionDauer = new Text("Sekunden bis zur neuen Anmeldung");
    $txfSessionDauer = new Textfield($sessionDauerName, $sessionDauer,4,5);
    $divSessionDauer = new Div();
    $divSessionDauer->add($txfSessionDauer);
    $divSessionDauer->add($txtSessionDauer);
                                
    $txtMotionDauer = new Text("Tage die Bewegungs-Bilder behalten");
    $txfMotionDauer = new Textfield($motionDauerName, $motionDauer,4,3);
    $divMotionDauer = new Div();
    $divMotionDauer->add($txfMotionDauer);
    $divMotionDauer->add($txtMotionDauer);
                                       
    $txtSensorlogDauer = new Text("Tage die Sensor-Log Daten behalten");
    $txfSensorlogDauer = new Textfield($sensorlogDauerName, $sensorlogDauer,4,4);
    $divSensorlogDauer = new Div();
    $divSensorlogDauer->add($txfSensorlogDauer);
    $divSensorlogDauer->add($txtSensorlogDauer);
                                       
                                       
    $txtPageTitel = new Text("Seiten-Titel");
    $txfPageTitel = new Textfield($pagetitelName, $pagetitel,30,50);
    
    $txtArduinoUrl = new Text("Arduino URL (IP/rawCmd)");
    $txfArduinoUrl = new Textfield($arduinoUrlName, $arduinoUrl,30,50);
                                       
    $txtNotifyTargetMail = new Text("Emailempfangs-Adresse");
    $txfNotifyTargetMail = new Textfield($notifyTargetMailName, $notifyTargetMail,30,50);
      
    $chbAnwesendMotion->setDisabled(true);                                
    $chbAbwesendAlarm->setDisabled(true);                                
    $chbAbwesendMotion->setDisabled(true);                                
    $chbAbwesendSimulation->setDisabled(true);                                
    
    $rightDiv = new Div();
    $rightDiv->add($chbLoginSwitchNeed);    
    $rightDiv->add(new Spacer(5));
    $rightDiv->add($divSessionDauer);    
    $rightDiv->add(new Spacer(15));
    $rightDiv->add($divMotionDauer);
    $rightDiv->add(new Spacer(5));
    $rightDiv->add($divSensorlogDauer);
    
        
    $leftTab = new Table(array("", ""));
    $rT = $leftTab->createRow();
    $rT->setAttribute(0,$txtPageTitel);
    $rT->setAttribute(1,$txfPageTitel);
    $leftTab->addRow($rT);
    $rA = $leftTab->createRow();
    $rA->setAttribute(0,$txtArduinoUrl);
    $rA->setAttribute(1,$txfArduinoUrl);
    $leftTab->addRow($rA);
    $rN = $leftTab->createRow();
    $rN->setAttribute(0,$txtNotifyTargetMail);
    $rN->setAttribute(1,$txfNotifyTargetMail);
    $leftTab->addRow($rN);
        
    
    $tblMain = new Table(array("", ""));
    $rMainT0 = $tblMain->createRow();
    $t1 = new Title("Generelle-Einstellungen");
    $t1->setAlign("left");
    $rMainT0->setAttribute(0,$t1);
    $tblMain->addRow($rMainT0);
    $tblMain->addSpacer(0,10);
    $rMain0 = $tblMain->createRow();
    $rMain0->setAttribute(0,$leftTab);
    $rMain0->setAttribute(1,$rightDiv);
    $tblMain->addRow($rMain0);

    $tblMain->addSpacer(0,15);
    $tblMain->addSpacer(1,25);
    

    $t2 = new Title("Anwesenheits-Einstellungen");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setAttribute(0,$t2);
    $tblMain->addRow($rMainT1);

    $tblMain->addSpacer(0,10);

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

    $tblMain->addSpacer(0,10);
    
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
    $rMainOk->setAttribute(0,new Button("DbTableUpdatepageconfig", "Speichern"));
    $tblMain->addRow($rMainOk);
    
    $f = new Form();
    $f->add(new Hiddenfield("UpdateAllMaskIsActive", "true"));
    $f->add($tblMain);
    $f->show();
    
       /*
    * NETZWERK
    */
        
    $spc  = new Spacer(20);
    $line = new Line(1,20);
    $spc->show();
    $line->show();

    include("includes/NetworkConfig.php");
    
    $spc->show();

}

?>