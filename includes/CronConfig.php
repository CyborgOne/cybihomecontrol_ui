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
                           'homecontrol_cron', 
	                    array(  "name",  "montag", "dienstag", "mittwoch", "donnerstag", "freitag", "samstag", "sonntag", "stunde", "minute") , 
                           "Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min",
			       "",
			       "montag, dienstag, mittwoch, donnerstag, freitag, samstag, sonntag, stunde, minute",
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
    if(isset($_REQUEST["DbTableUpdate".$scDbTable->TABLENAME])){
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

    if( isset($_REQUEST['SelectedcronToEdit']) && $_REQUEST['SelectedcronToEdit']>0 ){
      $_SESSION['SelectedcronToEdit'] = $_REQUEST['SelectedcronToEdit'];
    }

    $table = new Table(array("",""));
    $table->setWidth(640);

    $rTitle = $table->createRow();
    $rTitle->setAttribute(0, new Title("Zuordnungen bearbeiten"));
    $rTitle->setSpawnAll(true);
    $table->addRow($rTitle);

    $table->addSpacer(0,10);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_cron ORDER BY name", "SelectedcronToEdit", isset($_SESSION['SelectedcronToEdit'])?$_SESSION['SelectedcronToEdit']:"", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setColSizes(array(120));
    $rAuswahl->setAttribute(0, new Text("cron auswählen: "));
    $rAuswahl->setAttribute(1, $cobSelect);
    $table->addRow($rAuswahl);

    $table->addSpacer(0,20);

    $form = new Form();


// Zuordnung ausgewählt

    if( isset($_SESSION['SelectedcronToEdit']) && strlen($_SESSION['SelectedcronToEdit'])>0 ){

        $scItemsDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                                       'homecontrol_cron_items', 
	                                array("config_id", "art_id", "zimmer_id", "etagen_id", "on_off", "cron_id" ) , 
                                       "Objekt, Objekt-Art, Zimmer, Etage, An/Aus, CRON_ID",
                                       "cron_id=".$_SESSION['SelectedcronToEdit'],
                                       "config_id DESC, zimmer_id DESC, etagen_id DESC",
                                       "cron_id=".$_SESSION['SelectedcronToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id", "cron_id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        
        if(isset($_REQUEST["DbTableUpdate".$scItemsDbTable->TABLENAME])){
            $scItemsDbTable->doUpdate();
        }

// Neuer Eintrag
        if(isset($_REQUEST['InsertIntoDBhomecontrol_cron_items']) && $_REQUEST['InsertIntoDBhomecontrol_cron_items']=="Speichern"){

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else if(isset($_REQUEST['dbTableNew_cron_items']) ) {      

          $scItemsDbTable->setBorder(0);
          $insMsk = $scItemsDbTable->getInsertMask();
          $hdnFld = $insMsk->getAttribute(1);
          if($hdnFld instanceof Hiddenfield){
            $insMsk->setAttribute(1, new Hiddenfield("dbTableNew_cron_items", "-"));
          }
          $insMsk->show();

        }


        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateAllMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0,10);


    }

    $newItemBtn = $scDbTable->getNewEntryButton("Neuen Eintrag", "_cron_items");

    $form->add($table);
    $form->add($newItemBtn);
    $form->add(new Line());
    
    $form->show();



// ------------------------------------------------

    $currentDayNumber =  date('w', strtotime('today'));
    $currentStd =  date('G', strtotime('now')) + $_SESSION['config']->PUBLICVARS['timezoneadditional'];
    $currentMin =  date('i', strtotime('now'));
    $currentDayName = "weekDay";
    
    switch($currentDayNumber){
        case 0:
           $currentDayName = "Sonntag";
           break;
        case 1:
           $currentDayName = "Montag";
           break;
        case 2:
           $currentDayName = "Dienstag";
           break;
        case 3:
           $currentDayName = "Mittwoch";
           break;
        case 4:
           $currentDayName = "Donnerstag";
           break;
        case 5:
           $currentDayName = "Freitag";
           break;
        case 6:
           $currentDayName = "Samstag";
           break;
    }

    // Aktueller Wochentag muss übereinstimmen
    $whereStmtCurrCron = strtolower($currentDayName)."='J'";
    
    // Aktuelle Uhrzeit muss übereinstimmen
    $whereStmtCurrCron .= " and stunde=" .$currentStd ." and minute=" .$currentMin;

// echo "<br>".$whereStmtCurrCron."<br>";
    
    $currentCronDbTable  = new DbTable($_SESSION['config']->DBCONNECT,
                           'homecontrol_cron', 
	                    array(  "name", "beschreibung") , 
                           "Name, Beschreibung, Mo, Di, Mi, Do, Fr, Sa, So, Std, Min",
			       "",
			       "montag, dienstag, mittwoch, donnerstag, freitag, samstag, sonntag, stunde, minute",
			       $whereStmtCurrCron);

    $currentCronDbTable->setBorder(0);
    
// -----------------    

    $ttlCurrent = new Title("Aktuelle Jobs");
    
    $ttlCurrent->show();
    $currentCronDbTable->show();
}


?>

