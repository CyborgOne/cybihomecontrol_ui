<?php
//FileNAME: Object.php



class Object extends Style {
  public $NAME;
  public $XPOS;
  public $YPOS;
  public $WIDTH;
  public $HEIGHT;
  public $ALIGN;
  public $VALIGN;

  public $FONTTYPE;   // Array welches die Schriftformatierung enthÃÂÃÂÃÂÃÂ¤lt
  private $TOOLTIP;
  private $TOOLTIPTYPE;
  private $PADDING;
  private $SPACING;
  

  var $STYLECLASS;
  var $conf;

  function Object(){
    $this->XPOS = -1;
    $this->YPOS = -1;
    $this->setToolTip("");
    $this->setToolTipTyp("standard");
  
    $this->setFonttype(new FontType());
  }
 
   function setFontsize($s){
    $this->FONTTYPE->FONTSIZE = $s;
  }

  function getFontsize(){
    return  $this->getFontType()->FONTSIZE;
  }

  function getSlowerFontsize(){
	$fs = $this->getFontsize();
	
	$fs = $fs-1;
	if ($fs>0){
	  return $fs;
	}
	
	return 1;
  }
  
 /**
 * Legt die Tooltip-Art fest
 *   
 * @param $t  mÃÂÃÂÃÂÃÂ¶gliche Werte: standard, menÃÂÃÂÃÂÃÂ¼, smallmenÃÂÃÂÃÂÃÂ¼
 */  
  function setToolTipTyp($t){
  	 if ($t == "standard" || $t == "menÃÂÃÂÃÂÃÂ¼"){	
       $this->TOOLTIPTYPE = $t;
     }else {
	   $this->TOOLTIPTYPE = "standard";
	 }
  }
  
  function getTooltipType(){
  	if(strlen($this->TOOLTIPTYPE)>0){
      return $this->TOOLTIPTYPE;
    } else {
      return "standard";
    }
  }
 
  
  function setToolTip($t){
     $this->TOOLTIP = str_replace("'","\'", $t);
     $this->TOOLTIP = htmlspecialchars( $this->TOOLTIP);
  }
  
  function getTooltip(){
    return $this->TOOLTIP;
  }

  function setWidth($w){
    $this->WIDTH = $w;
  }

  function getWidth(){
    return $this->WIDTH;
  }

  function setFonttype($f){
    $this->FONTTYPE = $f;
  } 

  function getFonttype(){
    if ($this->FONTTYPE == null){
      $this->FONTTYPE = new FontType();
    }
   
    $type   = $this->FONTTYPE->FONT;
    $size   = $this->FONTTYPE->FONTSIZE;
    $bold   = $this->FONTTYPE->BOLD;
    $italic = $this->FONTTYPE->ITALIC;
    $under  = $this->FONTTYPE->getUnderline();
    $color  = $this->FONTTYPE->getColor();

    $ft = new FontType($type,$size,$bold, $italic, $under);
    $ft->setColor($color);
    return $ft;
  } 

  function setHeight($h){
    $this->HEIGHT = $h;
  }

  function getHeight(){
    return $this->HEIGHT;
  }

  function getAlign(){
    return $this->ALIGN;
  }

  function getVAlign(){
    return $this->VALIGN;
  }


  function setName($n){
    $this->NAME = $n;
  }

  function getName(){
    return $this->NAME;
  }


  function setXPos($x){
	$this->XPOS = $x;
	$this->checkPos();
  }


  function setYPos($y){
	$this->YPOS = $y;
	$this->checkPos();
  }

  function setAlign($a){
	$this->ALIGN = $a;
    $this->setStyle("horizontal-align", $this->ALIGN);
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
  
  function getXpos(){
    return $this->XPOS;
  }


  function getYpos(){
    return $this->YPOS;
  }
  function setPadding($y){
    $this->PADDING  = $y;
  }

  function getPadding(){
    return strlen($this->PADDING)>0?$this->PADDING:0;
  }
  function setSpacing($y){
    $this->SPACING  = $y;
  }
  function setStyleclass($y){
    $this->STYLECLASS = $y;
  }
 
  function getSpacing(){
    return strlen($this->SPACING)>0?$this->SPACING:0;
  }
  
  function checkPos(){
    if(strlen($this->XPOS)>0 && strlen($this->YPOS)>0){
         $this->setStyle("position", "absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; ");
    }
  }

  function hasFunction($functName){
    if(method_exists($this, $functName)){
	return true;
    }
    return false;
  }
  
  
  /**
  * Liefert je nach TOOLTIPTYPE entsprechend den Tooltip-onmouseover-string
  */   
  function getToolTipTag(){
  	
  	if(strlen($this->TOOLTIP)>0){
  	  if($this->getTooltipType()=="standard"){	
  	    $tt = str_replace("\r\n", "", nl2br($this->TOOLTIP));
  	    return" onmouseover=\"Tip('" .$tt ."')\" onmouseout=\"UnTip()\" ";
  	  
	  } else {
  	  	return $this->getToolTipFixedTag();
  	  
	  }
	}
	
	return "";
  }
  
  function getToolTipFixedTag(){
  	
  	if(strlen($this->TOOLTIP)>0){
  	  $tt = str_replace("\r\n", "", nl2br($this->TOOLTIP));
  	  return" onmouseover=\"TipMenu('" .$tt ."')\" onmouseout=\"UnTipMenu()\" ";
	}
	
	return "";
  }
  
  
  
  
  
  
  
  function setBackgroundColor($color){
	$this->setStyle("background-color", $color);
  }
    
  function getBackgroundColor($color){
	$this->getStyle("background-color");
  }
  
  
  
  
  
  
    
  function getToolTipSmallMenuTag(){
  	
  	if(strlen($this->TOOLTIP)>0){
  	  $tt = str_replace("\r\n", "", nl2br($this->TOOLTIP));
  	  return" onmouseover=\"TipSmallMenu('" .$tt ."')\" onmouseout=\"UnTipSmallMenu()\" ";
	}
	
	return "";
  }
  
  
  function getClassTag(){
  	
  	if(strlen($this->STYLECLASS)>0){
  	  return" Class='" .$this->STYLECLASS ."' ";
	}
	
	return "";
  }

}




class Style {
  var      $STYLEATTRS;
  private  $BORDERCOLOR;
  public   $BORDER;
  
  
  function Style(){
	 $STYLEATTRS = array();
  }
  
  
  
    
  function setBorder($w){
    $this->BORDER = $w;
    $this->setStyle("border", $w ."px solid " .$this->getBorderColor() ); 
  }
  
  function getBorder(){
    return $this->BORDER; 
  }
  
  function getBorderColor(){
     return $this->BORDERCOLOR;    
  }
  
  function setBorderColor($c){
    $this->BORDERCOLOR = $c;
    $this->setStyle("border", $this->getBorder() ."px solid " .$c);
  }
  
  function getStyle($styleName){
	return $this->STYLEATTRS[$styleName];
  }

  function setStyle($name, $value){
	$this->STYLEATTRS[$name] = $value;
  }
  
  
  function getStyleString(){
  	$ret = "";
    
    if (count($this->STYLEATTRS)>0) {
      $ret = $ret . " style=\"";          
	
	  foreach($this->STYLEATTRS as $name=>$style){
	  	if(strlen($name)>0 &&  strlen($style)>0){
		  $ret = $ret . $name.": " .$style ."; ";
		}		  		
	  }	
	
	  $ret = $ret . " \" ";
	}
    
    return $ret;
  }
  
  
  
  function showStyles(){
  	echo $this->getStyleString();
  }  
  
  
  
  
  
}




?>