<?php
//FileNAME: Listbox.php

class Listbox extends Object {
  private $SIZE;
  private $MULTIPLE_SELECTION;
  private $OBJECTS;

  /**
   * Listbox:
   * 
   * @param $size int > 0 
   */
  function Listbox($size=5){
     $this->NAME = "Listbox";
     $this->setSize($size);
     $this->setBorder(0);
     $this->OBJECTS = array();
     
     $this->MULTIPLE_SELECTION = false;     
  }


  /**
   * FÃÂÃÂÃÂÃÂ¼gt der Listbox einen Wert hinzu
   * 
   * @param $name    entspricht dem Wert der in der Liste angezeigt wird
   * @param $wert    entspricht dem Wert der bei ausfÃÂÃÂÃÂÃÂ¼hren der Form ÃÂÃÂÃÂÃÂ¼bergeben wird
   */
  function add($name, $wert){
    $this->OBJECTS[$name] = $wert; 
  }
  

  /**
   * gibt an, ob Mehrfachauswahl mÃÂÃÂÃÂÃÂ¶glich ist
   * 
   * @param $m    boolean 
   */
 function setMultipleSelection($m){
    if($m){
      $this->MULTIPLE_SELECTION = $m;
    } else {
	  $this->MULTIPLE_SELECTION = false;
    }
  }



  /**
   * liefert einen boolschen Wert zurÃÂÃÂÃÂÃÂ¼ck, 
   * der angibt, ob Mehrfachauswahl mÃÂÃÂÃÂÃÂ¶glich ist 
   */
  function isMultipleSelection(){
    return ($this->MULTIPLE_SELECTION=true ? true : false);
  }




  /**
   * gibt an wie viele Zeilen der Liste aufeinmal angezeigt werden sollen.  
   */
  function setSize($s){
    $this->SIZE = $s;
  }
  
  

  /**
   * liefert einen Int Wert zurÃÂÃÂÃÂÃÂ¼ck, 
   * der angibt, wie viele Zeilen der Liste aufeinmal angezeigt werden.  
   */
  function getSize($s){
    return $this->SIZE;
  }




  /**
   * zeigt die Liste an
   */
  function  show(){
    echo  "
	    <select  id = '" .$this->NAME ."' name=" .$this->NAME ." size='" .$this->SIZE ."' ";
	
	if($this->MULTIPLE_SELECTION){
		echo " multiple ";
	}   
	   
	echo "> 
	";
    foreach($this->OBJECTS as $name => $wert){
      echo "<option value='" .$wert ."'>" .$name ."</option>
      ";
    }

    echo "
	    </select> 
    ";

  }



}

?>