<?PHP 


class HomeControlShortcut {
    private $ROW;
    private $ITEMS = array();
    
    private $ID=-1;
    private $NAME="";
    private $BESCHREIBUNG="";
    private $SHOW_SHORTCUT=false;
    
    function HomeControlShortcut($shortcutRow){
        $this->ROW           = $shortcutRow;

        $this->ID            = $this->ROW->getNamedAttribute("id");
        $this->NAME          = $this->ROW->getNamedAttribute("name");
        $this->BESCHREIBUNG  = $this->ROW->getNamedAttribute("beschreibung");
        $this->SHOW_SHORTCUT = $this->ROW->getNamedAttribute("show_shortcut");
    }
    
    function getId() {
        return $this->ID;
    }
    
    function getName() {
        return $this->NAME;
    }

    function getBeschreibung() {
        return $this->BESCHREIBUNG;
    }

    function isVisible() {
        return $this->SHOW_SHORTCUT;
    }




    /**
     * Liefert ein Array aller IDs 
     * die dem Cron zugeordnet sind.
     */
    function getItemRowsForShortcut() {
        $ret_item_rows = array();

        $sqlItems = "SELECT * FROM homecontrol_shortcut_items WHERE shortcut_id=" . $this->ID;
        $resultItems = $_SESSION['config']->DBCONNECT->executeQuery($sqlItems);

        while ($row = mysql_fetch_array($resultItems)) {
            // Wenn ConfigId angegeben ist, diese hinzuf?gen
            $whereStmt = "";
            if (strlen($row['config_id']) > 0) {
                $whereStmt .= "id=" . $row['config_id'];
            } else {
                // Ansonsten die entsprechenden Ger?te ausw?hlen und alle hinzuf?gen
                if (strlen($row['art_id']) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt .= " AND ";
                    }
                    $whereStmt .= "control_art=" . $row['art_id'];
                }

                if (strlen($row['zimmer_id']) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt .= " AND ";
                    }
                    $whereStmt .= "zimmer=" . $row['zimmer_id'];
                }

                if (strlen($row['etagen_id']) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt .= " AND ";
                    }
                    $whereStmt .= "etage=" . $row['etagen_id'];
                }
            }

            $itemDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_config", array("*"), "", "", "", $whereStmt);

            foreach ($itemDbTbl->ROWS as $rowItem) {
                $ret_item_rows[count($ret_item_rows)] = $rowItem;
            }
        }

        return $ret_item_rows;
    }


    /**
     *  Schaltet alle vollständig konfigurierten Geräte
     */
    function switchShortcut() {
        ob_end_flush();
        ob_implicit_flush();
        
        foreach ($this->getItemRowsForShortcut() as $itemRow) {
            $itm = new HomeControlItem($itemRow);
            echo "</br>Item: " . $itm->getName();

            $params = $itm->getAllParameter();
            $urlParams = "";

            $allParams=array();
            $allParamsSet = true;
            foreach ($params as $param) {
                $allParams[count($allParams)] = $param->getName();
                //echo "</br> Param: ".$allParams[count($allParams)-1];
                $optional = false;
                $fix = false;
                $default_logic = false;

                if ($param->isOptional()) {
                    $optional = true;
                    echo " optional ";
                }
                if ($param->isFix()) {
                    $fix = true;
                    echo " fix ";
                }
                if ($param->isDefaultLogic()) {
                    $default_logic = true;
                    echo " default_logic ";
                }

                // Hinterlegten Wert für Parameter zum entsprechenden Shortcut-Job ermitteln
                $prefix = "";
                if($default_logic){
                    $prefix = $itm->getParameterValueForShortcut($param->getRow(), $this->ID)==$itm->getDefaultLogicAnText()?"":"-";
                }
                
                $value = $fix?($prefix.$itm->getParameterValue($param->getRow())):$itm->getParameterValueForShortcut($param->getRow(), $this->ID);

                if (isset($value) && strlen($value) > 0) {
                    //echo " = Wert: " . $value . "</br>";
                    if (strlen($urlParams) > 0) {
                        $urlParams = $urlParams . "&";
                    }
                    $urlParams = $urlParams . $param->getName() . "=" .$value;
                } else {
                    if ($optional){
                        if($itm->isParameterOptionalActive($param->getId())) {
                            echo " FEHLENDER WERT FUER: ".$param->getName();
                            $allParamsSet = false;
                        }
                    }
                }
            }

            if ($allParamsSet) {
                $senderUrl = getArduinoUrlForDeviceId($itm->getId(), $_SESSION['config']->DBCONNECT);
                $useSenderUrl = strlen($senderUrl) > 0 ? $senderUrl : $arduinoUrl;
                $urlArray = parse_url($useSenderUrl);
                $host = $urlArray['host'];
                $check = @fsockopen($host, 80);

                echo " - Schalte Shortcut: " . $useSenderUrl . "?" . $urlParams;
                
                if ($check) {
                    try {
                        $retVal = file_get_contents($useSenderUrl . "?" . $urlParams);
                    }
                    catch (exception $e) {
                        echo "FEHLER BEIM SCHALTEN!";
                    }
                }
            }
            
        }
        ob_start();
    }


    
}




?>