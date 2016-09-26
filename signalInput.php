<?PHP
include_once ("init.php");


if(!checkSensorInputMissingValues()){
    return;
}

refreshSensorValue($_SESSION['config']->DBCONNECT, $_REQUEST['sensorId'], $_REQUEST['sensorWert']);



?>
