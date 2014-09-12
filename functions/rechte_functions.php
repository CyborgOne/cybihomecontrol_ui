<?php

/**
 * @author Daniel Scheidler
 * @copyright August 2008
 */


   function isInsertAllowedForRunlinkId($runlink){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and run_link_id = " .$runlink ;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["insertjn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }


   function isInsertAllowedForProgrammgroupId($programmgruppe){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and prog_grp_id = " .$programmgruppe;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["insertjn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }





   function isUpdateAllowedForRunlinkId($runlink){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and run_link_id = " .$runlink ;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["updatejn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }


   function isUpdateAllowedForProgrammgroupId($programmgruppe){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and prog_grp_id = " .$programmgruppe;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["updatejn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }
   
   





   function isShowAllowedForRunlinkId($runlink){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and run_link_id = " .$runlink ;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["showjn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }


   function isShowAllowedForProgrammgroupId($programmgruppe){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and prog_grp_id = " .$programmgruppe ;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["showjn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }






   function isDeleteAllowedForRunlinkId($runlink){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and run_link_id = " .$runlink  ;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["deletejn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }

   function isDeleteAllowedForProgrammgroupId($programmgruppe){
	 $ret = false;
     $sql = "SELECT * FROM `berechtigung` where user_id = " .$this->SESSION['config']->CURRENTUSER->USERID ." or user_grp_id = " .$this->SESSION['config']->CURRENTUSER->USERGRPID ." and prog_grp_id = " .$programmgruppe ;
     $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     $rechte = mysql_fetch_array($res);
     
	 if($rechte["deletejn"]=='J'){
		$ret = true;
	 }
	 
     return $ret;
   }

?>