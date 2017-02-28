<?php

/**
 * @filename layout_mobile.php
 * @author  Daniel Scheidler
 * @copyright November 2012
 */
$_SESSION['additionalLayoutHeight'] = 215;
$_SESSION['currentView'] = 'mobile';

include("header_index.php");



$layoutTable = new Table(array(""));
$layoutTable->setWidth("100%");
$layoutTable->setBORDER(0);
$layoutTable->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$layoutTable->setSpacing(0);
$layoutTable->setPadding(0);
$layoutTable->setStyle("padding","0px 12px");




/* ------------------------------------
BANNER
------------------------------------ */
$banner = new Image("pics/Banner.png",-1,-1,800);
$banner->setGenerated(false);
$contentLayoutRow1 = $layoutTable->createRow();
$contentLayoutRow1->setAlign("left");
$contentLayoutRow1->setAttribute(0, $banner);
$layoutTable->addRow($contentLayoutRow1);


/* ------------------------------------
HAUPT-MENU
------------------------------------ */

$menuDiv = new Div();
$menuDiv->setWidth("99%");
$menuDiv->setBorder(0);
$menuDiv->setOverflow("hidden");
$menuDiv->setAlign("center");
$menuDiv->setStyle("padding-left", "2px");
$menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$spc = new Text(" | ");
$menu = new DbMenu("Mobilmenue");
$menu->setSpacer($spc);
$menu->setAlign("center");
$menu->setFontsize("7em");
$menu->setMenuType("horizontal");

$menuDiv->add(new Line(1, 6));
$menuDiv->add($menu);
$menuDiv->add(new Line(1, 6));

$contentLayoutRow1 = $layoutTable->createRow();
$contentLayoutRow1->setAttribute(0, $menuDiv);
$layoutTable->addRow($contentLayoutRow1);


/* --------------------------------- */
/*            Untermenü              */
/* --------------------------------- */

if (isset($_SESSION['MENU_PARENT']) && strlen($_SESSION['MENU_PARENT']) > 0) {
    $sql = "SELECT * FROM menu WHERE parent='" . $_SESSION['MENU_PARENT'] . "'";
    $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
    $menuHeight = 0;

    if (mysql_numrows($rslt) > 0) {
        $menuHeight = 50;
        $_SESSION['additionalLayoutHeight'] = $_SESSION['additionalLayoutHeight'] + $menuHeight;

        $menuDiv = new Div();
        $menuDiv->setWidth("99%");
        $menuDiv->setHeight($menuHeight);
        $menuDiv->setBorder(0);
        $menuDiv->setAlign("left");
        $menuDiv->setStyle("padding", "0px 0px");
        
        $menu = new DbMenu("Hauptmenue");
        $menu->setAlign("center");
        $menu->setFontsize("5em");
        $menu->setMenuType("horizontal");
        $menuDiv->add($menu);
        $menuDiv->add(new Line(1, 6));

        $contentLayoutRow2 = $layoutTable->createRow();
        $contentLayoutRow2->setAttribute(0, $menuDiv);
        $layoutTable->addRow($contentLayoutRow2);
    }
}

/* --------------------------------- */


/* ------------------------------------
HAUPTPANEL
------------------------------------ */

$MainPanel = new Div();
$MainPanel->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);
$MainPanel->setBorder(0);
$MainPanel->setWidth("85%");

$cont = null;

if (existRunlink($_SESSION['config']->DBCONNECT, "mobile_" . $_SESSION['runLink'])) {
    $cont = new DivByInclude("mobile_" . $_SESSION['mainpage'], false);
    $cont->setWidth("99%");
} else {
    $cont = new DivByInclude($_SESSION['mainpage'], false);
}

$MainPanel->add($cont);

$contentLayoutRow = $layoutTable->createRow();
if (isset($_SESSION['MENU_PARENT']) && $_SESSION['MENU_PARENT'] ==
    "Einstellungen" && isset($_SESSION['runLink']) && $_SESSION['runLink'] == "homeconfig") {
    $contentLayoutRow->setAlign("left");
} else {
    $contentLayoutRow->setAlign("center");
}
$contentLayoutRow->setAttribute(0, $MainPanel);
$layoutTable->addRow($contentLayoutRow);

/* --------------------------------- */

$layoutTable->addSpacer(0, 40);

/* ------------------------------------
FUSS-MENU 
------------------------------------ */

$footMenuDiv = new Div();
$footMenuDiv->setWidth("99%");
$footMenuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);
$footMenuDiv->setBorder(0);
$footMenuDiv->setAlign("center");

$footMenu = new DbMenu("Fussmenue");
//       $footMenu->setHeight(60);
$footMenu->setMenuType("horizontal");
$footMenu->setAlign("center");
$footMenu->setFontsize(6);

$footMenuDiv->add(new Line(1, 6));
$footMenuDiv->add($footMenu);
$footMenuDiv->add(new Line(1, 6));

$fussLayoutRow = $layoutTable->createRow();
$fussLayoutRow->setAttribute(0, $footMenuDiv);
$layoutTable->addRow($fussLayoutRow);

/* --------------------------------- */


$layoutTable->show();

$arduinoFrame = new IFrame($_SESSION['config'], "arduinoSwitch", -1, -1, 1, 1, 0);
$arduinoFrame->show();

?>
