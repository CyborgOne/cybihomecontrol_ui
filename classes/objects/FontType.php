<?php
//Filename: FontType.php

class FontType extends Object {
  public $FONT;  
  public $BOLD;  
  public $ITALIC;  
  public $UNDERLINE;  
  public $FONTSIZE;  
  public $COLOR;



  function FontType($font="Arial", $fontsize=null, $bold=false, $italic=false, $underline=false){
     $this->FONT       = $font;
     $this->FONTSIZE   = $fontsize;
     $this->setBold($bold);
     $this->setitalic($italic);
     $this->setunderline($underline);
  }



  function setBold($x){
    if($x=="1" || $x==true || $x=="true"){
      $this->BOLD = true;
    } else {
      $this->BOLD = false;
    }
  }

  function setItalic($x){
    if($x=="1" || $x==true || $x=="true"){
      $this->ITALIC = true;
    } else {
      $this->ITALIC = false;
    }
  }

  function setUnderline($x){
    if($x=="1" || $x==true || $x=="true"){
      $this->UNDERLINE = true;
    } else {
      $this->UNDERLINE = false;
    }
  }

  function setFontsize($x){
    $this->FONTSIZE = $x;
  }

  function getFontsize(){
    return $this->FONTSIZE;
  }

  function setColor($c){
    $this->COLOR = $c;
  }
  
  function getColor(){
    return $this->COLOR;
  }

  
  function getFont(){
    return $this->FONT;
  }

 
  function getBold(){
    return $this->BOLD;
  }

 
  function getItalic(){
    return $this->ITALIC;
  }

 
  function getUnderline(){
    return $this->UNDERLINE;
  }


}

?>