<?PHP
if(isset($_REQUEST['sensorId']) && strlen($_REQUEST['sensorId'])>0){
    header("Content-type: image/png");
    include ("../../config/dbConnect.php");
    include_once('../../classes/external/phpMyGraph5.0.php');
    
    $usedPoints = 50;
    
    //Set config directives
    $cfg['title'] = 'Example graph';
    $cfg['width']  = 500;
    $cfg['height'] = 250;
    
    if(isset($_REQUEST['title']) && strlen($_REQUEST['title'])>0){
        $cfg['title']  = $_REQUEST['title'];
    }

    if(isset($_REQUEST['width']) && strlen($_REQUEST['width'])>0){
        $cfg['width']  = $_REQUEST['width'];
    }

    if(isset($_REQUEST['height']) && strlen($_REQUEST['height'])>0){
        $cfg['height']  = $_REQUEST['height'];
    }

        
    $link = mysql_connect($DBHOST, $DBUSER, $DBPASS);
    if (!$link) {
        die('Verbindung schlug fehl: ' . mysql_error());
    }
    mysql_select_db($DBNAME, $link) or die('Could not select database.');


// ------------
    $sqlSensor = "SELECT * FROM homecontrol_sensor WHERE id = ".$_REQUEST['sensorId'];
    $resultSensor = mysql_query($sqlSensor);
    $rowSensor = mysql_fetch_array($resultSensor);
    $cfg['title'] = $rowSensor['name']; 
// ------------

$timeOffset = 60*60; //get_timezone_offset('UTC')*

// Daten für ausgewählten Sensor ermitteln
    $sql = "SELECT from_unixtime(update_time, '%d.%m.%Y') as tag,  from_unixtime(update_time, '%H:%i:%s') as zeit, value "
          ."FROM homecontrol_sensor_log WHERE sensor_id = " .$_REQUEST['sensorId'];
          
    if(isset($_REQUEST['startTime']) && strlen($_REQUEST['startTime'])>0){
        $sql .= " AND update_time >= " 
                .(strtotime(substr($_REQUEST['startTime'],8,2)."-".substr($_REQUEST['startTime'],3,2)."-".substr($_REQUEST['startTime'],0,2)) - $timeOffset);
    }
              
    if(isset($_REQUEST['endTime']) && strlen($_REQUEST['endTime'])>0){
        $sql .= " AND update_time <= "
                .(strtotime(substr($_REQUEST['endTime'],8,2)."-".substr($_REQUEST['endTime'],3,2)."-".(substr($_REQUEST['endTime'],0,2)+1)) - $timeOffset);
    }

    $result = mysql_query($sql);
    
// Anzahl der auszulassenden Datensätze ermitteln
    $rowCount = mysql_num_rows($result);
    $chkNr = floor($rowCount/$usedPoints);

    $i=0;
    $data = array();
    $val = 0;
    $lastDay = "";
    $index = "";
    
    if($chkNr>0){
        // Geforderte Daten einlesen und in Array aufbereiten
        while($row = mysql_fetch_array($result)){
            $val = averageValue($val, $row['value']);
            
            if($i%$chkNr==0){
                if($lastDay!=$row['tag']){
                    $lastDay=$row['tag'];
                    $index = $row['tag']." ".$row['zeit'];
                } else {
                    $index = $row['zeit'];            
                }
    
                $data[$index] = $val;
            }
            $i++;
        }
    }
     
    mysql_close($link);
    
    if(count($data)>0){
        // Create phpMyGraph instance
        $graph = new phpMyGraph();
        $graph->parseVerticalLineGraph($data, $cfg);
    }


} else {
  exit("Parameter: sensorId  muss eingegeben werden");
}


function averageValue($average, $newVal){
    if ($average==0){
        $average = $newVal;
    } else {
        $average = ($average+$newVal)/2;
    }
    return floor($average);
}
?>