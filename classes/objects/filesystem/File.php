<?php

class File{
  var $FOLDER;  // Verzeichniss in dem die Datei liegt
  var $NAME;    // Dateiname mit Endung
  var $CONTENT;

  function File($fullPath){
     $this->FOLDER = dirname($fullPath);
     $this->NAME = basename($fullPath);

     $handle = fopen ($fullPath, "r");
     while (!feof($handle)) {
     	
       $this->CONTENT[count($this->CONTENT)] = fgets($handle);
     }
     fclose ($handle);
  }
  
  
  function getRowCount(){
    return count($this->CONTENT);	
  }

  function getCsvValue($rowIndex, $colIndex, $separator=","){
 	if($rowIndex > 0 && $rowIndex <= $this->getRowCount()){
		$r = explode($separator, $this->CONTENT[$rowIndex]);
		echo $r[$colIndex];
		return $r[$colIndex];
	}
    return "";
  }
  

  function getRow($rowIndex){
 	if($rowIndex >= 0 && $rowIndex < $this->getRowCount()){
		return $this->CONTENT[$rowIndex];
	}
    return "";
  }
  
}

?>