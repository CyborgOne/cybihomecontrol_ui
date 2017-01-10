<?php
//FileNAME: Error.php

class Error {
  function Error($title, $text, $backLink=''){

    $t = "<font color='red' size='3' ><b>" 
                  .$text 
           ."</b></font>";
    if(strlen($backLink)>0){
      $t .= " <br><br><a target ='_top' href='" .$backLink ."'>ZurÃÂÃÂÃÂÃÂ¼ck</a>";
    }
    $text = new Text($t);
    $text->setFilter(false);
    $title = array("FEHLER: ".$title);

    $tbl = new Table($title);
    $r = $tbl->createRow();
    $r->setAttribute(0,$text);
    $tbl->addRow($r);
    $tbl->setWidth(500);
    $tbl->show();
    exit();
  }

}

?>