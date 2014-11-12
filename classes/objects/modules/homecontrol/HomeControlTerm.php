<?PHP

class HomeControlTerm extends Object {
    private $TERM_ROW = null;
    private $ADDITIONAL = false;
    private $EDIT_MODE = false;

    /**
     * Konstruktor für die Darstellung einer Bedingung
     * 
     * @param $termRow:       Objekt vom Typ DbRow (Tabelle: homecontrol_term)
     * @param $additional:    Gibt an, ob "and" bzw "or" je nach Definition vor der Bedingung mit ausgegeben werden soll
     * @param $editMode:      Gibt an, ob die Verwaltungselemente mit eingeblendet werden sollen. 
     */
    function HomeControlTerm($termRow, $additional=false, $editMode=false, $homeControlId="") {
        $this->TERM_ROW    = $termRow;
        $this->ADDITIONAL  = $additional;
        $this->EDIT_MODE   = $editMode;
        $this->CONTROL_ID  = $homeControlId;
    }

    function getTriggerId() {
        return $this->TERM_ROW->getNamedAttribute('trigger_id');
    }

    function getTriggerSubid() {
        return $this->TERM_ROW->getNamedAttribute('trigger_subid');
    }

    function getTermType() {
        return $this->TERM_ROW->getNamedAttribute('term_type');
    }

    private function getCondition() {
        return $this->TERM_ROW->getNamedAttribute('termcondition');
    }

    private function getStatus() {
        return $this->TERM_ROW->getNamedAttribute('status');
    }

    private function getValue() {
        return $this->TERM_ROW->getNamedAttribute('value');
    }

    private function getStd() {
        return str_pad($this->TERM_ROW->getNamedAttribute('std'), 2, '0', STR_PAD_LEFT);
    }


    private function getMin() {
        return str_pad($this->TERM_ROW->getNamedAttribute('min'), 2, '0', STR_PAD_LEFT);
    }

    function isAdditional() {
        return $this->ADDITIONAL;
    }


    /**
     * Liefert die Beschreibung für die Bedingung
     * 
     * Wertet den Type aus und gibt die entsprechende 
     * Beschreibung zurück 
     */
    function getDescription() {
        $descr = "";
        switch ($this->getTermType()) {
            case 1:
                $descr = $this->getDescriptionForSensorWert();

                break;

            case 2:
                $descr = $this->getDescriptionForSensorStatus();

                break;

            case 3:
                $descr = $this->getDescriptionForTime();

                break;

            case 4:
                $descr = $this->getDescriptionForDay();

                break;

            default:
                $descr = $this->getDescriptionForSensorStatus();

                break;
        }

        return "" . $this->isAdditional() ? (($this->TERM_ROW->getNamedAttribute('and_or') ==
            "and" ? " und " : " oder ") . $descr) : $descr;
    }


    /**
     * Liefert die Beschreibung für eine Sensor-Wert-Bedingung
     * (Type 1) 
     */
    private function getDescriptionForSensorWert() {
        return "Sensor " . $this->getTriggerId() . " " . $this->getCondition() .
            " " . $this->getValue();
    }

    /**
     * Liefert die Beschreibung für eine Sensor-Status-Bedingung
     * (Type 2) 
     */
    private function getDescriptionForSensorStatus() {
        return "Sensor " . $this->getTriggerId() . ": " .($this->getStatus()=="J"?"Aktiv":"Inaktiv");
    }

    /**
     * Liefert die Beschreibung für eine Uhrzeit-Bedingung
     * (Type 3) 
     */
    private function getDescriptionForTime() {
        return "Uhrzeit: " . $this->getCondition() . " " . $this->getStd() . ":" . $this->
            getMin();
    }

    /**
     * Liefert die Beschreibung für eine Wochentag-Bedingung
     * (Type 4) 
     */
    private function getDescriptionForDay() {
        $ret = "Tage: ";

        $ret .= $this->TERM_ROW->getNamedAttribute('montag') == "J" ? " Mo," : "";
        $ret .= $this->TERM_ROW->getNamedAttribute('dienstag') == "J" ? " Di," : "";
        $ret .= $this->TERM_ROW->getNamedAttribute('mittwoch') == "J" ? " Mi," : "";
        $ret .= $this->TERM_ROW->getNamedAttribute('donnerstag') == "J" ? " Do," : "";
        $ret .= $this->TERM_ROW->getNamedAttribute('freitag') == "J" ? " Fr," : "";
        $ret .= $this->TERM_ROW->getNamedAttribute('samstag') == "J" ? " Sa," : "";
        $ret .= $this->TERM_ROW->getNamedAttribute('sonntag') == "J" ? " So," : "";

        return substr($ret, 0, strlen($ret) - 1);
    }


    /**
     * Liefert den Link zum bearbeiten der Bedingung zurück
     */
    private function getEditLink() {
        $href = "?editTerm=" . $this->TERM_ROW->getNamedAttribute("id");
        $l = new Link($href, new Text("bearbeiten", 2));

        return $l;
    }

    /**
     * Liefert den Link zum löschen der Bedingung zurück
     */
    private function getDeleteLink() {
        $href = "?deleteTerm=" . $this->TERM_ROW->getNamedAttribute("id");
        $l = new Link($href, new Text("entfernen", 2));

        return $l;
    }


    /**
     * Prüft, ob die Bedingung bearbeitet oder gelöscht werden soll.
     * 
     * liefert falls notwendig ein alternativ anzuzeigendes Objekt zurück. 
     */
    private function checkEditTerm() {
        $ret = null;
        
        if ( isset($_REQUEST['deleteTerm']) && $_REQUEST['deleteTerm'] == $this->TERM_ROW->getNamedAttribute("id")) {
            $ret = $this->getDeleteMask();
        }
        
        if (isset($_REQUEST['editTerm']) && $_REQUEST['editTerm'] == $this->TERM_ROW->getNamedAttribute("id")) {
            $ret = $this->getEditMask();
        }
        
        return $ret;
    }

    /**
     *  
     */
    private function checkDeleteTerm(){
        if (isset($_REQUEST['deleteOk']) && $_REQUEST['deleteOk'] == "ok" && $_REQUEST['deleteTerm'] == $this->TERM_ROW->getNamedAttribute("id")) {
            return true;
        }
        return false;
    }

    /**
     * liefert die Anzeige zur Bestätigung des Löschvorgangs 
     */
    private function getDeleteMask(){
        $t = new Table(array("", "", "", ""));
        $t->setColSizes(array(null, 120, 40, 40));

        $delUrl = "?deleteTerm=".$this->TERM_ROW->getNamedAttribute("id") ."&deleteOk=ok";

        $r = $t->createRow();
        $r->setAttribute(0, $this->getDescription());
        $r->setAttribute(1, "Wirklich entfernen?");
        $r->setAttribute(2, new Link($delUrl, "Ja"));
        $r->setAttribute(3, new Link("", "Nein"));
        $t->addRow($r);

        return $t;

    }


    /**
     * liefert die Anzeige zur Bearbeitung der Bedingung
     */
    private function getEditMask(){
        $t = new Table(array("", ""));

        $editor = new HomeControlTermEditor($this->TERM_ROW, "editTerm=".$this->TERM_ROW->getNamedAttribute("id"));
        $r = $t->createRow();
        $r->setAttribute(0, new Text($this->getDescription(), 2));
        $r->setAttribute(1, $editor);
        $t->addRow($r);

        return $t;
    }


    /**
     * Liefert die Bedingung als Table zurück 
     * incl. Bearbeiten und Löschen Links 
     */
    private function getEditableTermItem() {
        $t = new Table(array("name", "edit", "delete"));
        $t->setWidth("99%");
        $t->setColSizes(array(null, 65, 65));
        $t->setAlignments(array("left", "left", "right"));

        $r = $t->createRow();
        $r->setAttribute(0, $this->getDescription());
        $r->setAttribute(1, $this->getEditLink());
        $r->setAttribute(2, $this->getDeleteLink());
        $t->addRow($r);

        return $t;
    }


    /**
     * Liefert die Bedingung als Table zurück 
     * reine Anzeige 
     */
    private function getReadonlyTermItem() {
        $t = new Table(array(""));

        $r = $t->createRow();
        $r->setAttribute(0, $this->getDescription());
        $t->addRow($r);

        return $t;
    }


    /**
     * Standard Anzeige
     */
    function show() {
        $t = new Text("");
        
        if ($this->EDIT_MODE) {
        
            if($this->checkDeleteTerm()){
                $delSql = "DELETE FROM homecontrol_term WHERE id = ".$_REQUEST['deleteTerm'];
                $_SESSION['config']->DBCONNECT->executeQuery($delSql);
            } else {
                $editItem = $this->checkEditTerm();
                if($editItem != null){
                    $t = $editItem;
                } else {
                    $t = $this->getEditableTermItem();
                }
            } 
        
        } else {
            $t = $this->getReadonlyTermItem();
        }

        $t->show();
    }

}

?>