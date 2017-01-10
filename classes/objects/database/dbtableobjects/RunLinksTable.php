<?php
 /**
 * 
 * @author Daniel Scheidler 
 * @copyright Mai 2008
 * 
 *  
	 id 		int(11)
	 name 		varchar(50)
	 link 		varchar(250)
	 target 	varchar(50)
 */

class RunLinksTable extends DbTable {
  var $DBCONNECT;
  var $TARGETNAME;

  function RunLinksTable(&$con, &$targetName){   //Konstruktor
	$this->DBCONNECT   =& $con;
	$this->TARGETNAME  =& $targetName;

    $this->TABLENAME   = "run_links";

	$cNames = array("id", 
			  	"name",
				"link",
	 			"target");
	
	$this->setColNames($cNames);
	if(isset($_SESSION['MENU_PARENT'])){
	  $this->setWhere("(parent = '" .$_SESSION['MENU_PARENT']."' or parent is null ) ");
	}
  }



  function getLinkByName($name){ 
    $sql = "SELECT * FROM run_links WHERE name = '" .$name ."' and target='" .$this->TARGETNAME  ."' and (parent = '" .$_SESSION['MENU_PARENT']."' or parent = ''  or parent is null )";
    $result = $this->DBCONNECT->executeQuery($sql);
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r['link'];
    }
    
    return "errors/siteNotFound.php";
  }


  function getFirstRunLinkNameByParent($parent){ 
    $sql = "SELECT * FROM run_links WHERE parent = '" .$_SESSION['MENU_PARENT'] ."' order by id";
    $result = $this->DBCONNECT->executeQuery($sql);
    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r['name'];
    }
    
    return "errors/siteNotFound.php";
  }


}
?>
