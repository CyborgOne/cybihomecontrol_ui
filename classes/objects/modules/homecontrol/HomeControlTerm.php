<?PHP

class HomeControlTerm extends Object {
    private $TERM_ROW = null;
    private $ADDITIONAL = false;
    
    function HomeControlTerm($termRow, $additional=false) {
        $this->TERM_ROW = $termRow;
        $this->ADDITIONAL = $additional;
    }

    
    function getTriggerId() {
        return $this->TERM_ROW->getNamedAttribute('trigger_id');
    }

    function getTriggerSubid() {
        return $this->TERM_ROW->getNamedAttribute('trigger_subid');
    }

    function getTermType() {
        return $this->TERM_ROW->getNamedAttribute('term_type');
    }

    private function getCondition(){
        return  $this->TERM_ROW->getNamedAttribute('termcondition');
    }
    
    private function getStatus(){
        return  $this->TERM_ROW->getNamedAttribute('status');
    }
    
    private function getValue(){
        return  $this->TERM_ROW->getNamedAttribute('value');
    }
    
    private function getStd(){
        return str_pad($this->TERM_ROW->getNamedAttribute('std'),2, '0', STR_PAD_LEFT);
    }
    

    private function getMin(){
        return str_pad($this->TERM_ROW->getNamedAttribute('min'),2, '0', STR_PAD_LEFT);
    }

    function isAdditional(){
        return $this->ADDITIONAL;
    }
    
    
    function getDescription() {
        $descr = "";
        switch ($this->getTermType()) {
            case 1:
                $descr = $this->getDescriptionForSensorWert();

                break;

            case 2:
                $descr = $this->getDescriptionForSensorStatus();

                break;

            case 3:
                $descr = $this->getDescriptionForTime();

                break;

            case 4:
                $descr = $this->getDescriptionForDay();

                break;

            default:
                $descr = $this->getDescriptionForSensorStatus();

                break;
        }
        
        return "".$this->isAdditional()?(($this->TERM_ROW->getNamedAttribute('and_or')=="and"?" und ":" oder ").$descr):$descr;
    }




    
    private function getDescriptionForSensorWert() {
        return "Sensor ".$this->TERM_ROW->getTriggerId().": ".$this->getCondition()." ".$this->TERM_ROW->getValue();
    }
    
    private function getDescriptionForSensorStatus() {
        return "Sensor ".$this->TERM_ROW->getTriggerId().": ".$this->getStatus();
    }

    private function getDescriptionForTime() {
        return "Uhrzeit: ".$this->getCondition()." ".$this->getStd().":".$this->getMin();
    }

    private function getDescriptionForDay() {
        $ret = "Tage: ";
        
        $ret .= $this->TERM_ROW->getNamedAttribute('montag')=="J"?" mo,":"";
        $ret .= $this->TERM_ROW->getNamedAttribute('dienstag')=="J"?" di,":"";
        $ret .= $this->TERM_ROW->getNamedAttribute('mittwoch')=="J"?" mi,":"";
        $ret .= $this->TERM_ROW->getNamedAttribute('donnerstag')=="J"?" do,":"";
        $ret .= $this->TERM_ROW->getNamedAttribute('freitag')=="J"?" fr,":"";
        $ret .= $this->TERM_ROW->getNamedAttribute('samstag')=="J"?" sa,":"";
        $ret .= $this->TERM_ROW->getNamedAttribute('sonntag')=="J"?" so,":"";
        
        return $ret;
    }



    function show(){
        $t = new Text($this->getDescription());
        $t->show();
    }

}

?>