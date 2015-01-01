<?PHP

if ( $_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->CURRENTUSER->STATUS != "user" ) {
            
   /* ------------------------------------
      BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;
     
    $USERSTATUS = new UserStatus($USR, -1, -1);
    
    $tbl = new Table( array("") );
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute( 0, $USERSTATUS );
    $tbl->addRow( $r );
    
    $tbl->show();
    /* --------------------------------- */


} else {


    $spc = new Spacer(20); 
    $ln  = new Line();

    $scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron',
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
    if(isset($_REQUEST['dbTableNewhomecontrol_cron']) ) {    
      $scDbTable->showInsertMask();
    }
    if(isset($_REQUEST['InsertIntoDBhomecontrol_cron']) && $_REQUEST['InsertIntoDBhomecontrol_cron']=="Speichern"){
       $scDbTable->doInsert();
     }
// --------------------------------------------------
//  Bearbeiten-Maske
// --------------------------------------------------
    if (isset($_REQUEST["DbTableUpdate" . $scDbTable->TABLENAME])) {
        $scDbTable->doUpdate();
    }

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

    if( isset($_REQUEST['SelectedCronToEdit']) ){
      $_SESSION['SelectedCronToEdit'] = $_REQUEST['SelectedCronToEdit'];
    }

    $table = new Table(array("",""));
    $table->setWidth("100%");

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Zuordnungen bearbeiten"));
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0,10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT,
        "SELECT id, name FROM homecontrol_cron ORDER BY name", "SelectedCronToEdit",
        isset($_SESSION['SelectedCronToEdit']) ? $_SESSION['SelectedCronToEdit'] : "",
        "id", "name", " ");    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(120));
    $rAuswahl->setAttribute(0, new Text("Cron auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0,20);

    $form = new Form();


// Zuordnung ausgewählt

    if( isset($_SESSION['SelectedCronToEdit']) && strlen($_SESSION['SelectedCronToEdit'])>0 ){

        $scItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_cron_items', array("id", "config_id", "art_id", "zimmer_id",
            "etagen_id", "on_off"),
            "ID, Objekt, Objekt-Art, Zimmer, Etage, An/Aus", "cron_id=" . $_SESSION['SelectedCronToEdit'],
            "config_id DESC, zimmer_id DESC, etagen_id DESC", "cron_id=" . $_SESSION['SelectedCronToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setNoInsertCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        $scItemsDbTable->setWidth("100%");

// Neuer Eintrag
        if(isset($_REQUEST['InsertIntoDBhomecontrol_cron_items']) && $_REQUEST['InsertIntoDBhomecontrol_cron_items']=="Speichern"){

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else if(isset($_REQUEST['dbTableNewhomecontrol_cron_items']) ) {      

          $scItemsDbTable->setBorder(0);
          $insMsk = $scItemsDbTable->getInsertMask();
          $hdnFld = $insMsk->getAttribute(1);
          if($hdnFld instanceof Hiddenfield){
            $insMsk->setAttribute(1, new Hiddenfield("dbTableNewhomecontrol_cron_items", "-"));
          }
          $insMsk->show();

        }
    
        if (isset($_REQUEST["DbTableUpdate" . $scItemsDbTable->TABLENAME])) {
            $scItemsDbTable->doUpdate();
        }

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0,10);

        $newItemBtn = $scItemsDbTable->getNewEntryButton();
        $form->add($table);
        $form->add($newItemBtn);
    } else {
        $form->add($table);
    }
    
    
    $form->show();

}


?>

