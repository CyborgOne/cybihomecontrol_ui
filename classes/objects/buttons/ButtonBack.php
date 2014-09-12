<?php
//FileNAME: ButtonBack.php

class ButtonBack extends Object {
  function ButtonBack(){
  }

  function Show(){
   
       echo "<INPUT TYPE=BUTTON 
                         VALUE='ZurÃÂÃÂÃÂÃÂ¼ck' 
                         onClick='history.back()'";

       if($this->XPOS>=0 && $this->YPOS>=0){        
         echo "style=\"  position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; \" ";
       }

       echo " >";
  }
}

?>