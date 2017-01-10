<?PHP

class HomeControlSenderTyp {
    private $ID;
    private $NAME;
    
    private $PARAMETER_DBTABLE;
    private $PARAMETER_DBTABLE_CONTROL;

    private $HAS_DEFAULT_PARAM = false;
    private $DEFAULT_PARAM_NAME = null;

    function HomeControlSenderTyp($senderTypRow){
        $this->ID = $senderTypRow->getNamedAttribute("id");
        $this->NAME = $senderTypRow->getNamedAttribute("name");
    
        $this->PARAMETER_DBTABLE = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter", array('*'),"","","","senderTypId=".$this->ID." AND fix='J'");
        $this->PARAMETER_DBTABLE_CONTROL = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter", array('*'),"","","","senderTypId=".$this->ID." AND fix='N'");
        
        $this->checkDefaultParam();
    }
    
    function hasDefaultParam(){
        return $this->HAS_DEFAULT_PARAM;
    }
    
    /**
     * prüft, ob Parameter für die 
     * Standard An/Aus-Logik vorhanden ist. 
     */
    function checkDefaultParam(){
        $rows = $this->PARAMETER_DBTABLE->ROWS;
        $this->HAS_DEFAULT_PARAM = false;    
           
        foreach($rows as $row){
            if($row->getNamedAttribute("default_logic")=="J"){
                $this->HAS_DEFAULT_PARAM = true;
            }
        }
    }
    
    
    function getParameterFixDbTbl(){
        return $this->PARAMETER_DBTABLE;
    }
    
    function getParameterControlDbTbl(){
        return $this->PARAMETER_DBTABLE_CONTROL;
    }
    
    
    /**
     * liefert die gesamte Maske zum bearbeiten der Parameter zurück
     * incl. anlegen, bearbeiten und löschen
     */
    function getParameterEditMask($configItem) {
        $ttl = new Title("Parameter");
        $txt = new Text("Parameter sind abh&auml;ngig vom gew&auml;hlten Sender");
        $txt->setFilter(false);
        
        $dv = new Div();
        $dv->add($ttl);
        $dv->add($txt);
        
        if(count($this->PARAMETER_DBTABLE_CONTROL->ROWS)+count($this->PARAMETER_DBTABLE->ROWS)>0){
            $tbl = new Table(array("", ""));
            $tbl->addSpacer(0,10);
            
            $rows = $this->PARAMETER_DBTABLE->ROWS;
            foreach($rows as $row){
                $r = $tbl->createRow();
                $r->setAttribute(0, $row->getNamedAttribute("name"));
                $r->setAttribute(1, $this->getEditParameterValueObject($row, $this->getParameterValue($row, $configItem->getRow())) );
                $tbl->addRow($r);
            }
    
            $rows = $this->PARAMETER_DBTABLE_CONTROL->ROWS;
            foreach($rows as $row){
                if($row->getNamedAttribute("optional")=="J"){
                    $r = $tbl->createRow();
                    $r->setAttribute(0, $row->getNamedAttribute("name") ." aktiv?");
                    $r->setAttribute(1, new Checkbox($row->getNamedAttribute("name")."Optional", "","J",$configItem->isParameterOptionalActive($row->getNamedAttribute("id"))));
                    $tbl->addRow($r);
                }
            }
            
            $dv->add($tbl);
        }
        
        return $dv;
    }
    

    /**
     * liefert die gesamte Maske zum Einstellen der Werte 
     * für die Steuerung zurück
     */
    function getParameterControlMask($configItem) {
        $obj = $this->getAlternativeParameterEditorObject($configItem);
        
        if($obj!=null && method_exists($obj, "show") ){
            return $obj;
        } else {
            $frm = new Form();
            $frm->add(new Hiddenfield("switchConfigId", $configItem->getId()));
            $tbl = new Table(array("", ""));
            $rows = $this->PARAMETER_DBTABLE_CONTROL->ROWS;
            foreach($rows as $row){
                if($row->getNamedAttribute("optional")!="J"||$configItem->isParameterOptionalActive($row->getNamedAttribute("id"))){
                    $r = $tbl->createRow();
                    $r->setBackgroundColor("#ffffff");
                    $r->setAttribute(0, $row->getNamedAttribute("name"));
                    $r->setAttribute(1, $this->getEditParameterValueObject($row, $this->getParameterValue($row, $configItem->getRow())) );
                    $tbl->addRow($r);
                }
            }
                    
            if(! $this->HAS_DEFAULT_PARAM) {
                $r = $tbl->createRow();
                $r->setBackgroundColor("#ffffff");
                $r->setSpawnAll(true);
                $r->setAttribute(0, new Button("sendParamsToSender", "OK"));
                $r->setAlign("center");
                $tbl->addRow($r);
            } else {
                $r = $tbl->createRow();
                $r->setBackgroundColor("#ffffff");
                $r->setSpawnAll(true);
                $r->setAttribute(0, $this->getDefaultLogicSwitchButtons($configItem));
                $r->setAlign("center");
                $tbl->addRow($r);
            }
            
            $frm->add($tbl);
            
            $rowsFix = $this->PARAMETER_DBTABLE->ROWS;
            foreach($rowsFix as $row){
              $frm->add(new Hiddenfield($row->getNamedAttribute("name"), $this->getParameterValue($row, $configItem->getRow()) ) );
            }
            
            return count($rows)>0?$frm:new Div();
        }
        
        return new Div();
    }
    
    
    function getDefaultLogicSwitchButtons($configItem){
        if($this->HAS_DEFAULT_PARAM) {
            $tbl = new Table(array("",""));

            $rows = $this->PARAMETER_DBTABLE->ROWS;
            foreach($rows as $row){
                if($row->getNamedAttribute("default_logic")=="J"){
                    switch ($configItem->getArt()) {
                        //case 1: // Steckdosen
                        //    break;
                        case 2: // Jalousien
                            $tAn = "AUF";
                            $tAus = "ZU";
                            break;
                        //case 3: // Glühbirne
                        //    break;
                        case 4: // Heizung
                            $tAn = "WARM";
                            $tAus = "KALT";
                            break;
                        default:
                            $tAn = "AN";
                            $tAus = "AUS";
            
                    }
                    $txtAn = new Text($tAn, 3, true);
                    $txtAus = new Text($tAus, 3, true);

                    $hddnAn = new HiddenField($row->getNamedAttribute("name").$tAn, $this->getParameterValue($row, $configItem->CONFIG_ROW));
                    $hddnAus = new HiddenField($row->getNamedAttribute("name").$tAus, "-".$this->getParameterValue($row, $configItem->CONFIG_ROW));

                    $lnkAn = new Button($row->getNamedAttribute("name"), $tAn);
                    $lnkAus = new Button($row->getNamedAttribute("name"), $tAus);
                    
                    $r = $tbl->createRow();
                    $r->setAttribute(0, $lnkAn);
                    $r->setAttribute(1, $lnkAus);
                    $tbl->addRow($r);

                    $r1 = $tbl->createRow();
                    $r1->setAttribute(0, $hddnAn);
                    $r1->setAttribute(1, $hddnAus);
                    $tbl->addRow($r1);
                }
            }
            return $tbl;
        }        
    }
    
    
    
    /**
     * prüft ob zu den Parametern ein alternativer Editor 
     * vorhanden ist und gibt diesen ggf zurueck. 
     * Ansonsten wird null zurueckgeliefert. 
     */
    function getAlternativeParameterEditorObject($configItem){
        $alternative = false;
        $editorTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_editoren");
        $dv = new Div();
        
        foreach($editorTbl->ROWS as $editorRow){
            $classname = $editorRow->getNamedAttribute("classname");
            
            $editor = new $classname($editorRow, $configItem);
            
            if($editor->isActivated()){
                $editor->setXPos(10);
                $editor->setYPos(-25);
                $dv->add($editor->getEditMask());
                $alternative = true;
            }
        }
        
        return $alternative?$dv:null;
    }

    
    
    
    function doParameterUpdate($configRow){
        $configItem = new HomeControlItem($configRow);
        
        $rows = $this->PARAMETER_DBTABLE->ROWS;
        $ids = "";
        $names = array();
        foreach($rows as $row){
            if(strlen($ids)>0){
                $ids .= ",";
            }
            $ids .= $row->getNamedAttribute('rowid');
            $names[$row->getNamedAttribute('rowid')] = $row->getNamedAttribute('name');
        }

        if(strlen($ids)>0){
            $pDbTbl = new DbTable(  $_SESSION['config']->DBCONNECT, 
                                        "homecontrol_sender_typen_parameter", 
                                        array('*'),
                                        "",
                                        "",
                                        "",
                                        "senderTypId=(SELECT senderTypId FROM homecontrol_sender WHERE id=" .$configRow->getNamedAttribute("sender_id").")");
            $paramRows = $pDbTbl->ROWS;

            foreach($paramRows as $paramRow){
                $paramName = $paramRow->getNamedAttribute("name");

                $paramDbTbl = new DbTable(  $_SESSION['config']->DBCONNECT, 
                                            "homecontrol_sender_parameter_values", 
                                            array('*'),
                                            "",
                                            "",
                                            "",
                                            "config_id=".$configRow->getNamedAttribute("rowid")
                                            ." AND param_id=".$paramRow->getNamedAttribute("id") ." ");
               
               
//              $paramName = $names[$valueRow->getNamedAttribute("param_id")];
                if (isset($_REQUEST[$paramName])) {
                    if($paramDbTbl->getRow(1)==null){
                        $valueRow = $paramDbTbl->createRow();
                        $valueRow->setNamedAttribute("config_id", $configRow->getNamedAttribute("rowid"));
                        $valueRow->setNamedAttribute("param_id", $paramRow->getNamedAttribute("rowid"));
                        $valueRow->setNamedAttribute("value", $_REQUEST[$paramName]);
                        $valueRow->insertIntoDB();
                    } else {
                        $valueRow = $paramDbTbl->getRow(1);
                        $valueRow->setNamedAttribute("value", $_REQUEST[$paramName]);
                        $valueRow->updateDB();
                    }
                }
            
                $rows = $this->PARAMETER_DBTABLE_CONTROL->ROWS;
                $ids = "";
                $names = array();
                foreach($rows as $row){
                    if(strlen($ids)>0){
                        $ids .= ",";
                    }
                    $ids .= $row->getNamedAttribute('rowid');
                    $names[$row->getNamedAttribute('rowid')] = $row->getNamedAttribute('name');
                    $paramName = $names[$row->getNamedAttribute('rowid')];
                    
                    if($row->getNamedAttribute('optional')=="J"){
                        $sql = "SELECT 'X' FROM homecontrol_sender_typen_parameter_optional p "
                              ."WHERE param_id = " .$row->getNamedAttribute('rowid') ." AND config_id=".$configItem->getId() ."";
                        $rslt = $_SESSION['config']->DBCONNECT->executeQuery($sql);
                        
                        if (isset($_REQUEST[$paramName."Optional"]) && $_REQUEST[$paramName."Optional"]=="J"){
                            
                            if(mysql_numrows($rslt)==0){
                                $sql = "INSERT INTO homecontrol_sender_typen_parameter_optional( config_id, param_id, active) VALUES ( " .$configItem->getId() .", " .$row->getNamedAttribute('rowid') .", 'J' ) ";
                                $_SESSION['config']->DBCONNECT->executeQuery($sql);
                            } else {
                                if(!$configItem->isParameterOptionalActive($valueRow->getNamedAttribute("param_id"))){
                                    $sql = "UPDATE homecontrol_sender_typen_parameter_optional SET active='J' WHERE config_id=".$configItem->getId() ." AND param_id=" .$row->getNamedAttribute('rowid');
                                    $_SESSION['config']->DBCONNECT->executeQuery($sql);
                                }
                            }
                            
                        } else {
                            
                            if(mysql_numrows($rslt)==0){
                                $sql = "INSERT INTO homecontrol_sender_typen_parameter_optional( config_id, param_id, active) VALUES ( " .$configItem->getId() .", " .$row->getNamedAttribute('rowid') .", 'N' ) ";
                                $_SESSION['config']->DBCONNECT->executeQuery($sql);
                            } else {
                                if($configItem->isParameterOptionalActive($row->getNamedAttribute("rowid"))){
                                    $sql = "UPDATE homecontrol_sender_typen_parameter_optional SET active='N' WHERE config_id=".$configItem->getId() ." AND param_id=" .$row->getNamedAttribute('rowid');
                                    $_SESSION['config']->DBCONNECT->executeQuery($sql);
                                }
                            }
                            
                        }
                    }
                }
            }            
        }
    }
    
    
    /**
     * Holt den Parameter-Wert des übergebenen Parameters zum übergebenen zu schaltenden Objekt.
     */
    function getParameterValue($paramRow, $configRow){
        $paramDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_parameter_values", array('*'),"","","","config_id=".$configRow->getNamedAttribute("rowid")." AND param_id=".$paramRow->getNamedAttribute("rowid"));
        $r = $paramDbTbl->getRow(1);

        return $r!=null?$r->getNamedAttribute("value"):"";
    }
    
    /**
     * Holt den Parameter-Wert des übergebenen Parameters zum übergebenen zu schaltenden Objekt.
     */
    function setParameterValue($paramRow, $configRow, $value){
        $paramDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_parameter_values", array('*'),"","","","config_id=".$configRow->getNamedAttribute("rowid")." AND param_id=".$paramRow->getNamedAttribute("rowid"));

        $r = $paramDbTbl->getRow(1);

        if($r == null){
            $r = $paramDbTbl->createRow();
            $r->setNamedAttribute("config_id", $configRow->getNamedAttribute("id"));
            $r->setNamedAttribute("param_id", $paramRow->getNamedAttribute("id"));
        }

        $r->setNamedAttribute("value", $value);
        $r->updateDB();
    }
    
    
    
    /**
     * liefert entsprechend des Parameter-Typs das entsprechende 
     * Objekt zum bearbeiten des Wertes zurück. 
     * z.B. Combobox oder Textfeld.  
     */
    function getEditParameterValueObject($parameterRow, $value){
        $obj = new Textfield($parameterRow->getNamedAttribute("name"), $value, 20);

        $artDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender_typen_parameter_arten", array('*'),"","","","id=".$parameterRow->getNamedAttribute("parameterArtId"));
        $artRow = $artDbTbl->getRow(1);
        
        if($artRow!=null && $parameterRow!=null){
            $von = $artRow->getNamedAttribute("von");
            $bis = $artRow->getNamedAttribute("bis");
            
            $minLen = $artRow->getNamedAttribute("minLen");
            $maxLen = $artRow->getNamedAttribute("maxLen");
            
            $defaultValueTag = $artRow->getNamedAttribute("defaultValueTag");
            
            $optional = $parameterRow->getNamedAttribute("optional")=="J";
            
            if(isset($defaultValueTag) && strlen($defaultValueTag)>0){
                $obj = new Combobox($parameterRow->getNamedAttribute("name"), getDefaultComboArray($defaultValueTag),$optional?"":$value," ");
            } else if (isset($von) && isset($bis) && strlen($von)>0 && strlen($bis)>0){
                $obj = new Combobox($parameterRow->getNamedAttribute("name"), getNumberComboArray($von,$bis), $optional?"":$value," ");
            } else if (isset($minLen) && isset($maxLen) && strlen($minLen)>0 && strlen($maxLen)>0){
                $obj = new Textfield($parameterRow->getNamedAttribute("name"), $optional?"":$value, 20, $maxLen);
            } 
        }
            
        return $obj;
    }   
    
}

?>