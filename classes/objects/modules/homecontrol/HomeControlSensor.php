<?PHP

class HomeControlSensor extends Object{
   private $ID = 0;
   private $SENSORNAME = "";
   private $DESCRPT = "";
   private $LASTSIGNAL = 0;
   private $LASTVALUE = 0;
   
   private $WITHHEADER = false;
   private $BGID = 0;
   
   
   function HomeControlSensor($currConfigRow) {
      $this->ID = $currConfigRow->getNamedAttribute("id");
      $this->SENSORNAME = $currConfigRow->getNamedAttribute("name");
      $this->DESCRPT = $currConfigRow->getNamedAttribute("beschreibung");
      $this->LASTSIGNAL = $currConfigRow->getNamedAttribute("lastSignal");
      $this->LASTVALUE = $currConfigRow->getNamedAttribute("lastValue");
      $this->setFonttype(new FontType());
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
   
   
   function show(){
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