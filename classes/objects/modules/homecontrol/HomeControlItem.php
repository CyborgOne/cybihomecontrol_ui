<?PHP

class HomeControlItem extends Object {
    private static $FIX_PARAMS_ARRAY_INDEX = 0;
    private static $CONTROL_PARAMS_ARRAY_INDEX = 1;

    private $ID = 0;
    private $X = 0;
    private $Y = 0;
    private $OBJNAME = "";
    private $BESCHREIBUNG = "";
    private $ART = 0;
    private $ETAGE = 0;
    private $ZIMMER = 0;
    private $PIC = "";
    private $FUNKID2_NEED = false;
    private $DIMMER = "N";
    private $SENDER;

    private $EDIT_MODE = false;

    private $CONTROL_IMAGE_WIDTH = 40;
    private $CONTROL_IMAGE_HEIGHT = 40;

    private $PARAMETER = array();    
    private $ALL_PARAMETERS = array();    

    private $FREE_PARAMS_ARRAY=null;

    public $CONFIG_ROW;

    function HomeControlItem($currConfigRow, $editModus=false) {
        $this->ID = $currConfigRow->getNamedAttribute("id");
        $this->X = $currConfigRow->getNamedAttribute("x");
        $this->Y = $currConfigRow->getNamedAttribute("y");
        $this->OBJNAME = $currConfigRow->getNamedAttribute("name");
        $this->BESCHREIBUNG = $currConfigRow->getNamedAttribute("beschreibung");
        $this->ART = $currConfigRow->getNamedAttribute("control_art");
        $this->ETAGE = $currConfigRow->getNamedAttribute("etage");
        $this->ZIMMER = $currConfigRow->getNamedAttribute("zimmer");
        $this->DIMMER = $currConfigRow->getNamedAttribute("dimmer");
        $this->PIC = $this->getIconPath();
        if (strlen($this->getIconPath()) <= 4){
            $this->PIC = "pics/homecontrol/steckdose_100.jpg";
        }

        $this->EDIT_MODE = $editModus;

        $senderDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender", array('*'), "", "", "", "id=".$currConfigRow->getNamedAttribute("sender_id"));
        $this->SENDER = new HomeControlSender($senderDbTbl->getRow(1));

        $this->CONFIG_ROW = $currConfigRow;
        
        $this->loadParams();
    }
    
    function getId(){
        return $this->ID;
    }
    
    function loadParams(){
        $this->PARAMETER[self::$FIX_PARAMS_ARRAY_INDEX]=array();
        $this->PARAMETER[self::$CONTROL_PARAMS_ARRAY_INDEX]=array();
        $this->ALL_PARAMETERS=array();
        
        foreach($this->SENDER->getTyp()->getParameterFixDbTbl()->ROWS as $fixRow){
            $p = new HomeControlSenderParameter($fixRow, $this);
            $this->PARAMETER[self::$FIX_PARAMS_ARRAY_INDEX][count($this->PARAMETER[self::$FIX_PARAMS_ARRAY_INDEX])] = $p;
            $this->ALL_PARAMETERS[count($this->ALL_PARAMETERS)] = $p;
        }
        
        foreach($this->SENDER->getTyp()->getParameterControlDbTbl()->ROWS as $controlRow){
            $p = new HomeControlSenderParameter($controlRow, $this);
            $this->PARAMETER[self::$CONTROL_PARAMS_ARRAY_INDEX][count($this->PARAMETER[self::$CONTROL_PARAMS_ARRAY_INDEX])] = $p;
            $this->ALL_PARAMETERS[count($this->ALL_PARAMETERS)] = $p;
        }
    }
    
    
    
    /**
     * liefert ein Array, welches alle Parameter 
     * die als fix markiert sind.
     * (fest dem Objekt zugeordnet und nur in Einstellungen einstellbar)
     */
    function getFixParameter(){
        return $this->PARAMETER[self::$FIX_PARAMS_ARRAY_INDEX];
    }
    
    /**
     * liefert ein Array, welches alle Parameter 
     * die nicht als fix markiert sind.
     * (Parameter die zur Steuerung verwendet werden)
     */
    function getControlParameter(){
        return $this->PARAMETER[self::$CONTROL_PARAMS_ARRAY_INDEX];
    }
    
    function getAllParameter(){
        return $this->ALL_PARAMETERS;
    }
    
    function getArt(){
        return $this->ART;
    }
    
    function getName(){
        return $this->OBJNAME;
    }
    

    function getRow(){
        return $this->CONFIG_ROW;
    }

    function isDimmable(){
        return $this->DIMMER=="J";
    }
 
    function getSender(){
        return $this->SENDER;
    }
 
 
    function hasFreeParam(){
        return $this->getFreeParamCount() > 0;
    }
    
    function getFreeParamCount(){
        return count($this->getFreeParamArray());
    }
    
    function refreshFreeParamArray(){
        $this->FREE_PARAMS_ARRAY=null;
    }



    function isParameterOptionalActive($paramId) {
        $sql = "SELECT 'X' FROM homecontrol_sender_typen_parameter_optional p "
              ."WHERE param_id = " .$paramId ." AND config_id=".$this->ID ." AND active='J' ";
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        
        return mysql_numrows($rslt)>0;
    }


    
    function getFreeParamArray(){
        if($this->FREE_PARAMS_ARRAY==null){
            $sql = "SELECT id, name FROM homecontrol_sender_typen_parameter p WHERE senderTypId=(SELECT senderTypId FROM homecontrol_sender s WHERE id = " .$this->SENDER->getId() .") AND NOT EXISTS ( SELECT 'X' FROM homecontrol_control_parameter_zu_editor z WHERE z.sender_param_id = p.id AND z.sendereditor_zuord_id IN (SELECT id FROM homecontrol_control_editor_zuordnung WHERE config_id= ".$this->ID."))";
            $this->FREE_PARAMS_ARRAY = getComboArrayBySql($sql);
        }
        return $this->FREE_PARAMS_ARRAY;
    }

    function getInsertEditorMask(){
        $sql = "SELECT id, name FROM homecontrol_editoren e WHERE (SELECT count('X') FROM homecontrol_editor_parameter p WHERE p.editor_id = e.id) <= ".$this->getFreeParamCount();
        $cobEditoren = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sql, "editorChoosenToAdd");
        $btnChooseEditorToAdd = new Button("chooseEditorToAdd", "Hinzuf&uuml;gen");
        
        $editorAddPanel = new Form();
        $editorAddPanel->add($cobEditoren);
        $editorAddPanel->add($btnChooseEditorToAdd);
        $editorAddPanel->add(new Hiddenfield("editControl", $_REQUEST["editControl"]));
    
        return $editorAddPanel;
    }
    
    function handleInsertEditorMask(){
        if(isset($_REQUEST["editControl"]) && isset($_REQUEST["chooseEditorToAdd"]) && isset($_REQUEST["editorChoosenToAdd"]) && strlen($_REQUEST["editorChoosenToAdd"])>0 && $_REQUEST["editControl"]==$this->ID ){
            $sql = "INSERT INTO homecontrol_control_editor_zuordnung (config_id, editor_id) VALUES (" .$this->ID  .", " .$_REQUEST["editorChoosenToAdd"] .")";
            $_SESSION['config']->DBCONNECT->executeQuery($sql);
            
            $newId = getDbValue("homecontrol_control_editor_zuordnung", "max(id)");
            
            $sql = "INSERT INTO homecontrol_control_parameter_zu_editor (editor_param_id, sender_param_id, sendereditor_zuord_id) "
                  ."SELECT id, null"  .", " .$newId ." FROM homecontrol_editor_parameter WHERE editor_id=".$_REQUEST["editorChoosenToAdd"] ."";
            $_SESSION['config']->DBCONNECT->executeQuery($sql);
            
            $this->refreshFreeParamArray();
        }
    }
    
    /**
     * 
     */
    function getEditorParamAssignMask(){
        $ttl = new Title("Editoren zuordnen");
        
        $senderDbTbl = new DbTable( $_SESSION['config']->DBCONNECT, 
                                    "homecontrol_control_parameter_zu_editor", 
                                    array("editor_param_id", "sender_param_id", "sendereditor_zuord_id"), 
                                    "Editor-Parameter, Sender-Parameter, Editor", 
                                    "", 
                                    "", 
                                    "WHERE sendereditor_zuord_id IN (SELECT id FROM homecontrol_control_editor_zuordnung WHERE config_id=".$this->ID.")");
        
        $senderDbTbl->setNoUpdateCols(array("sendereditor_zuord_id", "editor_param_id"));
        $senderDbTbl->setNoInsertCols(array("sendereditor_zuord_id"));
        $senderDbTbl->setInvisibleCols(array("sendereditor_zuord_id"));
                
        if (isset($_REQUEST['DbTableUpdate' . $senderDbTbl->TABLENAME]) 
            && $_REQUEST['DbTableUpdate' .$senderDbTbl->TABLENAME] == "Speichern" ) {
            $senderDbTbl->doUpdate();
        }         
        
        $tbl = new Table(array("",""));

        $rTtl = $tbl->createRow();
        $rTtl->setSpawnAll(true);
        $rTtl->setAttribute(0, $ttl);
        $tbl->addRow($rTtl);
        
        $this->handleInsertEditorMask();
        $senderDbTbl->refresh();
        
        if($this->hasFreeParam()){
            $newEditor = $this->getInsertEditorMask();
        
            $rTtl = $tbl->createRow();
            $rTtl->setSpawnAll(true);
            $rTtl->setAttribute(0, $newEditor);
            $tbl->addRow($rTtl);
        }
        
        $tbl->addSpacer(0,10);

        $updateParamZuordMask = $senderDbTbl->getUpdateAllMask();
        $updateParamZuordMask->add(new Hiddenfield("editControl", $_REQUEST["editControl"]));

        // ----------------------------
                
        
        $frm = new Form();
        
        $frm->add($tbl);
        $frm->add($updateParamZuordMask);
        
        return $frm;
    }
 
 
    function getIconTooltip($configButtons = true) {
        $ttt = "<table cellspacing='10'><tr><td>" .$this->getControlArtIconSrc(false,80) ."</td><td><center><b>" . $this->OBJNAME .
            "</b></center><hr></td></tr>";
       
        $ttt .= "<tr><td colspan=2 height='1px'> </td></tr>";

        return $ttt;
    }


    function getSwitchButtons() {
        $tbl = new Table(array("","",""));
        $tbl->setStyle("position", "relative");
        $tbl->setStyle("left", "-17px");
        $tbl->setStyle("top", "-20px");
        $tbl->setAlignments(array("left", "right"));
        $tbl->setColSizes(array(40, 5, 40));
        $tbl->setBorder(0);

        $senderParams = $this->SENDER->getSenderParameterControlMask($this);
        
        if($senderParams!=null){
            $rS = $tbl->createRow();
            $rS->setSpawnAll(true);
            $rS->setAttribute(0, $senderParams);
            $tbl->addRow($rS);
        }
        
        return $tbl;
    }
   
    function getMobileSwitch() {
        $tbl = new Table(array("", "", "", ""));
        $tbl->setAlignments(array("center", "left", "left", "right"));
        $tbl->setColSizes(array(60, "", 160, 150));
        $tbl->setBorder(0);
        $rowTtl = $tbl->createRow();
        $rowTtl->setVAlign("middle");

        $txtAn = new Text($this->getDefaultLogicAnText(), 7, true);
        $txtAus = new Text($this->getDefaultLogicAusText(), 7, true);

        $divAn = new Div();
        $divAn->add($txtAn);
        $divAn->setWidth(150);
        $divAn->setHeight(50);
        $divAn->setAlign("center");
        $divAn->setVAlign("middle");
        $divAn->setStyle("line-height", "50px");
        $divAn->setBorder(1);
        $divAn->setBackgroundColor("green");
        $divAn->setOverflow("hidden");

        $divAus = new Div();
        $divAus->setWidth(150);
        $divAus->setHeight(50);
        $divAus->setAlign("center");
        $divAus->setVAlign("middle");
        $divAus->setStyle("line-height", "50px");
        $divAus->add($txtAus);
        $divAus->setBorder(1);
        $divAus->setBackgroundColor("red");
        $divAus->setOverflow("hidden");


        $txtName = new Text($this->OBJNAME, 6, true);

        $img = $this->getControlArtIcon(false);

// TODO:
        $lnkAn = new Link("?switchShortcut=0-on", $divAn, false, "arduinoSwitch");
        $lnkAus = new Link("?switchShortcut=0-off", $divAus, false, "arduinoSwitch");
        
        $rowTtl->setAttribute(0, $img);
        $rowTtl->setAttribute(1, $txtName);
        $rowTtl->setAttribute(2, $lnkAn);
        $rowTtl->setAttribute(3, $lnkAus);
        
        $tbl->addRow($rowTtl);

        return $tbl;
    }

  
    function getDefaultLogicAnText(){
        $txtAn = "AN";
        switch ($this->ART) {
            case 1: // Steckdosen
            case 3: // Glühbirne
                $txtAn = "AN";
                break;
            case 2: // Jalousien
                $txtAn = "AUF";
                break;
            case 4: // Heizung
                $txtAn = "WARM";
                break;
            default:
                $txtAn = "AN";
        }
        return $txtAn;
    }

    
    function getDefaultLogicAusText(){
        $txtAus = "AUS";
        switch ($this->ART) {
            case 1: // Steckdosen
            case 3: // Glühbirne
                $txtAus = "AUS";
                break;
            case 2: // Jalousien
                $txtAus = "ZU";
                break;
            case 4: // Heizung
                $txtAus = "KALT";
                break;
            default:
                $txtAus = "AUS";
        }
        return $txtAus;
    }


    function getIconPath() {
        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_art', array
            ("pic"), "", "", "", "id=" . $this->ART);
        $row = $dbTable->getRow(1);

        return $row->getNamedAttribute("pic");
    }
    
    function getPic(){
        // $_SESSION['config']->getImageFromCache($this->PIC)
        return $this->PIC;
    }

    function isFunkId2Needed() {
        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_art', array
            ("zweite_funkid_jn"), "", "", "", "id=" . $this->ART);
        $row = $dbTable->getRow(1);

        return $row->getNamedAttribute("zweite_funkid_jn");
    }


    /**
     *  Liefert das Grafik-Symbol zurück (Image),
     *  welches zur Control-Art passt.
     */
    function getControlArtIcon($tooltipActive = true) {
        $lnkImg = new Image($this->PIC);
        $lnkImg->setWidth($this->CONTROL_IMAGE_WIDTH);

        if ($tooltipActive) {
            $ttt = $this->getIconTooltip();
            $lnkImg->setToolTip($ttt);
        }

        $lnkImg->setGenerated(false);
        return $lnkImg;
    }

    /**
     *  Liefert das Grafik-Symbol zurück (Image),
     *  welches zur Control-Art passt.
     */
    function getControlArtIconSrc($tooltipActive = true,$width=0) {
        $lnkImg = new Image($this->PIC);
        $lnkImg->setWidth($width==0?$this->CONTROL_IMAGE_WIDTH:$width);

        if ($tooltipActive) {
            $ttt = $this->getIconTooltip();
            $lnkImg->setToolTip($ttt);
        }
        $lnkImgSrc = $lnkImg->getImgSrc($this->PIC);

        return $lnkImgSrc;
    }
    
    
    function getParameterValue($paramRow){
        return $this->getSender()->getTyp()->getParameterValue($paramRow, $this->getRow());
    }
    
    function getParameterValueForCron($paramRow, $cronId){
        return $this->getSender()->getTyp()->getParameterValueForCron($paramRow, $this->getRow(), $cronId);
    }
        
    function getParameterValueForShortcut($paramRow, $shortcutId){
        return $this->getSender()->getTyp()->getParameterValueForShortcut($paramRow, $this->getRow(), $shortcutId);
    }
    
    function setParameterValue($paramRow, $configRow, $value){
        $this->getSender()->getTyp()->setParameterValue($paramRow, $this->getRow(), $value);
    }
    
    function setParameterValueForCron($paramRow, $configRow, $cronId, $value){
        $this->getSender()->getTyp()->setParameterValueForCron($paramRow, $this->getRow(), $cronId, $value);
    }

    function setParameterValueForShortcut($paramRow, $configRow, $shortcutId, $value){
        $this->getSender()->getTyp()->setParameterValueForShortcut($paramRow, $this->getRow(), $shortcutId, $value);
    }

    function show() {
        if ($this->EDIT_MODE) {
            echo "<a href=\"?editControl=" . $this->ID . "\" style=\"position:absolute; left:" .
                $this->X . "px; top:" . ($this->Y + $_SESSION['additionalLayoutHeight']) .
                "px; width:" . $this->CONTROL_IMAGE_WIDTH . "px; height:" . $this->
                CONTROL_IMAGE_HEIGHT . "px;\">";
            echo $this->getControlArtIconSrc();
            echo "</a>";

        } else {
            echo "<div style=\"position:absolute; left:" . $this->X . "px; top:" . ($this->
                Y + $_SESSION['additionalLayoutHeight']) . "px; width:" . $this->
                CONTROL_IMAGE_WIDTH . "px; height:" . $this->CONTROL_IMAGE_HEIGHT . "px;\">";
            echo $this->getControlArtIconSrc();
            
            if($_SESSION['config']->PUBLICVARS['switchButtonsOnIconActive']=="J"){
                $this->getSwitchButtons()->show(); 
            }
            echo "</div>";
            
        }
    }

    

}

?>