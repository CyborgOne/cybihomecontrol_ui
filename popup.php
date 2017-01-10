<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

 include("header_popup.php");
 
 if( (!isset($_REQUEST['popup']) || strlen($_REQUEST['popup'])==0) && 
     (!isset($_REQUEST['popupRunLink']) || strlen($_REQUEST['popupRunLink'])==0) ){
	$e = new Error("Übergabeparameter", "Übergabeparameter \$_REQUEST['popup'] wurde nicht gefunden! ");
 }

 echo "<center>";
 
 if((isset($_REQUEST['popup']) && strlen($_REQUEST['popup'])!=0)){
   include($_REQUEST['popup']);
 } else {

   $RUNLINKS = new RunLinks($_SESSION['config']->DBCONNECT, "mainpage");
	
   $run = $RUNLINKS->getLinkByName($_REQUEST['popupRunLink']);
   include($run);	
 }
 
 echo "</center>";

 include("foot_index.php");
 
?>