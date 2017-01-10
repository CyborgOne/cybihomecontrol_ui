<?PHP
 
    $tblMain = new Table(array("", ""));

    $tS = new Title("Sonstige Einstellungen");
    $tS->setAlign("left");  
    $rMainTs = $tblMain->createRow();
    $rMainTs->setSpawnAll(true);
    $rMainTs->setAttribute(0, $tS);
    $tblMain->addRow($rMainTs);
    $tblMain->addSpacer(0, 10);

    $dvMotion = new Table(array("",""));
    $fMotion = new Form();
    // Dienst aktiv?
    //echo exec("sudo pgrep -x motion", $output, $return);
    //echo "/";
    //print_r($output);
    //echo"/".$return."/";
    //echo "Ok, Motion-Detection-Process ist gestartet\n";
    $fMotion->add(new Hiddenfield("Motion", isset($return) && $return==0 ? "off" : "on"));
    $fMotion->add(new Button("ok", "Bewegungserkennung der Kamera " . (isset($return) && $return==0 ? "deaktivieren" : "aktivieren")));

    $rMotion = $tblMain->createRow();
    $rMotion->setSpawnAll(true);
    $rMotion->setAttribute(0, $fMotion);

    $tblMain->addRow($rMotion);
    
    $tblMain->addSpacer(0, 10);

    $fNoFrame = new Form();
    $fNoFrame->add(new Hiddenfield("noFrame", $noFrameExists ? "off" : "on"));
    $fNoFrame->add(new Button("ok", "Banner auf dieser IP " . ($noFrameExists ?
        "einblenden" : "ausblenden")));

    $rNoFrame = $tblMain->createRow();
    $rNoFrame->setSpawnAll(true);
    $rNoFrame->setAttribute(0, $fNoFrame);
    $tblMain->addRow($rNoFrame);

    $tblMain->addSpacer(0, 10);

    $fRemoveCamPics = new Form();
    $fRemoveCamPics->add(new Hiddenfield("removeCamPics", "dropIt"));
    $fRemoveCamPics->add(new Button("ok", "Bewegungserkennungsbilder entfernen"));

    $rRemoveCamPics = $tblMain->createRow();
    $rRemoveCamPics->setSpawnAll(true);
    $rRemoveCamPics->setAttribute(0, $fRemoveCamPics);
    $tblMain->addRow($rRemoveCamPics);

    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);

    

    if (isset($_REQUEST["removeCamPics"]) && $_REQUEST["removeCamPics"] == "dropIt") {
        echo shell_exec("sudo rm " .dirname($_SERVER['DOCUMENT_ROOT'] .$_SERVER['SCRIPT_NAME']) ."/cam_pics/* -R");
    }

    if (isset($_REQUEST["Motion"]) && $_REQUEST["Motion"] == "on") {
        echo shell_exec("sudo exec " . dirname($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME']) ."/startMotionDetection.sh");
    }

    if (isset($_REQUEST["Motion"]) && $_REQUEST["Motion"] == "off") {
        echo shell_exec("sudo exec " . dirname($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME']) ."/stopMotionDetection.sh");
    }


    $f = new Form();
    $f->add($tblMain);
    $f->show();
    
?>