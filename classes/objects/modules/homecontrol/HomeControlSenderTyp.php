<?PHP

class HomeControlSenderTyp {
    private $ID;
    private $NAME;

    private $PARAMETER_DBTABLE;
    private $PARAMETER_DBTABLE_CONTROL;

    private $HAS_DEFAULT_PARAM = false;
    private $DEFAULT_PARAM_NAME = null;

    function HomeControlSenderTyp($senderTypRow) {
        $this->ID = $senderTypRow->getNamedAttribute("id");
        $this->NAME = $senderTypRow->getNamedAttribute("name");

        $this->PARAMETER_DBTABLE = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter", array('*'), "", "", "", "senderTypId=" .
            $this->ID . " AND fix='J'");
        $this->PARAMETER_DBTABLE_CONTROL = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter", array('*'), "", "", "",
            "senderTypId=" . $this->ID . " AND fix='N'");

        $this->checkDefaultParam();
    }

    function hasDefaultParam() {
        return $this->HAS_DEFAULT_PARAM;
    }

    /**
     * prüft, ob Parameter für die 
     * Standard An/Aus-Logik vorhanden ist. 
     */
    function checkDefaultParam() {
        $rows = $this->PARAMETER_DBTABLE->ROWS;
        $this->HAS_DEFAULT_PARAM = false;

        foreach ($rows as $row) {
            if ($row->getNamedAttribute("default_logic") == "J") {
                $this->HAS_DEFAULT_PARAM = true;
            }
        }
    }


    function getParameterFixDbTbl() {
        return $this->PARAMETER_DBTABLE;
    }

    function getParameterControlDbTbl() {
        return $this->PARAMETER_DBTABLE_CONTROL;
    }

    /**
     * liefert die gesamte Maske zum Einstellen der Werte 
     * für die Steuerung zurück
     */
    function getParameterControlMask($configItem) {
        $formName = "ControlForm".$configItem->getId();
        $frm = new Form("", "", "", $formName);
        $formEmpty = true;
        
        $obj = $this->getAlternativeParameterEditorObject($configItem, $formName);

        if ($obj != null && method_exists($obj, "show")) {
            $frm->add($obj);
            return $frm;
        } else {
            $frm->add(new Hiddenfield("switchConfigId", $configItem->getId()));
            $tbl = new Table(array("", ""));
            $tbl->setAlignments(array("left", "left"));
            $tbl->setColSizes(array("120"));
            
            $rows = $this->PARAMETER_DBTABLE_CONTROL->ROWS;
            foreach ($rows as $row) {
                if ($row->getNamedAttribute("optional") != "J" || $configItem->isParameterOptionalActive($row->getNamedAttribute("id"))) {
                    $r = $tbl->createRow();
                    $r->setBackgroundColor("#ffffff");
                    $r->setAttribute(0, $row->getNamedAttribute("name"));
                    $r->setAttribute(1, $this->getEditParameterValueObject($row, $this->getParameterValue($row, $configItem->getRow())));
                    $tbl->addRow($r);
                    $formEmpty = false;
                }
            }

            if (!$this->hasDefaultParam()) {
                $r = $tbl->createRow();
                $r->setBackgroundColor("#ffffff");
                $r->setSpawnAll(true);
                $r->setAttribute(0, new Button("sendParamsToSender", "OK"));
                $r->setAlign("center");
                $tbl->addRow($r);
                $formEmpty = false;
            } else {
                $r = $tbl->createRow();
                $r->setBackgroundColor("#ffffff");
                $r->setSpawnAll(true);
                $r->setAttribute(0, $this->getDefaultLogicSwitchButtons($configItem));
                $r->setAlign("center");
                $tbl->addRow($r);
                $formEmpty = false;
            }

            $frm->add($tbl);

            $rowsFix = $this->PARAMETER_DBTABLE->ROWS;
            foreach ($rowsFix as $row) {
                if ($row->getNamedAttribute("default_logic") != "J") {
                    $frm->add(new Hiddenfield($row->getNamedAttribute("name"), $this->getParameterValue($row, $configItem->getRow())));
                }
            }

            return !$formEmpty ? $frm : new Div();
        }

        return new Div();
    }



    /**
     * liefert die gesamte Maske zum bearbeiten der Parameter zurück
     * incl. anlegen, bearbeiten und löschen
     */
    function getParameterEditMask($configItem) {
        $ttl = new Title("Parameter");
        $txt = new Text("Parameter sind abh&auml;ngig vom gew&auml;hlten Sender");
        $txt->setFilter(false);

        $dv = new Div();
        $dv->add($ttl);
        $dv->add($txt);

        if (count($this->PARAMETER_DBTABLE_CONTROL->ROWS) + count($this->PARAMETER_DBTABLE->ROWS) > 0) {
            $tbl = new Table(array("", ""));
            $tbl->addSpacer(0, 10);

            $rows = $this->PARAMETER_DBTABLE->ROWS;
            foreach ($rows as $row) {
                $r = $tbl->createRow();
                $r->setAttribute(0, $row->getNamedAttribute("name"));
                $r->setAttribute(1, $this->getEditParameterValueObject($row, $this->getParameterValue($row, $configItem->getRow())));
                $tbl->addRow($r);
            }

            $rows = $this->PARAMETER_DBTABLE_CONTROL->ROWS;
            foreach ($rows as $row) {
                if ($row->getNamedAttribute("optional") == "J") {
                    $r = $tbl->createRow();
                    $r->setAttribute(0, $row->getNamedAttribute("name") . " aktiv?");
                    $r->setAttribute(1, new Checkbox($row->getNamedAttribute("name") . "Optional", "", "J", $configItem->isParameterOptionalActive($row->
                        getNamedAttribute("id"))));
                    $tbl->addRow($r);
                }
            }

            $dv->add($tbl);
        }

        return $dv;
    }



    function getDefaultLogicSwitchButtons($configItem) {
        if ($this->hasDefaultParam()) {
            $tbl = new Table(array("", ""));
            $tbl->setColSizes(array("120"));
            $rows = $this->PARAMETER_DBTABLE->ROWS;
            foreach ($rows as $row) {
                if ($row->getNamedAttribute("default_logic") == "J") {
                    $tAn="";
                    $tAus="";
                    switch ($configItem->getArt()) {
                            //case 1: // Steckdosen
                            //    break;
                        case 2: // Jalousien
                            $tAn = "AUF";
                            $tAus = "ZU";
                            break;
                            //case 3: // Glühbirne
                            //    break;
                        case 4: // Heizung
                            $tAn = "WARM";
                            $tAus = "KALT";
                            break;
                        default:
                            $tAn = "AN";
                            $tAus = "AUS";
                    }

                    $hddnAn = new HiddenField($row->getNamedAttribute("name") . $tAn, $this->getParameterValue($row, $configItem->CONFIG_ROW));
                    $hddnAus = new HiddenField($row->getNamedAttribute("name") . $tAus, "-" . $this->getParameterValue($row, $configItem->CONFIG_ROW));

                    $lnkAn = new Button($row->getNamedAttribute("name"), $tAn);
                    $lnkAus = new Button($row->getNamedAttribute("name"), $tAus);

                    $r = $tbl->createRow();
                    $r->setAttribute(0, $lnkAn);
                    $r->setAttribute(1, $lnkAus);
                    $tbl->addRow($r);

                    $r1 = $tbl->createRow();
                    $r1->setAttribute(0, $hddnAn);
                    $r1->setAttribute(1, $hddnAus);
                    $tbl->addRow($r1);
                }
            }
            return $tbl;
        }
    }



    /**
     * prüft ob zu den Parametern mindestens ein alternativer Editor 
     * vorhanden ist und gibt diesen ggf zurueck. 
     * Ansonsten wird null zurueckgeliefert. 
     * 
     * Alle nicht zu einem alternativen Editor zugeordneten Parameter
     * werden als Standard-Parameter-Control in der Form integriert.
     */
    function getAlternativeParameterEditorObject($configItem, $formName) {
        $alternative = false;
        $editorTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_editoren", array("*"), "", "", "", "id IN (SELECT editor_id FROM homecontrol_control_editor_zuordnung WHERE config_id=".$configItem->getId().")");
        $dv = new Div();
        $dv->setAlign("center");
        $dv->setOverflow("visible");
        
        foreach ($editorTbl->ROWS as $editorRow) {
            $classname = $editorRow->getNamedAttribute("classname");
            
//          if($configItem->isEditorActivated($editorRow)){
            $editor = new $classname($editorRow, $configItem, $formName);

            if ($editor->isActivated()) {
                $dv->add($editor->getEditMask());
                $alternative = true;
            }
//          }
        }
        
        $freeParamArray = $configItem->getFreeParamArray();
        foreach($freeParamArray as $freeParam){
            $obj = $this->getEditParameterValueObject($freeParam->getRow(), $configItem->getParameterValue($freeParam->getRow()), $prefix="", $default="");
            $dv->add($obj);
        }
        $dv->add(new Button("Ok", "Ok"));
        return $alternative ? $dv : null;
    }


    function getSenderParameterIdByName($name){
        foreach($this->PARAMETER_DBTABLE->ROWS as $paramRow){
            if($paramRow->getNamedAttribute("name")==$name){
                return $paramRow->getNamedAttribute("id");
            }
        }
        
        foreach($this->PARAMETER_DBTABLE_CONTROL->ROWS as $paramRow){
            if($paramRow->getNamedAttribute("name")==$name){
                return $paramRow->getNamedAttribute("id");
            }
        }
        return null;
    }


    function doParameterUpdate($configRow) {
        $configItem = new HomeControlItem($configRow);

        $rows = $this->PARAMETER_DBTABLE->ROWS;
        $ids = "";
        $names = array();
        foreach ($rows as $row) {
            if (strlen($ids) > 0) {
                $ids .= ",";
            }
            $ids .= $row->getNamedAttribute('rowid');
            $names[$row->getNamedAttribute('rowid')] = $row->getNamedAttribute('name');
        }

        if (strlen($ids) > 0) {
            $pDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter", array('*'), "", "", "",
                "senderTypId=(SELECT senderTypId FROM homecontrol_sender WHERE id=" . $configRow->getNamedAttribute("sender_id") . ")");
            $paramRows = $pDbTbl->ROWS;

            foreach ($paramRows as $paramRow) {
                $paramName = $paramRow->getNamedAttribute("name");
                $paramDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_parameter_values", array('*'), "", "", "", "config_id=" . $configRow->
                    getNamedAttribute("rowid") . " AND param_id=" . $paramRow->getNamedAttribute("id") . " ");

                $valueRow = null;
                //              $paramName = $names[$valueRow->getNamedAttribute("param_id")];
                if (isset($_REQUEST[$paramName])) {
                    if ($paramDbTbl->getRow(1) == null) {
                        $valueRow = $paramDbTbl->createRow();
                        $valueRow->setNamedAttribute("config_id", $configRow->getNamedAttribute("rowid"));
                        $valueRow->setNamedAttribute("param_id", $paramRow->getNamedAttribute("rowid"));
                        $valueRow->setNamedAttribute("value", $_REQUEST[$paramName]);
                        $valueRow->insertIntoDB();
                    } else {
                        $valueRow = $paramDbTbl->getRow(1);
                        $valueRow->setNamedAttribute("value", $_REQUEST[$paramName]);
                        $valueRow->updateDB();
                    }


                    $rows = $this->PARAMETER_DBTABLE_CONTROL->ROWS;
                    $ids = "";
                    $names = array();
                    foreach ($rows as $row) {
                        if (strlen($ids) > 0) {
                            $ids .= ",";
                        }
                        $ids .= $row->getNamedAttribute('rowid');
                        $names[$row->getNamedAttribute('rowid')] = $row->getNamedAttribute('name');
                        $paramName = $names[$row->getNamedAttribute('rowid')];

                        if ($row->getNamedAttribute('optional') == "J") {
                            $sql = "SELECT 'X' FROM homecontrol_sender_typen_parameter_optional p " . "WHERE param_id = " . $row->getNamedAttribute('rowid') . " AND config_id=" .
                                $configItem->getId() . "";
                            $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);

                            if (isset($_REQUEST[$paramName . "Optional"]) && $_REQUEST[$paramName . "Optional"] == "J") {

                                if (mysql_numrows($rslt) == 0) {
                                    $sql = "INSERT INTO homecontrol_sender_typen_parameter_optional( config_id, param_id, active) VALUES ( " . $configItem->getId() . ", " . $row->
                                        getNamedAttribute('rowid') . ", 'J' ) ";
                                    $_SESSION['config']->DBCONNECT->executeQuery($sql);
                                } else {
                                    if (!$configItem->isParameterOptionalActive($valueRow->getNamedAttribute("param_id"))) {
                                        $sql = "UPDATE homecontrol_sender_typen_parameter_optional SET active='J' WHERE config_id=" . $configItem->getId() . " AND param_id=" . $row->
                                            getNamedAttribute('rowid');
                                        $_SESSION['config']->DBCONNECT->executeQuery($sql);
                                    }
                                }

                            } else {

                                if (mysql_numrows($rslt) == 0) {
                                    $sql = "INSERT INTO homecontrol_sender_typen_parameter_optional( config_id, param_id, active) VALUES ( " . $configItem->getId() . ", " . $row->
                                        getNamedAttribute('rowid') . ", 'N' ) ";
                                    $_SESSION['config']->DBCONNECT->executeQuery($sql);
                                } else {
                                    if ($configItem->isParameterOptionalActive($row->getNamedAttribute("rowid"))) {
                                        $sql = "UPDATE homecontrol_sender_typen_parameter_optional SET active='N' WHERE config_id=" . $configItem->getId() . " AND param_id=" . $row->
                                            getNamedAttribute('rowid');
                                        $_SESSION['config']->DBCONNECT->executeQuery($sql);
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        refreshEchoDb($_SESSION['config']->DBCONNECT);        
    }


    /**
     * Holt den Parameter-Wert des übergebenen Parameters zum übergebenen zu schaltenden Objekt.
     */
    function getParameterValue($paramRow, $configRow) {
        return getDbValue("homecontrol_sender_parameter_values", "value", "config_id=" . $configRow->getNamedAttribute("rowid") . " AND param_id=" . $paramRow->
            getNamedAttribute("rowid"));
    }

    /**
     * Liefert den Wert des Parameters
     * zur Item/Cron Kombination 
     */
    function getParameterValueForCron($paramRow, $configRow, $cronId) {
        return getDbValue("homecontrol_cron_parameter_values", "value", "config_id=" . $configRow->getNamedAttribute("rowid") . " AND param_id=" . $paramRow->
            getNamedAttribute("rowid") . " AND cron_id=" . $cronId);
    }

    /**
     * Liefert den Wert des Parameters
     * zur Item/Cron Kombination 
     */
    function getParameterValueForShortcut($paramRow, $configRow, $shortcutId) {
        return getDbValue("homecontrol_shortcut_parameter_values", "value", "config_id=" . $configRow->getNamedAttribute("rowid") . " AND param_id=" . $paramRow->
            getNamedAttribute("rowid") . " AND shortcut_id=" . $shortcutId);
    }


    /**
     * Setzt den Parameter-Wert des übergebenen Parameters zum übergebenen zu schaltenden Objekt.
     */
    function setParameterValue($paramRow, $configRow, $value) {
        $paramDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_parameter_values", array('*'), "", "", "", "config_id=" . $configRow->
            getNamedAttribute("rowid") . " AND param_id=" . $paramRow->getNamedAttribute("rowid"));

        $r = $paramDbTbl->getRow(1);

        if ($r == null) {
            $r = $paramDbTbl->createRow();
            $r->setNamedAttribute("config_id", $configRow->getNamedAttribute("id"));
            $r->setNamedAttribute("param_id", $paramRow->getNamedAttribute("id"));
            $r->insertIntoDB();
            
            $paramDbTbl->refresh();
            $r = $paramDbTbl->getRow(1);
        }

        $r->setNamedAttribute("value", $value);
        $r->updateDB();
    }


    /**
     * Setzt den Parameter-Wert des übergebenen Parameters zum übergebenen zu schaltenden Objekt und Cron.
     */
    function setParameterValueForCron($paramRow, $configRow, $cronId, $value) {
        $paramDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_cron_parameter_values", array('*'), "", "", "", "config_id=" . $configRow->
                                    getNamedAttribute("rowid") . " AND param_id=" . $paramRow->getNamedAttribute("rowid") ." AND cron_id=".$cronId);
        $r = $paramDbTbl->getRow(1);

        if ($r == null) {
            $r = $paramDbTbl->createRow();
            $r->setNamedAttribute("config_id", $configRow->getNamedAttribute("id"));
            $r->setNamedAttribute("param_id", $paramRow->getNamedAttribute("id"));
            $r->setNamedAttribute("cron_id", $cronId);
            $r->setNamedAttribute("value", $value);
            $r->insertIntoDB();
        } else {
            $r->setNamedAttribute("value", $value);
            $r->updateDB();
        }
    }


    /**
     * Setzt den Parameter-Wert des übergebenen Parameters zum übergebenen zu schaltenden Objekt und Cron.
     */
    function setParameterValueForShortcut($paramRow, $configRow, $shortcutId, $value) {
        $paramDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_shortcut_parameter_values", array('*'), "", "", "", "config_id=" . $configRow->
                                    getNamedAttribute("rowid") . " AND param_id=" . $paramRow->getNamedAttribute("rowid") ." AND shortcut_id=".$shortcutId);
        $r = $paramDbTbl->getRow(1);

        if ($r == null) {
            $r = $paramDbTbl->createRow();
            $r->setNamedAttribute("config_id", $configRow->getNamedAttribute("id"));
            $r->setNamedAttribute("param_id", $paramRow->getNamedAttribute("id"));
            $r->setNamedAttribute("shortcut_id", $shortcutId);
            $r->setNamedAttribute("value", $value);
            $r->insertIntoDB();
        } else {
            $r->setNamedAttribute("value", $value);
            $r->updateDB();
        }
    }


    /**
     * liefert entsprechend des Parameter-Typs das entsprechende 
     * Objekt zum bearbeiten des Wertes zurück. 
     * z.B. Combobox oder Textfeld.  
     */
    function getEditParameterValueObject($parameterRow, $value, $prefix="", $default="") {
        $obj = new Textfield($prefix.$parameterRow->getNamedAttribute("name"), $default!=""?$default:$value, 20);

        $artDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter_arten", array('*'), "", "", "", "id=" . $parameterRow->
            getNamedAttribute("parameterArtId"));
        $artRow = $artDbTbl->getRow(1);

        if ($artRow != null && $parameterRow != null) {
            $von = $artRow->getNamedAttribute("von");
            $bis = $artRow->getNamedAttribute("bis");

            $minLen = $artRow->getNamedAttribute("minLen");
            $maxLen = $artRow->getNamedAttribute("maxLen");

            $defaultValueTag = $artRow->getNamedAttribute("defaultValueTag");

            $optional = $parameterRow->getNamedAttribute("optional") == "J";

            if (isset($defaultValueTag) && strlen($defaultValueTag) > 0) {
                $obj = new Combobox($prefix.$parameterRow->getNamedAttribute("name"), getDefaultComboArray($defaultValueTag), $default!=""?$default:($optional ? "" : $value), " ");
            } else if (isset($von) && isset($bis) && strlen($von) > 0 && strlen($bis) > 0) {
                $obj = new Combobox($prefix.$parameterRow->getNamedAttribute("name"), getNumberComboArray($von, $bis), $default!=""?$default:($optional?"":$value), " ");
            } else if (isset($minLen) && isset($maxLen) && strlen($minLen) > 0 && strlen($maxLen) > 0) {
                $obj = new Textfield($prefix.$parameterRow->getNamedAttribute("name"), $default!=""?$default:($optional?"":$value), 20, $maxLen);
            }
        }

        return $obj;
    }


    function getPossibleParameterValues($parameterRow) {
        $obj = array();

        $artDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter_arten", array('*'), "", "", "", "id=" .$parameterRow->getNamedAttribute("parameterArtId"));
        $artRow = $artDbTbl->getRow(1);

        if ($artRow != null && $parameterRow != null) {
            $von = $artRow->getNamedAttribute("von");
            $bis = $artRow->getNamedAttribute("bis");

            $minLen = $artRow->getNamedAttribute("minLen");
            $maxLen = $artRow->getNamedAttribute("maxLen");

            $defaultValueTag = $artRow->getNamedAttribute("defaultValueTag");

            $optional = $parameterRow->getNamedAttribute("optional") == "J";

            if (isset($defaultValueTag) && strlen($defaultValueTag) > 0) {
                $obj = getDefaultComboArray($defaultValueTag);
            } else if (isset($von) && isset($bis) && strlen($von) > 0 && strlen($bis) > 0) {
                $obj = getNumberComboArray($von, $bis);
            } 
        }

        return $obj;
    }
}

?>