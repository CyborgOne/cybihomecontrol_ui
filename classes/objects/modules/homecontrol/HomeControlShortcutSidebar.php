<?PHP

class ShortcutSidebar extends Object {
    public  $LAYOUT_ART_DESKTOP = "DESKTOP";
    public  $LAYOUT_ART_MOBILE = "MOBILE";

    private $LAYOUT_ART;
    private $SHORTCUTS_DB;

    private $SHORTCUTS_URL_COMMAND;
    private $SHORTCUTS_TOOLTIP;

    private $SHORTCUTS_ROW_COLOR1 = "#dedede";
    private $SHORTCUTS_ROW_COLOR2 = "#cdcdcd";
    private $SHORTCUTS_ROW_COLOR_LAST;

    private $ON_LABEL;
    private $OFF_LABEL;

    function ShortcutSidebar($LAYOUT_ART = "DESKTOP") {
        $this->LAYOUT_ART = $LAYOUT_ART;

        $this->SHORTCUTS_DB = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_shortcut', array("id", "name", "beschreibung"),
            "Id, Name, Beschreibung", "", "name", "");

        $this->ON_LABEL = "<div style=\"background-color:#33ee33;\">Ein</Div> ";
        $this->OFF_LABEL = "<div style=\"background-color:#ee3333;\">Aus</Div> ";
    }


    function getConfigName($id) {
        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("name"), "", "", "", "id=" . $id);

        $row = $configDb->getRow(1);

        if ($row != null) {
            return $row->getNamedAttribute("name");
        }
        return "";
    }


    function getConfigFunkId($id, $status) {
        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("funk_id", "funk_id2", "control_art"), "", "", "", "id=" . $id);

        $row = $configDb->getRow(1);

        if ($row != null) {
            if ($status == "off" && isFunk2Need($row->getNamedAttribute("control_art"))) {
                return $row->getNamedAttribute("funk_id2");
            } else {
                return $row->getNamedAttribute("funk_id");
            }
        }


        return "";
    }


    /**
     * Alle Config-IDs des Zimmers in die URL übernehmen
     */
    function addZimmerShortcutCommandItems($zimmerId, $onOff) {

        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "funk_id", "funk_id2"), "", "", "", "zimmer=" . $zimmerId);

        foreach ($configDb->ROWS as $itemRow) {
            $this->addShortcutCommandItem($itemRow->getNamedAttribute("id"), $onOff);
        }

    }


    /**
     * Alle Config-IDs zu Objekten dieser Art in die URL übernehmen
     */
    function addArtShortcutCommandItems($artId, $onOff) {

        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "funk_id", "funk_id2"), "", "", "", "control_art=" . $artId);

        foreach ($configDb->ROWS as $itemRow) {
            $this->addShortcutCommandItem($itemRow->getNamedAttribute("id"), $onOff);
        }

    }


    /**
     * Alle Config-IDs der Etage in die URL übernehmen
     */
    function addEtagenShortcutCommandItems($etagenId, $onOff) {
        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "funk_id", "funk_id2"), "", "", "", "etage=" . $etagenId);

        foreach ($configDb->ROWS as $itemRow) {
            $this->addShortcutCommandItem($itemRow->getNamedAttribute("id"), $onOff);
        }
    }

    
    function getShortcutImageString($configId, $width=50){
        $ret = "";
        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("*"), "", "", "", "id=" .$configId);
        
        if($configDb->getRow(1)!=null){
          $itm = new HomeControlItem($configDb->getRow(1));
          $ret = "<img src='" .$itm->getIconPath() ."' width='".$width."'>";
        }
        
        return $ret;        
    }


    /**
     * Wenn ID nicht schon enthalten ist, Einstellungs-Werte übernehmen
     */
    function addShortcutCommandItem($id, $status) {
        $funkId = $this->getConfigFunkId($id, $status);

        if (!strpos($this->SHORTCUTS_URL_COMMAND, "_" . $funkId . "-") && strlen($funkId) >
            0 && strlen($status) > 1) {
            $this->SHORTCUTS_URL_COMMAND .= "_" . $funkId . "-" . $status . ";";

            if ($this->SHORTCUTS_ROW_COLOR_LAST == $this->SHORTCUTS_ROW_COLOR1) {
                $this->SHORTCUTS_ROW_COLOR_LAST = $this->SHORTCUTS_ROW_COLOR2;
            } else {
                $this->SHORTCUTS_ROW_COLOR_LAST = $this->SHORTCUTS_ROW_COLOR1;
            }
            
            if($this->LAYOUT_ART == $this->LAYOUT_ART_MOBILE){
                $this->SHORTCUTS_TOOLTIP .= "<tr style=\"background-color:" . $this->
                    SHORTCUTS_ROW_COLOR_LAST . ";\"><td>" .$this->getShortcutImageString($id) . "</td><td>" . "<font size='7em'>" . $this->
                    getConfigName($id) . "</font></td><td><font size='7em'>" . ($status == "on" ? $this->
                    ON_LABEL : $this->OFF_LABEL) . "</font></td></tr>";
            } else {
                $this->SHORTCUTS_TOOLTIP .= "<tr style=\"background-color:" . $this->
                    SHORTCUTS_ROW_COLOR_LAST . ";\"><td>" .$this->getShortcutImageString($id,15) . "</td><td>" . "<font size='2'>" . $this->
                    getConfigName($id) . "</font></td><td><font size='2'>" . ($status == "on" ? $this->
                    ON_LABEL : $this->OFF_LABEL) . "</font></td></tr>";
                
            }
        }
    }


    /**
     * Liefert die URL zurück, mit der die Kombination 
     * für die gewünschte Schaltung an den Arduino übergeben wird.
     * Hierbei wird für jedes Element die ID und der gewünschte Status (on/off) durch ein Minus getrennt übergeben.
     * Die einzelnen Elemente werden durch ein Semikolon getrennt. Begonnen wird vor der ID immer mit einem Unterstrich.
     *
     * Unterstriche werden verwendet um das vorkommen einzelner IDs sauber zu prüfen. 
     * Diese werden in der show()-Methode wieder entfernt!
     *
     * Beispiel:
     *   _15-on;_22-on;_32-off; ...
     */
    function prepareShortcutSwitchLink($shortcutId) {
        // Zuerst alle Config-IDs, Dann alle Zimmer und zum Schluss die Etagen bearbeiten.
        // Durch die Methode addShortcutCommandItem($id, $status) wird gewährleistet dass jede ID nur einmal pro Vorgang geschaltet wird.
        $itemDb = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_shortcut_items', array("id", "shortcut_id", "config_id", "art_id",
            "zimmer_id", "etagen_id", "funkwahl", "on_off"), "", "",
            "config_id DESC , zimmer_id DESC , etagen_id DESC ", "shortcut_id=" . $shortcutId);

        foreach ($itemDb->ROWS as $itemRow) {
            $whereStmt = "";
            $onOff = $itemRow->getNamedAttribute("on_off");

            if (strlen($itemRow->getNamedAttribute("config_id")) > 0) {
                $this->addShortcutCommandItem($itemRow->getNamedAttribute("config_id"), $onOff);
            } else {

                if (strlen($itemRow->getNamedAttribute("art_id")) > 0) {
                    $whereStmt = $whereStmt . "control_art=" . $itemRow->getNamedAttribute("art_id");
                }

                if (strlen($itemRow->getNamedAttribute("zimmer_id")) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt = $whereStmt . " AND ";
                    }
                    $whereStmt = $whereStmt . "zimmer=" . $itemRow->getNamedAttribute("zimmer_id");
                }

                if (strlen($itemRow->getNamedAttribute("etagen_id")) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt = $whereStmt . " AND ";
                    }
                    $whereStmt = $whereStmt . "etage=" . $itemRow->getNamedAttribute("etagen_id");
                }

                $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
                    array("id", "funk_id", "funk_id2"), "", "", "", $whereStmt);

                foreach ($configDb->ROWS as $configRow) {
                    $this->addShortcutCommandItem($configRow->getNamedAttribute("id"), $onOff);
                }
            }
        }

    }


    /**
     * Shortcut-Sidebar anzeigen 
     * (Standard-Anzeige-Methode)
     *
     * Unterstriche werden rausgefiltert. Diese werden verwendet um das vorkommen einzelner IDs sauber zu prüfen.
     */
    function show() {
        if($this->LAYOUT_ART == $this->LAYOUT_ART_MOBILE){
            $this->showMobile();
            return;
        }
        if (isset($_REQUEST['switchShortcut']) && strlen($_REQUEST['switchShortcut']) >
            3) {
            $this->executeShortcutURL($_REQUEST['switchShortcut']);
        }
        
        $dvSc = new Div();
        $dvSc->setWidth("100%");

        $title = new Title("Shortcuts");

        $spc = new Line();

        $dvSc->add(new Spacer(20));
        $dvSc->add($title);
        $dvSc->add($spc);

        foreach ($this->SHORTCUTS_DB->ROWS as $shortcutRow) {
            $this->SHORTCUTS_URL_COMMAND = "/?switchShortcut=";
            $this->SHORTCUTS_TOOLTIP = "<table width='100%' cellspacing=0 cellpadding=0>";

            $this->prepareShortcutSwitchLink($shortcutRow->getNamedAttribute("id"));

            if ($this->SHORTCUTS_URL_COMMAND != "/?switchShortcut=") {
                // aktiver Link mit konfigurierten Items
                $this->SHORTCUTS_URL_COMMAND = str_replace("_", "", $this->
                    SHORTCUTS_URL_COMMAND);


                $this->SHORTCUTS_TOOLTIP .= "</table> <br><br><a href='" . $this->
                    SHORTCUTS_URL_COMMAND . "'>" .
                    "<center><div align='center' style='display:table-cell; padding:6px; width:100%;vertical-align:middle;background-color:green'>" .
                    "<font size='4' color='#deffde'><b>aktivieren</b></font>" . "</div></center>" .
                    "</a><br><br>";

                //$txtShortcut = new Text($shortcutRow->getNamedAttribute("name"), 3, true);
                //$txtShortcut->setTooltip($this->SHORTCUTS_TOOLTIP);

                $spn = new Span($shortcutRow->getNamedAttribute("name"), $shortcutRow->
                    getNamedAttribute("name"));
                $spn->add(new Text($this->SHORTCUTS_TOOLTIP, null, false, false, false, false));

                $dvSc->add($spn);
                $dvSc->add($spc);

            } else {
                // inaktiv (Keine konfigurierten Items)

                $txt = new Text($shortcutRow->getNamedAttribute("name"), 3, false);
                $txt->setTooltip("Noch keine Konfiguration hinterlegt");

                $dvSc->add($txt);
                $dvSc->add($spc);

            }
        }

        $dvSc->show();
    }

    function showMobile() {
        if (isset($_REQUEST['switchShortcut']) && strlen($_REQUEST['switchShortcut']) >
            3) {
            $this->executeShortcutURL($_REQUEST['switchShortcut']);
        }

        $dvSc = new Div();
        $dvSc->setWidth("100%");

        $title = new Title("Shortcuts",0,"10em");

        $spc = new Line();

        $dvSc->add(new Spacer(20));
        $dvSc->add($title);
        $dvSc->add($spc);

        foreach ($this->SHORTCUTS_DB->ROWS as $shortcutRow) {
            $this->SHORTCUTS_URL_COMMAND = "/?switchShortcut=";
            $this->SHORTCUTS_TOOLTIP = "<table width='100%' cellspacing=0 cellpadding=0>";

            $this->prepareShortcutSwitchLink($shortcutRow->getNamedAttribute("id"));

            if ($this->SHORTCUTS_URL_COMMAND != "/?switchShortcut=") {
                // aktiver Link mit konfigurierten Items
                $this->SHORTCUTS_URL_COMMAND = str_replace("_", "", $this->
                    SHORTCUTS_URL_COMMAND);


                $this->SHORTCUTS_TOOLTIP .= "</table><br> <br><a href='" . $this->SHORTCUTS_URL_COMMAND .
                    "' >" . "<center><div align='center' style='display:table-cell; padding:20px 30px;width:100%;vertical-align:middle;background-color:green'>" .
                    "<font size='8em' color='#deffde'><b>aktivieren</b></font>" . "</div></center>" .
                    "</a><br><br>";

                //$txtShortcut = new Text($shortcutRow->getNamedAttribute("name"), 3, true);
                //$txtShortcut->setTooltip($this->SHORTCUTS_TOOLTIP);

                $spn = new Span($shortcutRow->getNamedAttribute("name"), $shortcutRow->
                    getNamedAttribute("name"));
                $spn->add(new Text($this->SHORTCUTS_TOOLTIP, 6, false, false, false, false));
                $spn->setFontsize(8);
                $dvSc->add($spn);
                $dvSc->add($spc);

            } else {
                // inaktiv (Keine konfigurierten Items)

                $txt = new Text($shortcutRow->getNamedAttribute("name"), 6, false);
                $txt->setTooltip("Noch keine Konfiguration hinterlegt");

                $dvSc->add($txt);
                $dvSc->add($spc);

            }
        }

        $dvSc->show();
    }

    function executeShortcutURL($shortcutUrl) {
        switchShortcut("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'], $shortcutUrl);
    }

}

?>