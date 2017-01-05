<?PHP


class EditorDbTable extends DbTable {    
    function postDelete($id){
        $sqlRemoveTypes = "DELETE FROM homecontrol_editor_parameter_possible WHERE editor_parameter_id IN ("
                         ."SELECT id FROM homecontrol_editor_parameter WHERE editor_id = " .$id 
                         .")";
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTypes);
        
        $sqlRemoveTypes = "DELETE FROM homecontrol_editor_parameter WHERE editor_id = " .$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTypes);
    }
}
        
    if(isset($_REQUEST['selectedEditor']) && strlen($_REQUEST['selectedEditor'])>0){
       $_SESSION['selectedEditor'] = $_REQUEST['selectedEditor']; 
    }

    $dbTblEditorenParameter = new EditorDbTable($_SESSION['config']->DBCONNECT, 
                                            'homecontrol_editoren', 
                                            array("id", "name", "classname", "descr"), 
                                            "Id, Name, Klassenname, Beschreibung",
                                            "",
                                            "name",
                                            "");
    $dbTblEditorenParameter->setColSizes(array(250,300,250));
    $dbTblEditorenParameter->setNoInsertCols(array("id"));
    $dbTblEditorenParameter->setNoUpdateCols(array("id"));
    $dbTblEditorenParameter->setInvisibleCols(array("id"));

    if(!(isset($_SESSION['selectedEditor']) && strlen($_SESSION['selectedEditor'])>0) ){
        $_SESSION['selectedEditor'] = $dbTblEditorenParameter->getRow(1)!=null?$dbTblEditorenParameter->getRow(1)->getNamedAttribute("id"):"";  
    }
    
    $dbTblEditorenParameter->setWhere("id=".$_SESSION['selectedEditor']);
    $dbTblEditorenParameter->refresh();
        
    $cobTypenSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_editoren", "selectedEditor", $_SESSION['selectedEditor']);
    $cobTypenSelect->setDirectSelect(true);
    
    $frmTypeSelect = new Form();
    $frmTypeSelect->add($cobTypenSelect);
    $frmTypeSelect->show();
    
    $spc = new Spacer(20);
    $spc->show();
    
   
    $ttlParam = new Title("Editoren");
    $ttlParam->setAlign("left");
    
    $tblMain = new Table(array("", ""));
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $ttlParam);
    $tblMain->addRow($rMainT1);

    
    $dbTblEditorenParameter->setHeaderEnabled(true);
    $dbTblEditorenParameter->setDeleteInUpdate(true);

    $newSwitchBtn = new Text("");
    
    // Neuer Eintrag
    if (isset($_REQUEST["InsertIntoDB" .$dbTblEditorenParameter->TABLENAME]) && $_REQUEST["InsertIntoDB" .$dbTblEditorenParameter->TABLENAME] == "Speichern") {
        $dbTblEditorenParameter->doInsert();
        $dbTblEditorenParameter->refresh();

    } else if (isset($_REQUEST[$dbTblEditorenParameter->getNewEntryButtonName()])) {

            $dbTblEditorenParameter->setBorder(0);
            $insMsk = $dbTblEditorenParameter->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($dbTblEditorenParameter->getNewEntryButtonName(), "-"));
            }

            $rNew = $tblMain->createRow();
            $rNew->setAttribute(0, $insMsk);
            $rNew->setSpawnAll(true);
            $tblMain->addRow($rNew);
            $tblMain->addSpacer(0,10);
    } else {
        $newSwitchBtn = $dbTblEditorenParameter->getNewEntryButton("Neuen Editor anlegen");    
    }

    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $newSwitchBtn);
    $tblMain->addRow($lS);


    if (isset($_REQUEST["DbTableUpdate" . $dbTblEditorenParameter->TABLENAME])) {
        $dbTblEditorenParameter->doUpdate();
    }
   

    $tblArduinoSwitches = $dbTblEditorenParameter->getUpdateAllMask();
    
    $tblMain->addSpacer(0,20);

    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $tblArduinoSwitches);
    $tblMain->addRow($lS);
    
    $tblMain->addSpacer(0, 20);
    $tblMain->addSpacer(1, 15);
    $tblMain->addSpacer(0, 20);

    
    $f = new Form();
    $f->add($tblMain);
    $f->show();



    
    include("EditorParameterConfig.inc.php");

?>