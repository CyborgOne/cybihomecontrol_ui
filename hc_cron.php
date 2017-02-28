<?PHP

/**
 *  Script f?r Min?tigen Cron-Job um Zeitgesteuerte Schaltvorg?nge durchzuf?hren
 *
 *  (c) by Daniel Scheidler   -   September 2014
 */
include ("init.php");
$arduinoUrl = "";

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

if (!checkAction("cron_" . $currentDayName . " " . date("d.M.Y") . " - " . $currentStd . ":" . $currentMin)) {
    echo "cron_" . $currentDayName . " " . date("d.M.Y") . ":" . $currentStd . ":" . $currentMin . " --- bereits ausgef&uuml;hrt\n";
    return;
}
echo "Check: cron_" . $currentDayName . " " . date("d.M.Y") . ":" . $currentStd . ":" . $currentMin . "\n";

// Aktueller Wochentag muss ?bereinstimmen
$whereStmtCurrCron = strtolower($currentDayName) . "='J'";

// Aktuelle Uhrzeit muss ?bereinstimmen
$whereStmtCurrCron .= " and stunde=" . $currentStd . " and minute=" . $currentMin;

// Betroffene Cron-Eintr?ge selektieren
$tblCrons = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_cron", array("*"), "", "", "", $whereStmtCurrCron);

$shortcutUrls = array();
if ($tblCrons->getRowCount() > 0) {
    echo "\nRUN HOMECONTROL-CRON: " . $currentDayName . " " . $currentStd . ":" . $currentMin . " (" . time() . ")\n";
    foreach ($tblCrons->ROWS as $row) {
        echo "</br>" . $row->getNamedAttribute("name");
        if (isCronPaused($_SESSION['config']->DBCONNECT, $row->getNamedAttribute('id'))) {
            deleteCronPause($_SESSION['config']->DBCONNECT, $row->getNamedAttribute('id'));
        } else {
            $cron = new HomeControlCron($row);
            $cron->switchCron(false);
        }
    }
}

// ------------------------------



function isCronPaused($con, $id) {
    $sql = "SELECT 'X' FROM homecontrol_cron_pause WHERE cron_id = " . $id;
    $result = $con->executeQuery($sql);
    return mysql_num_rows($result) > 0;
}


function deleteCronPause($con, $id) {
    $sql = "DELETE FROM homecontrol_cron_pause WHERE cron_id = " . $id;
    $result = $con->executeQuery($sql);
}

?>