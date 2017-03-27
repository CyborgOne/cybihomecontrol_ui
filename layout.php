<?php
/**
 * @filename layout.php
 * @author  Daniel Scheidler
 * @copyright Oktober 2012
 */
$detect = new Mobile_Detect();

$bannerWidth = 780;
$bannerHeight = 195;
$mainSizeInternal = 610;
$_SESSION['additionalLayoutHeight'] = $bannerHeight;

$mainHeight = 390;
$mainWidth = $mainSizeInternal;

if (isset($_SESSION['MENU_PARENT']) && $_SESSION['MENU_PARENT'] !=
    "Steuerung" && isset($_SESSION['runLink']) && $_SESSION['runLink'] !=
    "start") {
    $mainWidth = $bannerWidth;
}

if ($detect->isMobile()) {
    
    if ($detect->isTablet()) {
        // Tablet
        include ("layout_tablet.php");
        exit();
    }

    // Any other mobile device.
    include ("layout_mobile.php");
    exit();
}

$_SESSION['currentView'] = 'desktop';
include("header_index.php");


$sqlNoFrame = "SELECT * FROM homecontrol_noframe WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"; //$_SERVER['HTTP_X_FORWARDED_FOR']
$rslt = $_SESSION['config']->DBCONNECT->executeQuery($sqlNoFrame);
$noFrameExists = mysql_num_rows($rslt)>0; 

if(isset($_REQUEST['noFrame']) && ($_REQUEST['noFrame']=="on" || $_REQUEST['noFrame']=="off")){
  $_SESSION['noFrame'] = $_REQUEST['noFrame'];
  
  if(!$noFrameExists && $_REQUEST['noFrame']=="on"){
      $sqlAddNoFrame = "INSERT INTO homecontrol_noframe (ip) VALUES ('".$_SERVER['REMOTE_ADDR']."')"; //$_SERVER['HTTP_X_FORWARDED_FOR']
      $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sqlAddNoFrame);    
  }
  if($noFrameExists && $_REQUEST['noFrame']=="off"){
      $sqlDelNoFrame = "DELETE FROM homecontrol_noframe WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"; //$_SERVER['HTTP_X_FORWARDED_FOR']
      $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sqlDelNoFrame);    
  }
}


$sqlNoFrame = "SELECT * FROM homecontrol_noframe WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"; //$_SERVER['HTTP_X_FORWARDED_FOR']
$rslt = $_SESSION['config']->DBCONNECT->executeQuery($sqlNoFrame);
$noFrameLayout = mysql_num_rows($rslt)>0; 

if(isset($_REQUEST['changeMode']) && strlen($_REQUEST['changeMode'])>0 && $_SESSION['config']->PUBLICVARS['changeMode']!=$_REQUEST['changeMode']){
  $updSql = "UPDATE pageconfig SET value=".$_REQUEST['changeMode']." WHERE name='currentMode' ";
  $_SESSION['config']->DBCONNECT->executeQuery($updSql);

  $_SESSION['config']->PUBLICVARS['currentMode'] = $_REQUEST['changeMode'];
} 

echo "<div id=\"messageText\" class=\"messageText\">";
echo "TEST";
echo "</div>";

$topSpaceTable = new Table(array(""));
$topSpaceTable->show();

$layoutTable = new Table(array(""));
$layoutTable->setWidth($bannerWidth);
$layoutTable->setAlign("left");
$layoutTable->setBORDER(0);
$layoutTable->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$layoutTable->setSpacing(0);
$layoutTable->setPadding(0);


/* ------------------------------------
BANNER
------------------------------------ */
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
    $bannerHeight = $_SESSION['additionalLayoutHeight'];
}

  



$modeSwitchComboTbl = new Table(array(""));
$modeSwitchComboTbl->setWidth(100);
$modeSwitchComboTbl->setAlign("center");
$modeSwitchComboTbl->setVAlign("middle");
$modeSwitchComboTbl->setHeight(25);

$cobMode = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_modes WHERE selectable = 'J'","changeMode",$_SESSION['config']->PUBLICVARS['currentMode']);
$cobMode->setDirectSelect(true);

$rMode = $modeSwitchComboTbl->createRow();
$rMode->setAttribute(0, $cobMode);
$modeSwitchComboTbl->addRow($rMode);

$fMode = new Form();
$fMode->add($modeSwitchComboTbl);

/* ------------------------------------
HAUPT-MENU
------------------------------------ */
$menuDiv = new Div();
$menuDiv->setWidth($bannerWidth-80);
$menuDiv->setBorder(0);
$menuDiv->setOverflow("hidden");
$menuDiv->setAlign("left");
$menuDiv->setStyle("padding-left", "2px");
$menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

$menu = new DbMenu("Kopfmenue");
$menu->setAlign("center");
$menu->setFontsize("4em");
$menu->setMenuType("horizontal");
$menu->setSpacer(new Text("|"));
$menuDiv->add($menu);

$tblMenu = new Table(array("",""));
$tblMenu->setAlignments(array("left", "right"));
$tblMenu->setWidth($bannerWidth+20);
$tblMenu->setColSizes(array($bannerWidth-70));
$rMenu = $tblMenu->createRow();
$rMenu->setAttribute(0,$menuDiv);
$rMenu->setAttribute(1,$fMode);
$tblMenu->addRow($rMenu);

$layoutTable->addSpacer(0, 15);
$contentLayoutRow1 = $layoutTable->createRow();
$contentLayoutRow1->setAttribute(0, $tblMenu);
$layoutTable->addRow($contentLayoutRow1);

/* --------------------------------- */


/* ------------------------------------
HAUPTPANEL
------------------------------------ */
$MainPanel = new Div();
$MainPanel->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);
$MainPanel->setWidth($bannerWidth+12);
$MainPanel->setBorder(2);
$MainPanel->setStyle("padding", "0px 0px");
$MainPanel->setStyle("margin", "0px 0px");
$MainPanel->setStyle("overflow-x", "hidden");
$MainPanel->setStyle("overflow-y", "overflow");

$cont = new DivByInclude($_SESSION['mainpage'], false);
$cont->setWidth($mainWidth);
$cont->setStyle("overflow-y", "overflow");
$cont->setStyle("padding", "5px 6px");

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
        $menuDiv->setWidth($bannerWidth);
        $menuDiv->setHeight($menuHeight);
        $menuDiv->setBorder(0);
        $menuDiv->setAlign("left");
        $menuDiv->setStyle("padding", "0px 0px");
        //       $menuDiv->setBackgroundColor($_SESSION['config']->COLORS['panel_background']);

        $menu = new DbMenu("Hauptmenue");
        $menu->setAlign("center");
        $menu->setFontsize(3);
        $menu->setMenuType("horizontal");
        $menu->setSpacer(new Text("|"));
        $menuDiv->add(new Line(1, 6));
        $menuDiv->add($menu);
        $menuDiv->add(new Line(1, 6));

        $cont->add($menuDiv);
    }
}

if (isset($_SESSION['MENU_PARENT']) && $_SESSION['MENU_PARENT'] == "Steuerung") {
    $cont2x = new DivByInclude("includes/ShortcutSidebar.php", false);
    $cont2x->setWidth("160");
    $cont2x->setHeight($mainHeight);
    $cont2x->setStyle("padding-left", "4px");
    $cont2x->setStyle("padding-right", "6px");
    $cont2x->setBorder(0);
    $cont2x->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_2']);
    $cont2x->setStyle("overflow-x", "hidden");
    $cont2x->setStyle("overflow-y", "overflow");
      
    
    $cont2 = new Div();
    $cont2->setWidth("170");
    $cont2->add($cont2x);
    $cont2->setStyle("overflow-x", "hidden");
    $cont2->setStyle("overflow-y", "auto");
    

    $spcr = new Div();
    $spcr->setWidth(0);
    $spcr->setHeight($mainHeight);

    $tbl = new Table(array("", "", ""));
    $tbl->setBorder(0);
    $tbl->setColSizes(array($mainWidth, "1"));
    $tbl->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_1']);

    $rMain = $tbl->createRow();
    $rMain->setAttribute(0, $cont);
    $rMain->setAttribute(1, $spcr);
    $rMain->setAttribute(2, $cont2);
    $tbl->addRow($rMain);

    $MainPanel->add($tbl);
} else {
    $tbl = new Table(array(""));
    $tbl->setBorder(0);
    $tbl->setColSizes(array($mainWidth));
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
$footMenuDiv->setWidth($bannerWidth);
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
$layoutTable->show();




$arduinoFrame = new IFrame($_SESSION['config'], "arduinoSwitch", -1, -1, 1, 1, 0);
$arduinoFrame->show();

// MailSensor
$dbSensorTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_sensor',
                             array(  "*" ), 
                             "", 
                             "", 
                             "", 
                             "id=999999999");

if(count($dbSensorTable->ROWS)>0){
    $mailSensorRow = $dbSensorTable->getRow(1);
    $mailSensor = new HomeControlSensor($mailSensorRow, false);
    $x = 15;
    $y = $bannerHeight-10;
    echo "<div style=\"position:absolute; left:" .$x ."px; top:" .$y ."px; width:26px; height:26px;\">";
    echo $mailSensor->getSensorArtIconSrc();
    echo "</div>";
    if($mailSensor->getLastValue()!=null){
      echo "<div style=\"background-color:#dedede; position:absolute; left:" .($x + 5) ."px; top:" . ($y  + ($mailSensor->getControlImageHeight()/2)-3) ."px;\"><center><font size=2><b>".$mailSensor->getLastValue()."</b></font></center></div>";   
    }        
}

?>
