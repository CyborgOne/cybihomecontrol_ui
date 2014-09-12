<?php
// Dateiname: MenuTable.php

/*
	 id 		int(11)
	 text 		varchar(30)
	 parent 	int(11)
	 link 		varchar(100)
	 status 	varchar(5)
	 target 	varchar(25)	 
	 tooltip 	text
	 sortnr 	int(11)
*/

class MenuTable extends DbTable {
  var $DBCONNECT;

  function MenuTable($name){   //Konstruktor
	$this->DBCONNECT = $_SESSION['config']->DBCONNECT;

    $this->TABLENAME = "menu";

	$cNames = array(	"id", 
			 	 "text", 
				 "parent",
				 "link",
				 "status",
				 "target",
				 "tooltip",
				 "sortnr");
				 
	$this->NAME = $name;
	$this->setColNames($cNames);
  }



  function getTextByRunlink($id){

    $sql = "SELECT * FROM menu WHERE (link LIKE '%?run=" .$id ."&%') OR (link LIKE '%?run=" .$id ."')";
    $result = $this->DBCONNECT->executeQuery($sql);
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r['text'];
    }

    return "";
  }


  function getMenuById($id){
    $sql = "SELECT * FROM menu WHERE id = id ";
    
    $result = $this->DBCONNECT->executeQuery($sql);
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r;
    }
    
    return array();
  }



  function getMenuByStatus($status){
  	$sql = "SELECT * FROM menu m WHERE m.name = '" .$this->NAME ."' " 
		  ." and ( m.parent = '' or m.parent = '" .$_SESSION['MENU_PARENT']  ."' )"  //  
          ." and (m.status is null or m.status = '' ";
          
    if($status == "admin"){
	  $sql .= " or m.status = 'user' or m.status = 'admin'  or m.status = 'gast' ";   

    } else if ($status == "user"){
	  $sql .= " or m.status = 'user'  or m.status = 'gast' ";  

	} else if ($status == "gast"){
	  $sql .= " or m.status = 'gast' ";  

	} 

    $sql .= ") ORDER BY m.sortnr ASC ";  

    $result = $this->DBCONNECT->executeQuery($sql);

  //  if(mysql_num_rows($result)>0){
      return $result;
    //}

    //return array();
  }


  function getRowCountByStatus(){
    $result = $this->getMenuByStatus($_SESSION['config']->CURRENTUSER->STATUS);
    if (count($result)>0){
    	return mysql_num_rows($result);
	}
	return 0;	
  }

}
?>
