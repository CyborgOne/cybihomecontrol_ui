<?PHP

/**
 *  Script f�r Min�tigen Cron-Job um Zeitgesteuerte Schaltvorg�nge durchzuf�hren
 *
 *  (c) by Daniel Scheidler   -   September 2014
 */
include ("config/dbConnect.php");
include ("classes/objects/database/DbConnect.php");

include ("functions/global.php");
include ("functions/homecontrol_functions.php");

$con = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
if (!$con) {
    echo "Failed to connect to MySQL: ";
    return;
}

// TODO: eigentlich nicht mehr notwendig
$sqlA = "SELECT id, name, value FROM pageconfig WHERE name = 'arduino_url' ";
$resultA = mysqli_query($con, $sqlA);
$rA = mysqli_fetch_array($resultA);
$arduinoUrl = "http://".$rA['value'];

// ------------------------------


$currentDayNumber = date('w', strtotime('today'));
$currentStd = date('G', strtotime('now'));
if ($currentStd > 23) {
    $currentStd = $currentStd - 24;
    $currentDayNumber = $currentDayNumber + 1;
}
$currentMin = date('i', strtotime('now'));
$currentDayName = "weekDay";

switch ($currentDayNumber) {
    case 0:
        $currentDayName = "Sonntag";
        break;
    case 1:
        $currentDayName = "Montag";
        break;
    case 2:
        $currentDayName = "Dienstag";
        break;
    case 3:
        $currentDayName = "Mittwoch";
        break;
    case 4:
        $currentDayName = "Donnerstag";
        break;
    case 5:
        $currentDayName = "Freitag";
        break;
    case 6:
        $currentDayName = "Samstag";
        break;
}


// Aktueller Wochentag muss �bereinstimmen
$whereStmtCurrCron = strtolower($currentDayName)."='J'";

// Aktuelle Uhrzeit muss �bereinstimmen
$whereStmtCurrCron .= " and stunde=".$currentStd." and minute=".$currentMin;

// Betroffene Cron-Eintr�ge selektieren
$sql = "SELECT id, name, beschreibung FROM homecontrol_cron WHERE ".$whereStmtCurrCron;
$result = mysqli_query($con, $sql);

//echo "Aktuelle Cron Anzahl: ".mysqli_num_rows($result)."<br><br>";
if (mysqli_num_rows($result) > 0) {
    echo "\nRUN HOMECONTROL-CRON: ".$currentDayName." ".$currentStd.":".$currentMin."\n";

    $shortcutUrls = array();
    while ($row = mysqli_fetch_array($result)) {
        if (isCronPaused($con, $row['id'])) {
            deleteCronPause($con,$row['id']);
        } else {
            $shortcutUrl = getShortcutSwitchKeyForCron($con, $row['id']);
            
            $url =  parse_url(__FILE__);
            $currPath = dirname($url['path']);
            if(substr($currPath,strlen($currPath)-1) != "/" && strlen($currPath)>1){
                $currPath .= "/";
            }
            $shortcutUrls[count($shortcutUrls)]=$shortcutUrl;
        }
    }
}

// ------------------------------

mysqli_close($con);

$dc = new DbConnect($DBHOST, $DBUSER, $DBPASS, $DBNAME);

foreach($shortcutUrls as $shortcutUrl){
  echo "Switch: ".$shortcutUrl."\n\n";
  switchShortcut($arduinoUrl, $shortcutUrl, $dc);
}

// ------------------------------


function isCronPaused($con,$id) {
    $sql = "SELECT 'X' FROM homecontrol_cron_pause WHERE cron_id = ".$id;
    $result = mysqli_query($con, $sql);
    return mysqli_num_rows($result) > 0;
}


function deleteCronPause($con,$id) {
    $sql = "DELETE FROM homecontrol_cron_pause WHERE cron_id = ".$id;
    $result = mysqli_query($con, $sql);
}

?>
