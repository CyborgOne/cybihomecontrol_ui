<?php
include('header.php');
   
  /* ------------------------------------
    BENUTZERSTATUS ANZEIGEN
  ------------------------------------ */
  if($USER_STATUS['aktiv']){
    $CONFIG->CURRENTUSER->showStatus($USER_STATUS['xpos'],$USER_STATUS['ypos']);
  }
  /* --------------------------------- */



include("foot.php");
?>