<?php
//FileNAME: RadiobuttonGroup.php

class RadiobuttonGroup extends Object {
  private $OBJECTS;
  private $HORIZONTAL;

  /**
   * RadiobuttonGroup:
   * 
   * @param $name    default = 'rbg'
   */
  function RadiobuttonGroup($name='rbg'){
     $this->NAME = $name;
     $this->setBorder(0);
     $this->OBJECTS = array();
     $this->HORIZONTAL = false;
  }


  /**
   * FÃÂÃÂÃÂÃÂ¼gt der RadiobuttonGroup einen Wert hinzu
   * 
   * @param $name    entspricht dem Wert der in der Liste angezeigt wird
   * @param $wert    entspricht dem Wert der bei ausfÃÂÃÂÃÂÃÂ¼hren der Form ÃÂÃÂÃÂÃÂ¼bergeben wird
   */
  function add($name, $wert){
    $this->OBJECTS[$name] = $wert; 
  }
  
  

  /**
   * gibt an ob die Radiobuttons horizontal angerichtet sein sollen (sonst Vertikal)
   * 
   * @param $h bool 
   */
  function setHorizontal($h){
    $this->HORIZONTAL = $h===true?true:false;; 
  }
  

  /**
   * gibt an ob die Radiobuttons horizontal angerichtet sein sollen (sonst Vertikal)
   */
  function isHorizontal(){
    return $this->HORIZONTAL===true?true:false; 
  }
  
  
  
  
  

  /**
   * zeigt die Radiobuttons an
   */
  function  show(){
    foreach($this->OBJECTS as $text => $wert){
      if(! $this->isHorizontal()){
		$text = $text ."<br>";
	  }
      $rdb = new Radiobutton($this->NAME, $text, $wert);
      $rdb->show();
    }
  }



}

?>