<?php
/**
 * @filename layout_apple.php
 * @author  Daniel Scheidler
 * @copyright November 2012
 */

echo "NO APPLE!!!";
exit();


	$layoutTable = new Table(array(""));
	$layoutTable->setWidth(600);
	$layoutTable->setAlign("left");
	$layoutTable->setBORDER(0);
       $layoutTable->setBackgroundColor( "#ffdddd" );  //  $_SESSION['config']->COLORS['panel_background']);

	$layoutTable->setSpacing(0);
	$layoutTable->setPadding(0);	
	
	
    /* ------------------------------------
      HAUPT-MENU
    ------------------------------------ */    
 
       $menuDiv = new Div();
       $menuDiv->setWidth(600);
       $menuDiv->setBorder(0);    
       $menuDiv->setOverflow("hidden");
       $menuDiv->setAlign("center");
	$menuDiv->setStyle("padding-left","2px");
       $menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

       $spc = new Text(" | ");
       $menu = new DbMenu("Hauptmenue");
       $menu->setAlign("center");
       $menu->setFontsize(2);
       $menu->setMenuType("horizontal");
      
       $menuDiv->add($menu);
    
	$contentLayoutRow1 = $layoutTable->createRow();
       $contentLayoutRow1->setAttribute(0, $menuDiv);
       $layoutTable->addRow($contentLayoutRow1);

    /* --------------------------------- */


    /* ------------------------------------
      HAUPTPANEL
    ------------------------------------ */ 

       $MainPanel = new Div();
       $MainPanel->setBackgroundColor( $_SESSION['config']->COLORS['panel_background']);
       $MainPanel->setBorder(0);
	
       $cont = new DivByInclude($_SESSION['mainpage'], false);
       $cont->setWidth("600");
       $cont->setStyle("padding-left","4px");
       $cont->setStyle("padding-right","4px");

       $MainPanel->add($cont);
 
	$contentLayoutRow = $layoutTable->createRow();
	$contentLayoutRow->setAttribute(0, $MainPanel);
       $layoutTable->addRow($contentLayoutRow);
        
   /* --------------------------------- */
    

    /* ------------------------------------
      FUSS-MENU 
    ------------------------------------ */
        $footMenuDiv = new Div();
       $footMenuDiv->setWidth(600);
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
