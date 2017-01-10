<?php

function checkForMessages(){
	if (isMessageForCurrentUser()){
	   showMessageInfo();
	}
}


 

function isMessageForCurrentUser(){
  if(isset($_SESSION['MENU_PARENT']) && $_SESSION['MENU_PARENT']!="Mein Profil"){
    if($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->CURRENTUSER->STATUS == "user"){	
	  $tbl = new DbTable( $_SESSION['config']->DBCONNECT, 
	                      "pm_messages", 
						  array("*"), 
						  "", 
						  "", 
						  "SENDDATE DESC", 
						  "receiver=".$_SESSION['config']->CURRENTUSER->USERID ." AND gelesen='N' ");
						  
	  $cnt = $tbl->getRowCount();
	  
	  return $cnt>0;
	} else {
      return false;
    }
  } else {
	return false;
  }
}






function showMessageInfo(){
	$tblMsg = new Table(array(""));
	
	$r1Msg = $tblMsg->createRow();
	$r1Msg->setHeader(true);
	$r1Msg->setAttribute(0,"Ungelesene Nachrichten");
	$tblMsg->addRow($r1Msg);
	
	$r2Msg = $tblMsg->createRow();
	$r2Msg->setAttribute(0, new Text("Es existieren ungelesene Nachrichten in Ihrem Posteingang!\n\nKlicken Sie auf den Link um direkt zum Posteingang zu gelangen.", 3, true, true) );
	$tblMsg->addRow($r2Msg);
	
	$r3Msg = $tblMsg->createRow();
	$r3Msg->setAttribute(0,new Link("?menuParent=Mein Profil&run=pm", "Hier direkt zum Posteingang") );
	$tblMsg->addRow($r3Msg);
	
	
	$tblMsg->addSpacer(0,10);
	
	$tblMsg->show();
}


?>