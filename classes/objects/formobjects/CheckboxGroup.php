<?php
//FileNAME: CheckboxGroup.php

class CheckboxGroup extends Object {
  private $OBJECTS;
  private $HORIZONTAL;
  private $SELECTED;

  /**
   * CheckboxGroup:
   * 
   * @param $name    default = 'rbg'
   */
  function CheckboxGroup($name='rbg'){
     $this->NAME = $name;
     $this->setBorder(0);
     $this->OBJECTS = array();
     $this->HORIZONTAL = false;
     $this->SELECTED = false;
  }



  function setSelected($s){
	$this->SELECTED = $s===true?true:false;
  }

  function isSelected(){
	 return $this->SELECTED===true?true:false;
  }


  /**
   * FÃÂÃÂÃÂÃÂ¼gt der CheckboxGroup einen Wert hinzu
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
      $rdb = new Checkbox($this->NAME, $text, $wert);
      $rdb->setSelected($this->isSelected());
      $rdb->show();
    }
  }



}

?>