<?php
//FileNAME: Log.php

class Log extends Object{
  var $logTable;
  var $DBCONNECT;

  function Log($con){
    $this->DBCONNECT= $con;
    $this->logTable = new LogTable($this->DBCONNECT);
  }

  function add($text){
     $lt =  $this->logTable;
     $lt->insert($text);
  }


}

?>