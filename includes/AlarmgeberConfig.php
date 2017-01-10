<?PHP

$t = new Title("Alarmgeber");
$t->show();
if ($_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->
    CURRENTUSER->STATUS != "user") {

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
        
        
    $alarmgeberDBTbl = new DbTable( $_SESSION['config']->DBCONNECT, 
                                "homecontrol_alarm_geber", 
                                array("name", "ip"),
                                "",
                                "",
                                "name",
                                "");
    
    $alarmgeberDBTbl->setDeleteInUpdate(true);
    $alarmgeberDBTbl->setHeaderEnabled(true);
    $alarmgeberDBTbl->setToCheck("name", "ip");
    $alarmgeberDBTbl->setWidth("100%");
 
 
    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST['dbTableNew'. $alarmgeberDBTbl->TABLENAME])) {
        $alarmgeberDBTbl->showInsertMask();
    }
    if (isset($_REQUEST['InsertIntoDB'. $alarmgeberDBTbl->TABLENAME]) && $_REQUEST['InsertIntoDB'. $alarmgeberDBTbl->TABLENAME] == "Speichern") {
        $alarmgeberDBTbl->doInsert();
    }


    // --------------------------------------------------
    //  Bearbeiten-Maske
    // --------------------------------------------------
    if (isset($_REQUEST["DbTableUpdate" . $alarmgeberDBTbl->TABLENAME])) {
        $alarmgeberDBTbl->doUpdate();
    }

    $mskUpdate = $alarmgeberDBTbl->getUpdateAllMask();
    $mskUpdate->show();

    $spc = new Spacer();
    $spc->setHeight(10);
    $spc->show();

    if (!isset($_REQUEST['dbTableNew'. $alarmgeberDBTbl->TABLENAME])) {
        $newBtn = $alarmgeberDBTbl->getNewEntryButton("Neuen Alarmgeber anlegen");
        $newBtn->show();
    }
    
    $spc->show();
}
?>