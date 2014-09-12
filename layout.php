<?php
/**
 * @filename layout.php
 * @author  Daniel Scheidler
 * @copyright Oktober 2012
 */

  $detect = new Mobile_Detect();

  if ($detect->isMobile()) {
    if ($detect->is('iOS')) {
      // iOS
      include("layout_apple.php");
      exit();
    }
    
    if ($detect->isTablet()) {
      // Tablet
      include("layout_tablet.php");
      exit();
    }
	
    // Any other mobile device.
    include("layout_mobile.php");
    exit();
  }

	$topSpaceTable = new Table(array(""));
	$topSpaceTable->show();	
	
	$layoutTable = new Table(array(""));
	$layoutTable->setWidth(800);
	$layoutTable->setHeight(450);
	$layoutTable->setAlign("left");
	$layoutTable->setBORDER(0);
       $layoutTable->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

	$layoutTable->setSpacing(0);
	$layoutTable->setPadding(0);	
	
	
    /* ------------------------------------
      HAUPT-MENU
    ------------------------------------ */    
 
       $menuDiv = new Div();
       $menuDiv->setWidth(790);
       $menuDiv->setBorder(0);    
       $menuDiv->setOverflow("visible");
       $menuDiv->setAlign("center");
	   $menuDiv->setStyle("padding-left","2px");
       $menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

       $spc = new Text(" | ");
       $menu = new DbMenu("Hauptmenue");
       $menu->setAlign("center");
       $menu->setFontsize(3);
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
       $MainPanel->setStyle("padding-left","4px");
       $MainPanel->setStyle("padding-right","4px");

       $cont = new DivByInclude($_SESSION['mainpage'], true);
       $cont->setWidth("630");
       $cont->setStyle("overflow-x","hidden");

       if($_SESSION['runLink']=="homeconfig"){
         $cont->setStyle("overflow-y", "visible");
       } else {
         $cont->setHeight("420");
         $cont->setStyle("overflow-y", "auto");
       }
       $cont->setStyle("padding-left","4px");
       $cont->setStyle("padding-right","4px");
       $cont->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_1']);


       $cont2 = new DivByInclude("includes/ShortcutSidebar.php", false);
       $cont2->setWidth("140");
       $cont2->setHeight("100%");
       $cont2->setStyle("padding-left","8px");
       $cont2->setStyle("padding-right","2px");
       $cont2->setStyle("overflow-x","hidden");
       $cont2->setStyle("overflow-y","auto");
       $cont2->setBorder(0);  
       $cont2->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_2']);

       $spcr = new Div();
       $spcr->setWidth(0);
       $spcr->setHeight(400);

       $tbl = new Table(array("","",""));
       $tbl->setBorder(2);
       $tbl->setColSizes(array("650", "1", "150"));
       $tbl->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_1']);

       $rMain = $tbl->createRow();
       $rMain->setAttribute(0,$cont);     
       $rMain->setAttribute(1,$spcr);     
       $rMain->setAttribute(2,$cont2);     
       $tbl->addRow($rMain);

       $MainPanel->add($tbl);
 
	$contentLayoutRow = $layoutTable->createRow();
	$contentLayoutRow->setAttribute(0, $MainPanel);
       $layoutTable->addRow($contentLayoutRow);
        
   /* --------------------------------- */
    

    /* ------------------------------------
      FUSS-MENU 
    ------------------------------------ */
 
       $footMenuDiv = new Div();
       $footMenuDiv->setWidth(790);
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
