<?php
  include("header.php");
  

  if(isset($_SESSION['mainpage']) && strlen($_SESSION['mainpage'])>0){
    include($_SESSION['mainpage']);
  }

 

?>