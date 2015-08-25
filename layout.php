<?php

/**
 * @filename layout.php
 * @author  Daniel Scheidler
 * @copyright Oktober 2012
 */

$detect = new Mobile_Detect();
$_SESSION['additionalLayoutHeight'] = 195;

if ($detect->isMobile()) {
    if ($detect->is('iOS')) {
        // iOS
        include ("layout_apple.php");
        exit();
    }

    if ($detect->isTablet()) {
        // Tablet
        include ("layout_tablet.php");
        exit();
    }

    // Any other mobile device.
    include ("layout_mobile.php");
    exit();
}

if(isset($_REQUEST['noFrame']) && ($_REQUEST['noFrame']=="on" || $_REQUEST['noFrame']=="off")){
  $_SESSION['noFrame'] = $_REQUEST['noFrame'];
}
$noFrameLayout = isset($_SESSION['noFrame']) && $_SESSION['noFrame']=="on";


$topSpaceTable = new Table(array(""));
$topSpaceTable->show();

$layoutTable = new Table(array(""));
$layoutTable->setWidth(800);
$layoutTable->setAlign("left");
$layoutTable->setBORDER(0);
$layoutTable->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$layoutTable->setSpacing(0);
$layoutTable->setPadding(0);



/* ------------------------------------
BANNER
------------------------------------ */
$bannerWidth = 800;

$banner = new Image("pics/Banner.png");
$banner->setWidth($bannerWidth);


if(!$noFrameLayout){
    $banner->setGenerated(false);
    $contentLayoutRow1 = $layoutTable->createRow();
    $contentLayoutRow1->setAlign("left");
    $contentLayoutRow1->setAttribute(0, $banner);
    $contentLayoutRow1->setStyle("padding", "10px");
    $layoutTable->addRow($contentLayoutRow1);
} else {
    $_SESSION['additionalLayoutHeight'] = 15;
}

/* ------------------------------------
HAUPT-MENU
------------------------------------ */

$menuDiv = new Div();
$menuDiv->setWidth(790);
$menuDiv->setBorder(0);
$menuDiv->setOverflow("visible");
$menuDiv->setAlign("center");
$menuDiv->setStyle("padding-left", "2px");
$menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$spc = new Text(" | ");
$menu = new DbMenu("Kopfmenue");
$menu->setAlign("center");
$menu->setFontsize("4em");
$menu->setMenuType("horizontal");

$menuDiv->add($menu);

$layoutTable->addSpacer(0, 15);
$contentLayoutRow1 = $layoutTable->createRow();
$contentLayoutRow1->setAttribute(0, $menuDiv);
$layoutTable->addRow($contentLayoutRow1);

/* --------------------------------- */


/* ------------------------------------
HAUPTPANEL
------------------------------------ */
$mainWidth = 610;

if (isset($_SESSION['MENU_PARENT']) && $_SESSION['MENU_PARENT'] !=
    "Steuerung" && isset($_SESSION['runLink']) && $_SESSION['runLink'] !=
    "start") {
    $mainWidth = 804;
}

$MainPanel = new Div();
$MainPanel->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);
$MainPanel->setBorder(0);
$MainPanel->setStyle("padding", "5px 5px");


$cont = new DivByInclude($_SESSION['mainpage'], false);
$cont->setWidth($mainWidth);
$cont->setStyle("overflow-x", "hidden");

if ($_SESSION['runLink'] == "homeconfig") {
    $cont->setStyle("overflow-y", "visible");
} else {
    $cont->setStyle("overflow-y", "auto");
}
$cont->setStyle("padding", "5px 5px");

$cont->setBackgroundColor($_SESSION['config']->COLORS['main_background']);

// Kopftexte und Nachrichten-Prüfung werden in DivByInclude  verwaltet
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
        //       $menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

        $menu = new DbMenu("Hauptmenue");
        $menu->setAlign("center");
        $menu->setFontsize(3);
        $menu->setMenuType("horizontal");

        $menuDiv->add(new Line(1, 6));
        $menuDiv->add($menu);
        $menuDiv->add(new Line(1, 6));

        $cont->add($menuDiv);
    }
}

if (isset($_SESSION['MENU_PARENT']) && $_SESSION['MENU_PARENT'] == "Steuerung") {
    $cont2x = new DivByInclude("includes/ShortcutSidebar.php", false);
    $cont2x->setWidth("170");
    $cont2x->setHeight("100%");
    $cont2x->setStyle("padding-left", "8px");
    $cont2x->setStyle("padding-right", "2px");
    $cont2x->setStyle("overflow-x", "hidden");
    $cont2x->setStyle("overflow-y", "auto");
    $cont2x->setBorder(0);
    $cont2x->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_2']);
    

    $cont2 = new Div();
    $cont2->setWidth("180");
    $cont2->setStyle("overflow-x", "visible");
    $cont2->setStyle("overflow-y", "visible");

    $cont2->add($cont2x);        


    $spcr = new Div();
    $spcr->setWidth(0);
    $spcr->setHeight(400);

    $tbl = new Table(array("", "", ""));
    $tbl->setBorder(2);
    $tbl->setColSizes(array("650", "1", "150"));
    $tbl->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_1']);

    $rMain = $tbl->createRow();
    $rMain->setAttribute(0, $cont);
    $rMain->setAttribute(1, $spcr);
    $rMain->setAttribute(2, $cont2);
    $tbl->addRow($rMain);

    $MainPanel->add($tbl);
} else {
    $tbl = new Table(array(""));
    $tbl->setBorder(2);
    $tbl->setColSizes(array("800"));
    $tbl->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_1']);

    $rMain = $tbl->createRow();
    $rMain->setAttribute(0, $cont);
    $tbl->addRow($rMain);

    $MainPanel->add($tbl);
}

$contentLayoutRow = $layoutTable->createRow();
$contentLayoutRow->setAttribute(0, $MainPanel);
$layoutTable->addRow($contentLayoutRow);

/* --------------------------------- */


/* ------------------------------------
FUSS-MENU 
------------------------------------ */

$footMenuDiv = new Div();
$footMenuDiv->setWidth(800);
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
$fussLayoutRow->setAttribute(0, $footMenuDiv);
$layoutTable->addRow($fussLayoutRow);

/* --------------------------------- */

$versionInfo = "Version: " .file_get_contents('version.txt');;
$lVersion = new Link("http://smarthomeyourself.de/statusInfo.php", $versionInfo, false, "status");

$versionLayoutRow = $layoutTable->createRow();
$versionLayoutRow->setAttribute(0, $lVersion);
$layoutTable->addRow($versionLayoutRow);

/* --------------------------------- */


$layoutTable->show();
    
$arduinoFrame = new IFrame($_SESSION['config'], "arduinoSwitch", -1, -1, 1, 1, 0);
$arduinoFrame->show();

?>
