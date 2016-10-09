<?PHP

class HomeControlMap extends Object {
    private $EDITMODE = false;
    private $LAYOUT_ART = false;
    private $CONTROL_IMAGE_WIDTH = 40;
    private $CONTROL_IMAGE_HEIGHT = 40;
    private $SENSOR_IMAGE_WIDTH = 26;
    private $SENSOR_IMAGE_HEIGHT = 26;
    
    public $LAYOUTART_DESKTOP = "DESKTOP";
    public $LAYOUTART_TABLET = "TABLET";
    public $LAYOUTART_MOBILE = "MOBILE";



    function HomeControlMap($editModus = false, $layoutArt = "DESKTOP") {
        $this->EDITMODE = $editModus;
        $this->LAYOUT_ART = $layoutArt;
    }




    function getInsertSensorMask($x, $y) {
        $mask = new Table(array("", "", ""));
        $mask->setSpacing(3);

        $mask->addSpacer(0, 10);

        $rTitle = $mask->createRow();
        $rTitle->setAttribute(0, new Title("Neuen Sensor Anlegen"));
        $rTitle->setSpawnAll(true);
        $mask->addRow($rTitle);

        $mask->addSpacer(0, 10);

        $rArt = $mask->createRow();
        $rArt->setAttribute(0, "Sensor-Art: ");
        $rArt->setAttribute(1, new ComboBox("sensor_art", getComboArrayBySql("SELECT id, name FROM homecontrol_sensor_arten")));
        $rArt->setAttribute(2, "");        
        $mask->addRow($rArt);
        
        $rName = $mask->createRow();
        $rName->addSpan(1,2);
        $rName->setAttribute(0, "Id: ");
        $rName->setAttribute(1, new TextField("id", "", 30, 30));
        $mask->addRow($rName);

        $rName = $mask->createRow();
        $rName->addSpan(1,2);
        $rName->setAttribute(0, "Name: ");
        $rName->setAttribute(1, new TextField("name", "", 30, 30));
        $mask->addRow($rName);

        $rKoord = $mask->createRow();
        $rKoord->setAttribute(0, "Koordinaten: ");
        $rKoord->setAttribute(1, new TextField("x", $x, 15, 4, false));
        $rKoord->setAttribute(2, new TextField("y", $y, 15, 4, false));
        $mask->addRow($rKoord);

        $rZimmer = $mask->createRow();
        $rZimmer->setAttribute(0, "Zimmer: ");
        $rZimmer->setAttribute(1, $this->getZimmerCombo("zimmer"));
        $rZimmer->addSpan(1, 2);
        $mask->addRow($rZimmer);
        
        $mask->addSpacer(0, 20);

        $rActions = $mask->createRow();
        $rActions->setAttribute(0, new Button("SaveNewSensorControl", "Speichern"));
        $rActions->setSpawnAll(true);
        $mask->addRow($rActions);

        $mask->addSpacer(0, 10);


        $frm = new Form();
        $frm->add(new HiddenField("InsertNewSensorControl", "do"));
        $frm->add($mask);

        return $frm;
    }



    function getInsertMask($x, $y) {
        
        
        $txfName = new TextField("Name", "", 30, 20);
        $txfName->setToolTip("Angezeigter Name des Ger&auml;tes.");
        
        $txfX = new TextField("X", $x, 15, 4, false);
        $txfX->setToolTip("X-Koordinate an der das Ger&auml;t im Raumplan angezeigt wird.");
        
        $txfY = new TextField("Y", $y, 15, 4, false);
        $txfY->setToolTip("Y-Koordinate an der das Ger&auml;t im Raumplan angezeigt wird.");
        
        $cboDimm = new Checkbox("dimmer", "", "J", "N");
        $cboDimm->setToolTip("Gibt an, ob es sich um einen dimmbaren Funkempfänger handelt. Nur m&ouml;glich f&uuml;r BT-Switch Ger&auml;te (FunkID-Bereich: 307-386)");
        
        $cobSender = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_sender", "sender_id");
        $cobSender->setToolTip("Gibt an welcher Sender zum Schalten des Ger&auml;tes verwendet wird.");
        
        $cobSignalId = $this->getFunkIdCombo("FunkId", false);
        $cobSignalId->setToolTip("Die ID die an den Sender geschickt wird (z.B. Funk-ID oder Relais. Je nach dem was f&uuml;r ein Sender gew&auml;hlt ist");
        
        $cobZimmer = $this->getZimmerCombo("Zimmer");
        $cobZimmer->setToolTip("Das Zimmer in dem sich das Ger&auml;t befindet.");
        
        $cobArt = new ComboBox("Art", getComboArrayBySql("SELECT id, name FROM homecontrol_art"));
        $cobArt->setToolTip("Bestimmt, welches Icon und Buttons f&uuml;r das Ger&auml;t angezeigt werden. ");
        
        
        $mask = new Table(array("", "", "", ""));
        $mask->setSpacing(3);

        $mask->addSpacer(0, 10);

        $rTitle = $mask->createRow();
        $rTitle->setAttribute(0, new Title("Neues Objekt Anlegen"));
        $rTitle->setSpawnAll(true);
        $mask->addRow($rTitle);
        $mask->addSpacer(0, 10);
        
        $r2 = $mask->createRow();
        $r2->setAttribute(0, "Name: ");
        $r2->setAttribute(1, $txfName);
        $r2->addSpan(2, 3);
        $mask->addRow($r2);

        $rZimmer = $mask->createRow();
        $rZimmer->setAttribute(0, "Zimmer: ");
        $rZimmer->setAttribute(1, $cobZimmer);
        $rZimmer->addSpan(1, 3);
        $mask->addRow($rZimmer);

        $mask->addSpacer(0,2);

        $r1 = $mask->createRow();
        $r1->setAttribute(0, "Koordinate X: ");
        $r1->setAttribute(1, $txfX);
        $r1->setAttribute(2, "Koordinate Y");
        $r1->setAttribute(3, $txfY);
        $mask->addRow($r1);

        $mask->addSpacer(0,2);
        
        $r4 = $mask->createRow();
        $r4->setAttribute(0, "Geraete-Art: ");
        $r4->setAttribute(1, $cobArt);
        $r4->setAttribute(2, "Dimmer?: ");
        $r4->setAttribute(3, $cboDimm );
        $mask->addRow($r4);

        $mask->addSpacer(0,5);
        
        $r3 = $mask->createRow();
        $r3->setAttribute(0, "Sender: ");
        $r3->setAttribute(1, $cobSender);
        $r3->setAttribute(2, "Signal-ID 1: ");
        $r3->setAttribute(3, $cobSignalId);
        //$r3->setAttribute(2, "Signal-ID 2: ");
        //$r3->setAttribute(3, $this->getFunkIdCombo("FunkId2", true, $r->getNamedAttribute("funk_id2")));
        $mask->addRow($r3);
        
        
        $mask->addSpacer(0, 20);

        $rActions = $mask->createRow();
        $rActions->setAttribute(0, new Button("SaveNewControl", "Speichern"));
        $rActions->setSpawnAll(true);
        $mask->addRow($rActions);

        $mask->addSpacer(0, 10);


        $frm = new Form();
        $frm->add(new HiddenField("InsertNewControl", "do"));
        $frm->add($mask);

        return $frm;
    }


    function getFunkIdCombo($name, $leerEintrag = false, $default = null) {
        $cobArr = array();

        if ($leerEintrag) {
            $cobArr[''] = " ";
        }

        $usedFunkIds = $this->getUsedFunkIds();

        // Wenn BT-Switch aktiv stehen 80 IDs mehr zur Verfuegung
        $maxFunkId = getPageConfigParam($_SESSION['config']->DBCONNECT,"btSwitchActive")=="J"?386:306;
        
        for ($i = 1; $i <= $maxFunkId; $i++) {
            if (!existsInArray($i, $usedFunkIds) || $default == $i) {
                $code = $i;
                $value = $i;

                $cobArr[$code] = $value;
            }
        }

        $cob = new Combobox($name, $cobArr, $default);
        return $cob;
    }


    function getUsedFunkIds() {
        $arr = array();

        $sql = "SELECT funk_id, funk_id2 FROM homecontrol_config";
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);

        while ($row = mysql_fetch_array($rslt)) {
            if (strlen($row['funk_id']) > 0) {
                $arr[$row['funk_id']] = $row['funk_id'];
            }

            if (strlen($row['funk_id2']) > 0) {
                $arr[$row['funk_id2']] = $row['funk_id2'];
            }
        }

        return $arr;
    }



    function getEditMask($id) {
        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "name", "funk_id", "funk_id2", "beschreibung", "control_art",
            "etage", "zimmer", "x", "y", "dimmer", "sender_id"), "", "", "", "id=" . $id);
        $r = $dbTable->getRow(1);

        $txfName = new TextField("Name", $r->getNamedAttribute("name"), 30, 20);
        $txfName->setToolTip("Angezeigter Name des Ger&auml;tes.");
        
        $txfX = new TextField("X", $r->getNamedAttribute("x"), 15, 4, false);
        $txfX->setToolTip("X-Koordinate an der das Ger&auml;t im Raumplan angezeigt wird.");
        
        $txfY = new TextField("Y", $r->getNamedAttribute("y"), 15, 4, false);
        $txfY->setToolTip("Y-Koordinate an der das Ger&auml;t im Raumplan angezeigt wird.");
        
        $cboDimm = new Checkbox("dimmer", "", "J", "N");
        $cboDimm->setToolTip("Gibt an, ob es sich um einen dimmbaren Funkempfänger handelt. Nur m&ouml;glich f&uuml;r BT-Switch Ger&auml;te (FunkID-Bereich: 307-386)");
        
        $cobSender = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_sender", "sender_id");
        $cobSender->setToolTip("Gibt an welcher Sender zum Schalten des Ger&auml;tes verwendet wird.");
        
        $cobSignalId = $this->getFunkIdCombo("FunkId", false, $r->getNamedAttribute("funk_id"));
        $cobSignalId->setToolTip("Die ID die an den Sender geschickt wird (z.B. Funk-ID oder Relais. Je nach dem was f&uuml;r ein Sender gew&auml;hlt ist");
        
        $cobZimmer = $this->getZimmerCombo("Zimmer", $r->getNamedAttribute("zimmer"));
        $cobZimmer->setToolTip("Das Zimmer in dem sich das Ger&auml;t befindet.");
        
        $cobArt = new ComboBox("Art", getComboArrayBySql("SELECT id, name FROM homecontrol_art"), $r->getNamedAttribute("control_art"));
        $cobArt->setToolTip("Bestimmt, welches Icon und Buttons f&uuml;r das Ger&auml;t angezeigt werden. ");
        
        $mask = new Table(array("", "", "", ""));
        $mask->setSpacing(3);
        $mask->addSpacer(0, 10);
        
        $rTitle = $mask->createRow();
        $rTitle->setAttribute(0, new Title("Objekt Bearbeiten"));
        $rTitle->setSpawnAll(true);
        $mask->addRow($rTitle);
        
        $mask->addSpacer(0, 10);
        
        $r2 = $mask->createRow();
        $r2->setAttribute(0, "Name: ");
        $r2->setAttribute(1, $txfName);
        $r2->addSpan(2, 3);
        $mask->addRow($r2);

        $rZimmer = $mask->createRow();
        $rZimmer->setAttribute(0, "Zimmer: ");
        $rZimmer->setAttribute(1, $cobZimmer);
        $rZimmer->addSpan(1, 3);
        $mask->addRow($rZimmer);

        $mask->addSpacer(0,2);

        $r1 = $mask->createRow();
        $r1->setAttribute(0, "Koordinate X: ");
        $r1->setAttribute(1, $txfX);
        $r1->setAttribute(2, "Koordinate Y");
        $r1->setAttribute(3, $txfY);
        $mask->addRow($r1);

        $mask->addSpacer(0,2);
        
        $r4 = $mask->createRow();
        $r4->setAttribute(0, "Geraete-Art: ");
        $r4->setAttribute(1, $cobArt);
        $r4->setAttribute(2, "Dimmer?: ");
        $r4->setAttribute(3, $cboDimm );
        $mask->addRow($r4);

        $mask->addSpacer(0,5);
        
        $r3 = $mask->createRow();
        $r3->setAttribute(0, "Sender: ");
        $r3->setAttribute(1, $cobSender);
        $r3->setAttribute(2, "Signal-ID 1: ");
        $r3->setAttribute(3, $cobSignalId);
        //$r3->setAttribute(2, "Signal-ID 2: ");
        //$r3->setAttribute(3, $this->getFunkIdCombo("FunkId2", true, $r->getNamedAttribute("funk_id2")));
        $mask->addRow($r3);
        
        
        $mask->addSpacer(0, 20);
        
        $r4 = $mask->createRow();
        $r4->setAttribute(0, new Button("SaveEditedControl", "Speichern"));
        $r4->setSpawnAll(true);
        $mask->addRow($r4);
        
        $mask->addSpacer(0, 10);
        
        $frm = new Form();
        $frm->add(new HiddenField("editControl", $_REQUEST['editControl']));
        $frm->add(new HiddenField("RowId", $r->getNamedAttribute("rowid")));
        $frm->add($mask);
        
        $frmDel = new Form();
        $frmDel->add(new Button("DelControl" . $r->getNamedAttribute("id"), "Entfernen"));
        $frmDel->add(new HiddenField("removeId", $r->getNamedAttribute("id")));
        
        $dv = new Div();
        $dv->add($frm);
        $dv->add($frmDel);
        
        return $dv;
    }


    function getEditSensorMask($id) {
	if($id == 999999999){
		return new Text("Sensor 999999999 kann nicht Bearbeitet werden, da es eine Systemkomponente ist!");
	}

        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_sensor',
                                        array(  "id", 
                                                "name", 
                                                "beschreibung", 
                                                "status_sensor", 
                                                "sensor_art", 
                                                "etage", 
                                                "zimmer", 
                                                "x", 
                                                "y",
                                                "lastValue",
                                                "lastSignal"
                                                ), 
                                        "ID, Name, Beschreibung, Status-Sensor?, Art, Etage, Zimmer, X, Y, Letzter Wert, Letztes Signal", 
                                        "", 
                                        "", 
                                        "id=" . $id);

        $r = $dbTable->getRow(1);

        $mask = new Table(array("", "", ""));
        $mask->setSpacing(3);

        $mask->addSpacer(0, 10);

        $rTitle = $mask->createRow();
        $rTitle->setAttribute(0, new Title("Sensor Bearbeiten"));
        $rTitle->setSpawnAll(true);
        $mask->addRow($rTitle);

        $mask->addSpacer(0, 10);

        $r4 = $mask->createRow();
        $r4->setAttribute(0, "Sensor-Art: ");
        $r4->setAttribute(1, new ComboBox("sensor_art", getComboArrayBySql("SELECT id, name FROM homecontrol_sensor_arten"), $r->getNamedAttribute("sensor_art")));
        $r4->addSpan(1, 2);
        $mask->addRow($r4);

        $rId = $mask->createRow();
        $rId->setAttribute(0, "Id: ");
        $rId->setAttribute(1, new TextField("id", $r->getNamedAttribute("id"), 30, 11));
        $rId->addSpan(1, 2);
        $mask->addRow($rId);

        $r2 = $mask->createRow();
        $r2->setAttribute(0, "Name: ");
        $r2->setAttribute(1, new TextField("name", $r->getNamedAttribute("name"), 30, 30));
        $r2->addSpan(1, 2);
        $mask->addRow($r2);

        $r1 = $mask->createRow();
        $r1->setAttribute(0, "Koordinaten: ");
        $r1->setAttribute(1, new TextField("x", $r->getNamedAttribute("x"), 15, 4, false));
        $r1->setAttribute(2, new TextField("y", $r->getNamedAttribute("y"), 15, 4, false));
        $mask->addRow($r1);

        $rZimmer = $mask->createRow();
        $rZimmer->setAttribute(0, "Zimmer: ");
        $rZimmer->setAttribute(1, $this->getZimmerCombo("zimmer", $r->getNamedAttribute("zimmer")));
        $rZimmer->addSpan(1, 2);
        $mask->addRow($rZimmer);
        
        $mask->addSpacer(0, 20);

        $r4 = $mask->createRow();
        $r4->setAttribute(0, new Button("SaveEditedSensor", "Speichern"));
        $r4->setSpawnAll(true);
        $mask->addRow($r4);

        $mask->addSpacer(0, 10);

        $frm = new Form();
        $frm->add(new HiddenField("editSensorControl", $_REQUEST['editSensorControl']));
        $frm->add(new HiddenField("editSensor", $_REQUEST['editSensorControl']));
        $frm->add(new HiddenField("RowId", $r->getNamedAttribute("rowid")));
        $frm->add($mask);


        $frmDel = new Form();
        $frmDel->add(new Button("DelControl" . $r->getNamedAttribute("id"), "Entfernen"));
        $frmDel->add(new HiddenField("removeSensorId", $r->getNamedAttribute("id")));

        $dv = new Div();
        $dv->add($frm);
        $dv->add($frmDel);

        return $dv;
    }


    function getZimmerCombo($name, $default = null) {
        $combo = new ComboBox($name, getComboArrayBySql("SELECT null,' ' FROM dual UNION SELECT id, name FROM homecontrol_zimmer WHERE etage_id=" 
                                                    .(isset($_SESSION['aktEtage']) && strlen($_SESSION['aktEtage'])>0?$_SESSION['aktEtage']:"-1") . " "), $default);

        return $combo;
    }


    function getEtagenCombo($name, $default = null) {
        $combo = new ComboBox($name, getComboArrayBySql("SELECT null,' ' FROM dual UNION SELECT id, name FROM homecontrol_etagen "), $default);

        return $combo;
    }


    function getZimmerFullSwitchNavigation() {
        $cobZimmer = $this->getZimmerCombo("FullSwitchZimmer");
        $frmFullSwitch = new Form();
        $frmFullSwitch->add(new Text("Zimmer:"));
        $frmFullSwitch->add($cobZimmer);
        $frmFullSwitch->add(new Button("fullSwitchOn", "Alles an"));
        $frmFullSwitch->add(new Button("fullSwitchOff", "Alles aus"));

        return $frmFullSwitch;
    }


    function getNavigationBar() {
        $mask = new Table(array("Etage Label", "Etage Combo", "Spacer",
            "Zimmer Full-Switch"));
        $mask->setAlignments(array("left", "left", "left", "right"));
        $mask->setHeight(40);
        $mask->setWidth(600);
        $mask->setVAlign("middle");
        $row = $mask->createRow();
        $mask->addRow($row);

        $cobEtage = $this->getEtagenCombo("aktEtage", $_SESSION['aktEtage']);
        $cobEtage->setDirectSelect(true);
        $frmEtage = new Form();
        $frmEtage->add($cobEtage);

        $frmFullSwitch = $this->getZimmerFullSwitchNavigation();
        
        $frmSensorOrControlSwitch = $this->EDITMODE?$this->getSensorOrControlSwitch():new Text("");

        $row->setAttribute(0, "Etage:");
        $row->setAttribute(1, $frmEtage);
        $row->setAttribute(2, $frmSensorOrControlSwitch);
        $row->setAttribute(3, ""); //$frmFullSwitch);

        return $mask;
    }

    
    function getAnlageArt(){
        return $_SESSION['AnlageArt'];
    }
    
    
    function checkSensorOrControlSwitch(){
        if(isset($_REQUEST['rbgAnlageArt'])){
            $_SESSION['AnlageArt'] = $_REQUEST['rbgAnlageArt']=="S"?"S":"C";
        }
        if(!isset($_SESSION['AnlageArt'])){
            $_SESSION['AnlageArt']="C";
        }
    }
    

    function getSensorOrControlSwitch(){
        $frmFullSwitch = new Form();
        
        $rbGroup = new RadiobuttonGroup("rbgAnlageArt");
        $rbGroup->add("Geraet", "C", $_SESSION['AnlageArt']=="C");
        $rbGroup->add("Sensor", "S", $_SESSION['AnlageArt']=="S");
        $rbGroup->setHorizontal(true);
        
        $frmFullSwitch->add(new Text("Anlage-Art:"));
        $frmFullSwitch->add($rbGroup);
        
        return $frmFullSwitch;
    }


    function handleEtage() {
        if ((!isset($_SESSION['aktEtage']) || strlen($_SESSION['aktEtage']) == 0)) {
            if (isset($_REQUEST['aktEtage'])) {
                $_SESSION['aktEtage'] = $_REQUEST['aktEtage'];
            } else {
                $minId = getDbValue("homecontrol_etagen", "min(id)","id>0");
                $_SESSION['aktEtage'] = strlen($minId)==0?"":$minId;
            }
        } else {
            if (isset($_REQUEST['aktEtage'])) {
                $_SESSION['aktEtage'] = $_REQUEST['aktEtage'];
            }
        }
    }


    function handleControlEdit($dbTable) {
        // Neuen Eintrag anlegen
        if (isset($_SESSION['aktEtage']) && isset($_REQUEST['Name']) && isset($_REQUEST['FunkId']) && isset($_REQUEST['Art'])) {
            
            if (isset($_REQUEST['InsertNewControl']) && $_REQUEST['InsertNewControl'] == "do" && isset($_REQUEST['X']) && isset($_REQUEST['Y'])) {
                $newRow = $dbTable->createRow();
                $newRow->setNamedAttribute("name", strlen($_REQUEST['Name'])>0?$_REQUEST['Name']:"Tmp ".time());
                $newRow->setNamedAttribute("x", $_REQUEST['X']);
                $newRow->setNamedAttribute("y", $_REQUEST['Y']);
                $newRow->setNamedAttribute("etage", $_SESSION['aktEtage']);
                $newRow->setNamedAttribute("funk_id", $_REQUEST['FunkId']);
                $newRow->setNamedAttribute("sender_id", $_REQUEST['sender_id']);
                $newRow->setNamedAttribute("control_art", $_REQUEST['Art']);

                if (isset($_REQUEST['Zimmer'])) {
                    $newRow->setNamedAttribute("zimmer", $_REQUEST['Zimmer']);
                } else {
                    $newRow->setNamedAttribute("zimmer", null);
                }

                if (isset($_REQUEST['FunkId2']) && $_REQUEST['FunkId2'] > 0) {
                    $newRow->setNamedAttribute("funk_id2", $_REQUEST['FunkId2']);
                } else {
                    $newRow->setNamedAttribute("funk_id2", null);
                }


                if (isset($_REQUEST['Beschreibung'])) {
                    $newRow->setNamedAttribute("beschreibung", $_REQUEST['Beschreibung']);
                } else {
                    $newRow->setNamedAttribute("beschreibung", null);
                }
                
                if (isset($_REQUEST['dimmer'])) {
                    $newRow->setNamedAttribute("dimmer", $_REQUEST['dimmer']);
                } else {
                    $newRow->setNamedAttribute("dimmer", "N");
                }

                $newRow->insertIntoDB();
                return true;
            }


            // Existierenden Eintrag bearbeiten
            if (isset($_REQUEST['editControl']) && strlen($_REQUEST['editControl']) > 0) {
                $newRow = $dbTable->getRowById($_REQUEST['editControl']);
                $newRow->setNamedAttribute("name", $_REQUEST['Name']);
                $newRow->setNamedAttribute("x", $_REQUEST['X']);
                $newRow->setNamedAttribute("y", $_REQUEST['Y']);
                $newRow->setNamedAttribute("etage", $_SESSION['aktEtage']);
                $newRow->setNamedAttribute("funk_id", $_REQUEST['FunkId']);
                $newRow->setNamedAttribute("control_art", $_REQUEST['Art']);

                if (isset($_REQUEST['Zimmer'])) {
                    $newRow->setNamedAttribute("zimmer", $_REQUEST['Zimmer']);
                } else {
                    $newRow->setNamedAttribute("zimmer", null);
                }

                if (isset($_REQUEST['FunkId2']) && $_REQUEST['FunkId2'] > 0) {
                    $newRow->setNamedAttribute("funk_id2", $_REQUEST['FunkId2']);
                } else {
                    $newRow->setNamedAttribute("funk_id2", null);
                }

                if (isset($_REQUEST['Beschreibung'])) {
                    $newRow->setNamedAttribute("beschreibung", $_REQUEST['Beschreibung']);
                } else {
                    $newRow->setNamedAttribute("beschreibung", null);
                }

                if (isset($_REQUEST['dimmer'])) {
                    $newRow->setNamedAttribute("dimmer", $_REQUEST['dimmer']);
                } else {
                    $newRow->setNamedAttribute("dimmer", "N");
                }

                $newRow->updateDB();

                return true;
            }
        }

        if (isset($_REQUEST['removeId']) && isset($_REQUEST['DelControl' . $_REQUEST['removeId']]) &&
            $_REQUEST['DelControl' . $_REQUEST['removeId']] == "Entfernen") {
            $newRow = $dbTable->getRowById($_REQUEST['removeId']);
            $newRow->deleteFromDb();
            
            return true;
        }

        return false;
    }



    function handleSensorEdit($dbTable) {
	if((isset($_REQUEST['id']) && $_REQUEST['id'] == 999999999) || (isset($_REQUEST['editSensor'])&& $_REQUEST['editSensor'] == 999999999 )){
		return new Text("Sensor-ID 999999999 ist ung&uuml;ltig, da es eine Systemkomponente ist!");
	}

        // Neuen Sensor anlegen
        if (isset($_SESSION['aktEtage']) && isset($_REQUEST['name']) && isset($_REQUEST['id']) && isset($_REQUEST['sensor_art'])){
             if(isset($_REQUEST['InsertNewSensorControl']) && $_REQUEST['InsertNewSensorControl'] == "do" && isset($_REQUEST['x']) && isset($_REQUEST['y'])) {
                $newRow = $dbTable->createRow();
                $newRow->setNamedAttribute("id", $_REQUEST['id']);
                $newRow->setNamedAttribute("name", strlen($_REQUEST['name'])>0?$_REQUEST['name']:"Tmp ".time());
                $newRow->setNamedAttribute("etage", $_SESSION['aktEtage']);
                $newRow->setNamedAttribute("x", $_REQUEST['x']);
                $newRow->setNamedAttribute("y", $_REQUEST['y']);
                $newRow->setNamedAttribute("sensor_art", $_REQUEST['sensor_art']);
                $newRow->setNamedAttribute("beschreibung", $_REQUEST['beschreibung']);
    
                if (isset($_REQUEST['zimmer'])) {
                    $newRow->setNamedAttribute("zimmer", $_REQUEST['zimmer']);
                } else {
                    $newRow->setNamedAttribute("zimmer", null);
                }
    
                $newRow->insertIntoDB();
                return true;
            }
    
            // Existierenden Eintrag bearbeiten
            if (isset($_REQUEST['editSensor']) && strlen($_REQUEST['editSensor']) > 0) {
                $newRow = $dbTable->getRowById($_REQUEST['editSensor']);
                $newRow->setNamedAttribute("id", $_REQUEST['id']);
                $newRow->setNamedAttribute("name", $_REQUEST['name']);
                $newRow->setNamedAttribute("x", $_REQUEST['x']);
                $newRow->setNamedAttribute("y", $_REQUEST['y']);
                $newRow->setNamedAttribute("etage", $_SESSION['aktEtage']);
                $newRow->setNamedAttribute("sensor_art", $_REQUEST['sensor_art']);
    
                if (isset($_REQUEST['zimmer'])) {
                    $newRow->setNamedAttribute("zimmer", $_REQUEST['zimmer']);
                } else {
                    $newRow->setNamedAttribute("zimmer", null);
                }
    
                if (isset($_REQUEST['beschreibung'])) {
                    $newRow->setNamedAttribute("beschreibung", $_REQUEST['beschreibung']);
                } else {
                    $newRow->setNamedAttribute("beschreibung", null);
                }
                
                $newRow->FORCE_ID_UPDATE=true;
                $newRow->updateDB();
    
                return true;
            }
        }

        if (isset($_REQUEST['removeSensorId']) && isset($_REQUEST['DelControl' . $_REQUEST['removeSensorId']]) &&
            $_REQUEST['DelControl' . $_REQUEST['removeSensorId']] == "Entfernen") {
	    
            if($_REQUEST['removeSensorId'] == 999999999 ){
		return new Text("Sensor-ID 999999999 ist ung&uuml;ltig, da es eine Systemkomponente ist!");
            }
            $newRow = $dbTable->getRowById($_REQUEST['removeSensorId']);
            $newRow->deleteFromDb();
            
            return true;
        }

        return false;
    }



    function postHandleControlEdit($dbTable) {
        // Neuen Eintrag anlegen
        if (isset($_REQUEST['InsertNewControl']) && $_REQUEST['InsertNewControl'] == "do" && isset($_REQUEST['X']) && isset($_REQUEST['Y'])) {
            if (!(isset($_REQUEST['Name']) && isset($_REQUEST['FunkId']) && isset($_REQUEST['Art']))) {
                $mask = $this->getInsertMask($_REQUEST['X'], $_REQUEST['Y'] - $_SESSION['additionalLayoutHeight']);
                $mask->show();

                $dv = new Div();
                $dv->setVAlign("middle");
                $dv->setAlign("center");
                $dv->setBorder(3);
                $dv->setStyle("border-color", "#ff2200");
                $dv->setStyle("background-color", "#aacc00");
                $dv->setXPos($_REQUEST['X']);
                $dv->setYPos($_REQUEST['Y']);
                $dv->setWidth($this->CONTROL_IMAGE_WIDTH);
                $dv->setHeight($this->CONTROL_IMAGE_HEIGHT);
                $dv->add(new Text("Neu", 3, true, false, false, false));
                $dv->show();
            }
        }
        
          // Neuen Sensor anlegen
        if (isset($_REQUEST['InsertNewSensorControl']) && $_REQUEST['InsertNewSensorControl'] == "do") {
            if (!(isset($_REQUEST['name']) && isset($_REQUEST['id']) && isset($_REQUEST['sensor_art']))) {
                $mask = $this->getInsertSensorMask($_REQUEST['X'], $_REQUEST['Y'] - $_SESSION['additionalLayoutHeight']);
                $mask->show();

                $dv = new Div();
                $dv->setVAlign("middle");
                $dv->setAlign("center");
                $dv->setBorder(3);
                $dv->setStyle("border-color", "#ff2200");
                $dv->setStyle("background-color", "#aacc00");
                $dv->setXPos($_REQUEST['X']);
                $dv->setYPos($_REQUEST['Y']);
                $dv->setWidth($this->SENSOR_IMAGE_WIDTH);
                $dv->setHeight($this->SENSOR_IMAGE_HEIGHT);
                $dv->add(new Text("Neu", 2, true, false, false, false));
                $dv->show();
            }
        }
        

  
        // Existierenden Eintrag bearbeiten
        if (isset($_REQUEST['editControl']) && strlen($_REQUEST['editControl']) > 0) {
            if (!(isset($_REQUEST['RowId']) && isset($_REQUEST['Name']) && isset($_REQUEST['FunkId']) &&
                isset($_REQUEST['Art']))) {
                $mask = $this->getEditMask($_REQUEST['editControl']);
                $mask->show();
            }
        }

          
        // Existierenden Eintrag bearbeiten
        if (isset($_REQUEST['editSensorControl']) && strlen($_REQUEST['editSensorControl']) > 0) {
            if (!(isset($_REQUEST['RowId']) && isset($_REQUEST['name']) && isset($_REQUEST['sensor_art']) &&
                isset($_REQUEST['sensor_art']))) {
                $mask = $this->getEditSensorMask($_REQUEST['editSensorControl']);
                $mask->show();
            }
        }

    }


    function getEtagenImage() {
        $img = new Image($this->getEtagenImagePath(), -1, -1, 640);
        return $img;
    }

    function getEtagenImagePath() {
        if(isset($_SESSION['aktEtage'])&&strlen($_SESSION['aktEtage'])>0){
            $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_etagen',
                                   array("pic"), "", "", "", "id=" . $_SESSION['aktEtage']);
            $row = $dbTable->getRow(1);

            return $row!=null?$row->getNamedAttribute("pic"):"/pics/default_etage.jpg";
        } else {
            return "/pics/default_etage.jpg";
        }
    }

    function showMap($dbTable, $dbSensorTable) {
        $sMap = $this->getSensorMap($dbSensorTable);
        $sMap->show();
        $map = $this->getMap($dbTable);
        $map->show();
    }

    function getMap($dbTable) {
        $dv = new Div();

        $bgTbl = new Table(array(""));
        $bgTbl->setOnClick("Coords()");
        $bgTbl->setWidth("600");
        $bgTbl->setHeight("340");
        $bgTbl->setStyle("background-image", "url(" . $this->getEtagenImagePath() . ")");

        $rowImg = $bgTbl->createRow();
        //    $img = $this->getEtagenImage();

        $rowImg->setAttribute(0, " ");
        $rowImg->setPadding("4px");

        $bgTbl->addRow($rowImg);
        $dv->add($bgTbl);

        for ($i = 1; $i <= $dbTable->getRowCount(); $i++) {
            $currConfigRow = $dbTable->getRow($i);

            $ctrlItem = new HomeControlItem($currConfigRow, $this->EDITMODE);
            $dv->add($ctrlItem);
        }

        return $dv;
    }

    function getSensorMap($dbTable) {
        $dv = new Div();

        for ($i = 1; $i <= $dbTable->getRowCount(); $i++) {
            $currConfigRow = $dbTable->getRow($i);

            if($currConfigRow->getNamedAttribute("x")>0 || $currConfigRow->getNamedAttribute("y")>0){
                $ctrlItem = new HomeControlSensor($currConfigRow, $this->EDITMODE);
                $ctrlItem->setIconViewActive(true);
                $dv->add($ctrlItem);
            }
        }

        return $dv;
    }

    function showMobileView() {
        $mobileView = $this->getMobileView();
        $mobileView->show();
    }

    function getZimmerName($id) {
        $sql = "SELECT name FROM homecontrol_zimmer WHERE id = " . $id;
        $rslt = $_SESSION["config"]->DBCONNECT->executeQuery($sql);

        if ($row = mysql_fetch_array($rslt)) {
            return $row['name'];
        } else {
            return "";
        }
    }

    function getEtagenName($id) {
        $sql = "SELECT name FROM homecontrol_etagen WHERE id = " . $id;
        $rslt = $_SESSION["config"]->DBCONNECT->executeQuery($sql);

        if ($row = mysql_fetch_array($rslt)) {
            return $row['name'];
        } else {
            return "";
        }
    }

    function getMobileView() {
        $columnCount = 1;
        $this->handleEtage();


        $layoutTable = new Table(array(""));
        
        $etagenSql = "SELECT id, name FROM homecontrol_etagen";
        $cobChooser = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $etagenSql, "aktEtage", strlen($_SESSION['aktEtage'])>0?$_SESSION['aktEtage']:"");
        $cobChooser->setDirectSelect(true);
        $cobChooser->setStyle("font-size", "40px");
        $frmChooser = new Form();
        $frmChooser->add($cobChooser);
                
        
        $layoutTable->addSpacer(0,30);
                
        $rChooser = $layoutTable->createRow();
        $rChooser->setSpawnAll(true);
        $rChooser->setAttribute(0, $frmChooser);
        $layoutTable->addRow($rChooser);
        
                
        $layoutTable->addSpacer(0,15);

        $layoutRow = $layoutTable->createRow();
        $layoutTable->addRow($layoutRow);

        if(isset($_SESSION['aktEtage'])&&strlen($_SESSION['aktEtage'])>0){
            $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
                array("id", "name", "funk_id", "funk_id2", "beschreibung", "control_art",
                "etage", "zimmer", "x", "y", "dimmer", "sender_id"), "", "", "zimmer", "etage=" . $_SESSION['aktEtage']);
    
            $currCol = 0;
            $zimmer = null;
            foreach ($dbTable->ROWS as $row) {
                if ($currCol >= $columnCount) {
                    $currCol = 0;
                    $layoutTable->addSpacer(0, 7);
                    $layoutTable->addSpacer(1, 2);
                    $layoutTable->addSpacer(0, 7);
                    $layoutRow = $layoutTable->createRow();
                    $layoutTable->addRow($layoutRow);
                }
    
                if ($zimmer != $row->getNamedAttribute("zimmer")) {
                    //zimmer
                    $zimmer = $row->getNamedAttribute("zimmer");
                    $currCol = 0;
    
                    $layoutTable->addSpacer(0, 25);
    
                    $layoutRow = $layoutTable->createRow();
                    $layoutTable->addRow($layoutRow);
                    $iT = new Text($this->getZimmerName($zimmer), "7", true);
                    $iTFt = $iT->getFonttype();
                    $iTFt->setColor("#7babdb");
                    $iT->setFonttype($iTFt);
                    $layoutRow->setAttribute(0, $iT);
                    $layoutTable->addSpacer(0, 15);
                    $layoutTable->addSpacer(1, 2);
                    $layoutTable->addSpacer(0, 15);
                    $layoutRow = $layoutTable->createRow();
                    $layoutTable->addRow($layoutRow);
                }
    
                $hcItem = new HomeControlItem($row, false);
                $switchComp = $hcItem->getMobileSwitch();
                $layoutRow->setAttribute($currCol, $switchComp);
    
                $currCol++;
            }
        }
        
        return $layoutTable;
    }


    function showTabletView() {
        $tabletView = $this->getTabletView();
        $tabletView->show();
    }


    function getTabletView() {
        $colCount = 4;
        $layoutTable = new Table(array("", "", "", ""));
        $layoutTable->setSpacing(20);

        $this->handleEtage();

        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "name", "funk_id", "funk_id2", "beschreibung", "control_art",
            "etage", "zimmer", "x", "y", "sender_id"), "", "", "etage, name", "");

        $currCol = $colCount;
        $layoutRow = null;
        $letzteEtage = "";

        foreach ($dbTable->ROWS as $row) {

            if ($letzteEtage != $row->getNamedAttribute("etage")) {
                $letzteEtage = $row->getNamedAttribute("etage");

                $currCol = 0;

                $layoutTable->addSpacer(0, 25);

                $ttl = new Title($letzteEtage);
                $layoutRow = $layoutTable->createRow();
                $layoutRow->setSpawnAll(true);
                $layoutTable->addRow($layoutRow);
                $layoutRow->setAttribute(0, $ttl);

                $layoutRow = $layoutTable->createRow();
                $layoutTable->addRow($layoutRow);
            } elseif ($currCol == $colCount) {
                $currCol = 0;
                $layoutRow = $layoutTable->createRow();
                $layoutTable->addRow($layoutRow);
            }

            $hcItem = new HomeControlItem($row, false);
            $switchComp = $hcItem->getMobileSwitch();
            $layoutRow->setAttribute($currCol, $switchComp);

            $currCol++;
        }

        return $layoutTable;
    }


    function checkSwitch(){
        if(isset($_REQUEST['schalte']) && $_REQUEST['schalte']!=0){
            $dbActionLog = new DbTable($_SESSION['config']->DBCONNECT, 
                                       "action_log", 
                                       array("sessionid", "userid", "zeit"),
                                       "Session, Benutzer, Timestamp",
                                       "",
                                       "geaendert desc",
                                       "sessionid='?schalte=".$_REQUEST['schalte']."'".(strlen($_REQUEST['dimmer'])>0?"-".$_REQUEST['dimmer']:"")
                                        .(strlen($_REQUEST['tmstmp'])>0?" AND zeit=".$_REQUEST['tmstmp']:"") );

            
            if(count($dbActionLog->ROWS)==0){
                if(strlen($_REQUEST['tmstmp'])<=0){
                    $_REQUEST['tmstmp'] = time();
                }
                $actionLogRow = $dbActionLog->createRow();
                $actionLogRow->setNamedAttribute("sessionid", "?schalte=".$_REQUEST['schalte']);
                $actionLogRow->setNamedAttribute("userid", $_SESSION['config']->CURRENTUSER->USERID);
                $actionLogRow->setNamedAttribute("zeit", $_REQUEST['tmstmp']);
                $actionLogRow->insertIntoDB();
   
                //echo "Dimmer: ".$_REQUEST['dimmer'];
                
                $dayInMillis = 86400000;
                $dbActionLog->setWhere("zeit is null or zeit < " .($_REQUEST['tmstmp']-$dayInMillis) );
                $dbActionLog->refresh();
                foreach($dbActionLog->ROWS as $r){
                    $r->deleteFromDb();
                }
                $this->switchObject($_REQUEST['schalte']>0?$_REQUEST['schalte']:-$_REQUEST['schalte'], $_REQUEST['schalte']>0?"on":"off", $_REQUEST['dimmer']);            
            }
        }
    }


    /*
    * Standard Anzeige
    *
    * Zeigt die Karte mit allen Controls an.
    */
    function show() {
        $dbArr = getComboArrayBySql("select id, name from homecontrol_sender");
        if(count($dbArr) <= 0){
            $msg = new Message("Kein Sender vorhanden", "Legen Sie zuerst unter *Einstellungen - Basis* mindestens einen Sender an.");
            $msg->show();
        } else {
            
            $this->checkSensorOrControlSwitch();
            
            $this->handleEtage();
            
            $this->checkSwitch();
            
            if ($this->LAYOUT_ART == $this->LAYOUTART_MOBILE) {
                $this->showMobileView();
                return;
            }
    
            if ($this->LAYOUT_ART == $this->LAYOUTART_TABLET) {
                $this->showTabletView();
                return;
            }
    
    
            // ungültige Layout-Art korrigieren auf Default (Desktop)
            $this->LAYOUT_ART = $this->LAYOUTART_DESKTOP;
    
            if(isset($_SESSION['aktEtage'])&&strlen($_SESSION['aktEtage'])>0){
                $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
                    array("id", "name", "funk_id", "funk_id2", "beschreibung", "control_art",
                    "etage", "zimmer", "x", "y", "dimmer", "sender_id"), "", "", "", "etage=" . $_SESSION['aktEtage']);
                $dbTable->setNoInsertCols("id");
                
                $dbSensorTable = new DbTable(   $_SESSION['config']->DBCONNECT, 
                                                'homecontrol_sensor',
                                                array(  "id", 
                                                        "name", 
                                                        "beschreibung", 
                                                        "status_sensor", 
                                                        "sensor_art", 
                                                        "etage", 
                                                        "zimmer", 
                                                        "x", 
                                                        "y",
                                                        "lastValue",
                                                        "lastSignal"
                                                        ), 
                                                "ID, Name, Beschreibung, Status-Sensor?, Art, Etage, Zimmer, X, Y, Letzter Wert, Letztes Signal", 
                                                "", 
                                                "etage, zimmer, name", 
                                                "etage=" . $_SESSION['aktEtage']);
                
                if ($this->EDITMODE) {
                    if($this->handleSensorEdit($dbSensorTable)){
                        $dbSensorTable->refresh();
                    }
                    if ($this->handleControlEdit($dbTable) ) {
                        $dbTable->refresh();
                    }
                }
            }
    
            $navBar = $this->getNavigationBar();
            $navBar->show();
    
            if(isset($_SESSION['aktEtage'])&&strlen($_SESSION['aktEtage'])>0){
                if ($this->EDITMODE) {
        
                    echo "
            	  <script type=\"text/javascript\">
            		function Coords () {
                            var Ziel = \"?";
        
                    if($_SESSION['AnlageArt']=="S"){
                       echo "InsertNewSensorControl";
                    } else {
                       echo "InsertNewControl"; 
                    }
                            
                    echo "=do&X=\" + window.event.pageX + \"&Y=\" + window.event.pageY;
                            window.location.href = Ziel;  
            		}
                  </script>
                    ";
                }
        
        
                $this->showMap($dbTable, $dbSensorTable);
        
                $this->postHandleControlEdit($dbTable);
            }
        }
    }

    
    function switchObject($id, $onOff, $dimm=0){
        switchShortcut("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'],$id."-".$onOff.($dimm>0&&$dimm<17?"-".$dimm:""), $_SESSION['config']->DBCONNECT);
    }
}

?>