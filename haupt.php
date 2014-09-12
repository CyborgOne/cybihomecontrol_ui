<?php
  include("header.php");
  
  
  // Kopftexte und Nachrichten-Prüfung werden in DivByInclude  verwaltet
  
  if(isset($_SESSION['mainpage']) && strlen($_SESSION['mainpage'])>0){
    include($_SESSION['mainpage']);
  }

 

?>