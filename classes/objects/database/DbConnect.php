<?php
// Dateiname: DbConnect.php

/* **********************************************************************
  Datenbankverbingungs-Objekt 
  welches die Datenbankverbindung anhand der in "/config.php" 
  eingestellten Parameter herstellt.

********************************************************************** */

class DbConnect 
{
  var $host;
  var $user;
  var $pass;
  var $dbname;
  var $db_link = false;


  function DbConnect($DBHOST,$DBUSER,$DBPASS,$DBNAMEX){   //Konstruktor
    $this->host = $DBHOST;
    $this->user = $DBUSER;
    $this->pass = $DBPASS;
    $this->dbname = $DBNAMEX;

    $this->connect($this->host,$this->user,$this->pass,$this->dbname);
  }

  function setUser($usrName){
    $this->user = $usrName;
  }

  function setPassword($pwd){
    $this->pass = $pwd;
  }

  function setHost($dbhost){
    $this->host= $dbhost;
  }

  function setDatabase($db){
    $this->dbname= $db;
  }



  function reconnect(){ //baut die verbindung auf
     $this->db_link = @mysql_pconnect($this->host,$this->user,$this->pass) or die ("Datenbankverbindung *".$this->host."|".$this->user."|".$this->pass."* nicht mÃÂÃÂÃÂÃÂ¶glich!");
     $this->selectDatabase($this->dbname);
  }


  function connect($host,$user,$pass,$dbname){ //baut die verbindung auf
     $this->db_link = @mysql_pconnect($host,$user,$pass) or die ("Datenbankverbindung nicht mÃÂÃÂÃÂÃÂ¶glich!");
     $this->selectDatabase($dbname);
  }


  function selectDatabase($dbname){  // wÃÂÃÂÃÂÃÂ¤hlt die datenbank

	if(isset($_SESSION['config']) && isset($_SESSION['config']->CURRENTUSER) && $_SESSION['config']->CURRENTUSER->STATUS == "admin"){
		@mysql_select_db($dbname) or die ("Datenbank *" .$dbname ."* konnte nicht ausgewÃÂÃÂÃÂÃÂ¤hlt werden!");
	} else {
        @mysql_select_db($dbname) or die ("Fehler beim auswÃÂÃÂÃÂÃÂ¤hlen der Datenbank!");
    }

  }


  function executeQuery($query)  { // sendet einen query
    
	$err_msg = "Fehler bei der Datenbankabfrage!";
	
	if(isset($_SESSION['config']) && isset($_SESSION['config']->CURRENTUSER) && 
	   $_SESSION['config']->CURRENTUSER->STATUS == "admin"){
	  // 	echo $query;
		$res = @mysql_query($query) or die ($err_msg ."<br><br>".mysql_error()."<br> ||".$query."||");
	} else {
	   
        $res = @mysql_query($query);// or die ($err_msg ."<br><br>".mysql_error()."<br> ||".$query."||");
    }
 
    debugOutput($query);
    return $res;
  }

  



}
?>
