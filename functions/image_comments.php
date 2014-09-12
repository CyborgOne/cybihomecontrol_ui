<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

/**
 * Liefert den Dialog-Source zum Erfassen eines Kommentars zu einem Bild als Tooltip
 */ 
  function getCommentDialogForImage($pic){
  	$ret = "";
  	
  	if($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->CURRENTUSER->STATUS == "user" ) {
  	  $ret = "<b><h3>Schreiben Sie einen Kommentar zu diesem Bild:</h3></b>"
  	        ."<img src='/".$pic."' height='180'><br><br>"
  	        ."<form method='post' action='" .$_SERVER['SCRIPT_NAME'] ."'>"
  	        ."<textarea name='comment' cols='40' rows='4'></textarea><br>"
  	        ."<input type='SUBMIT' name='#' value='Abbrechen' >"
			."<input type='SUBMIT' name='SaveBBComment' value='Speichern' >"
  	        ."<input type='hidden' name='BBCommentPic' value='" .$pic ."' >"
  	        ."</center>"
		    ."</form><br><hr>";
  	 }
  	
	 return $ret;
  }
  
  
  



/**
 * Liefert die Kommentare zu einem Bild als Div
 */ 
  function getCommentsListForImage($pic){
	$dbTableComments = new DbTable($_SESSION['config']->DBCONNECT, 
		                           "bb_comments", 
								   array("comment", "autor"),
								   "Kommentar",
								   "",
								   "id ASC",
								   "pic = '" .$pic ."'");
	

    $ret = "<center><h3>Kommentare</h3><br><div style=\"overflow:auto;width:370px;\">";


    $ret  .= "<table cellpadding='0' cellpadding='0' width='350'>";
  	
	$rc=0;
	foreach($dbTableComments->ROWS as $row){
		$commentText = $row->getNamedAttribute("comment");
		$autorText = $row->getNamedAttribute("autor");
		
		$ret .= "<tr>"
		          ."<td><font color='#222222' size='2'>"
		             .$commentText
		          ."</font></td>"
		        ."</tr><tr>"
		          ."<td align='right'><font color='#222222'  size='1'>"
		             .$autorText
		          ."</font></td>"
		        ."</tr><tr>"
		          ."<td align='right'><hr></td>"
		        ."</tr>";
	    $rc++;
	}  

    $ret .= "</table>";
    $ret .= "</div></center>";
  	if($rc>0){
      return $ret;
    } else {
	  return "";	
	}
  }
  
  



  function checkForInsertBBComment(){
     //$con, $tbname="", $cols=array("*"), $labels="", $defaults="", $o="", $w=""
	 
	 if(isset($_REQUEST['SaveBBComment']) && 
	    isset($_REQUEST['comment']) && strlen($_REQUEST['comment'])>0 && 
		isset($_REQUEST['BBCommentPic']) && strlen($_REQUEST['BBCommentPic'])>0){
	   
	   
		   $currUserName = $_SESSION['config']->CURRENTUSER->VORNAME." ".$_SESSION['config']->CURRENTUSER->NACHNAME;
		   
		   $dbTableComments = new DbTable($_SESSION['config']->DBCONNECT, 
		                                "bb_comments", 
										array("comment"),
										"Kommentar",
										"autor = '" .$currUserName ."', pic = '" .$_REQUEST['BBCommentPic'] ."' ",
										"id ASC",
										"pic = '" .$_REQUEST['BBCommentPic'] ."'");
										
		   $dbTableComments->doInsert();
	 }
	
  } 
  
  
?>