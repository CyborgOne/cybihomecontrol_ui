<?PHP
  
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
               echo "<br><font color='green'><b>schalte ".$id>=0?$id:($id*-1)." ".($status=="on"?"ein":"aus")."</b></font>";
            } 
          } else {
             echo "<br><font color='red'>KEINE VERBINDUNG<br><b>Vorgang abgebrochen!</b></font>";
             break;
          }
        }
        echo "<br>";
      }
      ob_flush();
      sleep(.3);
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


  
?>