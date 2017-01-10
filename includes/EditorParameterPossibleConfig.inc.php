 <?PHP
    class PossibleDbTable extends DbTable{
        function addRow($r) {
            $paramId = $r->getNamedAttribute("editor_parameter_id");
            $r->setDeleteInUpdate(getDbValue("homecontrol_editor_parameter_possible", "count('X')", "editor_parameter_id=".$paramId)>1);
            $this->ROWS[count($this->ROWS) + 1] = $r;
        }
    }
 
    $tblMain = new Table(array("", ""));
    
    
    $t2 = new Title("Kompatible Werte der Parameter");
    $t2->setAlign("left");
    
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $t2);
    $tblMain->addRow($rMainT1);

    $dbTblEditoren = new PossibleDbTable($_SESSION['config']->DBCONNECT, 
                                'homecontrol_editor_parameter_possible', 
                                array("editor_parameter_id","param_art_id"), 
                                "Parameter, Art",
                                "",
                                "editor_parameter_id",
                                "editor_parameter_id IN (SELECT id FROM homecontrol_editor_parameter WHERE editor_id=" .$_SESSION['selectedEditor'] .")",
                                true);
    $dbTblEditoren->setColSizes(array(400,400));

    $dbTblEditoren->setHeaderEnabled(true);
    
    $newSwitchBtn = new Text("");
    
    // Neuer Eintrag
    if (isset($_REQUEST["InsertIntoDB" . $dbTblEditoren->TABLENAME]) && $_REQUEST["InsertIntoDB" . $dbTblEditoren->TABLENAME] == "Speichern") {
        $dbTblEditoren->doInsert();
        $dbTblEditoren->refresh();

    } else if (isset($_REQUEST[$dbTblEditoren->getNewEntryButtonName()])) {

            $dbTblEditoren->setBorder(0);
            $insMsk = $dbTblEditoren->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($dbTblEditoren->getNewEntryButtonName(), "-"));
            }

            $rNew = $tblMain->createRow();
            $rNew->setAttribute(0, $insMsk);
            $rNew->setSpawnAll(true);
            $tblMain->addRow($rNew);
            $tblMain->addSpacer(0,10);
    } else {
        $newSwitchBtn = $dbTblEditoren->getNewEntryButton("Neue Kompatibilit&auml;t hinzuf&uuml;gen");    
    }

    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $newSwitchBtn);
    $tblMain->addRow($lS);


    if (isset($_REQUEST["DbTableUpdate" . $dbTblEditoren->TABLENAME])) {
        $dbTblEditoren->doUpdate();
    }


    
    $tblEditorUpdatemask = $dbTblEditoren->getUpdateAllMask();
    
    $tblMain->addSpacer(0,20);

    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $tblEditorUpdatemask);
    $tblMain->addRow($lS);
    
    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);


    
    $f = new Form();
    $f->add($tblMain);
    $f->show();

?>