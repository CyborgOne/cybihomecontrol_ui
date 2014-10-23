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
        $configId . " ORDER BY order_nr");

    while ($termRow = mysql_fetch_array($result)) {
        if ($WHERE != "") {
            $WHERE .= " " . $termRow['and_or'] . " ";
        }
        switch ($term_type) {
            case 1:
                $WHERE = getWhereclauseForSensorWert("", $termRow);

                break;

            case 2:
                $WHERE = getWhereclauseForSensorStatus("", $termRow);

                break;

            case 3:
                $WHERE = getWhereclauseForTime("", $termRow);

                break;

            case 4:
                $WHERE = getWhereclauseForDay("", $termRow);

                break;

            default:
                $WHERE = getWhereclauseForSensorStatus("", $termRow);

                break;
        }

    }

    return $WHERE;
}

/**
 * Type 1
 * Sensorwert mit VALUE anhand der CONDITION vergleichen
 */
function getWhereclauseForSensorWert($sensorwert, $termRow) {
    return $sensorwert . " " . $termRow['termcondition'] . " " . $termRow['value'] . " ";
}


/**
 * Type 2
 * Sensorstatus mit STATUS  vergleichen
 * Falls ein Wert > 0 in value gesetzt ist, 
 * wird zusätzlich geprüft, ob der Letzte Sensorwert maximal 
 * die dort angegebenen Sekunden zurück liegt. 
 */
function getWhereclauseForSensorStatus($status, $termRow) {
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
 * Sensorwert mit STATUS anhand der CONDITION vergleichen
 */
function getWhereclauseForTime($time, $termRow) {
    return $time . " " . $termRow['termcondition'] . " " . $termRow['min'] . $termRow['std'];
}

/**
 * Type 4
 * Wochentag prüfen
 */
function getWhereclauseForDay($dow, $termRow) {
    switch (date("N")) {
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