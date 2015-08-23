<?php
//FileNAME: Button.php

class Button extends Object {
  var $NAME;
  var $VALUE;
  var $ONCLICK;
  var $WIDTH;
  var $HEIGHT;
  var $ONCLICKTYPE;
  
  function Button($name, $value, $onClick=''){
    $this->NAME = $name;
    $this->VALUE = $value;
    $this->ONCLICK = $onClick;
    $this->XPOS = -1;
    $this->YPOS = -1;
    $this->WIDTH = -1;
    $this->HEIGHT = -1;
    $this->ONCLICKTYPE = "Button";
  }

  function setOnClickType($v){
    $this->ONCLICKTYPE = $v;	
  }


  function Show(){
    if(strlen($this->ONCLICK)>0){
       $this->showOnKlickButton();
    } else {
	$this->showForForm();
    }
 
  }

  function showForForm(){
	echo "<INPUT   TYPE='SUBMIT'  ";
    
	if(strlen($this->NAME)>0){
	  echo " NAME='" .$this->NAME ."' ";
         echo " id='" .$this->NAME ."' ";

       }  
	
	echo " VALUE='" .$this->VALUE ."' ";
    
	echo $this->getToolTipTag();

       echo "style=\" ";

       if($this->XPOS>=0 && $this->YPOS>=0){        
         echo " position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; ";
       }
       if($this->WIDTH>=0 && $this->HEIGHT >=0){        
         if($this->WIDTH != 0 && $this->HEIGHT != 0){  
           echo " width:" .$this->WIDTH ."px; height:" .$this->HEIGHT ."px; ";
         } else {
           echo " display: none; ";  
         }
       } else {

       }

       echo " \" >";
  }

  function showOnKlickButton(){
    echo "	
 	<input type    = '" .$this->ONCLICKTYPE ."' 
              name    = '" .$this->NAME  ."' 
              value   = '" .$this->VALUE  ."'
              onClick = \""  .$this->ONCLICK ."\" ";

    echo $this->getToolTipTag();

    echo "style=\" ";

    if($this->XPOS>=0 && $this->YPOS>=0){        
      echo " position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; ";
    }

    if($this->WIDTH>=0 && $this->HEIGHT >=0){        
      echo " width:" .$this->WIDTH ."px; height:" .$this->HEIGHT ."px;  ";
    }

       echo "\" >";
  }

}






/*
 Formularen kann man statt eines SUBMIT auch ein IMAGE als Absendeknopf installieren. Dies sieht dann so aus:

<input type="image" src="meinbild.png" name="sub" />

Wenn der User das Bild anklickt, werden zwei Variablen mit den Namen sub.x und sub.y erzeugt, 
die die Koordinaten des Klicks relativ zur linken, oberen Ecke des Bildes beschreiben. 
Da Variablennamen in PHP keine Punkte enthalten dÃÂÃÂÃÂÃÂ¼rfen, wandelt PHP die Punkte in Unterstriche um. 
Im Beispiel bekommt man die Variablen mit den Namen $_REQUEST['sub_x'] und $_REQUEST['sub_y'] ÃÂÃÂÃÂÃÂ¼bergeben 
(bzw. vor PHP 4.1.0 $HTTP_GET_VARS['sub_x'], etc. oder $HTTP_POST_VARS['sub_x']). 

*/


?>