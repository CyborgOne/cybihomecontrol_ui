<?PHP

function checkUrlParameter($dbConnect) {
    checkEditorUrlParameter($dbConnect);
    checkDefaultUrlParameter($dbConnect);
    checkShortcutUrlParameter($dbConnect);
}


/**
 * Prüfung für URL-Parameter der Standard-Steuerung 
 * (Kein besonderer Editor definiert)
 */
function checkDefaultUrlParameter($dbConnect) {
    if (isset($_REQUEST['switchConfigId']) && strlen($_REQUEST['switchConfigId']) > 0) {
        $configItem =  $_SESSION['config']->getItemById($_REQUEST['switchConfigId']); 
        if($configItem != null){
            $switchParamArray = $configItem->checkUrlParams();
            $configItem->switchDevice($switchParamArray);
        }
        ob_flush();
    }
}


function checkEditorUrlParameter($dbConnect) {
    $doSwitch = true;
    if (isset($_REQUEST['switchEditorConfigZuordId']) && strlen($_REQUEST['switchEditorConfigZuordId']) > 0) {
        $dbTblConfig = new DbTable( $_SESSION['config']->DBCONNECT,
                                    "homecontrol_config",
                                    array("*"),
                                    "",
                                    "",
                                    "",
                                    "id = (SELECT config_id FROM homecontrol_control_editor_zuordnung WHERE id = " .$_REQUEST['switchEditorConfigZuordId'] .")");

        $dbTblEditor = new DbTable( $_SESSION['config']->DBCONNECT,
                                    "homecontrol_editoren",
                                    array("*"),
                                    "",
                                    "",
                                    "",
                                    "id = (SELECT editor_id FROM homecontrol_control_editor_zuordnung WHERE id = " .$_REQUEST['switchEditorConfigZuordId'] .")");

        if($dbTblConfig->getRowCount()>0 && $dbTblEditor->getRowCount()>0){
            $configRow = $dbTblConfig->getRow(1);
            foreach($dbTblEditor->ROWS as $editorRow){
                if($configRow!=null && $editorRow!=null){
                    $itm = $_SESSION['config']->getItemById($configRow->getNamedAttribute("id")); 
    
                    $classname = $editorRow->getNamedAttribute("classname");
                    $formName = "ControlForm".$itm->getId();
                    $editor = new $classname($editorRow, $itm, $formName);
                    
                    if(!$editor->doUpdate()){
                        $doSwitch = false;
                    }
                }
            }
        }        
    }
    return $doSwitch;
}


function checkShortcutUrlParameter($dbConnect) {
    if (isset($_REQUEST['switchShortcut']) && strlen($_REQUEST['switchShortcut']) > 0 && isset($_REQUEST['doShortcutId']) && strlen($_REQUEST['doShortcutId']) > 0) {
        $shortcutDbTbl = new DbTable($dbConnect, "homecontrol_shortcut", array("*"), "", "", "", "id=".$_REQUEST['doShortcutId']);
        
        foreach($shortcutDbTbl->ROWS as $shortcutRow){
          //echo "<br/>Shortcut: " .$shortcutRow->getNamedAttribute("name");
          $shortcut = new HomeControlShortcut($shortcutRow);
          $shortcut->switchShortcut(); 
        }
    }
}


function getEditorName($id) {
    $sql = "SELECT name FROM homecontrol_editoren WHERE id = " . $id;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row['name'];
}


function getSensorName($id) {
    $sql = "SELECT name FROM homecontrol_sensor WHERE id = " . $id;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row['name'];
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


function getHaBridgePath($dbCon){
    $haBridgePath = getPageConfigParam($dbCon, 'haBridgePath');  // "/services/haBridge";
    $haBridgePath = str_replace('\\','/',trim($haBridgePath));
    $haBridgePath = (substr($haBridgePath,-1)!='/') ? $haBridgePath.='/' : $haBridgePath;
    
    return $haBridgePath;
}


function refreshEchoDb($dbCon){
    if(getPageConfigParam($dbCon, 'haBridgeActive')!="J"){
        return;
    }
    
    $haBridgePath = getHaBridgePath($dbCon);
    $path = $haBridgePath ."data/device.db";
    //echo $path;
    
    $where = "WHERE EXISTS "
            ."(SELECT 'X' From homecontrol_sender_typen_parameter p WHERE p.senderTypId = "
            ."(SELECT s.senderTypId FROM homecontrol_sender s WHERE s.id = sender_id)"
            ."AND default_logic='J')";
    
    $dbItmTbl = new DbTable($dbCon, "homecontrol_config", array("*"), "", "", "", $where);
    
    $dbShortcutTbl = new DbTable($dbCon, "homecontrol_shortcut");
    
    $dbFile = fopen($path, "w");
    
    $lfdNr = 1;
    fwrite($dbFile, "[");

    foreach($dbItmTbl->ROWS as $itmRow){
        if($lfdNr>1){
            fwrite($dbFile, ",");
        }
        $itm = new HomeControlItem($itmRow);
        $haString = $itm->getHaBridgeDbString($lfdNr);
        fwrite($dbFile, $haString);
        $lfdNr++;
    }
    
    foreach($dbShortcutTbl->ROWS as $scRow){
        if($lfdNr>1){
            fwrite($dbFile, ",");
        }
        $sc = new HomeControlShortcut($scRow);
        $haString = $sc->getHaBridgeDbString($lfdNr);
        fwrite($dbFile, $haString);
        $lfdNr++;
    }
    
    fwrite($dbFile, "]");
    
    fclose($dbFile);
    
    exec("sudo systemctl restart habridge.service");
}

















function checkAndSwitchRegel($regelId, $SHORTCUTS_URL_COMMAND, $reverseJN = "J") {
    $dbRegelTerms = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_term", array("*"), "", "", "", "trigger_type=1 AND trigger_id=" . $regelId);
    $isValid = true;
    $allTriggerTermsValid = true;
    $allNoTriggerTermsValid = true;

    // Alle Regel-Bedingungen pr?fen
    foreach ($dbRegelTerms->ROWS as $rowRegelTerm) {
        //echo "</br>";
        $validator = new HomeControlTermValidator($rowRegelTerm);
        if (!$validator->isValid()) {
            //echo "<br/>" . $rowRegelTerm->getNamedAttribute("id") . ": Fail<br/>";
            //echo "TriggerJN: " . $rowRegelTerm->getNamedAttribute("trigger_jn") . "</br>";
            if ($rowRegelTerm->getNamedAttribute("trigger_jn") == "J") {
                $allTriggerTermsValid = false;
            }

            $isValid = false;
        } else {
            //echo "</br>" . $rowRegelTerm->getNamedAttribute("id") . ": OK<br/>";
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


/*
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
    */
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