<?PHP
include ("config/dbConnect.php");
include ("functions/homecontrol_functions.php");

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
    Sensor Signal: " . $sensorId . "</br>
    " .$SENSOR_URL_COMMAND . "</br>
  </body>
</html>
";

// MySQL UPDATE
$sql = "UPDATE homecontrol_sensor SET lastValue=" . $sensorWert .", lastSignal=" . time() .
       " WHERE id=" . $sensorId;
$result = mysql_query($sql);


mysql_close($link);
// HTML-Daten an Browser senden,
// bevor Schaltvorgaenge ausgeloest werden.

ob_implicit_flush();
ob_end_flush();
flush();
ob_flush ();


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

    $sql = "SELECT id, sensor_id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off " .
        "FROM homecontrol_sensor_items " . "WHERE sensor_id=" . $sensorId . " " .
        "ORDER BY config_id DESC , zimmer_id DESC , etagen_id DESC ";

    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $whereStmt = "";
        $onOff = $row["on_off"];

        if (strlen($row["config_id"]) > 0) {
            $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($row["config_id"], $onOff, $SHORTCUTS_URL_COMMAND);
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


            $resultConfig = mysql_query($sqlConfig);
            while ($rowConfig = mysql_fetch_array($resultConfig)) {
                $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($rowConfig["id"], $onOff, $SHORTCUTS_URL_COMMAND);
            }
        }
    }

    return $SHORTCUTS_URL_COMMAND;
}

/**
 * Wenn ID nicht schon enthalten ist, Einstellungs-Werte übernehmen
 */
function addShortcutCommandItem($id, $status, $command) {
    $funkId = getConfigFunkId($id, $status);

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
