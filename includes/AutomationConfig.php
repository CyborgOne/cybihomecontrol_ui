<?PHP

doUpdateAlarm();


$t = new Title("Regeln konfigurieren");
$t->setAlign("left");
$t->show();

$spc = new Spacer();
$spc->show();

$regelDbTbl   = new HcRegelnDbTable($_SESSION['config']->DBCONNECT, 
                            "homecontrol_regeln", 
                            array("name", "reverse_switch", "beschreibung"), 
                            "Name, Reverse-Switch, Beschreibung", 
                            "", 
                            "name", 
                            "");

$regelDbTbl->setDeleteInUpdate(true);
$regelDbTbl->setNoInsertCols(array("id", "beschreibung"));
$regelDbTbl->setNoUpdateCols(array("id"));
$regelDbTbl->setDefaults("reverse_switch='J'");
$regelDbTbl->setHeaderEnabled(true);
$regelDbTbl->setTexteditorEndabled(false);

$table = new Table(array("", ""));
$table->setColSizes(array(150));

// Neuer Eintrag
if (isset($_REQUEST['InsertIntoDB'.$regelDbTbl->TABLENAME]) && $_REQUEST['InsertIntoDB'.$regelDbTbl->TABLENAME] ==
    "Speichern") {

    $regelDbTbl->doInsert();
    $regelDbTbl->refresh();

} else if (isset($_REQUEST[$regelDbTbl->getNewEntryButtonName()])) {

    $regelDbTbl->setBorder(0);
    $insMsk = $regelDbTbl->getInsertMask();

    $hdnFld = $insMsk->getAttribute(1);
    if ($hdnFld instanceof Hiddenfield) {
        $insMsk->setAttribute(1, new Hiddenfield($regelDbTbl->getNewEntryButtonName(), "-"));
    }

    $rNew = $table->createRow();
    $rNew->setSpawnAll(true);
    $rNew->setAttribute(0, $insMsk);
    $table->addRow($rNew);
    $table->addSpacer(0,10);
}

$form = new Form();

$updMsk = $regelDbTbl->getUpdateMask();

$form->add($table);
$form->add($updMsk);
$form->add(new Spacer(10));
$form->add($regelDbTbl->getNewEntryButton("Neue Regel anlegen"));
$form->add($spc);

$form->show();





// -------------------------------------------
//                 BEDINGUNGEN
// -------------------------------------------
$table = new Table(array("", ""));
$table->addSpacer(0,15);
$table->setColSizes(array(130));

$ttlBedingung = new Title("Ausloesende Bedingungen");
$ttlBedingung->setAlign("left");

$r2Title = $table->createRow();
$r2Title->setSpawnAll(true);
$r2Title->setAttribute(0, $ttlBedingung);
$table->addRow($r2Title);
$table->addSpacer(0,15);

$sqlRegelItems = "SELECT id, name FROM homecontrol_regeln";
    
$cobSelectItems = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sqlRegelItems,
    "SelectedRegelToEdit", 
    isset($_SESSION['SelectedRegelToEdit']) ? $_SESSION['SelectedRegelToEdit'] : "", 0, 1, " ");
$cobSelectItems->setDirectSelect(true);

$r2Auswahl = $table->createRow();
$r2Auswahl->setAttribute(0, new Text("Regel auswaehlen: "));
$r2Auswahl->setAttribute(1, $cobSelectItems);
$table->addRow($r2Auswahl);

$table->addSpacer(0, 10);

if (isset($_SESSION['SelectedRegelToEdit']) && strlen($_SESSION['SelectedRegelToEdit'])>0) {
    $where = "trigger_id=" . $_SESSION['SelectedRegelToEdit'] ." AND trigger_type=1 ";
    
    $termDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_term',
        array("id", "trigger_id", "trigger_type", "config_id", "term_type", "sensor_id",
        "min", "std", "value", "termcondition", "status", "montag", "dienstag",
        "mittwoch", "donnerstag", "freitag", "samstag", "sonntag", "order_nr", "and_or"),
        "", "", "term_type,order_nr", 
        $where);

    $termDbTable->setReadOnlyCols(array("id"));
    $termDbTable->setNoInsertCols(array("id"));
    $termDbTable->setDeleteInUpdate(true);
    $termDbTable->setHeaderEnabled(true);
    $termDbTable->setWidth("100%");

    $table->addSpacer(0, 10);

    if (isset($_REQUEST[$termDbTable->getNewEntryButtonName()])) {
        $addUrl = $termDbTable->getNewEntryButtonName()."=".$_REQUEST[$termDbTable->getNewEntryButtonName()];
        $hcTermCreator = new HomeControlTermCreator($_SESSION['SelectedRegelToEdit'], 
                                   0, 1, $addUrl);

        $rNew = $table->createRow();
        $rNew->setAlign("center");
        $rNew->setSpawnAll(true);
        $rNew->setAttribute(0, $hcTermCreator);
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
        $rTermZuordnung->setSpawnAll(true);
        $rTermZuordnung->setAttribute(0, $term);
        $rTermZuordnung->setBackgroundColor($termCount%2==0?$c1:$c2);
        $table->addRow($rTermZuordnung);
        $termCount++;
    }
    $table->addSpacer(0, 10);
    $newItemBtn = $termDbTable->getNewEntryButton("Neue Bedingung anlegen");
    $rZuordnung = $table->createRow();
    $rZuordnung->setSpawnAll(true);
    $rZuordnung->setAttribute(0, $newItemBtn);
    $table->addRow($rZuordnung);
    
    $form = new Form();
    $form->add($spc);
    $form->add($table);
    $form->add($spc);
    $form->show();
    
    
    
    
    // -------------------------------------------
    //                 Schaltgruppen
    // -------------------------------------------
    
    $regelItemsDbTable = new DbTable($_SESSION['config']->DBCONNECT,
        'homecontrol_regeln_items', array("config_id", "art_id", "zimmer_id",
        "etagen_id", "on_off", "regel_id", "id"),
        "Objekt, Objekt-Art, Zimmer, Etage, An/Aus", "regel_id=" . $_SESSION['SelectedRegelToEdit'],
        "config_id DESC, zimmer_id DESC, etagen_id DESC", "regel_id=" . $_SESSION['SelectedRegelToEdit']);
    
    $regelItemsDbTable->setReadOnlyCols(array("id"));
    $regelItemsDbTable->setNoInsertCols(array("id"));
    $regelItemsDbTable->setNoUpdateCols(array("regel_id", "id"));
    $regelItemsDbTable->setDeleteInUpdate(true);
    $regelItemsDbTable->setHeaderEnabled(true);
    
    
    $itemsTable = new Table(array("", ""));
    //$itemsTable->setColSizes(array(150));
    $itemsTable->setBorder(0);
    
    $ttlItems = new Title("Zu schaltende Objekte");
    $ttlItems->setAlign("left");
    $itemsTable->addSpacer(0,15);
    
    $r1Title = $itemsTable->createRow();
    $r1Title->setSpawnAll(true);
    $r1Title->setAttribute(0, $ttlItems);
    $itemsTable->addRow($r1Title);
    
    // Neuer Eintrag
    if (isset($_REQUEST['InsertIntoDB'.$regelItemsDbTable->TABLENAME]) && $_REQUEST['InsertIntoDB'.$regelItemsDbTable->TABLENAME] == "Speichern") {
    
        $regelItemsDbTable->doInsert();
        $regelItemsDbTable->refresh();
    
    } else
        if (isset($_REQUEST[$regelItemsDbTable->getNewEntryButtonName()])) {
    
            $regelItemsDbTable->setBorder(0);
            $insMsk = $regelItemsDbTable->getInsertMask();
            $hdnFld = $insMsk->getAttribute(1);
            if ($hdnFld instanceof Hiddenfield) {
                $insMsk->setAttribute(1, new Hiddenfield($regelItemsDbTable->getNewEntryButtonName(), "-"));
            }
    
            $rNew = $itemsTable->createRow();
            $rNew->setSpawnAll(true);
            $rNew->setAttribute(0, $insMsk);
            $itemsTable->addRow($rNew);
            $itemsTable->addSpacer(0,10);
    }
    
    if (isset($_REQUEST["DbTableUpdate" . $regelItemsDbTable->TABLENAME])) {
        $regelItemsDbTable->doUpdate();
    }
    
    $itemsTable->addSpacer(0,15);
    
    $rZuordnung = $itemsTable->createRow();
    $rZuordnung->setSpawnAll(true);
    $rZuordnung->setAttribute(0, $regelItemsDbTable->getUpdateAllMask());
    $itemsTable->addRow($rZuordnung);
    
    $itemsTable->addSpacer(0, 10);
    
    $newItemBtn = $regelItemsDbTable->getNewEntryButton("Schaltung anlegen");
    $rZuordnung = $itemsTable->createRow();
    $rZuordnung->setSpawnAll(true);
    $rZuordnung->setAttribute(0, $newItemBtn);
    $itemsTable->addRow($rZuordnung);
    
    
    $form = new Form();
    $form->add($spc);
    $form->add($itemsTable);
    $form->add($spc);
    $form->show();
    
} else {
    // Regel-Auswahl Combobox anzeigen,
    // wenn noch keine Regel ausgewählt wurde
    $form = new Form();
    $form->add($spc);
    $form->add($table);
    $form->add($spc);
    $form->show();  
}
    








// -------------------------------------------
//          Klassen und Funktionen
// -------------------------------------------

function doUpdateAlarm(){
    if (isset($_REQUEST['editEtage'])) {
        if (isset($_REQUEST['showUpdateMaskhomecontrol_regeln'])) {
           $_REQUEST['SelectedRegelToEdit'] = $_REQUEST['showUpdateMaskhomecontrol_regeln'];
        }
    }

    if (isset($_REQUEST['SelectedRegelToEdit'])) {
        $_SESSION['SelectedRegelToEdit'] = $_REQUEST['SelectedRegelToEdit'];
    }    
}


class HcRegelnDbTable extends DbTable {
    function postDelete($id){
        $sqlRemoveItems = "DELETE FROM homecontrol_regeln_items WHERE regel_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveItems);
        
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=1 AND trigger_id = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);
    }
}




?>