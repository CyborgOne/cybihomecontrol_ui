<?PHP

if ($_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->CURRENTUSER->STATUS != "user") {

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
    $ttlC = new Title("Zeitsteuerung");
    $ttlC->setAlign("left");
    $ttlC->show();

    $spc = new Spacer(20);
    $ln = new Line();

    $scDbTable = new CronDbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron', array("id", "name", "montag", "dienstag", "mittwoch", "donnerstag",
        "freitag", "samstag", "sonntag", "stunde", "minute"), "Id, Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min", "",
        "montag, dienstag, mittwoch, donnerstag, freitag, samstag, sonntag, stunde, minute", "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setInvisibleCols(array("id"));
    $scDbTable->setWidth("100%");

    $spc->show();

    $scDbTable->setBorder(0);

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST['dbTableNewhomecontrol_cron']) || (isset($_REQUEST['InsertIntoDBhomecontrol_cron']) && $_REQUEST['InsertIntoDBhomecontrol_cron'] ==
        "Speichern")) {
        $scDbTable->showInsertMask();
    }

    // --------------------------------------------------
    //  Bearbeiten-Maske
    // --------------------------------------------------
    if (isset($_REQUEST["DbTableUpdate" . $scDbTable->TABLENAME])) {
        $scDbTable->doUpdate();
    }

    $updateMask = $scDbTable->getUpdateAllMask();
    $updateMask->show();

    $spc->setHeight(10);
    $spc->show();

    $newBtn = $scDbTable->getNewEntryButton();
    $newBtn->show();

    $spc->show();
    $ln->show();

    $spc->setHeight(20);
    $spc->show();


    // --------------------------------------------------
    //  Zuordnungen
    // --------------------------------------------------

    if (isset($_REQUEST['SelectedCronToEdit'])) {
        $_SESSION['SelectedCronToEdit'] = $_REQUEST['SelectedCronToEdit'];
    }

    $table = new Table(array("", ""));
    $table->setWidth("100%");

    $ttlZuord = new Title("Zuordnungen bearbeiten");
    $ttlZuord->setAlign("left");

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, $ttlZuord);
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0, 10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_cron ORDER BY name", "SelectedCronToEdit", isset($_SESSION['SelectedCronToEdit']) ?
        $_SESSION['SelectedCronToEdit'] : "", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(120));
    $rAuswahl->setAttribute(0, new Text("Job auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0, 20);

    $form = new Form();
    $cron = null;
    
    // Zuordnung ausgewählt
    if (isset($_SESSION['SelectedCronToEdit']) && strlen($_SESSION['SelectedCronToEdit']) > 0) {
        $cron = new HomeControlCron($scDbTable->getRowById($_SESSION['SelectedCronToEdit']));
        
        $scItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron_items', array("config_id", "art_id", "zimmer_id", "etagen_id",
            "cron_id"), "Objekt, Objekt-Art, Zimmer, Etage, Job", "cron_id=" . $_SESSION['SelectedCronToEdit'],
            "config_id DESC, zimmer_id DESC, etagen_id DESC", "cron_id=" . $_SESSION['SelectedCronToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setNoInsertCols(array("id"));
        $scItemsDbTable->setNoUpdateCols(array("cron_id"));
        $scItemsDbTable->setInvisibleCols(array("cron_id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        $scItemsDbTable->setWidth("100%");

        // Neuer Eintrag
        if (isset($_REQUEST['InsertIntoDBhomecontrol_cron_items']) && $_REQUEST['InsertIntoDBhomecontrol_cron_items'] == "Speichern") {

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else
            if (isset($_REQUEST['dbTableNewhomecontrol_cron_items'])) {

                $scItemsDbTable->setBorder(0);
                $insMsk = $scItemsDbTable->getInsertMask();
                $hdnFld = $insMsk->getAttribute(1);
                if ($hdnFld instanceof Hiddenfield) {
                    $insMsk->setAttribute(1, new Hiddenfield("dbTableNewhomecontrol_cron_items", "-"));
                }
                $insMsk->show();

            }

        if (isset($_REQUEST["DbTableUpdate" . $scItemsDbTable->TABLENAME])) {
            $scItemsDbTable->doUpdate();
        }

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0,  $scItemsDbTable->getUpdateAllMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0, 10);

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getNewEntryButton());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $form->add($table);

        $table->addSpacer(0, 10);
        $table->addSpacer(1, 0);
        $table->addSpacer(0, 20);

    } else {
        $form->add($table);
    }

    $form->add(new Spacer());
    $form->show();
    
    if($cron!=null){
        // --------------------------------------------------
        //  Parameter
        // --------------------------------------------------
        $tblItems = new Table(array(""));
        $tblItems->setAlign("left");
                  
        $ttlZuord = new Title("Parameter festlegen");
        $ttlZuord->setAlign("left");
    
        $rTitle = $tblItems->createRow();
        $rTitle->setAttribute(0, $ttlZuord);
        $rTitle->setSpawnAll(true);
        $tblItems->addRow($rTitle);
    
        $tblItems->addSpacer(0, 10);
        
        $cronItemRows = $cron->getItemRowsForCron();
        foreach($cronItemRows as $cronItemRow){
            $rItem = $tblItems->createRow();
            $ttl = new Title($cronItemRow->getNamedAttribute("name"));
            $ttl->setAlign("left");
            $rItem->setAttribute(0, $ttl);
            $tblItems->addRow($rItem);
    
            $itm = $_SESSION['config']->getItemById($cronItemRow->getNamedAttribute("id")); //new HomeControlItem($cronItemRow);
            $itmParams = $itm->getAllParameter();
    
            $paramTbl = new Table(array("", ""));
            $paramTbl->setColSizes(array("50%", "50%"));
            foreach($itmParams as $itmParam){
                if((!$itmParam->isFix()||$itmParam->isDefaultLogic()) && (!$itmParam->isOptional() || $itm->isParameterOptionalActive($itmParam->getId()))){
                    $paramPrefix = "c".$cron->getId()."_".$itmParam->getId()."_".$itm->getId()."_";
                    if (isset($_REQUEST["saveParameters"]) && $_REQUEST["saveParameters"] == "Parameter speichern" &&
                        (isset($_REQUEST[$paramPrefix.$itmParam->getName()]) && strlen($_REQUEST[$paramPrefix.$itmParam->getName()])>0 || !$itmParam->isMandatory())){
                            $itm->setParameterValueForCron($itmParam->getRow(), $cron->getId(), $_REQUEST[$paramPrefix.$itmParam->getName()]);
                    }
    
                    $val = $itm->getParameterValueForCron($itmParam->getRow(), $cron->getId());
    
                    $valueEditObject="";
                    if($itmParam->isDefaultLogic()){
                        $tAn=$itm->getDefaultLogicAnText();
                        $tAus=$itm->getDefaultLogicAusText();
    
                        $valueEditObject = new Combobox($paramPrefix.$itmParam->getName(), array($tAn=>$tAn,$tAus=>$tAus) , $val);
                    } else {
                        $valueEditObject = $itm->getSender()->getTyp()->getEditParameterValueObject($itmParam->getRow(), $val, $paramPrefix, $val);
                    }
        
                    $rParam = $paramTbl->createRow();
                    $rParam->setAttribute(0, new Text($itmParam->getName(), 3));
                    $rParam->setAttribute(1, $valueEditObject);
                    $paramTbl->addRow($rParam);
                }
            }
    
            $rItem = $tblItems->createRow();
            $rItem->setAttribute(0, $paramTbl);
            $tblItems->addRow($rItem);
    
            $tblItems->addSpacer(0, 10);
        }
    
        $frmParams = new Form();
        $frmParams->add($tblItems);
        $frmParams->add(new Button("saveParameters", "Parameter speichern"));
        $frmParams->add(new Spacer());
        $frmParams->show();
    }
}



class CronDbTable extends DbTable {
    function postDelete($id) {
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=2 AND trigger_id = " . $id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);

        $sqlRemoveItems = "DELETE FROM homecontrol_cron_items WHERE cron_id = " . $id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveItems);

        $sqlRemovePause = "DELETE FROM homecontrol_cron_pause WHERE cron_id = " . $id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemovePause);

    }
}

?>

