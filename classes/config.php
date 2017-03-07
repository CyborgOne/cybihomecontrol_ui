<?php
  /*
     ------------------------------------------------------------
	GRUND-KONFIGURATIONS-DATEI
     ------------------------------------------------------------
	Hier werden alle Globalen Grundeinstellungen bereitgehalten!
     ------------------------------------------------------------
  */
class Config 
{
    var $DBCONNECT;
    var $CURRENTUSER;
    var $LOG;
    var $News_CONFIG;
 
//-------------------------
// PUBLIC_VARS
//-------------------------
    var $PUBLICVARS;
    var $COLORS;
    var $GB_CONFIG;
    var $ACTION;
    var $MAINPATH;
    
    var $IMAGECACHE;
    var $CACHED_IMAGES = array();
    var $SENDER = array();
    var $SENSOR = array();
    var $ITEMS  = array();    
    var $ETAGEN = array();
    var $ZIMMER = array();
//-------------------------

    function Config(){
    }

    function getItemById($id){
        if(!isset($this->ITEMS[$id]) || $this->ITEMS[$id]==null){
             $itemsDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_config",array('*'),"","","","id=".$id);
             foreach($itemsDbTbl->ROWS as $rowItem){
                 $item = new HomeControlItem($rowItem);
                 $this->ITEMS[$rowItem->getNamedAttribute("id")] = $item;
             }
        }
        
        $this->ITEMS[$id]->loadParams();
        return $this->ITEMS[$id];
    }

    function getItemByRow($row){
        if(!isset($this->ITEMS[$row->getNamedAttribute("id")]) || $this->ITEMS[$row->getNamedAttribute("id")]==null){
             $item = new HomeControlItem($row);
             $this->ITEMS[$row->getNamedAttribute("id")] = $item;
        }
        
        $this->ITEMS[$row->getNamedAttribute("id")]->loadParams();
        return $this->ITEMS[$row->getNamedAttribute("id")];
    }

    function getSenderById($id){
        if(!isset($this->SENDER[$id]) || $this->SENDER[$id]==null){
            $senderDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sender",array('*'),"","","","id=".$id);
            foreach($senderDbTbl->ROWS as $rowSender){
                $sender = new HomeControlSender($rowSender);
                $this->SENDER[$rowSender->getNamedAttribute("id")] = $sender;
            }
        }  
        return $this->SENDER[$id];
    }

    function getSenderByRow($row){
        if(!isset($this->SENDER[$row->getNamedAttribute("id")]) || $this->SENDER[$row->getNamedAttribute("id")]==null){
            $sender = new HomeControlSender($row);
            $this->SENDER[$row->getNamedAttribute("id")] = $sender;
        }  
        return $this->SENDER[$row->getNamedAttribute("id")];
    }


    function getSensorById($id){
        if(!isset($this->SENSOR[$id]) || $this->SENSOR[$id]==null){
            $sensorDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_sensor",array('*'),"","","","id=".$id);
            foreach($sensorDbTbl->ROWS as $rowSensor){
                $sensor = new HomeControlSensor($rowSensor);
                $this->SENSOR[$rowSensor->getNamedAttribute("id")] = $sensor;
            }
        }  
        return $this->SENSOR[$id];
    }

    function getSensorByRow($row){
        if(!isset($this->SENSOR[$row->getNamedAttribute("id")]) || $this->SENSOR[$row->getNamedAttribute("id")]==null){
            $sensor = new HomeControlSensor($row);
            $this->SENSOR[$row->getNamedAttribute("id")] = $sensor;
        }  
        return $this->SENSOR[$row->getNamedAttribute("id")];
    }

    function getEtageById($id){
        if(!isset($this->ETAGEN[$id]) || $this->ETAGEN[$id]==null){
            $etagenDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_etagen",array('*'),"","","","id=".$id);
            foreach($etagenDbTbl->ROWS as $rowEtage){
                $etage = new HomeControlEtage($rowEtage);
                $this->ETAGEN[$rowEtage->getNamedAttribute("id")] = $etage;
            }
        }  
        return $this->ETAGEN[$id];
    }

    function getEtageByRow($row){
        if(!isset($this->ETAGEN[$row->getNamedAttribute("id")]) || $this->ETAGEN[$row->getNamedAttribute("id")]==null){
            $etage = new HomeControlEtage($row);
            $this->ETAGEN[$row->getNamedAttribute("id")] = $etage;
        }  
        return $this->ETAGEN[$row->getNamedAttribute("id")];
    }

    function getZimmerById($id){
        if(!isset($this->ZIMMER[$id]) || $this->ZIMMER[$id]==null){
            $zimmerDbTbl = new DbTable($_SESSION['config']->DBCONNECT, "homecontrol_zimmer",array('*'),"","","","id=".$id);
            foreach($zimmerDbTbl->ROWS as $rowZimmer){
                $zimmer = new HomeControlZimmer($rowZimmer);
                $this->ZIMMER[$rowZimmer->getNamedAttribute("id")] = $zimmer;
            }
        }  
        return $this->ZIMMER[$id];
    }


    function getZimmerByRow($row){
        if(!isset($this->ZIMMER[$row->getNamedAttribute("id")]) || $this->ZIMMER[$row->getNamedAttribute("id")]==null){
            $zimmer = new HomeControlZimmer($row);
            $this->ZIMMER[$row->getNamedAttribute("id")] = $zimmer;
        }  
        return $this->ZIMMER[$row->getNamedAttribute("id")];
    }



    function getImageFromCache($imagePath){
        if(!isset($this->IMAGECACHE)){
            $this->IMAGECACHE = new ImageCache();
        }
        
        if(!array_key_exists($imagePath, $this->CACHED_IMAGES)){
            echo "Cache: ".$imagePath."<br>";
            $this->CACHED_IMAGES[$imagePath] = $this->IMAGECACHE->cache($imagePath);
        }
        return $this->CACHED_IMAGES[$imagePath];
    }
    
}
?>