<?php
//FileNAME: MenuButton.php

class MenuButton extends Object{
  var $LINK;           //Href
  var $LABEL;          //Angezeigter Text
  var $BGPIC;          //Farb-Bild
  var $BGPICSW;  	  //Schwarz-WeiÃÂÃÂÃÂÃÂBild
  var $BGPICSMALLER;   //kleineres Farbbild
  var $COLOR;          //SchriftCOLOR
  var $HOVERPIC;

  var $TARGET;


  function MenuButton($link, $label, $bgPic, $target, $color='#222222', $w=0, $h=0, $hoverPic=""){
    $this->LINK   = $link;
    $this->LABEL  = $label;
    $this->BGPIC  = $bgPic;
    $this->COLOR  = $color;
    $this->WIDTH  = $w;
    $this->HEIGHT = $h;
    $this->HOVERPIC = $hoverPic;
    
    if($this->WIDTH==0 || $this->HEIGHT==0){
	  $info = getimagesize($_SERVER['DOCUMENT_ROOT'].substr(dirname($_SERVER['SCRIPT_NAME']),1)."/".$this->BGPIC);
	  
      $Breite_Quelle = $info[0];
      $Hoehe_Quelle  = $info[1];

	  if($this->WIDTH==0){
	    $this->WIDTH = $Breite_Quelle+5;
	  }
	  
	  if($this->HEIGHT==0) {
	    $this->HEIGHT = $Hoehe_Quelle+5;
	  }
	}
    
    $this->setFonttype(new FontType());
    $this->TARGET = $target;

  }


  function setHoverPic($hoverPic){
   $this->HOVERPIC = $hoverPic;	
  }



  function getHoverPic(){
   return $this->HOVERPIC;	
  }


  function show(){
  	echo "<table cellpadding='0'  cellspacing='0'  HEIGHT='".$this->HEIGHT."' WIDTH='".$this->WIDTH."' ";
	
	if(strlen($this->HOVERPIC)>3){	
	   echo "onmouseover=\"this.style.backgroundImage='url(" .$this->HOVERPIC  .")';this.style.backgroundRepeat='no-repeat'\" onmouseout=\"this.style.backgroundImage='url(" .$this->BGPIC  .")';this.style.backgroundRepeat='no-repeat'\"";
	}
	
	echo " style=\"background-repeat:no-repeat;background-image:url(" .$this->BGPIC  .");background-position: 1px 1px; \">
               <tr class='menubutton'  WIDTH='".$this->WIDTH."' >
	          <td HEIGHT='".$this->HEIGHT."' WIDTH='".$this->WIDTH."'>
		     <center>
     ";
     
     
     $txt = "";
     
     if(method_exists($this->LABEL, "show")){
       $txt = $this->LABEL;
	 } else {
	   $txt = new Text($this->LABEL);
	   $txt->setFilter(false);
	   $txt->setFonttype($this->getFonttype());
	 	
	 }
	
	 $l = new Link($this->LINK, $txt);
	 $l->setStyleclass("menulink");
	 $l->setToolTip($this->getTooltip());
	 $l->show(); 
     
     echo "
			</center>
	    	  </td>
	      	</tr>
            </table>
    ";
  }
}

?>