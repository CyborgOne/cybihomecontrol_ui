<?php
/**
* Mit der Klasse Link lÃÂÃÂÃÂÃÂ¤sst sich ein <a href HTML-Objekt erzeugen 
* 
* @author Daniel Scheidler 
* @copyright Mai 2008
*/

class Link extends Object {
  var $HREF;
  var $TEXT;
  var $TARGET;  
  var $BUTTONACTIVE;
  var $ONCLICK;
  private $TIMESTAMP_ADD;
  private $VALIDATE;
  private $POPUP;
  private $ANCHOR_ACTIVE; 

  function Link($ziel, $text='',$bt=false, $target="", $onclick="", $validateLnk=true, $tmstmpAdd=true){
    $this->NAME = "Link";
    $this->XPOS = 0;
    $this->YPOS = 0;
    $this->HREF = $ziel;
    $this->TARGET = $target;
    if(method_exists($text, "show") || strlen($text)>0){
	  if(method_exists($text, "show")){
	    $this->TEXT = $text;
      } else {
		$this->TEXT = new Text($text); 
	  }
	} else {
	  $this->TEXT = $ziel;
	}
    $this->VALIDATE = $validateLnk; 
    $this->TIMESTAMP_ADD = $tmstmpAdd;
    
    $this->BUTTONACTIVE = $bt;
    $this->POPUP = false;
    $this->setOnclick($onclick);
    $this->setAnchorActive(true); 
    
    if($this->HREF != "#" && $this->TIMESTAMP_ADD===true){ 
    	if(strpos(" ".$this->HREF,"?")>0){
    		$tmp = $this->HREF;
    		$this->HREF = $tmp."&tmstmp=".time();
    	}else{
    		$tmp = $this->HREF;
    		$this->HREF = $tmp."?tmstmp=".time();
    	}
    }
  }


  function setAnchorActive($v){
     $this->ANCHOR_ACTIVE = $v;	
  }

  function getAnchorActive(){
     return $this->ANCHOR_ACTIVE;	
  }


  function setValidate($v){
     $this->VALIDATE = $v;	
  }

  function getValidate(){
     return $this->VALIDATE;	
  }

  function setPopup($p){
  	if( $p == true || $p == false ){
      $this->POPUP = $p;
    } else {
      $this->POPUP = false;		
	}	
  }

  function getPopup(){
     return $this->POPUP;	
  }


  function setOnclick($o){
     $this->ONCLICK = $o;	
  }
  
  function getOnclick(){
     return $this->ONCLICK;	
  }

  function validateLink(){
	// WENN KEIN TEXT GEFÃÂÃÂÃÂÃÂLLT -> ZIEL ANZEIGEN
    if( !method_exists($this->TEXT, "show") && strlen($this->TEXT)==0){
    	$this->TEXT = $this->HREF;
    }


    if (!$this->getValidate()){
		return;
	}
    $currentPath  =  dirname($_SERVER['SCRIPT_NAME']);
    
    if(substr($currentPath,strlen($currentPath)-1) != "/" && strlen($currentPath)>1){
      $currentPath .= "/";
    }

    // WENN LOKALER LINK -> VERVOLLSTÃÂÃÂÃÂÃÂNDIGEN
    if (strtolower(substr($this->HREF,0,7)) != "http://" &&
        strtolower(substr($this->HREF,0,3)) != "www" &&
        strtolower(substr($this->HREF,0,6)) != "ftp://" &&
        $this->HREF != "#" && $this->HREF != ""
		){
	$pageProt = getPageConfigParam($_SESSION['config']->DBCONNECT, "https_active");
	
	if($pageProt === true || $pageProt == "true"){
	  $protokoll = "https://";
	} else {
	  $protokoll = "http://";

	}

        $this->HREF = $protokoll .$_SERVER['HTTP_HOST'] .$currentPath.$this->HREF;
    }
  }

  function getTarget(){
    return $this->TARGET;
  }

  function setTarget($t){
    $this->TARGET = $t;
  }

  function getHref(){
    return $this->HREF;
  }

  function setHref($h){
    $this->HREF = $h;
  }

  function getText(){
    return $this->TEXT;
  }

  function setText($t){
    $this->TEXT = $t;
  }







  /**
  * Zeigt den Link an, welcher das Ziel als Popup ÃÂÃÂÃÂÃÂ¶ffnet.
  *  wird von der Methode show() aufgerufen wenn $this-POPUP == true 
  */   
  private function showPopupLink(){
    echo "<a href='#' ";

    if(strlen($this->TARGET)>0){
      echo "target='" .$this->TARGET ."' ";
    }
    if ($this->XPOS>0 || $this->YPOS>0) {    
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
    } else {
		$this->showStyles();
	}
	
	if(isset($this->BORDER) && $this->BORDER>=0){
      echo " border='" .$this->BORDER ."' ";
    }
    
    echo $this->getClassTag();
    
    $href = str_replace("?", "&", $this->HREF);
    
    echo  $this->getToolTipTag() 
	     ." onclick=\"showPopup('popup.php?popup=" .$href ."'); return false; \"  >";

    if(get_class($this->TEXT) && method_exists($this->TEXT, "show") ){
//      $this->TEXT->setFilter(false);
      $this->TEXT->show();
    } else {
      $t = new Text($this->TEXT);      
      $t->setFilter(false);
      $t->show();
    }


    echo "</a>";

  }
  
  

  function  showWithAnchor(){
     $a = new Anchor($this->NAME);
  }



  function  show(){
  	if($this->POPUP){
		$this->showPopupLink();
		return;
	}


    $this->validateLink();
    
    echo "<a href='" .$this->HREF ."' ";

    if(strlen($this->TARGET)>0){
      echo " target='" .$this->TARGET ."' ";
    }

    if(isset($this->BORDER) && $this->BORDER>=0){
      echo " border='" .$this->BORDER ."' ";
    }

    if ($this->XPOS>0 || $this->YPOS>0) {    
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
    } else {
		$this->showStyles();
	}
    
    
    echo $this->getClassTag();
  
    echo  $this->getToolTipTag();
	
	if (isset($this->ONCLICK) && strlen($this->ONCLICK)>0){ 
	  echo " onclick=\"" .$this->ONCLICK ."\" ";
	}
	
	
	echo " >";
    
    
    if(is_object($this->TEXT) && get_class($this->TEXT) && method_exists($this->TEXT, "show") ){
      $t = $this->TEXT;
      $t->show();

    } else {
      $t = new Text($this->TEXT);
      $t->show();
    }


    echo "</a>";
  }


  function setFontsize($s){
    $this->setStyle("font-size",$s);
    $this->getFonttype()->setFontsize($s);
  }

}