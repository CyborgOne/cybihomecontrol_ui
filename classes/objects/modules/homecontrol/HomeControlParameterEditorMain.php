<?PHP

/**
 * Basis-Klasse für Parameter-Editor Objekte
 * Diese klasse dient als Grundlage zum ableiten alternativer Editoren für Parameter
 * (Beispiel: RGB-Controller -> Color-Chooser anstelle von 3 Textfeldern) 
 */
class HomeControlParameterEditorMain extends Object {
    private $PARAMETER_ARRAY = array();
    var $HC_ITEM;
    var $SENDER;
    var $SENDERTYP;
    
    private $ID;
    private $CLASSNAME;
    private $DESCR;
    private $PIC;
    
    private $FORM_NAME;
       
    private $EDITOR_CONFIG_ZUORDNUNG_ID;
    
    function HomeControlParameterEditorMain($editorRow, $controlItem, $formName){
        /* Den Inhalt des Konstruktors in abgeleiteten Konstruktoren einfügen */
        $this->setControl($controlItem);
        $this->SENDER = $this->getControl()->getSender();
        $this->SENDERTYP = $this->SENDER->getTyp();
        
        $this->FORM_NAME = $formName;
        
        $this->ID        = $editorRow->getNamedAttribute("id");
        $this->CLASSNAME = $editorRow->getNamedAttribute("classname");
        $this->DESCR     = $editorRow->getNamedAttribute("descr");
        $this->PIC       = $editorRow->getNamedAttribute("pic");
        
        $this->refreshEditorConfigZuordnungsId();
    } 
    
    /**
    * @override
    */
    function isActivated(){
    }
    
    /**
    * Methode zum überschreiben der Logik,
    * mit der die Editor-Maske generiert wird.
    *  
    * @override
    */
    function getEditMask(){
        $dv = new Div();
        return $dv;
    }
    
    /**
    * Methode zum überschreiben der Logik, 
    * mit der die Sender-Parameterwerte
    * anhand der Editorwerte gesetzt werden.
    * 
    * @override
    */
    function doUpdate(){
    }
    
    
    function refreshEditorConfigZuordnungsId(){
        $sql = "SELECT id FROM homecontrol_control_editor_zuordnung WHERE config_id=".$this->getControl()->getId()." AND editor_id=".$this->ID;
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        $r = mysql_fetch_array($rslt);
        
        $this->setEditorConfigZuordnungsId($r['id']);
    }
    
    function getEditorConfigZuordnungsId(){
        return $this->EDITOR_CONFIG_ZUORDNUNG_ID;
    }
    
    function setEditorConfigZuordnungsId($id){
        $this->EDITOR_CONFIG_ZUORDNUNG_ID = $id;
    }
    
    function getClassname(){
        return $this->CLASSNAME;
    }
    
    function setClassname($c){
        $this->CLASSNAME = $c;
    }
    
    function setPic($p){
        $this->PIC = $p;
    }
    
    function getPic(){
        return $this->PIC;
    }
    
    function getId(){
        return $this->ID;
    }
    
    function setId($p){
        $this->ID = $p;
    }
    
    function getDescr(){
        return $this->DESCR;
    }
    
    function setDescr($d){
        $this->DESCR = $d;
    }
      
    function getSender(){
        return $this->SENDER;
    }
    
    function getControl(){
        return $this->HC_ITEM;
    }
    
    function setControl($controlItem){
        $this->HC_ITEM = $controlItem;
    }
    
    function getSenderTyp(){
        return $this->SENDERTYP;
    }
    
    function getSenderParameterName($editorParamName){
        $sql =  "SELECT name FROM homecontrol_sender_typen_parameter WHERE id = ("
               ." SELECT sender_param_id FROM homecontrol_control_parameter_zu_editor WHERE sendereditor_zuord_id = (SELECT id
                    FROM homecontrol_control_editor_zuordnung 
                   WHERE  config_id = " .$this->getControl()->getId()
               ."    AND  editor_id = " .$this->getId() 
               .")   AND editor_param_id=(SELECT id FROM homecontrol_editor_parameter WHERE name = '" .$editorParamName ."')"
               .")";
         
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        if(mysql_numrows($rslt)>0){
            $r = mysql_fetch_array($rslt);
            
            return isset($r['name'])?$r['name']:null;
        }
        return null;
    }
    
    function show(){
        $msk = $this->getEditMask();
        $msk->show();
    }
}

 
 ?>