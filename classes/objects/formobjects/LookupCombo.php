<?php
//FileNAME: LookupCombo.php
 
class LookupCombo extends Object {
  var $CBO;
  var $NAME;

  function LookupCombo($dbConnect, $name, $tab, $col, $default=null) {
     $a = array();
     $d = $default;
     $res = getLookupWerte($dbConnect, $tab, $col);

     while($row = mysql_fetch_array($res)){
       $a[$row['code']] = $row['text'];
       if($row['default']=='J' && $default==null){
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
