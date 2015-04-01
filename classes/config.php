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

//-------------------------

    function Config(){
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