<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

/**
* Das Bilderbuch (Bilder-Ordner = /pics/bilderbuch/)
*/

    $currentPath  =  dirname($_SERVER['SCRIPT_NAME']);
    
    
    if(substr($currentPath,strlen($currentPath)-1) != "/" && strlen($currentPath)>1){
      $currentPath .= "/";
    }

     
  	// Endet DocumentRoot mit einem Slash? 
  	if(substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['DOCUMENT_ROOT'])-1, 1) != "/" ){
	  // FÃ¼hrendes Slash notfalls hinzufÃ¼gen
	  	if(substr($currentPath, 0, 1) == "/"){
	      $currentPath = $currentPath;
	    } else {
		  $currentPath = "/".$currentPath;	
		}
	} else {
	  // FÃ¼hrendes Slash notfalls entfernen
	  	if(substr($currentPath, 0, 1) == "/"){
	      $currentPath = substr($currentPath, 1);
	    } else {
		  $currentPath = $currentPath;	
		}
	}
    $bb = new Bilderbuch($_SERVER['DOCUMENT_ROOT'] .$currentPath ."cam_pics");
    $bb->setMaximumLines(30);
    $bb->show();

?>