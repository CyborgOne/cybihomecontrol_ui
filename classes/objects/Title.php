<?php
//FileNAME: Title.php

class Title extends Object{
  var $TEXT;
  var $BORDER;
  var $CONFIG;
  var $FILTER;
  var $FONTTYPE;

  function Title( $text, $b=0, $size=4, $bold=false, $italic=false, $under=false, $filter=true){
    $this->TEXT = $text;
    $this->setAlign("center");
    $this->BORDER = $b;
    $this->CONFIG = $_SESSION['config'];
    
    $this->FONTTYPE = new FontType();
    $this->FONTTYPE->setBold($bold);
    $this->FONTTYPE->setItalic($italic);
    $this->FONTTYPE->setUnderline($under);
    $this->FONTTYPE->setFontsize($size);
    
    $this->setFilter($filter);
    
	$this->FONTTYPE->setColor("");
  }


  function getFilter(){
     return $this->FILTER;
  }


  function setFilter($f){
     $this->FILTER = $f;
  }

  function getText(){
     return $this->TEXT;
  }

  function setText($t){
     $this->TEXT = $t;
  }




  function show(){
	$t = new Table(array(""));
	$t->setAlign($this->getAlign());
    $r = $t->createRow();
    $r->setHeader(true);
    
    $txt = new Text($this->TEXT);
    $txt->setFonttype($this->FONTTYPE);
    $txt->setFilter($this->getFilter());
	
    $r->setAttribute(0, $txt);
    $t->addRow($r);
    
    $t->show();
  }

}