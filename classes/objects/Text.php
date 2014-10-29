<?php
//FileNAME: Text.php

class Text extends Object {
  public $TEXT;
  public $FILTER;

  function Text($t, $s=2, $b=false, $i=false, $u=false, $f=true){
     $this->NAME = "Text";
     $this->border = 0;
     
     $this->TEXT = $t;
     $this->setFILTER($f);
 
     $ftX = new FontType();
     $ftX->setBold($b);
     $ftX->setItalic($i);
     $ftX->setUnderline($u);
     $ftX->setFontsize($s);
     $this->setFonttype($ftX);   
  }


  

  function setFontsize($s){
    $this->setStyle("font-size",$s);
    $this->getFonttype()->setFontsize($s);
  }

  function getFontsize(){
    return  $this->getFonttype()->FONTSIZE;
  }

  function setText($t){
    $this->TEXT = $t;
  }

  function getText(){
     $t = rtrim($this->TEXT);   //abschlieÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂende Leerzeichen entfernen

     if ($this->FILTER) {
         $t = htmlspecialchars( $t, ENT_QUOTES );   // Sonderzeichen austauschen
         $t = strip_tags($t);                       //html & php-Tags entfernen
         $t = stripslashes ($t);                    //ZeichenschÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼tzende slashes entfernen
     }
   
     $t = $this->replaceTags($t);

     return $t;
  }



  function changeForSQL($replaceMarker=false){
  	$tmp = $this->TEXT;
	$this->TEXT = getSqlConformString($tmp, $replaceMarker);	
  }





/**
 * ersetzt in dem ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenen Text die Tags und liefert den geÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¤nderten String zurÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼ck
 * fÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼hrt ebenfalls replaceSmileys($txt) aus und bindet die entsprechenden Grafiken sofort mit ein   
 */ 
  function replaceTags($txt){
    // Nach Tags suchen und ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¤ndern
    $sqlTags = "SELECT * from tags ";
    $resultTags =    $_SESSION['config']->DBCONNECT->executeQuery($sqlTags);

    while($rowTags = mysql_fetch_array($resultTags))  {
      $suchen = "[" .$rowTags['tag'] ."]";
      $repl   = $rowTags['html'];
      $txt    = str_replace($suchen, $repl, $txt);	
    }
    $txt = $this->replaceSmileys($txt);
    return $txt;
  }



/**
 * ersetzt in dem ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼bergebenen Text die Smileys durch <img>-Tags zu den Entsprechenden Bildern 
 * und liefert den geÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¤nderten String zurÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¼ck
 */ 
  function replaceSmileys($txt){
    // Nach Tags suchen und ÃÂÃÂÃÂÃÂÃÂÃÂÃÂÃÂ¤ndern
    $sqlTags = "SELECT * from smileys ";
    $resultTags =    $_SESSION['config']->DBCONNECT->executeQuery($sqlTags);

    while($rowTags = mysql_fetch_array($resultTags))  {
      $suchen = $rowTags['title'];
      $repl   = "<img src='".$rowTags['link']."'> ";
      $txt    = str_replace($suchen, $repl, $txt);	
    }
    
    return $txt;
  }


  function getFilter(){
     return $this->FILTER;
  }


  function setFilter($f){
     $this->FILTER = $f;
  }






  function  show(){
  	echo "
	  ";
    if($this->XPOS!=0 && $this->YPOS!=0){
      echo "
	  <div ";

      if($this->XPOS>0 && $this->YPOS>0){
        echo "style=\"  position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; \" ";
      }

      if($this->XPOS<0 && $this->YPOS>0){
        echo "style=\"  position:absolute; top:" .$this->YPOS ."px; right:" .$this->XPOS*(-1) ."px; \" ";
      }

      if($this->XPOS<0 && $this->YPOS<0){
        echo "style=\"  position:absolute; bottom:" .$this->YPOS*(-1) ."px; right:" .$this->XPOS*(-1) ."px; \" ";
      }

      if($this->XPOS>0 && $this->YPOS<0){
         echo "style=\"  position:absolute; bottom:" .$this->YPOS*(-1) ."px; left:" .$this->XPOS ."px; \" ";
      }
      
      echo ">";
    }


    if( $this->getFontsize() > 0 ||  strlen($this->getFonttype()->getColor())>0){
      echo "<font ";
	  
	  if( $this->getFontsize() > 0){
	    echo " size='" .$this->getFontsize() ."' " ;
	  }
	  
  	  if(strlen($this->getFonttype()->getColor())>0 ){
	    echo " color='" .$this->getFonttype()->getColor() ."' "; 
	  }
	  
	  echo $this->getToolTipTag();
	  
	  echo " >";
    }
    
    
    if( $this->getFonttype()->BOLD ){
      echo "<b>";
    }
    if( $this->getFonttype()->ITALIC ){
      echo "<i>";
    }
    if( $this->getFonttype()->UNDERLINE ){
      echo "<u>";
    }
//--------------------
// Wenn keine <br> Tags vorhanden, 
// Zelenumbruch in <br> wandeln
// Sonst nicht wandeln
    if(!strpos($this->getText(), '<br')){
      echo nl2br($this->getText());
    } else {
      echo $this->getText();
    }
 
//--------------------
    if( $this->getFonttype()->UNDERLINE ){
      echo "</u>";
    }
    if( $this->getFonttype()->ITALIC ){
      echo "</i>";
    }
    if( $this->getFonttype()->BOLD ){
      echo "</b>";
    }
    if( $this->getFontsize() > 0 ||  strlen($this->getFonttype()->getColor())>0){
      echo "</font>";
    }
    if($this->XPOS!=0 && $this->YPOS!=0){
      echo "</div>
	  ";
    }
    
      	echo "
	  ";
  }



}