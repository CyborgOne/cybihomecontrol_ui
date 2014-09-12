<?php
/**
 * @filename layout_mobile.php
 * @author  Daniel Scheidler
 * @copyright November 2012
 */

       echo "Mobile Version";
	
	   $layoutTable = new Table(array(""));
	   $layoutTable->setWidth("100%");
       $layoutTable->setAlign("center");
       $layoutTable->setBORDER(0);
       $layoutTable->setBackgroundColor( $_SESSION['config']->COLORS['panel_background']);

	   $layoutTable->setSpacing(0);
	   $layoutTable->setPadding(0);	
	
	
    /* ------------------------------------
      HAUPT-MENU
    ------------------------------------ */    
 
       $menuDiv = new Div();
	   $menuDiv->setWidth("99%");
       $menuDiv->setBorder(0);    
       $menuDiv->setOverflow("hidden");
       $menuDiv->setAlign("center");
       $menuDiv->setStyle("padding-left","2px");
       $menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

       $spc = new Text(" | ");
       $menu = new DbMenu("Hauptmenue");
       $menu->setAlign("center");
       $menu->setFontsize(6);
       $menu->setMenuType("horizontal");
      
       $menuDiv->add($menu);
    
       $contentLayoutRow1 = $layoutTable->createRow();
       $contentLayoutRow1->setAttribute(0, $menuDiv);
       $layoutTable->addRow($contentLayoutRow1);

    /* --------------------------------- */
    
       $layoutTable->addSpacer(0,40);

    /* ------------------------------------
      HAUPTPANEL
    ------------------------------------ */ 

       $MainPanel = new Div();
       $MainPanel->setBackgroundColor( $_SESSION['config']->COLORS['panel_background']);
       $MainPanel->setBorder(0);
       $MainPanel->setWidth("80%");

       $cont = null;
       
      // echo " - check: " ."mobile_".$_SESSION['runLink'];
       
       if(existRunlink($_SESSION['config']->DBCONNECT, "mobile_".$_SESSION['runLink']) ){
       // echo " - load: " ."mobile_".$_SESSION['mainpage'];
        
         $cont = new DivByInclude("mobile_".$_SESSION['mainpage'], false);
         $cont->setWidth("99%");
       } else {
         $cont = new DivByInclude($_SESSION['mainpage'], false);
       }

       $MainPanel->add($cont);
 
       $contentLayoutRow = $layoutTable->createRow();
       $contentLayoutRow->setAttribute(0, $MainPanel);
       $layoutTable->addRow($contentLayoutRow);
        
   /* --------------------------------- */
    

    /* ------------------------------------
      FUSS-MENU 
    ------------------------------------ */
 
       $footMenuDiv = new Div();
       $footMenuDiv->setWidth("99%");
       $footMenuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);
       $footMenuDiv->setBorder(0);
       $footMenuDiv->setAlign("center");

       $footMenu = new DbMenu("Fussmenue");
       $footMenu->setHeight(14);
       $footMenu->setMenuType("horizontal");
       $footMenu->setAlign("center");
       $footMenu->setFontsize(1);

       $footMenuDiv->add($footMenu);
 
       $fussLayoutRow = $layoutTable->createRow();
       $fussLayoutRow->setAttribute(0,$footMenuDiv);
       $layoutTable->addRow($fussLayoutRow);

    /* --------------------------------- */ 
    

       $layoutTable->show();

       $arduinoFrame = new IFrame($_SESSION['config'], "arduinoSwitch", -1, -1, 1, 1, 0);
       $arduinoFrame->show();
       

    
	
	
?>
