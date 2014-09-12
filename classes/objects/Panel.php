<?php

/**
 * @filename Panel.php
 * @author  Daniel Scheidler
 * @copyright April 2008
 */

class Panel extends Object {
  private $BGCOLOR;
  private $CONTENT;

  private $PANELTITELHEIGHT;
  private $PANELTITELLEFTWIDTH;
  private $PANELTITELRIGHTWIDTH;

  
  
  function Panel($Name){
     $this->NAME = $Name;
     $this->FONTTYPE = new FontType();
 
     $this->PANELTITELHEIGHT = 25;
     $this->PANELTITELLEFTWIDTH = 5;
     $this->PANELTITELRIGHTWIDTH = 5;

     $this->setWidth(150);
     $this->BGCOLOR = $_SESSION['config']->COLORS['panel_background'];
  }
  
  
    
  function setContent($object){
    if(method_exists($object,"show")){
       $this->CONTENT = $object;
    }else{
	  $e = new Error("Fehler in Panel.setContent()","Das mitgegebene Objekt enthÃÂÃÂÃÂÃÂ¤lt keine show-Methode.");
	}
  }

  

   /**
  *  Zeigt das gesamte Panel an
  */
  function show(){
  	  $this->CONTENT->setWidth($this->WIDTH-(8*$this->PANELTITELRIGHTWIDTH));
      
      //---------------------------------------------------
      // Inhalt
      //---------------------------------------------------
      
      $cont = new Div();
      $cont->setBorder(1);
      $cont->setWidth($this->WIDTH - (2*$this->PANELTITELRIGHTWIDTH));
      if($this->HEIGHT>0){
        $cont->setHeight($this->HEIGHT - $this->PANELTITELHEIGHT);
      }
      $cont->setOverflow("auto");
      $cont->setStyle("background-color", $this->BGCOLOR);
      $cont->setStyle("border-style", "solid");
	  $cont->setStyle("border-top", "1px");
      $cont->setStyle("border-left", "1px");
      $cont->setStyle("border-right", "2px");
      $cont->setStyle("border-bottom", "2px");
      $cont->setStyle("border-color", $_SESSION['config']->COLORS['text']);

      $cont->add($this->CONTENT);
      //---------------------------------------------------

 	  $tb = new Table(array(""));
 	  $rH = $tb->createRow();
 	  $rH->setAlign("center");
 	  $rH->setBackgroundColor($_SESSION['config']->COLORS['text']);
 	  $rH->setFontType($_SESSION['config']->COLORS['text']);
 	  
	  $rH->setAttribute(0, $this->NAME);
	  
	  $tb->addRow($rH);
	  
	  $rC = $tb->createRow();
 	  $rC->setAlign("center");
	  $rC->setAttribute(0, $cont);
	  
	  $tb->addRow($rC);
	  
	  return $tb;
	  
  }
}


?>
