<?PHP

$ttl = new Title("Sensor Logs");

$cboSensorSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, concat(name ,' (', id  ,')') descr FROM homecontrol_sensor", "sensorSelectCob", $_REQUEST['sensorSelectCob']);
$cboSensorSelect->setDirectSelect(true);
$cboSensorSelect->setNullValue(" ");

$txtSelect = new Text("Sensor Auswahl:");

$frmSelect = new Form();
$frmSelect->add($cboSensorSelect);


// -------------------------
// Show
// -------------------------

$tbl = new Table(array("", ""));
$tbl->setColSizes(array(130));

$rTtl = $tbl->createRow();
$rTtl->setSpawnAll(true);
$rTtl->setAttribute(0, $ttl);
$tbl->addRow($rTtl);

$tbl->addSpacer(0,30);

$rSelect = $tbl->createRow();
$rSelect->setAttribute(0, $txtSelect);
$rSelect->setAttribute(1, $frmSelect);
$tbl->addRow($rSelect);

$tbl->addSpacer(0,30);



if(isset($_REQUEST['sensorSelectCob']) && strlen($_REQUEST['sensorSelectCob'])>0){
  $imgTxt = new Text("<img src='includes/pictures/sensorLogGraphs.inc.php?width=800&height=350&sensorId=" .$_REQUEST['sensorSelectCob'] ."'>");
  $imgTxt->setFilter(false);

  $rGraph = $tbl->createRow();
  $rGraph->setSpawnAll(true);
  $rGraph->setAttribute(0, $imgTxt);
  $tbl->addRow($rGraph);
}

$tbl->show();

?>