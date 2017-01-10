<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */


/**
 * Liefert eine Tabelle mit einer Kurz-Info zum letzten Eintrag des Threads
 */ 
  function getLastPostInfo($thread){
    $ret = 	"Kein Eintrag vorhanden!";
    
	$sql = "SELECT f.title, f.parent from forum f, forum_posts p  where f.parent='" .$thread ."' or f.title='" .$thread ."' and f.title = p.title and f.parent = p.parent ";
    $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
    
    while($rowThread = mysql_fetch_array($res)){
		$dbTable =  new DbTable($_SESSION['config']->DBCONNECT, 
		                 'forum_posts', 
						  array( "parent", "title", "text", "datum", "autor" ) , 
						  "Titel", 
						  "",
						  "datum ASC",
						  "parent='" .$rowThread['parent'] ."' and title='" .$rowThread['title'] ."' ");
	    
		if($dbTable->getRowCount() > 0){
						  
	      $currRow = $dbTable->getRow(1);
		  $autorId = $currRow->getNamedAttribute("autor");
		  $datum = $currRow->getNamedAttribute("datum");
						  			  
	      $ret = getCreateInfo($autorId, $datum);
		}
	}
	
	return $ret;
  }
  
  
  
  
  
  
  
  
/**
 * Liefert ein Textobjekt mit Tooltip zurück, welches Informationen über das anlegen des Themas enthält.  
 * 
 * @param $thread         Name des aktuellen Themas  
 */ 
  function getCreateInfo($autorId, $datum){
    $tt_text = getUserprofilAsTooltipText($autorId);
    $erstellt_text = new Text("von: ".getUserFullnameById($autorId) ." am: ".$datum);
    $erstellt_text->setToolTip($tt_text);
    $erstellt_text->setToolTipTyp("smallmenü");

	return $erstellt_text;
  }

 
  
  
  
  
  
  
  
  
  
  
/**
 * Liefert die Anzahl der Einträge zu dem angegebenen Thread
 * 
 * @param $thread         Name des aktuellen Themas  
 */ 
  function getForumPostCount($thread){

	$dbTable =  new DbTable($_SESSION['config']->DBCONNECT, 
	                 'forum_posts', 
					  array("title") , 
					  "Titel", 
					  "",
					  "",
					  " title='" .$thread ."' ");
					  
	return $dbTable->getRowCount();
  }

  
  
  
  
  
  
  
  
  
  
  

/**
 * Liefert die Anzahl der Unterthemen  des angegebenen Threads
 * 
 * @param $CurrentThreadtitle           Name des aktuellen Themas
 */ 
  function getForumThreadCount($CurrentThreadtitle){

	$dbTable =  new DbTable($_SESSION['config']->DBCONNECT, 
	                 'forum', 
					  array("title") , 
					  "Titel", 
					  "",
					  "",
					  "parent='" .$CurrentThreadtitle ."' ");
					  

	return $dbTable->getRowCount();
  }







  
  
  
  
/**
 * Liefert den Namen des Übergeordneten Themas (parent)
 * 
 * @param $CurrentThreadtitle           Name des aktuellen Themas
 */ 
  function getParentThreadName($CurrentThreadtitle){
    $sql = "SELECT * FROM forum WHERE title = '" .$CurrentThreadtitle ."' ";
    $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
    $retRow = mysql_fetch_array($res);
    
    return $retRow['parent'];
  }
 
  
  
  
  
  
  
  
  
  
/**
 * Liefert das Userprofil zur klein-Anzeige (neben Posts)
 * 
 * @param $id           ID des Users
 */   
  function getUserprofileForUserId($id){
   	$sql = "SELECT * FROM user where id = '" .$id ."' ";
	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
	$retRow = mysql_fetch_array($res);

    $ft = new FontType();
    $ft->setBold(true);
    $ft->setFontsize(3);
        
	$picLnk = new Link("includes/user/show_userprofil.php?showUserId=" .$id ,getUserImageSourceByPicname($retRow['pic'], 80));
    $picLnk->setPopup(true);

    $status = new Text("<b>Status:</b> ".$retRow['Status']);
    $status->setFilter(false);
    
    $posts = new Text("<b>Posts:</b> ".$retRow['Posts']);
    $posts->setFilter(false);
    
    
    
    
    
    
    
    $tbl = new Table(array(""));

    $r1 = $tbl->createRow();
    $r1->setFonttype($ft);
	$r1->setAttribute(0, $retRow['Vorname'] ." " .$retRow['Nachname']);
    $tbl->addRow($r1);


    $r2 = $tbl->createRow();
	$r2->setAttribute(0, $picLnk);
    $tbl->addRow($r2);

    $r3 = $tbl->createRow();
	$r3->setAttribute(0, $status);
    $tbl->addRow($r3);

    $r4 = $tbl->createRow();
	$r4->setAttribute(0, $posts);
    $tbl->addRow($r4);



    return $tbl; 
           
  }
  
  
  
  
  
  
  
    
  
/**
 * Liefert den gesamten "Pfad" zum aktuellen Thread
 * @param $CurrentThreadtitle           Name des aktuellen Themas
 */   
  function getFullParentStructure($CurrentThreadtitle){
    $ret = new Div();
    
    $thread = $CurrentThreadtitle;
    
    while(getParentThreadName($thread) != "" && getParentThreadName($thread) != null){
		$thread = getParentThreadName($thread) ;
		$lnk = new Link("?changeCurrForumThread=".$thread,$thread);
		$ret->add($lnk);
		$t = new Text("&nbsp;-&nbsp;");
		$t->setFilter(false);	
		$ret->add($t);
	}
	$lnk = new Link("?changeCurrForumThread=".$CurrentThreadtitle,$CurrentThreadtitle);
    $ret->add($lnk);
    
    return $ret;
  }
 
 
 
 
  function deleteForumPost($rowId){
     $statement = "DELETE FROM forum_posts WHERE id = ".$rowId;
     $result = $_SESSION['config']->DBCONNECT->executeQuery($statement);  

     $e = new Message("Löschen", "Löschen erfolgreich");
  }
  
?>
