<?php
// Dateiname: LogTable.php

/*
	 id  	 	int(11)  
	 Date  	varchar(25)
	 User  	varchar(30)
	 Ip  		varchar(20)
	 Action  	varchar(150)
*/

class LogTable extends DbTable {
  var $DBCONNECT;

  function LogTable($con){   //Konstruktor
	$this->DBCONNECT = $con;

       $this->TABLENAME = "log";

	$cNames = array("id", "Date", "User", "Ip", "Action");
	
	$this->setColNames($cNames);
  }


  function insert($text){
    $text = str_replace("'"," ", $text);   
  
    $sql = "INSERT INTO `log` SET `Date`     =  current_date, " 
                              ." `User`    = '".@$_SESSION['currentuser']->USERNAME
                             ."', Ip     = '" .@$_SERVER['REMOTE_ADDR'] 
                             ."', Action = '" .$text ."' ";
    
    // $this->DBCONNECT->executeQuery($sql);
  }


}
?>
