<?php
   include("header_index.php");
   include("sig.php");

  
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




   /** ------------------------------------
   *  RUNLINKS
   *
   *  Der "run" URL-Parameter steuert Anderungen 
   *  der dynamischen Inhalte
   *  
   *  der Wert der mittels ?run= übergeben wird
   *  entspricht dem Datenbankwert:  (Tabelle: RUNLINKS / Spalte: NAME)
   *
   *  der optionale URL-Parameter "runtarget"
   *  gibt an wie der Name der Sessionvariable lautet 
   *  in der der Link aktualisiert werden soll.
   *  Wird dieser nicht angegeben, 
   *  gilt der Wert automatisch für **$_SESSION['mainpage']**
   ------------------------------------ */
   
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



/**
*
*  Hier werden alle STANDARD-Objekte erzeugt
*  
*  ( evtl noch auslagern? )
*/

   /* ------------------------------------
     HAUPTFRAME
   ------------------------------------ */

   $mainFrame = &new IFrame($CONFIG, "haupt");
   $mainFrame->setXPos($MAINFRAME['xpos']);
   $mainFrame->setYPos($MAINFRAME['ypos']);
   $mainFrame->setWidth($MAINFRAME['width']);
   $mainFrame->setHeight($MAINFRAME['height']);
   $mainFrame->setBorder($MAINFRAME['border']);
   $mainFrame->setSource("haupt.php"); //STEUERT DEN INHALT UND GARANTIERT SOMIT FÜR LINKS DAS ROOT_DIR
 

   $mainFrame->show();

   /* --------------------------------- */




   /* ------------------------------------
     MENU
   ------------------------------------ */

   $menu = &new DbMenu($CONFIG);
   $menu->setXPos(10);
   $menu->setYPos(190);

   $menu->show();
   
   /* --------------------------------- */




   /* ------------------------------------
     Statischer Zurück-Button
  ------------------------------------ */
   $backButton = new ButtonBack();
   $backButton->setXPos($MAINFRAME['xpos'] + $MAINFRAME['width']-83); 
   $backButton->setYPos($MAINFRAME['ypos'] + $MAINFRAME['border']+2); 

   $backButton->show(); 
 
   /* --------------------------------- */




   /* ------------------------------------
     Banner
   ------------------------------------ */
   if(isset($BANNER['activ']) && $BANNER['activ']){ 
     $banner = new Image($BANNER_PIC);  //$BANNER_PIC wird in Public_Vars definiert
     $banner->setXPos(200); 
     $banner->setYPos(5); 
     $banner->setWidth(600); 
     $banner->setHeight(80); 

     $banner->show(); 
   }
   /* --------------------------------- */





   /* ------------------------------------
     BENUTZERSTATUS ANZEIGEN
   ------------------------------------ */
   if($USER_STATUS['aktiv']){
     $CONFIG->CURRENTUSER->showStatus($USER_STATUS['xpos'],$USER_STATUS['ypos']);
   }
   /* --------------------------------- */



?>