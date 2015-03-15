<?PHP

include ("config/dbConnect.php");
include ("functions/homecontrol_functions.php");

if (!isset($_REQUEST['sensorId'])) {
    return;
}

$SHORTCUTS_URL_COMMAND = "";
$sensorId = $_REQUEST['sensorId'];
if (strlen($sensorId) <= 0) {
    return;
}


$link = mysql_connect($DBHOST, $DBUSER, $DBPASS);
if (!$link) {
    die('Verbindung schlug fehl: ' . mysql_error());
}
mysql_select_db($DBNAME, $link) or die('Could not select database.');

echo "
<html>
  <head>
    <title>SignalInput</title>
  </head>
  <body>
";


$SENSOR_URL_COMMAND = prepareSensorSwitchLink($sensorId);
echo $SENSOR_URL_COMMAND . "</br>";
$contents = file_get_contents("http://localhost/?switchShortcut=" . $SENSOR_URL_COMMAND);

echo "Sensor Signal: " . $sensorId . "</br>";

echo "
  </body>
</html>
";

if (isset($_REQUEST['sensorWert']) && strlen($_REQUEST['sensorWert']) > 0) {
    $sql = "UPDATE homecontrol_sensor SET lastValue=" . $_REQUEST['sensorWert'] .
        " WHERE id=" . $sensorId;
    $result = mysql_query($sql);
}

$sql = "UPDATE homecontrol_sensor SET lastSignal=" . time() . " WHERE id=" . $sensorId;
$result = mysql_query($sql);

mysql_close($link);


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