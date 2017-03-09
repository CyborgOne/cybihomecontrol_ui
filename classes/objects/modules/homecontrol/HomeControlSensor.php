<?PHP

class HomeControlSensor extends Object{
   private $ID = 0;
   private $X = 0;
   private $Y = 0;
   private $ETAGE = 0;
   private $ZIMMER = 0;
   
   private $SENSORNAME = "";
   private $DESCRPT = "";
   private $LASTSIGNAL = 0;
   private $LASTVALUE = 0;
   
   private $SENSOR_ART = 1;
   
   private $WITHHEADER = false;
   private $BGID = 0;

   private $PIC = 'pics/Sensor.png';
 
   private $EDIT_MODE = false;
   
   private $CONTROL_IMAGE_WIDTH = 26;
   private $CONTROL_IMAGE_HEIGHT = 26;
   
   private $ICON_VIEW_ACTIVE = false;
    
   private $SENSOR_ROW;

   function HomeControlSensor($currConfigRow, $editModus=false) {
      $this->SENSOR_ROW = $currConfigRow;
      
      $this->ID = $currConfigRow->getNamedAttribute("id");
      $this->NAME = $currConfigRow->getNamedAttribute("name");
      $this->X = $currConfigRow->getNamedAttribute("x");
      $this->Y = $currConfigRow->getNamedAttribute("y");
      $this->ETAGE = $currConfigRow->getNamedAttribute("etage");
      $this->ZIMMER = $currConfigRow->getNamedAttribute("zimmer");
      $this->SENSORNAME = $currConfigRow->getNamedAttribute("name");
      $this->DESCRPT = $currConfigRow->getNamedAttribute("beschreibung");
      $this->LASTSIGNAL = $currConfigRow->getNamedAttribute("lastSignal");
      $this->LASTVALUE = $currConfigRow->getNamedAttribute("lastValue");
      $this->setFonttype(new FontType());
      $this->SENSOR_ART = $currConfigRow->getNamedAttribute("sensor_art");
      
      $this->PIC = $this->getSensorArtImg();
      
      $this->EDIT_MODE = $editModus;
   }
   
   function getSensorArtImg(){
     $ret = getDbValue("homecontrol_sensor_arten", "pic", "id=".$this->SENSOR_ART);
     if(strlen($ret)==0){
        $ret = 'pics/Sensor.png';
     }
     
     return $ret;
   }
   
   
   function setWithHeader($b){
      $this->WITHHEADER = $b;
   }
   
   function isWithHeader(){
      return $this->WITHHEADER;
   }
   
   
   function setBgId($i){
      $this->BGID = $i;
   }
   
   function setId($i){
      $this->ID = $i;
   }
   
   function getId(){
      return $this->ID;
   }
   
   function setSensorname($s){
      $this->SENSORNAME = $s;
   }
   
   function getSensorname(){
      return $this->SENSORNAME;
   }
      
   function getLastValue(){
      return $this->LASTVALUE;
   }
         
   function getControlImageHeight(){
      return $this->CONTROL_IMAGE_HEIGHT;
   }
         
   function getControlImageWidth(){
      return $this->CONTROL_IMAGE_WIDTH;
   }
   
   function setX($x){
      $this->X = $x;
   }
   
   function getX(){
      return $this->X;
   }
   
   function setY($y){
      $this->Y = $y;
   }
   
   function getY(){
      return $this->Y;
   }
   
  /**
   *  Liefert das Grafik-Symbol zurück (Image),
   *  welches zur Sensor-Art passt.
   */
   function getSensorArtIconSrc($tooltip=true,$width=0) {
        $lnkImg = new Image($this->PIC);
        $lnkImg->setWidth($width==0?$this->CONTROL_IMAGE_WIDTH:$width);

        if ($tooltip) {
            $ttt = $this->getIconTooltip();
            $lnkImg->setToolTip($ttt);
        }
        
        $lnkImgSrc = $lnkImg->getImgSrc($this->PIC);

        return $lnkImgSrc;
   }


   function showAsIcon(){
        if ($this->EDIT_MODE) {
            echo "<a href=\"?editSensorControl=" . $this->ID . "\" style=\"position:absolute; left:" .
                $this->X . "px; top:" . ($this->Y + $_SESSION['additionalLayoutHeight']) .
                "px; width:" . $this->CONTROL_IMAGE_WIDTH . "px; height:" . $this->
                CONTROL_IMAGE_HEIGHT . "px;\">";
            echo $this->getSensorArtIconSrc();
            
            echo "</a>";
        
            if($this->LASTVALUE!=null){
              echo "<div style=\"background-color:#dedede; position:absolute; left:" .$this->X ."px; top:" . ($this->Y + $_SESSION['additionalLayoutHeight'] + ($this->CONTROL_IMAGE_HEIGHT/2)) ."px;\"><center><b>".$this->LASTVALUE."</b></center></div>";   
            }
            
        } else {
            echo "<div style=\"position:absolute; left:" . $this->X . "px; top:" . ($this->
                Y + $_SESSION['additionalLayoutHeight']) . "px; width:" . $this->
                CONTROL_IMAGE_WIDTH . "px; height:" . $this->CONTROL_IMAGE_HEIGHT . "px;\">";
            
            echo $this->getSensorArtIconSrc();
            //$this->getSwitchButtons()->show();     
            echo "</div>";
            if($this->LASTVALUE!=null){
              echo "<div style=\"background-color:#dedede; position:absolute; left:" .($this->X + 3) ."px; top:" . ($this->Y + $_SESSION['additionalLayoutHeight'] + ($this->CONTROL_IMAGE_HEIGHT/2)) ."px;\"><center><b>".$this->LASTVALUE."</b></center></div>";   
            }        
        }
   }
   
   
   function getIconTooltip() {
        $ttt = "<table cellspacing='10'><tr><td>" .$this->getSensorArtIconSrc(false,80) ."</td><td><center><b>" . $this->NAME ."</b></center><hr></br>";
        
        if(strlen($this->ID)>0 ){
            $ttt .= "<b>ID:</b> ".$this->ID."</br></br>";
        }
                
        if(strlen($this->ZIMMER)>0 ){
            $ttt .= "<b>Zimmer:</b> ".getDbValue("homecontrol_zimmer", "name", "id=".$this->ZIMMER)."</br></br>";
        }
        
        if(strlen($this->ETAGE)>0 ){
            $ttt .= "<b>Etage:</b> ".getDbValue("homecontrol_etagen", "name", "id=".$this->ETAGE)."</br>";
        }
        
        $ttt .= "</br>" .$this->DESCRPT ."</td></tr>"; 

        if(strlen($this->LASTVALUE)>0 && strlen($this->LASTSIGNAL)>0){    
            $ttt .= "<tr><td>Zu letzt gemeldeter Wert: " .$this->LASTVALUE ."</td><td align='right'>gemeldet am: " .date("d.m.Y h:i:s", $this->LASTSIGNAL) ."</td></tr>";
        }
        
        $ttt .= "<tr><td colspan=2 height='1px'> </td></tr>";  
        $ttt .= "</table>";

        return $ttt;
   }
   
   
   function setIconViewActive($active){
     $this->ICON_VIEW_ACTIVE = $active===true;
   }
   
   function isIconViewActive(){
     return $this->ICON_VIEW_ACTIVE;
   }
   
   function deleteSensor(){
     $this->SENSOR_ROW->deleteFromDb();
   }
   
   
   function show(){
     if($this->isIconViewActive()){
        $this->showAsIcon();
        return;
     }
    
     $active = true;
     if ((time()-(24*60*60*1000)) > $this->LASTSIGNAL  ){
        $active = false;
     }
    
     $tbl = new Table(array("Name", "ID", "letztes Signal", "letzter Wert"));
     $tbl->setBackgroundColorChange(false);
     $tbl->setHeadEnabled($this->isWithHeader());
     $tbl->setColSizes(array(null,100,150,120));
     $tbl->setStyle("padding-left", "5px");
     $tbl->setStyle("padding-right", "25px");
     $tbl->setStyle("padding-top", "5px");
     $tbl->setStyle("padding-bottom", "5px");
     $tbl->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_'.(($this->BGID%2)==0?"1":"2")]);
     $r = $tbl->createRow();
     $r->setAlignments(array("left", "left", "left", "right"));
     $r->setAttribute(0, new Text($this->SENSORNAME,3));
     $r->setAttribute(1, new Text($this->ID,3));
     $r->setAttribute(2, $active?new Text(date("D d.m.Y H:i:s",$this->LASTSIGNAL),3):"-");
     $r->setAttribute(3, $active?new Text($this->LASTVALUE,3):"-");
     $tbl->addRow($r);
     $tbl->show();
   }
}


?>