<?php

/**
 * @author 
 * @copyright 2008
 */

class Line extends Object {
	
	function Line($height=1,$spacing=7){
	  $this->HEIGHT = $height;
	  $this->setSpacing($spacing);
	}
	
	function show(){
	  if($this->HEIGHT>0){	
		echo "<hr style=\" height:" .$this->HEIGHT ."px; " 
		                ." margin-bottom: " .$this->getSpacing() ."px; margin-top:" .$this->getSpacing() ."px; "
						." \"></hr>";
	  } else {
	    echo " ";	
	  }
	}
}

?>