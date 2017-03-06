<?php

/**
 * 
 * @author Daniel Scheidler 
 * @copyright Oktober 2008
 */



function getDbComboArray($tab, $col, $currentRow = null) {
    // Liefert ein Array für eine Combobox (code=>text)
    // entsprechend Tabelle: dbcombos
    $ret = array();

    //DB-Combo-Definition laden
    $sqlDbCombo = "select * from dbcombos where tab_name = '" . $tab . "' and col_name = '" . $col . "' limit 1";
    $resDbCombo = $_SESSION['config']->DBCONNECT->executeQuery($sqlDbCombo);
    $rowDbCombo = mysql_fetch_array($resDbCombo);

    if ($rowDbCombo['combo_tab'] == "") {
        return "";
    }

    //Select zum holen der Daten für die Combobox
    $sqlComboData = "SELECT ";
    if ($rowDbCombo['distinct_jn'] == "J") {
        $sqlComboData .= " DISTINCT ";
    }
    $sqlComboData .= $rowDbCombo['combo_code_col'] . ", " . $rowDbCombo['combo_text_col'] . " FROM " . $rowDbCombo['combo_tab'] . " ";

    //Wenn definiert -> WHERE
    if (strlen($rowDbCombo['combo_where']) > 3) {
        $curRowStmt = getDbComboWhereStatement($rowDbCombo, $currentRow);
        if (strlen($curRowStmt) > 3) {
            $sqlComboData .= " WHERE " . $curRowStmt;
        }
    }

    //Wenn definiert -> ORDERBY
    if (strlen($rowDbCombo['combo_orderby']) > 0) {
        $sqlComboData .= " ORDER BY " . $rowDbCombo['combo_orderby'];
    } else {
        $sqlComboData .= " ORDER BY 2";
    }


    //wird nun nachträglich erledigt wegen Filter auf null in getdbcombovalue//    $sqlComboData    .=  " UNION SELECT ' ', ' ' FROM dual";
    //Combobox-Daten laden
    $resDbCobInit = $_SESSION['config']->DBCONNECT->executeQuery($sqlComboData . " limit 1 ");

    $codeCol = mysql_field_name($resDbCobInit, 0);
    $textCol = mysql_field_name($resDbCobInit, 1);

echo "<br>".$sqlComboData;

    $resComboData = $_SESSION['config']->DBCONNECT->executeQuery($sqlComboData);
    while ($rowComboData = mysql_fetch_array($resComboData)) {
        $code = $rowComboData[$codeCol];
        $value = $rowComboData[$textCol];

        $ret[$code] = $value;
    }

    $ret[''] = " ";

    return $ret;
}



function getDbComboWhereStatement($rowDbCombo, $currentRow = null) {
    $ret = "";
    // Werte aus der Aufrufenden Zeile ermitteln und ins WHERE Statement übernehmen
    $tmp = split('#', $rowDbCombo['combo_where']);
    $chkVal = 0; // 0 = WhereClause    1 = Param
    foreach ($tmp as $wherePart) {
        $append = $wherePart;
        if ($chkVal == 1) {
            $currRowFieldName = $wherePart;
            $whereValue = "";
            if ($currentRow != null) {
                $tmpTbl = new DbTable($_SESSION['config']->DBCONNECT, $rowDbCombo['tab_name'], array("*"), "", "", "", "id=" . $currentRow->getNamedAttribute("rowid"));
                $tmpRow = $tmpTbl->getRow(1);

                $whereValue = $tmpRow->getNamedAttribute($currRowFieldName);
            }
            $append = $whereValue;
        }

        $ret .= $append;
        $chkVal = $chkVal == 0 ? 1 : 0;
    }

    // Werte aus der Session ermitteln und ins WHERE Statement übernehmen
    $tmpSess = split('\$', $ret);
    if (sizeof($tmpSess) > 1) {
        $sessFieldName = $tmpSess[1];

        $whereValue = $_SESSION[$sessFieldName];

        $ret = $tmpSess[0] . " '" . $whereValue . "' ";

        if (sizeof($tmpSess) > 2) {
            $ret .= $tmpSess[2];
        }
    }

    return $ret;
}



function getFieldnameForDbComboDetailWhere($rowDbCombo, $currentRow = null) {
    $where = $rowDbCombo['combo_where'];
    $ret = "";

    if ($currentRow != null) {
        $tmp = split('#', $where);

        if (sizeof($tmp) > 1) {
            $ret = ", " . $tmp[1] . " as chkValCol";
        }

    }

    return $ret;

}



function getDefaultComboArray($name, $emptyRowBool = false) {
    // Liefert ein Array für eine Combobox (code=>text)
    // aus Tabelle: default_combo_values
    $ret = array();


    //Select zum holen der Daten für die Combobox
    $sqlComboData = "SELECT DISTINCT code, value from default_combo_values where combo_name = '" . $name . "' ";
    $resComboData = $_SESSION['config']->DBCONNECT->executeQuery($sqlComboData);
    while ($rowComboData = mysql_fetch_array($resComboData)) {
        $code = $rowComboData["code"];
        $value = $rowComboData["value"];

        $ret[$code] = $value;
    }

    if ($emptyRowBool) {
        $ret[''] = " ";
    }


    return $ret;
}


function getDefaultComboValue($name, $code) {
    $ret = null;

    $sqlComboData = "SELECT DISTINCT code, value from default_combo_values where combo_name = '" . $name . "' and code = " . $code;
    $resComboData = $_SESSION['config']->DBCONNECT->executeQuery($sqlComboData);
    while ($rowComboData = mysql_fetch_array($resComboData)) {
        $code = $rowComboData["code"];
        $value = $rowComboData["value"];

        $ret = $value;
    }

    return $ret;
}

function getNumberComboArray($von, $bis, $emptyRowBool = false) {
    // Liefert ein Array für eine Combobox (code=>text)
    // aus Tabelle: default_combo_values
    $ret = array();

    if ($emptyRowBool) {
        $ret[''] = " ";
    }

    for ($i = $von; $i <= $bis; $i++) {
        $code = $i;
        $value = $i;

        $ret[$code] = $value;
    }

    return $ret;
}



function getComboArrayBySql($sql) {
    $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);

    while ($rowCb = mysql_fetch_array($result)) {
        $cbArray[$rowCb[0]] = $rowCb[1];
    }

    return $cbArray;
}



function getDbComboValue($tab, $col, $code) {
    // Liefert ein Array für eine Combobox (code=>text)
    // entsprechend Tabelle: dbcombos
    $ret = "";

    //DB-Combo-Definition laden
    $sqlDbCombo = "select * from dbcombos where tab_name = '" . $tab . "' and col_name = '" . $col . "' limit 1";
    $resDbCombo = $_SESSION['config']->DBCONNECT->executeQuery($sqlDbCombo);
    $rowDbCombo = mysql_fetch_array($resDbCombo);
    if ($rowDbCombo['combo_tab'] == "") {
        return "";
    }

    //Select zum holen der Daten für die Combobox
    $sqlComboData = "SELECT  ";
    if ($rowDbCombo['distinct_jn'] == "J") {
        $sqlComboData .= " DISTINCT ";
    }
    $sqlComboData .= $rowDbCombo['combo_code_col'] . ", " . $rowDbCombo['combo_text_col'] . " FROM " . $rowDbCombo['combo_tab'] . " ";
    //Wenn definiert -> WHERE
    if (strlen($rowDbCombo['combo_where']) > 3) {
        if (strlen(getDbComboWhereStatement($rowDbCombo)) > 1) {
            $sqlComboData .= " WHERE " . getDbComboWhereStatement($rowDbCombo) . " AND " . $rowDbCombo['combo_code_col'] . " = '" . $code . "'  ";
        } else {
            $sqlComboData .= " WHERE " . $rowDbCombo['combo_code_col'] . " = '" . $code . "'  ";
        }
    } else
        if ($code != null && $code != "") {
            $sqlComboData .= " WHERE " . $rowDbCombo['combo_code_col'] . " = '" . $code . "'  ";
        } else {
            return "---";
        }

        //Wenn definiert -> ORDER BY
        if (strlen($rowDbCombo['combo_orderby']) > 0) {
            $sqlComboData .= " ORDER BY  " . $rowDbCombo['combo_orderby'];
        } else {
            $sqlComboData .= " ORDER BY 2";
        }


        //Combobox-Daten laden
        $resDbCobInit = $_SESSION['config']->DBCONNECT->executeQuery($sqlComboData . " limit 1 ");

    $codeCol = mysql_field_name($resDbCobInit, 0);
    $textCol = mysql_field_name($resDbCobInit, 1);


    $resComboData = $_SESSION['config']->DBCONNECT->executeQuery($sqlComboData);
    $rowComboData = mysql_fetch_array($resComboData);

    $code = $rowComboData[$codeCol];
    $value = $rowComboData[$textCol];

    $ret = $value;


    return $ret;
}

?>
