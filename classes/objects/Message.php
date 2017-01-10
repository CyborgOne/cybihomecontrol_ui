<?php
//FileNAME: Message.php

class Message {
  function Message($title, $text, $backLink=''){

    $t = " <center><font color='red' size='3' ><b>" 
                  .$text 
           ."</b></font><center>";
    if(strlen($backLink)>0){
      $t .= " <br><br><a target ='_top' href='" .$backLink ."'>ZurÃÂÃÂÃÂÃÂ¼ck</a>";
    }

    $title = array("".$title);
    $text  = new Text($t);
    $text->setFilter(false);

    $tbl = new Table($title);
    $tbl->setWidth(500);
    $r = $tbl->createRow();
    $r->setAttribute(0,$text);
    $tbl->addRow($r);
//    $tbl->setXpos(300);
//    $tbl->setYpos(0);
    $tbl->show();
  }

}

?>