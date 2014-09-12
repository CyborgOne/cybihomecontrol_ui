<?php

  /**
  * colors.php
  * 
  * Diese Datei ruft die aktuellen Farbwerte aus der Datenbanktablle COLORS ab 
  * und stellt sie in einem Array $COLORS zur Verfügung.
  *
  * ( Datei einfach mit include("config/colors.php") einbinden und $COLORS['name'] abrufen )
  */

  $colorTable = new DbTable($_SESSION['config']->DBCONNECT, "colors");

  foreach($colorTable->ROWS as $row){
    $COLORS[$row->getNamedAttribute("name")] = $row->getNamedAttribute("farbwert");
  }

?>