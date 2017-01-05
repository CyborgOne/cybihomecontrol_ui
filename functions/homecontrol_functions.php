<?PHP

function checkUrlParameter($dbConnect) {
    checkDefaultUrlParameter($dbConnect);
    checkEditorUrlParameter($dbConnect);
}


/**
 * Prüfung für URL-Parameter der Standard-Steuerung 
 * (Kein besonderer Editor definiert)
 */
function checkDefaultUrlParameter($dbConnect) {
    if (isset($_REQUEST['switchConfigId']) && strlen($_REQUEST['switchConfigId']) > 0) {
        $dbTblItem = new DbTable($dbConnect, "homecontrol_config", array('*'), "", "", "", "id=" . $_REQUEST['switchConfigId']);
        if ($dbTblItem->getRowCount() > 0) {
            $configItem = new HomeControlItem($dbTblItem->getRow(1));
            $allParams = array();
            $optionalParams = array();
            $fixParams = array();
            $defaultLogicParams = array();
            $switchParams = array();

            // Alle Parameter zur ConfigID holen
            $paramTable = new DbTable($dbConnect, "homecontrol_sender_typen_parameter", array('*'), "", "", "",
                "senderTypId=(SELECT s.senderTypId FROM homecontrol_sender s, homecontrol_config c WHERE s.id = c.sender_id AND c.id = " . $_REQUEST['switchConfigId'] .
                ")");

            $lastSenderTyp = "";
            $switchUrl = "";

            $allParamsSet = true;
            foreach ($paramTable->ROWS as $row) {
                $allParams[count($allParams)] = $row->getNamedAttribute('name');
                $optional = false;
                $fix = false;
                $default_logic = false;

                $val = isset($_REQUEST[$row->getNamedAttribute('name')]) ? $_REQUEST[$row->getNamedAttribute('name')] : "";
                //echo "Check ".$row->getNamedAttribute('name')." = " .$val ."</br>";

                if ($row->getNamedAttribute("optional") == "J") {
                    $optionalParams[count($optionalParams)] = $row->getNamedAttribute('name');
                    $optional = true;
                }
                if ($row->getNamedAttribute("fix") == "J") {
                    $fixParams[count($fixParams)] = $row->getNamedAttribute('name');
                    $fix = true;
                }
                if ($row->getNamedAttribute("default_logic") == "J") {
                    $defaultLogicParams[count($defaultLogicParams)] = $row->getNamedAttribute('name');
                    $default_logic = true;
                }

                $value = "";
                if (isset($_REQUEST[$row->getNamedAttribute('name')]) && strlen($_REQUEST[$row->getNamedAttribute('name')]) > 0) {
                    $value = $default_logic ? $_REQUEST[$row->getNamedAttribute('name') . $_REQUEST[$row->getNamedAttribute('name')]] : $_REQUEST[$row->getNamedAttribute('name')];
                    //echo "Wert: ".$value."</br>";
                    if (strlen($switchUrl) > 0) {
                        $switchUrl .= "&";
                    }
                    $switchUrl .= $row->getNamedAttribute('name') . "=" . $value;
                    $switchParams[count($switchParams)] = array($row, $value);
                }

                if (strlen($value) <= 0) {
                    if (!$optional || ($optional && $configItem->isParameterOptionalActive($row->getNamedAttribute('id')))) {
                        $allParamsSet = false;
                    }
                }
            }
            
            echo "Alle notwendigen Parameter gesetzt? ". ($allParamsSet?"Ja":"Nein")."</br>";

            if ($allParamsSet) {
                $senderUrl = getArduinoUrlForDeviceId($_REQUEST['switchConfigId'], $dbConnect);
                $useSenderUrl = strlen($senderUrl) > 0 ? $senderUrl : $arduinoUrl;
                $urlArray = parse_url($useSenderUrl);
                $host = $urlArray['host'];
                $check = @fsockopen($host, 80);

                if ($check) {
                    ob_implicit_flush(true);
                    //echo $useSenderUrl."?".$switchUrl."</br>";
                    try {
                        $retVal = file_get_contents($useSenderUrl . "?" . $switchUrl);
                    }
                    catch (exception $e) {
                        echo "FEHLER BEIM SCHALTEN!";
                    }
                }

                foreach ($switchParams as $p) {
                    $pRow = $p[0];
                    $pValue = $p[1];
                    //echo "refresh ".$pRow->getNamedAttribute("name") ." = ".$pValue."<br>";
                    if ($pRow->getNamedAttribute("fix") != "J" && $pRow->getNamedAttribute("default_logic") != "J") {
                        $configItem->setParameterValue($pRow, $configItem->CONFIG_ROW, $pValue);
                    }
                }

                try {
                    $myfile = fopen("/var/www/switch.log", "a+");
                    fwrite($myfile, "(" . date("d.M.Y - H:i:s") . "): " . $useSenderUrl . "?" . $switchUrl . "\n");
                    fclose($myfile);
                }
                catch (exception $e) {
                }
            }
        }
        ob_flush();
    }
}

// TODO: URL Parameter für definierte Editoren prüfen
function checkEditorUrlParameter($dbConnect) {

}


function switchDevice($configId, $dbConnect) {
    //echo "switchShortcut: ".$arduinoUrl." > ".$shortcutUrl."\n";
    $loginNeed = true;
    $loginExternOnly = false;
    $loginOK = false;

    try {
        $loginNeed = getPageConfigParam($dbConnect, "loginForSwitchNeed") == "J";
        //    echo "Login needed: ".$loginNeed."\n";
        $loginExternOnly = getPageConfigParam($dbConnect, "loginExternOnly") == "J";
        //    echo "Login extern only: ".$loginExternOnly."\n";
        $loginOK = isset($_SESSION['config']) && isset($_SESSION['config']->CURRENTUSER) && ($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->
            CURRENTUSER->STATUS == "user");
        //    echo "Login OK: ".$loginOK."\n";

        $clientIP = explode(".", $_SERVER['REMOTE_ADDR']);
        $serverIP = explode(".", $_SERVER['SERVER_ADDR']);
    }
    catch (exception $ex) {
        echo $ex->getMessage() . "\n";
    }
    //    echo "client: ".$_SERVER['REMOTE_ADDR']."\n";
    //    echo "server: ".$_SERVER['SERVER_ADDR']."\n";

    if (!$loginNeed || $loginOK || ($loginExternOnly && ($serverIP[0] == $clientIP[0] && $serverIP[1] == $clientIP[1] && $serverIP[2] == $clientIP[2]))) {
        $switchStatusCheck = true;


        ob_implicit_flush(true);

        // Nach Semikolon trennen,
        // um die einzelnen zu schaltenden Elemente zu erhalten
        $fullConfigArray = explode(";", $shortcutUrl);

        foreach ($fullConfigArray as $cfg) {
            // Nach Minus trennen um ID und STATUS zu erhalten.
            $tmp = explode("-", $cfg);

            if (count($tmp) >= 2) {
                $id = $tmp[0];
                $status = $tmp[1];
                $deviceId = $id;
                $dimmer = 0;
                if (count($tmp) == 3) {
                    $dimmer = $tmp[2];
                }
                if (strlen($id) > 0 && $id > 0) {
                    $status = $status == "on" ? "on" : "off";
                    //echo $id."->".$status."<br>\n";
                    // Wenn ausgeschaltet werden soll,
                    // negative ID ?bergeben
                    if ($status == "off") {
                        $id = $id * (-1);
                    }

                    //echo "\n<br>Switch-URL: " .$arduinoUrl."?schalte&" .$id;

                    $senderUrl = getArduinoUrlForDeviceId($deviceId, $dbConnect);
                    $useSenderUrl = strlen($senderUrl) > 0 ? $senderUrl : $arduinoUrl;
                    $urlArray = parse_url($useSenderUrl);
                    $host = $urlArray['host'];

                    $check = @fsockopen($host, 80);


                    if ($check) {
                        $retVal = file_get_contents($useSenderUrl . "?schalte=" . $id . "&dimm=" . $dimmer);

                        try {
                            $myfile = fopen("/var/www/switch.log", "a+");
                            fwrite($myfile, "(" . date("d.M.Y - H:i:s") . "): " . $useSenderUrl . "?schalte=" . $id . "&dimm=" . $dimmer . "\n");
                            fclose($myfile);
                        }
                        catch (exception $e) {
                        }

                        if (strpos(substr($retVal, 0, 50), "Warning") > 0) {
                            $switchStatusCheck = false;
                            echo "<b>Vorgang auf Grund eines unerwarteten Fehlers abgebrochen!</b><br><br>" . $retVal;
                            break;
                        } else {
                            //echo "<br><font color='green'><b>schalte ".$id>=0?$id:($id*-1)." ".($status=="on"?"ein":"aus")."</b></font>";
                        }
                    } else {
                        echo "<br><font color='red'>KEINE VERBINDUNG zu: " . $host . "<br><b>Vorgang abgebrochen!</b></font>";
                        break;
                    }

                }
                //echo "<br>";
            }
            ob_flush();
            sleep(1);
        }
    }
}



/**
 * Logik zum schalten eines Geräts mit Cron-Logik.
 *  
 * Holt die Parameter aus der Tabelle:
 * homecontrol_cron_parameter_values
 */
function switchCronDevice($cronId, $configId, $dbConnect) {
    if (isset($shortcutId) && strlen($shortcutId) > 0 && isset($configId) && strlen($configId) > 0) {
        $dbTblItem = new DbTable($dbConnect, "homecontrol_config", array('*'), "", "", "", "id=" . $configId);
        if ($dbTblItem->getRowCount() > 0) {
            $configItem = new HomeControlItem($dbTblItem->getRow(1));
            $allParams = array();
            $optionalParams = array();
            $fixParams = array();
            $defaultLogicParams = array();
            $switchParams = array();

            // Alle Parameter zur ConfigID holen
            $paramTable = new DbTable($dbConnect, "homecontrol_sender_typen_parameter", array('*'), "", "", "",
                "senderTypId=(SELECT s.senderTypId FROM homecontrol_sender s, homecontrol_config c WHERE s.id = c.sender_id AND c.id = " . $configId . ")");

            $lastSenderTyp = "";
            $switchUrl = "";

            $allParamsSet = true;
            foreach ($paramTable->ROWS as $row) {
                $allParams[count($allParams)] = $row->getNamedAttribute('name');
                $optional = false;
                $fix = false;
                $default_logic = false;

                // Hinterlegten Wert für Parameter zum entsprechenden Cron-Job ermitteln
                $value = getDbValue("homecontrol_cron_parameter_values", "value", "cron_id=" . $cronId . " AND config_id=" . $configId . " AND param_id=" . $row->
                    getNamedAttribute("id"));

                if ($row->getNamedAttribute("optional") == "J") {
                    $optionalParams[count($optionalParams)] = $row->getNamedAttribute('name');
                    $optional = true;
                }
                if ($row->getNamedAttribute("fix") == "J") {
                    $fixParams[count($fixParams)] = $row->getNamedAttribute('name');
                    $fix = true;
                }
                if ($row->getNamedAttribute("default_logic") == "J") {
                    $defaultLogicParams[count($defaultLogicParams)] = $row->getNamedAttribute('name');
                    $default_logic = true;
                }

                if (isset($value) && strlen($value) > 0) {
                    //echo "Wert: ".$value."</br>";
                    if (strlen($switchUrl) > 0) {
                        $switchUrl .= "&";
                    }
                    $switchUrl .= $row->getNamedAttribute('name') . "=" . $value;
                    $switchParams[count($switchParams)] = array($row, $value);
                }

                if (strlen($value) <= 0) {
                    if (!$optional && $configItem->isParameterOptionalActive($row->getNamedAttribute('id'))) {
                        $allParamsSet = false;
                    }
                }
            }
            //echo "Alle notwendigen Parameter gesetzt? ". ($allParamsSet?"Ja":"Nein")."</br>";

            if ($allParamsSet) {
                $senderUrl = getArduinoUrlForDeviceId($configId, $dbConnect);
                $useSenderUrl = strlen($senderUrl) > 0 ? $senderUrl : $arduinoUrl;
                $urlArray = parse_url($useSenderUrl);
                $host = $urlArray['host'];
                $check = @fsockopen($host, 80);

                if ($check) {
                    ob_implicit_flush(true);
                    //echo $useSenderUrl."?".$switchUrl."</br>";
                    try {
                        $retVal = file_get_contents($useSenderUrl . "?" . $switchUrl);
                    }
                    catch (exception $e) {
                        echo "FEHLER BEIM SCHALTEN!";
                    }
                }

                try {
                    $myfile = fopen("/var/www/switch.log", "a+");
                    fwrite($myfile, "(" . date("d.M.Y - H:i:s") . "): " . $useSenderUrl . "?" . $switchUrl . "\n");
                    fclose($myfile);
                }
                catch (exception $e) {
                }
            }
        }
        ob_flush();
    }
}



function getSensorName($id) {
    $sql = "SELECT name FROM homecontrol_sensor WHERE id = " . $id;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row['name'];
}


// TODO: $arduinoUrl wird eigtl nicht mehr ben?tigt da eh f?r jedes Device
//       die ArduinoURL ermittelt werden muss, seit mehrere Sender m?glich sind.
function switchShortcut($arduinoUrl, $shortcutUrl, $dbConnect) {
    //echo "switchShortcut: ".$arduinoUrl." > ".$shortcutUrl."\n";
    $loginNeed = true;
    $loginExternOnly = false;
    $loginOK = false;

    try {
        $loginNeed = getPageConfigParam($dbConnect, "loginForSwitchNeed") == "J";
        //    echo "Login needed: ".$loginNeed."\n";
        $loginExternOnly = getPageConfigParam($dbConnect, "loginExternOnly") == "J";
        //    echo "Login extern only: ".$loginExternOnly."\n";
        $loginOK = isset($_SESSION['config']) && isset($_SESSION['config']->CURRENTUSER) && ($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->
            CURRENTUSER->STATUS == "user");
        //    echo "Login OK: ".$loginOK."\n";

        $clientIP = explode(".", $_SERVER['REMOTE_ADDR']);
        $serverIP = explode(".", $_SERVER['SERVER_ADDR']);
    }
    catch (exception $ex) {
        echo $ex->getMessage() . "\n";
    }
    //    echo "client: ".$_SERVER['REMOTE_ADDR']."\n";
    //    echo "server: ".$_SERVER['SERVER_ADDR']."\n";

    if (!$loginNeed || $loginOK || ($loginExternOnly && ($serverIP[0] == $clientIP[0] && $serverIP[1] == $clientIP[1] && $serverIP[2] == $clientIP[2]))) {
        $switchStatusCheck = true;


        ob_implicit_flush(true);

        // Nach Semikolon trennen,
        // um die einzelnen zu schaltenden Elemente zu erhalten
        $fullConfigArray = explode(";", $shortcutUrl);

        foreach ($fullConfigArray as $cfg) {
            // Nach Minus trennen um ID und STATUS zu erhalten.
            $tmp = explode("-", $cfg);

            if (count($tmp) >= 2) {
                $id = $tmp[0];
                $status = $tmp[1];
                $deviceId = $id;
                $dimmer = 0;
                if (count($tmp) == 3) {
                    $dimmer = $tmp[2];
                }
                if (strlen($id) > 0 && $id > 0) {
                    $status = $status == "on" ? "on" : "off";
                    //echo $id."->".$status."<br>\n";
                    // Wenn ausgeschaltet werden soll,
                    // negative ID ?bergeben
                    if ($status == "off") {
                        $id = $id * (-1);
                    }

                    //echo "\n<br>Switch-URL: " .$arduinoUrl."?schalte&" .$id;

                    $senderUrl = getArduinoUrlForDeviceId($deviceId, $dbConnect);
                    $useSenderUrl = strlen($senderUrl) > 0 ? $senderUrl : $arduinoUrl;
                    $urlArray = parse_url($useSenderUrl);
                    $host = $urlArray['host'];

                    $check = @fsockopen($host, 80);


                    if ($check) {
                        $retVal = file_get_contents($useSenderUrl . "?schalte=" . $id . "&dimm=" . $dimmer);

                        try {
                            $myfile = fopen("/var/www/switch.log", "a+");
                            fwrite($myfile, "(" . date("d.M.Y - H:i:s") . "): " . $useSenderUrl . "?schalte=" . $id . "&dimm=" . $dimmer . "\n");
                            fclose($myfile);
                        }
                        catch (exception $e) {
                        }

                        if (strpos(substr($retVal, 0, 50), "Warning") > 0) {
                            $switchStatusCheck = false;
                            echo "<b>Vorgang auf Grund eines unerwarteten Fehlers abgebrochen!</b><br><br>" . $retVal;
                            break;
                        } else {
                            //echo "<br><font color='green'><b>schalte ".$id>=0?$id:($id*-1)." ".($status=="on"?"ein":"aus")."</b></font>";
                        }
                    } else {
                        echo "<br><font color='red'>KEINE VERBINDUNG zu: " . $host . "<br><b>Vorgang abgebrochen!</b></font>";
                        break;
                    }

                }
                //echo "<br>";
            }
            ob_flush();
            sleep(1);
        }
    }
}



function isFunk2Need($art) {
    /*$configDb  = new DbTable($_SESSION['config']->DBCONNECT,
    'homecontrol_art', 
    array("zweite_funkid_jn") , 
    "",
    "",
    "",
    "id=".$art);

    $row = $configDb->getRow(1);

    if($row != null){
    return $row->getNamedAttribute("zweite_funk_id") == "J";    
    } */

    return false;
}



/**
 * Liefert den URL-String zum schalten aller Items  
 * 
 * Beispiel:
 * ?10-off;100-off;101-off;104-off;91-off;103-off;2-off;110-off;111-off;112-off;113-off;114-off;3-off;120-off;121-off;  
 */
function getShortcutSwitchKeyForCron($con, $cronId) {
    $sqlItems = "SELECT id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off FROM homecontrol_cron_items WHERE cron_id=" . $cronId;
    $resultItems = $con->executeQuery($sqlItems);
    $shortcutUrl = ""; //" "?switchShortcut=";

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

        $sqlSubItems = "SELECT id, funk_id, funk_id2 FROM homecontrol_config WHERE " . $whereStmt;
        $resultSubItems = $con->executeQuery($sqlSubItems);
        while ($rowSub = mysql_fetch_array($resultSubItems)) {
            $shortcutUrl .= $rowSub['funk_id'] . "-" . (strlen($row['on_off']) > 0 ? $row['on_off'] : "off") . ";";
        }
    }

    return $shortcutUrl;
}



function getShortcutSwitchKeyByName($shortcutName) {
    $sqlSubItems = "SELECT * FROM homecontrol_shortcutview WHERE lower(shortcut_name)='" . strtolower($shortcutName) . "' ORDER BY on_off";
    $resultSubItems = mysql_query($sqlSubItems);
    $shortcutUrl = "";
    while ($rowSub = mysql_fetch_array($resultSubItems)) {
        // TODO:
        //      $shortcutUrl .= $rowSub['funk_id'] ."-" . (strlen($rowSub['on_off'])>0?$rowSub['on_off']:"off") .";";
    }
    return $shortcutUrl;
}


function getShortcutSwitchKeyById($con, $shortcutId) {
    $sqlSubItems = "SELECT * FROM homecontrol_shortcutview WHERE shortcut_id=" . $shortcutId . " ORDER BY on_off";
    $resultSubItems = mysql_query($sqlSubItems);
    $shortcutUrl = "";
    while ($rowSub = mysql_fetch_array($resultSubItems)) {
        // TODO:
        //     $shortcutUrl .= $rowSub['funk_id'] ."-" . (strlen($rowSub['on_off'])>0?$rowSub['on_off']:"off") .";";
    }
    return $shortcutUrl;
}



function checkAndSwitchRegel($regelId, $SHORTCUTS_URL_COMMAND, $reverseJN = "J") {
    $dbRegelTerms = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_term", array("*"), "", "", "", "trigger_type=1 AND trigger_id=" . $regelId);
    $isValid = true;
    $allTriggerTermsValid = true;
    $allNoTriggerTermsValid = true;

    // Alle Regel-Bedingungen pr?fen
    foreach ($dbRegelTerms->ROWS as $rowRegelTerm) {
        echo "</br>";
        $validator = new HomeControlTermValidator($rowRegelTerm);
        if (!$validator->isValid()) {
            echo "<br/>" . $rowRegelTerm->getNamedAttribute("id") . ": Fail<br/>";
            echo "TriggerJN: " . $rowRegelTerm->getNamedAttribute("trigger_jn") . "</br>";
            if ($rowRegelTerm->getNamedAttribute("trigger_jn") == "J") {
                $allTriggerTermsValid = false;
            }

            $isValid = false;
        } else {
            echo "</br>" . $rowRegelTerm->getNamedAttribute("id") . ": OK<br/>";
        }
    }

    // Wenn alle Bedingungen erf?llt sind,
    // Ger?te schalten. Bei reverseJN == "J"
    // negiert schalten.
    if ($isValid || ($reverseJN == "J" && !$allTriggerTermsValid)) {
        $sql = "SELECT id, regel_id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off " . "FROM homecontrol_regeln_items WHERE regel_id=" . $regelId .
            " " . "ORDER BY on_off DESC, config_id DESC , zimmer_id DESC , etagen_id DESC ";

        $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        while ($row = mysql_fetch_array($result)) {
            $whereStmt = "";
            $onOff = $isValid ? $row["on_off"] : ($row["on_off"] == "on" ? "off" : "on");

            if (strlen($row["config_id"]) > 0) {
                $funkId = getConfigFunkId($row["config_id"], $onOff);
                $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($funkId, $onOff, $SHORTCUTS_URL_COMMAND);
            } else {

                if (strlen($row["art_id"]) > 0) {
                    $whereStmt = $whereStmt . " control_art=" . $row["art_id"];
                }

                if (strlen($row["zimmer_id"]) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt = $whereStmt . " AND ";
                    }
                    $whereStmt = $whereStmt . " zimmer=" . $row["zimmer_id"];
                }

                if (strlen($row["etagen_id"]) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt = $whereStmt . " AND ";
                    }
                    $whereStmt = $whereStmt . " etage=" . $row["etagen_id"];
                }

                $sqlConfig = "SELECT id, funk_id, funk_id2 FROM homecontrol_config " . "WHERE " . $whereStmt;

                $resultConfig = $_SESSION['config']->DBCONNECT->executeQuery($sqlConfig);
                while ($rowConfig = mysql_fetch_array($resultConfig)) {
                    $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($rowConfig["funk_id"], $onOff, $SHORTCUTS_URL_COMMAND);
                }
            }
        }
    }

    return $SHORTCUTS_URL_COMMAND;
}



/**
 * Wenn ID nicht schon enthalten ist, Einstellungs-Werte ?bernehmen
 */
function addShortcutCommandItem($funkId, $status, $command) {

    if (!strpos($command, $funkId . "-") && strlen($funkId) > 0 && strlen($status) > 1) {
        $command .= $funkId . "-" . $status . ";";
    }
    return $command;
}



function getConfigFunkId($id, $status) {

    $sqlConfig = "SELECT id, funk_id, funk_id2, control_art FROM homecontrol_config WHERE id=" . $id;

    $resultConfig = mysql_query($sqlConfig);
    if ($rowConfig = mysql_fetch_array($resultConfig)) {
        if ($status == "off" && isFunk2Need($rowConfig["control_art"])) {
            return $rowConfig["funk_id2"];
        } else {
            return $rowConfig["funk_id"];
        }

        return "";
    }

}



function getArduinoUrlForDeviceId($hcConfigId, $dbConnect) {
    $selectSenderIP = "SELECT ip FROM `homecontrol_sender` WHERE id = (SELECT sender_id FROM homecontrol_config WHERE id = " . $hcConfigId . " )";
    $rslt = $dbConnect->executeQuery($selectSenderIP);
    $r = mysql_fetch_array($rslt);

    if (isset($r['ip']) && strlen($r['ip']) > 0) {
        return "http://" . $r['ip'] . "/rawCmd";
    } else {
        // Wenn keine Sender-IP zum Device ermittelt werden kann, Default holen.
        $selectSenderIP = "SELECT ip FROM `homecontrol_sender` WHERE default_jn = 'J'";
        $rslt = $dbConnect->executeQuery($selectSenderIP);
        $r = mysql_fetch_array($rslt);

        if (isset($r['ip']) && strlen($r['ip']) > 0) {
            return "http://" . $r['ip'] . "/rawCmd";
        }
    }

    // wird keine IP gefunden: FallBack auf altes Vorgehen
    return "http://" . getPageConfigParam($dbConnect, 'arduino_url');
}



function prepareSensorSwitchLink($sensorId) {
    // Zuerst alle Config-IDs, Dann alle Zimmer und zum Schluss die Etagen bearbeiten.
    // Durch die Methode addShortcutCommandItem($id, $status) wird gewaehrleistet dass jede ID nur einmal pro Vorgang geschaltet wird.
    $SHORTCUTS_URL_COMMAND = "";

    // Alle Automatisierungs-Regeln die von der Sensor-?nderung betroffen sind holen
    $dbRegeln = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_regeln", array("id", "name", "reverse_switch", "beschreibung"),
        "Id, Name, Reverse-Switch, Beschreibung", "", "",
        "id IN (SELECT trigger_id FROM homecontrol_term WHERE  trigger_type = 1 and term_type IN (1,2) and sensor_id = " . $sensorId . " AND trigger_jn='J')");

    foreach ($dbRegeln->ROWS as $regelRow) {
        $regelId = $regelRow->getNamedAttribute("id");
        $SHORTCUTS_URL_COMMAND = checkAndSwitchRegel($regelId, $SHORTCUTS_URL_COMMAND, $regelRow->getNamedAttribute("reverse_switch"));
    }

    return $SHORTCUTS_URL_COMMAND;
}



function refreshSensorValue($con, $sensorId, $sensorWert) {
    $SHORTCUTS_URL_COMMAND = "";

    $lastVal = getDbValue("homecontrol_sensor", "lastValue", "id=" . $sensorId);
    if ($lastVal == $sensorWert) {
        return;
    }

    // MySQL UPDATE
    $sql = "UPDATE homecontrol_sensor SET lastValue=" . $sensorWert . ", lastSignal=" . time() . " WHERE id=" . $sensorId;
    $result = $con->executeQuery($sql);

    $sql = "INSERT INTO homecontrol_sensor_log(sensor_id, value, update_time) values (" . $sensorId . "," . $sensorWert . "," . time() . ")";
    $result = $con->executeQuery($sql);

    try {
        $myfile = fopen("signalIn.log", "a+");
        fwrite($myfile, "" . date("d.M.Y H:i:s") . ": " . "Sensor " . $sensorId . "  aktualisiert von: " . $lastVal . " nach " . $sensorWert . "\n");
        fclose($myfile);
    }
    catch (exception $e) {
    }



    // URL-Aufruf ermitteln
    // Wenn keine Schaltvorgaenge notwendig sind (nur Status-Update)
    // wird ein Leerstring zurueckgeliefert
    $SENSOR_URL_COMMAND = prepareSensorSwitchLink($sensorId);

    // HTML-Daten an Browser senden,
    // bevor Schaltvorgaenge ausgeloest werden.
    ob_implicit_flush();
    ob_end_flush();
    flush();


    // Wenn auszufuehrendes Kommando gefunden wurde, ausfuehren
    if (strlen($SENSOR_URL_COMMAND) > 0) {
        switchShortcut("", $SENSOR_URL_COMMAND, $con);

        try {
            $myfile = fopen("signalIn.log", "a+");
            fwrite($myfile, "SCHALTUNG -> " . $SENSOR_URL_COMMAND . "\n");
            fclose($myfile);
        }
        catch (exception $e) {
        }
    }
}


function checkSensorInputMissingValues() {
    if (!isset($_REQUEST['sensorId']) || !isset($_REQUEST['sensorWert'])) {
        return false;
    } else {
        if (strlen($_REQUEST['sensorId']) <= 0 || strlen($_REQUEST['sensorWert']) <= 0) {
            return false;
        }
    }
    return true;
}

?>