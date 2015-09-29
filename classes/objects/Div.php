<?php

/**
*  DIV
*/
Class Div extends Object {
  public $OBJECTS;  /*Array of Objects*/
  private $OVERFLOW; 
  private $ID; 

 
  /**
  *  DIV Konstruktor 
  */
  function Div($id="", $width="99%", $height=0){
    $this->OBJECTS = array();
    $this->setOverflow("auto");  //hidden, visible, scroll
    $this->setWidth($width);
    $this->setHeight($height);
    $this->setBorder(0);
    $this->setAlign("left");
    $this->setVAlign("top");
    
    $this->ID = $id;
	
    if( isset($this->OVERFLOW) && strlen($this->OVERFLOW) > 0){
      $this->setStyle("overflow", $this->OVERFLOW);
    }

    if( isset($this->WIDTH) && $this->WIDTH > 0){
      $this->setStyle("width", $this->WIDTH);
    }

    if( isset($this->HEIGHT) && $this->HEIGHT > 0 ){
      $this->setStyle("height", $this->HEIGHT);
    }
  	
    if($this->XPOS>0 || $this->YPOS>0){
      $tmp = ":absolute; ";
      
      if ($this->YPOS>0){
	 $tmp .= " top:" .$this->YPOS ."px; ";
      }
      
      if ($this->XPOS>0){
        $tmp .= " left:" .$this->XPOS ."px; ";
      }
		   
      $this->setStyle("position", $tmp);
    }

  }


  function setWidth($w){

    if($w > 0) {
      $this->setStyle("width", $w);
      $this->WIDTH = $w;
    }

  }

  

  function setHeight($w){

    if($w > 0) {
      $this->setStyle("height", $w);
      $this->HEIGHT = $w;
    }

  }


	

  function setBorder($b){
    $this->BORDER = $b;
	if( isset($b) && $b>=0){
	  $this->setStyle("border-left", $b."px");
  	  $this->setStyle("border-right", $b."px");
  	  $this->setStyle("border-bottom", $b."px");
  	  $this->setStyle("border-top", $b."px");
      
	  $this->setStyle("border-style", "solid");
	  $this->setStyle("border-color", $_SESSION['config']->COLORS['text']);
  	}
  }

  function setOverflow($w){
    if($w == "hidden") {
      $this->setStyle("overflow", "hidden"); 

	} else if($w == "visible") {
         $this->setStyle("overflow", "visible");   
   	} else if ($w == "scroll") { 
         $this->setStyle("overflow",  "scroll");
	} else { 
         $this->setStyle("overflow", "auto");
	}
  }


  function setAlign($w){
    if($w == "center" || $w == "right") {
      $this->setStyle("horizontal-align", $w);
      $this->setStyle("text-align", $w);
      
    } else  {
      $this->setStyle("horizontal-align", "left");
      $this->setStyle("text-align", "left");
	}
  }


  function setVAlign($w){
    if($w == "middle" || $w == "bottom") {
      $this->setStyle("vertical-align", $w);
      $this->setStyle("text-valign", $w);
      $this->VALIGN = $w;
    } else  {
      $this->setStyle("vertical-align", "top");
      $this->setStyle("text-valign", "top");
      $this->VALIGN = "top";
    }
  }






  function setFonttype($f){
    $this->FONTTYPE = $f;
    $counter = 0;
    if(count($this->OBJECTS)>0){
	    foreach($this->OBJECTS as $OBJ){
	      $OBJ->setFonttype($this->FONTTYPE);
	      $counter++;
	    }
	}   
  }


  /**
  * fügt das übergebene Element in das OBJECTS Array ein.
  * Das übergebene Objekt muss eine show Methode enthalten!
  */
  function add($object){
    if( method_exists($object, "show") ){
	  $this->OBJECTS[count($this->OBJECTS)] = $object;	
	} else {
	  $e = new Error("Nicht anzeigbares Objekt", "Es wurde versucht ein Objekt ohne 'show'-Methode in das DIV einzufügen!");	
	}
  }

  

  /**
  * Liefert die Anzahl der aktuell enthaltenen Objekte zurück
  */
  function getObjectCount(){
    return count($this->OBJECTS);
  }  

  /**
  * Liefert das angeforderte Objekt zurück
  */
  function getObject($i){
    return $this->OBJECTS[$i];
  }


  /**
  * Zeigt das gesamte DIV an
  */
  function show(){
		
    echo "<div ";
    
    if($this->ID !=""){
		echo " id=" .$this->ID ." ";
	}
	
	$this->showStyles();
     
    echo $this->getToolTipTag();
	
	echo " >";
	
    if( $this->getObjectCount() > 0){
      foreach($this->OBJECTS as $obj)	{
	    $obj->show();
      }
    }
	
    echo "</div>";

  }

}



Class DivByInclude extends Div {
  /**
  *  DIV Konstruktor 
  */
  var $FILE;
  var $KOPFTEXT_NEED;
  
  function DivByInclude($file, $kopftextJN=true){
    $this->FILE = $file; 
    $this->OVERFLOW = "visible";  //hidden, visible, scroll
    $this->setWidth($_SESSION['mainpanelwidth']);
    $this->setHeight(0);
    $this->ALIGN = "top";
    $this->VALIGN = "center";
    $this->BORDER = 0;
    $this->KOPFTEXT_NEED = $kopftextJN;
  }


  /**
  * Zeigt das gesamte DIV an
  */
  function show(){
    echo "<div ";

    if($this->XPOS>0 || $this->YPOS>0){
    	  $tmp = ":absolute; ";
		  if ($this->YPOS>0){
			$tmp .= " top:" .$this->YPOS ."px; ";
		  }
		  if ($this->XPOS>0){
			$tmp .= " left:" .$this->XPOS ."px; ";
		  }
		   
         $this->setStyle("position", $tmp);
    }
	$this->getToolTipTag();
    $this->showStyles();
    
    echo " > ";
    
    
    if( $this->KOPFTEXT_NEED ){
        
       /*
        * Bei berechtigung, Kopftext bearbeiten.
        */
        if($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->CURRENTUSER->STATUS == "user"){	  
    	    
    		if( isset($_REQUEST['changeHeadInfo']) && strlen($_REQUEST['changeHeadInfo'])>5 ){
    			$ktRl = substr($_REQUEST['changeHeadInfo'],5);
    			
    			$dbtbl = new DbTable($_SESSION['config']->DBCONNECT, 
    			                     "kopftexte", 
    								 array("text"), 
    								 "", 
    								 "", 
    								 "", 
    								 "runlink = '" .$ktRl ."' " );
    			
    			if($dbtbl->getRowCount()==0){
    				$dbtblInsert = new DbTable($_SESSION['config']->DBCONNECT, 
    						                     "kopftexte", 
    											 array("runlink", "parent", "text"), 
    											 "", 
    											 "", 
    											 "", 
    											 "runlink = '" .$ktRl ."' " );
    				
    				$dbtblInsert->insertRowByArray(array($ktRl, null, ""));
    				$dbtbl->refresh();
    			}
    			
    			if( isset($_REQUEST['DbTableUpdate'.$dbtbl->TABLENAME]) && $_REQUEST['DbTableUpdate'.$dbtbl->TABLENAME] == "Speichern" ){
    			  $dbtbl->doUpdate();
    		    }
    		    
    			$frm = $dbtbl->getUpdateAllMask();
    			$frm->add(new Hiddenfield("changeHeadInfo", $_REQUEST['changeHeadInfo']));
    			$frm->show();
    		} else {
    		  $sp = new Spacer(5);	
    		  $sp->show();
    		  
              $txKtb = new Text("Kopftext bearbeiten<br>");
              $txKtb->setFilter(false);
    	  	  $lnk = new Link("?changeHeadInfo=Cnge-".$_SESSION['runLink'], $txKtb);
    		  $lnk->show();
    		}
	   }
        
       $tx = new Text(getKopfText());
	$tx->setFilter(false);
       $tx->show();  
   	}
    
    if( $this->getObjectCount() > 0){
      foreach($this->OBJECTS as $obj)	{
	    $obj->show();
      }
    }
//--------------	
    include($this->FILE);	
//--------------    
    echo "</div>";

  }
  
  
  
  
  
  
  
  
  
  
  

}

?>