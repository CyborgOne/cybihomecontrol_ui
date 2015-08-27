<?PHP
include ("config/dbConnect.php");

$link = mysql_connect($DBHOST, $DBUSER, $DBPASS);
if (!$link) {
    die('Verbindung schlug fehl: ' . mysql_error());
}
mysql_select_db($DBNAME, $link) or die('Could not select database.');



$sql = "SELECT value FROM pageconfig WHERE name = 'sensorlogDauer'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$loggingDays = $row['value'];

 
// MySQL UPDATE
$sql = "DELETE homecontrol_sensor_log WHERE update_time < ".time()-(60*60*24*$loggingDays);
$result = mysql_query($sql);

mysql_close($link);

?>
