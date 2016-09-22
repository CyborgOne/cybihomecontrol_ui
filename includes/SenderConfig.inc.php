 <?PHP
 
    $tblMain = new Table(array("", ""));

    $t2 = new Title("Sender-Einstellungen");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $t2);
    $tblMain->addRow($rMainT1);

    $dbTblSender = new DbTable($_SESSION['config']->DBCONNECT, 
                                'homecontrol_sender', 
                                array("name", "ip", "etage", "zimmer", "range_von", "range_bis", "default_jn"), 
                                "Name, IP, Etage, Zimmer, Bereich von:, Bis, Standard?",
                                "",
                                "default_jn, name");
    
    $dbTblSender->setHeaderEnabled(true);
    $dbTblSender->setDeleteInUpdate(true);
    $dbTblSender->setColSizes(array("200", "40", "60", "90", "50", "50", "50"));
    
    $deleteMask=null;
    if ($dbTblSender->isDeleteInUpdate()) {
        $deleteMask = !$dbTblSender->doDeleteFromUpdatemask() ? null : $dbTblSender->doDeleteFromUpdatemask();
    }
    if ($deleteMask != null) {
        $rDel = $tblMain->createRow();
        $rDel->setAttribute(0, $deleteMask);
        $rDel->setSpawnAll(true);
        $tblMain->addRow($rDel);
    }
    
    $newSwitchBtn = new Text("");
    
    // Neuer Eintrag
    if (isset($_REQUEST['InsertIntoDBhomecontrol_sender']) && $_REQUEST['InsertIntoDBhomecontrol_sender'] == "Speichern") {
        checkDefaultSender($dbTblSender->ROWS);

        $dbTblSender->doInsert();
        $dbTblSender->refresh();

    } else if (isset($_REQUEST[$dbTblSender->getNewEntryButtonName()])) {

            $dbTblSender->setBorder(0);
            $insMsk = $dbTblSender->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($dbTblSender->getNewEntryButtonName(), "-"));
            }

            $rNew = $tblMain->createRow();
            $rNew->setAttribute(0, $insMsk);
            $rNew->setSpawnAll(true);
            $tblMain->addRow($rNew);
            $tblMain->addSpacer(0,10);
    } else {
        $newSwitchBtn = $dbTblSender->getNewEntryButton("Neuen Sender anlegen");    
    }

    if (isset($_REQUEST["DbTableUpdate" . $dbTblSender->TABLENAME])) {
        checkDefaultSender($dbTblSender->ROWS);
        
        $dbTblSender->doUpdate();
    }
    
        
    if ($dbTblSender->isDeleteInUpdate()) {
        $deleteMask = $dbTblSender->doDeleteFromUpdatemask() ? null : $dbTblSender->doDeleteFromUpdatemask();
        if ($deleteMask != null) {
            $lS = $tblMain->createRow();
            $lS->setSpawnAll(true);
            $lS->setAttribute(0, $deleteMask);
            $tblMain->addRow($lS);
        }            
    }

    
    $tblArduinoSwitches = $dbTblSender->getUpdateMask();
    
    $tblMain->addSpacer(0,20);

    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $tblArduinoSwitches);
    $tblMain->addRow($lS);
    
    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $newSwitchBtn);
    $tblMain->addRow($lS);


    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);


    
    $f = new Form();
    $f->add($tblMain);
    $f->show();

?>