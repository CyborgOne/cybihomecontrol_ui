<?PHP

$spc = new Spacer(20);
$ln = new Line();
$weekDays = array(0 => "sonntag", 1 => "montag", 2 => "dienstag", 3 =>
    "mittwoch", 4 => "donnerstag", 5 => "freitag", 6 => "samstag");

$dayOfWeek = date("w", time());
$nextDayOfWeek = $dayOfWeek < 6 ? $dayOfWeek + 1:0;

//Order By (Aktueller Wochentag als erstes)
$o = "if(".$weekDays[$dayOfWeek]."='J' AND ("."(stunde = ".date("H").
    " AND minute > ".date("i")." ) OR "."(stunde > ".date("H").
    ")) ,'0','1'),stunde, minute,";

$o .= $weekDays[$dayOfWeek];
for ($iO = 1; $iO < 7; $iO++) {
    $weekDayIndex = ($dayOfWeek + $iO) <= 6 ? ($dayOfWeek + $iO):($dayOfWeek + $iO) -
        7;
    $o .= ",".$weekDays[$weekDayIndex];
}

//Where (Aktueller und nächster Wochentag)
$w = "(".$weekDays[$dayOfWeek]."='J' AND ("."(stunde = ".date("H").
    " AND minute > ".date("i")." ) OR "."(stunde > ".date("H").")))"." OR (".$weekDays[$nextDayOfWeek].
    "='J' AND ("."(stunde = ".date("H")." AND minute <= ".date("i")." ) OR ".
    "(stunde < ".date("H").")))";


$scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron',
    array("name", "montag", "dienstag", "mittwoch", "donnerstag", "freitag",
    "samstag", "sonntag", "stunde", "minute", "id"),
    "Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min", "", $o, $w);


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

?>

