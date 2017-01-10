<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

// Dateiname: init.php
  $url =  parse_url(__FILE__);
  $currentInitPath = dirname($url['path']);
  if(substr($currentInitPath,strlen($currentInitPath)-1) != "/" && strlen($currentInitPath)>1){
    $currentInitPath .= "/";
  }
  
//--------------------------------------------------
// Initialisierung der Homepage
//--------------------------------------------------
  include($currentInitPath."ClassLoader.php");

//-------------------------  
// GLOBALE FUNKTIONEN LADEN
//-------------------------  
  //include($currentInitPath."functions/global.php");
  $FUNCTLOADER = new ClassLoader($currentInitPath."functions", false);
  $FUNCTLOADER->loadAllClasses();
//-------------------------  
// GRUNDKONFIGURATION LADEN
//-------------------------
  include($currentInitPath."config/public_vars.php");

  include($currentInitPath."config/gb.php");
  include($currentInitPath."config/news.php");

  
//-------------------------  
// TEMPLATES LADEN
//-------------------------  
  include($currentInitPath."templates/Templates.php");



//-------------------------  
// KLASSEN LADEN /ERSTELLEN
//-------------------------  
  $CLASSLOADER = new ClassLoader($currentInitPath."classes", $PUBLICVARS['classes_autoupdate']);
  $CLASSLOADER->loadAllClasses();

//Session darf erst gestartet werden wenn alle Klassen geladen sind.
  if(!isset($_SESSION)){
    session_start();
  } 
    // Letzte Aktion ist mehr als X Sekunden her.
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $_SESSION['config']->PUBLICVARS['sessionDauer'])) {
      session_unset();     // unset $_SESSION variable for the run-time 
      session_destroy();   // destroy session data in storage
      session_start();
    }
  

  $_SESSION['LAST_ACTIVITY'] = time(); 

  $COLORS = array();





//-------------------------  
// CONFIG LADEN / ERSTELLEN
//-------------------------  
  if(!isset($_SESSION['config']) ){

  // CONFIG MIT DBCONNECTION ETC ERSTELLEN
    $CONFIG = null;
    $CONFIG = new Config();
    $_SESSION['mainpanelwidth'] = 800;
    $_SESSION['config'] =& $CONFIG;

    $CONFIG->CLASSPATH =& $currentInitPath."classes/";
    $_SESSION['classpath'] =& $CONFIG->CLASSPATH;

    $CONFIG->DBCONNECT = new DbConnect($DBHOST,$DBUSER,$DBPASS,$DBNAME); 
    $_SESSION['dbconnect'] =& $CONFIG->DBCONNECT;

    $CONFIG->CURRENTUSER = new User(); 
    $_SESSION['currentuser'] =& $CONFIG->CURRENTUSER;

    $CONFIG->LOG = new Log($CONFIG->DBCONNECT);
    $_SESSION['log'] = $CONFIG->LOG;


    include($currentInitPath."config/colors.php");

    $CONFIG->COLORS = $COLORS;
    $CONFIG->GB_CONFIG = $GB_CONFIG;
    $CONFIG->News_CONFIG = $News_CONFIG;
    $CONFIG->PUBLICVARS = $PUBLICVARS;
    $CONFIG->MAINPATH = $currentInitPath;
    
    $CONFIG->IMAGECACHE = new ImageCache();
  } else {
  // CONFIG AUS SESSION LADEN

    include($currentInitPath."config/colors.php");
    
    $_SESSION['config']->PUBLICVARS = $PUBLICVARS;
    $_SESSION['config']->COLORS = $COLORS;

    $CONFIG = $_SESSION['config'];
  }


  if(!isset($_SESSION['tmp'])){
     $_SESSION['tmp'] = array();
  }
  
  $_SESSION['letzteAenderung'] = $CLASSLOADER->getLastChange();
?>