<?PHP
//Filename: Startseite.php

$loginNeed        = getPageConfigParam($_SESSION['config']->DBCONNECT, "loginForSwitchNeed") == "J";
$loginExternOnly  = getPageConfigParam($_SESSION['config']->DBCONNECT, "loginExternOnly") == "J";
$loginOK          = ($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->CURRENTUSER->STATUS == "user");

$clientIP = explode(".", $_SERVER['REMOTE_ADDR']);
$serverIP = explode(".",$_SERVER['SERVER_ADDR']);

if (!$loginNeed || 
      $loginOK  ||                   
      ($loginExternOnly && ($serverIP[0]==$clientIP[0] && $serverIP[1]==$clientIP[1] && $serverIP[2]==$clientIP[2]))
   ) {

    $hcMap = new HomeControlMap(false, "MOBILE");
    $hcMap->show();
            
} else {
       
   /* ------------------------------------
      BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;
     
    $USERSTATUS = new UserStatus($USR, -1, -1);
    
    $tbl = new Table( array("") );
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute( 0, $USERSTATUS );
    $tbl->addRow( $r );
    
    $tbl->show();
    /* --------------------------------- */

}


?>