<?php
  /*
  $fTbl = new Table(array("", ""));
  $fRow = $fTbl->createRow();
  
  $cntr = new Counter();
  $fRow->setAttribute(0, $cntr);

  $fTbl->addRow($fRow);
  $fTbl->setWidth(940);
  $fTbl->show();
  */

 // $t = new Text("Arduino URL: ".$_SESSION['config']->PUBLICVARS['arduino_url'], 2, false, true, false);
  $versionInfo = "Status-Info";
  $t = new Link("http://smarthomeyourself.de/statusInfo.php", $versionInfo, false, "status");
  
  
  $versionInfo = "Version: " .file_get_contents('version.txt');;
  $lVersion = new Link("/versionHistory.txt", $versionInfo, false, "versionHist");
    
  $fTbl = new Table(array("", ""));
  $fTbl->setAlignments(array("left","right"));
  $fTbl->setWidth($bannerWidth+15);
  $fRow = $fTbl->createRow();
  $fRow->setAttribute(0,$t);
  $fRow->setAttribute(1,$lVersion);
  $fTbl->addRow($fRow);
  
  $fTbl->show();
  
  echo "</body>
  
    </html>
  ";

?>