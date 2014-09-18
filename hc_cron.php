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

    $sqlA = "SELECT id, name, value FROM pageconfig WHERE name = 'arduino_url' ";
    $resultA = mysqli_query($con, $sqlA);
    $rA = mysqli_fetch_array($resultA);
    $arduinoUrl = "http://".$rA['value'];
    
    // ------------------------------

    $timeAdditional = 2;  // Der Wert müsste eigentlich aus der PageConfig Tabelle geholt werden

    $currentDayNumber =  date('w', strtotime('today'));
    $currentStd =  date('G', strtotime('now')) + $timeAdditional;
    if($currentStd>23){
        $currentStd = $currentStd-24;
        $currentDayNumber=$currentDayNumber+1;
    }
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
    $whereStmtCurrCron .= " and stunde=" .$currentStd ." and minute=" .$currentMin;

    // Betroffene Cron-Einträge selektieren
    $sql = "SELECT id, name, beschreibung FROM homecontrol_cron WHERE " .$whereStmtCurrCron;
    $result = mysqli_query($con, $sql);

    //echo "Aktuelle Cron Anzahl: ".mysqli_num_rows($result)."<br><br>";
    if(mysqli_num_rows($result)>0){
      echo "RUN HOMECONTROL-CRON: " .$currentDayName ." " .$currentStd.":".$currentMin."\n";

      while($row = mysqli_fetch_array($result)) {
        $shortcutUrl =  getShortcutSwitchKeyForCron($con, $row['id']);
        echo $shortcutUrl;
      
        switchShortcut($arduinoUrl, $shortcutUrl);
      }
    }
    
// ------------------------------

    mysqli_close($con);

// ------------------------------

/**
 * Liefert den URL-String zum schalten aller Items  
 * 
 * Beispiel:
 * ?switchShortcut=10-off;100-off;101-off;104-off;91-off;103-off;2-off;110-off;111-off;112-off;113-off;114-off;3-off;120-off;121-off;  
 */
function getShortcutSwitchKeyForCron($con, $cronId){
    $sqlItems = "SELECT id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off FROM homecontrol_cron_items WHERE cron_id=" .$cronId;
    $resultItems = mysqli_query($con, $sqlItems);
    $shortcutUrl = ""; //" "?switchShortcut=";
    
    while($row = mysqli_fetch_array($resultItems)) {
      // Wenn ConfigId angegeben ist, diese hinzufügen
      $whereStmt = "";
      if( strlen($row['config_id'])>0 ){
          $whereStmt .= "id=" .$row['config_id'];
      } else {
        // Ansonsten die entsprechenden Geräte auswählen und alle hinzufügen
        if( strlen($row['art_id'])>0 ){
          if($whereStmt!=""){
            $whereStmt .= " AND ";
          }          
          $whereStmt .= "control_art=" .$row['art_id'];
        }
        
        if( strlen($row['zimmer_id'])>0 ){
          if($whereStmt!=""){
            $whereStmt .= " AND ";
          }          
          $whereStmt .= "zimmer=" .$row['zimmer_id'];
        }
        
        if( strlen($row['etagen_id'])>0 ){
          if($whereStmt!=""){
            $whereStmt .= " AND ";
          }          
          $whereStmt .= "etage=" .$row['etagen_id'];
        }
      }

      $sqlSubItems = "SELECT id, funk_id, funk_id2 FROM homecontrol_config WHERE " .$whereStmt;
      $resultSubItems = mysqli_query($con, $sqlSubItems);
      while($rowSub = mysqli_fetch_array($resultSubItems)) {
        $shortcutUrl .= $rowSub['funk_id'] ."-" . (strlen($row['on_off'])>0?$row['on_off']:"off") .";";
      }      
    }
    
    return $shortcutUrl;
}




  function switchShortcut($arduinoUrl, $shortcutUrl){
    $switchStatusCheck = true;
    ob_implicit_flush(true);

    // Nach Semikolon trennen, 
    // um die einzelnen zu schaltenden Elemente zu erhalten
    $fullConfigArray = explode(";",$shortcutUrl);    
    foreach($fullConfigArray as $cfg){
      // Nach Minus trennen um ID und STATUS zu erhalten.
      $tmp    = explode("-",$cfg);
      if(count($tmp)==2){
        $id     = $tmp[0];
        $status = $tmp[1];
     

        if( strlen($id)>0 && $id>0 ){
          $status = $status=="on"?"on":"off";
  
          // Wenn ausgeschaltet werden soll,
          // negative ID übergeben
          if($status == "off"){
            $id = $id*(-1);
          }
  
//          echo "<br>Switch-URL: " .$arduinoUrl."?schalte&" .$id;
          $urlArr = parse_url($arduinoUrl);
          $host = $urlArr['host'];

          $check = @fsockopen($host, 80); 
          If ($check) { 
            $retVal = file_get_contents( $arduinoUrl."?schalte&" .$id );
 
            if(strpos(substr($retVal,0,50), "Warning")>0){
               $switchStatusCheck = false;
               echo "<b>Vorgang auf Grund eines unerwarteten Fehlers abgebrochen!</b><br><br>".$retVal;
               break;
            } else {
               echo "<br>\n<font color='green'><b>schalte ".$id>=0?$id:($id*-1)." ".($status=="on"?"ein":"aus")."</b></font>";
            } 
          } else {
             echo "<br>\n<font color='red'>KEINE VERBINDUNG mit " .$host ."<br><b>Vorgang abgebrochen!</b></font>";
             break;
          }
        }
        echo "<br>\n";
      }
      ob_flush();
      sleep(.2);
    }
  }



?>