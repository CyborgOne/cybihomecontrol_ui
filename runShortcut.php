<?PHP
include ("config/dbConnect.php");
include ("classes/objects/database/DbConnect.php");

include ("functions/global.php");
include ("functions/homecontrol_functions.php");if (!isset($_REQUEST['shortcutName']) ) {
    parse_str($argv[1]);
}

if (!isset($_REQUEST['shortcutName']) ) {
  exitMissingValues();
}

$SHORTCUTS_URL_COMMAND = "";
$shortcutName   = $_REQUEST['shortcutName'];

if ( strlen($shortcutName) <= 0 ) {
  exitMissingValues();
}

$link = mysql_connect($DBHOST, $DBUSER, $DBPASS);
if (!$link) {
    die('Verbindung schlug fehl: ' . mysql_error());
}
mysql_select_db($DBNAME, $link) or die('Could not select database.');


/**********************
 *    Script Start
 **********************/

// URL-Aufruf ermitteln
// Wenn keine Schaltvorgaenge notwendig sind (nur Status-Update)
// wird ein Leerstring zurueckgeliefert
$SENSOR_URL_COMMAND = getShortcutSwitchKeyByName($shortcutName);

// HTML Ausgabe
echo "
<html>
  <head>
    <title>Shortcut schalten</title>
  </head>
  <body>
    Shortcut: " . $shortcutName . "</br>
    " .$SENSOR_URL_COMMAND . "</br>
  </body>
</html>
";

mysql_close($link);
// HTML-Daten an Browser senden,
// bevor Schaltvorgaenge ausgeloest werden.

ob_implicit_flush();
ob_end_flush();
flush();



// Wenn auszufuehrendes Kommando gefunden wurde, ausfuehren
if(strlen($SENSOR_URL_COMMAND)>0){
    $dc = new DbConnect($DBHOST, $DBUSER, $DBPASS, $DBNAME);
    switchShortcut($arduinoUrl, $SENSOR_URL_COMMAND, $dc);
}












function exitMissingValues(){
  echo "<html>
    <head>
      <title>Input: FAILED!</title>
    </head>
    <body>
      FEHLER!</br>
      shortcutName muss angegeben werden!
    </body>
  </html>
  ";
  exit("shortcutName muss angegeben werden!");
}


?>
