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
class HomeControlRGBColorEditor  extends HomeControlParameterEditorMain {
    private $PARAMS;
    
    private $RED;
    private $GREEN;
    private $BLUE;
    
    private $PARAM_NAME_RED;
    private $PARAM_NAME_GREEN;
    private $PARAM_NAME_BLUE;
    
    private $PARAM_URL_NAME;
         
    function HomeControlRGBColorEditor($editorRow, $configItem, $formName){
        $this->setControl($configItem);
        $this->SENDER    = $this->getControl()->getSender();
        $this->SENDERTYP = $this->SENDER->getTyp();
        $this->PARAMS    = $this->getControl()->getControlParameter();
        
        $this->FORM_NAME = $formName;
        
        $this->setId($editorRow->getNamedAttribute("id"));
        $this->setClassname($editorRow->getNamedAttribute("classname"));
        $this->setDescr($editorRow->getNamedAttribute("descr"));
        $this->setPic($editorRow->getNamedAttribute("pic"));
        
        $this->PARAM_URL_NAME = "choosen-color".$this->getControl()->getId();
    
        // Sender-Parameternamen anhand der Editor-Parameternamen ermitteln
        $this->PARAM_NAME_RED = $this->getSenderParameterName("red");
        $this->PARAM_NAME_GREEN = $this->getSenderParameterName("green");
        $this->PARAM_NAME_BLUE = $this->getSenderParameterName("blue");
                
        if($this->isActivated()){
            foreach($this->PARAMS as $param){
                if($param->getName()==$this->PARAM_NAME_RED){
                    $this->RED = $param->getValue(); 
                }
                if($param->getName()==$this->PARAM_NAME_GREEN){
                    $this->GREEN = $param->getValue(); 
                }
                if($param->getName()==$this->PARAM_NAME_BLUE){
                    $this->BLUE = $param->getValue(); 
                }
            }
        }
        
        $this->refreshEditorConfigZuordnungsId();
    }
    
    
    /**
    * Gibt an, ob die Parameter zum Editor kompatibel sind
    * 
    * Es sind drei Parameter (red, green und blue) von Typ Zahl 0-255 erforderlich 
    */
    function isActivated(){
        $r=false;
        $g=false;
        $b=false;
        
        // TODO: Prüfung auf PARAMETER-ART
        foreach($this->PARAMS as $param){
            if($param->getName()==$this->PARAM_NAME_RED){
                $r = true;
            }
            if($param->getName()==$this->PARAM_NAME_GREEN){
                $g = true;
            }
            if($param->getName()==$this->PARAM_NAME_BLUE){
                $b = true;
            }
        }
        
        return $r && $g && $b;
    }
    
    
    function getEditMask(){
        $cs  = new ColorChooser($this->PARAM_URL_NAME, "#"
                            .str_pad(dechex($this->RED),   2, "0", STR_PAD_LEFT)
                            .str_pad(dechex($this->GREEN), 2, "0", STR_PAD_LEFT) 
                            .str_pad(dechex($this->BLUE),  2, "0", STR_PAD_LEFT), 
                            $this->FORM_NAME);
        
        $ret = new Div();
        $ret->setOverflow("visible");
        $ret->add(new Hiddenfield("switchEditorConfigZuordId", $this->getEditorConfigZuordnungsId()));
        $ret->add($cs);
        return $ret; 
    }
    
    
    function doUpdate(){
        if (isset($_REQUEST['switchEditorConfigZuordId']) && $_REQUEST['switchEditorConfigZuordId']==$this->getEditorConfigZuordnungsId()){
            $r = false;
            $g = false;
            $b = false;
            if(isset($_REQUEST[$this->PARAM_URL_NAME]) && strlen($_REQUEST[$this->PARAM_URL_NAME])>0){
                //echo $_REQUEST[$this->PARAM_URL_NAME]."<br/>";
                foreach($this->PARAMS as $param){
                    if($param->getName()==$this->PARAM_NAME_RED){
                        $_REQUEST[$param->getName()] = hexdec(substr($_REQUEST[$this->PARAM_URL_NAME],1,2));
                        //echo $param->getName()."=".$_REQUEST[$param->getName()]."<br/>";
                        $r = true; 
                    }
                    if($param->getName()==$this->PARAM_NAME_GREEN){
                        $_REQUEST[$param->getName()] = hexdec(substr($_REQUEST[$this->PARAM_URL_NAME],3,2));
                        //echo $param->getName()."=".$_REQUEST[$param->getName()]."<br/>";
                        $g = true; 
                    }
                    if($param->getName()==$this->PARAM_NAME_BLUE){
                        $_REQUEST[$param->getName()] = hexdec(substr($_REQUEST[$this->PARAM_URL_NAME],5,2));
                        //echo $param->getName()."=".$_REQUEST[$param->getName()]."<br/>";
                        $b = true; 
                    }
                }
            }
            if($r && $g && $b){
                $_REQUEST['switchConfigId'] = $this->getControl()->getId();
            }
        }
    }
}
 
?>