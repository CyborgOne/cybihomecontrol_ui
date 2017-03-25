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

/**
 * stellt sicher, dass immer nur ein Default existiert
 */ 
function checkDefaultSender($rows){
    // doUpdate auf Default prüfen
    foreach ($rows as $row) {
        if(isset($_REQUEST["default_jn".$row->getNamedAttribute("rowid")]) && $_REQUEST["default_jn".$row->getNamedAttribute("rowid")]=="J"){
            $doDelOldDefault = true;
        }
    }
    
    // doInsert auf Default prüfen
    if(isset($_REQUEST["default_jn"]) && $_REQUEST["default_jn"]=="J"){
        $doDelOldDefault = true;
    }
    
    if($doDelOldDefault){
        $resetDefaultSql = "UPDATE homecontrol_sender SET default_jn = 'N' WHERE default_jn = 'J'";
        $_SESSION['config']->DBCONNECT->executeQuery($resetDefaultSql);
    }
}




if ($_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->
    CURRENTUSER->STATUS != "user") {

    /* ------------------------------------
    BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;

    $USERSTATUS = new UserStatus($USR, -1, -1);

    $tbl = new Table(array(""));
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute(0, $USERSTATUS);
    $tbl->addRow($r);

    $tbl->show();
    /* --------------------------------- */


} else {


    /*
    * Basis-Einstellungen
    */
    $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'pageconfig', array("*"));
    $configDb->setReadOnlyCols(array("name"));

    $loginForSwitchNeedRow = getRowByName($configDb->ROWS, "loginForSwitchNeed");
    $loginForTimelinePauseNeedRow = getRowByName($configDb->ROWS, "loginForTimelinePauseNeed");
    $anwesendMotionRow = getRowByName($configDb->ROWS, "anwesendMotion");
    $abwesendAlarmRow = getRowByName($configDb->ROWS, "abwesendAlarm");
    $abwesendSimulationRow = getRowByName($configDb->ROWS, "abwesendSimulation");
    $abwesendMotionRow = getRowByName($configDb->ROWS, "abwesendMotion");
    $sensorlogDauerRow = getRowByName($configDb->ROWS, "sensorlogDauer");
    $motionDauerRow = getRowByName($configDb->ROWS, "motionDauer");
    $sessionDauerRow = getRowByName($configDb->ROWS, "sessionDauer");
    $pagetitelRow = getRowByName($configDb->ROWS, "pagetitel");
    $notifyTargetMailRow = getRowByName($configDb->ROWS, "NotifyTargetMail");
    $timelineDurationRow = getRowByName($configDb->ROWS, "timelineDuration");
    $btSwitchActiveRow = getRowByName($configDb->ROWS, "btSwitchActive");
    $switchButtonsOnIconActiveRow = getRowByName($configDb->ROWS, "switchButtonsOnIconActive");
    $haBridgeActiveRow = getRowByName($configDb->ROWS, "haBridgeActive");
    $haBridgePathRow = getRowByName($configDb->ROWS, "haBridgePath");
    $showNamesInUiRow = getRowByName($configDb->ROWS, "showNamesInUi");
    

    $loginForSwitchNeedName = 'value' . $loginForSwitchNeedRow->getNamedAttribute('id');
    $loginForTimelinePauseNeedName = 'value' . $loginForTimelinePauseNeedRow->getNamedAttribute('id');
    $anwesendMotionName = 'value' . $anwesendMotionRow->getNamedAttribute('id');
    $abwesendAlarmName = 'value' . $abwesendAlarmRow->getNamedAttribute('id');
    $abwesendSimulationName = 'value' . $abwesendSimulationRow->getNamedAttribute('id');
    $abwesendMotionName = 'value' . $abwesendMotionRow->getNamedAttribute('id');
    $sensorlogDauerName = 'value' . $sensorlogDauerRow->getNamedAttribute('id');
    $motionDauerName = 'value' . $motionDauerRow->getNamedAttribute('id');
    $sessionDauerName = 'value' . $sessionDauerRow->getNamedAttribute('id');
    $notifyTargetMailName = 'value' . $notifyTargetMailRow->getNamedAttribute('id');
    $pagetitelName = 'value' . $pagetitelRow->getNamedAttribute('id');
    $timelineDurationName = 'value' . $timelineDurationRow->getNamedAttribute('id');
    $btSwitchActiveName = 'value' . $btSwitchActiveRow->getNamedAttribute('id');
    $switchButtonsOnIconActiveName = 'value' . $switchButtonsOnIconActiveRow->getNamedAttribute('id');
    $haBridgeActiveName = 'value' . $haBridgeActiveRow->getNamedAttribute('id');
    $haBridgePathName = 'value' . $haBridgePathRow->getNamedAttribute('id');
    $showNamesInUiName = 'value' . $showNamesInUiRow->getNamedAttribute('id');
    

    // Checkboxen brauchen Sonderbehandlung da bei fehlender Auswahl kein Wert mitgegeben wird
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$loginForSwitchNeedName])) {
        $_REQUEST[$loginForSwitchNeedName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$loginForTimelinePauseNeedName])) {
        $_REQUEST[$loginForTimelinePauseNeedName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$anwesendMotionName])) {
        $_REQUEST[$anwesendMotionName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$abwesendAlarmName])) {
        $_REQUEST[$abwesendAlarmName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$abwesendSimulationName])) {
        $_REQUEST[$abwesendSimulationName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$abwesendMotionName])) {
        $_REQUEST[$abwesendMotionName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$btSwitchActiveName])) {
        $_REQUEST[$btSwitchActiveName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$switchButtonsOnIconActiveName])) {
        $_REQUEST[$switchButtonsOnIconActiveName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$haBridgeActiveName])) {
        $_REQUEST[$haBridgeActiveName] = 'N';
    }
    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && !isset($_REQUEST[$showNamesInUiName])) {
        $_REQUEST[$showNamesInUiName] = 'N';
    }


    if (isset($_REQUEST['DbTableUpdate' . $configDb->TABLENAME]) && $_REQUEST['DbTableUpdate' .$configDb->TABLENAME] == "Speichern") {
        $configDb->doUpdate();

        $configDb->refresh();

        $loginForSwitchNeedRow = getRowByName($configDb->ROWS, "loginForSwitchNeed");
        $loginForTimelinePauseNeedRow = getRowByName($configDb->ROWS,  "loginForTimelinePauseNeed");
        $anwesendMotionRow = getRowByName($configDb->ROWS, "anwesendMotion");
        $abwesendAlarmRow = getRowByName($configDb->ROWS, "abwesendAlarm");
        $abwesendSimulationRow = getRowByName($configDb->ROWS, "abwesendSimulation");
        $abwesendMotionRow = getRowByName($configDb->ROWS, "abwesendMotion");
        $sensorlogDauerRow = getRowByName($configDb->ROWS, "sensorlogDauer");
        $motionDauerRow = getRowByName($configDb->ROWS, "motionDauer");
        $sessionDauerRow = getRowByName($configDb->ROWS, "sessionDauer");
        $pagetitelRow = getRowByName($configDb->ROWS, "pagetitel");
        $notifyTargetMailRow = getRowByName($configDb->ROWS, "NotifyTargetMail");
        $timelineDurationRow = getRowByName($configDb->ROWS, "timelineDuration");
        $btSwitchActiveRow = getRowByName($configDb->ROWS, "btSwitchActive");
        $switchButtonsOnIconActiveRow = getRowByName($configDb->ROWS, "switchButtonsOnIconActive");
        $haBridgeActiveRow = getRowByName($configDb->ROWS, "haBridgeActive");
        $haBridgePathRow = getRowByName($configDb->ROWS, "haBridgePath");
        $showNamesInUiRow = getRowByName($configDb->ROWS, "showNamesInUi");
        
    }


    $loginForSwitchNeed = $loginForSwitchNeedRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $loginForTimelinePauseNeed = $loginForTimelinePauseNeedRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $anwesendMotion = $anwesendMotionRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $abwesendAlarm = $abwesendAlarmRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $abwesendSimulation = $abwesendSimulationRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $abwesendMotion = $abwesendMotionRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $btSwitchActive = $btSwitchActiveRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $switchButtonsOnIconActive = $switchButtonsOnIconActiveRow->getNamedAttribute('value') == "J" ? "J" : "N";        
    $haBridgeActive = $haBridgeActiveRow->getNamedAttribute('value') == "J" ? "J" : "N";
    $showNamesInUiActive = $showNamesInUiRow->getNamedAttribute('value') == "J" ? "J" : "N";
    
    $sensorlogDauer = $sensorlogDauerRow->getNamedAttribute('value');
    $motionDauer = $motionDauerRow->getNamedAttribute('value');
    $sessionDauer = $sessionDauerRow->getNamedAttribute('value');
    $notifyTargetMail = $notifyTargetMailRow->getNamedAttribute('value');
    $pagetitel = $pagetitelRow->getNamedAttribute('value');
    $timelineDuration = $timelineDurationRow->getNamedAttribute('value');
    $haBridgePath = $haBridgePathRow->getNamedAttribute('value');


    $sqlNoFrame = "SELECT * FROM homecontrol_noframe WHERE ip = '" . $_SERVER['REMOTE_ADDR'] .
        "'"; //$_SERVER['HTTP_X_FORWARDED_FOR']
    $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sqlNoFrame);
    $noFrameExists = mysql_num_rows($rslt) > 0;


    $chbLoginSwitchNeed = new Checkbox($loginForSwitchNeedName,
        "Login zum Schalten notwendig?", "J", $loginForSwitchNeed);

    $chbLoginForTimelinePauseNeed = new Checkbox($loginForTimelinePauseNeedName,
        "Login zum Pausieren notwendig?", "J", $loginForTimelinePauseNeed);

    $chbAbwesendAlarm = new Checkbox($abwesendAlarmName, "Alarmanlage Aktiv?", "J",
        $abwesendAlarm);

    $chbAbwesendSimulation = new Checkbox($abwesendSimulationName,
        "Anwesenheits-Simulation aktiv?", "J", $abwesendSimulation);

    $chbAbwesendMotion = new Checkbox($abwesendMotionName, "Kamera Bewegungserkennung?",
        "J", $abwesendMotion);

    $chbAnwesendMotion = new Checkbox($anwesendMotionName,
        "Kamera Bewegungserkennung?", "J", $anwesendMotion);

    $chbBtSwitchActive = new Checkbox($btSwitchActiveName, "Intertechno BT-Switch aktiv?","J", $btSwitchActive);

    $chbSwitchButtonsOnIconActive = new Checkbox($switchButtonsOnIconActiveName, "Schalt-Buttons in Steuerung direkt sichtbar?","J", $switchButtonsOnIconActive);

    $chbHaBridgeActive = new Checkbox($haBridgeActiveName, "HA-Bridge Aktiv? (Notwendig f&uuml;r Amazon-Echo)","J", $haBridgeActive);

    $chbShowNamesInUi = new Checkbox($showNamesInUiName, "Name in Steuerung?","J", $showNamesInUiActive);
    $chbShowNamesInUi->setToolTip("Gibt an, ob der Name des Ger&auml;tes in der Steuerung angezeigt werden soll.");

    $cobTimelineDuration = new Combobox($timelineDurationName, array(1 => "1", 2 =>
        "2", 3 => "3", 4 => "4", 5 => "5", 6 => "6"), $timelineDuration);


    $txtSessionDauer = new Text("Sekunden bis zur neuen Anmeldung");
    $txfSessionDauer = new Textfield($sessionDauerName, $sessionDauer, 4, 5);
    $divSessionDauer = new Div();
    $divSessionDauer->add($txfSessionDauer);
    $divSessionDauer->add($txtSessionDauer);

    $txtMotionDauer = new Text("Tage die Bewegungs-Bilder behalten");
    $txfMotionDauer = new Textfield($motionDauerName, $motionDauer, 4, 3);
    $divMotionDauer = new Div();
    $divMotionDauer->add($txfMotionDauer);
    $divMotionDauer->add($txtMotionDauer);

    $txtSensorlogDauer = new Text("Tage die Sensor-Log Daten behalten");
    $txfSensorlogDauer = new Textfield($sensorlogDauerName, $sensorlogDauer, 4, 4);
    $divSensorlogDauer = new Div();
    $divSensorlogDauer->add($txfSensorlogDauer);
    $divSensorlogDauer->add($txtSensorlogDauer);

    $txtTimelineDuration = new Text("Tage in der Timeline anzeigen (1-6)");
    $divTimelineDuration = new Div();
    $divTimelineDuration->add($cobTimelineDuration);
    $divTimelineDuration->add($txtTimelineDuration);


    $txtPageTitel = new Text("Seiten-Titel");
    $txfPageTitel = new Textfield($pagetitelName, $pagetitel, 30, 50);

    $txtNotifyTargetMail = new Text("Emailempfangs-Adresse");
    $txfNotifyTargetMail = new Textfield($notifyTargetMailName, $notifyTargetMail, 30, 50);

    $txtHaBridgePath = new Text("Pfad zur HA-Bridge Installation");
    $txfHaBridgePath = new Textfield($haBridgePathName, $haBridgePath, 30, 50);
    $txfHaBridgePath->setToolTip("Pfad zum Verzeichnis, in dem die HA-Bridge installiert wurde.<br/>Darin sollte die Jar-Datei liegen und ein Unterordner data existieren der eine .db und .conf Datei beinhaltet. ");
    
    
    $rightDiv = new Div();
    $rightDiv->add($chbLoginSwitchNeed);
    $rightDiv->add(new Spacer(5));
    $rightDiv->add($chbLoginForTimelinePauseNeed);
    $rightDiv->add(new Spacer(5));
    $rightDiv->add($divSessionDauer);

    $rightDiv->add(new Spacer(20));
    $rightDiv->add($divMotionDauer);
    $rightDiv->add(new Spacer(5));
    $rightDiv->add($divSensorlogDauer);
    $rightDiv->add(new Spacer(5));
    $rightDiv->add($divTimelineDuration);
    
    $leftTab = new Table(array("", ""));
    $lT = $leftTab->createRow();
    $lT->setAttribute(0, $txtPageTitel);
    $lT->setAttribute(1, $txfPageTitel);
    $leftTab->addRow($lT);
    $leftTab->addSpacer(0,3);
    $lN = $leftTab->createRow();
    $lN->setAttribute(0, $txtNotifyTargetMail);
    $lN->setAttribute(1, $txfNotifyTargetMail);
    $leftTab->addRow($lN);
    
    $leftTab->addSpacer(0,10);
    
    $lS = $leftTab->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $chbShowNamesInUi);
    $leftTab->addRow($lS);

    $lS = $leftTab->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $chbSwitchButtonsOnIconActive);
    $leftTab->addRow($lS);

    $lB = $leftTab->createRow();
    $lB->setSpawnAll(true);
    $lB->setAttribute(0, $chbBtSwitchActive);
    $leftTab->addRow($lB);
    
    $leftTab->addSpacer(0,10);

    $lS = $leftTab->createRow();
    $lS->setSpawnAll(true);
    $lS->setVAlign("middle");
    $lS->setAttribute(0, $chbHaBridgeActive);
    $leftTab->addRow($lS);
    
    $haT = $leftTab->createRow();
    $haT->setAttribute(0, $txtHaBridgePath);
    $haT->setAttribute(1, $txfHaBridgePath);
    $leftTab->addRow($haT);
    
    $leftTab->addSpacer(0,20);
    


    $tblMain = new Table(array("", ""));
    $tblMain->setVAlign("middle");
    $rMainT0 = $tblMain->createRow();
    $t1 = new Title("Generelle-Einstellungen");
    $t1->setAlign("left");
    $rMainT0->setAttribute(0, $t1);
    $tblMain->addRow($rMainT0);
    $tblMain->addSpacer(0, 10);
    $rMain0 = $tblMain->createRow();
    $rMain0->setAttribute(0, $leftTab);
    $rMain0->setAttribute(1, $rightDiv);
    $tblMain->addRow($rMain0);

     $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);

    $t2 = new Title("Anwesenheits-Einstellungen");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $t2);
    $tblMain->addRow($rMainT1);

    $tblMain->addSpacer(0, 10);

    $rMain0 = $tblMain->createRow();
    $rMain0->setAttribute(0, $chbAnwesendMotion);
    $rMain0->setAttribute(1, "");
    $tblMain->addRow($rMain0);


    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);


    $t3 = new Title("Abwesenheits-Einstellungen");
    $t3->setAlign("left");
    $rMainT2 = $tblMain->createRow();
    $rMainT2->setSpawnAll(true);
    $rMainT2->setAttribute(0, $t3);
    $tblMain->addRow($rMainT2);

    $tblMain->addSpacer(0, 10);

    $rMain1 = $tblMain->createRow();
    $rMain1->setAttribute(0, $chbAbwesendAlarm);
    $rMain1->setAttribute(1, $chbAbwesendSimulation);
    $tblMain->addRow($rMain1);

    $rMain2 = $tblMain->createRow();
    $rMain2->setAttribute(0, $chbAbwesendMotion);
    $rMain2->setAttribute(1, "");
    $tblMain->addRow($rMain2);

    $tblMain->addSpacer(0, 25);

    $rMainOk = $tblMain->createRow();
    $rMainOk->setSpawnAll(true);
    $rMainOk->setAttribute(0, new Button("DbTableUpdatepageconfig", "Speichern"));
    $tblMain->addRow($rMainOk);



    /*
    * NETZWERK
    */
    $network = new DivByInclude("includes/NetworkConfig.php", false);

    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);

    $rMainNet = $tblMain->createRow();
    $rMainNet->setSpawnAll(true);
    $rMainNet->setAttribute(0, $network);
    $tblMain->addRow($rMainNet);


    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);
    
    
    

    $f = new Form();
    $f->add(new Hiddenfield("UpdateAllMaskIsActive", "true"));
    $f->add($tblMain);
    $f->show();


    include ("includes/OtherSettings.inc.php");
   
    include ("includes/SenderConfig.inc.php");

    include ("includes/UserConfig.inc.php");

    $spc = new Spacer();
    $spc->show();
}

?>