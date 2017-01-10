<?PHP

class HomeControlTermEditor extends Object {
    private $TYPE         = null;
    private $URL_PARAMS   = "";
    private $TERM_ROW     = null;
    
    private $TYPE_SENSOR_WERT      = 1;
    private $TYPE_SENSOR_STATUS    = 2;
    private $TYPE_ZEIT             = 3;
    private $TYPE_WOCHENTAG        = 4;
    
    function HomeControlTermEditor($dbRowHcTerm, $additionalUrlParams=""){
        $this->URL_PARAMS = $additionalUrlParams;
        $this->TERM_ROW = $dbRowHcTerm;
        $this->TYPE = $this->TERM_ROW->getNamedAttribute("term_type");
        
        $this->checkSensorWertTermEditorMask();
        $this->checkSensorStatusTermEditorMask();
        $this->checkTimeTermEditorMask();
        $this->checkWochentagTermEditorMask();
    }
    
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getSensorWertTermEditorMask(){
        $div = new Div ("editSensorWert");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition");
        $sensorSql = "SELECT id, concat(name, ' (', IFNULL((SELECT name FROM homecontrol_etagen e WHERE e.id=s.etage),''), ' - ',"
                    ." IFNULL((SELECT name FROM homecontrol_zimmer z WHERE z.id=s.zimmer),''), ')') "
                    ." FROM homecontrol_sensor s WHERE (SELECT status_sensor_jn FROM homecontrol_sensor_arten WHERE id = s.sensor_art)!='J' ORDER BY etage, zimmer, name";
                         
        $sensorCbo = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sensorSql, "sensor");
        $sensorCbo->setStyle("width",200);
   
        $wertTxt     = new Textfield("value",$this->TERM_ROW->getNamedAttribute("value"),9,9);
        
        $triggerChb  = new Checkbox("trigger_jn","Trigger?","J",$this->TERM_ROW->getNamedAttribute("trigger_jn"));
        $triggerChb->setToolTip("Gibt an, ob eine &Auml;nderung des Wertes einen Schaltvorgang aktiviert oder nur als Bedingung dient.");
        
        $t = new Table(array("","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Wert");
        $r->setAttribute(1,$sensorCbo);
        $r->setAttribute(2,$condition);
        $r->setAttribute(3,$wertTxt);
        $r->setAttribute(4,$triggerChb);
        $r->setAttribute(5,new Button("saveEditSensorWertTerm", " Speichern "));
        $t->addRow($r);

        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("editTerm", $_REQUEST['editTerm']));
        $t->addRow($rH);

        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("editSensorWert", "ok"));
        $t->addRow($rH2);

        $div->add($t);

        return $div;
    }
    
    
    /**
     * Prüfen ob TimeTermEditor angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkSensorWertTermEditorMask($update=true){
        if (isset($_REQUEST['saveEditSensorWertTerm']) && isset($_REQUEST['editTerm']) && strlen($_REQUEST['saveEditSensorWertTerm'])>0 
          && strlen($_REQUEST['condition'])>0 && strlen($_REQUEST['sensor'])>0 && strlen($_REQUEST['value'])>0 ){
            $sqlUpdate = "UPDATE homecontrol_term " 
                    ."SET termcondition='".$_REQUEST['condition'] ."', sensor_id=" .$_REQUEST['sensor'] 
                       .", value='" .$_REQUEST['value'] ."' " 
                       ."WHERE id = ".$_REQUEST['editTerm'];
            if($update){
                $_SESSION['config']->DBCONNECT->executeQuery($sqlUpdate);
                $this->TERM_ROW->setNamedAttribute('sensor_id', $_REQUEST['sensor']);
                $this->TERM_ROW->setNamedAttribute('value', $_REQUEST['value']);
                $this->TERM_ROW->setNamedAttribute('termcondition', $_REQUEST['condition']);
            }

            $this->TYPE = null;
            
            return false;
        }
        return true;
    }
    
       
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getSensorStatusTermEditorMask(){
        $div = new Div ("editSensorStatus");
        
        $statusCbo = new Checkbox("status", "", "J", $this->TERM_ROW->getNamedAttribute("status"));
        $sensorSql = "SELECT id, concat(name, ' (', IFNULL((SELECT name FROM homecontrol_etagen e WHERE e.id=s.etage),''), ' - ',"
                    ." IFNULL((SELECT name FROM homecontrol_zimmer z WHERE z.id=s.zimmer),''), ')') "
                    ." FROM homecontrol_sensor s WHERE (SELECT status_sensor_jn FROM homecontrol_sensor_arten WHERE id = s.sensor_art)='J' ORDER BY etage, zimmer, name";
                         
        $sensorCbo = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sensorSql, "sensor");
        $sensorCbo->setStyle("width",200);
        
        $triggerChb  = new Checkbox("trigger_jn","Trigger?","J",$this->TERM_ROW->getNamedAttribute("trigger_jn"));
        $triggerChb->setToolTip("Gibt an, ob eine &Auml;nderung des Wertes einen Schaltvorgang aktiviert oder nur als Bedingung dient.");

        $t = new Table(array("","","","","","",""));
        $t->setAlignments(array("","","","right","","right","right"));
        $r = $t->createRow();
        $r->setAttribute(0, "Status");
        $r->setAttribute(1, $sensorCbo);
        $r->setAttribute(2, "=");
        $r->setAttribute(3, $statusCbo);
        $r->setAttribute(4, " ");
        $r->setAttribute(5, $triggerChb);
        $r->setAttribute(6,new Button("saveEditSensorStatusTerm", " Speichern "));
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("editTerm", $_REQUEST['editTerm']));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("editSensorStatus", "ok"));
        $t->addRow($rH2);
        
        $div->add($t);
        
        return $div;
    }
    
    
    /**
     * Prüfen ob TimeTermEditor angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkSensorStatusTermEditorMask($update=true){
        if (isset($_REQUEST['saveEditSensorStatusTerm']) && isset($_REQUEST['editTerm']) && strlen($_REQUEST['saveEditSensorStatusTerm'])>0 
           && strlen($_REQUEST['sensor'])>0 ){
            $sqlUpdate = "UPDATE homecontrol_term " 
                       ."SET status='".($_REQUEST['status']=="J"?"J":"N") ."', sensor_id=" .$_REQUEST['sensor'] ." "
                       ."WHERE id = ".$_REQUEST['editTerm'];
                       
            if($update){
                $_SESSION['config']->DBCONNECT->executeQuery($sqlUpdate);
                $this->TERM_ROW->setNamedAttribute('status', $_REQUEST['status']);
                $this->TERM_ROW->setNamedAttribute('sensor_id', $_REQUEST['sensor']);
            }
            

            $this->TYPE = null;
            
            return false;
        }
        return true;
    }
    
       
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getTimeTermEditorMask(){
        $div = new Div ("editZeit");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition", $this->TERM_ROW->getNamedAttribute("termcondition"));
        $hourCob = new Combobox("stunde", getNumberComboArray(0,24), $this->TERM_ROW->getNamedAttribute("std"), " ");
        $minCob  = new Combobox("minute", getNumberComboArray(0,60), $this->TERM_ROW->getNamedAttribute("min"), " ");
                
        $triggerChb  = new Checkbox("trigger_jn","Trigger?","J",$this->TERM_ROW->getNamedAttribute("trigger_jn"));
        $triggerChb->setToolTip("Gibt an, ob eine &Auml;nderung des Wertes einen Schaltvorgang aktiviert oder nur als Bedingung dient.");

        $t = new Table(array("","","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Uhrzeit");
        $r->setAttribute(1,$condition);
        $r->setAttribute(2,$hourCob);
        $r->setAttribute(3,":");
        $r->setAttribute(4,$minCob);
        $r->setAttribute(5,$triggerChb);
        $r->setAttribute(6,new Button("saveEditTimeTerm", " Speichern "));
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("editTerm", $_REQUEST['editTerm']));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("editZeit", "ok"));
        $t->addRow($rH2);
        
        $div->add($t);
        
        return $div;
    }
    
    
    /**
     * Prüfen ob TimeTermEditor angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkTimeTermEditorMask($update=true){
        if (isset($_REQUEST['saveEditTimeTerm']) && isset($_REQUEST['editTerm']) && strlen($_REQUEST['saveEditTimeTerm'])>0 
          && strlen($_REQUEST['stunde'])>0 && strlen($_REQUEST['minute'])>0 && strlen($_REQUEST['condition'])>0 ){
            $sqlUpdate = "UPDATE homecontrol_term " 
                    ."SET min=".$_REQUEST['minute'] .", std=" .$_REQUEST['stunde'] 
                       .", termcondition='" .$_REQUEST['condition'] ."' " 
                       ."WHERE id = ".$_REQUEST['editTerm'];
            if($update){
                $_SESSION['config']->DBCONNECT->executeQuery($sqlUpdate);
            }
            $this->TERM_ROW->setNamedAttribute('std', $_REQUEST['stunde']);
            $this->TERM_ROW->setNamedAttribute('min', $_REQUEST['minute']);
            $this->TERM_ROW->setNamedAttribute('termcondition', $_REQUEST['condition']);

            $this->TYPE = null;
            
            return false;
        }
        return true;
    }
    
       
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getWochentagTermEditorMask(){
        $div = new Div ("editWochentag");
        
        $cboMo = new Checkbox("montag", "", "J", $this->TERM_ROW->getNamedAttribute("montag") );
        $cboDi = new Checkbox("dienstag", "", "J", $this->TERM_ROW->getNamedAttribute("dienstag"));
        $cboMi = new Checkbox("mittwoch", "", "J", $this->TERM_ROW->getNamedAttribute("mittwoch"));
        $cboDo = new Checkbox("donnerstag", "", "J", $this->TERM_ROW->getNamedAttribute("donnerstag"));
        $cboFr = new Checkbox("freitag", "", "J", $this->TERM_ROW->getNamedAttribute("freitag"));
        $cboSa = new Checkbox("samstag", "", "J", $this->TERM_ROW->getNamedAttribute("samstag"));
        $cboSo = new Checkbox("sonntag", "", "J", $this->TERM_ROW->getNamedAttribute("sonntag"));
        
        $triggerChb  = new Checkbox("trigger_jn","","J",$this->TERM_ROW->getNamedAttribute("trigger_jn"));
        $triggerChb->setToolTip("Gibt an, ob Bedingung für Reverse-Schaltungen geprüft werden soll.");

        $t = new Table(array("Mo","Di","Mi","Do","Fr","Sa","So","",""));
        $t->setAlign("center");
        $r = $t->createRow();
        $r->setAttribute(0,"Montag");
        $r->setAttribute(1,"Dienstag");
        $r->setAttribute(2,"Mittwoch");
        $r->setAttribute(3,"Donnerstag");
        $r->setAttribute(4,"Freitag");
        $r->setAttribute(5,"Samstag");
        $r->setAttribute(6,"Sonntag");
        $r->setAttribute(7,"Trigger?");
        $r->setAttribute(8,"");
        $t->addRow($r);
        
        $r = $t->createRow();
        $r->setAttribute(0,$cboMo);
        $r->setAttribute(1,$cboDi);
        $r->setAttribute(2,$cboMi);
        $r->setAttribute(3,$cboDo);
        $r->setAttribute(4,$cboFr);
        $r->setAttribute(5,$cboSa);
        $r->setAttribute(6,$cboSo);
        $r->setAttribute(7,$triggerChb);
        $r->setAttribute(8,new Button("saveEditWochentagTerm", " Speichern "));
        $t->addRow($r);
        
        $rH3 = $t->createRow();
        $rH3->setSpawnAll(true);
        $rH3->setAttribute(0, new Hiddenfield("editTerm", $_REQUEST['editTerm']));
        $t->addRow($rH3);
                
        $rH4 = $t->createRow();
        $rH4->setSpawnAll(true);
        $rH4->setAttribute(0, new Hiddenfield("editWochentag", "ok"));
        $t->addRow($rH4);
        
        $div->add($t);
        
        return $div;
    }
    
    
    /**
     * Prüfen ob TimeTermEditor angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkWochentagTermEditorMask($update=true){
          if ( isset($_REQUEST['saveEditWochentagTerm']) && strlen($_REQUEST['saveEditWochentagTerm'])>0 
          && ( strlen($_REQUEST['montag'])>0 || 
               strlen($_REQUEST['dienstag'])>0 || 
               strlen($_REQUEST['mittwoch'])>0 || 
               strlen($_REQUEST['donnerstag'])>0 || 
               strlen($_REQUEST['freitag'])>0 || 
               strlen($_REQUEST['samstag'])>0 || 
               strlen($_REQUEST['sonntag'])>0 ) ){
        
            
            $sqlUpdate = "UPDATE homecontrol_term SET montag='".($_REQUEST['montag']=='J'?"J":"N")."', dienstag='".($_REQUEST['dienstag']=='J'?"J":"N")
                        ."', mittwoch='".($_REQUEST['mittwoch']=='J'?"J":"N")."', donnerstag='".($_REQUEST['donnerstag']=='J'?"J":"N")
                        ."', freitag='".($_REQUEST['freitag']=='J'?"J":"N")."', samstag='".($_REQUEST['samstag']=='J'?"J":"N")
                        ."', sonntag='".($_REQUEST['sonntag']=='J'?"J":"N")."' "
                        ."WHERE id=".$_REQUEST['editTerm'] ;
            if($update){      
              $_SESSION['config']->DBCONNECT->executeQuery($sqlUpdate);
              $this->TERM_ROW->setNamedAttribute('montag', $_REQUEST['montag']);
              $this->TERM_ROW->setNamedAttribute('dienstag', $_REQUEST['dienstag']);
              $this->TERM_ROW->setNamedAttribute('mittwoch', $_REQUEST['mittwoch']);
              $this->TERM_ROW->setNamedAttribute('donnerstag', $_REQUEST['donnerstag']);
              $this->TERM_ROW->setNamedAttribute('freitag', $_REQUEST['freitag']);
              $this->TERM_ROW->setNamedAttribute('samstag', $_REQUEST['samstag']);
              $this->TERM_ROW->setNamedAttribute('sonntag', $_REQUEST['sonntag']);
            }
            $this->TYPE = null;
            
            return false;
        }
        return true;
    }
    
    
    /**
     * $_REQUEST Werte prüfen 
     */
    private function checkRequests(){
        if (isset($_REQUEST['editSensorWert']) && $_REQUEST['editSensorWert']=="ok"){
            $this->TYPE = $this->TYPE_SENSOR_WERT;
        }
        if (isset($_REQUEST['editSensorStatus']) && $_REQUEST['editSensorStatus']=="ok"){
            $this->TYPE = $this->TYPE_SENSOR_STATUS;
        }
        if (isset($_REQUEST['editZeit']) && $_REQUEST['editZeit']=="ok"){
            $this->TYPE = $this->TYPE_ZEIT;
        }
        if (isset($_REQUEST['editWochentag']) && $_REQUEST['editWochentag']=="ok"){
            $this->TYPE = $this->TYPE_WOCHENTAG;
        }    
        if (isset($_REQUEST['trigger_jn']) && $_REQUEST['trigger_jn']!="J"){
            $_REQUEST['trigger_jn']="N";
        }               
    }
    
    
    /**
     * Standard Anzeige-Methode
     */
    public function show(){
        $this->checkRequests();
        
        $t = new Text("");
        
        if($this->TYPE!=null){

          switch ($this->TYPE){
             case $this->TYPE_SENSOR_WERT:
               if($this->checkSensorWertTermEditorMask(false)){
                 $t = $this->getSensorWertTermEditorMask();
               } else {
                 $t = new Text("gespeichert");
               }               
               break;

             case $this->TYPE_SENSOR_STATUS:
               if($this->checkSensorStatusTermEditorMask(false)){
                 $t = $this->getSensorStatusTermEditorMask();
               } else {
                 $t = new Text("gespeichert");
               }
               break;

             case $this->TYPE_ZEIT:
               if($this->checkTimeTermEditorMask(false)){
                 $t = $this->getTimeTermEditorMask();
               } else {
                 $t = new Text("gespeichert");
               }
               break;

             case $this->TYPE_WOCHENTAG:
               if($this->checkWochentagTermEditorMask(false)){
                 $t = $this->getWochentagTermEditorMask();
               } else {
                 $t = new Text("gespeichert");
               }             
               break;
          }

        } 
        
        $t->show();
    }
}

?>