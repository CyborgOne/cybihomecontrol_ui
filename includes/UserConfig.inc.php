 <?PHP
 
    
    $tblMain = new Table(array("", ""));

    $t2 = new Title("Benutzer-Einstellungen");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $t2);
    $tblMain->addRow($rMainT1);

    $dbTblBenutzer = new DbTable($_SESSION['config']->DBCONNECT, 
                                'user', 
                                array("Vorname", "Nachname", "User", "Status", "aktiv", "Pw"), 
                                "Vorname, Nachname, Benutzername, Status, Aktiv?, Passwort",
                                "",
                                "Nachname, Vorname");
    
    $insertMaskActive = (isset($_REQUEST["dbTableNew" . $dbTblBenutzer->TABLENAME]) && strlen($_REQUEST["dbTableNew" . $dbTblBenutzer->TABLENAME])>0);
    $updateMaskActive = (isset($_REQUEST["showUpdateMask" . $dbTblBenutzer->TABLENAME]) && strlen($_REQUEST["showUpdateMask" . $dbTblBenutzer->TABLENAME])>0);
    
    $dbTblBenutzer->setHeaderEnabled(true);
    $dbTblBenutzer->setInvisibleCols(array("Pw"));
    $dbTblBenutzer->setNoUpdateCols(array("Pw"));
    $dbTblBenutzer->setColSizes(array("200", "40", "60", "90", "50", "50"));

    if( $updateMaskActive || $insertMaskActive ){
        $pwFieldName = "Pw".($updateMaskActive?($_REQUEST[("showUpdateMask").$dbTblBenutzer->TABLENAME]):"");
        $pwField = $insertMaskActive?$pwField = new TextField($pwFieldName):new PasswordField($pwFieldName);
        
        $dbTblBenutzer->setAdditionalUpdateFields(array("Passwort"=>$pwField));
    }
    
    $newBenutzerBtn = new Text("");
    

    // Neuer Eintrag
    if (isset($_REQUEST['InsertIntoDB'.$dbTblBenutzer->TABLENAME]) && $_REQUEST['InsertIntoDB'.$dbTblBenutzer->TABLENAME] == "Speichern") {
        if(isset($_REQUEST['Pw']) && strlen($_REQUEST['Pw'])>0){
            $_REQUEST['Pw'] = md5($_REQUEST['Pw']);
        }
        
        $dbTblBenutzer->doInsert();
        $dbTblBenutzer->refresh();

    } else if (isset($_REQUEST[$dbTblBenutzer->getNewEntryButtonName()])) {
        $dbTblBenutzer->setBorder(0);
        $insMsk = $dbTblBenutzer->getInsertMask();
        $hdnFld = $insMsk->getAttribute(1);
        
        if ($hdnFld instanceof Hiddenfield) {
            $insMsk->setAttribute(1, new Hiddenfield($dbTblBenutzer->getNewEntryButtonName(), "-"));
        }

        $rNew = $tblMain->createRow();
        $rNew->setAttribute(0, $insMsk);
        $rNew->setSpawnAll(true);
        $tblMain->addRow($rNew);
        $tblMain->addSpacer(0,10);
    } else {
        $newBenutzerBtn = $dbTblBenutzer->getNewEntryButton("Neuen Benutzer anlegen");    
    }
    
    if($dbTblBenutzer->getRowCount()>1){
        $dbTblBenutzer->setDeleteInUpdate(true);
    }
    
    if (isset($_REQUEST["DbTableUpdate" . $dbTblBenutzer->TABLENAME])) {
        if(isset($_REQUEST["Pw".$_REQUEST["SingleUpdateRowId"]]) && strlen($_REQUEST["Pw".$_REQUEST["SingleUpdateRowId"]])>0){
            $_REQUEST["Pw".$_REQUEST["SingleUpdateRowId"]] = md5($_REQUEST["Pw".$_REQUEST["SingleUpdateRowId"]]);
        }
        $dbTblBenutzer->doUpdate();
    }


    if ($dbTblBenutzer->isDeleteInUpdate()) {
        $deleteMask = $dbTblBenutzer->doDeleteFromUpdatemask() ? null : $dbTblBenutzer->doDeleteFromUpdatemask();
        if ($deleteMask != null) {
            $lS = $tblMain->createRow();
            $lS->setSpawnAll(true);
            $lS->setAttribute(0, $deleteMask);
            $tblMain->addRow($lS);
        }            
    }

    $tblArduinoBenutzeres = $dbTblBenutzer->getUpdateMask();

    $tblMain->addSpacer(0,20);

    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $tblArduinoBenutzeres);
    $tblMain->addRow($lS);
    
    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $newBenutzerBtn);
    $tblMain->addRow($lS);


    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);


    
    $f = new Form();
    $f->add($tblMain);
    $f->show();

?>