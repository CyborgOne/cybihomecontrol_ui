<?php

  /**
  *  function getPageConfigParam ($dbConnect, $ident, $id)
  *  dbConnect: Datenbank Verbindungsobjekt
  *  ident:     Spaltenname -> angeforderter Wert für entsprechenden Parameter der Config
  *  id:        Page-Id -> ID der entsprechenden Homepage (>0 nur bei mehreren pages )
  */ 
  function getPageConfigParam($dbConnect, $ident, $id=0){
    //Liefert den angeforderten Wert aus der Page-Konfiguration zu der entsprechenden Page-ID
      $sql = "select * from pageconfig where name = '" .$ident ."' ";
      $res = $dbConnect->executeQuery($sql);
      $row = mysql_fetch_array($res);
      return $row['value'];    
  }

  function getPageConfigParamRow($dbConnect, $ident, $id=0){
    //Liefert den angeforderten Wert aus der Page-Konfiguration zu der entsprechenden Page-ID
      $sql    = "select *, id rowid from pageconfig where name = '" .$ident ."' ";
      $res = $dbConnect->executeQuery($sql);
      $row = mysql_fetch_array($res);
       return $row;    
  }

  function getRunlinkParam($dbConnect, $runlinkName, $paramName){
    $sql = "SELECT * FROM run_links WHERE name = '" .$runlinkName  ."' ";
    $res = $dbConnect->executeQuery($sql);
    $row = mysql_fetch_array($res);
    return $row[$paramName];   
  }

  function existRunlink($dbConnect, $runlinkName){
    $sql = "SELECT * FROM run_links WHERE name = '" .$runlinkName  ."' ";
    $res = $dbConnect->executeQuery($sql);
    return mysql_numrows($res)>0;
  }

    function checkAction($chkVal){
        $w = "chkVal='".$chkVal."'";
        
        if(isset($_SESSION['config']) && $_SESSION['config']->CURRENTUSER->USERID != null ){
           $w .= " and user_id=".$_SESSION['config']->CURRENTUSER->USERID; 
        }
        $dbTblTest = new DbTable($_SESSION['config']->DBCONNECT, 
                                 "chkActions",
                                 array("*"),
                                 "",
                                 "", 
                                 "id desc",
                                 $w
                                  );

        if ( $dbTblTest->getRowCount() > 0 ) {
            $r = $dbTblTest->getRow(1);

            if ($r->getNamedAttribute("chkVal") == $chkVal ) {
                return false;
            } 
        }
        
        $r = $dbTblTest->createRow();
        $r->setNamedAttribute("user_id", $_SESSION['config']->CURRENTUSER->USERID );
        $r->setNamedAttribute("chkVal", $chkVal);
        $r->insertIntoDB();

        return true;
    }
    





  function getLookupWerte($dbConnect, $tab, $col){
    //Liefert den angeforderten Wert aus der Lookupwerte - Tabelle

    $sql = "select * from lookupwerte where tab_name = '" .$tab ."' and col_name = '" .$col ."' ";
    $res = $dbConnect->executeQuery($sql);

    return $res;    
  }



  function getTimeString($h, $m){
	for($i=strlen($h);$i<2;$i++){
	  $h = "0".$h;
	}
	

 	for($i=strlen($m);$i<2;$i++){
      $m = "0".$m;
	}
	
	return $h.":".$m;
  }



  function getPublicVarArray($dbConnect, $group, $name){
    //Liefert die angeforderten Werte aus der public_vars - Tabelle als Array ($title=>$text)
    $ret = array();

    $sql    = "select * from public_vars where gruppe = '" .$group ."' and name = '" .$name ."' ORDER BY sortnr";
    $res = $dbConnect->executeQuery($sql);
    
    while ($row = mysql_fetch_array($res)){
       $ret[$row['titel']] = $row['text'];
    }

    return $ret;    
  }


   


  /**
   * Wenn das DebugOutput aktiviert ist gibt diese Methode Meldungen aus. 
   * Ansonsten werden diese unterdrückt
   */
    function debugOutput($text){
      if($_SESSION['config']->PUBLICVARS['debugoutput_aktiv'] == "true"){
	    echo "<font color='orange'>" .$text ."</font><br>";
      }
    }


    
    function getHpSystemPath(){
		$currentPath  =  dirname($_SERVER['SCRIPT_NAME']);
		
		if(substr($currentPath,strlen($currentPath)-1) != "/" && strlen($currentPath)>1){
		  $currentPath .= "/";
		}
		
		if(substr($currentPath,0,1) == "/"){
		  $currentPath = substr($currentPath,1);
		}
		
		$ret =  $_SERVER['DOCUMENT_ROOT'].$currentPath;
		
		if(substr($ret, strlen($ret)-1,1) != "/"){
			$ret .= "/";
		}	
		return $ret;	
	}
	


  


    function getColor($name){
      $sql = "SELECT * FROM colors where name = '" .$name ."' ";
      $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
  
      $row = mysql_fetch_array($result);
      return $row['farbwert'];
      

      return $cbArray; 
    }




	
	function getFormatedDate($date, $format="standard"){
	  $ret = $date;
	  if ($format=="standard"){
	    $ret = substr($date,8,2) ."." .substr($date,5,2) ."." .substr($date,0,4);
	  }
	
	  return $ret;
	}
	
	
	/**
	 * Liefert den Kopf-Text des aktuellen Runlinks zurück
	 * 
	 * @return String  Kopftext zum aktuellen Runlink ($_SESSION['runLink'])
	 */
	function getKopfText(){
		if(isset($_SESSION['runLink'])){
	      $sql =  "SELECT * FROM kopftexte where runlink = '" .$_SESSION['runLink'] ."' ";  
		  $sql .= "and (parent='" .$_SESSION['MENU_PARENT']."' or parent is null )";
		  
	      
		  $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	  
	      $row = mysql_fetch_array($result);
	      return $row['text'];
		}
	}
	
	
    
    
  		
	function execShellCommand($cmd){
		echo "<!--";
		$last_line = system($cmd, $retval);
		echo "!>";
		
		if( $retval!=0 ){
			return $retval;    
		} else {
			return true;
		}
		
	}
    
    
    
    function isValidRun( $run ) {
		  $sql  = "SELECT * FROM menu WHERE link LIKE '%" .$run ."' ";
          
          $currStatus = $_SESSION['config']->CURRENTUSER->STATUS;
          
          if($currStatus == "admin"){
            // keine Einschränkung
          } elseif($currStatus == "user"){
            $sql .= " AND(status = 'user' OR status is null OR status = '') ";
            
		  } else {
            $sql .= " AND (status is null  OR status = '')";
              
		  }
      
               
		  $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	  
	      $count = mysql_num_rows($result);
          
	      return $count > 0;
    }
    
    /**
     * Durchsucht die angegebene Datei nach dem 
     * angegebenen Schlagwort (am Zeilenanfang)
     * und liefert den dahinter befindlichen Wert zurück
     */
    function findInFileBehind($path, $searchString){
        $ret = null;
        $file=fopen($path, 'r');
        if($file){
          while (($line = fgets($file))!==false){
            if (substr(trim($line),0,strlen($searchString))==$searchString){
                $ret = trim(substr(trim($line),strlen($searchString)));
            }
          }
        }
        return $ret;
    }
    
?>