<?php
//FileNAME: Hiddenfield.php

class Hiddenfield extends Object {
  var $VALUE="";

  function Hiddenfield($n="Hiddenfield", $v="", $w=30, $m=100){
     $this->NAME = $n;
     $this->VALUE = $v;
     $this->XPOS = 0;
     $this->YPOS = 0;
     $this->WIDTH = $w;
     $this->MAX = $m;
  }


  function  show(){
    echo "<input id=\"" .$this->NAME ."\" type=\"hidden\" NAME=\"" .$this->NAME ."\" value=\"" .$this->VALUE ."\"  size=\"" .$this->WIDTH  ."\" maxlength=\"" .$this->MAX ."\">";
  }



}
?>