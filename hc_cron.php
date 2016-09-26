<?PHP

/**
 *  Script f?r Min?tigen Cron-Job um Zeitgesteuerte Schaltvorg?nge durchzuf?hren
 *
 *  (c) by Daniel Scheidler   -   September 2014
*/
include("init.php");
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

if(!checkAction("cron_".$currentStd.":".$currentMin.":".$currentDayName)){
    echo "cron_" .$currentDayName ." " .date("d.M.Y") .":" .$currentStd .":" .$currentMin. " --- bereits ausgef&uuml;hrt\n";
    return;
}
echo "Check: cron_" .$currentDayName ." " .date("d.M.Y") .":" .$currentStd .":" .$currentMin ."\n";

if(strlen($_SESSION['config']->PUBLICVARS['gmailAdress'])>0 && strlen($_SESSION['config']->PUBLICVARS['gmailAppPassword'])>0 ){
  $in    = connectToGmailInbox($_SESSION['config']->PUBLICVARS['gmailAdress'], $_SESSION['config']->PUBLICVARS['gmailAppPassword']);
  $mails = getMailsFromGmailInbox($in);
  $unreadMails = getEmailCount($in, $mails, true);
  closeGmailInbox($in);
//  echo "Ungelesene Mails: ".$unreadMails."\n";
  
  // Anzahl ungelesener Mails in Sensor-Tabelle speichern
  if(checkSensorInputMissingValues()){
     refreshSensorValue($_SESSION['config']->DBCONNECT, 999999999, $unreadMails);
  }
}

// Aktueller Wochentag muss ?bereinstimmen
$whereStmtCurrCron = strtolower($currentDayName)."='J'";

// Aktuelle Uhrzeit muss ?bereinstimmen
$whereStmtCurrCron .= " and stunde=".$currentStd." and minute=".$currentMin;

// Betroffene Cron-Eintr?ge selektieren
$sql = "SELECT id, name, beschreibung FROM homecontrol_cron WHERE ".$whereStmtCurrCron;
$result =  $_SESSION['config']->DBCONNECT->executeQuery($sql);

//echo "Aktuelle Cron Anzahl: ".mysqli_num_rows($result)."<br><br>";
$ts = isset($_REQUEST['tmstmp'])?$_REQUEST['tmstmp']:"";
if (mysql_num_rows($result) > 0 ) {
    echo "\nRUN HOMECONTROL-CRON: ".$currentDayName." ".$currentStd.":".$currentMin."-".time()."\n";

    $shortcutUrls = array();
    while ($row = mysql_fetch_array($result)) {
        if (isCronPaused($_SESSION['config']->DBCONNECT, $row['id'])) {
            deleteCronPause($_SESSION['config']->DBCONNECT, $row['id']);
        } else {
            $shortcutUrl = getShortcutSwitchKeyForCron($_SESSION['config']->DBCONNECT, $row['id']);
            
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

foreach($shortcutUrls as $shortcutUrl){
  echo "Switch: ".$shortcutUrl."\n\n";
  switchShortcut($arduinoUrl, $shortcutUrl, $_SESSION['config']->DBCONNECT);
}

// ------------------------------


function isCronPaused($con,$id) {
    $sql = "SELECT 'X' FROM homecontrol_cron_pause WHERE cron_id = ".$id;
    $result = $con->executeQuery($sql);
    return mysql_num_rows($result) > 0;
}


function deleteCronPause($con,$id) {
    $sql = "DELETE FROM homecontrol_cron_pause WHERE cron_id = ".$id;
    $result = $con->executeQuery($sql);
}

?>