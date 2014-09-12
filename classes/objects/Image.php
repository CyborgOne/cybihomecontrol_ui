<?php
//FileNAME: Image.php

class Image extends Object {
  var $SOURCE;
  var $VSPACE;
  private $GENERATED;
  private $COMMENTS_ACTIVE;

  function Image($source = "", $x=-1, $y=-1, $w=150, $h=0, $b=0){
  	if($source == ""){
	  $source = "" ;
	/*  if($s == "/"){
		$s .= "pics/unknownpic.jpg";
	  } else {*/
		$source = $source."/pics/unknownpic.jpg";
	  //}
	}
    //Defaults setzen
     $this->NAME = "Image";
     $this->XPOS = $x;
     $this->YPOS = $y;
     $this->WIDTH = $w;
     $this->HEIGHT = $h;
     $this->setBorder($b);
     $this->setCommentsActive(false);
     $this->styleClass = "image";
     $this->setSource($source);
     $this->GENERATED = true;
     $this->ALIGN = "center";
     $this->VSPACE = 0;

  }




  function getImageRealWidth(){
	$imgSizeArr = getimagesize($this->SOURCE);
	return $imgSizeArr[0];
  }


  function getImageRealHeight(){
	$imgSizeArr = getimagesize($this->SOURCE);
	return $imgSizeArr[1];
  }


 /**
  *  gibt an (volle Pfadangabe) welches Bild angezeigt werden soll
  */ 
  function setSource($s){
  	// Endet DocumentRoot mit einem Slash? 
  	if(substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['DOCUMENT_ROOT'])-1, 1) != "/" ){
	  // FÃÂÃÂÃÂÃÂ¼hrendes Slash notfalls hinzufÃÂÃÂÃÂÃÂ¼gen
	  	if(substr($s, 0, 1) == "/"){
	      $this->SOURCE = $s;
	    } else {
		  $this->SOURCE = "/".$s;	
		}
	} else {
	  // FÃÂÃÂÃÂÃÂ¼hrendes Slash notfalls entfernen
	  	if(substr($s, 0, 1) == "/"){
	      $this->SOURCE = substr($s, 1);
	    } else {
		  $this->SOURCE = $s;	
		}

	}
  }

 /**
  *  gibt an (boolean) ob die Datei dynamisch neu generiert oder direkt angesprochen werden soll
  */ 
  function setGenerated($g){
    $this->GENERATED = $g;
  }


 /**
  * De-/Aktiviert Die Kommentar-Funktion mittels Tooltip 
  * 
  * @param $bool boolean true = aktiv / false = inaktiv  
  */ 
  function setCommentsActive($bool){
    $this->COMMENTS_ACTIVE = $bool;
  }




  /**
   *  liefert die volle Pfadangabe des Bildes, welches angezeigt werden soll
   */ 
  function getSource(){
    return $this->SOURCE;
  }
  
    




 /**
  * erzeugt ein IMG-Objekt der Grafik (Orginal wird angezeigt. Keine generierung)
  * 
  * @param $p relative Pfadangabe  
  */
  function generatePicture ($p){

    if(($this->COMMENTS_ACTIVE)){    
      checkForInsertBBComment();
	  $this->setToolTip(getCommentDialogForImage($this->getSource()) .getCommentsListForImage($this->getSource()) );
	}

  	$imgSrc = $this->getImgSrc($p);
    
    echo $imgSrc;
  }
  
   
  function getImgSrc($p){
     $ret = "<img vspace='".$this->VSPACE."' align='".$this->ALIGN."' "; 

    if($this->WIDTH>0){
      $ret = $ret ." WIDTH='" .$this->WIDTH ."' ";
    }
      
    if($this->HEIGHT>0){
	   $ret = $ret ." HEIGHT='" .$this->HEIGHT ."' ";
    }
    

    $ret = $ret . $this->getStyleString();
   
 	if(substr($p,0,1)=="/"){
    	$p = substr($p,1);
    }


    $ret = $ret ." src='" .$p ."' "
           ."NAME='" .$this->NAME ."' ";
        
    if($this->XPOS>=0 && $this->YPOS>=0){        
      $ret = $ret ."style=\"  position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; \" ";
    }
    
    if(($this->COMMENTS_ACTIVE)){ 
	  if($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->CURRENTUSER->STATUS == "user"){	   
	    $ret = $ret .$this->getToolTipFixedTag();
	  } else {
	    $ret = $ret .$this->getToolTipTag();
	  }
	} else {
	    $ret = $ret .$this->getToolTipTag();
	}
	     
    $ret = $ret ." >";
    
    return $ret;
  }
  
  
  

 /**
  * Erzeugt eine aus der grafik-datei generierte grafik in angeforderter grÃÂÃÂÃÂÃÂ¶ÃÂÃÂÃÂÃÂe
  * ÃÂÃÂÃÂÃÂndert den Source des Bildes in eine php-Datei welche das neue Bild generiert. 
  * Zur Anzeige wird weiterhin die Methode  generatePicture($p) genutzt.
  */
  function show_generated(){
    $picLink = "";
    
    if( strstr(strtolower($this->SOURCE), "jpg") ){
      $picLink = "includes/pictures/generateBBThumb.inc.php?picname=" .$this->SOURCE;
    } else     if( strstr(strtolower($this->SOURCE), "png") ){
      $picLink = "includes/pictures/generateBBThumbPng.inc.php?picname=" .$this->SOURCE;		
	} else     if( strstr(strtolower($this->SOURCE), "gif") ){
      $picLink = "includes/pictures/generateBBThumbGif.inc.php?picname=" .$this->SOURCE;		
	}
    
    if($this->WIDTH>0){
      $picLink .= "&picwidth=" .$this->WIDTH;
    } else if($this->HEIGHT > 0) {
      $picLink .= "&picwidth=" .round($this->HEIGHT*1.5);   //wenigstens grobe anpassung
    }

    $this->generatePicture($picLink);
  }


  function existsFile(){
     $sn = dirname($_SERVER['SCRIPT_NAME']);
     return  (  file_exists($_SERVER['DOCUMENT_ROOT'] .$this->SOURCE) 
               || file_exists($_SERVER['DOCUMENT_ROOT'] .$sn."/".$this->SOURCE)
             );
  }

 /**
  * Default-Anzeige-Methode
  */  
  function  show(){
    $sn = dirname($_SERVER['SCRIPT_NAME']);
		// Endet DocumentRoot mit einem Slash? 
  	if(substr($_SERVER['DOCUMENT_ROOT'], strlen($_SERVER['DOCUMENT_ROOT'])-1, 1) != "/" ){
	  // FÃÂÃÂÃÂÃÂ¼hrendes Slash notfalls hinzufÃÂÃÂÃÂÃÂ¼gen
	  	if(substr($sn, 0, 1) == "/"){
	      $sn = $sn;
	    } else {
		  $sn = "/".$sn;	
		}
	} else {
	  // FÃÂÃÂÃÂÃÂ¼hrendes Slash notfalls entfernen
	  	if(substr($sn, 0, 1) == "/"){
	      $sn = substr($sn, 1);
	    } else {
		  $sn = $sn;	
		}
	}
  	if(!$this->existsFile()){
	  $this->setWidth(0);
	  $this->setHeight(0);
   
      $this->generatePicture($sn."/pics/unknownpic.jpg");
	  return;
	}
  	
  	
  	if($this->GENERATED === true){
      $this->show_generated();
    } else {
	  $this->generatePicture($this->SOURCE);	
	}
  }


}
