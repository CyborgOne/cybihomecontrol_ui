<?php
//FileNAME: LookupCombo.php
 
class LookupCombo extends Object {
  var $CBO;
  var $NAME;

  function LookupCombo($dbConnect, $name, $tab, $col) {
     $a = array();
     $d = "";
     $res = getLookupWerte($dbConnect, $tab, $col);

     while($row = mysql_fetch_array($res)){
       $a[$row['code']] = $row['text'];
       if($row['default']=='J'){
 	     $d = $row['code'];
       }
     }

     $c = new ComboBox($name, $a, $d);
     
     $this->CBO = $c;
  }


  function show(){
     $this->CBO->show();
  }

}

?>
