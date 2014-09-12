<?php
  /*
  $fTbl = new Table(array(""));
  $fRow = $fTbl->createRow();
  
  $cntr = new Counter();
  $fRow->setAttribute(0, $cntr);

  $fTbl->addRow($fRow);
  $fTbl->setWidth(940);
  $fTbl->show();
  */

  $t = new Text("Arduino URL: ".$_SESSION['config']->PUBLICVARS['arduino_url'], 2, false, true, false);
  $t->show();
  
  echo "</body>
  
    </html>
  ";

?>