<?php
   include("header_index4frameset.php");
 

     /** ------------------------------------
   *  HANDLER
   *
   *  Der IndexHandler verwaltet alle Aktionen
   *  die mittels "do" URL-Parameter angestoßen 
   *  werden.
   ------------------------------------ */
   if(isset($_REQUEST['do']) && strlen($_REQUEST['do'])>0){
     $HANDLER = new IndexHandler($_SESSION['config']);
     $HANDLER->DoAction($_REQUEST['do']);
   }


   // Prüfung ob abweichendes Ziel angegeben wurde
   if( isset($_REQUEST['runtarget']) && strlen($_REQUEST['runtarget'])>0){
     $runLinkTarget = $_REQUEST['runtarget'];
   } else {
     $runLinkTarget = "mainpage";
   }

   //Alle RunLinks für ermitteltes Ziel bereitstellen
   $RUNLINKS = new RunLinks($_SESSION['config']->DBCONNECT, $runLinkTarget);
   
   $run = "";
   // Wenn neues Ziel angefordert wurde, dieses ermitteln
   if(isset($_REQUEST['run']) && strlen($_REQUEST['run'])>0){
     // NEUES ZIEL ANGEFORDERT
     $run = $RUNLINKS->getLinkByName($_REQUEST['run']); 
   } else if(!isset($_SESSION[$runLinkTarget]) || strlen($_SESSION[$runLinkTarget]) == 0 ) { 
     //KEIN ZIEL ANGEGEBEN -> KEIN ALTES ZIEL VORHANDEN    Default: start
     $run = $RUNLINKS->getLinkByName("start"); 
   }

   //Wurde ein Link gefunden wird dieser Wert im entsprechenden Ziel aktualisiert
   if(strlen($run)>0){
     // Ist ein alternatives Ziel gesetzt, 
     // wird der neue link in die entsprechende session-variable geschrieben
     // ansonsten gilt der RUNLINK für die Hauptseite (mainpage)
     if( isset($runLinkTarget) && strlen($runLinkTarget)>0){
       $_SESSION[ $runLinkTarget ] = $run;
     } else {
       $_SESSION['mainpage'] = $run;
     }
   }




   $framesetTop = new Frameset();
   $framesetTop->setRowOrCol("r");
   $headFrame = new Frame("head", "head.php");
   $headFrame->setSize(100);   

   $frameset = new Frameset();
   $frameset->setRowOrCol("c");

   $menuFrame = new Frame("menu", "menu4frameset.php");
   $menuFrame->setSize(210);   
   $hauptframe = new Frame("haupt", "haupt.php");
   $hauptframe->setSize("*");   
   
   $frameset->addFrame($menuFrame);
   $frameset->addFrame($hauptframe);   


   $framesetTop->addFrame($headFrame);
   $framesetTop->addFrame($frameset);   
   
   $framesetTop->show();
 include("foot_index.php");

?>