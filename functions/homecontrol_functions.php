<?PHP
  
  function getSensorName($id){
    $sql = "SELECT name FROM homecontrol_sensor WHERE id = ".$id;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row['name'];

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

      if(count($tmp)>=2){
        $id     = $tmp[0];
        $status = $tmp[1];
        $dimmer = 0;
        if(count($tmp)==3){
            $dimmer = $tmp[2];
            echo $dimmer;
        }
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
            $retVal = file_get_contents( $arduinoUrl."?schalte=".$id."&dimm=".$dimmer );
 
            if(strpos(substr($retVal,0,50), "Warning")>0){
               $switchStatusCheck = false;
               echo "<b>Vorgang auf Grund eines unerwarteten Fehlers abgebrochen!</b><br><br>".$retVal;
               break;
            } else {
               //echo "<br><font color='green'><b>schalte ".$id>=0?$id:($id*-1)." ".($status=="on"?"ein":"aus")."</b></font>";
            } 
          } else {
             echo "<br><font color='red'>KEINE VERBINDUNG<br><b>Vorgang abgebrochen!</b></font>";
             break;
          }
        }
        //echo "<br>";
      }
      ob_flush();
      sleep(.2);
    }
  }



  function isFunk2Need($art){
    /*$configDb  = new DbTable($_SESSION['config']->DBCONNECT,
                            'homecontrol_art', 
                            array("zweite_funkid_jn") , 
                            "",
                            "",
                            "",
                            "id=".$art);

    $row = $configDb->getRow(1);

    if($row != null){
	return $row->getNamedAttribute("zweite_funk_id") == "J";    
    } */    
    
    return false;
  }
 
 
  
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




function getShortcutSwitchKeyByName($shortcutName){
    $sqlSubItems = "SELECT * FROM homecontrol_shortcutview WHERE lower(shortcut_name)='" .strtolower($shortcutName)."' ORDER BY on_off"; 
    $resultSubItems = mysql_query($sqlSubItems);
    $shortcutUrl = "";
    while($rowSub = mysql_fetch_array($resultSubItems)) {
      $shortcutUrl .= $rowSub['funk_id'] ."-" . (strlen($rowSub['on_off'])>0?$rowSub['on_off']:"off") .";";
    }      
    return $shortcutUrl;
}


function getShortcutSwitchKeyById($con, $shortcutId){
    $sqlSubItems = "SELECT * FROM homecontrol_shortcutview WHERE shortcut_id=" .$shortcutId." ORDER BY on_off"; 
    $resultSubItems = mysql_query($sqlSubItems);
    $shortcutUrl = "";
    while($rowSub = mysql_fetch_array($resultSubItems)) {
      $shortcutUrl .= $rowSub['funk_id'] ."-" . (strlen($rowSub['on_off'])>0?$rowSub['on_off']:"off") .";";
    }      
    return $shortcutUrl;
}



function checkAndSwitchRegel($regelId, $SHORTCUTS_URL_COMMAND, $reverseJN="J"){
    $dbRegelTerms = new DbTable($_SESSION['config']->DBCONNECT,
                                "homecontrol_term",
                                array("*"),
                                "",
                                "",
                                "",
                                "trigger_type=1 AND trigger_id=".$regelId);
    $isValid = true;
    
    // Alle Regel-Bedingungen prüfen
    foreach($dbRegelTerms->ROWS as $rowRegelTerm){
        $validator = new HomeControlTermValidator($rowRegelTerm);
        if (!$validator->isValid()){
            echo $rowRegelTerm->getNamedAttribute("id").": Fail<br/>";
            $isValid = false;
        } else {
            echo $rowRegelTerm->getNamedAttribute("id").": OK<br/>";
        }
    }

    // Wenn alle Bedingungen erfüllt sind,
    // Geräte schalten. Bei reverseJN == "J"
    // negiert schalten.
    if($isValid || $reverseJN=="J"){    
        $sql = "SELECT id, regel_id, config_id, art_id, zimmer_id, etagen_id, funkwahl, on_off " .
            "FROM homecontrol_regeln_items WHERE regel_id=".$regelId . " " .
            "ORDER BY on_off DESC, config_id DESC , zimmer_id DESC , etagen_id DESC ";

        $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        while ($row = mysql_fetch_array($result)) {
            $whereStmt = "";
            $onOff = $isValid?$row["on_off"]:($row["on_off"]=="on"?"off":"on");
            
            if (strlen($row["config_id"]) > 0) {
                $funkId = getConfigFunkId($row["config_id"], $onOff);
                $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($funkId, $onOff, $SHORTCUTS_URL_COMMAND);
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
            
                $resultConfig = $_SESSION['config']->DBCONNECT->executeQuery($sqlConfig);
                while ($rowConfig = mysql_fetch_array($resultConfig)) {
                    $SHORTCUTS_URL_COMMAND = addShortcutCommandItem($rowConfig["funk_id"], $onOff, $SHORTCUTS_URL_COMMAND);
                }
            }
        }
    }

    echo $SHORTCUTS_URL_COMMAND."<br>";
    
    return $SHORTCUTS_URL_COMMAND;
}





/**
 * Wenn ID nicht schon enthalten ist, Einstellungs-Werte übernehmen
 */
function addShortcutCommandItem($funkId, $status, $command) {

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