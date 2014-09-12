<?php
//FileNAME: PasswordField.php

class PasswordField extends Textfield {
   function PasswordField($n="PasswordField", $v="", $w=10, $m=20){
     $this->NAME = $n;
     $this->VALUE = $v;
     $this->XPOS = 0;
     $this->YPOS = 0;
     $this->WIDTH = $w;
     $this->MAX = $m;
  }

  function  show(){
    echo "<input type='PASSWORD' NAME='" .$this->NAME ."' value='" .$this->VALUE ."'  size='" .$this->WIDTH  ."' maxlength='" .$this->MAX ."'>";
  }



}