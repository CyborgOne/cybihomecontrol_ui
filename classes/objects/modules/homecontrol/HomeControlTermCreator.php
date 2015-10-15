<?PHP

class HomeControlTermCreator extends Object {
    private $TYPE         = null;
    private $URL_PARAMS   = "";
    
    private $TYPE_SENSOR_WERT      = 1;
    private $TYPE_SENSOR_STATUS    = 2;
    private $TYPE_ZEIT             = 3;
    private $TYPE_WOCHENTAG        = 4;
    
    private $TRIGGER_ID            = 0;
    private $TRIGGER_SUBID         = 0;
    private $TRIGGER_TYPE          = 0;

    function HomeControlTermCreator($triggerId, $triggerSubid, $triggerType, $additionalUrlParams=""){
        $this->URL_PARAMS = $additionalUrlParams;

        $this->TRIGGER_ID = $triggerId;
        $this->TRIGGER_SUBID = $triggerSubid;
        $this->TRIGGER_TYPE = $triggerType;
        
        $this->checkRequests();
        
        $this->checkSensorWertTermCreatorMask();
        $this->checkSensorStatusTermCreatorMask();
        $this->checkTimeTermCreatorMask();
        $this->checkWochentagTermCreatorMask();
    }
    
    
    
    private function getTypeChooseDialog(){
        $sensorWertOption    = new Div("createSensorWert", 250, 40);
        $sensorWertOption->setToolTip("Sensoren die einen Messwert liefern<br><br>z.b. <br>- Helligkeitssensor<br>- Temperatursensor<br>- Abstandssensor");
        $sensorStatusOption  = new Div("createSensorStatus", 250, 40);
        $sensorStatusOption->setToolTip("Sensoren die nur 1 oder 0 als Wert liefern<br><br>z.b. <br>- Bewegungsmelder<br>- Regensensor<br>- Lichtschranke");
        $zeitOption          = new Div("createZeit", 250, 40);
        $wochentagOption     = new Div("createWochentag", 250, 40);
        
        $sensorWertOption->setStyle("line-height", "40px");
        $sensorStatusOption->setStyle("line-height", "40px");
        $zeitOption->setStyle("line-height", "40px");
        $wochentagOption->setStyle("line-height", "40px");
        
        $sensorWertOption->setAlign("center");
        $sensorWertOption->setVAlign("middle");
        
        $sensorStatusOption->setAlign("center");
        $sensorStatusOption->setVAlign("middle");
        
        $zeitOption->setAlign("center");
        $zeitOption->setVAlign("middle");
        
        $wochentagOption->setAlign("center");
        $wochentagOption->setVAlign("middle");
        
        $sensorWertOption->add(new Text("Sensor Wert", 4, true));
        $sensorStatusOption->add(new Text("Sensor Status", 4, true));
        $zeitOption->add(new Text("Uhrzeit", 4, true));
        $wochentagOption->add(new Text("Wochentag", 4, true));

        $sensorWertOption->setBackgroundColor($_SESSION['config']->COLORS['button_background']);
        $sensorStatusOption->setBackgroundColor($_SESSION['config']->COLORS['button_background']);
        $zeitOption->setBackgroundColor($_SESSION['config']->COLORS['button_background']);
        $wochentagOption->setBackgroundColor($_SESSION['config']->COLORS['button_background']);

        $sensorWertLink     = new Link("?createSensorWert=ok".($this->URL_PARAMS!=""?"&".$this->URL_PARAMS:"")."#createSensorWert", $sensorWertOption,false,"","",true, false);
        $sensorStatusLink   = new Link("?createSensorStatus=ok".($this->URL_PARAMS!=""?"&".$this->URL_PARAMS:"")."#createSensorStatus", $sensorStatusOption,false,"","",true, false);
        $zeitLink           = new Link("?createZeit=ok".($this->URL_PARAMS!=""?"&".$this->URL_PARAMS:"")."#createZeit", $zeitOption,false,"","",true, false);
        $wochentagLink      = new Link("?createWochentag=ok".($this->URL_PARAMS!=""?"&".$this->URL_PARAMS:"")."#createWochentag", $wochentagOption,false,"","",true, false);

        
        $t = new Table(array("", "", ""));
        $t->setColSizes(array(null, 5, null));
        $t->setAlignments(array("center", "center", "center"));
        
        $r = $t->createRow();
        $r->setSpawnAll(true);
        $r->setAttribute(0, new Title("Art der neuen Bedingung auswaehlen", true, 4));
        $t->addRow($r);
        
        $t->addSpacer(0,5);

        $r1 = $t->createRow();
        $r1->setAttribute(0,$sensorStatusLink);
        $r1->setAttribute(1,"");
        $r1->setAttribute(2,$sensorWertLink);
        $t->addRow($r1);
        
        $t->addSpacer(0,5);
        
        $r2 = $t->createRow();
        $r2->setAttribute(0,$zeitLink);
        $r2->setAttribute(1,"");
        $r2->setAttribute(2,$wochentagLink);
        $t->addRow($r2);
        
        return $t;
    }


    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getSensorWertTermCreatorMask(){
        $div = new Div ("createSensorWert");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition");
        $sensorCbo = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_sensor WHERE status_sensor!='J'", "sensor");
        $wertTxt   = new Textfield("value","",9,9);
        
        $t = new Table(array("","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Sensorwert");
        $r->setAttribute(1,$sensorCbo);
        $r->setAttribute(2,$condition);
        $r->setAttribute(3,$wertTxt);
        $r->setAttribute(4,new Button("saveCreateSensorWertTerm", "Bedingung hinzufuegen"));
        $r->setAttribute(5,"");
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("dbTableNewhomecontrol_term", "Neuen Eintrag"));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("createSensorWert", "ok"));
        $t->addRow($rH2);
        
        $div->add($t);
        
        return $div;
    }
    
    /**
     * Prüfen ob TimeTermCreator angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkSensorWertTermCreatorMask($insert=true){
        if (isset($_REQUEST['saveCreateSensorWertTerm']) && strlen($_REQUEST['saveCreateSensorWertTerm'])>0 
          && strlen($_REQUEST['sensor'])>0 && strlen($_REQUEST['value'])>0 && strlen($_REQUEST['condition'])>0 ){
            $orderNr = 1;
            $andOr    = "and";            
            
            $sqlInsert = "INSERT INTO homecontrol_term (trigger_id, trigger_subid, trigger_type, term_type, value, termcondition, sensor_id, order_nr, and_or) " 
                        ."VALUES (" .$this->TRIGGER_ID .", " .$this->TRIGGER_SUBID .", " .$this->TRIGGER_TYPE .", 1" 
                        .", '" .$_REQUEST['value'] ."', '" .$_REQUEST['condition'] ."', " .$_REQUEST['sensor'] .", " .$orderNr .", '" .$andOr ."' )";
            if($insert){              
              $_SESSION['config']->DBCONNECT->executeQuery($sqlInsert);
              $this->TYPE = null;
              $_REQUEST['saveCreateSensorWertTerm'] = null;
            }

            return false;
        }
        return true;
    }
    
    
      /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getSensorStatusTermCreatorMask(){
        $div = new Div ("createSensorStatus");
        
        $statusCbo = new Checkbox("status");
        $sensorCbo = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_sensor  WHERE status_sensor='J'", "sensor");
        
        $t = new Table(array("","","","","",""));
        $t->setAlignments(array("","","","","","right"));
        $r = $t->createRow();
        $r->setAttribute(0, "Sensorstatus");
        $r->setAttribute(1, $sensorCbo);
        $r->setAttribute(2, "=");
        $r->setAttribute(3, $statusCbo);
        $r->setAttribute(4, "");
        $r->setAttribute(5, new Button("saveCreateSensorStatusTerm", "Bedingung hinzufuegen"));
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("dbTableNewhomecontrol_term", "Neuen Eintrag"));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("createSensorStatus", "ok"));
        $t->addRow($rH2);
        
        $div->add($t);
        
        return $div;
    }
    

    /**
     * Prüfen ob TimeTermCreator angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkSensorStatusTermCreatorMask($insert=true){
        if (isset($_REQUEST['saveCreateSensorStatusTerm']) && strlen($_REQUEST['saveCreateSensorStatusTerm'])>0 
          && strlen($_REQUEST['status'])>0 && strlen($_REQUEST['sensor'])>0  ){
            $orderNr = 1;
            $andOr    = "and";            
            
            $sqlInsert = "INSERT INTO homecontrol_term (trigger_id, trigger_subid, trigger_type, term_type, status, sensor_id, order_nr, and_or) " 
                        ."VALUES (" .$this->TRIGGER_ID .", " .$this->TRIGGER_SUBID .", " .$this->TRIGGER_TYPE .", 2, '" 
                        .$_REQUEST['status'] ."', " .$_REQUEST['sensor'] .", " .$orderNr .", '" .$andOr ."' )";
            if($insert){                        
              $_SESSION['config']->DBCONNECT->executeQuery($sqlInsert);
              $this->TYPE = null;
              $_REQUEST['saveCreateSensorStatusTerm'] = null;
            }

            return false;
        }
        return true;
    }
    
  
  
    
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getTimeTermCreatorMask(){
        $div = new Div ("createZeit");
        
        $condition = new ComboBoxBySql($_SESSION['config']->DBCONNECT, 
                         "SELECT value, name FROM homecontrol_condition ", 
                         "condition");
        $hourCob = new Combobox("stunde", getNumberComboArray(0,24), 12, " ");
        $minCob  = new Combobox("minute", getNumberComboArray(0,60), 30, " ");
        
        $t = new Table(array("","","","","","",""));
        $r = $t->createRow();
        $r->setAttribute(0,"Uhrzeit");
        $r->setAttribute(1,$condition);
        $r->setAttribute(2,$hourCob);
        $r->setAttribute(3,":");
        $r->setAttribute(4,$minCob);
        $r->setAttribute(5,new Button("saveCreateTimeTerm", "Zeit-Bedingung hinzufuegen"));
        $r->setAttribute(6,"");
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("dbTableNewhomecontrol_term", "Neuen Eintrag"));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("createZeit", "ok"));
        $t->addRow($rH2);
        
        $div->add($t);
        
        return $div;
    }
    
    
    
    /**
     * Prüfen ob TimeTermCreator angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkTimeTermCreatorMask($insert=true){
        if (isset($_REQUEST['saveCreateTimeTerm']) && strlen($_REQUEST['saveCreateTimeTerm'])>0 
          && strlen($_REQUEST['stunde'])>0 && strlen($_REQUEST['minute'])>0 && strlen($_REQUEST['condition'])>0 ){
echo "saveCreateTimeTerm<br>";
            $orderNr = 1;
            $andOr    = "and";            
            
            $sqlInsert = "INSERT INTO homecontrol_term (trigger_id, trigger_subid, trigger_type, term_type, min, std, termcondition, order_nr, and_or) " 
                        ."VALUES (" .$this->TRIGGER_ID .", " .$this->TRIGGER_SUBID .", " .$this->TRIGGER_TYPE .", 3, " 
                        .$_REQUEST['minute'] .", " .$_REQUEST['stunde'] .", '" .$_REQUEST['condition'] ."', " .$orderNr .", '" .$andOr ."' )";
            if($insert){   
                echo $sqlInsert;
              $_SESSION['config']->DBCONNECT->executeQuery($sqlInsert);
              $this->TYPE = null;
              $_REQUEST['saveCreateTimeTerm'] = null;
            }
            return false;
        }
        return true;
    }
    
    
    /**
     * Maske um Urhzeit incl. Bedingung (<>=) zu erzeugen
     */
    private function getWochentagTermCreatorMask(){
        $div = new Div ("createWochentag");
        
        $cboMo = new Checkbox("montag");
        $cboDi = new Checkbox("dienstag");
        $cboMi = new Checkbox("mittwoch");
        $cboDo = new Checkbox("donnerstag");
        $cboFr = new Checkbox("freitag");
        $cboSa = new Checkbox("samstag");
        $cboSo = new Checkbox("sonntag");
        
        $t = new Table(array("Mo","Di","Mi","Do","Fr","Sa","So", ""));
        $t->setAlign("center");
        $r = $t->createRow();
        $r->setAttribute(0,"Montag");
        $r->setAttribute(1,"Dienstag");
        $r->setAttribute(2,"Mittwoch");
        $r->setAttribute(3,"Donnerstag");
        $r->setAttribute(4,"Freitag");
        $r->setAttribute(5,"Samstag");
        $r->setAttribute(6,"Sonntag");
        $t->addRow($r);
        
        $r = $t->createRow();
        $r->setAttribute(0,$cboMo);
        $r->setAttribute(1,$cboDi);
        $r->setAttribute(2,$cboMi);
        $r->setAttribute(3,$cboDo);
        $r->setAttribute(4,$cboFr);
        $r->setAttribute(5,$cboSa);
        $r->setAttribute(6,$cboSo);
        $r->setAttribute(7,new Button("saveCreateWochentagTerm", "Bedingung hinzufuegen"));
        $t->addRow($r);
        
        $rH = $t->createRow();
        $rH->setSpawnAll(true);
        $rH->setAttribute(0, new Hiddenfield("dbTableNewhomecontrol_term", "Neuen Eintrag"));
        $t->addRow($rH);
                
        $rH2 = $t->createRow();
        $rH2->setSpawnAll(true);
        $rH2->setAttribute(0, new Hiddenfield("createWochentag", "ok"));
        $t->addRow($rH2);
        
        $div->add($t);
        
        return $div;
    }
    
    
    /**
     * Prüfen ob TimeTermCreator angezeigt werden muss
     * 
     * Wenn Eingabe OK return false 
     * 
     * TODO: REQUEST und SESSION-Variablen als Parameter
     */
    private function checkWochentagTermCreatorMask($insert=true){
        if (isset($_REQUEST['saveCreateWochentagTerm']) && strlen($_REQUEST['saveCreateWochentagTerm'])>0 
          && ( strlen($_REQUEST['montag'])>0 || 
               strlen($_REQUEST['dienstag'])>0 || 
               strlen($_REQUEST['mittwoch'])>0 || 
               strlen($_REQUEST['donnerstag'])>0 || 
               strlen($_REQUEST['freitag'])>0 || 
               strlen($_REQUEST['samstag'])>0 || 
               strlen($_REQUEST['sonntag'])>0 )){
            $orderNr = 1;
            $andOr    = "and";            
            
            $sqlInsert = "INSERT INTO homecontrol_term (trigger_id, trigger_subid, trigger_type, term_type, montag, dienstag, mittwoch, donnerstag, freitag, samstag, sonntag, order_nr, and_or) " 
                        ."VALUES (" .$this->TRIGGER_ID .", " .$this->TRIGGER_SUBID .", " .$this->TRIGGER_TYPE .", 4"
                        .", '".$_REQUEST['montag'] ."', '" .$_REQUEST['dienstag'] ."', '" .$_REQUEST['mittwoch'] ."', '" .$_REQUEST['donnerstag'] 
                        ."', '" .$_REQUEST['freitag']  ."', '" .$_REQUEST['samstag'] ."', '" .$_REQUEST['sonntag'] ."', " .$orderNr .", '" .$andOr ."' )";
            if($insert){                        
              $_SESSION['config']->DBCONNECT->executeQuery($sqlInsert);
              $this->TYPE = null;
              $_REQUEST['saveCreateWochentagTerm'] = null;
            }
            return false;
        }
        return true;
    }
    

    
    
    /**
     * $_REQUEST Werte prüfen 
     */
    private function checkRequests(){
        if (isset($_REQUEST['createSensorWert']) && $_REQUEST['createSensorWert']=="ok"){
            $this->TYPE = $this->TYPE_SENSOR_WERT;
        }
        if (isset($_REQUEST['createSensorStatus']) && $_REQUEST['createSensorStatus']=="ok"){
            $this->TYPE = $this->TYPE_SENSOR_STATUS;
        }
        if (isset($_REQUEST['createZeit']) && $_REQUEST['createZeit']=="ok"){
            $this->TYPE = $this->TYPE_ZEIT;
        }
        if (isset($_REQUEST['createWochentag']) && $_REQUEST['createWochentag']=="ok"){
            $this->TYPE = $this->TYPE_WOCHENTAG;
        }                
    }
    
    
    /**
     * Standard Anzeige-Methode
     */
    public function show(){
        
        $t = new Text("");
        
        if($this->TYPE!=null){

          switch ($this->TYPE){
             case $this->TYPE_SENSOR_WERT:
               if($this->checkSensorWertTermCreatorMask(false)){
                 $t = $this->getSensorWertTermCreatorMask();
               }
               break;

             case $this->TYPE_SENSOR_STATUS:
               if($this->checkSensorStatusTermCreatorMask(false)){
                 $t = $this->getSensorStatusTermCreatorMask();
               }
               break;

             case $this->TYPE_ZEIT:
               if($this->checkTimeTermCreatorMask(false)){
                 $t = $this->getTimeTermCreatorMask();
               }
               break;

             case $this->TYPE_WOCHENTAG:
               if($this->checkWochentagTermCreatorMask(false)){
                 $t = $this->getWochentagTermCreatorMask();
               }
               break;
          }

        } else { 
            $t = $this->getTypeChooseDialog();
        }
        
        $t->show();
    }
}

?>