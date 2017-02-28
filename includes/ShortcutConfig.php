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

    $ttlZuord = new Title("Shortcut Einstellungen");
    $ttlZuord->setAlign("left");
    $ttlZuord->show();

    $spc = new Spacer(20); 
    $ln  = new Line();

    $scDbTable  = new ShortcutTable($_SESSION['config']->DBCONNECT,
                            'homecontrol_shortcut',
                            array( "id", "name", "beschreibung", "show_shortcut" ) , 
                            "Id, Name, Beschreibung, Shortcut anzeigen",
			                "",
                            "name",
                            "");
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setInvisibleCols(array("id"));
    $scDbTable->setNoInsertCols(array("id"));
    $scDbTable->setNoUpdateCols(array("id"));
    
    $scDbTable->setWidth("100%");

    $spc->show();

    $scDbTable->setBorder(0);

// --------------------------------------------------
//  Neuer Eintrag
// --------------------------------------------------
    if(isset($_REQUEST['dbTableNewhomecontrol_shortcut']) ) {    
      $scDbTable->showInsertMask();
    }
    if(isset($_REQUEST['InsertIntoDBhomecontrol_shortcut']) && $_REQUEST['InsertIntoDBhomecontrol_shortcut']=="Speichern"){
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

    if( isset($_REQUEST['SelectedShortcutToEdit']) ){
      $_SESSION['SelectedShortcutToEdit'] = $_REQUEST['SelectedShortcutToEdit'];
    }

    $table = new Table(array("",""));
    $table->setWidth("100%");
    
    $ttlZuord = new Title("Zuordnungen bearbeiten");
    $ttlZuord->setAlign("left");
    
    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, $ttlZuord);
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0,10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_shortcut ORDER BY name", "SelectedShortcutToEdit", isset($_SESSION['SelectedShortcutToEdit'])?$_SESSION['SelectedShortcutToEdit']:"", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(150));
    $rAuswahl->setAttribute(0, new Text("Shortcut auswaehlen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0,20);

    $form = new Form();


// Zuordnung ausgewählt
    $shortcut = null;
    if( isset($_SESSION['SelectedShortcutToEdit']) && strlen($_SESSION['SelectedShortcutToEdit'])>0 ){
        $shortcut = new HomeControlShortcut($scDbTable->getRowById($_SESSION['SelectedShortcutToEdit']));
        
        $scItemsDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                                       'homecontrol_shortcut_items', 
	                                array("config_id", "art_id", "zimmer_id", "etagen_id",  "shortcut_id" ) , 
                                       "Objekt, Objekt-Art, Zimmer, Etage",
                                       "shortcut_id=".$_SESSION['SelectedShortcutToEdit'],
                                       "config_id DESC, zimmer_id DESC, etagen_id DESC",
                                       "shortcut_id=".$_SESSION['SelectedShortcutToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setNoUpdateCols(array("shortcut_id"));
        $scItemsDbTable->setInvisibleCols(array("shortcut_id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        $scItemsDbTable->setWidth("100%");

// Neuer Eintrag
        if(isset($_REQUEST['InsertIntoDBhomecontrol_shortcut_items']) && $_REQUEST['InsertIntoDBhomecontrol_shortcut_items']=="Speichern"){

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else if(isset($_REQUEST['dbTableNewhomecontrol_shortcut_items']) ) {      

          $scItemsDbTable->setBorder(0);
          $insMsk = $scItemsDbTable->getInsertMask();
          $hdnFld = $insMsk->getAttribute(1);
          if($hdnFld instanceof Hiddenfield){
            $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_Shortcut_items", "-"));
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

        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getNewEntryButton());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $form->add($table);

        $table->addSpacer(0, 10);
        $table->addSpacer(1, 0);
        $table->addSpacer(0, 20);

    } else {
        $form->add($table);
    }

    $form->add(new Spacer());
    $form->show();
    
    
    if($shortcut!=null){    
        // --------------------------------------------------
        //  Parameter
        // --------------------------------------------------
        $tblItems = new Table(array(""));
        $tblItems->setAlign("left");
                  
        $ttlZuord = new Title("Parameter festlegen");
        $ttlZuord->setAlign("left");
    
        $rTitle = $tblItems->createRow();
        $rTitle->setAttribute(0, $ttlZuord);
        $rTitle->setSpawnAll(true);
        $tblItems->addRow($rTitle);
    
        $tblItems->addSpacer(0, 10);
        
        
        $shortcutItemRows = $shortcut->getItemRowsForShortcut();
        foreach($shortcutItemRows as $shortcutItemRow){
            $rItem = $tblItems->createRow();
            $ttl = new Title($shortcutItemRow->getNamedAttribute("name"));
            $ttl->setAlign("left");
            $rItem->setAttribute(0, $ttl);
            $tblItems->addRow($rItem);
    
            $itm = new HomeControlItem($shortcutItemRow);
            $itmParams = $itm->getAllParameter();
    
            $paramTbl = new Table(array("", ""));
            $paramTbl->setColSizes(array("50%", "50%"));
            foreach($itmParams as $itmParam){
                if((!$itmParam->isFix()||$itmParam->isDefaultLogic()) && (!$itmParam->isOptional() || $itm->isParameterOptionalActive($itmParam->getId()))){
                    $paramPrefix = "s".$shortcut->getId()."_".$itmParam->getId()."_".$itm->getId()."_";
                    if (isset($_REQUEST["saveParameters"]) && $_REQUEST["saveParameters"] == "Parameter speichern" &&
                        isset($_REQUEST[$paramPrefix.$itmParam->getName()]) && strlen($_REQUEST[$paramPrefix.$itmParam->getName()])>0 ){
                            $itm->setParameterValueForShortcut($itmParam->getRow(), $shortcut->getId(), $_REQUEST[$paramPrefix.$itmParam->getName()]);
                    }
    
                    $val = $itm->getParameterValueForShortcut($itmParam->getRow(), $shortcut->getId());
    
                    $valueEditObject="";
                    if($itmParam->isDefaultLogic()){
                        $tAn=$itm->getDefaultLogicAnText();
                        $tAus=$itm->getDefaultLogicAusText();
    
                        $valueEditObject = new Combobox($paramPrefix.$itmParam->getName(), array($tAn=>$tAn,$tAus=>$tAus) , $val);
                    } else {
                        $valueEditObject = $itm->getSender()->getTyp()->getEditParameterValueObject($itmParam->getRow(), $val, $paramPrefix, $val);
                    }
        
                    $rParam = $paramTbl->createRow();
                    $rParam->setAttribute(0, new Text($itmParam->getName(), 3));
                    $rParam->setAttribute(1, $valueEditObject);
                    $paramTbl->addRow($rParam);
                }
            }
    
            $rItem = $tblItems->createRow();
            $rItem->setAttribute(0, $paramTbl);
            $tblItems->addRow($rItem);
    
            $tblItems->addSpacer(0, 10);
        }
    
        $frmParams = new Form();
        $frmParams->add($tblItems);
        $frmParams->add(new Button("saveParameters", "Parameter speichern"));
        $frmParams->add(new Spacer());
        $frmParams->show();
    }
}



class ShortcutTable extends DbTable {
    function postDelete($id) {
        refreshEchoDb($_SESSION['config']->DBCONNECT);
    }
    
    function doInsert(){
        parent::doInsert();
        refreshEchoDb($_SESSION['config']->DBCONNECT);
    }
    
    function doUpdate(){
        parent::doUpdate();
        refreshEchoDb($_SESSION['config']->DBCONNECT);
    }
}


?>

