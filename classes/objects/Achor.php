<?php

/**
 * Mit der Klasse Anchor lÃÂÃÂÃÂÃÂ¤sst sich ein <a name HTML-Objekt erzeugen 
 *  
 * @author Daniel Scheidler 
 * @copyright Mai 2008
 */

class Anchor extends Object {
  private $CONTENT;

  function Anchor ($name){
    $this->NAME = $name;
  }
  
  
  
  /**
  * Hier wird der Inhalt des Anchors gesetzt.
  * 
  * Der ÃÂÃÂÃÂÃÂ¼bergebene Inhalt wird zwischen dem Start-Tag (<a...>) und dem Ende-Tag (</a>) angezeigt
  * Der ÃÂÃÂÃÂÃÂ¼bergebene Inhalt muss vom Typ Object abgeleitet sein und eine show()-Methode enthalten  
  */      
  function setContent($c){
    if(is_subclass_of($c,"Object") && method_exists($c, "show")) {
		$this->CONTENT = $c;
	}	
  }
  
  
  
  
  
  
 
  /**
  * Standard-Angezeige.
  *
  * ist ein Content definiert, wird dieser ebenfalls mit angegeben.   
  */      
 
  function  show(){
    echo "<a name='" .$this->NAME ."' ";
    
    if($this->XPOS>0 && $this->YPOS>0){        
       echo " style=\"  position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; \" ";
    }

    if($this->XPOS<0 && $this->YPOS>0){        
       echo " style=\"  position:absolute; top:" .$this->YPOS ."px; right:" .$this->XPOS*(-1) ."px; \" ";
    }

    if($this->XPOS<0 && $this->YPOS<0){        
       echo " style=\"  position:absolute; bottom:" .$this->YPOS*(-1) ."px; right:" .$this->XPOS*(-1) ."px; \" ";
    }

    if($this->XPOS>0 && $this->YPOS<0){        
       echo " style=\"  position:absolute; bottom:" .$this->YPOS*(-1) ."px; left:" .$this->XPOS ."px; \" ";
    }
  
    echo  $this->getToolTipTag()
	.">";

    if(get_class($this->TEXT) && method_exists($this->TEXT, "show") ){
      $this->TEXT->show();
    } else {
      $t = new Text($this->TEXT);
      $t->setFilter(false);
      $t->show();
    }


    echo "</a>";
  }

}

?>