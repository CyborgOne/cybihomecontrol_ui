<?php
//FileNAME: Form.php

class Form  extends Object{
  var $ACTION;
  var $TARGET;
  var $DEFAULTOBJECT;


  var $OBJECTS;


  function Form ($action="", $target="", $param="", $name='Form'){
     $this->NAME = $name;
     
     if( strlen($this->ACTION)<=0 ){
        $this->ACTION = $_SERVER['SCRIPT_NAME'];
     } else {
        $this->ACTION = $action;
     }
     $this->TARGET = $target;
     $this->OBJECTS = array();
     $this->PARAMS = $param;
     $this->DEFAULTOBJECT = "";
  }

  function setAction($a){
    $this->ACTION = $a;
  }

  function getAction(){
    return $this->ACTION;
  }

  function setParam($p){
     $this->PARAMS = $p;	
  }

  function setDefaultObject($v){
    $this->DEFAULTOBJECT = $v;
  }

  function  add($formObject){
    if(method_exists($formObject, "show")){
        // Objekt ins Array aufnehmen
        $this->OBJECTS[count($this->OBJECTS)] = $formObject;
    } else {
        $e = new Error("Form-Objekt ohne show()", "Das ÃÂÃÂÃÂÃÂ¼bergebene Objekt hat keine <b>show()</b> - Methode!!!");
    }
  }

  function  addInFront($formObject){
    if(method_exists($formObject, "show")){
        // Objekt ins Array aufnehmen
        array_unshift($this->OBJECTS , $formObject);
    } else {
        $e = new Error("Form-Objekt ohne show()", "Das ÃÂÃÂÃÂÃÂ¼bergebene Objekt hat keine <b>show()</b> - Methode!!!");
    }
  }

  function  getAttribute($id){
    if(count($this->OBJECTS)>$id){
        return $this->OBJECTS[$id];
    } else {
        $e = new Error("Indexüberschreitung", $id ." überschreitet den zulässigen Index in dieser Form");
    }
  }


  function  setAttribute($id, $obj){
    if(! method_exists($obj, "show")){
        $e = new Error("Form-Objekt ohne show()", "Das Objekt, welches an die Form übergeben wurde, hat keine <b>show()</b> - Methode!!!");
    }

    if(count($this->OBJECTS)>=$id){
        // Objekt ins Array aufnehmen
        $this->OBJECTS[$id] = $obj;
    } else {
        $e = new Error("Indexüberschreitung", $id ." überschreitet den zulässigen Index in dieser Form");
    }
  }




  function  show(){
    echo "<form action='" .$this->ACTION  ."' method='post' ";
    
    if(strlen($this->DEFAULTOBJECT)>0){
      echo " defaultbutton='" .$this->DEFAULTOBJECT ."' " ;
    }
    
    if(strlen($this->TARGET)>0){
       echo " target='" .$this->TARGET  ."' ";    
    }
    
    if(strlen($this->NAME)>0){
       echo " id='" .$this->NAME  ."' ";    
       echo " name='" .$this->NAME  ."' ";    
    }

    if(strlen($this->PARAMS)>0){
       echo $this->PARAMS ;    
    }

    echo ">";
    
    if(isset($_REQUEST['popupRunLink'])){
	    $popupHidden = new Hiddenfield("popupRunLink", $_REQUEST['popupRunLink']);
	    $this->add($popupHidden);
	}

    
    foreach($this->OBJECTS as $OBJ){
      $OBJ->show();
    }


    echo "</form>";    
  }



}

?>