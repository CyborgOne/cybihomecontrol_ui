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
        $this->checkTimeTermEditorMask();
    }
    
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getSensorWertTermEditorMask(){
        $div = new Div ("editSensorWert");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition", $this->TERM_ROW->getNamedAttribute("termcondition"));
        $hourCob = new Combobox("stunde", getNumberComboArray(0,24), $this->TERM_ROW->getNamedAttribute("std"), " ");
        $minCob  = new Combobox("minute", getNumberComboArray(0,60), $this->TERM_ROW->getNamedAttribute("min"), " ");
        
        $t = new Table(array("","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Uhrzeit");
        $r->setAttribute(1,$condition);
        $r->setAttribute(2,$hourCob);
        $r->setAttribute(3,":");
        $r->setAttribute(4,$minCob);
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
    private function getSensorStatusTermEditorMask(){
        $div = new Div ("editSensorStatus");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition", $this->TERM_ROW->getNamedAttribute("termcondition"));
        $hourCob = new Combobox("stunde", getNumberComboArray(0,24), $this->TERM_ROW->getNamedAttribute("std"), " ");
        $minCob  = new Combobox("minute", getNumberComboArray(0,60), $this->TERM_ROW->getNamedAttribute("min"), " ");
        
        $t = new Table(array("","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Uhrzeit");
        $r->setAttribute(1,$condition);
        $r->setAttribute(2,$hourCob);
        $r->setAttribute(3,":");
        $r->setAttribute(4,$minCob);
        $r->setAttribute(5,new Button("saveEditTimeTerm", " Speichern "));
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
    private function checkSensorStatusTermEditorMask($update=true){
        if (isset($_REQUEST['saveEditSensorStatusTerm']) && isset($_REQUEST['editTerm']) && strlen($_REQUEST['saveEditSensorStatusTerm'])>0 
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
    private function getTimeTermEditorMask(){
        $div = new Div ("editZeit");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition", $this->TERM_ROW->getNamedAttribute("termcondition"));
        $hourCob = new Combobox("stunde", getNumberComboArray(0,24), $this->TERM_ROW->getNamedAttribute("std"), " ");
        $minCob  = new Combobox("minute", getNumberComboArray(0,60), $this->TERM_ROW->getNamedAttribute("min"), " ");
        
        $t = new Table(array("","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Uhrzeit");
        $r->setAttribute(1,$condition);
        $r->setAttribute(2,$hourCob);
        $r->setAttribute(3,":");
        $r->setAttribute(4,$minCob);
        $r->setAttribute(5,new Button("saveEditTimeTerm", " Speichern "));
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
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition", $this->TERM_ROW->getNamedAttribute("termcondition"));
        $hourCob = new Combobox("stunde", getNumberComboArray(0,24), $this->TERM_ROW->getNamedAttribute("std"), " ");
        $minCob  = new Combobox("minute", getNumberComboArray(0,60), $this->TERM_ROW->getNamedAttribute("min"), " ");
        
        $t = new Table(array("","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Uhrzeit");
        $r->setAttribute(1,$condition);
        $r->setAttribute(2,$hourCob);
        $r->setAttribute(3,":");
        $r->setAttribute(4,$minCob);
        $r->setAttribute(5,new Button("saveEditWochentagTerm", " Speichern "));
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("editTerm", $_REQUEST['editTerm']));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("editWochentag", "ok"));
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
    private function checkWochentagTermEditorMask($update=true){
        if (isset($_REQUEST['saveEditWochentagTerm']) && isset($_REQUEST['editTerm']) && strlen($_REQUEST['saveEditWochentagTerm'])>0 
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