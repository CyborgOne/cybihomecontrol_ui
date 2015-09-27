<?PHP

$timelineDuration = getPageConfigParam($_SESSION['config']->DBCONNECT, "timelineDuration");

$spc = new Spacer(20);
$ln = new Line();
$weekDays = array(0 => "sonntag", 1 => "montag", 2 => "dienstag", 3 =>
    "mittwoch", 4 => "donnerstag", 5 => "freitag", 6 => "samstag");

$dayOfWeek = date("w", time());
$w = "(tagnr=".$dayOfWeek ." AND ((stunde >= ".date("H").
        " AND minute > ".date("i")." ) OR (stunde > ".date("H")."))) OR ";
     
$o = "if( ((tagnr=" .$dayOfWeek ." AND ((stunde >= ".date("H").
        " AND minute > ".date("i")." ) OR (stunde > ".date("H")."))) OR (tagnr!=" .$dayOfWeek .") ) AND tagnr-" .($dayOfWeek) .">=0 ,tagnr-" .($dayOfWeek) .", tagnr+(7-" .($dayOfWeek) .")), stunde, minute";
        
if ($timelineDuration>1){
    for($tmpDays=1;$tmpDays<$timelineDuration;$tmpDays++){
      $dayOfWeek = $dayOfWeek < 6 ? $dayOfWeek + 1:0;
      $w .= "tagnr=".$dayOfWeek." OR ";
    }
}
$nextDayOfWeek = $dayOfWeek < 6 ? $dayOfWeek + 1:0;

//Where (Aktueller und nächster Wochentag)
$w .= " (tagnr=".$nextDayOfWeek." AND ((stunde <= ".date("H")." AND minute <= ".date("i")." ) OR ".
     "(stunde < ".date("H").")))";


$scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cronview',
    array("wochentag", "tagnr", "name", "montag", "dienstag", "mittwoch", "donnerstag", "freitag",
    "samstag", "sonntag", "stunde", "minute", "id"),
    "Wochentag, Tag Nr, Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min", "", $o, $w);


// --------------------------------------------


$t = new Title("Timeline");
$t->show();


$tbl = new Table(array("", "", ""));
$tbl->setBackgroundColorChange(true);

foreach ($scDbTable->ROWS as $row) {
    $cron = new HomeControlCron($row);

    $cron->checkPauseLink();

    $r = $tbl->createRow();
    $r->setAlignments(array("left", "left", "right"));
    $r->setStyle("padding-left", "5px");
    $r->setStyle("padding-right", "25px");
    $r->setStyle("padding-top", "12px");
    $r->setStyle("padding-bottom", "7px");
    
    $executionTime = new Text($cron->getNextExecutionTimeAsString(), 3);
    $name = new Text($cron->getName(), 3);
    if($cron->isCronPaused()){
        $ft = $executionTime->getFonttype(); 
        $ft->setColor("red");
        $executionTime->setFonttype($ft);
        $name->setFonttype($ft);
    }
    $r->setAttribute(0, $executionTime);
    $r->setAttribute(1, $name);
    $r->setAttribute(2, $cron->getPauseLink());

    $tbl->addRow($r);
}

$tbl->show();

if ( $_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->CURRENTUSER->STATUS != "user" ) {
           
   /* ------------------------------------
      BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;
     
    $USERSTATUS = new UserStatus($USR, -1, -1);
    
    $tbl = new Table( array("") );
    $tbl->addSpacer(0, 20);
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute( 0, $USERSTATUS );
    $tbl->addRow( $r );
    $tbl->addSpacer(0, 20);
    $tbl->show();
    /* --------------------------------- */
}

?>

