<?PHP
include ("init.php");

if (!isset($_REQUEST['sensorId']) || !isset($_REQUEST['sensorWert']) ) {
  exitMissingValues();
}

$SHORTCUTS_URL_COMMAND = "";
$sensorId   = $_REQUEST['sensorId'];
$sensorWert = $_REQUEST['sensorWert'];

if (strlen($sensorId) <= 0 || strlen($sensorWert)<=0 ) {
  exitMissingValues();
}

$link = mysql_connect($DBHOST, $DBUSER, $DBPASS);
if (!$link) {
    die('Verbindung schlug fehl: ' . mysql_error());
}
mysql_select_db($DBNAME, $link) or die('Could not select database.');


/**********************
 *    Script Start
 **********************/
 
// MySQL UPDATE
$sql = "UPDATE homecontrol_sensor SET lastValue=" . $sensorWert .", lastSignal=" . time() .
       " WHERE id=" . $sensorId;
$result = mysql_query($sql);

$sql = "INSERT INTO homecontrol_sensor_log(sensor_id, value, update_time) values (".$sensorId.",".$sensorWert.",".time().")";
$result = mysql_query($sql);



// URL-Aufruf ermitteln
// Wenn keine Schaltvorgaenge notwendig sind (nur Status-Update)
// wird ein Leerstring zurueckgeliefert
$SENSOR_URL_COMMAND = prepareSensorSwitchLink($sensorId);


// HTML Ausgabe
echo "
<html>
  <head>
    <title>SignalInput</title>
  </head>
  <body>
    Sensor Signal: " . $sensorId . "  (" .$sensorWert .")</br>
  </body>
</html>
";

mysql_close($link);

// HTML-Daten an Browser senden,
// bevor Schaltvorgaenge ausgeloest werden.
ob_implicit_flush();
ob_end_flush();
flush();



// Wenn auszufuehrendes Kommando gefunden wurde, ausfuehren
if(strlen($SENSOR_URL_COMMAND)>0){
  $contents = file_get_contents("http://localhost/?switchShortcut=" . $SENSOR_URL_COMMAND);
}


function exitMissingValues(){
  echo "<html>
    <head>
      <title>SignalInput: FAILED!</title>
    </head>
    <body>
      FEHLER!</br>
      sensorId und sensorWert muessen angegeben werden!
    </body>
  </html>
  ";
  exit("sensorId und sensorWert muessen angegeben werden!");
}



function prepareSensorSwitchLink($sensorId) {
    // Zuerst alle Config-IDs, Dann alle Zimmer und zum Schluss die Etagen bearbeiten.
    // Durch die Methode addShortcutCommandItem($id, $status) wird gewährleistet dass jede ID nur einmal pro Vorgang geschaltet wird.
    $SHORTCUTS_URL_COMMAND = "";
    
    $dbRegeln = new DbTable($_SESSION['config']->DBCONNECT,
                            "homecontrol_regeln",
                            array("id", "name", "beschreibung"),
                            "Id, Name, Beschreibung",
                            "",
                            "",
                            "id IN (SELECT trigger_id FROM homecontrol_term WHERE term_type = 1 and sensor_id = ".$sensorId.")");
    
    foreach($dbRegeln->ROWS as $regelRow){
        $regelId = $regelRow->getNamedAttribute("id");


        $dbRegelTerms = new DbTable($_SESSION['config']->DBCONNECT,
                                    "homecontrol_term",
                                    array("*"),
                                    "",
                                    "",
                                    "",
                                    "trigger_type=1 AND trigger_id=".$regelId);
        $isValid = true;
        
        // Alle Regel-Bedingungen prüfen
        foreach($dbRegelTerms->ROWS as $rowRegelTerm){
            $validator = new HomeControlTermValidator($rowRegelTerm);
            if (!$validator->isValid()){
                echo $rowRegelTerm->getNamedAttribute("id").": Fail<br/>";
                $isValid = false;
            } else {
                echo $rowRegelTerm->getNamedAttribute("id").": OK<br/>";
            }
        }
    
        // Wenn alle Bedingungen erfüllt sind
        // Geräte schalten
        if($isValid){    
            echo "Alle Gültig!<br><br>";
                       
            $sql = "SELECT id, regel_id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off " .
                "FROM homecontrol_regeln_items WHERE regel_id=".$regelId . " " .
                "ORDER BY on_off DESC, config_id DESC , zimmer_id DESC , etagen_id DESC ";

            $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
            while ($row = mysql_fetch_array($result)) {
                $whereStmt = "";
                $onOff = $row["on_off"];
      
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
        
                    $sqlConfig = "SELECT id, funk_id, funk_id2 FROM homecontrol_config " . "WHERE " .
                        $whereStmt;
                
                    $resultConfig = $_SESSION['config']->DBCONNECT->executeQuery($sqlConfig);
                    while ($rowConfig = mysql_fetch_array($resultConfig)) {
                        $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($rowConfig["funk_id"], $onOff, $SHORTCUTS_URL_COMMAND);
                        echo $SHORTCUTS_URL_COMMAND."<br>";
                    }
                }
            }
        }
    }
    
    return $SHORTCUTS_URL_COMMAND;
}





/**
 * Wenn ID nicht schon enthalten ist, Einstellungs-Werte übernehmen
 */
function addShortcutCommandItem($funkId, $status, $command) {

    if (!strpos($command, $funkId . "-") && strlen($funkId) > 0 && strlen($status) >
        1) {
        $command .= $funkId . "-" . $status . ";";
    }
    return $command;
}


function getConfigFunkId($id, $status) {

    $sqlConfig = "SELECT id, funk_id, funk_id2, control_art FROM homecontrol_config WHERE id=" .
        $id;

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

?>
