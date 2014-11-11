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

    $scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_sensor',
        array("id", "name", "beschreibung"), "ID, Name, Beschreibung", "", "name", "");

    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setWidth(600);

    $spc->show();

    $scDbTable->setBorder(0);

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST[$scDbTable->getNewEntryButtonName()])) {
        $scDbTable->showInsertMask();
    }

    // --------------------------------------------------
    //  Bearbeiten-Maske
    // --------------------------------------------------
    $updateMask = $scDbTable->getUpdateMask();
    $updateMask->show();

    $spc->setHeight(10);
    $spc->show();

    $newBtn = $scDbTable->getNewEntryButton();
    $newBtn->show();

}


?>