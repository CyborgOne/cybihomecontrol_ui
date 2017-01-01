 <?PHP
 
 
class TypDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveTypes = "DELETE FROM homecontrol_sender_typen_parameter WHERE senderTypId = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTypes);
    }
}
 
    $tblMain = new Table(array("", ""));

    $t2 = new Title("Sender-Typ Einstellungen");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $t2);
    $tblMain->addRow($rMainT1);

    $dbTblTypen = new TypDbTable($_SESSION['config']->DBCONNECT, 
                                'homecontrol_sender_typen', 
                                array("name"), 
                                "Name",
                                "",
                                "name",
                                "id=".$_SESSION['selectedType']);
    $dbTblTypen->setColSizes(array(800));
    $dbTblTypen->setHeaderEnabled(true);
    $dbTblTypen->setDeleteInUpdate(true);
    
    $newSwitchBtn = new Text("");

    
    // Neuer Eintrag
    if (isset($_REQUEST["InsertIntoDB" . $dbTblTypen->TABLENAME]) && $_REQUEST["InsertIntoDB" . $dbTblTypen->TABLENAME] == "Speichern") {
        $dbTblTypen->doInsert();
        $dbTblTypen->refresh();

    } else if (isset($_REQUEST[$dbTblTypen->getNewEntryButtonName()])) {

            $dbTblTypen->setBorder(0);
            $insMsk = $dbTblTypen->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($dbTblTypen->getNewEntryButtonName(), "-"));
            }

            $rNew = $tblMain->createRow();
            $rNew->setAttribute(0, $insMsk);
            $rNew->setSpawnAll(true);
            $tblMain->addRow($rNew);
            $tblMain->addSpacer(0,10);
    } else {
        $newSwitchBtn = $dbTblTypen->getNewEntryButton("Neuen Sender-Typ anlegen");    
    }

    if (isset($_REQUEST["DbTableUpdate" . $dbTblTypen->TABLENAME])) {
        $dbTblTypen->doUpdate();
    }



    
    $lS = $tblMain->createRow();
    $lS->setSpawnAll(true);
    $lS->setAttribute(0, $newSwitchBtn);
    $tblMain->addRow($lS);
    
    
    $tblArduinoSwitches = $dbTblTypen->getUpdateAllMask();
    
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