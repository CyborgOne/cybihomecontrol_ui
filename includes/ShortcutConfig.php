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

    $scDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                           'homecontrol_shortcut', 
	                    array(  "name", "beschreibung" ) , 
                           "Name, Beschreibung",
			       "",
			       "name",
			       "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);


    $spc->show();

    $scDbTable->setBorder(0);

// --------------------------------------------------
//  Neuer Eintrag
// --------------------------------------------------
    if(isset($_REQUEST['dbTableNew']) ) {      
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

    if( isset($_REQUEST['SelectedShortcutToEdit']) ){
      $_SESSION['SelectedShortcutToEdit'] = $_REQUEST['SelectedShortcutToEdit'];
    }

    $table = new Table(array("",""));
    $table->setWidth(640);

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Zuordnungen bearbeiten"));
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0,10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_shortcut ORDER BY name", "SelectedShortcutToEdit", isset($_SESSION['SelectedShortcutToEdit'])?$_SESSION['SelectedShortcutToEdit']:"", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(120));
    $rAuswahl->setAttribute(0, new Text("Shortcut auswählen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0,20);

    $form = new Form();


// Zuordnung ausgewählt

    if( isset($_SESSION['SelectedShortcutToEdit']) && strlen($_SESSION['SelectedShortcutToEdit'])>0 ){

        $scItemsDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                                       'homecontrol_shortcut_items', 
	                                array("config_id", "art_id", "zimmer_id", "etagen_id", "on_off", "shortcut_id" ) , 
                                       "Objekt, Objekt-Art, Zimmer, Etage, An/Aus",
                                       "shortcut_id=".$_SESSION['SelectedShortcutToEdit'],
                                       "config_id DESC, zimmer_id DESC, etagen_id DESC",
                                       "shortcut_id=".$_SESSION['SelectedShortcutToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);


// Neuer Eintrag
        if(isset($_REQUEST['InsertIntoDBhomecontrol_shortcut_items']) && $_REQUEST['InsertIntoDBhomecontrol_shortcut_items']=="Speichern"){

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else if(isset($_REQUEST['dbTableNew_Shortcut_items']) ) {      

          $scItemsDbTable->setBorder(0);
          $insMsk = $scItemsDbTable->getInsertMask();
          $hdnFld = $insMsk->getAttribute(1);
          if($hdnFld instanceof Hiddenfield){
            $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_Shortcut_items", "-"));
          }
          $insMsk->show();

        }


        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0,10);


    }

    $newItemBtn = $scDbTable->getNewEntryButton("Neuen Eintrag", "_Shortcut_items");

    $form->add($table);
    $form->add($newItemBtn);
    
    $form->show();

}


?>

