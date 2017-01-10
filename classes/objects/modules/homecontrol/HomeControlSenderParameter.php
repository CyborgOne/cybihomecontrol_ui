<?PHP

class HomeControlSenderParameter {
    private $ID;    
    private $NAME;    
    private $ART_ID;    
    private $FIX;    
    private $DEFAULT_LOGIC;
    private $CONFIG_ID;
    private $VALUE;
        
    private $CONFIG_ITEM;
    private $ROW;
    private $CONFIG_ROW;

    function HomeControlSenderParameter($parameterRow, $configItem) {
        $configRow = $configItem->getRow();
        
        $this->ID   = $parameterRow->getNamedAttribute("id");
        $this->NAME = $parameterRow->getNamedAttribute("name");
        $this->ART_ID = $parameterRow->getNamedAttribute("parameterArtId");
        $this->FIX  = $parameterRow->getNamedAttribute("fix");
        $this->DEFAULT_LOGIC = $parameterRow->getNamedAttribute("default_logic");
        $this->CONFIG_ID = $configRow->getNamedAttribute("id");

        $this->ROW = $parameterRow;
        $this->CONFIG_ROW = $configRow;
        
        $this->CONFIG_ITEM = $configItem;

        $this->VALUE = $configItem->getParameterValue($parameterRow);
    }
    
    function getId(){
        return $this->ID;
    }
    
    function getName(){
        return $this->NAME;
    }
    
    function getArtId(){
        return $this->ART_ID;
    }
    
    function isFix(){
        return "J"==$this->FIX;
    }
    
    function isDefaultLogic(){
        return "J"==$this->DEFAULT_LOGIC;
    }
    
    function getValue(){
        return $this->VALUE;
    }
    
    function getRow(){
        return $this->ROW;
    }
}

?>