<?PHP
// Array für alle anzuzeigenden Log-Files.
// Nach Kategorie sortiert anlegen um doppelte Überschrift zu vermeiden. 
// (bisher keine automatische Sortierung)
$logFiles    = array(
                     new LogFile("/var/www/switch.log", 
                                 "HomeControl",
                                 ""), 
                     new LogFile("/var/log/homecontrol_cron",
                                 "HomeControl", 
                                 ""),
                     new LogFile("/var/www/signalIn.log",
                                 "HomeControl", 
                                 ""),
                     new LogFile("/var/log/homecontrol_log_cleanup",    
                                 "HomeControl", 
                                 ""),
                     new LogFile("/var/log/homecontrol_motion_cleanup", 
                                 "HomeControl", 
                                 "")
/*                                 ,
                     new LogFile("/var/log/apache2/error.log",          
                                 "System", 
                                 ""),
                     new LogFile("/var/log/syslog",
                                 "System", 
                                 ""),
                     new LogFile("/var/log/auth.log", 
                                 "System", 
                                 ""),
                     new LogFile("/var/log/messages",
                                 "System", 
                                 "")
*/                                 
                    );


$lastCategorie = "";
$spc = new Spacer();
$ln = new Line();

foreach ($logFiles as $log ){
    if($lastCategorie != $log->getCategory()){
        $log->setShowCategory(true);
        $lastCategorie = $log->getCategory();

        $spc->show();
    }
    
    $log->show();
    
    $spc->show();
}




?>