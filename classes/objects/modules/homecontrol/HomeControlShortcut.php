<?PHP 


class HomeControlShortcut {
    private $ROW;
    private $ITEMS = array();
    
    private $ID=-1;
    private $NAME="";
    private $BESCHREIBUNG="";
    private $SHOW_SHORTCUT=false;
    
    function HomeControlShortcut($shortcutRow){
        $this->ROW           = $shortcutRow;

        $this->ID            = $this->ROW->getNamedAttribute("id");
        $this->NAME          = $this->ROW->getNamedAttribute("name");
        $this->BESCHREIBUNG  = $this->ROW->getNamedAttribute("beschreibung");
        $this->SHOW_SHORTCUT = $this->ROW->getNamedAttribute("show_shortcut");
    }
    
    function getId() {
        return $this->ID;
    }
    
    function getName() {
        return $this->NAME;
    }

    function getBeschreibung() {
        return $this->BESCHREIBUNG;
    }

    function isVisible() {
        return $this->SHOW_SHORTCUT;
    }




    /**
     * Liefert ein Array aller IDs 
     * die dem Cron zugeordnet sind.
     */
    function getItemRowsForShortcut() {
        $ret_item_rows = array();

        $sqlItems = "SELECT * FROM homecontrol_shortcut_items WHERE shortcut_id=" . $this->ID;
        $resultItems = $_SESSION['config']->DBCONNECT->executeQuery($sqlItems);

        while ($row = mysql_fetch_array($resultItems)) {
            // Wenn ConfigId angegeben ist, diese hinzuf?gen
            $whereStmt = "";
            if (strlen($row['config_id']) > 0) {
                $whereStmt .= "id=" . $row['config_id'];
            } else {
                // Ansonsten die entsprechenden Ger?te ausw?hlen und alle hinzuf?gen
                if (strlen($row['art_id']) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt .= " AND ";
                    }
                    $whereStmt .= "control_art=" . $row['art_id'];
                }

                if (strlen($row['zimmer_id']) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt .= " AND ";
                    }
                    $whereStmt .= "zimmer=" . $row['zimmer_id'];
                }

                if (strlen($row['etagen_id']) > 0) {
                    if ($whereStmt != "") {
                        $whereStmt .= " AND ";
                    }
                    $whereStmt .= "etage=" . $row['etagen_id'];
                }
            }

            $itemDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_config", array("*"), "", "", "", $whereStmt);

            foreach ($itemDbTbl->ROWS as $rowItem) {
                $ret_item_rows[count($ret_item_rows)] = $rowItem;
            }
        }

        return $ret_item_rows;
    }


    /**
     *  Schaltet alle vollständig konfigurierten Geräte
     */
    function switchShortcut() {
        $command = "";
        
        foreach ($this->getItemRowsForShortcut() as $itemRow) {
            $configItem = $_SESSION['config']->getItemById($itemRow->getNamedAttribute("id"));
            //echo "</br>Item: " . $configItem->getName();
            
            if($configItem != null){
                $allParams = array();
                $optionalParams = array();
                $fixParams = array();
                $defaultLogicParams = array();
                $switchParams = array();
    
                $paramTable = new DbTable($_SESSION['config']->DBCONNECT, 
                                          "homecontrol_sender_typen_parameter", array('*'), "", "", "",
                                          "senderTypId=(SELECT s.senderTypId FROM homecontrol_sender s, homecontrol_config c WHERE s.id = c.sender_id AND c.id = " .$configItem->getId() .")" 
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
                    $prefix = "";
                    if($default_logic){
                        $prefix = $configItem->getParameterValueForShortcut($row, $this->ID)==$configItem->getDefaultLogicAnText()?"":"-";
                    }

                    $value = $fix?($prefix.$configItem->getParameterValue($row)):$configItem->getParameterValueForShortcut($row, $this->ID);

                    $lfdnr = count($switchParamArray);
                    $switchParamArray[$row->getNamedAttribute("id")][0] = $row;
                    $switchParamArray[$row->getNamedAttribute("id")][1] = $value;
                }
                
                $command .= "curl '".$configItem->getSwitchCommand($switchParamArray)."' > /dev/null 2>&1\nsleep 1\n";
            }
        }
        
        // Temporäres Bash-Script erstellen
        $executePath = "/shortcuts/";
        $shortcutPath = "/var/www/".$executePath;
        $fileName = "s".time().".sh";
        $callerFileName = "do_".time().".sh";
        
        try {
            // Schalt-Befehle des Shortcuts
            $myfile = fopen($shortcutPath.$fileName, "w");
            fwrite($myfile, $command);
            fclose($myfile);
            
            // Shortcut-Script als Job-Aufruf
            $myfile = fopen($shortcutPath.$callerFileName, "w");
            fwrite($myfile, "cd " .$shortcutPath ."\n");
            fwrite($myfile, "./".$fileName ." > /dev/null 2>/dev/null &");
            fclose($myfile);
            
            // Rechte setzen 
            exec("chmod +x ".$shortcutPath.$fileName);
            exec("chmod +x ".$shortcutPath.$callerFileName);
            
            // Shortcut starten
            exec(".".$executePath.$callerFileName);
            
            // Alte Scripts vom Vortag entfernen
            exec("find " .$shortcutPath ." -mtime +1 -exec rm -f {} \;");
        } catch (exception $e) {
            echo $e;
        }
        
        
    }



    function getHaBridgeDbString($id, $mainUid="00:17:88:5E:D3"){
        $uidTmp = $id;
        $uid1=0;
        $uid2=0;
        
        while($uidTmp>99){
            $uid1++;
        }
        $uid2 = $uidTmp;
        
        $uid = str_pad($id, 2, "0", STR_PAD_LEFT);
        $switchOnUrl = "http://".$_SERVER['SERVER_ADDR']."/api.php?switch=shortcut&shortcutId=".$this->getId();
        
        $ret = "{"
              ."\"id\":\"" .$id ."\","
              ."\"uniqueid\":\"" .$mainUid .":" .$uid1 ."-" .$uid2 ."\","
              ."\"name\":\"" ."Shortcut " .$this->getName() ."\","
              ."\"mapId\":\"100\","
              ."\"mapType\":\"httpDevice\","
              ."\"deviceType\":\"custom\","
              ."\"targetDevice\":\"Encapsulated\","
              //."\"offUrl\":\"[{\\\"item\\\":\\\"" .str_replace("=", "\\u003d", $switchOffUrl) ."\\\",\\\"httpVerb\\\":\\\"GET\\\",\\\"contentType\\\":\\\"text/html\\\"}]\","
              ."\"onUrl\":\"[{\\\"item\\\":\\\"" .str_replace("=", "\\u003d", $switchOnUrl) ."\\\",\\\"httpVerb\\\":\\\"GET\\\",\\\"contentType\\\":\\\"text/html\\\"}]\","
              ."\"httpVerb\":\"GET\","
              ."\"contentType\":\"text/html\","
              ."\"inactive\":false,"
              ."\"noState\":false"
              ."}";
              
        return $ret;
    }

    
}




?>