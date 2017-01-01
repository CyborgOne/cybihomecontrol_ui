<?php
//FileNAME: Textfield.php

class ColorChooser extends Object {
  private $VALUE;
  
  private $RED;
  private $GREEN;
  private $BLUE;

  function ColorChooser( $n="ColorChooser", $v="#000000"){
    $this->NAME  = $n;
    $this->VALUE = $v;
  }



  function  show(){
    echo  "<div";
    
    echo $this->getStyleString();
    echo "><center>";

    
    echo  "<input id=\"background-color\" type=\"color\" value=\"" 
          .$this->VALUE 
          ."\" onchange=\"document.getElementById('" .$this->NAME ."').value = document.getElementById('background-color').value;document.getElementById('" .$this->NAME ."Form').submit();\">"
          
          ."<input id=\"" .$this->NAME ."\" type=\"text\" size=\"4\" value=\"" 
          .$this->VALUE 
          ."\" readonly=\"\" ></center></div>";
    
    
    echo $this->getToolTipTag();
    
	echo ">";
  }



}

?>