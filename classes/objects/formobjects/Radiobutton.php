<?php
//FileNAME: Radiobutton.php

class Radiobutton extends Object {
  private $TEXT;
  private $WERT;
  private $SELECTED;

  /**
   * Radiobutton:
   * 
   * @param $text   setzt den anzuzeigenden Text
   * @param $wert   setzt den zuÃÂÃÂÃÂÃÂ¼bergebenden Wert
   */
  function Radiobutton($name, $text, $wert, $sel=false){
  	 $this->NAME = $name; 
     $this->setBorder(0);
     $this->SELECTED=$sel;
     $this->WERT = $wert;
     $this->TEXT = $text;
  }



  /**
   * zeigt die Radiobuttons an
   */
  function  show(){
    echo "<input   id = '" .$this->NAME ."' type='radio' name='".$this->NAME ."' value='".$this->WERT ."' ";
	if($this->SELECTED){
		echo " checked ";
	}
	echo "> " .$this->TEXT." ";
	
  }



}


?>