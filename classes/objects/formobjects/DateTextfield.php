<?php
//FileNAME: DateTextfield.php
/**
 * Datums-Eingabefeld 
 * 
 * fÃÂÃÂÃÂÃÂ¼r Form-Objekte geeignet
 */
class DateTextfield extends Object {
  private $VALUE;
  private $DATEFORMAT;
  private $isReadOnly;

/**
 * @param $name Der Name der auch an die Form ÃÂÃÂÃÂÃÂ¼bergeben wird (=> $_REQUEST[$name] )
 */
  function DateTextfield($name, $value="", $format="YYYY-MM-DD"){
     $this->NAME = $name;
     $this->DATEFORMAT = $format;
     
	 if(strlen($value)==strlen($this->DATEFORMAT)){
	  // echo ">DateTextfield>".$value."<<";
	   $this->setValue($value);
     }
     
	 $this->XPOS = 0;
     $this->YPOS = 0;
     
  }



/**
 * Das Standard-Format fÃÂÃÂÃÂÃÂ¼r Datumsfelder ist YYYY-MM-DD. 
 *
 * MÃÂÃÂÃÂÃÂ¶gliche Werte sind:
 * YYYYMMDD
 * YYYY-MM-DD 
 * YYYY-DD-MM 
 * YYYY/MM/DD 
 * YYYY/DD/MM 
 * YYYY-DD-MON 
 * YYYY-MON-DD 
 * MM-DD-YYYY 
 * DD-MM-YYYY 
 * MM/DD/YYYY  
 * DD/MM/YYYY 
 * DD-MON-YYYY
 * MON-DD-YYYY 
 *    
 *    * 2-digit year can be used instead of 4-digit year
 *    * 3-character month name abbreviation can also be specified as MMM.
 * 
 * @param $formatstring  hierbei muss es sich um einen der oben genannten Werte handeln!
 */
  function setDateFormat($formatstring){
  	$validFormats = array( "YYYYMMDD", 
	                       "YYYY-MM-DD", 
						   "YYYY-DD-MM", 
						   "YYYY/MM/DD", 
						   "YYYY/DD/MM",
						   "YYYY-DD-MON", 
						   "YYYY-MON-DD", 
						   "MM-DD-YYYY", 
						   "DD-MM-YYYY",
						   "MM/DD/YYYY",
						   "DD/MM/YYYY", 
						   "DD-MON-YYYY",
						   "MON-DD-YYYY");
  	
    if( array_search($formatstring, $validFormats) != false ){
	  $this->DATEFORMAT = $formatstring;  
	} else {
	  $e = new Error("Falsches Format", "DateTextfield " .$this->NAME ." wurde versucht ein falsches Datumsformat zu setzen!", "mailto:d.scheidler@web.de");
	}
	
  }

  /**
   * liefert dein aktuellen Formatstring zurÃÂÃÂÃÂÃÂ¼ck
   */
  function getDateFormat(){
    return $this->DATEFORMAT;
  }
  
  
  
  
  
  /**
   * setzt das aktuell angezeigte Datum
   * ÃÂÃÂÃÂÃÂbergebener String muss dem Datumsformat entsprechen 
   */
  function setValue($wert){
    $this->VALUE = $wert;
  }
  
  /**
   * liefert das aktuell angezeigte Datum
   */
  function getValue(){
    return $this->VALUE;
  }
    
  
  /**
   * Zeigt das DateTextfield an
   * Es wird eine Tabelle um das Feld gesetzt um eine 
   * freie Positionierung so wie Tooltips oÃÂÃÂÃÂÃÂ¤ zu ermÃÂÃÂÃÂÃÂ¶glichen. 
   * (da der eigentliche Kalender ein Script ist)
   */
  function  show(){
  	
    $tbl = new Table(array(""));
    $tbl->setWidth(50);
    $tbl->setAlign("left");
	$dtText = "<script>DateInput('" .$this->NAME ."', true";
	
	if(strlen($this->DATEFORMAT)>7){
	  $dtText .= ", '" .$this->DATEFORMAT ."'";
	  
	  if(strlen($this->VALUE) == strlen($this->DATEFORMAT)){
	    $dtText .= ", '" .$this->VALUE ."'";	
	  }
    }
	
	$dtText .= ")</script>";
	$dateTextField = new Text($dtText);
    $dateTextField->setFilter(false);
    
    $r = $tbl->createRow();
	$r->setAttribute(0, $dateTextField);
	$tbl->addRow($r);
    $tbl->setToolTip($this->getTooltip());
    $tbl->show();
  }



}

?>