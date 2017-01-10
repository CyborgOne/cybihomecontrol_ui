<?php
// Dateiname: UserTable.php

/*
	 id   			int(11)
	 Vorname  		varchar(50) 
	 Nachname  		varchar(50) 
	 Name  		varchar(100)
	 Geburtstag  		date 	  	
	 Strasse  		varchar(50) 	
	 Plz  			varchar(50) 	
	 Ort  			varchar(50) 	
	 Email  		varchar(50) 	
	 Telefon  		varchar(50) 	
	 Fax  			varchar(20) 	
	 Handy  		varchar(50) 	
	 Icq  			varchar(25) 	
	 Aim  			varchar(25) 	
	 Homepage  		varchar(50) 	
	 User  		varchar(20) 	
	 Pw  			varchar(128) 	
	 Nation  		char(1) 	
	 Status  		varchar(20) 	
	 Newsletter  		enum('true', 'false') 
	 Signatur  		text 	
	 Lastlogin  		varchar(20) 
	 Lastpost  		date 	  	
	 Posts  		int(10) 	
	 prepost  		int(10) 	
	 Hobbys  		text 	
	 Beschreibung  	text 	
	 pic  			varchar(150) 
	 pnnotify  		char(1) 	
	 autoforumnotify  	char(1) 	
	 registerDate  	timestamp 	
	 emailJN  		char(1) 	
	 icqJN  		char(1) 	
	 telefonJN  		char(1) 	
	 last_notifymail  	timestamp 	
	 last_logincheck
*/

class UserTable extends DbTable {
  var $DBCONNECT;

  function UserTable(&$con){   //Konstruktor
	$this->DBCONNECT =& $con;

       $this->TABLENAME = "user";

	$cNames = array(	"id", 
			  	"Vorname",
				"Nachname",
	 			"Name",
				 "Geburtstag",
				 "Strasse",
				 "Plz",
				 "Ort",
				 "Email",
				 "Telefon",
				 "Fax",
				 "Handy",
				 "Icq",
				 "Aim",
				 "Homepage",
				 "User",
				 "Pw",
				 "Nation",
				 "Status",
				 "Newsletter",
				 "Signatur",
				 "Lastlogin",
				 "Lastpost",
				 "Posts",
				 "prepost",
				 "Hobbys",
				 "Beschreibung",
				 "pic",
				 "pnnotify",
				 "autoforumnotify",
				 "registerDate",
				 "emailJN",
				 "icqJN",
				 "telefonJN",
				 "last_notifymail",
				 "last_logincheck");
	
	$this->setColNames($cNames);
  }


  function insert($text){
    /*
    $sql = "INSERT INTO user ";

    $this->DBCONNECT->executeQuery($sql);
    */
  }

  function getUserByUsername($name){
    $sql = "SELECT * FROM user WHERE User = '" .$name ."' ";
    $result = $this->DBCONNECT->executeQuery($sql);
   
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r;
    }
    return array();
  }

  function getUser($id){
    $sql = "SELECT * FROM user WHERE id = '" .$id ."' ";
    $result = $this->DBCONNECT->executeQuery($sql);
   
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r;
    }
    return array();
  }


  function getUserByEmail($email){
    $sql = "SELECT * FROM user WHERE Email = '" .$email ."' ";
    $result = $this->DBCONNECT->executeQuery($sql);
   
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r;
    }
    return array();
  }

  function setPassword(){
    $this->setNamedAttribute("Pw", $text);
  }


} 
?>
