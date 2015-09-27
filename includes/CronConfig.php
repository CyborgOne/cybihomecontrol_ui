<?PHP

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


    $spc = new Spacer(20);
    $ln = new Line();

    $scDbTable = new CronDbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron',
        array("name", "montag", "dienstag", "mittwoch", "donnerstag", "freitag",
        "samstag", "sonntag", "stunde", "minute"),
        "Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min", "",
        "montag, dienstag, mittwoch, donnerstag, freitag, samstag, sonntag, stunde, minute",
        "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setWidth("100%");

    $spc->show();

    $scDbTable->setBorder(0);

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST['dbTableNewhomecontrol_cron']) || 
        (isset($_REQUEST['InsertIntoDBhomecontrol_cron']) && $_REQUEST['InsertIntoDBhomecontrol_cron'] ==
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

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Zuordnungen bearbeiten"));
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0, 10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT,
        "SELECT id, name FROM homecontrol_cron ORDER BY name", "SelectedCronToEdit",
        isset($_SESSION['SelectedCronToEdit']) ? $_SESSION['SelectedCronToEdit'] : "",
        "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(120));
    $rAuswahl->setAttribute(0, new Text("Job auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0, 20);

    $form = new Form();


    // Zuordnung ausgewählt
    if (isset($_SESSION['SelectedCronToEdit']) && strlen($_SESSION['SelectedCronToEdit']) >
        0) {

        $scItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_cron_items', array("id", "config_id", "art_id", "zimmer_id",
            "etagen_id", "on_off", "cron_id"), "ID, Objekt, Objekt-Art, Zimmer, Etage, An/Aus",
            "cron_id=" . $_SESSION['SelectedCronToEdit'],
            "config_id DESC, zimmer_id DESC, etagen_id DESC", "cron_id=" . $_SESSION['SelectedCronToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setNoInsertCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        $scItemsDbTable->setWidth("100%");

        // Neuer Eintrag
        if (isset($_REQUEST['InsertIntoDBhomecontrol_cron_items']) && $_REQUEST['InsertIntoDBhomecontrol_cron_items'] ==
            "Speichern") {

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
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateAllMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0, 10);

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getNewEntryButton());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $form->add($table);

        $table->addSpacer(1, 30);

        // --------------------------------------------------
        //  Bedingungen
        // --------------------------------------------------
/*
        $r2Title = $table->createRow();
        $r2Title->setAttribute(0, new Title("Bedingungen bearbeiten"));
        $r2Title->setSpawnAll(true);
        $table->addRow($r2Title);
        $table->addSpacer(0, 5);


        $table->addSpacer(0, 10);

        if (isset($_SESSION['SelectedCronToEdit']) && strlen($_SESSION['SelectedCronToEdit']) >
            0) {
            $termDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_term',
                array("id", "trigger_id", "trigger_type", "config_id", "term_type", "sensor_id",
                "min", "std", "value", "termcondition", "status", "montag", "dienstag",
                "mittwoch", "donnerstag", "freitag", "samstag", "sonntag", "order_nr", "and_or"),
                "", "trigger_subid=1, trigger_type=2", "order_nr", "trigger_id=" . $_SESSION['SelectedCronToEdit'] .
                " AND trigger_subid=1 AND trigger_type=2");

            $termDbTable->setReadOnlyCols(array("id"));
            $termDbTable->setNoInsertCols(array("id"));
            $termDbTable->setDeleteInUpdate(true);
            $termDbTable->setHeaderEnabled(true);
            $termDbTable->setWidth("100%");

            $table->addSpacer(0, 10);

            if (isset($_REQUEST[$termDbTable->getNewEntryButtonName()])) {
                $addUrl = $termDbTable->getNewEntryButtonName() . "=" . $_REQUEST[$termDbTable->
                    getNewEntryButtonName()];
                $hcTermCreator = new HomeControlTermCreator($_SESSION['SelectedCronToEdit'], 1,
                    2, $addUrl);

                $rNew = $table->createRow();
                $rNew->setAlign("center");
                $rNew->setAttribute(0, $hcTermCreator);
                $rNew->setSpawnAll(true);
                $table->addRow($rNew);
                $table->addSpacer(0, 20);
            }

            $c1 = $_SESSION['config']->COLORS['Tabelle_Hintergrund_1'];
            $c2 = $_SESSION['config']->COLORS['Tabelle_Hintergrund_2'];

            $termCount = 0;
            $termDbTable->refresh();
            foreach ($termDbTable->ROWS as $r) {
                $term = new HomeControlTerm($r, $termCount > 0, true);
                $rTermZuordnung = $table->createRow();
                $rTermZuordnung->setStyle("padding", "5px 5px");
                $rTermZuordnung->setAttribute(0, $term);
                $rTermZuordnung->setSpawnAll(true);
                $rTermZuordnung->setBackgroundColor($termCount % 2 == 0 ? $c1 : $c2);
                $table->addRow($rTermZuordnung);
                $termCount++;
            }
        }

        $table->addSpacer(0, 10);

        $newItemBtn = $termDbTable->getNewEntryButton();
        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $newItemBtn);
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(1, 30);
*/
    } else {
        $form->add($table);
    }

    $form->show();
}




class CronDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=2 AND trigger_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);
        
        $sqlRemoveItems = "DELETE FROM homecontrol_cron_items WHERE cron_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveItems);
  
        $sqlRemovePause = "DELETE FROM homecontrol_cron_pause WHERE cron_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemovePause);
  
    }
}
?>

