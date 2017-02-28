<?php
/**
 * @filename layout_tablet.php
 * @author  Daniel Scheidler
 * @copyright November 2012
 */
$_SESSION['currentView'] = 'tablet';
include("header_index.php");


echo "Tablet-Version:<br>";

$topSpaceTable = new Table(array(""));
$topSpaceTable->show();	

$layoutTable = new Table(array(""));
$layoutTable->setWidth(820);
$layoutTable->setHeight(400);
$layoutTable->setAlign("left");
$layoutTable->setBORDER(0);
$layoutTable->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$layoutTable->setSpacing(0);
$layoutTable->setPadding(0);	


/* ------------------------------------
  HAUPT-MENU
------------------------------------ */    

   $menuDiv = new Div();
   $menuDiv->setWidth(810);
   $menuDiv->setBorder(0);    
   $menuDiv->setOverflow("hidden");
   $menuDiv->setAlign("center");
   $menuDiv->setStyle("padding-left","2px");

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
   $MainPanel->setBorder(0);

   $cont = null;
   if( file_exists( "mobile_".$_SESSION['mainpage']) ){	
     $cont = new DivByInclude("mobile_".$_SESSION['mainpage'], false);
   } else {
     $cont = new DivByInclude($_SESSION['mainpage'], false);
   }

   $cont->setWidth("800");
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
$footMenuDiv->setWidth(810);
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

echo "Diese Tablet-Version befindet sich im Aufbau;)"


?>
