<?PHP

class HomeControlMap extends Object {
    private $EDITMODE = false;
    private $LAYOUT_ART = false;
    private $CONTROL_IMAGE_WIDTH = 40;
    private $CONTROL_IMAGE_HEIGHT = 40;

    public $LAYOUTART_DESKTOP = "DESKTOP";
    public $LAYOUTART_TABLET = "TABLET";
    public $LAYOUTART_MOBILE = "MOBILE";

    function HomeControlMap($editModus = false, $layoutArt = "DESKTOP") {
        $this->EDITMODE = $editModus;
        $this->LAYOUT_ART = $layoutArt;
    }


    function getInsertMask($x, $y) {
        $mask = new Table(array("", "", ""));
        $mask->setSpacing(3);

        $mask->addSpacer(0, 10);

        $rTitle = $mask->createRow();
        $rTitle->setAttribute(0, new Title("Neues Objekt Anlegen"));
        $rTitle->setSpawnAll(true);
        $mask->addRow($rTitle);

        $mask->addSpacer(0, 10);

        $rZimmer = $mask->createRow();
        $rZimmer->setAttribute(0, "Zimmer: ");
        $rZimmer->setAttribute(1, $this->getZimmerCombo("Zimmer"));
        $rZimmer->addSpan(1, 2);
        $mask->addRow($rZimmer);

        $rArt = $mask->createRow();
        $rArt->setAttribute(0, "Geraete-Art: ");
        $rArt->setAttribute(1, new ComboBox("Art", getComboArrayBySql("SELECT id, name FROM homecontrol_art")));
        $rArt->addSpan(1, 2);
        $mask->addRow($rArt);

        $rKoord = $mask->createRow();
        $rKoord->setAttribute(0, "Koordinaten: ");
        $rKoord->setAttribute(1, new TextField("X", $x, 15, 4, true));
        $rKoord->setAttribute(2, new TextField("Y", $y, 15, 4, true));
        $mask->addRow($rKoord);

        $rFunk = $mask->createRow();
        $rFunk->setAttribute(0, "Funk-ID 1/2: ");
        $rFunk->setAttribute(1, $this->getFunkIdCombo("FunkId", false));
        $rFunk->setAttribute(2, $this->getFunkIdCombo("FunkId2", true));
        $mask->addRow($rFunk);

        $rName = $mask->createRow();
        $rName->setAttribute(0, "Name: ");
        $rName->setAttribute(1, new TextField("Name", "", 30, 30));
        $rName->addSpan(1, 2);
        $mask->addRow($rName);

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

        for ($i = 1; $i <= 306; $i++) {
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
            "etage", "zimmer", "x", "y"), "", "", "", "id=" . $id);

        $r = $dbTable->getRow(1);

        $mask = new Table(array("", "", ""));
        $mask->setSpacing(3);

        $mask->addSpacer(0, 10);

        $rTitle = $mask->createRow();
        $rTitle->setAttribute(0, new Title("Objekt Bearbeiten"));
        $rTitle->setSpawnAll(true);
        $mask->addRow($rTitle);

        $mask->addSpacer(0, 10);

        $rZimmer = $mask->createRow();
        $rZimmer->setAttribute(0, "Zimmer: ");
        $rZimmer->setAttribute(1, $this->getZimmerCombo("Zimmer", $r->getNamedAttribute
            ("zimmer")));
        $rZimmer->addSpan(1, 2);
        $mask->addRow($rZimmer);

        $r4 = $mask->createRow();
        $r4->setAttribute(0, "Geraete-Art: ");
        $r4->setAttribute(1, new ComboBox("Art", getComboArrayBySql("SELECT id, name FROM homecontrol_art"),
            $r->getNamedAttribute("control_art")));
        $r4->addSpan(1, 2);
        $mask->addRow($r4);

        $r1 = $mask->createRow();
        $r1->setAttribute(0, "Koordinaten: ");
        $r1->setAttribute(1, new TextField("X", $r->getNamedAttribute("x"), 15, 4, false));
        $r1->setAttribute(2, new TextField("Y", $r->getNamedAttribute("y"), 15, 4, false));
        $mask->addRow($r1);

        $r3 = $mask->createRow();
        $r3->setAttribute(0, "Funk-ID 1/2: ");
        $r3->setAttribute(1, $this->getFunkIdCombo("FunkId", false, $r->
            getNamedAttribute("funk_id")));
        $r3->setAttribute(2, $this->getFunkIdCombo("FunkId2", true, $r->
            getNamedAttribute("funk_id2")));
        $mask->addRow($r3);

        $r2 = $mask->createRow();
        $r2->setAttribute(0, "Name: ");
        $r2->setAttribute(1, new TextField("Name", $r->getNamedAttribute("name"), 30, 20));
        $r2->addSpan(1, 2);
        $mask->addRow($r2);

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


    function getZimmerCombo($name, $default = null) {
        $combo = new ComboBox($name, getComboArrayBySql("SELECT null,' ' FROM dual UNION SELECT id, name FROM homecontrol_zimmer WHERE etage_id=" .
            $_SESSION['aktEtage'] . " "), $default);

        return $combo;
    }


    function getEtagenCombo($name, $default = null) {
        $combo = new ComboBox($name, getComboArrayBySql("SELECT null,' ' FROM dual UNION SELECT id, name FROM homecontrol_etagen "),
            $default);

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

        $row->setAttribute(0, "Etage:");
        $row->setAttribute(1, $frmEtage);
        $row->setAttribute(2, "");
        $row->setAttribute(3, ""); //$frmFullSwitch);

        return $mask;
    }


    function handleEtage() {
        if ((!isset($_SESSION['aktEtage']) || strlen($_SESSION['aktEtage']) == 0)) {
            if (isset($_REQUEST['aktEtage'])) {
                $_SESSION['aktEtage'] = $_REQUEST['aktEtage'];
            } else {
                $_SESSION['aktEtage'] = 2;
            }
        } else
            if (isset($_REQUEST['aktEtage'])) {
                $_SESSION['aktEtage'] = $_REQUEST['aktEtage'];
            }
    }


    function handleControlEdit($dbTable) {
        // Neuen Eintrag anlegen
        if ($_SESSION['aktEtage'] && isset($_REQUEST['Name']) && isset($_REQUEST['FunkId']) &&
            isset($_REQUEST['Art'])) {
            if (isset($_REQUEST['InsertNewControl']) && $_REQUEST['InsertNewControl'] ==
                "do" && isset($_REQUEST['X']) && isset($_REQUEST['Y'])) {
                $newRow = $dbTable->createRow();
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


    function postHandleControlEdit($dbTable) {
        // Neuen Eintrag anlegen
        if (isset($_REQUEST['InsertNewControl']) && $_REQUEST['InsertNewControl'] ==
            "do" && isset($_REQUEST['X']) && isset($_REQUEST['Y'])) {

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

        // Existierenden Eintrag bearbeiten
        if (isset($_REQUEST['editControl']) && strlen($_REQUEST['editControl']) > 0) {
            if (!(isset($_REQUEST['RowId']) && isset($_REQUEST['Name']) && isset($_REQUEST['FunkId']) &&
                isset($_REQUEST['Art']))) {
                $mask = $this->getEditMask($_REQUEST['editControl']);
                $mask->show();
            }
        }

    }


    function getEtagenImage() {
        $img = new Image($this->getEtagenImagePath(), -1, -1, 640);
        return $img;
    }

    function getEtagenImagePath() {
        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_etagen',
            array("pic"), "", "", "", "id=" . $_SESSION['aktEtage']);
        $row = $dbTable->getRow(1);

        return $row->getNamedAttribute("pic");

    }

    function showMap($dbTable) {
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

        //$layoutTable->setStyle("padding-bottom","5px");
        $etagenName = new Title($this->getEtagenName($_SESSION['aktEtage']), 0, 8, true, false);
        $layoutTable->addSpacer(0, 25);
        $layoutTable->addSpacer(1, 2);
        $layoutTable->addSpacer(0, 15);
        $layoutRow = $layoutTable->createRow();
        $layoutTable->addRow($layoutRow);
        $layoutRow->setAttribute(0, $etagenName);


        $layoutRow = $layoutTable->createRow();
        $layoutTable->addRow($layoutRow);


        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "name", "funk_id", "funk_id2", "beschreibung", "control_art",
            "etage", "zimmer", "x", "y"), "", "", "zimmer", "etage=" . $_SESSION['aktEtage']);

        $currCol = 0;
        $zimmer = null;
        foreach ($dbTable->ROWS as $row) {
            if ($currCol >= $columnCount) {
                $currCol = 0;
                $layoutTable->addSpacer(0, 15);
                $layoutTable->addSpacer(1, 2);
                $layoutTable->addSpacer(0, 15);
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
                $layoutRow->setAttribute(0, new Text($this->getZimmerName($zimmer), 7));
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
            "etage", "zimmer", "x", "y"), "", "", "etage, name", "");

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
            $this->switchObject($_REQUEST['schalte']>0?$_REQUEST['schalte']:-$_REQUEST['schalte'], $_REQUEST['schalte']>0?"on":"off");            
        }
    }


    /*
    * Standard Anzeige
    *
    * Zeigt die Karte mit allen Controls an.
    */
    function show() {

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

        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("name", "funk_id", "funk_id2", "beschreibung", "control_art",
            "etage", "zimmer", "x", "y"), "", "", "", "etage=" . $_SESSION['aktEtage']);

        if ($this->handleControlEdit($dbTable)) {
            $dbTable->refresh();
        }

        $navBar = $this->getNavigationBar();
        $navBar->show();


        if ($this->EDITMODE) {
            echo "
    	  <script type=\"text/javascript\">
    		function Coords () {
    
                    var Ziel = \"?InsertNewControl=do&X=\" + window.event.pageX + \"&Y=\" + window.event.pageY;
                    window.location.href = Ziel;  
    		}
          </script>
            ";
        }


        $this->showMap($dbTable);

        $this->postHandleControlEdit($dbTable);
    }



    function switchObject($id, $onOff){
        switchShortcut("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'],$id."-".$onOff);
    }
}

?>