<?PHP
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

if(!checkAction("cron_" .$currentDayName ." " .date("d.M.Y") ." - " .$currentStd .":" .$currentMin)){
    echo "mail_" .$currentDayName ." " .date("d.M.Y") .":" .$currentStd .":" .$currentMin. " --- bereits ausgef&uuml;hrt\n";
    return;
}

echo "Check: mail_" .$currentDayName ." " .date("d.M.Y") .":" .$currentStd .":" .$currentMin ."\n";

if(strlen($_SESSION['config']->PUBLICVARS['gmailAdress'])>0 && strlen($_SESSION['config']->PUBLICVARS['gmailAppPassword'])>0 ){
  $in    = connectToGmailInbox($_SESSION['config']->PUBLICVARS['gmailAdress'], $_SESSION['config']->PUBLICVARS['gmailAppPassword']);
  $mails = getMailsFromGmailInbox($in);
  $unreadMails = getEmailCount($in, $mails, true);
  closeGmailInbox($in);
 // echo "Ungelesene Mails: ".$unreadMails."\n";
  
  // Anzahl ungelesener Mails in Sensor-Tabelle speichern
  if(checkSensorInputMissingValues()){
     refreshSensorValue($_SESSION['config']->DBCONNECT, 999999999, $unreadMails);
  }
}
?>