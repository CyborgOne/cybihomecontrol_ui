<?php
//FileNAME: Textfield.php

class Textfield extends Object {
  var $WIDTH;
  var $HEIGHT;
  var $border;
  var $VALUE;
  var $isReadOnly;

  function Textfield( $n="Textfield", $v="", $w=30, $m=50, $isReadOnly=false ){
     $this->NAME = $n;
     $this->VALUE = $v;
     $this->XPOS = 0;
     $this->YPOS = 0;
     $this->WIDTH = $w;
     $this->MAX = $m;
     $this->isReadOnly = $isReadOnly;
  }


  
  function changeForSQL(){
  	$tmp = $this->VALUE;
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ", "ss", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¼", "ue", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¤", "ae", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¶", "oe", $tmp);
    $tmp = str_replace("-", "_", $tmp);

	$this->VALUE = $tmp;	
  }


  function setBorder($size){
    $this->border = $size;
  }

  function setReadOnly($b){
    if($b==true){
      $this->isReadOnly = true;
    } else {
      $this->isReadOnly = false;
    }
  }

  function isReadOnly(){
    return $this->isReadOnly;
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




  function  show(){
    echo "<input  id='" .$this->NAME ."' 
	             type      =   'TEXT' 
	             NAME      =   '" .$this->NAME ."' 
	             value     =   '" .$this->VALUE ."'  
				 size      =   '" .$this->WIDTH  ."' 
				 maxlength = '" .$this->MAX ."'  ";

    if($this->isReadOnly()){
		echo " readonly ";
	}				
				 

    echo $this->getToolTipTag();
    
	echo ">";
  }



}

?>