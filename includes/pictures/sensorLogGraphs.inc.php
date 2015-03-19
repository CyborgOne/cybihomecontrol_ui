<?PHP
  if(isset($_REQUEST['sensorId']) && strlen($_REQUEST['sensorId'])>0){
    header("Content-type: image/png");
    include_once('../../classes/external/phpMyGraph5.0.php');
    
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

    //Set data
    $data = array(
        'Jan' => 12,
        'Feb' => 25,
        'Mar' => 0,
        'Apr' => 7,
        'May' => 80,
        'Jun' => 67,
        'Jul' => 45,
        'Aug' => 66,
        'Sep' => 23,
        'Oct' => 23,
        'Nov' => 78,
        'Dec' => 23
    );
    


    //Create phpMyGraph instance
    $graph = new phpMyGraph();
    $graph->parseVerticalLineGraph($data, $cfg);


} else {
  exit("Parameter: sensorId  muss eingegeben werden");
}
?>