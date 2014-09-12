<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

  /**
  * liefert Vor- und Nachnamen des Benutzers  der übergebenen Id als String zurück
  */  
  function getUserFullnameById($id){
    $sql = "SELECT Vorname, Nachname FROM user where id = '" .$id ."' ";
	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	$retRow = mysql_fetch_array($res);
	
	return $retRow['Vorname'] ." " .$retRow['Nachname']; 	
  }
  
   /**
  * liefert den Anmelde-Namen des Benutzers  der übergebenen Id als String zurück
  */  
  function getUsernameById($id){
    $sql = "SELECT User FROM user where id = '" .$id ."' ";
	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	$retRow = mysql_fetch_array($res);
	
	return $retRow['User'] ; 	
  }
  
 
  function getUserImageByPicname($picName, $width=200){
  
    if(strlen($picName)>0 && 
	  !file_exists($_SERVER['DOCUMENT_ROOT'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/pics/user/" .$picName )){
      $pic = substr(dirname($_SERVER['SCRIPT_NAME']),1) ."pics/unknownpic.jpg";
    } else if(strlen($picName)==0){
 	  $pic = substr(dirname($_SERVER['SCRIPT_NAME']),1) ."pics/unknown_user.jpg";
    } else {
 	  $pic = substr(dirname($_SERVER['SCRIPT_NAME']),1) ."pics/user/".$picName;
    }

    $ret = new Image($pic);
    $ret->setWidth($width);

//    $ret = "<img src='"  .$pic  ."' width='" .$width ."'>";
    
    return $ret;
  }
   
  
  function getUserImagePath($userId, $trimLeftCount=0) {
    $sql = "SELECT pic FROM user where id = '" .$userId ."' ";
	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	$retRow = mysql_fetch_array($res);
    
    $picName = $retRow['pic'];
 
    if(strlen($picName)>0 && !file_exists($_SERVER['DOCUMENT_ROOT'] .substr(dirname(substr($_SERVER['SCRIPT_NAME'],$trimLeftCount) ),1) ."/pics/user/" .$picName )){
      $pic = substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/pics/unknownpic.jpg";
    } else if(strlen($picName)==0){
 	  $pic = substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/pics/unknown_user.jpg";
    } else {
 	  $pic = substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/pics/user/".$picName;
    }
    
    return $pic;
  }
  
   
  function isUserOnFriendlist( $friendId ){
     $sql = " SELECT * FROM freundesliste " 
           ." WHERE user_id = ".$_SESSION['config']->CURRENTUSER->USERID 
           ." AND friend_id = " .$friendId;
           
     $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
     if (mysql_num_rows($rslt)>0){
        return true;
     }         
     
     return false;
  }
  
   
  function getUserImageByUserId($userId, $width=200){
    $pic = getUserImagePath($userId);
    $ret = new Image($pic);
    $ret->setWidth($width);
    //$ret = "<img src='"  .$pic  ."' width='" .$width ."'>";
    
    return $ret;
  }
  
  
  
  function getUserIdByUsername($userName){
    $sql = "SELECT id FROM user where User = '" .$userName ."' ";
	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	$retRow = mysql_fetch_array($res);
    
    return $retRow['id'];
  }
  
  
  
    
  function getUserImageSourceByPicname($picName, $width=200){
  	$sn = substr(dirname($_SERVER['SCRIPT_NAME']),0);
  	if(strlen($sn)>0){
		if(substr($sn,strlen($sn)-1,1)!="/"){
		    $sn = $sn."/";
		}
	}
  	
    if(strlen($picName)>0 && !file_exists($_SERVER['DOCUMENT_ROOT'] .$sn."pics/user/" .$picName )){
      $pic = $sn ."pics/unknownpic.jpg";
    } else if(strlen($picName)==0){
 	  $pic = $sn ."pics/unknown_user.jpg";
    } else {
 	  $pic = $sn ."pics/user/".$picName;
    }

    $ret = "<img src='"  .$pic  ."' width='" .$width ."'>";
    
    return $ret;
  }
  
 

  /**
  * liefert das Profil des Benutzers  der übergebenen Id als Tooltip zurück
  */   
  function getUserprofilAsTooltipText($userId){
    $ret = "";
    
	$sql = "SELECT * FROM user where id = '" .$userId ."' ";
	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	$retRow = mysql_fetch_array($res);
	
	$color = "#555555";
	
	$ret = "<table width='300'>"
	      ."<tr><th colspan='2'><font size='2' color='".$color."'>" .$retRow['User'] ."</font></th></tr>"
	      ."<tr><th colspan='2'>" .getUserImageSourceByPicname($retRow['pic'],150) ."</th></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Name: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Vorname'] ." " .$retRow['Nachname'] ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Status: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Status'] ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Strasse: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Strasse']  ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Plz/Ort: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Plz'] ." " .$retRow['Ort'] ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Telefon: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Telefon']  ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Fax: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Fax']  ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Handy: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Handy']  ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>ICQ: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Icq']  ."</font></td></tr>"
	      ."<tr><td width = '20%'><font size='2' color='".$color."'>Homepage: </font></td><td width = '80%'><font size='2' color='#555555'>" .$retRow['Homepage']  ."</font></td></tr>"
	      
		  ."</table>";
	
	
	return $ret; 	
	
  }


    function isLastGpsUpdateInLast5Minutes($dbConnect, $userid){
        $sql = "SELECT (now() - timeonupdate) as diffTime FROM  gpsPositions WHERE user_id = " .$userid;
        $res = $dbConnect->executeQuery($sql);
        $row = mysql_fetch_array($res);
        
        $ret = "Zu lange her / Nie";
        
        if(strlen($row['diffTime'])>0 ){
            $tmp = (time() - strtotime($row['timeonupdate']));
            return $tmp <= 300;
        }
        
        return false;
    }


    function getLastGpsUpdateSince($dbConnect, $userid){
        $sql = "SELECT timeonupdate FROM  gpsPositions WHERE user_id = " .$userid;
        $res = $dbConnect->executeQuery($sql);
        $row = mysql_fetch_array($res);
        
        $ret = "Zu lange her / Nie";
        
        if(strlen($row['timeonupdate'])>0 ){
            $tmp = (time() - strtotime($row['timeonupdate']));
            
            $tag = floor($tmp/(24*60*60));
            //echo "t".$tag;
            $std = floor(($tmp-($tag*(24*60*60)))/(60*60));
            //echo "s".$std;
            $min = floor((($tmp-(($std*(60*60))+($tag*(24*60*60)) ) ) / 60) );
            //echo "m".$min."<br>";
            
            $ret = "vor: ";
            if($tag>0){
                $ret = $ret .$tag .($tag==1 ? "Tag " : "Tagen ");
            }
            if($std>0){
                $ret = $ret .$std ."Std. ";
            }
            if($min>0){
                $ret = $ret .$min ."Min. ";
            }
            if($min==0 && $std==0 && $tag==0){
                $ret = $ret ."0Min. ";
            }
        }
        
        return $ret;    
    }


?>