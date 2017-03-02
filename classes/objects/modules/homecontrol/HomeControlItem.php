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
        $this->SENDER = $_SESSION['config']->getSenderById($currConfigRow->getNamedAttribute("sender_id"));
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


    /**
     * Die Methode prüft, ob der Optionale Parameter zur übergebenen 
     * Parameter-ID als aktiv markiert ist. 
     * 
     * Wenn ja gibt die Methode true zurück. Ansonsten false. 
     * Bei false wird der Parameter für das Objekt nicht weiter berücksichtigt.   
     */
    function isParameterOptionalActive($paramId) {
        $sql = "SELECT 'X' FROM homecontrol_sender_typen_parameter_optional p "
              ."WHERE param_id = " .$paramId ." AND config_id=".$this->ID ." AND active='J' ";
        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        
        return mysql_numrows($rslt)>0;
    }

    /**
     * Liefert ein Array mit den Sender-Parametern, 
     * die bisher noch keinem alternativen Editor zugeordnet sind. 
     * Dies ist primär für die Parameterzuordnung in der 
     * Editor-Konfiguration zu den Geräten gedacht. 
     */    
    function getFreeParamArray(){
        if($this->FREE_PARAMS_ARRAY==null){
            //$sql = "SELECT id, name FROM homecontrol_sender_typen_parameter p WHERE senderTypId=(SELECT senderTypId FROM homecontrol_sender s WHERE id = " .$this->SENDER->getId() .") AND NOT EXISTS ( SELECT 'X' FROM homecontrol_control_parameter_zu_editor z WHERE z.sender_param_id = p.id AND z.sendereditor_zuord_id IN (SELECT id FROM homecontrol_control_editor_zuordnung WHERE config_id= ".$this->ID."))";
            //if($this->SENDER->getId()==2) {echo $sql;}
            //$this->FREE_PARAMS_ARRAY = getComboArrayBySql($sql);            
            
            $dbTblFree =  new DbTable($_SESSION['config']->DBCONNECT,
                                    "homecontrol_sender_typen_parameter",
                                    array("*"),
                                    "",
                                    "",
                                    "",
                                    "senderTypId=(SELECT senderTypId FROM homecontrol_sender s WHERE id=" .$this->SENDER->getId() .") AND NOT EXISTS (SELECT 'X' FROM homecontrol_control_parameter_zu_editor z WHERE z.sender_param_id = homecontrol_sender_typen_parameter.id AND z.sendereditor_zuord_id IN (SELECT id FROM homecontrol_control_editor_zuordnung WHERE config_id=".$this->ID."))");

            $this->FREE_PARAMS_ARRAY = array();
            foreach($dbTblFree->ROWS as $freeRow){
                $this->FREE_PARAMS_ARRAY[count($this->FREE_PARAMS_ARRAY)] = new HomeControlSenderParameter($freeRow, $this);
            }

        }
        return $this->FREE_PARAMS_ARRAY;
    }

    function hasUnselectedEditorParam(){
        $dbTblFree =  new DbTable($_SESSION['config']->DBCONNECT,
                                "homecontrol_control_parameter_zu_editor",
                                array("*"),
                                "",
                                "",
                                "",
                                "sendereditor_zuord_id IN ( SELECT id FROM homecontrol_control_editor_zuordnung WHERE config_id = " .$this->getId() ." ) AND sender_param_id IS NULL");

        return $dbTblFree->getRowCount() > 0;
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
        $tbl = new Table(array("",""));

        $ttl = new Title("Editoren zuordnen");
    
        $rTtl = $tbl->createRow();
        $rTtl->setSpawnAll(true);
        $rTtl->setAttribute(0, $ttl);
        $tbl->addRow($rTtl);
        $tbl->addSpacer(0,15);
        
        $this->handleInsertEditorMask();

        $editZuordDbTbl = new DbTable( $_SESSION['config']->DBCONNECT, 
                            "homecontrol_control_editor_zuordnung", 
                            array("id", "config_id", "editor_id"), 
                            "ID, Item, Editor", 
                            "", 
                            "", 
                            "config_id=".$this->ID);

        foreach($editZuordDbTbl->ROWS as $zuordRow){
            $editorZuordId = $zuordRow->getNamedAttribute("id");
            if(isset($_REQUEST["delZuordEdit".$editorZuordId]) && $_REQUEST["delZuordEdit".$editorZuordId]=="Editor Entfernen"){
                $sqlRemoveTerms = "DELETE FROM homecontrol_control_parameter_zu_editor WHERE sendereditor_zuord_id = " .$editorZuordId;
                $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);

                $zuordRow->deleteFromDb();
            } else {
                $senderDbTbl = new DbTable( $_SESSION['config']->DBCONNECT, 
                                            "homecontrol_control_parameter_zu_editor", 
                                            array("editor_param_id", "sender_param_id", "sendereditor_zuord_id"), 
                                            "Editor-Parameter, Sender-Parameter, Editor", 
                                            "", 
                                            "", 
                                            "WHERE sendereditor_zuord_id = ".$editorZuordId);
                
                $senderDbTbl->setNoUpdateCols(array("sendereditor_zuord_id", "editor_param_id"));
                $senderDbTbl->setNoInsertCols(array("sendereditor_zuord_id"));
                $senderDbTbl->setInvisibleCols(array("sendereditor_zuord_id"));
                        
                if (isset($_REQUEST['DbTableUpdate' . $senderDbTbl->TABLENAME]) 
                    && $_REQUEST['DbTableUpdate' .$senderDbTbl->TABLENAME] == "Speichern" ) {
                    $senderDbTbl->doUpdate();
                }         
                
                $senderDbTbl->refresh();
    
                $updMask = $senderDbTbl->getUpdateAllMask();
                $updMask->add(new Hiddenfield("editControl", $_REQUEST["editControl"]));
                
                $rUpdMskTtl = $tbl->createRow();
                $rUpdMskTtl->setAttribute(0, new Title(getEditorName($zuordRow->getNamedAttribute("editor_id"))));
                $rUpdMskTtl->setAttribute(1, new Button("delZuordEdit".$editorZuordId, "Editor Entfernen"));
                $tbl->addRow($rUpdMskTtl);
                
                $rUpdMsk = $tbl->createRow();
                $rUpdMsk->setSpawnAll(true);
                $rUpdMsk->setAttribute(0, $updMask);
                $tbl->addRow($rUpdMsk);
                
                $tbl->addSpacer(0,10);
            }
        }

        // ----------------------------

        if ($this->hasUnselectedEditorParam()){
            $rTtl = $tbl->createRow();
            $rTtl->setSpawnAll(true);
            $rTtl->setAttribute(0, new Text("Erst alle Parameter zuordnen, bevor ein neuer Editor zugeordnet werden kann."));
            $tbl->addRow($rTtl);            
        } else if($this->hasFreeParam()){
            $newEditor = $this->getInsertEditorMask();
        
            $rTtl = $tbl->createRow();
            $rTtl->setSpawnAll(true);
            $rTtl->setAttribute(0, $newEditor);
            $tbl->addRow($rTtl);
        } 
                
        $frm = new Form();
        $frm->add($tbl);
        
        
        return $frm;
    }
 
 
 
 
    function getIconTooltip($configButtons = true) {
        $ttt = "<table cellspacing='10'><tr><td>" .$this->getControlArtIconSrc(false,80) ."</td><td><center><b>" . $this->OBJNAME .
            "</b></center><hr></td></tr>";
       
        $ttt .= "<tr><td colspan=2 height='1px'> </td></tr>";

        return $ttt;
    }


    function getSwitchButtons() {
        $tbl = new Table(array(""));
        $tbl->setAlignments(array("center"));
        $tbl->setBorder(0);

        $senderParams = $this->SENDER->getSenderParameterControlMask($this);
        if($senderParams!=null){
            $rS = $tbl->createRow();
            $rS->setAttribute(0, $senderParams);
            $tbl->addRow($rS);
        }
        
        return $tbl;
    }
   
   
    function getMobileSwitch() {
        $tbl = new Table(array("", "", ""));
        $tbl->setAlignments(array("center", "left", "right"));
        $tbl->setColSizes(array(60, null, 250));
        $tbl->setBorder(0);

        $img = $this->getControlArtIcon(false);

        $txtName = new Text($this->OBJNAME, 6, true);

        $switchForm = $this->getSwitchButtons();

        $rowTtl = $tbl->createRow();
        $rowTtl->setAttribute(0, $img);
        $rowTtl->setAttribute(1, $txtName);
        $rowTtl->setAttribute(2, $switchForm);
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
    
    function setParameterValue($paramRow, $value){
        $this->getSender()->getTyp()->setParameterValue($paramRow, $this->getRow(), $value);
    }
    
    function setParameterValueForCron($paramRow, $cronId, $value){
        $this->getSender()->getTyp()->setParameterValueForCron($paramRow, $this->getRow(), $cronId, $value);
    }

    function setParameterValueForShortcut($paramRow, $shortcutId, $value){
        $this->getSender()->getTyp()->setParameterValueForShortcut($paramRow, $this->getRow(), $shortcutId, $value);
    }
    
    function isEditorActivated($editorRow){
        if($editorRow!=null && $editorRow->getNamedAttribute("id")!=null){
            $sql = "SELECT * FROM homecontrol_control_editor_zuordnung WHERE config_id=".$this->getId()." AND editor_id=".$editorRow->getNamedAttribute("id");
            $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
            if($rslt && mysql_numrows($rslt)>0){
                return true;
            }            
        }
        
        return false;
    }
    

    /**
     * Schaltet das Objekt anhand der übergebenen Parameter.
     * 
     * Als Parameter wird ein Array übergeben zu jedem Parameter 
     * die Parameter-Row und dem Sender zu übermittelnden Wert enthält.
     * 
     * @param  $paramsRowValArray      
     *         Aufbau:  
     *          ARRAY[PARAM_ID][0] = PARAMETER_ROW
     *          ARRAY[PARAM_ID][1] = PARAMETER_NEW_VALUE
     */
    function switchDevice($paramsRowValArray){
        $command = $this->getSwitchCommand($paramsRowValArray, true);
        
        if($command != ""){
            try {
                exec("wget '".$command."' > /dev/null 2>/dev/null &");
            } catch (exception $e) {
                echo "FEHLER BEIM SCHALTEN!";
            }
            
            // Logging
            try {
                $myfile = fopen("/var/www/switch.log", "a+");
                fwrite($myfile, "(" . date("d.M.Y - H:i:s") . "): " . $command . "\n");
                fclose($myfile);
            }
            catch (exception $e) {
            }
        } else {
            echo "Es wurden nicht alle notwendigen Parameter angegeben!";
        }
    }
    
    
    /**
     * Die Methode liefert ein Parameter-Array zurück,
     * welches zum schalten des Objekts benötigt wird.
     * 
     * Die Methode geht alle Parameter des Objekts durch, 
     * prüft ob ein passender $_REQUEST-Parameter existiert
     * und trägt falls vorhanden den Wert in das Parameter-Array ein.
     * 
     * Dieses Array enthält nur Werte für Parameter, bei denen auch der 
     * $_REQUEST-Parameter gesetzt ist. Die Prüfung, ob alle notwendigen
     * Werte gesetzt sind, erfolgt beim schalten mit switchDevice($paramArray).
     */
    function checkUrlParams(){
        $allParams = array();
        $optionalParams = array();
        $fixParams = array();
        $defaultLogicParams = array();
        $switchParams = array();

        $paramTable = new DbTable($_SESSION['config']->DBCONNECT, 
                                  "homecontrol_sender_typen_parameter", array('*'), "", "", "",
                                  "senderTypId=(SELECT s.senderTypId FROM homecontrol_sender s, homecontrol_config c WHERE s.id = c.sender_id AND c.id = " . $_REQUEST['switchConfigId'] .
                                  ")" 
                                 );
                                 
        $lastSenderTyp = "";
        $switchUrl = "";
        $switchParamArray = array();
        $allParamsSet = true;
        foreach ($paramTable->ROWS as $row) {
            $optional = false;
            $fix = false;
            $default_logic = false;
            $mandatory = false;

            if ($row->getNamedAttribute("optional") == "J") {
                $optionalParams[count($optionalParams)] = $row->getNamedAttribute('name');
                $optional = true;
            }
            if ($row->getNamedAttribute("mandatory") == "J") {
                $mandatory = true;
            }
            if ($row->getNamedAttribute("fix") == "J") {
                $fixParams[count($fixParams)] = $row->getNamedAttribute('name');
                $fix = true;
            }
            if ($row->getNamedAttribute("default_logic") == "J") {
                $defaultLogicParams[count($defaultLogicParams)] = $row->getNamedAttribute('name');
                $default_logic = true;
            }

            $value = "";
            if (isset($_REQUEST[$row->getNamedAttribute('name')]) && strlen($_REQUEST[$row->getNamedAttribute('name')]) > 0) {
                $value = $default_logic ? $_REQUEST[$row->getNamedAttribute('name') . $_REQUEST[$row->getNamedAttribute('name')]] : $_REQUEST[$row->getNamedAttribute('name')];
            }

            $switchParamArray[$row->getNamedAttribute("id")][0] = $row;
            $switchParamArray[$row->getNamedAttribute("id")][1] = $value;
        }
        
        return $switchParamArray;            
    }
    
    
    /**
     * Die Methode liefert für Objekte mit einem "Default-Logic" Parameter
     * das Parameter-Array fürs ein- oder ausschalten je nach gesetztem
     * $switchOn Parameter. 
     * Hat das Objekt keinen Parameter mit "Default-Logic", so gibt die 
     * Methode null zurück.
     */
    function getDefaultSwitchParams($switchOn=true){
        $allParams = array();
        $optionalParams = array();
        $fixParams = array();
        $defaultLogicParams = array();
        $switchParams = array();

        $paramTable = new DbTable($_SESSION['config']->DBCONNECT, 
                                  "homecontrol_sender_typen_parameter", array('*'), "", "", "",
                                  "senderTypId=(SELECT s.senderTypId FROM homecontrol_sender s, homecontrol_config c WHERE s.id = c.sender_id AND c.id = " . $this->getId() .
                                  ")" 
                                 );
        $lastSenderTyp = "";
        $switchUrl = "";
        $switchParamArray = array();
        $allParamsSet = true;
        $hasDefaultLogicParam = false;
        foreach ($paramTable->ROWS as $row) {
            $optional = false;
            $fix = false;
            $default_logic = false;
            $mandatory = false;

            if ($row->getNamedAttribute("optional") == "J") {
                $optionalParams[count($optionalParams)] = $row->getNamedAttribute('name');
                $optional = true;
            }
            if ($row->getNamedAttribute("mandatory") == "J") {
                $mandatory = true;
            }
            if ($row->getNamedAttribute("fix") == "J") {
                $fixParams[count($fixParams)] = $row->getNamedAttribute('name');
                $fix = true;
            }
            if ($row->getNamedAttribute("default_logic") == "J") {
                $defaultLogicParams[count($defaultLogicParams)] = $row->getNamedAttribute('name');
                $default_logic = true;
                $hasDefaultLogicParam = true;
            }

            $value = $this->getParameterValue($row);
            if($default_logic && !$switchOn){
                $value = "-".$value;
            }
            
            $lfdnr = count($switchParamArray);
            $switchParamArray[$row->getNamedAttribute("id")][0] = $row;
            $switchParamArray[$row->getNamedAttribute("id")][1] = $value;
        }
        
        return $hasDefaultLogicParam?$switchParamArray:null;
    }

    /**
     * Die Methode liefert den String für das Objekt, 
     * der in der Datenbank für die HA-Bridge-Devices eingetragen werden muss
     * um eine Schaltung per Sprachsteuerung zu ermöglichen. 
     * 
     * Diese Logik funktioniert nur bei Geräten mit mindestens einem Parameter.
     * der "Default_Logik" aktiviert hat. 
     * Sonst liefert die Methode einen Leer-String zurück.
     */
    function getHaBridgeDbString($id, $mainUid="00:17:88:5E:D3" ){
        $uidTmp = $id;
        $uid1=0;
        $uid2=0;
        
        while($uidTmp>99){
            $uid1++;
        }
        $uid2 = $uidTmp;
        
        $uid = str_pad($id, 2, "0", STR_PAD_LEFT);
        $switchOnUrl = $this->getSwitchCommand($this->getDefaultSwitchParams(true));
        $switchOffUrl = $this->getSwitchCommand($this->getDefaultSwitchParams(false));
        
        $ret = "{"
              ."\"id\":\"" .$id ."\","
              ."\"uniqueid\":\"" .$mainUid .":" .$uid1 ."-" .$uid2 ."\","
              ."\"name\":\"" .$this->getName() ."\","
              ."\"mapId\":\"100\","
              ."\"mapType\":\"httpDevice\","
              ."\"deviceType\":\"custom\","
              ."\"targetDevice\":\"Encapsulated\","
              ."\"offUrl\":\"[{\\\"item\\\":\\\"" .str_replace("=", "\\u003d", $switchOffUrl) ."\\\",\\\"httpVerb\\\":\\\"GET\\\",\\\"contentType\\\":\\\"text/html\\\"}]\","
              ."\"onUrl\":\"[{\\\"item\\\":\\\"" .str_replace("=", "\\u003d", $switchOnUrl) ."\\\",\\\"httpVerb\\\":\\\"GET\\\",\\\"contentType\\\":\\\"text/html\\\"}]\","
              ."\"httpVerb\":\"GET\","
              ."\"contentType\":\"text/html\","
              ."\"inactive\":false,"
              ."\"noState\":false"
              ."}";
              
        return $this->getDefaultSwitchParams()!=null ? $ret : "";
    }


    /**
     * Liefert entsprechend des übergebenen Parameter-Arrays 
     * den URL-String zurück, der zum schalten des Objektes
     * ausgeführt werden muss. 
     * 
     * @param $paramsRowValArray  Zweidimensionales Array aller Parameter.
     *                            zu jedem Parameter-Eintrag im Array ist 
     *                            der erste Wert die Parameter-Row und 
     *                            der zweite Wert der Wert der verwendet werden soll. 
     *  
     * @param $saveParamValues    gibt an, ob die Werte aus dem 
     *                            Array als letzter Wert des Parameters
     *                            gespeichert werden sollen. 
     */
    function getSwitchCommand($paramsRowValArray, $saveParamValues=false){
        $command = "";
        $switchParams = array();

        $lastSenderTyp = "";
        $switchUrl = "";
        $switchParamArray = array();
        $allParamsSet = true;
        
        foreach ($paramsRowValArray as $paramArray) {
            $row = $paramArray[0];
            $newValue = $paramArray[1];
            
            $optional = false;
            $fix = false;
            $default_logic = false;
            $mandatory = false;

            if ($row->getNamedAttribute("optional") == "J") {
                $optional = true;
            }
            if ($row->getNamedAttribute("mandatory") == "J") {
                $mandatory = true;
            }
            if ($row->getNamedAttribute("fix") == "J") {
                $fix = true;
            }
            if ($row->getNamedAttribute("default_logic") == "J") {
                $default_logic = true;
            }

            if (strlen($newValue) > 0) {
                if (strlen($switchUrl) > 0) {
                    $switchUrl .= "&";
                }
                $switchUrl .= $row->getNamedAttribute('name') . "=" . $newValue;
                $switchParams[count($switchParams)] = array($row, $newValue);
            } else {
                if(!$mandatory){
                    if($saveParamValues){
                        $this->setParameterValue($row, null);
                    }
                }
            }

            if (strlen($newValue) <= 0) {
                if ($mandatory){
                    if (!$optional || ($optional && $itm->isParameterOptionalActive($row['id']))) {
                        $allParamsSet = false;
                    }
                } 
            }
        }
        
        //echo "Alle notwendigen Parameter gesetzt? ". ($allParamsSet?"Ja":"Nein")."</br>";
        // Schalten wenn alle Parameter gesetzt wurden 
        if ($allParamsSet) {
            $senderUrl = getArduinoUrlForDeviceId($this->getId(), $_SESSION['config']->DBCONNECT);
            $useSenderUrl = strlen($senderUrl) > 0 ? $senderUrl : $arduinoUrl;
            $urlArray = parse_url($useSenderUrl);
            $host = $urlArray['host'];

            $command = $useSenderUrl . "?" . $switchUrl;
        }
        
        if($saveParamValues){
            echo "saveParams<br/>";
            // Neue Parameter-Werte merken
            foreach ($switchParams as $p) {
                $pRow = $p[0];
                $pValue = $p[1];

                if ($pRow->getNamedAttribute("fix") != "J" && $pRow->getNamedAttribute("default_logic") != "J") {
                    echo $pRow->getNamedAttribute("name") ."=" .$pValue ."<br/>";
                    $this->setParameterValue($pRow, $pValue);
                }
            }
        }
        
        return $command;
    }
    
    /**
     * Die Methode löscht das Objekt inklusive aller abhängigen Einträge in der Datenbank. 
     */
    function deleteItem(){
        $sqlRemoveEditorZuordnung = "DELETE FROM homecontrol_control_editor_zuordnung WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveEditorZuordnung);

        $sqlRemoveCronItems = "DELETE FROM homecontrol_cron_items WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveCronItems);

        $sqlRemoveCronParamItems = "DELETE FROM homecontrol_cron_parameter_values WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveCronParamItems);

        $sqlRemoveAlarmItems = "DELETE FROM homecontrol_alarm_items WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveAlarmItems);

        $sqlRemoveRegelItems = "DELETE FROM homecontrol_regeln_items WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveRegelItems);

        $sqlRemoveSenderTypenParamOptional = "DELETE FROM homecontrol_sender_typen_parameter_optional WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveSenderTypenParamOptional);

        $sqlRemoveShortcutItems = "DELETE FROM homecontrol_shortcut_items WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveShortcutItems);

        $sqlRemoveShortcutParamValues = "DELETE FROM homecontrol_shortcut_parameter_values WHERE config_id = " .$this->getId();
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveShortcutParamValues);


        $this->CONFIG_ROW->deleteFromDb();
        refreshEchoDb($_SESSION['config']->DBCONNECT);
    }
    
    
    
    /**
     * Standard-Anzeige Methode
     */
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
                $btns = $this->getSwitchButtons();
                $btns->setStyle("position", "relative");
                $btns->setStyle("left", "-17px");
                $btns->setStyle("top", "-20px");
                $btns->show();
            }
            echo "</div>";
        }
    }


}

?>