<?PHP

if ($_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->
    CURRENTUSER->STATUS != "user") {

    /* ------------------------------------
    BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;

    $USERSTATUS = new UserStatus($USR, -1, -1);

    $tbl = new Table(array(""));
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute(0, $USERSTATUS);
    $tbl->addRow($r);

    $tbl->show();
    /* --------------------------------- */


} else {


    $spc = new Spacer(20);
    $ln = new Line();
    $weekDays = array(0 => "sonntag", 
                      1 => "montag", 
                      2 => "dienstag", 
                      3 => "mittwoch", 
                      4 => "donnerstag", 
                      5 => "freitag", 
                      6 => "samstag");
                      
    $dayOfWeek = date( "w", time());
    $nextDayOfWeek = $dayOfWeek<6?$dayOfWeek+1:0;
    
    //Order By (Aktueller Wochentag als erstes)
    $o = "if(".$weekDays[$dayOfWeek]."='J' AND (" 
        ."(stunde = " .date("H") ." AND minute > " .date("i") ." ) OR "
        ."(stunde > " .date("H") .")) ,'0','1'),stunde, minute,";
        
    $o .= $weekDays[$dayOfWeek];
    for ($iO=1;$iO<7;$iO++){
      $weekIndex = ($dayOfWeek+$iO)<=6?($dayOfWeek+$iO):($dayOfWeek+$iO)-7;
      $o .= ",".$weekDays[$weekIndex]; 
    }

    //Where (Aktueller und n�chster Wochentag)
    $w = "(".$weekDays[$dayOfWeek]."='J' AND (" 
        ."(stunde = " .date("H") ." AND minute > " .date("i") ." ) OR "
        ."(stunde > " .date("H") .")))"
    ." OR (".$weekDays[$nextDayOfWeek]."='J' AND ("
        ."(stunde = " .date("H") ." AND minute <= " .date("i") ." ) OR "
        ."(stunde < " .date("H") .")))"; 
    
    
    $scDbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_cron',
        array("name", "montag", "dienstag", "mittwoch", "donnerstag", "freitag",
        "samstag", "sonntag", "stunde", "minute"),
        "Name,  Mo, Di, Mi, Do, Fr, Sa, So, Std, Min", "",
        $o,
        $w);
  
  
    $scDbTable->show();
}

?>

