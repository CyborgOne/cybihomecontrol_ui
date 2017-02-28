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
    var $ITEMS = array();
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