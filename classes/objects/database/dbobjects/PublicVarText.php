<?php
//FileNAME: PublicVarText.php
 
class PublicVarText extends Object {
  var $TBL;
  var $BORDER;

  function PublicVarText($dbConnect, $group, $name) {
     $this->BORDER = 0;
     $a = getPublicVarArray($dbConnect, $group, $name);
     $t = new Table(array(""));

     $t->setBorder($this->BORDER);

     foreach($a as $title=>$text){
       $r1 = $t->createRow();
       $r1->setAttribute(0, new Title($title));
       $r1->setHeader(true);

       $t->addRow($r1);

       $r2 = $t->createRow();
       $r2->setAttribute(0, $text);
	   $t->addRow($r2);
      
       $t->addSpacer(); 
     }     
    
     $this->TBL = $t;
  }


  function setBorder($b){
    $this->TBL->setBorder($b);
    $this->BORDER = $b;
  }

  function setWidth($b){
    $this->TBL->setWidth($b);
    $this->WIDTH = $b;
  }
  
  function setHeight($b){
    $this->TBL->setHeight($b);
    $this->HEIGHT = $b;
  }

  function setSpacing($b){
    $this->TBL->setSpacing($b);
    $this->SPACING = $b;
  }

  function setPadding($b){
    $this->TBL->setPadding($b);
    $this->PADDING = $b;
  }

  function show(){
     $this->TBL->show();
  }

}

?>
