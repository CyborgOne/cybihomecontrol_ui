<?php
/**
*  ------------------------------------
*  RUNLINKS
*  -------------------------------------
*
*  Das RunLink Object dient zur Verwaltung der Seiteninternen Links.
*  Es liefert mittels der getLinkByName($name) Methode URL-Links zum ÃÂÃÂÃÂÃÂ¼bergebenen Ziel
*
*  Definiert werden die Links in der Tabelle run_links 
* 
*  ------------------------------------- 
*  Der "run" URL-Parameter steuert Anderungen 
*  der dynamischen Inhalte
*  
*  der Wert der mittels ?run= ÃÂÃÂÃÂÃÂ¼bergeben wird
*  entspricht dem Datenbankwert:  (Tabelle: RUNLINKS / Spalte: NAME)
*
*  der optionale URL-Parameter "runtarget"
*  gibt an wie der Name der Sessionvariable lautet 
*  in der der Link aktualisiert werden soll.
*  Wird dieser nicht angegeben, 
*  gilt der Wert automatisch fÃÂÃÂÃÂÃÂ¼r **$_SESSION['mainpage']**
*  ------------------------------------ 
*
* 
* @author Daniel Scheidler 
* @copyright Mai 2008
*/

class RunLinks {
  var $TABLE;
  var $DBCONNECT;

  function RunLinks($con, $targetName){
    $this->DBCONNECT = $con;
    $this->TABLE = new RunLinksTable($this->DBCONNECT, $targetName);
  }

  function getLinkByName($NAME){
    $l = $this->TABLE->getLinkByName($NAME);

    return $l;
  }

  function getFirstRunLinkNameByParent($PARENT){
    $l = $this->TABLE->getFirstRunLinkNameByParent($PARENT);

    return $l;
  }
  
  
}

?>