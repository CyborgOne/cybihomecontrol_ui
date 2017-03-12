<?PHP

$ttl = new Title("Sensor Logs");
 
$cboSensorSelect = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, concat(name ,' (', id  ,')') descr FROM homecontrol_sensor", "sensorSelectCob", isset($_REQUEST['sensorSelectCob'])?$_REQUEST['sensorSelectCob']:"" );
$cboSensorSelect->setDirectSelect(true);
$cboSensorSelect->setNullValue(" ");

$cobStartTime = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT distinct from_unixtime(update_time, '%d.%m.%Y') as zeit, from_unixtime(update_time, '%d.%m.%Y') as zeit2 FROM homecontrol_sensor_log", "startTimeCob", isset($_REQUEST['startTimeCob'])?$_REQUEST['startTimeCob']:"");
$cobStartTime->setDirectSelect(true);
$cobStartTime->setNullValue(" ");

$sqlEndCob = "SELECT distinct from_unixtime(update_time, '%d.%m.%Y') as zeit, from_unixtime(update_time, '%d.%m.%Y') as zeit2 FROM homecontrol_sensor_log";
if(isset($_REQUEST['startTimeCob'])&&strlen($_REQUEST['startTimeCob'])>0){
    $sqlEndCob .= " WHERE update_time >= ".strtotime(substr($_REQUEST['startTimeCob'],8,2)."-".substr($_REQUEST['startTimeCob'],3,2)."-".substr($_REQUEST['startTimeCob'],0,2));
}
$cobEndTime = new ComboBoxBySql($_SESSION['config']->DBCONNECT, $sqlEndCob, "endTimeCob", isset($_REQUEST['endTimeCob'])?$_REQUEST['endTimeCob']:"" );
$cobEndTime->setDirectSelect(true);
$cobEndTime->setNullValue(" ");

$txtSelect = new Text("Sensor Auswahl:");

$btnDeleteData = new Button("deleteSensorData", "Sensordaten entfernen");

$frmSelect = new Form();

$timeSelect = new Table(array("","", "",""));
$rT = $timeSelect->createRow();
$rT->setColSizes(array(130,100,63));
$rT->setAlignments(array("left","left","center"));
$rT->setAttribute(0, new Text("Zeitraum von: "));
$rT->setAttribute(1, $cobStartTime);
$rT->setAttribute(2, new Text("bis: "));
$rT->setAttribute(3, $cobEndTime);
$timeSelect->addRow($rT);


// -------------------------
// Show
// -------------------------

$tbl = new Table(array("", "", ""));
$tbl->setColSizes(array(130));

$rTtl = $tbl->createRow();
$rTtl->setSpawnAll(true);
$rTtl->setAttribute(0, $ttl);
$tbl->addRow($rTtl);

$tbl->addSpacer(0,30);

$rSelect = $tbl->createRow();
$rSelect->setAttribute(0, $txtSelect);
$rSelect->setAttribute(1, $cboSensorSelect);
$rSelect->setAttribute(2, $btnDeleteData);
$tbl->addRow($rSelect);

$tbl->addSpacer(0,10);

$rTimeSelect = $tbl->createRow();
$rTimeSelect->setSpawnAll(true);
$rTimeSelect->setAttribute(0, $timeSelect);
$tbl->addRow($rTimeSelect);

$tbl->addSpacer(0,30);


if( isset($_REQUEST['deleteSensorData']) && $_REQUEST['deleteSensorData']=="Sensordaten entfernen" && isset($_REQUEST['sensorSelectCob']) && strlen($_REQUEST['sensorSelectCob'])>0 ){
    echo "Sensor-Daten entfernt!<br/>";
    $delSql = "DELETE FROM homecontrol_sensor_log WHERE sensor_id = " .$_REQUEST['sensorSelectCob'];
    $_SESSION['config']->DBCONNECT->executeQuery($delSql);
} 

if(isset($_REQUEST['sensorSelectCob']) && strlen($_REQUEST['sensorSelectCob'])>0 && isset($_REQUEST['startTimeCob']) && isset($_REQUEST['endTimeCob'])){
  $imgTxt = new Text("<img src='includes/pictures/sensorLogGraphs.inc.php?width=800&height=350&sensorId=".$_REQUEST['sensorSelectCob'] 
                    ."&startTime=" .$_REQUEST['startTimeCob'] ."&endTime=" .$_REQUEST['endTimeCob'] ."'>");
  $imgTxt->setFilter(false);

  $rGraph = $tbl->createRow();
  $rGraph->setSpawnAll(true);
  $rGraph->setAttribute(0, $imgTxt);
  $tbl->addRow($rGraph);
}

$frmSelect->add($tbl);
$frmSelect->show();

?>