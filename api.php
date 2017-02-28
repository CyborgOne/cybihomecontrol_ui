<?PHP
include_once ("init.php");

/**
 * API für allgemeine Funktionen des SmartHome yourself Systems
 * 
 * Inputs 
 * (Werte ans System übermitteln)
 * ------------------------------------
 * set = sensor
 * ------------------------------------
 * 
 * 
 * 
 * Outputs
 * (Werte aus dem System abfragen)
 * ------------------------------------
 * get = objects / shortcuts / crons / sender
 * ------------------------------------
 * 
 * 
 * 
 * Switch
 * (Schaltvorgänge auslösen)
 * ------------------------------------
 * switch = object / shortcut
 * ------------------------------------
 * 
 * 
 * 
 */


if(isset($_REQUEST['set']) && strlen($_REQUEST['set'])>0){
    if($_REQUEST['set']=="sensor"){
        if( isset($_REQUEST['sensorId'])&&isset($_REQUEST['sensorWert']) && strlen($_REQUEST['sensorId'])>0 && strlen($_REQUEST['sensorWert'])>0 ){
            refreshSensorValue($_SESSION['config']->DBCONNECT, $_REQUEST['sensorId'], $_REQUEST['sensorWert']);
            
        } else {
            echo "ERROR:</br>"
                ."Zum setzen eines Sensorwertes sind folgende Parameter notwendig:</br>"
                ."- sensorId</br>"
                ."- sensorWert</br>";
        }
        
    } else {
        echo "ERROR:</br>"
            ."Beim Parameter [set] sind folgende Werte zugelassen:</br>"
            ."- sensor";
    }
    
} else if(isset($_REQUEST['get']) && strlen($_REQUEST['get'])>0){
    if($_REQUEST['get']=="objects"){
        outputObjects(getObjects());
        
    } else if($_REQUEST['get']=="shortcuts"){
        outputObjects(getShortcuts());
        
    } else if($_REQUEST['get']=="crons"){
        outputObjects(getCrons());
        
    } else if($_REQUEST['get']=="sender"){
        outputObjects(getSender());
        
    } else {
        echo "ERROR:</br>"
            ."Beim Parameter [get] sind folgende Werte zugelassen:</br>"
            ."- objects</br>"
            ."- shortcuts</br>"
            ."- crons</br>"
            ."- sender</br>";
    }
    
} else if(isset($_REQUEST['switch']) && strlen($_REQUEST['switch'])>0){
    if($_REQUEST['switch']=="object"){
        if( isset($_REQUEST['objectId']) && strlen($_REQUEST['objectId'])>0 ){
            $itm = $_SESSION['config']->getItemById($_REQUEST['objectId']);
            if($itm != null){
                $itm->switchDevice($itm->checkUrlParams());
            }

        } else {
            echo "ERROR:</br>"
                ."Zum schalten eines Objekts ist folgender Parameter notwendig:</br>"
                ."- objectId</br>";
        }
        
    } else if($_REQUEST['switch']=="shortcut"){
        if( isset($_REQUEST['shortcutId']) && strlen($_REQUEST['shortcutId'])>0 ){
            $shortcutDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_shortcut", array("*"), "", "", "", "id=".$_REQUEST['shortcutId']);
        
            foreach($shortcutDbTbl->ROWS as $shortcutRow){
                $shortcut = new HomeControlShortcut($shortcutRow);
                $shortcut->switchShortcut(); 
            }

        } else if( isset($_REQUEST['shortcutName']) && strlen($_REQUEST['shortcutName'])>0 ){
            $shortcutDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_shortcut", array("*"), "", "", "", "lower(name) = lower(".$_REQUEST['shortcutName'].")");
        
            foreach($shortcutDbTbl->ROWS as $shortcutRow){
                $shortcut = new HomeControlShortcut($shortcutRow);
                $shortcut->switchShortcut(); 
            }

        } else {
            echo "ERROR:</br>"
                ."Zum schalten eines Shortcuts ist folgender Parameter notwendig:</br>"
                ."- shortcutId oder shortcutName</br>";
                
        }

    } else {
        echo "ERROR:</br>"
            ."Beim Parameter [switch] sind folgende Werte zugelassen:</br>"
            ."- object"
            ."- shortcut";
    }    
    
} else {
    echo "ERROR:</br>"
        ."Einer der folgenden Parameter muss gesetzt sein:</br>"
        ."- set = [sensor]</br>"
        ."- get = [objects / shortcuts / crons]</br>"
        ."- switch = [object / shortcut]</br>";
}






/**
 * Liefert ein DOM Object aller hinterlegten Shortcuts mit allen ausgewählten Geräten 
 * incl. zugeordneten Parametern und entsprechenden Werten.
 */
function getShortcuts(){
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    $shortcutList = $dom->createElement('Crons');

    $tblShortcut = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_shortcut");
    
    foreach($tblShortcut->ROWS as $shortcutRow){
        $shortcutNode = $dom->createElement("cron");
        
        $shortcutNode->setAttribute("name", $shortcutRow->getNamedAttribute("name"));
        $shortcutNode->setAttribute("beschreibung", $shortcutRow->getNamedAttribute("beschreibung"));
        $shortcutNode->setAttribute("visible", $shortcutRow->getNamedAttribute("show_shortcut"));
        
        $shortcutList->appendChild($shortcutNode);
    }    
    
    $dom->appendChild($shortcutList);
        
    return $dom;
}




/**
 * Liefert ein DOM Object aller hinterlegten Zeitgesteuerten Ereignisse
 * mit allen ausgewählten Geräten incl. zugeordneten Parametern und entsprechenden Werten.
 */
function getCrons(){
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    $cronsList = $dom->createElement('Crons');

    $tblCrons = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_cron");
    
    foreach($tblCrons->ROWS as $cronRow){
        $cron = new HomeControlCron($cronRow);
        $cronNode = $dom->createElement("cron");
        
        $cronNode->setAttribute("id",$cronRow->getNamedAttribute("id"));
        $cronNode->setAttribute("name",$cronRow->getNamedAttribute("name"));
        $cronNode->setAttribute("montag",$cronRow->getNamedAttribute("montag"));
        $cronNode->setAttribute("dienstag",$cronRow->getNamedAttribute("dienstag"));
        $cronNode->setAttribute("mittwoch",$cronRow->getNamedAttribute("mittwoch"));
        $cronNode->setAttribute("donnerstag",$cronRow->getNamedAttribute("donnerstag"));
        $cronNode->setAttribute("freitag",$cronRow->getNamedAttribute("freitag"));
        $cronNode->setAttribute("samstag",$cronRow->getNamedAttribute("samstag"));
        $cronNode->setAttribute("sonntag",$cronRow->getNamedAttribute("sonntag"));
        $cronNode->setAttribute("stunde",$cronRow->getNamedAttribute("stunde"));
        $cronNode->setAttribute("minute",$cronRow->getNamedAttribute("minute"));
        
        // Items des Crons
        $itemListNode = $dom->createElement("ItemList");
        
        $itemsArray = $cron->getItemRowsForCron();
        foreach( $itemsArray as $objectRow ){
            $itm = new HomeControlItem($objectRow);
            $objectNode = $dom->createElement("Item");
            
            $objectNode->setAttribute("id", $objectRow->getNamedAttribute("id"));
            $objectNode->setAttribute("name", $objectRow->getNamedAttribute("name"));
            $objectNode->setAttribute("beschreibung", $objectRow->getNamedAttribute("beschreibung"));
            $objectNode->setAttribute("art", $objectRow->getNamedAttribute("control_art"));
            $objectNode->setAttribute("etage", $objectRow->getNamedAttribute("etage"));
            $objectNode->setAttribute("zimmer", $objectRow->getNamedAttribute("zimmer"));
            $objectNode->setAttribute("x", $objectRow->getNamedAttribute("x"));
            $objectNode->setAttribute("y", $objectRow->getNamedAttribute("y"));
            $objectNode->setAttribute("senderId", $objectRow->getNamedAttribute("sender_id"));
            
            // -----------------------------
            //  Sender
            $dbTblSender = new DbTable($_SESSION['config']->DBCONNECT, 
                                       "homecontrol_sender",
                                       "",
                                       "",
                                       "",
                                       "",
                                       "id=".$objectRow->getNamedAttribute("sender_id") );
                                       
            if($dbTblSender->getRowCount()>0){
                $senderRow = $dbTblSender->getRow(1);
                
                $senderNode = $dom->createElement("Sender");
                $senderNode->setAttribute("id", $senderRow->getNamedAttribute("id"));
                $senderNode->setAttribute("name", $senderRow->getNamedAttribute("name"));
                $senderNode->setAttribute("ip", $senderRow->getNamedAttribute("ip"));
                $senderNode->setAttribute("default", $senderRow->getNamedAttribute("default_jn"));
                $senderNode->setAttribute("senderTyp", $senderRow->getNamedAttribute("senderTypId"));
                $senderNode->setAttribute("etage", $senderRow->getNamedAttribute("etage"));
                $senderNode->setAttribute("zimmer", $senderRow->getNamedAttribute("zimmer"));
                $senderNode->setAttribute("x", $senderRow->getNamedAttribute("x"));
                $senderNode->setAttribute("y", $senderRow->getNamedAttribute("y"));
                
                // -----------------------------
                // Parameter
                $parameterNode = $dom->createElement("ParameterList");
                $allParamsSet = true;
                foreach($itm->getAllParameter() as $fixParam){
                    $optional = false;
                    $fix = false;
                    $default_logic = false;
    
                    if ($fixParam->isOptional()) {
                        $optional = true;
                    }
                    if ($fixParam->isFix()) {
                        $fix = true;
                    }
                    if ($fixParam->isDefaultLogic()) {
                        $default_logic = true;
                    }

                    $prefix = "";
                    if($default_logic){
                        $prefix = $itm->getParameterValueForCron($fixParam->getRow(), $cronRow->getNamedAttribute("id"))==$itm->getDefaultLogicAnText()?"":"-";
                    }
                    $value = $fix?($prefix.$itm->getParameterValue($fixParam->getRow())):$itm->getParameterValueForCron($fixParam->getRow(), $cronRow->getNamedAttribute("id"));
                    
                    if (isset($value) && strlen($value) > 0) {
                        $fixParameterNode = $dom->createElement("Parameter");
                        $fixParameterNode->setAttribute("name",  $fixParam->getName());
                        $fixParameterNode->setAttribute("value", $value);
                        $parameterNode->appendChild($fixParameterNode);
                    }else {
                        if ($optional){
                            if($itm->isParameterOptionalActive($fixParam->getId())) {
                                $allParamsSet = false;
                            }
                        } else {
                            $allParamsSet = false;
                        }
                    }
                }
                
                if($allParamsSet){
                    $senderNode->appendChild($parameterNode);
                }
                // -----------------------------
        
                $objectNode->appendChild($senderNode);
            }            
            
            $itemListNode->appendChild($objectNode);
        }
        $cronNode->appendChild($itemListNode);
        
        $cronsList->appendChild($cronNode);
    }    
    
    $dom->appendChild($cronsList);
        
    return $dom;
}



/**
 * Liefert ein DOM Object aller hinterlegten Sendern
 * incl den entsprechenden Parametern.
 * Bei optionalen Parametern werden, wenn entsprechend eingeschränkt,
 * die möglichen Werte mit angegeben
 */
 function getSender(){
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    $senderList = $dom->createElement('SenderListe');
        
    $dbTblSender = new DbTable($_SESSION['config']->DBCONNECT, 
                               "homecontrol_sender");
                               
    foreach($dbTblSender->ROWS as $senderRow){
        $sender = new HomeControlSender($senderRow);
        
        $senderNode = $dom->createElement("Sender");
        $senderNode->setAttribute("id", $senderRow->getNamedAttribute("id"));
        $senderNode->setAttribute("name", $senderRow->getNamedAttribute("name"));
        $senderNode->setAttribute("ip", $senderRow->getNamedAttribute("ip"));
        $senderNode->setAttribute("default", $senderRow->getNamedAttribute("default_jn"));
        $senderNode->setAttribute("senderTyp", $senderRow->getNamedAttribute("senderTypId"));
        $senderNode->setAttribute("etage", $senderRow->getNamedAttribute("etage"));
        $senderNode->setAttribute("zimmer", $senderRow->getNamedAttribute("zimmer"));
        $senderNode->setAttribute("x", $senderRow->getNamedAttribute("x"));
        $senderNode->setAttribute("y", $senderRow->getNamedAttribute("y"));
        
        // -----------------------------
        // Parameter
        $dbTblParam = new DbTable($_SESSION['config']->DBCONNECT, 
                                  "homecontrol_sender_typen_parameter",
                                  "",
                                  "",
                                  "",
                                  "",
                                  "senderTypId=".$senderRow->getNamedAttribute("senderTypId"));

        foreach($dbTblParam->ROWS as $paramRow){
            $parameterNode = $dom->createElement("Parameter");
            
            $parameterNode->setAttribute("name", $paramRow->getNamedAttribute("name"));
            $parameterNode->setAttribute("parameterArtId", $paramRow->getNamedAttribute("parameterArtId"));
            $parameterNode->setAttribute("fix", $paramRow->getNamedAttribute("fix"));
            $parameterNode->setAttribute("default_logic", $paramRow->getNamedAttribute("default_logic"));
            $parameterNode->setAttribute("optional", $paramRow->getNamedAttribute("optional"));
            
            // Parameter-Art
            $dbTblParamArt = new DbTable($_SESSION['config']->DBCONNECT, 
                                         "homecontrol_sender_typen_parameter_arten",
                                         "",
                                         "",
                                         "",
                                         "",
                                         "id=".$paramRow->getNamedAttribute("parameterArtId"));
                                         
            if($dbTblParamArt->getRowCount()>0){
                $paramArtRow = $dbTblParamArt->getRow(1);
                $parameterArtNode = $dom->createElement("ParameterArt");
                $parameterArtNode->setAttribute("name", $paramArtRow->getNamedAttribute("name"));
                
                if(strlen($paramArtRow->getNamedAttribute("von"))>0 && strlen($paramArtRow->getNamedAttribute("bis"))>0 ){
                    $parameterArtNode->setAttribute("von", $paramArtRow->getNamedAttribute("von"));
                    $parameterArtNode->setAttribute("bis", $paramArtRow->getNamedAttribute("bis"));
                    for($i=$paramArtRow->getNamedAttribute("von");$i<=$paramArtRow->getNamedAttribute("bis");$i++){
                        $parameterArtValuesNode = $dom->createElement("possibleValue");
                        $parameterArtValuesNode->setAttribute("key", $i);
                        $parameterArtValuesNode->setAttribute("value", $i);
                        $parameterArtNode->appendChild($parameterArtValuesNode);
                    }
                }
                
                if(strlen($paramArtRow->getNamedAttribute("minlen"))>0 && strlen($paramArtRow->getNamedAttribute("maxlen"))>0 ){
                    $parameterArtNode->setAttribute("minlen", $paramArtRow->getNamedAttribute("minlen"));
                    $parameterArtNode->setAttribute("maxlen", $paramArtRow->getNamedAttribute("maxlen"));
                }
                
                if(strlen($paramArtRow->getNamedAttribute("defaultValueTag"))>0){
                    $parameterArtNode->setAttribute("defaultValueTag", $paramArtRow->getNamedAttribute("defaultValueTag"));
                    $valueTags = getDefaultComboArray($paramArtRow->getNamedAttribute("defaultValueTag"));
                    foreach($valueTags as $tagKey=>$tagVal){
                        $parameterArtValuesNode = $dom->createElement("possibleValue");
                        $parameterArtValuesNode->setAttribute("key", $tagKey);
                        $parameterArtValuesNode->setAttribute("value", $tagVal);
                        $parameterArtNode->appendChild($parameterArtValuesNode);
                    }
                }
                
                $parameterNode->appendChild($parameterArtNode);
            }
            
            $senderNode->appendChild($parameterNode);
        }
        // -----------------------------

        $senderList->appendChild($senderNode);
    }
        
    $dom->appendChild($senderList);
        
    return $dom;
}



/**
 * Liefert ein DOM Object aller hinterlegten Geräte
 * incl zugeordnetem Sender und den entsprechenden Parametern.
 * Bei optionalen Parametern werden, wenn entsprechend eingeschränkt,
 * die möglichen Werte mit übergeben
 */
function getObjects(){
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    
    $dbTblObjects = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_config");

    $objectList = $dom->createElement('Objects');
    
    // Alle Geraete als XML 
    foreach($dbTblObjects->ROWS as $objectRow){
        $itm = new HomeControlItem($objectRow);
        
        $objectNode = $dom->createElement("Object");
        $objectNode->setAttribute("id", $objectRow->getNamedAttribute("id"));
        $objectNode->setAttribute("name", $objectRow->getNamedAttribute("name"));
        $objectNode->setAttribute("beschreibung", $objectRow->getNamedAttribute("beschreibung"));
        $objectNode->setAttribute("art", $objectRow->getNamedAttribute("control_art"));
        $objectNode->setAttribute("etage", $objectRow->getNamedAttribute("etage"));
        $objectNode->setAttribute("zimmer", $objectRow->getNamedAttribute("zimmer"));
        $objectNode->setAttribute("x", $objectRow->getNamedAttribute("x"));
        $objectNode->setAttribute("y", $objectRow->getNamedAttribute("y"));
        $objectNode->setAttribute("senderId", $objectRow->getNamedAttribute("sender_id"));

        // -----------------------------
        //  Sender
        $dbTblSender = new DbTable($_SESSION['config']->DBCONNECT, 
                                   "homecontrol_sender",
                                   "",
                                   "",
                                   "",
                                   "",
                                   "id=".$objectRow->getNamedAttribute("sender_id") );
                                   
        if($dbTblSender->getRowCount()>0){
            $senderRow = $dbTblSender->getRow(1);
            
            $senderNode = $dom->createElement("Sender");
            $senderNode->setAttribute("id", $senderRow->getNamedAttribute("id"));
            $senderNode->setAttribute("name", $senderRow->getNamedAttribute("name"));
            $senderNode->setAttribute("ip", $senderRow->getNamedAttribute("ip"));
            $senderNode->setAttribute("default", $senderRow->getNamedAttribute("default_jn"));
            $senderNode->setAttribute("senderTyp", $senderRow->getNamedAttribute("senderTypId"));
            $senderNode->setAttribute("etage", $senderRow->getNamedAttribute("etage"));
            $senderNode->setAttribute("zimmer", $senderRow->getNamedAttribute("zimmer"));
            $senderNode->setAttribute("x", $senderRow->getNamedAttribute("x"));
            $senderNode->setAttribute("y", $senderRow->getNamedAttribute("y"));
            
                // -----------------------------
                // Parameter
                $parameterNode = $dom->createElement("Parameter");
                
                foreach($itm->getFixParameter() as $fixParam){
                    $fixParameterNode = $dom->createElement("Fix");
                    $fixParameterNode->setAttribute("name",$fixParam->getName());
                    $fixParameterNode->setAttribute("value", $fixParam->getValue());
                    $parameterNode->appendChild($fixParameterNode);
                }
    
                foreach($itm->getControlParameter() as $optionalParam){
                    $optionalParameterNode = $dom->createElement("Optional");
                    $optionalParameterNode->setAttribute("name", $optionalParam->getName());
                    $values = $itm->getSender()->getTyp()->getPossibleParameterValues($optionalParam->getRow());
                    foreach($values as $v){
                        $pValNode = $dom->createElement("PossibleValue");
                        $pValNode->setAttribute("value", $v);
                        $optionalParameterNode->appendChild($pValNode);
                    }
                    $parameterNode->appendChild($optionalParameterNode);
                }
                
                $senderNode->appendChild($parameterNode);
                // -----------------------------
    
            $objectNode->appendChild($senderNode);
        }
        // -----------------------------


        $objectList->appendChild($objectNode);
    }
    
    $dom->appendChild($objectList);
        
    return $dom;
}



/**
 * gibt das übergebene DOM Object aus
 */
function outputObjects($dom){
    header("Content-type: text/xml; charset=utf-8");
    echo $dom->saveXML();
}


?>