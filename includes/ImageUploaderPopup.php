<?PHP
  if( isset($_REQUEST['targetPath']) && strlen($_REQUEST['targetPath'])>0 ){
    $dbInsTbl = "";
    $dbInsCol = "";
    $dbInsId  = "";
    $prefix   = "";
    
	if(isset($_REQUEST['filePrefix']) && strlen($_REQUEST['filePrefix'])>0 ){
		$prefix = $_REQUEST['filePrefix'];
	}

	if( isset($_REQUEST['DbInsertTable']) && strlen($_REQUEST['DbInsertTable'])>0 &&
	    isset($_REQUEST['DbInsertCol']) && strlen($_REQUEST['DbInsertCol'])>0 &&
		isset($_REQUEST['DbInsertId']) && strlen($_REQUEST['DbInsertId'])>0 ){
	
	    $dbInsTbl = $_REQUEST['DbInsertTable'];
	    $dbInsCol = $_REQUEST['DbInsertCol'];
    	$dbInsId  = $_REQUEST['DbInsertId'];
	}
    
    $imgUploader = new ImageUploader($_REQUEST['targetPath'], $prefix, $dbInsTbl, $dbInsCol, $dbInsId);
	$imgUploader->show();
  }
  
?>