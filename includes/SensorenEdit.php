<?PHP


if ($_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->
    CURRENTUSER->STATUS != "user") {

    /* ------------------------------------
    BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;

    $USERSTATUS = new UserStatus($USR, -1, -1);

    $tbl = new Table(array(""));
    $tbl->setWidth(600);
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute(0, $USERSTATUS);
    $tbl->addRow($r);

    $tbl->show();
    /* --------------------------------- */

} else {

    $spc = new Spacer(20); 
    $ln  = new Line();

    $scDbTable = new HcSensorDbTable($_SESSION['config']->DBCONNECT, 'homecontrol_sensor',
        array("name", "id", "beschreibung"), "Name, ID, Beschreibung", "", "name", "");

    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setWidth("100%");

    $spc->show();

    $scDbTable->setBorder(0);

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST[$scDbTable->getNewEntryButtonName()]) ||
        (isset($_REQUEST['InsertIntoDB' . $scDbTable->TABLENAME]) && $_REQUEST['InsertIntoDB'.$scDbTable->TABLENAME] == "Speichern")){
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

}


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

?>