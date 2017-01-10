<?php

/**
 * Die Klasse IndexHandler verwaltet Standard-Aktionen die in index.php behandelt werden
 * 
 * Standard-FunktionalitÃÂÃÂÃÂÃÂ¤ten werden mit dem ÃÂÃÂÃÂÃÂbergabeparameter ?do=  aufgerufenzz   
 *  
 * @author Daniel Scheidler 
 * @copyright Mai 2008
 */

class IndexHandler{
  var $CONFIG;

  function IndexHandler(&$conf){
	$this->CONFIG =& $conf;
  }


//----------------------------------------
//  Alle Dorm-Objekte mit Namen "do" 
//  werden in dieser Methode behandelt
//----------------------------------------
  function DoAction($do){
    
    // Benutzer-Aktionen ( Login / Logout / Activate )
    if ($do == "logout"){
 	  $this->CONFIG->CURRENTUSER->logout();
 	  $_SESSION['runLink'] = "start";
	  $_SESSION['MENU_PARENT'] = ""; 
	  $_SESSION['runLink'] = "start";
    }
      
    if($do == "login"){
	   $this->CONFIG->CURRENTUSER->login($_REQUEST['user'], $_REQUEST['pw']);
    } 
   
  
    if($do == "Activate" && strlen($_REQUEST['userActivate'])>0 && strlen($_REQUEST['ActivationCode'])>0 ){
        $USERSTATUS = new UserStatus($_SESSION['config']->CURRENTUSER, -1, -1);
        $USERSTATUS->activateUser($_REQUEST['userActivate'] , $_REQUEST['ActivationCode'] );
    }



    
  }

//----------------------------------------



    
}

?>