<?php
// Filename: DbRow.php

class DbRow extends Object{
  var $ROW;        // Array welches die Zeile darstellt
  var $COLNAMES;   // Array welches die Namen der Spalten enthÃÂÃÂÃÂÃÂ¤lt 
  var $FONTTYPES;  // Array welches die Schriftformatierung fÃÂÃÂÃÂÃÂ¼r einzelne Spalten vorgibt
  private $TABLENAME;
  private $FIELDNAMES; 
  var $FORCE_ID_UPDATE=false; 
  
  function DbRow($colNamesArray, $TableName, $fldNames){
  	$this->TABLENAME = $TableName;
    $this->ROW = array();
    $this->setColNames($colNamesArray);
		//Spaltennamen Komma-getrennt verketten
	    $COLNAMESTRING = "";
	    if(count($this->COLNAMES)>0){
	    	$chk=0;
			foreach($this->COLNAMES as $cn){
		      if($chk>0){
		 	    $COLNAMESTRING .= ", ";
		      }
		      $COLNAMESTRING .= $cn;
		    
		      $chk++;
		    }
	    } else {
			$COLNAMESTRING = "*";
		}
	
    
    $this->FIELDNAMES = $fldNames;
    $this->prepareFonts();
  }


  function prepareFonts(){
     //legt Standard-Fonttypes an
     $fts = array();

     for( $i=0; $i<count($this->COLNAMES); $i++ ){
	    $fts[$i] = new Fonttype();
     }
     
     $this->FONTTYPES = $fts;
  }


  function setColNames($NameArray){
     $this->COLNAMES = $NameArray;
  }


  /**
  * gibt den im DbTable ermittelten Wert zurÃÂÃÂÃÂÃÂ¼ck (wenn nicht geÃÂÃÂÃÂÃÂ¤ndert)
  *
  * AUSNAHME: Datum -> wird im Standard-Format zurÃÂÃÂÃÂÃÂ¼ckgegeben.
  */
  function getAttribute($colIndex){
  	$ret = "";
  	$tn1 = explode(',', $this->TABLENAME);
	$preTabAliasx = explode(' ', $tn1[0]);
	
	//prÃÂÃÂÃÂÃÂ¼ft ob aliase verwendet werden
	if(count($preTabAliasx)>1){
		$preTabAlias = $preTabAliasx[1].".";
	}else{
		$preTabAlias = "";
	}
	
	//Spaltennamen Komma-getrennt verketten
    $COLNAMESTRING = "";
    if(count($this->COLNAMES)>0){
    	$chk=0;
		foreach($this->COLNAMES as $cn){
	      if($chk>0){
	 	    $COLNAMESTRING .= ", ";
	      }
	      $COLNAMESTRING .= $cn;
	    
	      $chk++;
	    }
    } else {
		$COLNAMESTRING = "*";
	}
	
	//Leer? dann Abbruch
	if(strlen($COLNAMESTRING)==0){
		exit();
	}
	
    //Gebe Wert aus Row zurÃÂÃÂÃÂÃÂ¼ck wenn vorhanden
	// Datum wird hier noch formatiert 
	if (isset($this->ROW[$colIndex])){
      $result = $_SESSION['config']->DBCONNECT->executeQuery("SELECT " .$COLNAMESTRING .", " 
	         .$preTabAlias ."id as rowid  FROM " .$this->TABLENAME ." " 
			 ." LIMIT 1");
	  $ret = $this->ROW[$colIndex];
	  if (mysql_field_type($result, $colIndex) == "date"){
        $ret = getFormatedDate($ret,"standard");
      }
    }
    
    return $ret; 
  }

  function setTablename($tn){
     $this->TABLENAME = $tn;
  }

  function getTablename(){
    return $this->TABLENAME;
  }


  function getColCount(){
     return count($this->COLNAMES) ;
  }

  function setAttribute($colIndex, $obj){
    //mÃÂÃÂÃÂÃÂ¼sste eigntl. ">" sein aber ist ">=" da rowid sonst hier nicht gesetzt werden kann
     if($this->getColCount()>=$colIndex){
       $this->ROW[$colIndex] = $obj;
     }
  }
  function setFonttypes($f){
    $this->FONTTYPES = $f;
  }


  function getNamedAttribute($colName){
	for ( $i=0;  $i<count($this->FIELDNAMES);  $i++ ){
      if($this->FIELDNAMES[$i]==$colName){
        return $this->getAttribute($i);
      }
    }
    if($colName=="rowid"){
		return $this->getAttribute(count($this->COLNAMES));
	}
  }


  function setNamedAttribute($colName, $text){
	for ( $i=0;  $i<count($this->FIELDNAMES);  $i++ ){
      if($this->FIELDNAMES[$i]==$colName){
        $this->ROW[$i] = $text;
      }
    }
  }

  

  function insertIntoDB(){
  	$COLNAMESTRING = "";
  	
    if(count($this->COLNAMES)>0){
    	$chk=0;
        $i=0;
	foreach($this->COLNAMES as $cn){
          if(strlen($this->getAttribute($i))>0){
    	      if($chk>0){
    	 	    $COLNAMESTRING .= ", ";
    	      }
  	          $COLNAMESTRING .= $cn;
    	      $chk++;
          }
          $i++;
	}
    } 
	
	if(strlen($COLNAMESTRING)==0){
		return;
	}
  	
    $sql = "insert into ".$this->TABLENAME." (" .$COLNAMESTRING .") VALUES (";
	$first=true;
	for($i=0;$i<count($this->COLNAMES);$i++){
	    if(strlen($this->getAttribute($i))>0){
    		if(!$first){
    			$sql .= ", ";
    		}
            $sql .= "'" .$this->getAttribute($i) ."' ";
           	$first=false;
        }
	}	
	
	$sql .= ") ";
//echo $sql;
	$_SESSION['config']->DBCONNECT->executeQuery($sql);
  }

  

  function deleteFromDb(){
	if(count($this->COLNAMES)==0){
		return;
	}
  	
    $sql = "DELETE FROM ".$this->TABLENAME." ";
	$sql .= "WHERE id = ".$this->getNamedAttribute("rowid");
	
    if( strlen($this->getNamedAttribute("rowid"))>0 ){
	   $_SESSION['config']->DBCONNECT->executeQuery($sql);
    } else {
        new Message("LÃÂÃÂÃÂÃÂ¶schen nicht mÃÂÃÂÃÂÃÂ¶glich", "Keine Id vorhanden. LÃÂÃÂÃÂÃÂ¶schen nicht mÃÂÃÂÃÂÃÂ¶glich");
        
        if($_SESSION['config']->CURRENTUSER->STATUS=="admin"){
            print_r($this);
        }
    }
  }

  function updateDB(){
	if(count($this->COLNAMES)==0){
		return;
	}
  	
    $sql = "UPDATE ".$this->TABLENAME." SET ";
	
	for($i=0;$i<count($this->COLNAMES);$i++){
		if($this->COLNAMES[$i] != "id" || $this->FORCE_ID_UPDATE){
		  $sql .= $this->COLNAMES[$i] ." = '" .$this->getAttribute($i) ."' ";
  		  
		  if($i+1<count($this->COLNAMES)){
			$sql .= ", ";
		  }
		}
	}	
	
	$sql .= " WHERE id = ".$this->getNamedAttribute("rowid");

	$_SESSION['config']->DBCONNECT->executeQuery($sql);
  }


  function show(){
     $r = new Row($this->COLNAMES);
     $r->setToolTip($this->getTooltip());
     for ($i=0;$i<count($this->ROW); $i++){
       $r->setAttribute($i, $this->ROW[$i]);
     }

     $r->show();
  }
  
}