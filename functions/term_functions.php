<?PHP

/**
 * TODO: 
 * 
 * DB Comments!
 */
function getTermWhereclauseByGroupName($configId) {
    // SELECT * FROM homecontrol_config WHERE ...
    $WHERE = "";
    $term_type = 0;

    $result = $_SESSION['config']->DBCONNECT->executeQuery("SELECT * FROM homecontrol_term WHERE config_id=" .
        $configId ." ORDER BY order_nr");

    while ($termRow = mysql_fetch_array($result)) {
        if ($WHERE != "") {
            $WHERE .= " " . $termRow['and_or'] . " ";
        }
        switch ($term_type) {
            case 1:
                $WHERE = getWhereclauseForSensorWert($termRow['sensor_id'], $termRow);

                break;

            case 2:
                $WHERE = getWhereclauseForSensorStatus($termRow['sensor_id'], $termRow);

                break;

            case 3:
                $WHERE = getWhereclauseForTime(str_pad(date("H"), 2, '0', STR_PAD_LEFT)
                         .str_pad(date("i"), 2, '0', STR_PAD_LEFT), $termRow);

                break;

            case 4:
                $WHERE = getWhereclauseForDay(date("N"), $termRow);

                break;

            default:
                $WHERE = getWhereclauseForSensorStatus(getSensorValue($termRow['sensor_id']), $termRow);

                break;
        }

    }

    return $WHERE;
}

/**
 * Type 1
 * Letzten Sensorwert mit VALUE der $termRow vergleichen
 * 
 * Falls ein Wert > 0 in lastSensorintervall value gesetzt ist, 
 * wird zusätzlich geprüft, ob der Letzte Sensorwert maximal 
 * die dort angegebenen Sekunden zurück liegt. 
 */
function getWhereclauseForSensorWert($sensor, $termRow) {
    $intervall    = strlen($termRow['lastSensorintervall'])>0 ? $termRow['lastSensorintervall'] : 10;
    $rslt = $_SESSION['config']->DBCONNECT->executeQuery("SELECT * FROM homecontrol_sensor WHERE id=" .$sensor ." AND lastSensorIntervall+".$intervall .">" .time() );

    while( $row  = mysql_fetch_array($rslt) ){
      return $row['lastValue'] . " " . $termRow['termcondition'] . " " . $termRow['value'];
    }
    
    return "1=2";
}  


/**
 * Type 2
 * Sensorstatus mit STATUS  vergleichen
 * 
 * Falls ein Wert > 0 in value gesetzt ist, 
 * wird zusätzlich geprüft, ob der Letzte Sensorwert maximal 
 * die dort angegebenen Sekunden zurück liegt. 
 * 
 * @param $status   int     =>   1 oder 0
 *        1 = an  /  0 = aus
 */
function getWhereclauseForSensorStatus($sensor, $termRow) {
    $rslt = $_SESSION['config']->DBCONNECT->executeQuery("SELECT * FROM homecontrol_sensor WHERE id=" .$sensor );
    
    $w = "'" . $status . "' = '" . $termRow['status'] . "' ";

    if (strlen($termRow['value']) > 0) {
        $w = $w . " AND " . (time() - $termRow['value']) .
            " > (SELECT lastSignal FROM homecontrol_sensor WHERE id = " . $termRow['sensor_id'] .
            ")";
    }

    return $w;
}


/**
 * Type 3
 * Zeit prüfen
 * 
 * 
 * @param $time string   =>  str_pad(date("H"), 2, '0', STR_PAD_LEFT).str_pad(date("i"), 2, '0', STR_PAD_LEFT)
 *        Verkettete Std./Min. mit führenden Nullen
 */
function getWhereclauseForTime($time, $termRow) {
    return $time . " " . $termRow['termcondition'] . " "  
           .str_pad($termRow['std'], 2, '0', STR_PAD_LEFT) 
           .str_pad($termRow['min'], 2, '0', STR_PAD_LEFT);
}

/**
 * Type 4
 * Wochentag prüfen
 * 
 * @param $day  int   =>  date("N")
 */
function getWhereclauseForDay($day, $termRow) {
    switch ($day) {
        case 1:
            return "'J' = " . $termRow['montag'] . " ";

            break;
        case 2:
            return "'J' = " . $termRow['dienstag'] . " ";

            break;
        case 3:
            return "'J' = " . $termRow['mittwoch'] . " ";

            break;
        case 4:
            return "'J' = " . $termRow['donnerstag'] . " ";

            break;
        case 5:
            return "'J' = " . $termRow['freitag'] . " ";

            break;
        case 6:
            return "'J' = " . $termRow['samstag'] . " ";

            break;
        case 7:
            return "'J' = " . $termRow['sonntag'] . " ";

            break;
    }
    ;
}

?>