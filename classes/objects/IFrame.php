<?php
//FileNAME: IFrame.php

class IFrame extends Object {
  var $CONFIG;
  var $DBCONNECT;
  var $BORDER;
  var $SOURCE;
  var $RUNLINK;
  var $SCROLLING_ACTIVE;

  function IFrame(&$conf,$n,$x=-1,$y=-1,$w=400,$h=200,$b=0){
    //Defaults setzen
     
     $this->NAME = $n;
     $this->XPOS = $x;
     $this->YPOS = $y;
     $this->WIDTH = $w;
     $this->HEIGHT = $h;
     $this->BORDER = $b;
     $this->SCROLLING_ACTIVE = true;
     $this->styleClass = "iframe";
     $this->CONFIG  =& $conf;
     $this->DBCONNECT =& $this->CONFIG->DBCONNECT;
     $this->RUNLINKS = new RunLinks($this->DBCONNECT, $n);
  }


  function setBorder($size){
    $this->BORDER = $size;
  }


 function setScrollingActive($w){
    $this->SCROLLING_ACTIVE = $w===true?true:false;
  }

 function getScrollingActive(){
    return $this->SCROLLING_ACTIVE;
  }
 
 function setSource($w){
    $this->SOURCE = $w;
  }

 function getSource(){
    return $this->SOURCE;
  }
 function setName($h){
    $this->NAME = $h;
  }




  function getSourceByRun($run){
    return $this->RUNLINKS->getLinkByName($run);
  }

  function setSourceByRun($run){
    $s = $this->RUNLINKS->getLinkByName($run);

    $this->SOURCE = $s;
  }


  function  show(){
    echo "
          <iframe WIDTH='" .$this->WIDTH ."' "
                  ."HEIGHT='" .$this->HEIGHT ."' "
                  ."frameBORDER='" .$this->BORDER ."' "
                  ."src='" .$this->SOURCE ."' "
                  ."NAME='" .$this->NAME ."' ";
				  
				  $this->setStyle("spacing","0px");
				  $this->setStyle("padding","0px");
				  
				  if($this->YPOS>0 || $this->XPOS>0){
				    $$this->setStyle("position","absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px;  ");
				  }
				  
				  $this->showStyles();
				  
				  echo "\" class='" .$this->styleClass ."'> "
       ."</iframe> 
    ";
  }


  
}