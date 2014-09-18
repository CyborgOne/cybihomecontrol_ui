<?PHP
/**
 *  Script für Minütigen Cron-Job um Zeitgesteuerte Schaltvorgänge durchzuführen
 *
 *  (c) by Daniel Scheidler   -   September 2014
 */

    $con = new mysqli('localhost', 'homecontrol', 'CybiHOME82', 'homecontrol');
    if (!$con) {
      echo "Failed to connect to MySQL: ";
      return;
    }

// ------------------------------

    $timeAdditional = 2;  // Der Wert müsste eigentlich aus der PageConfig Tabelle geholt werden

    $currentDayNumber =  date('w', strtotime('today'));
    $currentStd =  date('G', strtotime('now')) + $timeAdditional;
    $currentMin =  date('i', strtotime('now'));
    $currentDayName = "weekDay";
    
    switch($currentDayNumber){
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
  //  $whereStmtCurrCron .= " and stunde=" .$currentStd ." and minute=" .$currentMin;

    $sql = "SELECT id, name, beschreibung FROM homecontrol_cron WHERE " .$whereStmtCurrCron;
    $result = mysqli_query($con, $sql);

    echo "Aktuelle Cron Anzahl: ".mysqli_num_rows($result)."<br><br>";

    while($row = mysqli_fetch_array($result)) {
      echo $row['id'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row['name'];
      echo "<br>";
      
      getAllItems($con, $row['id']);
      echo "<br><br>";

    }

// ------------------------------

mysqli_close($con);


function getAllItems($con, $id){
    $sqlItems = "SELECT id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off FROM homecontrol_cron_items WHERE cron_id=" .$id;
    $resultItems = mysqli_query($con, $sqlItems);

    echo "Aktuelle Items Anzahl: (Cron: " .$id .") " .mysqli_num_rows($resultItems)."<br>";

    while($row = mysqli_fetch_array($resultItems)) {
      echo "- " .$row['id'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row['name']."<br>";
      
    }
}

?>