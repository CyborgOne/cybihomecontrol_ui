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


    
    $t = new Title("Etagen");
    $t->show();
    
    $spc = new Spacer(20); 
    $ln  = new Line();

    $scDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                           'homecontrol_etagen', 
	                    array(  "name", "pic" ) , 
                           "Name, Raumplan",
			       "",
			       "name",
			       "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setWidth("100%");

    $spc->show();

    $scDbTable->setBorder(0);

// --------------------------------------------------
//  Neuer Eintrag
// --------------------------------------------------
    if(isset($_REQUEST['dbTableNewhomecontrol_etagen']) ) {    
      $scDbTable->showInsertMask();
    }
    if(isset($_REQUEST['InsertIntoDBhomecontrol_etagen']) && $_REQUEST['InsertIntoDBhomecontrol_etagen']=="Speichern"){
       $scDbTable->doInsert();
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

    if( isset($_REQUEST['SelectedEtageToEdit']) ){
      $_SESSION['SelectedEtageToEdit'] = $_REQUEST['SelectedEtageToEdit'];
    }

    $table = new Table(array("",""));
    $table->setWidth("100%");

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Zimmer"));
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0,10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_etagen ORDER BY name", "SelectedEtageToEdit", isset($_SESSION['SelectedEtageToEdit'])?$_SESSION['SelectedEtageToEdit']:"", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(150));
    $rAuswahl->setAttribute(0, new Text("Etage auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0,20);

    $form = new Form();


// Zuordnung ausgewählt

    if( isset($_SESSION['SelectedEtageToEdit']) && strlen($_SESSION['SelectedEtageToEdit'])>0 ){

        $scItemsDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                                       'homecontrol_zimmer', 
	                                array( "name",  "etage_id") , 
                                       "Name, Etage",
                                       "etage_id=".$_SESSION['SelectedEtageToEdit'],
                                       "name",
                                       "etage_id=".$_SESSION['SelectedEtageToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        $scItemsDbTable->setWidth("100%");

// Neuer Eintrag
        if(isset($_REQUEST['InsertIntoDBhomecontrol_zimmer']) && $_REQUEST['InsertIntoDBhomecontrol_zimmer']=="Speichern"){

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else if(isset($_REQUEST['dbTableNewhomecontrol_zimmer']) ) {      

          $scItemsDbTable->setBorder(0);
          $insMsk = $scItemsDbTable->getInsertMask();
          $hdnFld = $insMsk->getAttribute(1);
          if($hdnFld instanceof Hiddenfield){
            $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_Zimmer_items", "-"));
          }
          
 
          $rx = $table->createRow();
          $rx->setAttribute(0, $insMsk );
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

