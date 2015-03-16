<?PHP

class HomeControlCron {
    private $CRON_ROW;
    private $WEEKDAYS = array(0 => "sonntag", 
                              1 => "montag", 
                              2 => "dienstag", 
                              3 => "mittwoch", 
                              4 => "donnerstag", 
                              5 => "freitag", 
                              6 => "samstag");
                      
    private $SHORTWEEKDAYS = array(0 => "So.", 
                                   1 => "Mo.", 
                                   2 => "Di.", 
                                   3 => "Mi.", 
                                   4 => "Do.", 
                                   5 => "Fr.", 
                                   6 => "Sa.");
                      
    function HomeControlCron($homeControlCronDbRow){
        $this->CRON_ROW = $homeControlCronDbRow;
        
    }
    
   
    function getId(){
        return $this->CRON_ROW->getNamedAttribute("id");
    }
         
    function getName(){
        return $this->CRON_ROW->getNamedAttribute("name");
    }
      
    function getBeschreibung(){
        return $this->CRON_ROW->getNamedAttribute("beschreibung");
    }
   
    function getStunde(){
        return $this->CRON_ROW->getNamedAttribute("stunde");
    }
   
    function getMinute(){
        return $this->CRON_ROW->getNamedAttribute("minute");
    }
   
    function getPauseLink(){
        $lnk = new Link("?pauseCron=".$this->getId(), new Text("Pause", 4));
        $lnk->setToolTip("Nächste Ausführung auslassen");
        
        return $lnk;
    }
        
    function getNextExecutionDayIndex(){
        $dayOfWeek = date( "w", time());
        
        for ($iO=0;$iO<7;$iO++){
            $weekDayIndex = ($dayOfWeek+$iO)<=6?($dayOfWeek+$iO):($dayOfWeek+$iO)-7;

            if($this->CRON_ROW->getNamedAttribute($this->WEEKDAYS[$weekDayIndex])=="J"){
                $std = $this->CRON_ROW->getNamedAttribute("stunde");
                $min = $this->CRON_ROW->getNamedAttribute("minute");
                
                if( (($std == date("H") && $min > date("i")) || 
                    ($std > date("H"))) || $iO > 0 ){
                    return $weekDayIndex;
                }
            }
        }
    }
    
    
    function getNextExecutionTimeAsString(){
        $dayIndex = $this->getNextExecutionDayIndex();
        $ret = "";
        
        if(strlen($dayIndex)>0 && $dayIndex>=0 && $dayIndex<=6){
            $ret = $this->SHORTWEEKDAYS[$dayIndex]; 
            $ret .= " " .sprintf('%02d',$this->CRON_ROW->getNamedAttribute("stunde"));
            $ret .= ":" .sprintf('%02d',$this->CRON_ROW->getNamedAttribute("minute")); 
        }   
        return $ret;
    }    
}

?>