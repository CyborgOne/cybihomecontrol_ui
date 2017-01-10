<?php

/**
 * @author  Daniel Scheidler
 * @copyright Mai 2008
 */

class Paragraph extends Container{
  
   function Paragraph(){
   }
   
   
   function show(){
	 echo "<p>";
	 
	 foreach($this->OBJECTS as $obj){
       if(method_exists($obj, "show")){  
         $obj->show();
       }
     }
     
     echo "</p>";
   } 	



}

?>