<?PHP

class HomeControlSender{
    private $ID;
    private $NAME;
    private $IP;
    private $ETAGE;
    private $ZIMMER;
    private $X;
    private $Y;
    private $SENDER_TYP_ID;
    private $TYP;
    private $DEFAULT;
    
    private $SENDER_ROW;
    
    function HomeControlSender($senderRow){
        $this->ID = $senderRow->getNamedAttribute("id");
        $this->NAME = $senderRow->getNamedAttribute("name");
        $this->IP = $senderRow->getNamedAttribute("ip");
        $this->ETAGE = $senderRow->getNamedAttribute("etage");
        $this->ZIMMER = $senderRow->getNamedAttribute("zimmer");
        $this->X = $senderRow->getNamedAttribute("x");
        $this->Y = $senderRow->getNamedAttribute("y");
        $this->SENDER_TYP_ID = $senderRow->getNamedAttribute("senderTypId");
        $this->DEFAULT = $senderRow->getNamedAttribute("default_jn");
        $this->SENDER_ROW = $senderRow;
        
        $typDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen",array('*'),"","","","id=".$this->SENDER_TYP_ID);
        $this->TYP = new HomeControlSenderTyp($typDbTbl->getRow(1));
    }

    function getId(){
        return $this->ID;
    }
    
    function getSenderTypId(){
        return $this->SENDER_TYP_ID;
    }
    
    function getTyp(){
        return $this->TYP;
    }    
    
    function getName(){
        return $this->NAME;
    }
    
    function isDefault(){
        return "J"==$this->DEFAULT;
    }
    
    function hasDefaultParam(){
        return $this->TYP->hasDefaultParam();
    }
    
    /**
     * Liefert die Maske zum bearbeiten der Parameter entsprechend des Sender-Typs.
     * Damit die richtigen Werte angezeigt werden können muss hier die entsprechende
     * DbRow der Tabelle homecontrol_config mitgegeben werden. 
     */
    function getSenderParameterEditMask($configItem){
        return $this->TYP->getParameterEditMask($configItem);
    }
        
    function getSenderParameterControlMask($configItem){
        return $this->TYP->getParameterControlMask($configItem);
    }
    
    function doParameterUpdate($configRow){
        $this->TYP->doParameterUpdate($configRow);
    }
}

?>