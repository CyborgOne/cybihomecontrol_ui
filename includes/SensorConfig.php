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

    $scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_sensor',
        array("id", "name", "beschreibung"), "ID, Name, Beschreibung", "", "name", "");

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
    $updateMask = $scDbTable->getUpdateMask();
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

    if (isset($_REQUEST['SelectedSensorToEdit'])) {
        $_SESSION['SelectedSensorToEdit'] = $_REQUEST['SelectedSensorToEdit'];
    }


    if (isset($_REQUEST['SelectedSensorItemToEdit'])) {
        $_SESSION['SelectedSensorItemToEdit'] = $_REQUEST['SelectedSensorItemToEdit'];
    }

    $table = new Table(array("", ""));
    $table->setWidth(640);

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Schaltgruppe bearbeiten"));
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0, 10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT,
        "SELECT id, name FROM homecontrol_sensor ORDER BY name", "SelectedSensorToEdit",
        isset($_SESSION['SelectedSensorToEdit']) ? $_SESSION['SelectedSensorToEdit'] :
        "", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(120));
    $rAuswahl->setAttribute(0, new Text("Sensor auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0, 20);

    $form = new Form();


    // Zuordnung ausgewählt

    if (isset($_SESSION['SelectedSensorToEdit']) && strlen($_SESSION['SelectedSensorToEdit']) >
        0) {
        $scItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_sensor_items', array("id", "config_id", "art_id", "zimmer_id",
            "etagen_id", "on_off", "sensor_id"),
            "ID, Objekt, Objekt-Art, Zimmer, Etage, An/Aus", "sensor_id=" . $_SESSION['SelectedSensorToEdit'],
            "config_id DESC, zimmer_id DESC, etagen_id DESC", "sensor_id=" . $_SESSION['SelectedSensorToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);

        // Neuer Eintrag
        if (isset($_REQUEST['InsertIntoDBhomecontrol_sensor_items']) && $_REQUEST['InsertIntoDBhomecontrol_sensor_items'] ==
            "Speichern") {

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else
            if (isset($_REQUEST['dbTableNew_Sensor_items'])) {

                $scItemsDbTable->setBorder(0);
                $insMsk = $scItemsDbTable->getInsertMask();
                $hdnFld = $insMsk->getAttribute(1);
                if ($hdnFld instanceof Hiddenfield) {
                    $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_Sensor_items", "-"));
                }
                $insMsk->show();

            }

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0, 10);

        $newItemBtn = $scItemsDbTable->getNewEntryButton("Neuen Eintrag",
            "_Sensor_items");
        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $newItemBtn);
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(1, 30);


        // --------------------------------------------------
        //  Bedingungen
        // --------------------------------------------------

        $table->addSpacer(1, 30);

        $r2Title = $table->createRow();
        $r2Title->setAttribute(0, new Title("Bedingungen bearbeiten"));
        $r2Title->setSpawnAll(true);
        $table->addRow($r2Title);


        $sqlSensorItems = "SELECT id, id id2 FROM homecontrol_sensor_items WHERE sensor_id=" .
            $_SESSION['SelectedSensorToEdit'];
        $cobSelectItems = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sqlSensorItems,
            "SelectedSensorItemToEdit", isset($_SESSION['SelectedSensorItemToEdit']) ? $_SESSION['SelectedSensorItemToEdit'] :
            "", "id", "id2", " ");
        $cobSelectItems->setDirectSelect(true);

        $r2Auswahl = $table->createRow();
        $r2Auswahl->setColSizes(array(120));
        $r2Auswahl->setAttribute(0, new Text("Schaltgruppe auswaehlen: "));
        $r2Auswahl->setAttribute(1, $cobSelectItems);
        $table->addRow($r2Auswahl);

        if (isset($_SESSION['SelectedSensorItemToEdit']) && strlen($_SESSION['SelectedSensorItemToEdit']) >
            0) {
            $termDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_term',
                array("id", "trigger_id", "trigger_type", "config_id", "term_type", "sensor_id",
                "min", "std", "value", "termcondition", "status", "montag", "dienstag",
                "mittwoch", "donnerstag", "freitag", "samstag", "sonntag", "order_nr", "and_or"),
                "", "", "order_nr", "trigger_id=" . $_SESSION['SelectedSensorToEdit'] .
                " AND trigger_subid=" . $_SESSION['SelectedSensorItemToEdit'] .
                " AND trigger_type=1");

            $termDbTable->setReadOnlyCols(array("id"));
            $termDbTable->setDeleteInUpdate(true);
            $termDbTable->setHeaderEnabled(true);


            $table->addSpacer(0, 10);

            $termCount=0;
            foreach( $termDbTable->ROWS as $r ){
                $term = new HomeControlTerm($r, $termCount>0);
                $rTermZuordnung = $table->createRow();
                $rTermZuordnung->setAttribute(0, $term);
                $rTermZuordnung->setSpawnAll(true);
                $table->addRow($rTermZuordnung);
                $termCount++;
            }

            $newItemBtn = $termDbTable->getNewEntryButton("Neuen Eintrag", "_Sensor_items");
            $rZuordnung = $table->createRow();
            $rZuordnung->setAttribute(0, $newItemBtn);
            $rZuordnung->setSpawnAll(true);
            $table->addRow($rZuordnung);

            $table->addSpacer(1, 30);
        }

    }


    $form->add($table);

    $form->show();

}

?>

