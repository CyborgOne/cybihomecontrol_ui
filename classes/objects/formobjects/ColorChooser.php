<?php
//FileNAME: Textfield.php

class ColorChooser extends Object {
  private $VALUE;
  private $FORM_NAME;
  
  private $RED;
  private $GREEN;
  private $BLUE;

  function ColorChooser( $n="ColorChooser", $v="#000000", $formName="ColorChooserForm"){
    $this->NAME  = $n;
    $this->VALUE = $v;
    
    $this->FORM_NAME = $formName;
  }

  function getFormName(){
    return $this->FORM_NAME;
  }

  function  show(){
    $dv = new Div();
    $dv->setAlign("center");
    $dv->getStyleString();
    $dv->getToolTipTag();
    $dv->setOverflow("visible");
    
    $t = new Text("<input id=\"choosen_" .$this->NAME ."\" name=\"choosen_" .$this->NAME ."\" type=\"color\" value=\"" 
            .$this->VALUE 
            ."\" onchange=\"document.getElementById('" .$this->NAME ."').value = document.getElementById('choosen_" .$this->NAME ."').value;"
            //."document.getElementById('" .$this->FORM_NAME ."').submit();" 
            ."\"/>"          
          ."<input id=\"" .$this->NAME ."\" name=\"" .$this->NAME ."\" type=\"text\" size=\"4\" value=\"" 
            .$this->VALUE
            ."\" />");
    $t->setFilter(false);
    
    $dv->add($t);
    $dv->show();
  }



}

?>