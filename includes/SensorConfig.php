<?PHP
/**
 * Abgeleitete Klasse von DbTable, mit anpassung beim löschen.
 * So wird sicher gestellt, dass auch Details entfernt werden. 
 */
class HcSensorDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveLogs = "DELETE FROM homecontrol_sensor_log WHERE sensor_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveLogs);
        
        $sqlRemoveItems = "DELETE FROM homecontrol_sensor_items WHERE sensor_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveItems);
        
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=1 AND trigger_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);
    }
}



$t = new Title("Sensor Einstellungen");
$t->setAlign("left");
$t->show();

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

    $spc = new Spacer(20);
    $ln = new Line();
    $spc->show();

    $table = new Table(array("", ""));
    $table->setColSizes(array(150));


    $sensorDBTbl = new HcSensorDbTable( $_SESSION['config']->DBCONNECT, 
                            "homecontrol_sensor", 
                            array("id", "name", "beschreibung"),
                            "Id, Name, Beschreibung",
                            "",
                            "name",
                            "");
    
    $sensorDBTbl->setDeleteInUpdate(true);
    $sensorDBTbl->setReadOnlyCols(array("id","geaendert", "lastSignal", "lastValue"));
//    $sensorDBTbl->setNoInsertCols(array("geaendert", "lastSignal", "lastValue"));
    
    
    // Neuer Eintrag
    if (isset($_REQUEST['InsertIntoDBhomecontrol_sensor']) && $_REQUEST['InsertIntoDBhomecontrol_sensor'] ==
        "Speichern") {

        $sensorDBTbl->doInsert();
        $sensorDBTbl->refresh();

    } else if (isset($_REQUEST[$sensorDBTbl->getNewEntryButtonName()])) {

            $sensorDBTbl->setBorder(0);
            $insMsk = $sensorDBTbl->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($sensorDBTbl->getNewEntryButtonName(), "-"));
            }

            $rNew = $table->createRow();
            $rNew->setAttribute(0, $insMsk);
            $rNew->setSpawnAll(true);
            $table->addRow($rNew);
            $table->addSpacer(0,10);
    }

    $form = new Form();

    $form->add($table);
    $form->add($sensorDBTbl->getUpdateAllMask());
    $form->add(new Spacer(10));
    $form->add($sensorDBTbl->getNewEntryButton("Neuen Sensor anlegen"));
    $form->add(new Spacer());
    
    $form->show();
}


?>

