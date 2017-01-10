<?php
//FileNAME: TextEditor.php

class TextEditor extends Object {
  var $VALUE;

  function TextEditor($n, $v="", $w=40, $h=5 ){
     $this->NAME   = $n;
     $this->VALUE   = $v;
     $this->XPOS   = 0;
     $this->YPOS   = 0;
     $this->WIDTH  = $w;
     $this->HEIGHT = $h;
     $this->BORDER = 0;
  }




  function setBorder($size){
    $this->border = $size;
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



  function changeForSQL(){
  	$tmp = $this->VALUE;
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ", "ss", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¼", "ue", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¤", "ae", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¶", "oe", $tmp);
    $tmp = str_replace("-", "_", $tmp);
	$this->VALUE = $tmp;	
  }


  function  show(){
    echo "
          <textarea NAME='" .$this->NAME ."' cols='" .$this->WIDTH ."' rows='" .$this->HEIGHT 
        ."'>".$this->VALUE."</textarea>
    ";
  }



}

?>