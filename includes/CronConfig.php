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

    $scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron',
        array("name", "montag", "dienstag", "mittwoch", "donnerstag", "freitag",
        "samstag", "sonntag", "stunde", "minute"),
        "Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min", "",
        "montag, dienstag, mittwoch, donnerstag, freitag, samstag, sonntag, stunde, minute",
        "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);


    $spc->show();

    $scDbTable->setBorder(0);

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST['dbTableNew'])) {
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

    if (isset($_REQUEST['SelectedCronItemToEdit'])) {
        $_SESSION['SelectedCronItemToEdit'] = $_REQUEST['SelectedCronItemToEdit'];
    }

    $table = new Table(array("", ""));
    $table->setWidth(640);

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Schaltgruppe bearbeiten"));
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
    $rAuswahl->setAttribute(0, new Text("cron auswählen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0, 20);

    $form = new Form();


    // Zuordnung ausgewählt

    if (isset($_SESSION['SelectedCronToEdit']) && strlen($_SESSION['SelectedCronToEdit']) >
        0) {

        $scItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_cron_items', array("id", "config_id", "art_id", "zimmer_id",
            "etagen_id", "on_off", "cron_id"),
            "ID, Objekt, Objekt-Art, Zimmer, Etage, An/Aus, CRON_ID", "cron_id=" . $_SESSION['SelectedCronToEdit'],
            "config_id DESC, zimmer_id DESC, etagen_id DESC", "cron_id=" . $_SESSION['SelectedCronToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id", "cron_id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);

        if (isset($_REQUEST["DbTableUpdate" . $scItemsDbTable->TABLENAME])) {
            $scItemsDbTable->doUpdate();
        }

        // Neuer Eintrag
        if (isset($_REQUEST['InsertIntoDBhomecontrol_cron_items']) && $_REQUEST['InsertIntoDBhomecontrol_cron_items'] ==
            "Speichern") {

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else
            if (isset($_REQUEST['dbTableNew_cron_items'])) {

                $scItemsDbTable->setBorder(0);
                $insMsk = $scItemsDbTable->getInsertMask();
                $hdnFld = $insMsk->getAttribute(1);
                if ($hdnFld instanceof Hiddenfield) {
                    $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_cron_items", "-"));
                }
                $insMsk->show();

            }


        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateAllMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);


        $newItemBtn = $scItemsDbTable->getNewEntryButton("Neuen Eintrag",
            "_Cron_items");
        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $newItemBtn);
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);


               $table->addSpacer(1, 30);

// --------------------------------------------------
//  Bedingungen
// --------------------------------------------------

        $r2Title = $table->createRow();
        $r2Title->setAttribute(0, new Title("Bedingungen bearbeiten"));
        $r2Title->setSpawnAll(true);
        $table->addRow($r2Title);


        $sqlCronItems = "SELECT id, id id2 FROM homecontrol_cron_items WHERE cron_id=" .
            $_SESSION['SelectedCronToEdit'];
        $cobSelectItems = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sqlCronItems,
            "SelectedCronItemToEdit", isset($_SESSION['SelectedCronItemToEdit']) ? $_SESSION['SelectedCronItemToEdit'] :
            "", "id", "id2", " ");
        $cobSelectItems->setDirectSelect(true);

        $r2Auswahl = $table->createRow();
        $r2Auswahl->setColSizes(array(120));
        $r2Auswahl->setAttribute(0, new Text("Schaltgruppe auswaehlen: "));
        $r2Auswahl->setAttribute(1, $cobSelectItems);
        $table->addRow($r2Auswahl);

        if (isset($_SESSION['SelectedCronItemToEdit']) && strlen($_SESSION['SelectedCronItemToEdit']) >
            0) {
            $termDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_term',
                array("id", "trigger_id", "trigger_type", "config_id", "term_type", "Cron_id",
                "min", "std", "value", "termcondition", "status", "montag", "dienstag",
                "mittwoch", "donnerstag", "freitag", "samstag", "sonntag", "order_nr", "and_or"),
                "", "", "order_nr", "trigger_id=" . $_SESSION['SelectedCronToEdit'] .
                " AND trigger_subid=" . $_SESSION['SelectedCronItemToEdit'] .
                " AND trigger_type=2");

            $termDbTable->setReadOnlyCols(array("id"));
            $termDbTable->setDeleteInUpdate(true);
            $termDbTable->setHeaderEnabled(true);


            $table->addSpacer(0, 10);



            // Neuer Eintrag
            if (isset($_REQUEST['InsertIntoDBhomecontrol_term']) && $_REQUEST['InsertIntoDBhomecontrol_term'] ==
                "Speichern") {

                $termDbTable->doInsert();
                $termDbTable->refresh();

            } else if (isset($_REQUEST[$termDbTable->getNewEntryButtonName()])) {
                    $termDbTable->setBorder(0);
                    $insMsk = $termDbTable->getInsertMask();
                    $hdnFld = $insMsk->getAttribute(1);
                    if ($hdnFld instanceof Hiddenfield) {
                        $insMsk->setAttribute(1, new Hiddenfield($termDbTable->getNewEntryButtonName(), "-"));
                    }
                    $rNew = $table->createRow();
                    $rNew->setAttribute(0, $insMsk);
                    $rNew->setSpawnAll(true);
                    $table->addRow($rNew);
                    $table->addSpacer(0,20);
            }

            $termCount=0;
            foreach( $termDbTable->ROWS as $r ){
                $term = new HomeControlTerm($r, $termCount>0);
                $rTermZuordnung = $table->createRow();
                $rTermZuordnung->setAttribute(0, $term);
                $rTermZuordnung->setSpawnAll(true);
                $table->addRow($rTermZuordnung);
                $termCount++;
            }

            $newItemBtn = $termDbTable->getNewEntryButton("Neuen Eintrag");
            $rZuordnung = $table->createRow();
            $rZuordnung->setAttribute(0, $newItemBtn);
            $rZuordnung->setSpawnAll(true);
            $table->addRow($rZuordnung);

            $table->addSpacer(1, 30);
        }

    }


    $form->add($table);

    $form->add(new Line());

    $form->show();


    // ------------------------------------------------
}

?>

