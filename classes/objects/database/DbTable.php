<?php

// Dateiname: DbTable.php


/**
 *  DbTable($con, $tbname="", $cols=array("*"), $labels="", $defaults="", $orderBy="", $where)
 *
 *  $con erwartet ein Datenbank-Verbindungsobjekt vom Typ DbConnect
 *
 *  $tbname ist der Tabellenname der Datenbanktabelle
 *
 *  $cols erwartet ein String-Array welches die Namen der Gewünschten Spalten enthält. (Default = "*")
 *
 *  $labels sind die Lables die in der Tabelle für die Spalten angezeigt werden sollen. 
 *          Die Angabe kann hier sowohl als String-Array als auch in einem String durch Kommas getrennt erfolgen.
 *          Wird $labels gesetzt werden nur so viele Spalten angezeigt wie Labels angegeben sind!!!
 *          so lassen sich einfach Spalten ausblenden aber die Daten trotzdem laden.
 *   !!! ACHTUNG !!!
 *          Die auszublendenen Spalten müssen nach den anzuzeigenden Spalten stehen, da ansonsten die Zuordnung der Labels nicht mehr mit den Spalten übereinstimmt.
 *
 *  $defaults gibt die gewünschte Default-Werte für das einfügen von neuen Zeilen an.
 *          Bspl.: "spaltenname1 = 'neuer Default', spaltenname2 = 'noch ein Default' "
 *
 *  $o  gibt die Sortierung an.
 *          Bspl.: "spalte1 ASC, spalte2 DESC"
 * 
 *  $w  gibt die Suchbedingung an.
 *          Bspl.: "spalte1 = 'Wert2', spalte2 LIKE 'We%' "
 */

class DbTable extends Object {
    var $ROWS; // Array welches die Zeilen(Row) enthält
    var $DBCONNECT;
    var $TABLENAME;
    var $COLNAMES;
    var $COLNAMEIDS;
    var $COLSIZES = array();

    var $BORDER;
    var $LABELS;
    var $ALIGNMENTS;
    
    var $ORDERBY;
    var $PADDING;
    var $SPACING;
    var $DEFAULTS;

    var $MAX_ROWS_TO_FETCH;

    var $NOINSERTCOLS; // Array welches die Spalten enthält die aus Insert ausgenommen werden sollen
    var $NOUPDATECOLS; // Array welches die Spalten enthält die nicht änderbar sein sollen
    var $READONLYCOLS; // Array welches die Spaltennamen enthält die nicht änderbar sein sollen

    var $TOCHECK; // String der mit Kommas getrennt alle Spaltennamen enthält, die Pflichtfelder darstellen
    var $COLNAMESTRING;
    var $FONTTYPES; // Array welches die Schriftformatierung für einzelne Spalten vorgibt
    var $HEAD_ENABLED;
    var $DELETE_IN_UPDATE; //boolean der angibt ob ein Button zum entfernen in der UPDATE-Maske angezeigt werden soll
    var $CURRENT_PAGE;

    private $LIMIT_ACTIVE;

    private $FIELDNAMES;
    private $WHERE;
    private $initialized;

    private $DEFAULT_HIDDEN_FIELDS;
    private $UPDATE_USERID_ON_INSERT = true;
    private $UPDATE_USERID_ON_UPDATE = false;
    private $TEXTEDITOR_ENABLED = true;
    
    private $ADDITIONAL_UPDATE_FIELDS = array();

    function isUpdateUserIdOnInsert() {
        return $this->UPDATE_USERID_ON_INSERT;
    }

    function setUpdateUserIdOnInsert($b) {
        $this->UPDATE_USERID_ON_INSERT = ($b === true);
    }

    function isUpdateUserIdOnUpdate() {
        return $this->UPDATE_USERID_ON_UPDATE;
    }

    function setUpdateUserIdOnUpdate($b) {
        $this->UPDATE_USERID_ON_UPDATE = ($b === true);
    }

    function isTexteditorEndabled(){
        return $this->TEXTEDITOR_ENABLED;
    }
    
    function setTexteditorEndabled($bool){
        $this->TEXTEDITOR_ENABLED = $bool;
    }

    function setAdditionalUpdateFields($formFieldArray){
        $this->ADDITIONAL_UPDATE_FIELDS = $formFieldArray;
    }
    
    
    /**
     * DbTable($con, $tbname="", $cols=array("*"), $labels="", $defaults="", $o="", $w="")
     *  
     * @param $con 
     * @param $tbname=""
     * @param $cols=array("*")
     * @param $labels=""
     * @param $defaults=""
     * @param $o=""
     * @param $w="" 
     */
    function DbTable($con, $tbname = "", $cols = array("*"), $labels = "", $defaults =
        "", $o = "", $w = "") { //Konstruktor
        $this->initialized = false;
        $this->ROWS = array();
        $this->DBCONNECT = $con;
        $this->TABLENAME = $tbname;
        $this->setColNames($cols);
        $this->FIELDNAMES = $this->getFieldNames();
        $this->LABELS = array();
        $this->DEFAULTS = $defaults;
        $this->COLNAMESTRING = "";
        $this->HEAD_ENABLED = false;
        $this->DELETE_IN_UPDATE = false;
        $this->setWhere($w);
        $this->setOrderBy($o);
        $this->MAX_ROWS_TO_FETCH = getPageConfigParam($_SESSION['config']->DBCONNECT,
            "max_rowcount_for_dbtable");

        $this->setLimitActive(false);

        if (isset($_SESSION['tmp']['CURRENT_PAGE_' . $this->TABLENAME]) && strlen($_SESSION['tmp']['CURRENT_PAGE_' .
            $this->TABLENAME]) > 0) {
            $this->CURRENT_PAGE = $_SESSION['tmp']['CURRENT_PAGE_' . $this->TABLENAME];
        } else {
            $this->CURRENT_PAGE = 1;
            $_SESSION['tmp']['CURRENT_PAGE_' . $this->TABLENAME] = 1;
        }

        if (isset($_REQUEST["changeDbPage" . $this->TABLENAME]) && $_REQUEST["changeDbPage" .
            $this->TABLENAME] > 0) {
            $this->CURRENT_PAGE = $_REQUEST["changeDbPage" . $this->TABLENAME];
            $_SESSION['tmp']['CURRENT_PAGE_' . $this->TABLENAME] = $_REQUEST["changeDbPage" .
                $this->TABLENAME];
        }


        $this->NOINSERTCOLS = array();
        $this->NOUPDATECOLS = array();
        $this->READONLYCOLS = array();
        $this->DEFAULT_HIDDEN_FIELDS = new Container();

        // Falls keine Spaltennamen übergeben -> ALLE ermitteln
        if ($this->COLNAMES[0] == "*") {
            $stmnt = "SELECT *, id as rowid FROM " . $this->TABLENAME . " LIMIT 1 ";
            $result = $this->DBCONNECT->executeQuery($stmnt);

            //Spaltennamen in array setzen
            for ($i = 0; $i < mysql_num_fields($result); $i++) {
                $fldName = mysql_field_name($result, $i);
                if ($fldName != "rowid") {
                    $this->COLNAMES[$i] = $fldName;
                }
            }
        }
        // -------------------------------------------------------

        if (count($this->COLNAMES) > 0) {
            $chk = 0;
            foreach ($this->COLNAMES as $cn) {
                if ($chk > 0) {
                    $this->COLNAMESTRING .= ", ";
                }
                $this->COLNAMESTRING .= $cn;

                $chk++;
            }
        }

        //-----------------------------
        // ÃƒÂƒÃ‚ÂƒÃƒÂ‚Ã‚ÂƒÃƒÂƒÃ‚Â‚ÃƒÂ‚Ã‚Âœbergebene Labels prüfen
        // und falls nicht gesetzt
        // automatisch setzen.
        //-----------------------------
        if (strlen($labels) >= 2) {
            $this->LABELS = explode(',', $labels);
        } else {
            $chk = 0;
            $stmnt = "SELECT  ";

            foreach ($this->COLNAMES as $cn) {
                if ($chk > 0) {
                    $stmnt .= ", ";
                }
                $stmnt .= $cn;

                $chk++;
            }

            $stmnt .= " FROM " . $this->TABLENAME . " LIMIT 1 ";

            //echo $stmnt;
            $result = $this->DBCONNECT->executeQuery($stmnt);

            for ($i = 0; $i < mysql_num_fields($result); $i++) {
                $fldName = mysql_field_name($result, $i);
                $this->LABELS[$i] = $fldName;
            }
        }

        $this->setLimitActive(false);

        $this->checkTableStructure();

        $this->initialized = true;

        $this->refresh();
    }

    function addDefaultHiddenField($name, $value) {
        array_push($this->DEFAULT_HIDDEN_FIELDS, new HiddenField($name, $value));
    }

    function setColSizes($array) {
        $this->COLSIZES = $array;
    }

    function getColSizes() {
        return $this->COLSIZES;
    }

    function getTableName() {
        return $this->TABLENAME;
    }

    /**
     * fügt den angegebenen Default-String 
     * zu dem aktuellen hinzu.
     */
    function addDefault($defVal) {

        if (strlen($defVal) > 0) {
            $this->DEFAULTS = $this->DEFAULTS . ", " . $defVal;

        } else {
            $this->DEFAULTS = $defVal;

        }

        $this->refresh();
    }

    function setDefaults($d) {
        $this->DEFAULTS = $d;
    }
    //----------------------------------------------------------------------


    function setAlignments($array) {
        foreach ($this->ROWS as $r) {
            $r->ALIGNMENTS = $array;
        }
        $this->ALIGNMENTS = $array;
    }

    function getAlignments() {
        return $this->ALIGNMENTS;
    }
    /**
     * legt fest, ob die Seitennavigation aktiviert werden soll. 
     * (beim überschreiten von MAX_ROWS_TO_FETCH)
     */
    function setLimitActive($bool) {
        $this->LIMIT_ACTIVE = $bool === true ? true : false;
        if ($this->initialized) {
            $this->refresh();
        }
    }


    //----------------------------------------------------------------------


    /**
     * legt fest, ob die Seitennavigation aktiviert werden soll. 
     * (beim überschreiten von MAX_ROWS_TO_FETCH)
     */
    function isLimitActive() {
        return $this->LIMIT_ACTIVE === true ? true : false;
    }


    //----------------------------------------------------------------------
    /**
     * gibt an wie viele Seiten die Tabelle maximal hat.
     */
    function getPageCount() {
        return ceil($this->getRealRowCount() / $this->MAX_ROWS_TO_FETCH);
    }

    //----------------------------------------------------------------------


    /**
     *  Zeigt (Wenn mehr als eine Seite notwendig) eine Seitennavigation an
     */
    function getPageNavigation() {
        if ($this->getPageCount() <= 1 || $this->isLimitActive() != true) {
            return new Text("");
        }

        $maxPage = $this->getPageCount();

        $div = new Div();
        $label = new Text("Seite: ");
        $label->setFontsize(2);
        $div->add($label);

        for ($i = 1; $i <= $maxPage; $i++) {
            $txt = $i;
            $link = new Link("?changeDbPage" . $this->TABLENAME . "=" . $i, $txt, false);

            if ($this->CURRENT_PAGE == $i) {
                $ft = new FontType();
                $ft->setFontsize(2);
                $ft->setColor($_SESSION['config']->COLORS['hover']);
                $link->setFontType($ft);

            }

            $div->add($link);

            if ($i < $maxPage) {
                $label = new Text(", ");
                $label->setFontsize(2);
                $div->add($label);
            }
        }
        return $div;
    }


    //----------------------------------------------------------------------


    /**
     *  prüft die Tabelle auf die Struktur (vorhandensein von *id* und *geaendert*)
     *  wenn diese Spalten nicht vorhanden sind werden sie angelegt 
     *  (autoincrement und timestamp on update damit keine probleme auftreten können)
     */
    function checkTableStructure() {
        if (!$this->existsColumn("id")) {
            $this->addColumn("id", "int(11)", "");
        }

        if (!$this->existsColumn("geaendert")) {
            $this->addColumn("geaendert", "TIMESTAMP",
                "ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        }
    }


    /**
     * Prüft ob eine Spalte entsprechend dem übergebenen String existiert
     */
    function existsColumn($columnName) {
        $result = mysql_query("SHOW COLUMNS FROM " . $this->getTableName() . " LIKE '" .
            $columnName . "'");
        if (!$result) {
            return false;
        }
        $exists = (mysql_num_rows($result)) ? true : false;
        return $exists;
    }


    /**
     * Fügt der Datenbanktabelle eine neue Spalte hinzu
     */
    function addColumn($columnName, $type = "varchar(100)", $params = "") {
        if (strpos($this->TABLENAME, ",") == false) {
            $sql = "ALTER TABLE " . $this->TABLENAME . " ADD `" . $columnName . "` " . $type .
                " " . $params . " ";
            $result = $this->DBCONNECT->executeQuery($sql);
        }
    }


    //----------------------------------------------------------------------
    /**
     * Schriftarten vorbereiten
     */
    function prepareFonts() {
        //legt Standard-Fonttypes an
        $fts = array();

        for ($i = 0; $i < count($this->COLNAMES); $i++) {
            $fts[$i] = new Fonttype();
        }

        $this->FONTTYPES = $fts;
    }


    //----------------------------------------------------------------------

    /**
     * setzt die Order-By Clause 
     * Die Sortierbedingung wird als String übergeben.  Bspl: " id ASC, value DESC ..."
     */
    function setOrderBy($o) {
        if (strlen($o) > 0) {
            $this->ORDERBY = "ORDER BY " . $o;
        } else {
            $this->ORDERBY = "";
        }
    }


    function setFonttypes($f) {
        $this->FONTTYPES = $f;

        //Allen Rows neuen Parameter mitgeben
        foreach ($this->ROWS as $row) {
            $row->setFonttypes($this->FONTTYPES);
        }
    }

    function setMaxRowsToFetch($c) {
        $this->MAX_ROWS_TO_FETCH = $c;
    }

    function getMaxRowsToFetch() {
        return $this->MAX_ROWS_TO_FETCH;
    }


    function setNoInsertCols($c) {
        $this->NOINSERTCOLS = $c;
    }

    function setNoUpdateCols($c) {
        $this->NOUPDATECOLS = $c;
    }


    function isNoUpdateCol($name) {
        if(strlen($name)<=0 || count($this->NOUPDATECOLS)<=0){
            return false;
        }
        return existsValueInArray($name, $this->NOUPDATECOLS);
    }

    function setReadOnlyCols($c) {
        $this->READONLYCOLS = $c;
    }

    function getRowCount() {
        return count($this->ROWS);
    }


    function setWhere($w) {
        if (strtolower(substr(trim($w), 0, 5)) == "where") {
            $w = substr(trim($w), 5);
        }
        if (strlen($w) > 3 && ((strpos($w, "=") > 0 && strpos($w, "=") < strlen($w)) ||
            (strpos($w, "is") > 0 && strpos($w, "is") < strlen($w)) || (strpos($w, "in") > 0 &&
            strpos($w, "in") < strlen($w)))) {
            $this->WHERE = " WHERE " . $w . " ";
        } else {
            $this->WHERE = "";
        }
    }

    function getWhere() {
        return $this->WHERE;
    }


    function setDeleteInUpdate($b) {
        $this->DELETE_IN_UPDATE = $b;
    }

    function isDeleteInUpdate() {
        return $this->DELETE_IN_UPDATE;
    }


    function setSpacing($s) {
        $this->SPACING = $s;
    }


    //----------------------------------------------------------------------

    function createRow() {
        $r = new DbRow($this->COLNAMES, $this->TABLENAME, $this->FIELDNAMES);
        return $r;
    }


    /**
     * Im unterschied zu getRowCount() wird hier nicht
     * die Länge des Arrays abgefragt sondern
     * ein direkter SELECT count(*) FROM tablename ausgewertet 
     */
    function getRealRowCount() {
        $ret = 0;

        $sql = "SELECT count(*) as rc FROM " . $this->TABLENAME;
        $res = $this->DBCONNECT->executeQuery($sql);
        $row = mysql_fetch_array($res);

        if (isset($row['rc']) && $row['rc'] > 0) {
            $ret = $row['rc'];
        }

        return $ret;
    }


    /**
     * fügt die übergebene Row ins aktuelle Table-Objekt ein.
     * 
     * KEINE ÃƒÂƒÃ‚ÂƒÃƒÂ‚Ã‚ÂƒÃƒÂƒÃ‚Â‚ÃƒÂ‚Ã‚ÂœBERNAHME IN DIE DATENBANK!!! 
     */
    function addRow($r) {
        $this->ROWS[count($this->ROWS) + 1] = $r;
    }


    /**
     * holt alle Daten neu aus der Datenbank
     */
    function refresh() {
        //RESET

        $this->ROWS = array();

        //-----------------------------
        // DB abfragen
        //-----------------------------
        $tn1 = explode(',', $this->TABLENAME);

        $preTabAliasx = explode(' ', $tn1[0]);

        if (count($preTabAliasx) > 1) {
            $preTabAlias = $preTabAliasx[1] . ".";
        } else {
            $preTabAlias = "";
        }

        $limitFrom = ($this->CURRENT_PAGE - 1) * $this->getMaxRowsToFetch();

        //SQL zusammenbauen für aktualisierung
        $stmt = "SELECT " . $this->COLNAMESTRING . ", " . $preTabAlias .
            "id as rowid FROM " . $this->TABLENAME . " " . $this->getWhere() . $this->
            ORDERBY;

        if ($this->isLimitActive()) {
            $stmt .= " limit " . $limitFrom . ", " . $this->getMaxRowsToFetch();
        }

        debugOutput($stmt . "<br>");

        $result = $this->DBCONNECT->executeQuery($stmt);

        if ($result) {
            if (mysql_num_rows($result) <= 0) {
                return;
            }
        } else {
            //echo $stmt;
            return;
        }


        while ($dbRow = (mysql_fetch_array($result))) {
            $newRow = $this->createRow();

            for ($i = 0; $i < mysql_num_fields($result); $i++) {
                $newRow->setAttribute($i, $dbRow[$i]);
            }

            $this->addRow($newRow);
        }

        $this->prepareFonts();
    }


    /**
     * Liefert ein Array der SQL-konformen Feldnamen 
     * bezieht sich auf das aktuelle $this->COLNAMES
     */
    function getFieldNames() {
        $cns = "";
        $ret = array();

        if (count($this->FIELDNAMES) < 1) {
            $chk = 0;
            $stmnt = "SELECT  ";
            foreach ($this->COLNAMES as $cn) {
                if ($chk > 0) {
                    $stmnt .= ", ";
                }
                $stmnt .= $cn;

                $chk++;
            }

            $stmnt .= " FROM " . $this->TABLENAME . " LIMIT 1 ";

            $result = $this->DBCONNECT->executeQuery($stmnt);

            for ($i = 0; $i < mysql_num_fields($result); $i++) {
                $fldName = mysql_field_name($result, $i);
                $ret[count($ret)] = $fldName;
            }

        } else {
            return $this->FIELDNAMES;

        }


        return $ret;
    }


    /**
     * Liefert die angeforderte Row zurück
     * @param $index int > 0 
     * @return Row   
     */
    function getRow($index) {
        if (count($this->ROWS) >= $index) {
            return $this->ROWS[$index];
        }
    }


    /**
     * Liefert die aktuelle Border-Breite zurück
     * @return int   
     */
    function getBorder() {
        return $this->BORDER;
    }


    /**
     * Border der Tabelle setzen
     * @param $b int   
     */
    function setBorder($b) {
        $this->BORDER = $b;
    }

    /**
     * Kopfzeile der Tabelle In-/Aktiv setzen
     * @param $b boolean  
     */
    function setHeaderEnabled($b) {
        $this->HEAD_ENABLED = $b;
    }


    /**
     * Spaltennamen setzen
     * @param $names Array of Strings  
     */
    function setColNames($names) {
        if (is_array($names)) {
            $this->COLNAMES = $names;
        } else {
            echo " Es wurde ein falsches Objekt an die Methode **setColNames** übergeben (benötigt ARRAY)<br>
              -> " . $this->TABLENAME . "
            ";
        }

    }

    /**
     * die Methode prüft die ÃƒÂƒÃ‚ÂƒÃƒÂ‚Ã‚ÂƒÃƒÂƒÃ‚Â‚ÃƒÂ‚Ã‚Âœbergabewerte der Einfügen-Maske auf Vollständigkeit
     * Geprüft werden nur Spalten, die als toCheck* angegeben wurden
     * 
     * *toCheck: 
     * Spalten können mit der Methode setToCheck(String) als Pflichtfelder definiert werden    
     */

    function checkInsertValue() {
        $checkVals = explode(",", $this->TOCHECK);

        foreach ($checkVals as $val) {
            $checkValue = trim($val);

            if (strlen($checkValue) > 0 && (!isset($_REQUEST[$checkValue]) || strlen($_REQUEST[$checkValue]) < 1)) {
                echo $checkValue . " fehlt";
                return false;
            }
        }

        return true;
    }


    function checkUpdateValue($rowId) {
        $checkVals = explode(",", $this->TOCHECK);

        foreach ($checkVals as $val) {
            $checkValue = trim($val);

            if (strlen($checkValue) > 0 && (!isset($_REQUEST[$checkValue.$rowId]) || strlen($_REQUEST[$checkValue.$rowId]) < 1)) {
                echo $checkValue . " fehlt<br/>";
                return false;
            }
        }

        return true;
    }
    //----------------------------------------------------------------------

    /**
     * setzen der Pflichtfelder 
     * @param $string  Komma-getrennter String  
     */
    function setToCheck($string) {
        $this->TOCHECK = $string;
    }


    //----------------------------------------------------------------------
    //  EINGABEMASKE + VERARBEITUNG
    //----------------------------------------------------------------------
    /**
     * Zeigt die Standard Eingabemaske an und führt wenn nötig doInsert() aus.  
     */

    function showInsertMask() {
        if (isset($_REQUEST['InsertIntoDB' . $this->TABLENAME]) && $_REQUEST['InsertIntoDB' .
            $this->TABLENAME] == "Speichern") {
            $this->doInsert();

            return;
        }

        $title = new Title("Neuer Eintrag");
        $title->show();

        $form = $this->getInsertMask();
        $form->add($this->DEFAULT_HIDDEN_FIELDS);
        $form->show();
    }

    //----------------------------------------------------------------------


    /**
     * Liefert den Default-Wert zum übergebenen Spaltennamen zurück
     * 
     * @param $string  Defaultwerte als String. SQL-Konform, komma-getrennt    
     * @param $id      Spaltenname    
     */
    function getDefaultValue($string, $id) {
        $array = explode(',', $string);

        for ($x = 0; $x < count($array); $x++) {
            if (trim(substr(trim($array[$x]), 0, strlen($id))) == $id) {
                $ret = trim(substr($array[$x], strpos($array[$x], "=") + 1));
                
                $ret = str_replace("'", "", $ret);

                return $ret;
            }
        }
        return null;
    }


    //----------------------------------------------------------------------


    /**
     * Liefert eine Standard Eingabemaske
     * Eingabefelder sind Angepasst an die Datenbankfelder
     * 
     * Felder die als Default angegeben sind werden nicht angezeigt!    
     */
    function getInsertMask($hiddenKey = "dbTableNew") {

        $f1 = new FontType();
        $f1->setFontSize(2);
        $f1->setBold(true);

        $f2 = new FontType();

        $fts = array($f1, $f2);

        $table = new Table(array("", ""));
        $table->setHeadEnabled(false);
        $table->setBorder(0);
        $table->setFontTypes($fts);
        $table->setAlign("left");


        $chk = 0;
        $stmnt = "SELECT ";

        foreach ($this->COLNAMES as $cn) {
            if ($stmnt != "SELECT ") {
                $stmnt .= ", ";
            }
            $stmnt .= $cn;

            $chk++;
        }

        $stmnt .= " FROM " . $this->TABLENAME . " " . $this->ORDERBY . "  LIMIT 1 ";

        $result = $this->DBCONNECT->executeQuery($stmnt);

        /*
        VERWENDETE SPALTENARTEN
        ----------------------------
        string
        blob
        date
        int
        timestamp
        */

        for ($i = 0; $i < mysql_num_fields($result); $i++) {
            $fieldName = mysql_field_name($result, $i);
            $fieldLen = 30;

            $maxLen = mysql_field_len($result, $i);

            if ($maxLen < $fieldLen) {
                $fieldLen = $maxLen;
            }
            $arrChk = array_search($fieldName, $this->NOINSERTCOLS);
            
            if (strlen($arrChk) == 0 && $this->getDefaultValue($this->DEFAULTS, $fieldName)==null) {
                $r = $table->createRow();
                $o = "";

                // in der Datenbank für dieses Datenbankfeld
                // definierte LookupWerteArray laden (wenn vorhanden)
                $lookups = getLookupWerte($_SESSION['config']->DBCONNECT, $this->TABLENAME, $fieldName);

                // in der Datenbank für dieses Datenbankfeld
                // definierte Combobox laden (wenn vorhanden)
                $dbCombo = getDbComboArray($this->TABLENAME, $fieldName);


                if ((mysql_num_rows($lookups) == 0 && !$this->isDbComboSet($this->TABLENAME, $fieldName)) ) {//|| strlen($this->getDefaultValue($this->DEFAULTS, $fieldName)) > 0 
                    $val = "";
                    if (strlen($this->getDefaultValue($this->DEFAULTS, $fieldName)) > 0) {
                           $val = $this->getDefaultValue($this->DEFAULTS, $fieldName);
                    }
                    if (isset($_REQUEST[$fieldName]) && strlen($_REQUEST[$fieldName]) > 0) {
                        $val = $_REQUEST[$fieldName];
                    }

                     
                    if (strpos(mysql_field_flags($result, $i), "enum") > 0) {
                        $ev = $this->getEnumValues($fieldName);

                        if (count($ev) == 2 && (in_array('J', $ev) && in_array('N', $ev))) {
                            $o = new Checkbox($fieldName, "", "J");
                            $o->setSelected(true);
                        } else {
                            $o = new ComboBox($fieldName, $this->getComboboxEnumArray($fieldName));
                        }

                    } else
                        if (mysql_field_type($result, $i) == "blob") {
                            $o = new TextArea($fieldName, $val, 80, 10);
                            $o->setTextEditor($this->TEXTEDITOR_ENABLED);

                        } else
                            if (mysql_field_type($result, $i) == "date") {
                                $o = new DateTextfield($fieldName, $val);

                            } else
                                if (mysql_field_type($result, $i) == "int") {

                                    $o = new TextField($fieldName, $val, $fieldLen, $maxLen);

                                } else
                                    if (mysql_field_type($result, $i) == "timestamp") {
                                        $o = new TextField($fieldName, $val, $fieldLen, $maxLen);

                                    } else {
                                        $o = new TextField($fieldName, $val, $fieldLen, $maxLen);
                                    }

                } else {
                    if (mysql_num_rows($lookups) > 0) {
                        $o = new LookupCombo($_SESSION['config']->DBCONNECT, $fieldName, $this->
                            TABLENAME, $fieldName);
                    } else
                        if (count($dbCombo) > 0) {
                            $o = new ComboBox($fieldName, $dbCombo, $this->getDefaultValue($this->DEFAULTS, $fieldName));
                        }
                }

                $r->setAttribute(0, $this->LABELS[$i]);

                $r->setAttribute(1, $o);

                $table->addRow($r);

            } else {
                if($this->getDefaultValue($this->DEFAULTS, $fieldName)!=null){
                    $r = $table->createRow();
                    $r->setSpawnAll(true);
                    $r->setAttribute(0, new Hiddenfield($fieldName, $this->getDefaultValue($this->DEFAULTS, $fieldName)));
                    $table->addRow($r);
                }
            }
        }

        $okButton = new Button("InsertIntoDB" . $this->TABLENAME, "Speichern");
        $r = $table->createRow();
        $r->setAttribute(0, $okButton);
        $table->addRow($r);

        $hidden = new Hiddenfield($hiddenKey, "-");

        $f = new Form($_SERVER['SCRIPT_NAME']);
        $f->add($table);
        $f->add($hidden);
        $f->add($this->DEFAULT_HIDDEN_FIELDS);
        return $f;
    }


    //----------------------------------------------------------------------
    /**
     * generelle Insert-Statement bearbeitung
     * Zugeschnitten auf dynamische InsertMaske (showInsertMask)
     *
     * 
     */

    function doInsert($showOutput = true) {

        //Alle Spalten Holen
        $chk = 0;
        $stmnt = "SELECT  ";
        foreach ($this->COLNAMES as $cn) {
            $this->COLNAMEIDS[$cn] = count($this->COLNAMEIDS);

            if ($chk > 0) {
                $stmnt .= ", ";
            }
            $stmnt .= $cn;
            $chk++;
        }
        $stmnt .= " FROM " . $this->TABLENAME . " " . $this->getWhere() . $this->
            ORDERBY . " LIMIT 1 ";
        $result = $this->DBCONNECT->executeQuery($stmnt);

        // Prüfung der Pflichtfelder
        if (!$this->checkInsertValue()) {
            $e = new Message("Fehlende Eingabe", "Es wurden nicht alle Werte eingegeben!", $backLink =
                '');
            $form = $this->getInsertMask();
            $form->add($this->DEFAULT_HIDDEN_FIELDS);
            $form->show();
            return false;
        }
        /*
        TODO:  sollte umgebaut werden dass ab hier die lokale Methode: insertRowByArray($array) genutzt wird 
        */

        $statement = "INSERT INTO " . $this->TABLENAME . " SET ";
        $chkLen = strlen($statement);

        for ($i = 0; $i < mysql_num_fields($result); $i++) {

            $fieldName = mysql_field_name($result, $i);
            $fieldValue = isset($_REQUEST[$fieldName]) ? $_REQUEST[$fieldName] : "";

            $ev = $this->getEnumValues($fieldName);

            if ((isset($fieldValue) && trim(strlen($fieldValue)) > 0) || (count($ev) == 2 &&
                (in_array('J', $ev) && in_array('N', $ev)))) {

                if (strlen($statement) > $chkLen) {
                    $statement .= ", ";
                }
                if (strlen($fieldValue) == 0 && !((count($ev) == 2 && in_array('J', $ev) && in_array('N', $ev))) ) {
                    $fieldValue = $this->getDefaultValue($this->DEFAULTS, $fieldName);
                }

                $fieldValue = str_replace("\\", "\\\\", $fieldValue);
                $fieldValue = str_replace("'", "\'", $fieldValue);

                // Feld gefüllt?
                if (strlen($fieldValue) == 0) {
                    if (strpos(mysql_field_flags($result, $i), "enum") > 0) {
                        $ev = $this->getEnumValues($fieldName);
                        if (count($ev) == 2 && (in_array('J', $ev) && in_array('N', $ev))) {
                            //Checkbox braucht Sonderbehandlung, da bei Wert=N  kein Wert übergeben wird!
                            $statement .= "" . $fieldName . " = 'N' ";
                        }
                    } else {
                        // NOT NULL - Feld?
                        $flags = mysql_fieldflags($result, $i);

                        if (strpos(" " . $flags, "not_null") > 0) {
                            if (mysql_field_type($result, $i) == "int" || mysql_field_type($result, $i) ==
                                "real") {
                                $statement .= "" . $fieldName . " = 0 ";
                            } else {
                                $statement .= "" . $fieldName . " = '' ";
                            }
                        } else {
                            $statement .= "" . $fieldName . " = null ";
                        }
                    }
                } else
                    if (mysql_field_type($result, $i) == "blob") {

                        $statement .= "" . $fieldName . " = '" . $fieldValue . "' ";

                    } else
                        if (mysql_field_type($result, $i) == "date") {

                            $statement .= "" . $fieldName . " = '" . $fieldValue . "' ";

                        } else
                            if (mysql_field_type($result, $i) == "int") {

                                $statement .= "" . $fieldName . " = " . $fieldValue . " ";

                            } else
                                if (mysql_field_type($result, $i) == "timestamp") {

                                    $statement .= "" . $fieldName . " = '" . $fieldValue . "' ";

                                } else
                                    if (strpos(mysql_field_flags($result, $i), "enum") > 0) {
                                        $ev = $this->getEnumValues($fieldName);
                                        if (count($ev) == 2 && (in_array('J', $ev) && in_array('N', $ev))) {
                                            //Checkbox braucht Sonderbehandlung, da bei Wert=N  kein Wert übergeben wird!
                                            if (isset($fieldValue)) {
                                                $statement .= "" . $fieldName . " = '" . $fieldValue . "' ";
                                            } else {
                                                $statement .= "" . $fieldName . " = 'N' ";
                                            }
                                        }

                                    } else
                                        if (mysql_field_type($result, $i) == "real") {
                                            if (isset($fieldValue)) {
                                                $statement .= "" . $fieldName . " = '" . str_replace(",", ".", $fieldValue) .
                                                    "' ";
                                            }
                                        } else {
                                            if (isset($fieldValue)) {
                                                $statement .= "" . $fieldName . " = '" . $fieldValue . "' ";
                                            }

                                        }
            }

            //Damit kein nicht versehentlich ein 2. Insert erfolgen kann, hier die Werte leeren
            $fieldValue = "";
        }

        $result = $this->DBCONNECT->executeQuery($statement);

        if ((!isset($_REQUEST['saveOK']) || (isset($_REQUEST['saveOK']) && $_REQUEST['saveOK'] !=
            "OK")) && $showOutput) {
            $e = new Message("Speichern", "Speichern erfolgreich");
            $_REQUEST['saveOK'] = "OK";
        }
        $this->refresh();
        return true;
    }


    //----------------------------------------------------------------------
    //  LÃƒÂƒÃ‚ÂƒÃƒÂ‚Ã‚ÂƒÃƒÂƒÃ‚Â‚ÃƒÂ‚Ã‚Â–SCHEN - MASKE und Verarbeitung
    //----------------------------------------------------------------------

    function showDeleteMask() {
        //Methode prüft selber ob gelöscht werden muss
        $this->doDelete();

        $tNames = $this->COLNAMES;
        $tNames[count($tNames)] = " ";

        $table = new Table($tNames);
        $table->setBorder(0);

        if ($this->WIDTH > 0) {
            $table->setWidth($this->WIDTH);
        }
        if ($this->HEIGHT > 0) {
            $table->setHeight($this->HEIGHT);
        }
        if ($this->BORDER >= 0) {
            $table->setBorder($this->BORDER);
        }
        if ($this->PADDING >= 0) {
            $table->setPadding($this->PADDING);
        }
        if ($this->SPACING >= 0) {
            $table->setSpacing($this->SPACING);
        }
        if ($this->XPOS > 0 && $this->YPOS > 0) {
            $table->setXPos($this->XPOS);
            $table->setYPos($this->YPOS);
        }


        //---------------------------------------------------
        // ROWS in Table aufnehmen
        //---------------------------------------------------
        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {
            $r = $table->createRow();
            for ($ia = 0; $ia < count($this->COLNAMES); $ia++) {
                $row = $this->ROWS[$ir];

                $r->setAttribute($ia, new Text($row->getAttribute($ia)));
            }
            $btnDel = new Button($this->ROWS[$ir]->getNamedAttribute("id"), "entfernen");
            $r->setAttribute(count($this->COLNAMES), $btnDel);

            $table->addRow($r);
        }

        $form = new Form($_SERVER['SCRIPT_NAME']);
        $form->add($table);
        $form->add($this->DEFAULT_HIDDEN_FIELDS);
        $form->show();
    }

    //----------------------------------------------------------------------


    function doDelete() {
        //generelle Delete-Statement bearbeitung
        //Zugeschnitten auf dynamische DeleteMaske (showDeleteMask)
        $ret = false;

        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {

            if (isset($_REQUEST[$this->ROWS[$ir]->getNamedAttribute("id")]) && $_REQUEST[$this->
                ROWS[$ir]->getNamedAttribute("id")] == "entfernen") {

                if (isset($_REQUEST['RowDeleteCommited']) && $_REQUEST['RowDeleteCommited'] ==
                    "wirklich entfernen") {

                    $statement = "DELETE FROM " . $this->TABLENAME . " WHERE id = " . $this->ROWS[$ir]->
                        getNamedAttribute("id");
                    $result = $this->DBCONNECT->executeQuery($statement);

                    $e = new Message("entfernen", "entfernen erfolgreich");

                    $this->refresh();
                    $ret = true;
                } else {

                    $tbl = new Table($this->COLNAMES);
                    $tbl->setStyle("background-color", "#ff5555");
                    $dataRow = $this->getRowById($this->ROWS[$ir]->getNamedAttribute("id"));

                    $r1 = $tbl->createRow();
                    $r1->setSpawnAll(true);

                    $txt = new Text("Wollen Sie den folgenden Datensatz wirklich entfernen?");
                    $r1->setAttribute(0, $txt);
                    $tbl->addRow($r1);

                    $tbl->addSpacer(1, 8);

                    $r = $tbl->createRow();
                    $c = 0;

                    foreach ($this->COLNAMES as $colname) {
                        $r->setAttribute($c, new Title($colname));
                        $c++;
                    }
                    $tbl->addRow($r);

                    $r = $tbl->createRow();
                    $c = 0;
                    foreach ($this->COLNAMES as $colname) {
                        $r->setAttribute($c, new Text($this->ROWS[$ir]->getNamedAttribute($colname)));
                        $c++;
                    }
                    $tbl->addRow($r);

                    $tbl->addSpacer(1, 5);

                    $rbtn = $tbl->createRow();
                    $rbtn->setSpawnAll(true);

                    $btnOk = new Button("RowDeleteCommited", "wirklich entfernen");
                    $btnCancle = new Button("Abbrechen", "Abbrechen", "location='" . $_SERVER['SCRIPT_NAME'] .
                        "'");
                    $div = new Div();
                    $div->add($btnOk);
                    $div->add($btnCancle);
                    $rbtn->setAttribute(0, $div);
                    $tbl->addRow($rbtn);

                    $delName = "delete" . $this->ROWS[$ir]->getNamedAttribute("id") . $this->
                        TABLENAME;

                    if (isset($_REQUEST[$delName])) {
                        $hiddenOk = new Hiddenfield($delName, $_REQUEST[$delName]);
                    } else {
                        $hiddenOk = new Hiddenfield($delName, "");
                    }

                    $frm = new Form($_SERVER['SCRIPT_NAME']);
                    $frm->add($tbl);
                    $frm->add($hiddenOk);
                    $frm->add($this->DEFAULT_HIDDEN_FIELDS);

                    $frm->show();
                }
            }
            return $ret;
        }

    }


    //----------------------------------------------------------------------


    function showUpdateMask() {
        $checkValues = true;
        $doUpdateActive = false;
        $deleteMask = null;
        
        if ($this->isDeleteInUpdate()) {
            $deleteMask = !$this->doDeleteFromUpdatemask() ? null : $this->doDeleteFromUpdatemask();
        }

        if (isset($_REQUEST['DbTableUpdate' . $this->TABLENAME]) 
            && $_REQUEST['DbTableUpdate' .$this->TABLENAME] == "Speichern" ) {
            $checkValues = $this->checkUpdateValue($_REQUEST['SingleUpdateRowId']);
            if($checkValues){
                $this->doUpdate();
            }
        } 
        
        if (!$checkValues){
            $_REQUEST["showUpdateMaskuser"] = $_REQUEST['SingleUpdateRowId'];    
        }
        
            
        $form = $this->getUpdateMask();
        if ($deleteMask != null) {
            $form->addInFront($deleteMask);
        }            
        $form->add($this->DEFAULT_HIDDEN_FIELDS);

        $form->show();
    }


    function isDbComboSet($tab, $col) {
        $sql = "SELECT * FROM dbcombos where tab_name = '" . $tab . "' AND col_name = '" .
            $col . "' limit 1";
        $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        if (mysql_num_rows($res) > 0) {
            return true;
        }
    }


    function getUpdateMask() {

        $tNames = $this->LABELS;
        $colNames = $this->COLNAMES;
        array_push($colNames, "Aktion");
        array_push($tNames, " ");

        $table = new Table($tNames);

        if (count($this->COLSIZES) > 0) {
            $table->setColSizes($this->COLSIZES);
        }
        if (count($this->ALIGNMENTS) > 0) {
            $table->setAlignments($this->ALIGNMENTS);
        }

        if (isset($_REQUEST["showUpdateMask" . $this->TABLENAME]) && strlen($_REQUEST["showUpdateMask" .$this->TABLENAME]) > 0) {
            $r = $table->createRow();
            $r->setSpawnAll(true);
            $r->setAttribute(0, $this->getSingleUpdateMask($_REQUEST["showUpdateMask" . $this->
                TABLENAME]));
            $rX = $table->getRow(0);
            $table->setRow(0, $r);
            $table->addRow($rX);
            $table->addSpacer(0, 15);
        }

        $table->setHeadEnabled($this->HEAD_ENABLED);
        $table->setBackgroundColorChange(true);

        if ($this->isDeleteInUpdate()) {
            $this->doDeleteFromUpdatemask();

            for ($i = 0; $i < count($tNames); $i++) {
                if ($i != count($tNames) - 1) {
                    $tmpW[$i] = "";
                } else {
                    $tmpW[$i] = "125";
                }
            }
            $table->setColSizes($tmpW);
        }


        if ($this->WIDTH > 0) {
            $table->setWidth($this->WIDTH);
        }
        if ($this->HEIGHT > 0) {
            $table->setHeight($this->HEIGHT);
        }
        if ($this->BORDER != null && strlen($this->BORDER) > 0) {
            $table->setBorder($this->BORDER);
        }
        if ($this->PADDING >= 0) {
            $table->setPadding($this->PADDING);
        }
        if ($this->HEAD_ENABLED) {
            $table->setHeadEnabled($this->HEAD_ENABLED);
        }
        if ($this->SPACING >= 0) {
            $table->setSpacing($this->SPACING);
        }
        if ($this->XPOS > 0 && $this->YPOS > 0) {
            $table->setXPos($this->XPOS);
            $table->setYPos($this->YPOS);
        }

        //---------------------------------------------------
        // gesamte Tabelle einlesen um Feldtypen zu ermitteln
        //---------------------------------------------------
        $stmnt = "SELECT " . $this->COLNAMESTRING . " FROM " . $this->TABLENAME .
            " LIMIT 1 ";
        $result = $this->DBCONNECT->executeQuery($stmnt);


        //---------------------------------------------------
        // ROWS in Table aufnehmen
        //---------------------------------------------------
        $bgCtr = 1;
        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {
            $r = $table->createRow();
            $r->setStyle("padding", "5px 5px");

            for ($ia = 0; $ia < count($this->COLNAMES); $ia++) {
                $row = $this->ROWS[$ir];
                $rowId = $this->ROWS[$ir]->getAttribute(count($this->COLNAMES));
                $val = "";
                $t = "";
                if (strlen($row->getAttribute($ia)) > 0) {
                    $val = getDbComboValue($this->TABLENAME, $this->COLNAMES[$ia], $row->
                        getAttribute($ia), $row);
                }
                // Wenn DbCombo definiert wurde wird der passende Text zum Code der Spalte angezeigt
                if (strlen($val) > 0) {
                    $t = $val;
                } else {
                    $t = $row->getAttribute($ia);
                }


                $txt = new Text($t);

                if (mysql_field_type($result, $ia) == "blob") {
                    $txt = new Text("...");
                    $fn = mysql_field_name($result, $ia);
                    $txt->setToolTip("<b>" . $fn . ":</b> " . $t);
                } else
                    if (mysql_field_type($result, $ia) == "date") {
                        $txt->setText($txt->getText());

                    } else {
                        //Maximal  ersten 30Stellen anzeigen

                        if (strlen($t) > 30) {
                            $tmp = $t;
                            if (strtolower($this->COLNAMES[$ia]) == "email") {
                                $tmp = "<a href='mailto:" . $t . "'>" . substr($t, 0, 30) . "..." . "</a>";
                            }

                            $txt->setFilter(false);
                            $txt->setText($tmp);
                            $txt->setToolTip($t);
                        } else {
                            $tmp = $t;
                            if (strtolower($this->COLNAMES[$ia]) == "email") {
                                $tmp = "<a href='mailto:" . $t . "'>" . $t . "</a>";
                            }
                            $txt->setFilter(false);
                            $txt->setText($tmp);
                        }
                    }

                    $r->setAttribute($ia, $txt);
            }

            $tblBtns = new Table(array("", ""));
            $tblBtns->setWidth(150);
        
            $frmUpdBtn = new Form();
            $frmUpdBtn->add($this->DEFAULT_HIDDEN_FIELDS);
            $frmUpdBtn->add(new Hiddenfield("showUpdateMask" . $this->TABLENAME, $rowId));
            $frmUpdBtn->add(new Button("editEtage", "bearbeiten"));
            

            //bearbeiten Link
            $btnUpd = new Link($_SERVER['SCRIPT_NAME'] . "?" . "showUpdateMask" . $this->
                TABLENAME . "=" . $rowId, "bearbeiten");
            $btnUpd->setValidate(false);
            //$div->add($btnUpd);

            // entfernen Link einfügen
            $frmDelBtn = new Form();
            $frmDelBtn->add($this->DEFAULT_HIDDEN_FIELDS);
            $frmDelBtn->add(new Button("delete" . $rowId . $this->TABLENAME, "entfernen"));

            $rBtns = $tblBtns->createRow();
            $rBtns->setAttribute(0, $frmUpdBtn);
            if ($this->isDeleteInUpdate()) {
                $rBtns->setAttribute(1,$frmDelBtn);
            } else {
                $rBtns->setSpawnAll(true);
            }
            $tblBtns->addRow($rBtns);
            
            $r->setAttribute(count($tNames) - 1, $tblBtns);

            $table->addRow($r);
        }


        $form = new Form($_SERVER['SCRIPT_NAME']);
        $form->add($this->getPageNavigation());
        $form->add($table);
        $form->add($this->DEFAULT_HIDDEN_FIELDS);

        return $form;
    }


    //----------------------------------------------------------------------

    function isDeleteActive() {
        foreach ($this->ROWS as $r) {
            $rowId = $r->getAttribute(count($this->COLNAMES));
            $delName = "delete" . $rowId . $this->TABLENAME;

            if (isset($_REQUEST[$delName])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Im Unterschied zu getUpdateMask() liefert diese Methode eine Tabelle in der Direkt alle Felder Eingabefelder sind, 
     *  somit kann man direkt mehrere Zeilen auf einmal bearbeiten. 
     */
    function getUpdateAllMask() {
        $tNames   = array(); 
        $colNames = array();
        
        $i = 0;
        foreach($this->COLNAMES as $colName){
            if(!$this->isNoUpdateCol($colName)){
                $lbl = "";
                if($i<count($this->LABELS)){
                  $lbl = $this->LABELS[$i];
                } else {
                  $lbl = $colName; 
                }
                array_push($tNames, $lbl);
                array_push($colNames, $colName);
            }
            $i++;
        }

        $deleteMask = null;
        if ($this->isDeleteInUpdate()) {
            $deleteMask = !$this->doDeleteFromUpdatemask() ? null : $this->
                doDeleteFromUpdatemask();
            // Damit die Spalte mit dem Entfernen Button
            // zur Verfügung steht, in Arrays einbinden.
            array_push($colNames, "entfernen");
            array_push($tNames, "entfernen");
        }

        $table = new Table($tNames);

        if (count($this->COLSIZES) > 0) {
            $table->setColSizes($this->COLSIZES);
        }

        $table->setHeadEnabled(true);
        $table->setBackgroundColorChange(true);
        if ($this->WIDTH > 0) {
            $table->setWidth($this->WIDTH);
        }
        if ($this->HEIGHT > 0) {
            $table->setHeight($this->HEIGHT);
        }
        if ($this->BORDER != null && strlen($this->BORDER) > 0) {
            $table->setBorder($this->BORDER);
        }
        if ($this->PADDING >= 0) {
            $table->setPadding($this->PADDING);
        }
        if ($this->HEAD_ENABLED) {
            $table->setHeadEnabled($this->HEAD_ENABLED);
        }
        if ($this->SPACING >= 0) {
            $table->setSpacing($this->SPACING);
        }
        if ($this->XPOS > 0 && $this->YPOS > 0) {
            $table->setXPos($this->XPOS);
            $table->setYPos($this->YPOS);
        }
        
        //---------------------------------------------------
        // gesamte Tabelle einlesen um Feldtypen zu ermitteln
        //---------------------------------------------------
        $cns = "";

        foreach($colNames as $cn){
            if(strpos(" ".$this->COLNAMESTRING, $cn)>0){
                $cns .= ($cns=="")?$cn:",".$cn;
            }
        }
        
        $stmnt = "SELECT " . $cns . " FROM " . $this->TABLENAME .
            " LIMIT 1 ";
        $result = $this->DBCONNECT->executeQuery($stmnt);


        //---------------------------------------------------
        // ROWS in Table aufnehmen
        //---------------------------------------------------
        $dbComboArrays = array();

        $bgCtr = 1;
        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {
            $r = $table->createRow();
            $r->setStyle("padding", "5px 5px");

            $rowId = $this->ROWS[$ir]->getAttribute(count($this->COLNAMES));

            $r->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_' . $bgCtr]);

            if ($bgCtr == 1) {
                $bgCtr = 2;
            } else {
                $bgCtr = 1;
            }

            //---------------------------------------------------
            // SPALTEN aufbauen
            //---------------------------------------------------
            for ($ia = 0; $ia < count($this->COLNAMES); $ia++) {
                $row = $this->ROWS[$ir];

                $t = "";
                $fieldName = mysql_field_name($result, $ia);
                $fieldLen = 30;
                
                if(!$this->isNoUpdateCol($fieldName)){
                    $maxLen = mysql_field_len($result, $ia);
    
                    if ($maxLen < $fieldLen) {
                        $fieldLen = $maxLen;
                    }
    
                    $lookups = getLookupWerte($_SESSION['config']->DBCONNECT, $this->TABLENAME, $fieldName);
                    if (mysql_num_rows($lookups) > 0) {
                        $t = new LookupCombo($_SESSION['config']->DBCONNECT, $fieldName . $rowId, $this->
                            TABLENAME, $fieldName, $row->getNamedAttribute($fieldName));
                    } else
                        if ($this->isDbComboSet($this->TABLENAME, $fieldName)) {
                            // Wenn DbCombo definiert wurde wird die Combobox zur Spalte angezeigt
                            //Wenn die Combobox noch nicht erzeugt wurde, erzeugen.
                            if (!(isset($dbComboArrays[$this->TABLENAME . $colNames[$ia]]))) {
                                $dbComboArrays[$this->TABLENAME . $colNames[$ia]] = getDbComboArray($this->
                                    TABLENAME, $colNames[$ia], $this->ROWS[$ir]);
                            }
    
                            $dbCombo = $dbComboArrays[$this->TABLENAME . $colNames[$ia]];
    
                            if (count($dbCombo) > 0) {
                                $default = $row->getAttribute($ia);
                                if (!existsKeyInArray($default, $dbCombo)) {
                                    $default = null;
                                }
                                $t = new ComboBox($fieldName . $rowId, $dbCombo, $default);
                            }
    
                        } else {
    
                            if (mysql_field_type($result, $ia) == "blob") {
                                $t = new TextArea($fieldName . $rowId, $row->getNamedAttribute($colNames[$ia]),
                                    round(70 / count($this->COLNAMES), 0), 4);
    
                            } else
                                if (strpos(mysql_field_flags($result, $ia), "enum") > 0) {
                                    $ev = $this->getEnumValues($fieldName);
    
                                    if (count($ev) == 2 && (in_array('J', $ev) && in_array('N', $ev))) {
                                        $t = new Checkbox($fieldName . $rowId);
    
                                        if ($row->getNamedAttribute($colNames[$ia]) == "J") {
                                            $t->setSelected(true);
                                        }
    
                                    } else {
                                        $t = new ComboBox($fieldName . $rowId, $this->getComboboxEnumArray($fieldName));
                                    }
    
                                } else
                                    if (mysql_field_type($result, $ia) == "int") {
                                        $t = new TextField($fieldName . $rowId, $row->getNamedAttribute($colNames[$ia]),
                                            $fieldLen, $maxLen);
    
                                    } else
                                        if (mysql_field_type($result, $ia) == "date") {
                                            //$t = new TextField($fieldName.$rowId,$row->getNamedAttribute($colNames[$ia]),  $tfWidth, mysql_field_len ( $result, $ia ) );
                                            $val = $row->getNamedAttribute($colNames[$ia]);
                                            $t = new DateTextfield($fieldName . $rowId, $val);
    
                                        } else
                                            if (mysql_field_type($result, $ia) == "timestamp") {
                                                $t = new TextField($fieldName . $rowId, $row->getNamedAttribute($colNames[$ia]),
                                                    $fieldLen, $maxLen);
    
                                            } else {
                                                $t = new TextField($fieldName . $rowId, $row->getNamedAttribute($colNames[$ia]),
                                                    $fieldLen, $maxLen);
                                            }
    
                                            $arrChk = array_search($fieldName, $this->READONLYCOLS);
                            if (strlen($arrChk) != 0) {
                                $t->setReadOnly(true);
                            }
    
                        }
                        // Eingabe-Objekt in Zeile einfügen
                        $r->setAttribute($ia, $t);
                } else {
                    $r->setAttribute($ia, "");
                }
            }

            // entfernen Button einfügen
            if ($this->isDeleteInUpdate()) {
                $btnDel = new Button("delete" . $rowId . $this->TABLENAME, "entfernen");
                $r->setAttribute(count($tNames) - 1, $btnDel);
            }
            $table->addRow($r);
        }


        // Speichern/Abbrechen Button einfügen
        $btnOk = new Button("DbTableUpdate" . $this->TABLENAME, "Speichern");
        $btnOkFake = new Button("DbTableUpdate" . $this->TABLENAME, "Speichern");
        $btnOkFake->setStyle("display", "none");
        $btnOkFake->setWidth(0);
        $btnOkFake->setHeight(0);
        $btnCancel = new Button("", "Abbrechen");

        $hidden = new Hiddenfield("UpdateAllMaskIsActive", "true");

        $form = new Form($_SERVER['SCRIPT_NAME']);
        $form->add($this->DEFAULT_HIDDEN_FIELDS);

        if ($deleteMask != null) {
            $form->add($deleteMask);
        }

        $form->add($btnOkFake);
        $form->add($this->getPageNavigation());
        $form->add($table);
        $form->add($btnOk);
        $form->add($btnCancel);
        $form->add($hidden);

        return $form;
    }


    function getSingleUpdateMask($rowId) {
        $tblAll = new Table(array(""));

        if ($rowId == null || $rowId == "") {
            return $tblAll;
        }

        $f1 = new FontType();
        $f1->setFontSize(2);
        $f1->setBold(true);

        $f2 = new FontType();

        $fts = array($f1, $f2);

        $table = new Table(array("", ""));
        $table->setHeadEnabled(false);
        $table->setBorder(0);
        $table->setFontTypes($fts);

        $table->setAlign($this->getAlign());
        $table->setVAlign($this->getVAlign());
        $table->setAlignments($this->getAlignments());

        if ($this->WIDTH > 0) {
            $table->setWidth($this->WIDTH);
        }
        if ($this->HEIGHT > 0) {
            $table->setHeight($this->HEIGHT);
        }
        if ($this->BORDER != null && strlen($this->BORDER) > 0) {
            $table->setBorder($this->BORDER);
        }
        if ($this->PADDING >= 0) {
            $table->setPadding($this->PADDING);
        }
        if ($this->HEAD_ENABLED) {
            $table->setHeadEnabled($this->HEAD_ENABLED);
        }
        if ($this->SPACING >= 0) {
            $table->setSpacing($this->SPACING);
        }
        if ($this->XPOS > 0 && $this->YPOS > 0) {
            $table->setXPos($this->XPOS);
            $table->setYPos($this->YPOS);
        }

        $chk = 0;
        $stmnt = "SELECT ";
        foreach ($this->COLNAMES as $cn) {
            if ($stmnt != "SELECT ") {
                $stmnt .= ", ";
            }
            $stmnt .= $cn;

            $chk++;
        }
        $stmnt .= ", id as rowid ";
        $stmnt .= " FROM " . $this->TABLENAME . "  where id = " . $rowId . " " . $this->
            ORDERBY . " LIMIT 1 ";

        $result = $this->DBCONNECT->executeQuery($stmnt);
        $rowEdit = mysql_fetch_array($result);

        for ($i = 0; $i < mysql_num_fields($result) - 1; $i++) {
            $fieldName = mysql_field_name($result, $i);

            $arrChk = array_search($fieldName, $this->NOUPDATECOLS);
            if (strlen($arrChk) == 0) {
                $r = $table->createRow();

                $o = "";
                $lookups = getLookupWerte($_SESSION['config']->DBCONNECT, $this->TABLENAME, $fieldName);
                // in der Datenbank für dieses Datenbankfeld
                // definierte Combobox laden (wenn vorhanden)
                $dbCombo = getDbComboArray($this->TABLENAME, $fieldName, $rowEdit);

                $val = "";
                if (isset($rowEdit[$fieldName]) && strlen($rowEdit[$fieldName]) > 0) {
                    $val = $rowEdit[$fieldName];
                }

                if (mysql_num_rows($lookups) == 0 && !$this->isDbComboSet($this->TABLENAME, $fieldName)) {

                    /*if (strpos(" " . $this->DEFAULTS, $fieldName) > 0) {
                    $tmpval = substr($this->DEFAULTS, strpos($this->DEFAULTS, "=") + 1);
                    $o = new HiddenField($fieldName, $tmpval);

                    } else*/
                    if (strpos(mysql_field_flags($result, $i), "enum") > 0) {
                        $ev = $this->getEnumValues($fieldName);

                        if (count($ev) == 2 && (in_array('J', $ev) && in_array('N', $ev))) {
                            $o = new Checkbox($fieldName . $rowId, "", "J");

                            if ($rowEdit[$fieldName] == "J") {
                                $o->setSelected(true);
                            }

                        } else {
                            $o = new ComboBox($fieldName . $rowId, $this->getComboboxEnumArray($fieldName));
                        }

                    } else
                        if (mysql_field_type($result, $i) == "blob") {
                            $o = new TextArea($fieldName . $rowId, $val, 80, 10);
                            $o->setTextEditor($this->TEXTEDITOR_ENABLED);

                        } else
                            if (mysql_field_type($result, $i) == "date") {
                                $o = new DateTextfield($fieldName . $rowId, $val);
                                $o->setToolTip("Bitte im Format:  <b>YYYY-MM-TT</b>  angeben");

                            } else
                                if (mysql_field_type($result, $i) == "int") {
                                    $o = new TextField($fieldName . $rowId, $val);

                                } else
                                    if (mysql_field_type($result, $i) == "timestamp") {
                                        $o = new TextField($fieldName . $rowId, $val);

                                    } else {
                                        $o = new TextField($fieldName . $rowId, $val);
                                    }

                } else {
                    if (mysql_num_rows($lookups) > 0) {
                        $o = new LookupCombo($_SESSION['config']->DBCONNECT, $fieldName . $rowId, $this->
                            TABLENAME, $fieldName, $rowEdit[$fieldName]);
                    } else
                        if (count($dbCombo) > 0) {
                            if (!strpos(" " . $this->DEFAULTS, $fieldName) > 0) {
                                $o = new ComboBox($fieldName . $rowId, $dbCombo, $val);
                            } else {
                                $o = new HiddenField($fieldName . $rowId, $val);
                            }
                        }
                }

                if ($i < count($this->LABELS) ) {
                    $r->setAttribute(0, $this->LABELS[$i]);
                } else {
                    $r->setAttribute(0, "");
                }

                $arrChk = array_search($fieldName, $this->READONLYCOLS);
                if (strlen($arrChk) != 0) {
                    $o->setReadOnly(true);
                }

                $r->setAttribute(1, $o);

                $table->addRow($r);
            }
        }
        
        foreach($this->ADDITIONAL_UPDATE_FIELDS as $label=>$field){
                $r = $table->createRow();
                $r->setAttribute(0, $label);
                $r->setAttribute(1, $field);
                $table->addRow($r);
        }

        $okButton = new Button("DbTableUpdate" . $this->TABLENAME, "Speichern");
        $r = $table->createRow();
        $r->setSpawnAll(true);
        $r->setAttribute(0, $okButton);
        $table->addRow($r);

        $rowAll1 = $tblAll->createRow();
        $rowAll2 = $tblAll->createRow();

        $rowAll1->setAttribute(0, $table);

        $tblAll->addRow($rowAll1);
        $tblAll->addRow($rowAll2);


        $f = new Form($_SERVER['SCRIPT_NAME']);
        $f->add($tblAll);
        $f->add(new Hiddenfield("SingleUpdateRowId", $rowId));
        $f->add($this->DEFAULT_HIDDEN_FIELDS);
        return $f;
    }


    function doUpdate() {
        //generelle Update bearbeitung
        //Zugeschnitten auf dynamische UpdateMaske (showUpdateMask)
        $updateDo = false;

        $this->refresh();
        //---------------------------------------------------
        // gesamte Tabelle einlesen um Feldtypen zu ermitteln
        //---------------------------------------------------
        $stmnt = "SELECT " . $this->COLNAMESTRING . " FROM " . $this->TABLENAME ." LIMIT 1 ";
        $result = $this->DBCONNECT->executeQuery($stmnt);
        $chk = 0;
        $sql = "";

        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {
            $rowId = $this->ROWS[$ir]->getAttribute(count($this->FIELDNAMES));
            
            if ((isset($_REQUEST['SingleUpdateRowId']) && $rowId == $_REQUEST['SingleUpdateRowId']) ||
                isset($_REQUEST['UpdateAllMaskIsActive'])) {
                $chk = 0;
                $sql = "";

                for ($ia = 0; $ia < count($this->FIELDNAMES); $ia++) {
                    $fieldName = mysql_field_name($result, $ia);
                    $row = $this->ROWS[$ir];
                    $x = $fieldName . $rowId;

                    $ev = $this->getEnumValues($fieldName);

                    if ($chk > 0 && ((isset($_REQUEST[$x]) && strlen($_REQUEST[$x]) >= 0) || (count($ev) == 2 && in_array('J', $ev) && in_array('N', $ev)))  ) {
                        $sql .= ", ";
                    }
                    if (isset($_REQUEST[$x]) && strlen($_REQUEST[$x]) > 0) {
                        $val = $_REQUEST[$x];
                        // echo "neuer Wert: "+$val;
                        if (mysql_field_type($result, $ia) == "real") {
                            if (isset($val)) {
                                $val = str_replace(",", ".", $val);
                            }
                        }
                        $sql .= $fieldName . " = '" . str_replace("'", "''", $val) . "' ";
                        $chk++;
                    } elseif (count($ev) == 2 && (in_array('J', $ev) && in_array('N', $ev))) {
                        //Checkbox braucht Sonderbehandlung, da bei Wert=N  kein Wert übergeben wird!
                        $sql .= $fieldName . " = 'N' ";
                        $chk++;
                    } else {
                        if (isset($_REQUEST[$x]) && strlen($_REQUEST[$x]) == 0) { // && strpos(" " . $this->DEFAULTS, $fieldName) <= 0
                            $sql .= $fieldName . " = null ";
                            $chk++;
                        }
                    }
                }


                if ($chk > 0 && $this->checkUpdateValue($rowId)) {
                    $sql = "UPDATE " . $this->TABLENAME . " SET " . $sql;

                    //   if (strlen(trim($this->DEFAULTS)) > 0) {
                    //       $sql = $sql . ", " . $this->DEFAULTS;
                    //   }

                    $sql = $sql . " WHERE id = " . $rowId;
                          echo $sql."<br>";
                    $this->DBCONNECT->executeQuery($sql);

                    $updateDo = true;
                    if (!isset($_REQUEST['UpdateAllMaskIsActive'])) {
                        if (!isset($_REQUEST['saveOK']) || (isset($_REQUEST['saveOK']) && $_REQUEST['saveOK'] !=
                            "OK")) {
                            $e = new Message("Speichern", "Erfolgreich gespeichert");
                            $_REQUEST['saveOK'] = "OK";
                        }
                        $this->refresh();
                        return true;
                    }
                }
            }
        }

        if ($updateDo) {
            if (!isset($_REQUEST['saveOK']) || (isset($_REQUEST['saveOK']) && $_REQUEST['saveOK'] !=
                "OK")) {
                $e = new Message("Speichern", "Erfolgreich gespeichert");
                $_REQUEST['saveOK'] = "OK";
            }
            $this->refresh();
            return true;
        } else {
            if (!isset($_REQUEST['saveOK']) || (isset($_REQUEST['saveOK']) && $_REQUEST['saveOK'] !=
                "OK")) {
                $e = new Message("Speichern", "Nichts zu speichern");
                $_REQUEST['saveOK'] = "OK";
            }
            return false;
        }
    }

    //----------------------------------------------------------------------


    function doDeleteFromUpdatemask() {
        //generelle Delete bearbeitung
        //Zugeschnitten auf dynamische UpdateMaske (showUpdateMask)
        $ret = false;

        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {
            $rowId = $this->ROWS[$ir]->getAttribute(count($this->COLNAMES));
            $delName = "delete" . $rowId . $this->TABLENAME;
            if (isset($_REQUEST[$delName])) {
                if (isset($_REQUEST['RowDeleteCommited']) && $_REQUEST['RowDeleteCommited'] ==
                    "Wirklich entfernen") {

                    $rowId = $this->ROWS[$ir]->getAttribute(count($this->COLNAMES));
                    $chk = 0;
                    $sql = "";
                    $row = $this->ROWS[$ir];

                    // Wenn Zeile gelöscht werden soll
                    $statement = "DELETE FROM " . $this->TABLENAME . " WHERE id = " . $rowId . " ";
                    $result = $this->DBCONNECT->executeQuery($statement);

                    $e = new Message("entfernen", "Entfernen erfolgreich ");
                    $this->refresh();
                    $ret = true;

                    $this->postDelete($rowId);
                } else {

                    $tbl = new Table($this->COLNAMES);
                    $dataRow = $this->getRowById($this->ROWS[$ir]->getNamedAttribute("id"));
                    $tbl->setStyle("background-color", "#ee9999");
                    $tbl->setStyle("padding", "5px");

                    $r1 = $tbl->createRow();
                    $r1->setSpawnAll(true);

                    $txt = new Text("Wollen Sie den folgenden Datensatz wirklich entfernen?");
                    $r1->setAttribute(0, $txt);
                    $tbl->addRow($r1);

                    $tbl->addSpacer(1, 8);

                    $r = $tbl->createRow();

                    $c = 0;
                    foreach ($this->COLNAMES as $colname) {
                        $r->setAttribute($c, new Title($colname));
                        $c++;
                    }
                    $tbl->addRow($r);


                    $r = $tbl->createRow();
                    $c = 0;
                    foreach ($this->COLNAMES as $colname) {
                        $r->setAttribute($c, new Text($this->ROWS[$ir]->getNamedAttribute($colname)));
                        $c++;
                    }
                    $tbl->addRow($r);

                    $tbl->addSpacer(1, 5);

                    $rbtn = $tbl->createRow();
                    $rbtn->setSpawnAll(true);

                    $btnOk = new Button("RowDeleteCommited", "Wirklich entfernen");
                    $btnCancle = new Button("Abbrechen", "Abbrechen", "location='" . $_SERVER['SCRIPT_NAME'] .
                        "'");
                    $div = new Div();
                    $div->add($btnOk);
                    $div->add($btnCancle);
                    $rbtn->setAttribute(0, $div);
                    $tbl->addRow($rbtn);


                    $hiddenOk = new Hiddenfield($delName, $_REQUEST[$delName]);

                    //$frm = new Form($_SERVER['SCRIPT_NAME']);
                    $frm = new Div();
                    $frm->add($tbl);
                    $frm->add($hiddenOk);
                    $frm->add($this->DEFAULT_HIDDEN_FIELDS);
                    return $frm;
                }
            }
        }
        return $ret;
    }

    /**
     * Funktion zum überschreiben für eventuell 
     * nach löschen erforderlichen Ereignissen
     */
    function postDelete($id) {

    }

    //----------------------------------------------------------------------

    function getSearchMask() {

        $ft = new FontType();
        $ft->setFontsize(2);

        $table = new Table(array(""));
        $table->setFonttypes(array($ft));
        $table->setHeadEnabled(false);
        $table->setDesignJN("J");

        $r = $table->createRow();
        $r->setAttribute(0, new Text("Geben Sie hier ihren Suchbegriff ein"));
        $table->addRow($r);

        $r = $table->createRow();
        $r->setAttribute(0, new Textfield("SuchString"));
        $table->addRow($r);


        $hidden = new Hiddenfield($this->TABLENAME . "SEARCH", "doSearch");


        $form = new Form($_SERVER['SCRIPT_NAME']);

        $form->add($hidden);
        $form->add($table);

        $form->show();

    }


    function show() {
        $d = $this->getPageNavigation();
        $d->show();
        $table = $this->getShowMask();
        $table->show();

    }


    function getRowById($id) {
        foreach ($this->ROWS as $row) {
            $idX = $row->getNamedAttribute("id");

            if ($id == $idX) {
                return $row;
            }
        }
        return false;
    }


    function getShowMask() {
        $tNames = $this->COLNAMES;
        if (count($this->LABELS) == count($this->COLNAMES)) {
            $tNames = $this->LABELS;
        }
        $table = new Table($tNames);
        $table->setAlignments($this->getAlignments());
        $table->setBorder($this->BORDER);
        $table->setHeadEnabled($this->HEAD_ENABLED);
        $table->setBackgroundColorChange(true);

        if ($this->WIDTH > 0) {
            $table->setWidth($this->WIDTH);
        }
        if ($this->HEIGHT > 0) {
            $table->setHeight($this->HEIGHT);
        }
        if ($this->BORDER >= 0) {
            $table->setBorder($this->BORDER);
        }
        if ($this->PADDING >= 0) {
            $table->setPadding($this->PADDING);
        } else {
            $table->setPadding(0);
        }

        if ($this->SPACING >= 0) {
            $table->setSpacing($this->SPACING);
        } else {
            $table->setSpacing(0);
        }

        if ($this->XPOS > 0 && $this->YPOS > 0) {
            $table->setXPos($this->XPOS);
            $table->setYPos($this->YPOS);
        }


        //---------------------------------------------------
        // ROWS in Table aufnehmen
        //---------------------------------------------------
        $bgCtr = 1;

        for ($ir = 1; $ir <= count($this->ROWS); $ir++) {
            $r = $table->createRow();

            for ($ia = 0; $ia < count($this->COLNAMES); $ia++) {
                $row = $this->ROWS[$ir];
                $val = "";
                $t = "";
                if (strlen($row->getAttribute($ia)) > 0) {
                    $val = getDbComboValue($this->TABLENAME, $this->COLNAMES[$ia], $row->
                        getAttribute($ia));
                }

                // Wenn DbCombo definiert wurde wird der passende Text zum Code der Spalte angezeigt
                if (strlen($val) > 0) {
                    $t = $val;
                } else {
                    $t = $row->getAttribute($ia);
                }


                if (strtolower($this->COLNAMES[$ia]) == "email") {
                    $txt = $row->getAttribute($ia);

                    $tmp = new Text($txt);
                    $tmp->setFilter(false);

                    $r->setAttribute($ia, new Link("mailto:" . $txt, $tmp));
                } else {

                    $r->setAttribute($ia, new Text($t));

                }

            }

            $table->addRow($r);
        }

        return $table;
    }


    function insertRowByArray($array) {
        if (count($array) > 0 && count($array) <= count($this->COLNAMES)) {
            $statement = "INSERT INTO " . $this->TABLENAME . " SET ";
            $chkLen = strlen($statement);
            $chk = 0;
            $stmnt = "SELECT  ";
            foreach ($this->COLNAMES as $cn) {
                if ($chk > 0) {
                    $stmnt .= ", ";
                }
                $stmnt .= $cn;
                $chk++;
            }
            $stmnt .= " FROM " . $this->TABLENAME . " LIMIT 1 ";
            $result = $this->DBCONNECT->executeQuery($stmnt);


            for ($i = 0; $i < count($array); $i++) {
                $fieldName = mysql_field_name($result, $i);

                if (strlen($statement) > $chkLen) {
                    $statement .= ", ";
                }

                if (strlen($array[$i]) > 0) {
                    if (mysql_field_type($result, $i) == "blob") {
                        $statement .= "" . $fieldName . " = '" . str_replace("'", "''", $array[$i]) .
                            "' ";

                    } else
                        if (mysql_field_type($result, $i) == "date") {
                            $statement .= "" . $fieldName . " = '" . str_replace("'", "''", $array[$i]) .
                                "' ";

                        } else
                            if (mysql_field_type($result, $i) == "int") {
                                $statement .= "" . $fieldName . " = " . str_replace("'", "''", $array[$i]) . " ";

                            } else
                                if (mysql_field_type($result, $i) == "timestamp") {
                                    $statement .= "" . $fieldName . " = '" . str_replace("'", "''", $array[$i]) .
                                        "' ";

                                } else {
                                    $statement .= "" . $fieldName . " = '" . str_replace("'", "''", $array[$i]) .
                                        "' ";

                                }
                } else {
                    $statement .= "" . $fieldName . " = null ";
                }
            }

            if (strlen($this->DEFAULTS) >= 3) {
                $statement .= ", " . $this->DEFAULTS;
            }

            $result = $this->DBCONNECT->executeQuery($statement);
            $this->refresh();
        }
    }


    function getEnumValues($field) {
        $result = @mysql_query("show columns from {$this->TABLENAME} like \"$field\"");
        $result = @mysql_fetch_assoc($result);

        if ($result["Type"]) {
            preg_match("/(enum\((.*?)\))/", $result["Type"], $enumArray);
            $enumFields = array();

            if (count($enumArray) > 0) {
                $getEnumSet = explode("'", $enumArray["2"]);
                $getEnumSet = preg_replace("/,/", "", $getEnumSet);
                foreach ($getEnumSet as $enumFieldValue) {
                    if ($enumFieldValue) {
                        $enumFields[] = $enumFieldValue;
                    }
                }
            }
            return $enumFields;
        }

        $e = new Error("Fehler in getEnumValues()",
            "Fehler beim holen der Enum-Werte für  Feld {$field} aus Tabelle {$this->TABLENAME}");
    }


    function getComboboxEnumArray($fieldName) {
        $ret = array();

        foreach ($this->getEnumValues($fieldName) as $value) {
            $ret[$value] = $value;
        }

        return $ret;
    }

    function getNewEntryButtonName($postFix = "") {
        if ($postFix == "") {
            $postFix = $this->TABLENAME;
        }
        return "dbTableNew" . $postFix;
    }

    function getNewEntryButton($text = "Neuen Eintrag", $postFix = "") {
        $insertButton = new Button($this->getNewEntryButtonName($postFix), $text);

        $form = new Form($_SERVER['SCRIPT_NAME']);
        $form->add($insertButton);
        $form->add($this->DEFAULT_HIDDEN_FIELDS);

        return $form;
    }


    function showNewEntryButton() {
        $xs = $this->getNewEntryButton();
        $xs->show();
    }


}

?>
