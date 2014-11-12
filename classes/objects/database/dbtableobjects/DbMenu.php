<?php
//FileNAME: DbMenu.php
 
class DbMenu  extends Object {
  var $CONFIG;
  var $MENUTABLE;
  var $MENUTYPE;  // normal, buttons, horizontal
  var $DBCONNECT;
  var $BORDER;
  var $COLOR;
  var $SPACER;

/**
 * der Konstruktor
 * 
 *  @param $name  der Parameter Name legt die ZugehÃÂÃÂÃÂÃÂ¶rigkeit des MenÃÂÃÂÃÂÃÂ¼objektes fest. Der Name muss dem Wert in der Spalte: Name  der Datenbanktabelle: menu   entsprechen.
 */
  function DbMenu($name){
  	  $this->setName($name);
      $this->CONFIG = $_SESSION['config'];

      $this->DBCONNECT = &$this->CONFIG->DBCONNECT;
      $this->MENUTABLE = new MenuTable($this->NAME);

      $this->setBorder(0);
      $this->COLOR = $this->CONFIG->COLORS['menu'];

      $this->setWidth(0);

      $this->FONTTYPE = new FontType();
      
      	    
      $spacer = new Text("&nbsp|&nbsp;");
	  $spacer->setFonttype($this->getFonttype());
	  $spacer->setFilter(false);
      $this->SPACER = $spacer;
	   
  }


  function setFontType($ft){
    $this->FONTTYPE = 	$ft;
    
	$this->SPACER->setFonttype($this->getFonttype());
  }


  function setSpacer($spc){
    $this->SPACER = $spc;	
  }

  function getRowCount(){
    return $this->MENUTABLE->getRowCountByStatus();	
  }

/**
 * die Methode ermittelt den MenÃÂÃÂÃÂÃÂ¼text zu einem Runlink (fÃÂÃÂÃÂÃÂ¼r Titelanzeige durch $_REQUEST['run']) 
 */
  function getLabelByRunlink($runLinkName){
	$x = $this->MENUTABLE->getTextByRunlink($runLinkName);
	return $x;
  }


/**
 * die Methode legt die Rahmenbreite fest 
 */
  function setBorder($b){
    $this->BORDER=$b;
  } 


/**
 * die Methode legt den aktuellen Wert der Einstellung fÃÂÃÂÃÂÃÂ¼r art der MenÃÂÃÂÃÂÃÂ¼anzeige fest
 * 
 * mÃÂÃÂÃÂÃÂ¶gliche Typen: 
 * - default
 * - buttons
 * - horizontal  
 * - horizontalbutton   
 */
  function setMenuType($t){
    if($t=="horizontal" || $t == "buttons" || $t == "horizontalbutton"){
	  $this->MENUTYPE=$t;
	} else {
	  $this->MENUTYPE="default";
	}
  } 



/**
 * die Methode liefert den aktuellen Wert der Einstellung fÃÂÃÂÃÂÃÂ¼r die Rahmen zurÃÂÃÂÃÂÃÂ¼ck 
 */
  function getBorder(){
    return $this->BORDER;
  } 



















/**
 * show-Methode (wertet automatisch den gesetzten Typ aus) -> siehe setMenuType($t)
 */ 
  function show(){  	
    if($this->MENUTYPE == "buttons"){
      $this->showWithButtons(getPageConfigParam($_SESSION['config']->DBCONNECT, "hauptmenu_button_image"), getPageConfigParam($_SESSION['config']->DBCONNECT, "hauptmenu_button_image_hover"));
    
    } else if($this->MENUTYPE == "horizontal"){
       $this->showHorizontalMenu();
    
	} else if ($this->MENUTYPE == "horizontalbutton"){
	   $this->showHorizontalButtonMenu();	
	
	} else { 
      $this->showNormalMenu();
      
    }
  }




/**
 * show-Methode fÃÂÃÂÃÂÃÂ¼r TYPE = default
 */ 
  function showNormalMenu(){
		$tbl = new Table( array(""));
		
		if(! (get_class($this->SPACER) == "Text" && $this->SPACER->getText() == "&nbsp|&nbsp;") ){
		    $tbl = new Table( array("", ""));
			$tbl->setColSizes(array("27"))	;
		}
		
	    $tbl->setHeadEnabled(false);
	    
		if(strlen($this->getAlign())>0){
			$tbl->setAlign($this->getAlign());
		}
					  
	    $tbl->setXPos($this->XPOS);
	    $tbl->setYPos($this->YPOS);
	    $tbl->setBorder($this->BORDER);
	    $userStatus = $this->CONFIG->CURRENTUSER->STATUS;
	    $menuArray = $this->MENUTABLE->getMenuByStatus($userStatus);
	    
	    
        $r = array();
        $txt = array();
        $lSpacer = "";
	    while($row = mysql_fetch_array($menuArray)){
	      if($row['id']>0){ 
            $rcnt = count($r);
    
			if(! (get_class($this->SPACER) == "Text" && $this->SPACER->getText() == "&nbsp|&nbsp;") ){
			  // Spacer ( | oder ÃÂÃÂÃÂÃÂ¼bergebener Spacer )
                $lnk = $row['link'];
                if(strlen($row['parent'])>0 && !strpos($lnk, "menuParent") ){
                    $lnk .= "&menuParent=" .$row['parent'];
                } 
			    $lSpacer = new Link($lnk ,$this->SPACER, false, $row['target'] );
				$lSpacer->setToolTip($row['tooltip']);
	        }  	
	      	
        
            $txt[$rcnt] = new Text($row['text']);
            $txt[$rcnt]->setFonttype($this->getFonttype());
    
            $lnk = $row['link'];
            if(strlen($row['parent'])>0 && !strpos($lnk, "menuParent") ){
                $lnk .= "&menuParent=" .$row['parent'];
            } 
	        $l = new Link($lnk, $txt[$rcnt], false, $row['target']);
	        $l->setStyleclass("menulink");
	        $l->setToolTip($row['tooltip']);


	        
            $r[$rcnt] = $tbl->createRow();
            $r[$rcnt]->setVAlign("middle");
            
            if(! (get_class($this->SPACER) == "Text" && $this->SPACER->getText() == "&nbsp|&nbsp;") ){
              $r[$rcnt]->setAttribute(0, $lSpacer);
              $r[$rcnt]->setAttribute(1, $l);
			} else {
              $r[$rcnt]->setAttribute(0, $l);
			}
            
	        $tbl->addRow($r[$rcnt]);
    	      
			$ftx = $txt[$rcnt]->getFonttype();
			      
            if($row['name'] == $_SESSION['MENU_PARENT'] || $row['name'] == $this->getLabelByRunlink($_SESSION['runLink'])){
              $ftx->setColor($_SESSION['config']->COLORS['hover']);                  #
			  $ftx->setBold(true);
            } else {
              $ftx->setBold(false);
            }
    		
			$txt[$rcnt]->setFonttype($ftx);
  	      }
  	      
  	      $tbl->addSpacer(0,2);	      
	    }
	
	    $tbl->show();
  }

    
/**
 * show-Methode fÃÂÃÂÃÂÃÂ¼r TYPE = button
 * 
 * @param $PIC  gibt die Hintergrundgrafik der Buttons an   
 */ 
  function showWithButtons($PIC, $HOVER=""){
    $tbl = new Table(array(""));
    $tbl->setWidth($this->getWidth()+5);
	$tbl->setXPos($this->getXPos());
	$tbl->setYPos($this->getYPos());
	$tbl->setBorder($this->getBorder());
	$tbl->setFonttypes(array($this->getFonttype()));

    $userStatus = $this->CONFIG->CURRENTUSER->STATUS;
	$menuresult = $this->MENUTABLE->getMenuByStatus($userStatus);
	
	
       $txt = array();	
       $r = array();
       while($row = mysql_fetch_array($menuresult)){
         if($row['id']>0){
         	$rcnt = count($txt); 
     	      	
            $txt[$rcnt] = new Text($row['text']);
            $txt[$rcnt]->setFonttype($this->getFonttype());

		    $ftx = $txt[$rcnt]->getFonttype();
		      
             if($row['text'] == $_SESSION['MENU_PARENT'] || $row['text'] == $this->getLabelByRunlink($_SESSION['runLink'])){
                $ftx->setColor($_SESSION['config']->COLORS['hover']);                  
			    $ftx->setBold(true);
             } else {
                $ftx->setBold(false);
             }
		    $txt[$rcnt]->setFonttype($ftx);

             $btn = new MenuButton($row['link'], $txt[$rcnt], $PIC, $row['target'],$this->COLOR);
             $btn->setHoverPic($HOVER);
		     $btn->setToolTip($row['tooltip']);
           
	         $r[$rcnt] = $tbl->createRow();
             $r[$rcnt]->setAttribute(0, $btn);
           
             $tbl->addRow($r[$rcnt]);
         }
       }

	$tbl->show();
  }





  
/**
 * show-Methode fÃÂÃÂÃÂÃÂ¼r TYPE = horizontal
 */ 
  function showHorizontalMenu(){
		$div = new Div();
	    $div->setHeight($this->getHeight());
        $div->setWidth($this->getWidth());
		if(strlen($this->getAlign())>0){
			$div->setAlign($this->getAlign());
		}
		
	    $div->setXPos($this->XPOS);
	    $div->setYPos($this->YPOS);
	    $div->setBorder($this->BORDER);
	    $userStatus = $this->CONFIG->CURRENTUSER->STATUS;
	    $menuArray = $this->MENUTABLE->getMenuByStatus($userStatus);
	    
		$spacer = $this->SPACER;
		$spacer->setFonttype($this->getFonttype());
        $rowCtr = mysql_num_rows($menuArray);      
        
		$ctr=0; //prÃÂÃÂÃÂÃÂ¼fvariable zum Rows mitzÃÂÃÂÃÂÃÂ¤hlen
		
		while($row = mysql_fetch_array($menuArray)){
	      if($row['id']>0){ 
	      // Spacer ( | oder ÃÂÃÂÃÂÃÂ¼bergebener Spacer )
            $lnk = $row['link'];
            if(strlen($row['parent'])>0 && !strpos($lnk, "menuParent") ){
                $lnk .= "&menuParent=" .$row['parent'];
            } 

		    $lSpacer = new Link($lnk ,$spacer, false, $row['target'] );
			$lSpacer->setToolTip($row['tooltip']);
			$div->add($lSpacer);
		  
		  // Link	
	      	$txt = new Text($row['text']);
	        $txt->setFonttype($this->getFonttype());
		  
		    if(ltrim($txt->getText()) == $_SESSION['MENU_PARENT'] || ltrim($txt->getText()) == $this->getLabelByRunlink($_SESSION['runLink'])){
               $txt->setText("<font color='" .$_SESSION['config']->COLORS['hover'] ."' >" .$txt->getText() ."</font>");
               $txt->setFilter(false);
            }
            
			$l = new Link($lnk ,$txt, false, $row['target'] );
			$l->setStyleclass("menulink");
	        $l->setToolTip($row['tooltip']);
	        
	        $div->add($l);
            
            //if(! (get_class($this->SPACER) == "Text" && $this->SPACER->getText() == "&nbsp|&nbsp;") ){
	          if($ctr==($rowCtr-1)){
     		    $lSpacer = new Link($lnk ,$spacer, false, $row['target'] );
			    $lSpacer->setToolTip($row['tooltip']);
			    $div->add($lSpacer);	
			  }
			//}
	      }
	      $ctr++; //prÃÂÃÂÃÂÃÂ¼fvariable zum Rows mitzÃÂÃÂÃÂÃÂ¤hlen
	    }
	
		$div->show();
  }


  
/**
 * show-Methode fÃÂÃÂÃÂÃÂ¼r TYPE = horizontalbutton
 */ 
  function showHorizontalButtonMenu(){
	    $userStatus = $this->CONFIG->CURRENTUSER->STATUS;
	    $menuArray = $this->MENUTABLE->getMenuByStatus($userStatus);
        $count=0;
        $c = $this->MENUTABLE->getRowCountByStatus();
        
	    $colArr = array("");//[0] wird hier schon initialisiert!
	    for($i=1;$i<$c;$i++){
		  $colArr[$i] = "";	
		}
		
	    $table = new Table($colArr);
  
	    $table->setStyle("text-align", "center");
	    $table->setStyle("vertical-align", "middle");
		$tblrow = $table->createRow();
	    $tblrow->setStyle("vertical-align", "middle");
	    $tblrow->setHeight($this->getHeight());
        
		while($row = mysql_fetch_array($menuArray)){
	      if($row['id']>0){
		  	$btn = new HorizontalMenuButton($row['link'] ,$row['text'] ); 
	        
			$tblrow->setAttribute($count, $btn);
	        $count++;
		  }
	    }
	    
	    $table->addRow($tblrow);
	    
	    $table->setHeight($this->getHeight());
		$table->show();
  }

}























/**
 *  Die Klasse stellt die Buttons der Horizontalen MenÃÂÃÂÃÂÃÂ¼s zur VerfÃÂÃÂÃÂÃÂ¼gung
 */ 
class HorizontalMenuButton extends Object{
	
   /**
    *  Konstruktor
    * 
    *  @param $link     entspricht der Zielangabe des Links
    *  @param $text     entspricht dem angezeigten Text
    *  @param $tooltip  (OPTIONAL) entspricht dem angezeigten Tooltip-Text (bei Mouseover)		 	 
    */ 
    private $TEXT;
    private $LINK;
    private $MENUBUTTONHEIGHT;
    private $MENUBUTTONRIGHTWIDTH;
    private $MENUBUTTONLEFTWIDTH;
    private $MENUBUTTONSTYLE;
    private $MENUTABLE;
    
	function HorizontalMenuButton($link, $text, $tooltip=""){
      $this->setWidth(150);
      $this->MENUTABLE = new MenuTable($text);
      
      if(strlen($tooltip)>0){
	    $this->setToolTip($tooltip);
	  }
	  
      $this->TEXT = $text;
      $this->LINK = $link;
      $this->setToolTip($tooltip);
      
      
      

      $this->MENUBUTTONHEIGHT = 25;
      $this->MENUBUTTONLEFTWIDTH = 5;
      $this->MENUBUTTONRIGHTWIDTH = 5;
      $this->MENUBUTTONSTYLE = "green1";
	}
	

    function getLabelByRunlink($runLinkName){
  	  $x = $this->MENUTABLE->getTextByRunlink($runLinkName);
	  return $x;
    }
		
	function setBtn($b){
		$this->BTN = $b;		
	}

	function getBtn(){
		return $this->BTN;		
	}
	
	function setText($b){
		$this->TEXT = $b;		
	}

	function getText(){
		return $this->TEXT;		
	}
	
	function setLink($b){
		$this->LINK = $b;		
	}

	function getLink(){
		return $this->LINK;		
	}
	
	

	
	
	

	
	
	/**
    *  Zeigt den Button an.
    */ 	  	 	
	function show(){		
	  echo "<table  ";
      
	  $w = $this->WIDTH;
	  if($this->WIDTH < (2*$this->MENUBUTTONLEFTWIDTH)){
	    $w = 80 + (2*$this->MENUBUTTONLEFTWIDTH);
	  }
	  
	  echo " WIDTH='" .$w ."' ";
  
      if($this->HEIGHT > 0){
	      echo " HEIGHT='" .($this->HEIGHT) ."' ";
      }
      
	  //Border, Padding und Spacing muss wegen bg hier fix auf null stehen
      echo "  cellPADDING='0'  cellSPACING='0' border='0'>";
      
	  if($this->TEXT == $_SESSION['MENU_PARENT'] || $this->TEXT == $this->getLabelByRunlink($_SESSION['runLink'])){
        $this->TEXT = "<font color='" .$_SESSION['config']->COLORS['hover'] ."' >" .$this->TEXT ."</font>";
      } 
      $lnk = $this->LINK;
      if(strlen($row['parent'])>0 && !strpos($lnk, "menuParent") ){
        $lnk .= "&menuParent=" .$row['parent'];
      } 

      $l = new Link($lnk , $this->TEXT);
      
      
      echo "<tr height='" .$this->MENUBUTTONHEIGHT ."' >
		        <th background='pics/menubutton/".$this->MENUBUTTONSTYLE."_title_01.png' width='" .$this->MENUBUTTONLEFTWIDTH ."'>
	            </th>
	            <th background='pics/menubutton/".$this->MENUBUTTONSTYLE."_title_02.png' WIDTH='" .($this->WIDTH - (2*$this->MENUBUTTONRIGHTWIDTH)) ."' >
	  ";
	   
      $l->show();
	   
	  echo 		  "</th>
		        </th>
	            <th background='pics/menubutton/".$this->MENUBUTTONSTYLE."_title_03.png' width='" .$this->MENUBUTTONRIGHTWIDTH ."'>
	            </th>
            </tr>
          </table>
      ";
	}
}