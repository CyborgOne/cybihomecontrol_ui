<?PHP

/**
 *  Script für Minütigen Cron-Job um Zeitgesteuerte Schaltvorgänge durchzuführen
 *
 *  (c) by Daniel Scheidler   -   September 2014
 */
include ("functions/homecontrol_functions.php");

$con = new mysqli('localhost', 'homecontrol', 'CybiHome82', 'homecontrol');
if (!$con) {
    echo "Failed to connect to MySQL: ";
    return;
}

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


// Aktueller Wochentag muss übereinstimmen
$whereStmtCurrCron = strtolower($currentDayName)."='J'";

// Aktuelle Uhrzeit muss übereinstimmen
$whereStmtCurrCron .= " and stunde=".$currentStd." and minute=".$currentMin;

// Betroffene Cron-Einträge selektieren
$sql = "SELECT id, name, beschreibung FROM homecontrol_cron WHERE ".$whereStmtCurrCron;
$result = mysqli_query($con, $sql);

//echo "Aktuelle Cron Anzahl: ".mysqli_num_rows($result)."<br><br>";
if (mysqli_num_rows($result) > 0) {
    echo "RUN HOMECONTROL-CRON: ".$currentDayName." ".$currentStd.":".$currentMin."\n";

    while ($row = mysqli_fetch_array($result)) {
        if (isCronPaused($con,$row['id'])) {
            deleteCronPause($con,$row['id']);
        } else {
            $shortcutUrl = getShortcutSwitchKeyForCron($con, $row['id']);
            echo $shortcutUrl;

            switchShortcut($arduinoUrl, $shortcutUrl);
        }
    }
}

// ------------------------------

mysqli_close($con);

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
