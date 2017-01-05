<?PHP

class HomeControlCron {
    private $CRON_ROW;
    private $ID;
    private $NAME;
    private $BESCHREIBUNG;

    private $STUNDE;
    private $MINUTE;

    private $WEEKDAYS = array(0 => "sonntag", 1 => "montag", 2 => "dienstag", 3 => "mittwoch", 4 => "donnerstag", 5 => "freitag", 6 => "samstag");

    private $SHORTWEEKDAYS = array(0 => "So.", 1 => "Mo.", 2 => "Di.", 3 => "Mi.", 4 => "Do.", 5 => "Fr.", 6 => "Sa.");

    function HomeControlCron($homeControlCronDbRow) {
        $this->CRON_ROW = $homeControlCronDbRow;

        $this->ID = $this->CRON_ROW->getNamedAttribute("id");
        $this->NAME = $this->CRON_ROW->getNamedAttribute("name");
        $this->BESCHREIBUNG = $this->CRON_ROW->getNamedAttribute("beschreibung");

        $this->STUNDE = $this->CRON_ROW->getNamedAttribute("stunde");
        $this->MINUTE = $this->CRON_ROW->getNamedAttribute("minute");
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


    function getStunde() {
        return $this->STUNDE;
    }


    function getMinute() {
        return $this->MINUTE;
    }


    function getPauseLink() {
        $loginNeed = getPageConfigParam($_SESSION['config']->DBCONNECT, "loginForTimelinePauseNeed") == "J";
        $loggedIn = ($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->CURRENTUSER->STATUS == "user");

        if ($this->isNextExecutionCron() && (($loginNeed && $loggedIn) || !$loginNeed)) {
            if ($this->isCronPaused()) {
                return $this->getPauseDeactivationLink();
            } else {
                return $this->getPauseActivationLink();
            }
        } else {
            return new Spacer(0);
        }
    }


    private function getPauseActivationLink() {
        $lnk = new Link("?pauseCron=" . $this->getId(), new Text("Pause", 4));
        $lnk->setToolTip("Folgende Ausführung auslassen");

        return $lnk;
    }


    private function getPauseDeactivationLink() {
        $lnk = new Link("?unpauseCron=" . $this->getId(), new Text("Aktivieren", 4));
        $lnk->setToolTip("Pausierung aufheben");

        return $lnk;
    }


    function checkPauseLink() {
        if (isset($_REQUEST['pauseCron']) && $_REQUEST['pauseCron'] == $this->getId() && $this->isNextExecutionCron()) {
            $this->setPause(true);
        }
        if (isset($_REQUEST['unpauseCron']) && $_REQUEST['unpauseCron'] == $this->getId() && $this->isNextExecutionCron()) {
            $this->setPause(false);
        }
    }


    function getNextExecutionDayIndex() {
        $dayOfWeek = date("w", time());

        for ($iO = 0; $iO < 7; $iO++) {
            $weekDayIndex = ($dayOfWeek + $iO) <= 6 ? ($dayOfWeek + $iO) : ($dayOfWeek + $iO) - 7;

            if ($this->CRON_ROW->getNamedAttribute($this->WEEKDAYS[$weekDayIndex]) == "J") {
                $std = $this->CRON_ROW->getNamedAttribute("stunde");
                $min = $this->CRON_ROW->getNamedAttribute("minute");

                if ((($std == date("H") && $min > date("i")) || ($std > date("H"))) || $iO > 0) {
                    return $weekDayIndex;
                }
            }
        }
    }


    function getNextExecutionTimeAsString() {
        $dayIndex = $this->getNextExecutionDayIndex();
        $ret = "";

        if (strlen($dayIndex) > 0 && $dayIndex >= 0 && $dayIndex <= 6) {
            $ret = $this->CRON_ROW->getNamedAttribute("wochentag");
            $ret .= " " . sprintf('%02d', $this->CRON_ROW->getNamedAttribute("stunde"));
            $ret .= ":" . sprintf('%02d', $this->CRON_ROW->getNamedAttribute("minute"));
        }
        return $ret;
    }


    function isNextExecutionCron() {
        $dayIndex = $this->CRON_ROW->getNamedAttribute("tagnr");
        if ($dayIndex >= 0 && $dayIndex <= 6) {
            $std = $this->CRON_ROW->getNamedAttribute("stunde");
            $min = $this->CRON_ROW->getNamedAttribute("minute");

            $rlNxtDay = date("w") < 6 ? date("w") + 1 : 0;

            $isCurrentDay = $dayIndex == date("w");
            $isNextDay = $dayIndex == $rlNxtDay;

            if (($isCurrentDay && ($std == date("H") && $min > date("i") || $std > date("H"))) || ($isNextDay && ($std == date("H") && $min < date("i") || $std <
                date("H")))) {
                return true;
            }
        }
        return false;
    }


    function isCronPaused() {
        $sql = "SELECT 'X' FROM homecontrol_cron_pause WHERE cron_id = " . $this->getId();
        $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        return (mysql_num_rows($result) > 0) && $this->isNextExecutionCron();
    }


    function setPause($b) {
        $sql = "";

        if ($b === true) {
            $sql = "INSERT INTO homecontrol_cron_pause (cron_id, pause_time) VALUES (" . $this->getId() . "," . time() . ")";
        } else {
            $sql = "DELETE FROM homecontrol_cron_pause WHERE cron_id=" . $this->getId();
        }

        $_SESSION['config']->DBCONNECT->executeQuery($sql);
    }



    /**
     * Liefert ein Array aller IDs 
     * die dem Cron zugeordnet sind.
     */
    function getItemRowsForCron() {
        $ret_item_rows = array();

        $sqlItems = "SELECT * FROM homecontrol_cron_items WHERE cron_id=" . $this->ID;
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

            // echo "</br>WHERE ".$whereStmt."</br>";
            
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
    function switchCron() {
        ob_end_flush();
        ob_implicit_flush();
        
        foreach ($this->getItemRowsForCron() as $itemRow) {
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

                // Hinterlegten Wert für Parameter zum entsprechenden Cron-Job ermitteln
                $prefix = "";
                if($default_logic){
                    $prefix = $itm->getParameterValueForCron($param->getRow(), $this->ID)==$itm->getDefaultLogicAnText()?"":"-";
                }
                
                $value = $fix?($prefix.$itm->getParameterValue($param->getRow())):$itm->getParameterValueForCron($param->getRow(), $this->ID);

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

                echo " - Schalte Cron: " . $useSenderUrl . "?" . $urlParams;
                
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