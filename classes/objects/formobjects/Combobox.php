<?php
//FileNAME: Combobox.php
  /**
  * Combobox($NAME,$array=array(), $default="")
  */  
class Combobox extends Object {
  public $CBARRAY;
  public $DEFAULT;
  private $DIREKTSELECT;
  public $NULLVALUE_OK;
  

  function Combobox($n, $array=array(), $default="", $nullValue="" ){
     $this->NAME = $n;
     $this->XPOS = -1;
     $this->YPOS = -1;
     $this->CBARRAY = $array;
     $this->DEFAULT = $default;
     $this->NULLVALUE_OK = $nullValue;
  }

  function setNullValue($val){
    $this->NULLVALUE_OK = $val;
  }
  
  function getNullValue(){
    return $this->NULLVALUE_OK;
  }


  function setDataArray($a){
     $this->CBARRAY = $a;
  }
  
  function setDefault($d){
     $this->DEFAULT = $d;
  }
  
  /**
  * Mit DIRECTSELECT wird angegeben ob die Combobox die Form absenden soll oder nicht.
  * wird hier TRUE gesetzt, entspricht das AuswÃÂÃÂÃÂÃÂ¤hlen der Combobox einem Submit auf die Form. 
  */  
  function setDirectSelect($d){
     if($d){
	   $this->DIREKTSELECT = $d;
	 } else {
	   $this->DIREKTSELECT = false;
	 } 
  }
  
  
  /**
  * show-Methode fÃÂÃÂÃÂÃÂ¼r eine Standard Ja/Nein (J/N)Combobox 
  */  
   function showJN(){
    $this->setDataArray(array ( 'J' => 'Ja','N' => 'Nein'));
    $this->show();
  }



  function showTrueFalse(){
    $this->setDataArray(array ( 'true' => 'Ja','false' => 'Nein'));
    $this->show();
  }
 

  function  show(){
	echo " <select id = '" .$this->NAME ."'  NAME=\"" .$this->NAME ."\" size=\"1\" ";
    
    
    
    if($this->DIREKTSELECT){
	 echo " onchange=\"this.form.submit();\" ";	
	}
	
	echo $this->getToolTipTag();
	
	echo  "> ";

    $isselected=false;

    foreach($this->CBARRAY as $KEY=>$VALUE){
      $t = ltrim($KEY);   // fÃÂÃÂÃÂÃÂ¼hrende und abschlieÃÂÃÂÃÂÃÂende Leerzeichen entfernen
      $t = htmlspecialchars( $t, ENT_QUOTES );   // Sonderzeichen austauschen
      $t = strip_tags($t);                       //html & php-Tags entfernen
      $t = stripslashes ($t);                    //ZeichenschÃÂÃÂÃÂÃÂ¼tzende slashes entfernen
   
	  $htmlKEY = $t;
       
      echo "  <option value='" .$htmlKEY ."' ";
      
      if( $KEY == $this->DEFAULT ){
        echo "selected";
	    $isselected="true";
      }
 
       echo ">" .$VALUE ."</option>";  
    }
    
    if ( strlen($this->getNullValue())>0 || count($this->CBARRAY)==0 ){
	    echo "  <option value='' ";
        
        if( '' == $this->DEFAULT ){
            echo "selected";
    	    $isselected="true";
        }
        
        echo ">".$this->getNullValue()."</option>";
          
	}

    echo " </select > ";
  
  }
 
} 

class ComboBoxBySql extends ComboBox {
  var $DBCONNECT;

  function ComboBoxBySql($con, $sql, $NAME="ComboBox", $default="", $valFieldName="", $txtFieldName="", $nullValue=""){
     $array = array();
     $this->NAME = $NAME;
     $this->XPOS = 0; 
     $this->YPOS = 0;
     $this->DBCONNECT = $con;
     $this->DEFAULT = $default;
     $this->NULLVALUE_OK = $nullValue;
     
     if(strlen($valFieldName)>0){ 
       $valFN = $valFieldName; 
     } else {
       $valFN = 0;
     }

     if(strlen($valFieldName)>0){ 
       $txtFN = $txtFieldName;
     } else {
       $txtFN = 1;
     }

     $result = $this->DBCONNECT->executeQuery($sql);

     // ARRAY FÃÂÃÂÃÂÃÂR CB-LISTE AUFBAUEN
     if(mysql_num_fields($result) >= 2){
       while ($row = mysql_fetch_array($result)){ 
	  $array[$row[$valFN]] = $row[$txtFN] ;
       }
     } else if (mysql_num_fields($result)==1){
       while ($row = mysql_fetch_array($result)){ 
	  $array[$row[$valFN]] = $row[$valFN] ;
       }
     } 

     $this->CBARRAY = $array;
  }

}

?>
