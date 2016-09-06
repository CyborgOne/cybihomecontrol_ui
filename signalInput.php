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

 
// MySQL UPDATE
$sql = "UPDATE homecontrol_sensor SET lastValue=" . $sensorWert .", lastSignal=" . time() .
       " WHERE id=" . $sensorId;
$result = $_SESSION['config']->DBCONNECT->executeQuery($sql);

$sql = "INSERT INTO homecontrol_sensor_log(sensor_id, value, update_time) values (".$sensorId.",".$sensorWert.",".time().")";
$result = $_SESSION['config']->DBCONNECT->executeQuery($sql);

shell_exec("tail /var/www/signalIn.log -n 99 > /var/www/signalIn.cut");
shell_exec("mv /var/www/signalIn.cut /var/www/signalIn.log");

$myfile = fopen("signalIn.log", "a+") or die("Unable to open file!");
fwrite($myfile, "\r\n Sensor: ".$sensorId." - Neuer Wert: ".$sensorWert ."(".date("d.M.Y H:i:s").")");
fclose($myfile);



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
if(strlen($SENSOR_URL_COMMAND)>0){
  echo "http://localhost/?switchShortcut=" . $SENSOR_URL_COMMAND;
  $contents = file_get_contents("http://localhost/?switchShortcut=" . $SENSOR_URL_COMMAND);
  $myfile = fopen("signalIn.log", "a+") or die("Unable to open file!");
  fwrite($myfile, "\r\n SCHALTUNG -> ".date("d.M.Y H:i:s").": ");
  fwrite($myfile, $SENSOR_URL_COMMAND);
  fclose($myfile);
}

function exitMissingValues(){
  exit("sensorId und sensorWert muessen angegeben werden!");
}

function prepareSensorSwitchLink($sensorId) {
    // Zuerst alle Config-IDs, Dann alle Zimmer und zum Schluss die Etagen bearbeiten.
    // Durch die Methode addShortcutCommandItem($id, $status) wird gewährleistet dass jede ID nur einmal pro Vorgang geschaltet wird.
    $SHORTCUTS_URL_COMMAND = "";
    
    // Alle Automatisierungs-Regeln die von der Sensor-Änderung betroffen sind holen
    $dbRegeln = new DbTable($_SESSION['config']->DBCONNECT,
                            "homecontrol_regeln",
                            array("id", "name", "reverse_switch", "beschreibung"),
                            "Id, Name, Reverse-Switch, Beschreibung",
                            "",
                            "",
                            "id IN (SELECT trigger_id FROM homecontrol_term WHERE  trigger_type = 1 and term_type IN (1,2) and sensor_id = ".$sensorId." )"); // AND trigger_jn='J'

    foreach($dbRegeln->ROWS as $regelRow){
        $regelId = $regelRow->getNamedAttribute("id");
        $SHORTCUTS_URL_COMMAND = checkAndSwitchRegel($regelId, $SHORTCUTS_URL_COMMAND, $regelRow->getNamedAttribute("reverse_switch"));
    }
    
    return $SHORTCUTS_URL_COMMAND;
}



?>
