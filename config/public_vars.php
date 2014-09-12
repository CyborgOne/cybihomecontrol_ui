<?php
// Dateiname: public_vars.php
$PUBLICVARS = array();
/* ---------------------------------------
    Globale Variablen-Definitionen 
--------------------------------------- */
  $url =  parse_url(__FILE__);
  $currPath = dirname($url['path']);
  if(substr($currPath,strlen($currPath)-1) != "/" && strlen($currPath)>1){
    $currPath .= "/";
  }

include($currPath."dbConnect.php");
$dc                 = new DbConn($DBHOST,$DBUSER,$DBPASS,$DBNAME);



// Muss immer neu geladen werden,
// da Session hier noch nicht gestartet werden kann weil Klassen noch nicht geladen sind!
  $sql = "select name, value from pageconfig ";
  $res = $dc->executeQuery($sql);

  while($row = mysql_fetch_array($res)){
	$PUBLICVARS[$row['name']] = $row['value'];
  }














class DbConn 
{
  var $host;
  var $user;
  var $pass;
  var $dbname;
  var $db_link = false;


  function DbConn($DBHOST,$DBUSER,$DBPASS,$DBNAMEX){   //Konstruktor
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
     $this->db_link = @mysql_pconnect($this->host,$this->user,$this->pass) or die ("Datenbankverbindung *".$this->host."|".$this->user."|".$this->pass."* nicht möglich!");
     $this->selectDatabase($this->dbname);
  }


  function connect($host,$user,$pass,$dbname){ //baut die verbindung auf
     $this->db_link = @mysql_pconnect($host,$user,$pass) or die ("Datenbankverbindung nicht möglich!");
     $this->selectDatabase($dbname);
  }


  function selectDatabase($dbname){  // wählt die datenbank
    @mysql_select_db($dbname) or die ("Datenbank *" .$dbname ."* konnte nicht ausgewählt werden!");
  }


  function executeQuery($query)  { // sendet einen query
    $res = @mysql_query($query) or die ("Abfrage war ungültig!".mysql_error()."<br> ||".$query."||");
   //echo $query;
    return $res;
  }
}


?>
