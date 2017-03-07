<?PHP

class HomeControlEtage {
    private $ID;
    private $NAME;
    private $PIC;
    
    private $ITEMS = array();
    private $ZIMMER = array();
    private $SENSOREN = array();
    
    private $ETAGEN_ROW;
    
    function HomeControlEtage($rowEtage){
        $this->ETAGEN_ROW = $rowEtage;
        
        $this->ID = $rowEtage->getNamedAttribute("id");
        $this->NAME = $rowEtage->getNamedAttribute("name");
        $this->PIC = $rowEtage->getNamedAttribute("pic");
        
        $this->refresh();
    }
    
    function refresh(){
        $this->refreshZimmer();
        $this->refreshItems();
        $this->refreshSensoren();        
    }
    
    function refreshZimmer(){
        $zimmerDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_zimmer",array('*'),"","","","etage_id=".$this->ID);
        $this->ZIMMER = array();
        foreach($zimmerDbTbl->ROWS as $zimmerRow){
            $this->ZIMMER[count($this->ZIMMER)] = $_SESSION['config']->getZimmerByRow($zimmerRow);
        }
    }
    

    function refreshItems(){
        $itemDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_config",array('*'),"","","","etage=".$this->ID);
        $this->ITEMS = array();
        foreach($itemDbTbl->ROWS as $itemRow){
            $this->ITEMS[count($this->ITEMS)] = $_SESSION['config']->getItemByRow($itemRow);
        }
    }
    
        
    function refreshSensoren(){
        $sensorDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sensor",array('*'),"","","","etage=".$this->ID);
        $this->SENSOREN = array();
        foreach($sensorDbTbl->ROWS as $sensorRow){
            $this->SENSOREN[count($this->SENSOREN)] = $_SESSION['config']->getSensorByRow($sensorRow);
        }
    }
    
    
    function getRaumplan() {
        $img = new Image($this->getEtagenImagePath(), -1, -1, 640);
        return $img;
    }


    function getImagePath() {
        return $this->PIC!=null&&strlen($this->PIC)>0?$this->PIC:"/pics/default_etage.jpg";
    }
    
    
    function deleteEtageDetails(){
        foreach($this->ZIMMER as $z){
            $z->deleteZimmer();
        }

        $this->refreshItems();
        
        foreach($this->ITEMS as $i){
            $i->deleteItem();
        }

        $this->refreshSensoren();
        
        foreach($this->SENSOREN as $sensor){
            $sensor->deleteSensor();
        }
        
        $this->refresh();
    }

    function deleteEtage(){
        $this->deleteEtageDetails();
        $this->ETAGEN_ROW->deleteFromDb();
    }        
}

?>