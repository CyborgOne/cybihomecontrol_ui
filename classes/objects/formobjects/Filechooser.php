<?php
//FileNAME: Filechooser.php

class Filechooser extends Object {
  var $ACCEPT;
  var $VALUE;
  

  function Filechooser($name, $accept="text/*"){
     $this->ACCEPT = $accept;
     $this->NAME = $name;
     $this->XPOS = 0;
     $this->YPOS = 0;
  }





  function  show(){
    echo "<input type      =   'file' 
	             NAME      =   '" .$this->NAME ."' "; 
    if(strlen($this->ACCEPT)>0){
		echo "   accept    = '" .$this->ACCEPT ."' "; 
	}				 
	
    echo $this->getToolTipTag();
    
	echo " >";
  }



}

?>