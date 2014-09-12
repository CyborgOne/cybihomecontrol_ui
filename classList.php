<?php
   $classes = getClassArray("classes");   

   foreach($classes as $className){
     echo $className .";";
   }

   function getClassArray($path){
    $classArray = array();

    // Aktuelles Verzeichniss laden
    $reloadArray = array();
    $verzeichnis = opendir($path);

    //Alle Dateien im übergebenen Ordner Laden
    while(false !==  ($eintrag = readdir($verzeichnis) ) ){
      if($eintrag!=".." && ($eintrag != ".")){
        if( is_file($path  ."/" .$eintrag) && strpos($eintrag, '.php')>0 ){
          $file = $path  ."/" .$eintrag;
          $classArray[count($classArray)] = $file;
        }
      }
    }
    $verzeichnis = opendir($path);
    while(false !==  ($eintrag = readdir($verzeichnis) ) ){
      if($eintrag!=".." && ($eintrag != ".")){
        if (is_dir($path  ."/" .$eintrag)){
          $ca = getClassArray($path  ."/" .$eintrag);
          foreach($ca as $c){
            $classArray[count($classArray)] = $c;
          }
        }
      }
   }

    closedir($verzeichnis);

    return $classArray;
  }

?>