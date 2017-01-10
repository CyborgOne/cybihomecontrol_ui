<?php

/**
 * @author Daniel Scheidler
 * @copyright April 2008
 */


  $actionLog = new DbTable($_SESSION['config']->DBCONNECT, "action_log", array("sessionid", "userid", "zeit", "request_dump") );
  
  $uId = $_SESSION['config']->CURRENTUSER->USERID;
  if (strlen($uId)<=0) {
	$uId = -1;
  }
  $rowArray = array(  $_REQUEST['CySess'], 
                      $uId,
					  time(),
					  getArrayText($_REQUEST));
  
  //Wenn keine Login-Informationen enthalten sind...
  if(! (strpos(" ".getArrayText($_REQUEST) ,"pw")>0 && strpos(" ".getArrayText($_REQUEST) ,"user")>0 )){
    $actionLog->insertRowByArray($rowArray);
  }
  
  
  // Daten die älter als eine Woche sind entfernen
  $sql = "DELETE FROM action_log WHERE zeit < ".(time() - 129600);
  $_SESSION['config']->DBCONNECT->executeQuery($sql);
  
  




  function getArrayText($arr){
    $ret = "";
	foreach($arr as $key=>$val){
		$ret .= $key ."\n" .htmlspecialchars ($val , ENT_QUOTES) ."\n\n";
	}	
	return $ret;
  }

?>