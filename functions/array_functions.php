<?PHP
/**
* 
* @author Daniel Scheidler 
* @copyright März 2009
*/




    function existsInArray($wert, $array, $absolute=true){
      //Prüft ob Wert im übergebenen Array vorkommt
      if(existsKeyInArray($wert,$array, $absolute) || existsValueInArray($wert,$array, $absolute)){
		return true;
	  }
	  
	  return false;
    }
      
      
      
    
    function existsKeyInArray($wert, $array, $absolute=true ){
      //Prüft ob Wert im übergebenen Array vorkommt
      if(count($array)<=0){
        return false;
      }

      foreach ($array as $key=>$value){
	    if ($absolute && $key == $wert){
	      return true;
		       
        } else if (!$absolute && strpos($key, $wert)>0){
	      return true;     
        }
      }

      return false;	
    }




    function existsValueInArray($wert, $array, $absolute=true ){
      //Prüft ob Wert im übergebenen Array vorkommt
 
     foreach ($array as $key=>$value){
	    if ($absolute && $value == $wert){
	      return true;
		       
        } else if (!$absolute && strpos($value, $wert)>0){
	      return true;     
        }
      }
      
      return false;	
    }
      
    
?>