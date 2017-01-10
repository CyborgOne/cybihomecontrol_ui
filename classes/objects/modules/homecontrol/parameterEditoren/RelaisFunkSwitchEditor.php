<?PHP

/**
 * SHYS - Editor
 * 
 * Klassenname: HomeControlRGBColorEditor
 * 
 * Parameter:
 *  red:   Zahl (0-255)
 *  green: Zahl (0-255)
 *  blue:  Zahl (0-255)
 */
 class RelaisFunkSwitchEditor  extends HomeControlParameterEditorMain{
   private $PARAMS;

   private $RELAIS_ID;
   private $RELAIS_STATUS;

   private $PARAM_NAME_RELAIS_ID;
   private $PARAM_NAME_RELAIS_STATUS;


   private $RELAIS_STATUS_ARRAY = array();


         
   function RelaisFunkSwitchEditor($editorRow, $configItem){
        $this->setControl($configItem);
        $this->SENDER    = $this->getControl()->getSender();
        $this->SENDERTYP = $this->SENDER->getTyp();
        $this->PARAMS    = $this->getControl()->getControlParameter();
        
        $this->PARAM_NAME_RELAIS_ID = $this->getSenderParameterName("relaisId");
        $this->PARAM_NAME_RELAIS_STATUS = $this->getSenderParameterName("relaisStatus");
        
        $this->setId($editorRow->getNamedAttribute("id"));
        $this->setClassname($editorRow->getNamedAttribute("classname"));
        $this->setDescr($editorRow->getNamedAttribute("descr"));
        $this->setPic($editorRow->getNamedAttribute("pic"));
        
        $this->PARAM_URL_NAME = "choosen-color".$this->HC_ITEM->getRow()->getNamedAttribute("id");
        
        if($this->isActivated()){
            foreach($this->PARAMS as $param){
                if($param->getName()==$this->PARAM_NAME_RELAIS_ID){
                    $this->RELAIS_ID = $param->getValue(); 
                }
                if($param->getName()==$this->PARAM_NAME_RELAIS_STATUS){
                    $this->RELAIS_STATUS = $param->getValue(); 
                }
            }
            $this->RELAIS_STATUS_ARRAY[$this->RELAIS_ID] = $this->RELAIS_STATUS>0;
        }
   }
   
   
   /**
    * Gibt an, ob die Parameter zum Editor kompatibel sind
    * 
    * Es sind zwei Parameter (relaisId und relaisStatus) von Typ Zahl und vom Typ relaisStatus erforderlich 
    * Die ID muss zwischen 0 und der Anzahl von Relais-1 liegen
    * Der Status ist entweder "Einschalten" oder "Ausschalten" 
    */
   function isActivated(){
     $idOk = false;
     $statusOk = false;
     
     // TODO: Prüfung auf PARAMETER-ART
     foreach($this->PARAMS as $param){
        if($param->getName()==$this->PARAM_NAME_RELAIS_ID){
            $idOk = true;
        }
        if($param->getName()==$this->PARAM_NAME_RELAIS_STATUS){
            $statusOk = true;
        }
     }
     
     return $idOk && $statusOk;
   }

   
   function getEditMask(){
     $dv = new Div();
          
     $ret = new Form("","","",$this->PARAM_URL_NAME."Form");
     $ret->add($dv);
     return $ret; 
   }
   
   
   function doUpdate(){
     if(isset($_REQUEST[$this->PARAM_URL_NAME])&&strlen($_REQUEST[$this->PARAM_URL_NAME])>0){
        echo "Neue Farbe: ".$_REQUEST[$this->PARAM_URL_NAME]."<br>";
     }
   }
 }
 
 ?>