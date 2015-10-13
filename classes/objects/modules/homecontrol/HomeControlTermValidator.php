<?PHP

class HomeControlTermValidator {
    private $TERM_ROW;
    private $TYPE;
    

    private $TYPE_SENSOR_WERT      = 1;
    private $TYPE_SENSOR_STATUS    = 2;
    private $TYPE_ZEIT             = 3;
    private $TYPE_WOCHENTAG        = 4;


    function HomeControlTermValidator($termRow){
        $this->TERM_ROW = $termRow;
        
        $this->TYPE = $this->TERM_ROW->getNamedAttribute("term_type");
    }

    
    function checkSensorStatus(){
        $sensorVal         = getDbValue("homecontrol_sensor", "lastValue", "id=".$this->TERM_ROW->getNamedAttribute("sensor_id"));         
        $checkSensorStatus = $this->TERM_ROW->getNamedAttribute("status");
         
        if($checkSensorStatus=="J"){
            return $sensorVal>0;
        } else {
            return $sensorVal<=0;            
        }
    }


    function checkSensorWert(){
        $lastSensorVal  = getDbValue("homecontrol_sensor", "lastValue", "id=".$this->TERM_ROW->getNamedAttribute("sensor_id"));
        $checkSensorVal = $this->TERM_ROW->getNamedAttribute("value");
        $comperator     = $this->TERM_ROW->getNamedAttribute("termcondition");

        return $this->isCompare($lastSensorVal, $checkSensorVal, $comperator);
    }
    

    function checkZeit(){
        $currentHour = date("G");
        $currentMin  = date("s");
        $checkHour   = $this->TERM_ROW->getNamedAttribute("std");
        $checkMin    = $this->TERM_ROW->getNamedAttribute("min");
        $comperator  = $this->TERM_ROW->getNamedAttribute("termcondition");
        
        if ( $currentHour == $checkHour ){
            // Stunde stimmt überein, also Minuten vergleichen
            return $this->isCompare($currentMin, $checkMin, $comperator);
        } else {
            // Stunde ist abweichend, also Stunden vergleichen
            return $this->isCompare($currentHour, $checkHour, $comperator);
        }
        
        return false;
    }
    

    function checkWochentag(){
        $currentDay = strtolower(getDefaultComboValue("tage", date("N")));
        
        if($this->TERM_ROW->getNamedAttribute($currentDay)=="J"){
            return true;
        }
        
        return false;        
    }


    function isCompare($valueA, $valueB, $comp){
        
        if($comp == "<"){
            echo "$valueA < $valueB";
            return $valueA < $valueB;           
        } else if($comp == ">"){
            echo "$valueA > $valueB";
            return $valueA > $valueB;            
        } else if($comp == "<="){
            echo "$valueA <= $valueB";
            return $valueA <= $valueB;            
        } else if($comp == ">="){
            echo "$valueA >= $valueB";
            return $valueA >= $valueB;
        } else if($comp == "="){
            echo "$valueA = $valueB";
            return $valueA == $valueB;
        }
        
    }


    /**
     *  
     * @return boolean    gibt zurück ob die Bedingung erfüllt ist
     */
    function isValid(){
        if($this->TYPE == $this->TYPE_SENSOR_STATUS){
            return $this->checkSensorStatus();
        } else if($this->TYPE == $this->TYPE_SENSOR_WERT){
            return $this->checkSensorWert();
        } else if($this->TYPE == $this->TYPE_ZEIT){
            return $this->checkZeit();
        } else if($this->TYPE == $this->TYPE_WOCHENTAG){
            return $this->checkWochentag();
        }

        return false;
    }
}

?>