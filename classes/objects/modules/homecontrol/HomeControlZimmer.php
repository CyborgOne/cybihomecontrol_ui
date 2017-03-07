<?PHP

class HomeControlZimmer {
    private $ID;
    private $NAME;
    private $ETAGE_ID;
    private $ETAGE;

    private $ZIMMER_ROW;
    
    private $ITEMS = array();
    private $SENSOREN = array();
    
    function HomeControlZimmer($zimmerRow){
        $this->ZIMMER_ROW =  $zimmerRow;

        $this->ID =  $zimmerRow->getNamedAttribute("id");
        $this->NAME =  $zimmerRow->getNamedAttribute("id");
        $this->ETAGE_ID =  $zimmerRow->getNamedAttribute("id");
        
        if($this->ETAGE_ID != null && strlen($this->ETAGE_ID)>0){
            $this->ETAGE = $_SESSION['config']->getEtageById($this->ETAGE_ID);
        }
        
        $this->refresh();
    }
    

    function refresh(){
        $this->refreshItems();
        $this->refreshSensoren();        
    }
    
    
    function refreshItems(){
        $itemDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_config",array('*'),"","","","zimmer=".$this->ID);
        $this->ITEMS = array();
        foreach($itemDbTbl->ROWS as $itemRow){
            $this->ITEMS[count($this->ITEMS)] = $_SESSION['config']->getItemByRow($itemRow);
        }
    }
    
    function refreshSensoren(){
        $sensorDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sensor",array('*'),"","","","zimmer=".$this->ID);
        $this->SENSOREN = array();
        foreach($sensorDbTbl->ROWS as $sensorRow){
            $this->SENSOREN[count($this->SENSOREN)] = $_SESSION['config']->getSensorByRow($sensorRow);
        }
    }
    
    
    function deleteZimmerDetails(){
        foreach($this->ITEMS as $itm){
            $itm->deleteItem();
        }
        
        foreach($this->SENSOREN as $sensor){
            $sensor->deleteSensor();
        }
        
        if($this->ETAGE != null && $this->ETAGE instanceof HomeControlEtage ){
            $this->ETAGE->refresh();
        }
        
        $this->refresh();
    }

    function deleteZimmer(){
        $this->deleteZimmerDetails();
        $this->ZIMMER_ROW->deleteFromDb();
    }    
}

?>