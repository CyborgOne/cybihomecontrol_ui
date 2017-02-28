<?php
//FileNAME: Container.php
 
class Container extends Object {
  var $WIDTH;
  var $HEIGHT;
  var $border;
  var $objCount=0;
  var $OBJECTS;
  
  function Container() {
    $this->OBJECTS = array();
  }

  function add($obj){
    $this->objCount++;
    $this->OBJECTS[$this->objCount] = $obj;
  }

  function setBorder($size){
    $this->border = $size;
  }

  function getBorder(){
    return  $this->border;
  }

  function setWidth($w){
    $this->WIDTH = $w;
  }

  function getWidth(){
    return $this->WIDTH;
  }

  function setHeight($h){
    $this->HEIGHT = $h;
  }

  function getHeight(){
    return $this->HEIGHT;
  }

  function getObjectsByClass($className){
    // Liefert ein Array of Objects 
    // in dem Alle Objekte vom Typ der ÃÂÃÂÃÂÃÂ¼bergebenen Klasse enthalten sind.

    $x=0;		
    $ret = array();
    $aCount=0;

    foreach ($this->OBJECTS as $o){ 
       $x++;
	if (get_class($o)==$className){
	  $ret[$aCount] = $o;
	  $aCount++;
	}
    }
    return $ret;
  }

  function getObjectsByClassfunction($functName){
    // Liefert ein Array of Objects 
    // in dem Alle Objekte enthalten sind die die angegebene Methode enthalten.

    $x=0;		
    $ret = array();
    $aCount=0;

    foreach ($this->OBJECTS as $o){ 
       $x++;

    	if (method_exists($o, $functName)){
    	  $ret[$aCount] = $o;
    	  $aCount++;
    	}
    }
    return $ret;
  }

  function show(){
    foreach($this->OBJECTS as $obj){
      if(method_exists($obj, "show")){  
        $obj->show();
      }
    }
  }
}