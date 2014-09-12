<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */


  /**
  * liefert boolean ob ein User für ein Voting schon seine Stimme abgegeben hat
  *
  * @param $user int    Benutzer-Id
  * @param $voting int  Voting-Id
  */  
  function hasUserVoteForVoting($user, $voting){
    if($user == null || $user == ""){
		$user=-1;
	}
	$sql = "SELECT * FROM voting_stimmen where voting_id = " .$voting ." and user_id = " .$user ." ";
    $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
    
    return mysql_num_rows($res)>0; 	
  }



?>