 <?PHP
class ParameterDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveTypes = "DELETE FROM homecontrol_editor_parameter_possible WHERE editor_parameter_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTypes);
    }
    
    function postInsert($newId){
        $sql = "INSERT INTO homecontrol_editor_parameter_possible SET editor_parameter_id=" .$newId .", param_art_id=".getDbValue("homecontrol_sender_typen_parameter_arten", "min(id)"); 
        $_SESSION['config']->DBCONNECT->executeQuery($sql);
    }
}
 
    $tblMain = new Table(array("", ""));

    $t2 = new Title("Parameter");
    $t2->setAlign("left");
    $rMainT1 = $tblMain->createRow();
    $rMainT1->setSpawnAll(true);
    $rMainT1->setAttribute(0, $t2);
    $tblMain->addRow($rMainT1);

    $dbTblEditoren = new ParameterDbTable($_SESSION['config']->DBCONNECT, 
                                'homecontrol_editor_parameter', 
                                array("name", "editor_id"), 
                                "Name",
                                "editor_id=".$_SESSION['selectedEditor'],
                                "name",
                                "editor_id=".$_SESSION['selectedEditor']);
    
    $dbTblEditoren->setColSizes(array(800));
    $dbTblEditoren->setHeaderEnabled(true);
    $dbTblEditoren->setDeleteInUpdate(true);
    $dbTblEditoren->setInvisibleCols(array("editor_id"));
    
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
        $newSwitchBtn = $dbTblEditoren->getNewEntryButton("Neuen Parameter anlegen");    
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


    include("EditorParameterPossibleConfig.inc.php");
?>