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

    // -----------------------------------
    //   Image Upload
    // -----------------------------------
    $t = new Title("Etagen");
    $t->setAlign("left");
    $t->show();

    $spc = new Spacer(20);
    $ln = new Line();

    $scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_etagen',
        array("name", "pic"), "Name, Raumplan", "", "name", "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setNoInsertCols(array("pic"));
    $scDbTable->setToCheck("name");
    $scDbTable->setWidth("100%");

    $spc->show();

    $scDbTable->setBorder(0);

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST['dbTableNewhomecontrol_etagen'])) {
        $scDbTable->showInsertMask();
    }
    if (isset($_REQUEST['InsertIntoDBhomecontrol_etagen']) && $_REQUEST['InsertIntoDBhomecontrol_etagen'] ==
        "Speichern") {
        $scDbTable->doInsert();
        
        $getMaxSql = "SELECT max(id) maxId FROM homecontrol_etagen";
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($getMaxSql);
        $r = mysql_fetch_array($rslt);
        
        exec("cp /var/www/pics/default_etage.jpg /var/www/pics/raumplan/".$r['maxId'].".jpg");
        
        $getMaxSql = "UPDATE homecontrol_etagen SET pic = 'pics/raumplan/".$r['maxId'].".jpg' WHERE id = ".$r['maxId'];
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($getMaxSql);
        
        $scDbTable->refresh();
    }


    // --------------------------------------------------
    //  Bearbeiten-Maske
    // --------------------------------------------------
    $tDel = $scDbTable->doDeleteFromUpdatemask();

    if (method_exists($tDel, "show")) {
        $fDel = new Form();
        $fDel->add($tDel);
        $fDel->show();
    }

    if (isset($_REQUEST["DbTableUpdate" . $scDbTable->TABLENAME])) {
        $scDbTable->doUpdate();
    }

    $tblEtagen = new Table(array("Name", "Raumplan", "hochladen", "entfernen"));
    $tblEtagen->setHeadEnabled(true);
    $tblEtagen->setVAlign("middle");
    $tblEtagen->setAlignments(array("left", "center", "center", "right"));
    $tblEtagen->setBackgroundColorChange(true);
    
    foreach ($scDbTable->ROWS as $etagenRow) {
        $rowId = $etagenRow->getNamedAttribute("rowid");

        $txfName = new Textfield("name" . $rowId, $etagenRow->getNamedAttribute("name"));
 
        $btnRaumplan = new Button("uploadImage" . $rowId, "Raumplan hochladen");

        $btnDelete = new Button("delete" . $rowId . "homecontrol_etagen", "entfernen");


        $imgRaumplan = "";
   
        if (isset($_REQUEST["uploadImage" . $rowId]) &&
            $_REQUEST["uploadImage" . $rowId] == "Raumplan hochladen" ) {
            $hdnImg = new Hiddenfield("uploadImage" .$rowId, $_REQUEST["uploadImage" .$rowId]);
            
            $imgUploader = new ImageUploader("/pics/raumplan", "RP_", "homecontrol_etagen",
                "pic", $rowId, $hdnImg, $rowId . ".jpg");
                
            $imgUploader->show();
            
            $imgRaumplan = new Image(getDbValue("homecontrol_etagen", "pic", "id=".$rowId));
        } else {
            $imgRaumplan = new Image($etagenRow->getNamedAttribute("pic"));
        }
        $imgRaumplan->setGenerated(false);
        $imgRaumplan->setWidth(140);
        
        $cBtnRaumplan = new Container();
        $cBtnRaumplan->add($btnRaumplan);
        $cBtnRaumplan->add(new Text("<br>JPG Datei mit den Ma&#223;en: 600x340",1,false,false,false,false));

        $r = $tblEtagen->createRow();
        $r->setStyle("padding","10px 5px");
        $r->setVAlign("middle");
        $r->setAttribute(0, $txfName);
        $r->setAttribute(1, $imgRaumplan);
        $r->setAttribute(2, $cBtnRaumplan);
        $r->setAttribute(3, $btnDelete);
        $tblEtagen->addRow($r);

    }

    $fEt = new Form();
    $okBtn = new Button("DbTableUpdatehomecontrol_etagen", "Speichern");
    $okBtn->setStyle("display", "none");
    $fEt->add($okBtn);
    $fEt->add($tblEtagen);
    $fEt->add(new Button("DbTableUpdatehomecontrol_etagen", "Speichern"));
    $fEt->add(new Hiddenfield("UpdateAllMaskIsActive", "true"));
    $fEt->show();


    $spc->setHeight(10);
    $spc->show();

    if (!isset($_REQUEST['dbTableNewhomecontrol_etagen'])) {
        $newBtn = $scDbTable->getNewEntryButton("Neue Etage anlegen");
        $newBtn->show();
    }

    $spc->show();
    $ln->show();

    $spc->setHeight(20);
    $spc->show();


    // --------------------------------------------------
    //         ZIMMER
    // --------------------------------------------------

    if (isset($_REQUEST['SelectedEtageToEdit'])) {
        $_SESSION['SelectedEtageToEdit'] = $_REQUEST['SelectedEtageToEdit'];
    }

    $table = new Table(array("", ""));
    $table->setWidth("100%");

    $ttlZ =  new Title("Zimmer");
    $ttlZ->setAlign("left");

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0,$ttlZ);
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0, 10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT,
        "SELECT id, name FROM homecontrol_etagen ORDER BY name", "SelectedEtageToEdit",
        isset($_SESSION['SelectedEtageToEdit']) ? $_SESSION['SelectedEtageToEdit'] : "",
        "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(150));
    $rAuswahl->setAttribute(0, new Text("Etage auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0, 20);

    $form = new Form();


    // Zuordnung ausgewählt

    if (isset($_SESSION['SelectedEtageToEdit']) && strlen($_SESSION['SelectedEtageToEdit']) >
        0) {

        $scItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_zimmer', array("name", "etage_id"), "Name, Etage", "etage_id=" . $_SESSION['SelectedEtageToEdit'],
            "name", "etage_id=" . $_SESSION['SelectedEtageToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        $scItemsDbTable->setWidth("100%");

        // Neuer Eintrag
        if (isset($_REQUEST['InsertIntoDBhomecontrol_zimmer']) && $_REQUEST['InsertIntoDBhomecontrol_zimmer'] ==
            "Speichern") {

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else
            if (isset($_REQUEST['dbTableNewhomecontrol_zimmer'])) {

                $scItemsDbTable->setBorder(0);
                $insMsk = $scItemsDbTable->getInsertMask();
                $hdnFld = $insMsk->getAttribute(1);
                if ($hdnFld instanceof Hiddenfield) {
                    $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_zimmer", "-"));
                }


                $rx = $table->createRow();
                $rx->setAttribute(0, $insMsk);
                $rx->setSpawnAll(true);
                $table->addRow($rx);
            }

        if (isset($_REQUEST["DbTableUpdate" . $scItemsDbTable->TABLENAME])) {
            $scItemsDbTable->doUpdate();
        }

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateAllMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0, 10);

        $newItemBtn = $scItemsDbTable->getNewEntryButton("Neues Zimmer anlegen");
        $form->add($table);
        $form->add($newItemBtn);
    } else {
        $form->add($table);
    }
    $form->add(new Spacer());

    $form->show();
    
}

?>

