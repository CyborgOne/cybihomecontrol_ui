<?PHP
include_once ("init.php");


if(!checkSensorInputMissingValues()){
    return;
}

refreshSensorValue($_SESSION['config']->DBCONNECT, $_REQUEST['sensorId'], $_REQUEST['sensorWert']);




function checkSensorInputMissingValues(){
  if (!isset($_REQUEST['sensorId']) || !isset($_REQUEST['sensorWert']) ) {
    return false;
  } else {
    if (strlen($_REQUEST['sensorId']) <= 0 || strlen($_REQUEST['sensorWert'])<=0 ) {
      return false;
    }
  }
  return true;
}

?>
