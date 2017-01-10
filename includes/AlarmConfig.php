<?PHP

$t = new Title("Alarmanlagen Einstellungen");
$t->setAlign("left");
$t->show();

doUpdateAlarm();



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

    $spc = new Spacer(20); 
    $ln  = new Line();

    $scDbTable = new HcAlarmDbTable($_SESSION['config']->DBCONNECT, 'homecontrol_alarm',
        array("id", "name", "email", "cam_trigger_jn", "foto_senden_jn"), "Id, Name, Email, Kamera als \nAusloeser, Bei Alarm \nFoto per Email", "", "name", "");

    $scDbTable->setReadOnlyCols(array("id"));
    $scDbTable->setNoInsertCols(array("id"));
    $scDbTable->setDeleteInUpdate(true);
    $scDbTable->setHeaderEnabled(true);
    $scDbTable->setWidth("100%");
    $scDbTable->setPadding("5px");    
    $scDbTable->setSpacing("5px");
    $scDbTable->setVAlign("middle");
    $scDbTable->setBorder(0);
    $spc->show();

    if (isset($_REQUEST["DbTableUpdate" . $scDbTable->TABLENAME])) {
        $scDbTable->doUpdate();
    }

    // --------------------------------------------------
    //  Neuer Eintrag
    // --------------------------------------------------
    if (isset($_REQUEST[$scDbTable->getNewEntryButtonName()]) ||
        (isset($_REQUEST['InsertIntoDB' . $scDbTable->TABLENAME]) && $_REQUEST['InsertIntoDB'.$scDbTable->TABLENAME] == "Speichern")){
        $scDbTable->showInsertMask();
    } else {
        $newBtn = $scDbTable->getNewEntryButton("Neuen Alarm definieren");
        $newBtn->show();
    }
    
    $spc->setHeight(10);
    $spc->show();

}


// ---------------------------------------------------------------

    // --------------------------------------------------
    //  Zuordnungen
    // --------------------------------------------------

    $spc = new Spacer(10);
    $ln = new Line();

    $table = new Table(array("", ""));
    $table->setColSizes(array(150));
    
    $table->addSpacer(1,15);
    
    $table->addSpacer(0,15);

    $cobSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT,
        "SELECT id, name FROM homecontrol_alarm ORDER BY name", "SelectedAlarmToEdit",
        isset($_SESSION['SelectedAlarmToEdit']) ? $_SESSION['SelectedAlarmToEdit'] :
        "", "id", "name", " ");
    $cobSelect->setDirectSelect(true);

    $rAuswahl = $table->createRow();
    $rAuswahl->setAttribute(0, new Text("Alarm auswaehlen: "));
    $table->addRow($rAuswahl);

    $rAuswahl = $table->createRow();
    $rAuswahl->setAttribute(0, $cobSelect);
    $table->addRow($rAuswahl);


        $table->addSpacer(0,15);
        $rTitle = $table->createRow();
        $ttl = new Title("Grundeinstellung");
        $ttl->setAlign("left");
        $rTitle->setAttribute(0, $ttl);
        $rTitle->setSpawnAll(true);
        $table->addRow($rTitle);
        $table->addSpacer(0,15);


    $msk = isset($_SESSION['SelectedAlarmToEdit'])?$scDbTable->getSingleUpdateMask($_SESSION['SelectedAlarmToEdit']):new Text("");
    
    $rAuswahl = $table->createRow();
    $rAuswahl->setAttribute(0, $msk);
    $table->addRow($rAuswahl);

    
    $table->addSpacer(1, 10);



    // Zuordnung ausgewählt

    if (isset($_SESSION['SelectedAlarmToEdit']) && strlen($_SESSION['SelectedAlarmToEdit']) >
        0) {

        $scItemsDbTable = new AlarmItemDbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_alarm_items', array("id", "config_id", "art_id", "zimmer_id",
            "etagen_id", "on_off", "alarm_id"),
            "ID, Objekt, Objekt-Art, Zimmer, Etage, An/Aus", "alarm_id=" . $_SESSION['SelectedAlarmToEdit'],
            "config_id DESC, zimmer_id DESC, etagen_id DESC", "alarm_id=" . $_SESSION['SelectedAlarmToEdit']);

        $scItemsDbTable->setReadOnlyCols(array("id"));
        $scItemsDbTable->setNoInsertCols(array("id"));
        $scItemsDbTable->setDeleteInUpdate(true);
        $scItemsDbTable->setHeaderEnabled(true);
        
        // Neuer Eintrag
        if (isset($_REQUEST['InsertIntoDBhomecontrol_alarm_items']) && $_REQUEST['InsertIntoDBhomecontrol_alarm_items'] ==
            "Speichern") {

            $scItemsDbTable->doInsert();
            $scItemsDbTable->refresh();

        } else
            if (isset($_REQUEST[$scItemsDbTable->getNewEntryButtonName()])) {

                $scItemsDbTable->setBorder(0);
                $insMsk = $scItemsDbTable->getInsertMask();
                $hdnFld = $insMsk->getAttribute(1);
                if ($hdnFld instanceof Hiddenfield) {
                    $insMsk->setAttribute(1, new Hiddenfield($scItemsDbTable->getNewEntryButtonName(), "-"));
                }

                $rNew = $table->createRow();
                $rNew->setAttribute(0, $insMsk);
                $rNew->setSpawnAll(true);
                $table->addRow($rNew);
                $table->addSpacer(0,10);
        }
        if (isset($_REQUEST["DbTableUpdate" . $scItemsDbTable->TABLENAME])) {
            $scItemsDbTable->doUpdate();
        }

        $table->addSpacer(0,15);
        $r1Title = $table->createRow();
        $ttlS = new Title("Ggf. zu Schaltende Objekte");
        $ttlS->setAlign("left");
        $r1Title->setAttribute(0, $ttlS);
        $r1Title->setSpawnAll(true);
        $table->addRow($r1Title);
        $table->addSpacer(0,15);

        
        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $scItemsDbTable->getUpdateAllMask());
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(0, 10);

        $newItemBtn = $scItemsDbTable->getNewEntryButton("Neuen Eintrag");
        $rZuordnung = $table->createRow();
        $rZuordnung->setAttribute(0, $newItemBtn);
        $rZuordnung->setSpawnAll(true);
        $table->addRow($rZuordnung);

        $table->addSpacer(1, 10); 
 
 
// --------------------------------------------------
//  Bedingungen
// --------------------------------------------------

        $table->addSpacer(0,15);
        $r2Title = $table->createRow();
        $ttl2 = new Title("Ausloesende Bedingungen");
        $ttl2->setAlign("left");
        $r2Title->setAttribute(0, $ttl2);
        $r2Title->setSpawnAll(true);
        $table->addRow($r2Title);
        $table->addSpacer(0,15);


        $sqlAlarmItems = "SELECT id, id id2 FROM homecontrol_alarm_items WHERE alarm_id=" .
            $_SESSION['SelectedAlarmToEdit'];
            
        $cobSelectItems = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sqlAlarmItems,
            "SelectedAlarmItemToEdit", 
            isset($_SESSION['SelectedAlarmItemToEdit']) ? $_SESSION['SelectedAlarmItemToEdit'] : "", 0, 1, " ");
        $cobSelectItems->setDirectSelect(true);

        $r2Auswahl = $table->createRow();
        $r2Auswahl->setSpawnAll(true);
        $r2Auswahl->setAttribute(0, new Text("Schaltgruppe auswaehlen: "));
        $table->addRow($r2Auswahl);

        $r2Auswahl = $table->createRow();
        $r2Auswahl->setSpawnAll(true);
        $r2Auswahl->setAttribute(0, $cobSelectItems);
        $table->addRow($r2Auswahl);

        $table->addSpacer(0, 10);

        if (isset($_SESSION['SelectedAlarmItemToEdit'])) {
            $where = "trigger_id=" . $_SESSION['SelectedAlarmToEdit'] ." AND trigger_type=3 ";
            if(isset($_SESSION['SelectedAlarmItemToEdit']) && strlen($_SESSION['SelectedAlarmItemToEdit'])>0){
                $where .= " AND trigger_subid=" . $_SESSION['SelectedAlarmItemToEdit'];
            }
            
            $termDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_term',
                array("id", "trigger_id", "trigger_type", "config_id", "term_type", "sensor_id",
                "min", "std", "value", "termcondition", "status", "montag", "dienstag",
                "mittwoch", "donnerstag", "freitag", "samstag", "sonntag", "order_nr", "and_or"),
                "", "", "order_nr", 
                $where);

            $termDbTable->setReadOnlyCols(array("id"));
            $termDbTable->setNoInsertCols(array("id"));
            $termDbTable->setDeleteInUpdate(true);
            $termDbTable->setHeaderEnabled(true);
            $termDbTable->setWidth("100%");

            $table->addSpacer(0, 10);

            if (isset($_REQUEST[$termDbTable->getNewEntryButtonName()])) {
                $addUrl = $termDbTable->getNewEntryButtonName()."=".$_REQUEST[$termDbTable->getNewEntryButtonName()];
                $hcTermCreator = new HomeControlTermCreator($_SESSION['SelectedAlarmToEdit'], 
                                           $_SESSION['SelectedAlarmItemToEdit'], 3, $addUrl);

                $rNew = $table->createRow();
                $rNew->setAlign("center");
                $rNew->setAttribute(0, $hcTermCreator);
                $rNew->setSpawnAll(true);
                $table->addRow($rNew);
                $table->addSpacer(0, 20);
            }
                    
            $c1 = $_SESSION['config']->COLORS['Tabelle_Hintergrund_1'];
            $c2 = $_SESSION['config']->COLORS['Tabelle_Hintergrund_2'];

            $termCount=0;
            $termDbTable->refresh();
            foreach( $termDbTable->ROWS as $r ){
                $term = new HomeControlTerm($r, $termCount>0, true);
                $rTermZuordnung = $table->createRow();
                $rTermZuordnung->setStyle("padding","5px 5px");
                $rTermZuordnung->setAttribute(0, $term);
                $rTermZuordnung->setSpawnAll(true);
                $rTermZuordnung->setBackgroundColor($termCount%2==0?$c1:$c2);
                $table->addRow($rTermZuordnung);
                $termCount++;
            }
            $table->addSpacer(0, 10);
            $newItemBtn = $termDbTable->getNewEntryButton();
            $rZuordnung = $table->createRow();
            $rZuordnung->setAttribute(0, $newItemBtn);
            $rZuordnung->setSpawnAll(true);
            $table->addRow($rZuordnung);
        }

        
        $table->addSpacer(1, 30);
    }

 
 
        
// ---------------------------------------------------------------


$form = new Form();
$form->add($table);

$form->show();










function doUpdateAlarm(){
    
    if (isset($_REQUEST['SelectedAlarmToEdit'])) {
        $_SESSION['SelectedAlarmToEdit'] = $_REQUEST['SelectedAlarmToEdit'];
    }

    if (isset($_REQUEST['SelectedAlarmItemToEdit'])) {
        $_SESSION['SelectedAlarmItemToEdit'] = $_REQUEST['SelectedAlarmItemToEdit'];
    }
}




/**
 * Abgeleitete Klasse von DbTable, mit anpassung beim löschen.
 * So wird sicher gestellt, dass auch Details entfernt werden. 
 */
class HcAlarmDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveItems = "DELETE FROM homecontrol_alarm_items WHERE alarm_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveItems);
        
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=3 AND trigger_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);
    }
}

/**
 *
 */
class AlarmItemDbTable extends DbTable{
    function postDelete($id){
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=3 " 
                           ." AND trigger_id = ".$_SESSION['SelectedAlarmItemToEdit']
                           ." AND trigger_subid = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);
    }
}


?>