<?PHP

class ShortcutSidebar extends Object {
    public $LAYOUT_ART_DESKTOP = "DESKTOP";
    public $LAYOUT_ART_MOBILE = "MOBILE";

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
            "Id, Name, Beschreibung", "", "name", "show_shortcut='J'");

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
            $this->addShortcutCommandItem($itemRow);
        }

    }


    /**
     * Alle Config-IDs zu Objekten dieser Art in die URL übernehmen
     */
    function addArtShortcutCommandItems($artId, $onOff) {

        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "funk_id", "funk_id2"), "", "", "", "control_art=" . $artId);

        foreach ($configDb->ROWS as $itemRow) {
            $this->addShortcutCommandItem($itemRow);
        }

    }


    /**
     * Alle Config-IDs der Etage in die URL übernehmen
     */
    function addEtagenShortcutCommandItems($etagenId, $onOff) {
        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("id", "funk_id", "funk_id2"), "", "", "", "etage=" . $etagenId);

        foreach ($configDb->ROWS as $itemRow) {
            $this->addShortcutCommandItem($itemRow);
        }
    }


    function getShortcutImageString($configId, $width = 50) {
        $ret = "";
    
        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_config',
            array("*"), "", "", "", "id=" . $configId);

        if ($configDb->getRow(1) != null) {
            $itm = new HomeControlItem($configDb->getRow(1), false);
            $ret = "<img src='" . $itm->getPic() . "' width='" . $width . "'>";
        }
    
        return $ret;
    }


    /**
     * Wenn ID nicht schon enthalten ist, Einstellungs-Werte übernehmen
     */
    function addShortcutCommandItem($shortcutViewRow) {
        $id = $shortcutViewRow->getNamedAttribute("config_id");
        $name = $shortcutViewRow->getNamedAttribute("name");
        $funkId = $shortcutViewRow->getNamedAttribute("funk_id");
        $status = $shortcutViewRow->getNamedAttribute("on_off");
        $picLink = $shortcutViewRow->getNamedAttribute("pic");
        $width = 20;
        
        if (!strpos($this->SHORTCUTS_URL_COMMAND, "_" . $funkId . "-") && strlen($funkId) >
            0 && strlen($status) > 1) {
            $this->SHORTCUTS_URL_COMMAND .= "_" . $funkId . "-" . $status . ";";

            if ($this->SHORTCUTS_ROW_COLOR_LAST == $this->SHORTCUTS_ROW_COLOR1) {
                $this->SHORTCUTS_ROW_COLOR_LAST = $this->SHORTCUTS_ROW_COLOR2;
            } else {
                $this->SHORTCUTS_ROW_COLOR_LAST = $this->SHORTCUTS_ROW_COLOR1;
            }

            if ($this->LAYOUT_ART == $this->LAYOUT_ART_MOBILE) {
                $this->SHORTCUTS_TOOLTIP .= "<tr style=\"background-color:" . $this->
                    SHORTCUTS_ROW_COLOR_LAST . ";\"><td style=\"vertical-align: middle;\">".
                    "<img src='" . $picLink . "' width='" . $width . "'>" .
                    "</td><td style=\"vertical-align: middle;\">" . "<font size='6em'>" . $name .
                    "</font></td><td style=\"vertical-align: middle;\"><font size='6em'>" . ($status == "on" ? $this->ON_LABEL : $this->
                    OFF_LABEL) . "</font></td></tr>";
            } else {
                $this->SHORTCUTS_TOOLTIP .= "<tr  style=\"height:22px;background-color:" . $this->
                    SHORTCUTS_ROW_COLOR_LAST . ";\"><td style=\"vertical-align: middle;padding-right:2px;\">" .
                    "<img src='" . $picLink . "' width='" . $width . "'>" .
                    "</td><td width='70' style=\"vertical-align: middle;white-space: pre-wrap;word-wrap:break-word;\">" . "<p style=\"font-size:10px;width=50px;white-space: pre-wrap;word-wrap:break-word;\">" . $name .
                    "</p></td><td style=\"vertical-align: middle;\"><font size='2'>" . ($status == "on" ? $this->ON_LABEL : $this->
                    OFF_LABEL) . "</font></td></tr>";

            }
            
        }
    }


    /**
     * Erzeugt die benötigte URL und den Tooltip, mit der die Kombination 
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

        $configDb = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_shortcutview',
        array("*"), "", "", "", "shortcut_id=".$shortcutId);

        foreach ($configDb->ROWS as $configRow) {
            $onOff = $configRow->getNamedAttribute("on_off");
            $this->addShortcutCommandItem($configRow);
        }
    }
    
    /**
     * Shortcut-Sidebar anzeigen 
     * (Standard-Anzeige-Methode)
     *
     * Unterstriche werden rausgefiltert. Diese werden verwendet um das vorkommen einzelner IDs sauber zu prüfen.
     */
    function show() {
        if ($this->LAYOUT_ART == $this->LAYOUT_ART_MOBILE) {
            $this->showMobile();
            return;
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
            $this->SHORTCUTS_TOOLTIP = "<table width='120' cellspacing=0 cellpadding=0>";

            $this->prepareShortcutSwitchLink($shortcutRow->getNamedAttribute("id"));

            if ($this->SHORTCUTS_URL_COMMAND != "/?switchShortcut=") {
                // aktiver Link mit konfigurierten Items
                $this->SHORTCUTS_URL_COMMAND = str_replace("_", "", $this->
                    SHORTCUTS_URL_COMMAND);


                $this->SHORTCUTS_TOOLTIP .= "</table> ";

                //$txtShortcut = new Text($shortcutRow->getNamedAttribute("name"), 3, true);
                //$txtShortcut->setTooltip($this->SHORTCUTS_TOOLTIP);

                $spn = new Span($shortcutRow->getNamedAttribute("name"), $shortcutRow->
                    getNamedAttribute("name"));
                $spn->add(new Text($this->SHORTCUTS_TOOLTIP, null, false, false, false, false));

                $dvSc->add($spn);
                $dvSc->add(new Text("<a href='" . $this->
                    SHORTCUTS_URL_COMMAND . "'>" .
                    "<center><div align='center' style='display:table-cell; padding:2px 25px; width:100%;vertical-align:middle;background-color:green'>" .
                    "<font size='3' color='#deffde'><b>aktivieren</b></font>" . "</div></center>" .
                    "</a>", 2, false, false, false, false));
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
        $dvSc = new Div();
        $dvSc->setWidth("100%");

        $title = new Title("Shortcuts", 0, "10em");

        $spc = new Line();

        $dvSc->add(new Spacer(20));
        $dvSc->add($title);
        $dvSc->add($spc);

        foreach ($this->SHORTCUTS_DB->ROWS as $shortcutRow) {
            $this->SHORTCUTS_URL_COMMAND = "/?switchShortcut=";
            $this->SHORTCUTS_TOOLTIP = "<table width='120' cellspacing=0 cellpadding=0>";

            $this->prepareShortcutSwitchLink($shortcutRow->getNamedAttribute("id"));

            if ($this->SHORTCUTS_URL_COMMAND != "/?switchShortcut=") {
                // aktiver Link mit konfigurierten Items
                $this->SHORTCUTS_URL_COMMAND = str_replace("_", "", $this->
                    SHORTCUTS_URL_COMMAND);


                $this->SHORTCUTS_TOOLTIP .= "</table> ";

                //$txtShortcut = new Text($shortcutRow->getNamedAttribute("name"), 3, true);
                //$txtShortcut->setTooltip($this->SHORTCUTS_TOOLTIP);

                $spn = new Span($shortcutRow->getNamedAttribute("name"), $shortcutRow->
                    getNamedAttribute("name"));
                $spn->setFontsize("12em");
                $spn->add(new Text($this->SHORTCUTS_TOOLTIP, null, false, false, false, false));

                $dvSc->add($spn);
                $dvSc->add(new Text("<a href='" . $this->
                    SHORTCUTS_URL_COMMAND . "'>" .
                    "<center><div align='center' style='display:table-cell; padding:2px 25px; width:100%;vertical-align:middle;background-color:green'>" .
                    "<font size='12em' color='#deffde'><b>aktivieren</b></font>" . "</div></center>" .
                    "</a>", 2, false, false, false, false));
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

}

?>