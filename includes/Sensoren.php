<?PHP

$t = new Title("Sensoren");
$t->show();

$sensorDBTbl = new DbTable( $_SESSION['config']->DBCONNECT, 
                            "homecontrol_sensor", 
                            array("*"),
                            "",
                            "",
                            "etage, zimmer, name",
                            "");
$cnt=0;           
$bgCtr = 1;          
foreach($sensorDBTbl->ROWS as $row){
    $s = new HomeControlSensor($row);
    if($cnt==0){
        $s->setWithHeader(true);
    }
    $s->setBgId($cnt);

    $s->show();
    $cnt++;
}

$spc = new Spacer();
$spc->show();

?>