<?PHP
include ("init.php");

if (isset($_SESSION['lastTimerMin']) && isset($_SESSION['lastTimerStd']) && !checkLastTime() ) {
  exitRunOnce();
}

$SHORTCUTS_URL_COMMAND = "";
 
// URL-Aufruf ermitteln
// Wenn keine Schaltvorgaenge notwendig sind (nur Status-Update)
// wird ein Leerstring zurueckgeliefert
$SENSOR_URL_COMMAND = prepareTimerSwitchLink($sensorId);


$_SESSION['lastTimerStd'] = date("H");
$_SESSION['lastTimerMin'] = date("i");

// HTML Ausgabe
echo "Timer Signal: " . $_SESSION['lastTimerStd'] . ":" .$_SESSION['lastTimerMin'];



// HTML-Daten an Browser senden,
// bevor Schaltvorgaenge ausgeloest werden.
ob_implicit_flush();
ob_end_flush();
flush();



// Wenn auszufuehrendes Kommando gefunden wurde, ausfuehren
if(strlen($SENSOR_URL_COMMAND)>0){
//  $contents = file_get_contents("http://localhost/?switchShortcut=" . $SENSOR_URL_COMMAND);
}



    

function prepareTimerSwitchLink($hour, $min) {
    // Zuerst alle Config-IDs, Dann alle Zimmer und zum Schluss die Etagen bearbeiten.
    // Durch die Methode addShortcutCommandItem($id, $status) wird gewährleistet dass jede ID nur einmal pro Vorgang geschaltet wird.
    $SHORTCUTS_URL_COMMAND = "";
    
    $dbRegeln = new DbTable($_SESSION['config']->DBCONNECT,
                            "homecontrol_regeln",
                            array("id", "name", "reverse_switch", "beschreibung"),
                            "Id, Name, Reverse-Switch, Beschreibung",
                            "",
                            "",
                            "id IN (SELECT trigger_id FROM homecontrol_term WHERE term_type IN (3,4) and trigger_type = 1  AND trigger_jn='J') ");
    
    foreach($dbRegeln->ROWS as $regelRow){
        $regelId = $regelRow->getNamedAttribute("id");
        $SHORTCUTS_URL_COMMAND = checkAndSwitchRegel($regelId, $SHORTCUTS_URL_COMMAND, $regelRow->getNamedAttribute("reverse_switch"));
    }
    
    return $SHORTCUTS_URL_COMMAND;
}


function checkLastTime(){
    $lastStd = $_SESSION['lastTimerStd'];
    $lastMin = $_SESSION['lastTimerMin'];
    $currStd = date("H");
    $currMin = date("i");
    
    if($lastStd == $currStd){
        return $lastMin < $currMin;
    } else {
        return $lastStd < $currStd;
    }
}

function exitRunOnce(){
  exit("Timer wurde bereits gestartet!");
}



?>
