<?PHP

class TypParamDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveTypes = "DELETE FROM homecontrol_sender_parameter_values WHERE param_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTypes);
    }
}

    if(isset($_REQUEST['selectedType']) && strlen($_REQUEST['selectedType'])>0){
       $_SESSION['selectedType'] = $_REQUEST['selectedType']; 
    }

    $dbTblTypenParameter = new TypParamDbTable($_SESSION['config']->DBCONNECT, 
                                'homecontrol_sender_typen_parameter', 
                                array("name", "parameterArtId", "fix", "default_logic", "optional", "senderTypId", "id"), 
                                "Name, Art, Fix je\nControl, Standard\n(An/Aus), Optional",
                                "senderTypId=".$_SESSION['selectedType'],
                                "senderTypId, fix, name",
                                "");
    $dbTblTypenParameter->setColSizes(array(250,250,100,100,100));
                                    
    if(!(isset($_SESSION['selectedType']) && strlen($_SESSION['selectedType'])>0) ){
        $_SESSION['selectedType'] = $dbTblTypenParameter->getRow(1)!=null && $dbTblTypenParameter->getRow(1)->getNamedAttribute("id");  
    }

    
    $dbTblTypenParameter->setWhere("senderTypId=".$_SESSION['selectedType']);
    $dbTblTypenParameter->refresh();
    
    $dbTblTypenParameter->setInvisibleCols(array("id", "senderTypId"));


    $cobTypenSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_sender_typen", "selectedType", $_SESSION['selectedType']);
    $cobTypenSelect->setDirectSelect(true);
    
    $frmTypeSelect = new Form();
    $frmTypeSelect->add($cobTypenSelect);
    $frmTypeSelect->show();
    
    $spc = new Spacer(20);
    $spc->show();
    
    
    include("SenderTypConfig.inc.php");
    
    $ttlParam = new Title("Parameter");
    $ttlParam->setAlign("left");
    $tblMain = new Table(array("", ""));
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $ttlParam);
    $tblMain->addRow($rMainT1);

    
    $dbTblTypenParameter->setHeaderEnabled(true);
    $dbTblTypenParameter->setDeleteInUpdate(true);
    

    $newSwitchBtn = new Text("");
    
    // Neuer Eintrag
    if (isset($_REQUEST["InsertIntoDB" .$dbTblTypenParameter->TABLENAME]) && $_REQUEST["InsertIntoDB" .$dbTblTypenParameter->TABLENAME] == "Speichern") {
        $dbTblTypenParameter->doInsert();
        $dbTblTypenParameter->refresh();

    } else if (isset($_REQUEST[$dbTblTypenParameter->getNewEntryButtonName()])) {

            $dbTblTypenParameter->setBorder(0);
            $insMsk = $dbTblTypenParameter->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($dbTblTypenParameter->getNewEntryButtonName(), "-"));
            }

            $rNew = $tblMain->createRow();
            $rNew->setAttribute(0, $insMsk);
            $rNew->setSpawnAll(true);
            $tblMain->addRow($rNew);
            $tblMain->addSpacer(0,10);
    } else {
        $newSwitchBtn = $dbTblTypenParameter->getNewEntryButton("Neuen Parameter anlegen");    
    }
    
    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $newSwitchBtn);
    $tblMain->addRow($lS);


    if (isset($_REQUEST["DbTableUpdate" . $dbTblTypenParameter->TABLENAME])) {
        $dbTblTypenParameter->doUpdate();
    }
   

    $tblArduinoSwitches = $dbTblTypenParameter->getUpdateAllMask();

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


?>