<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

function getStadtplanNavigation(){
	$pnl = new Panel("Navigation");
	
	$navMenu = new DBMenu("StadtplanNavigation");
	
	$pnl->setContent($navMenu);
	
	return $pnl;
}



function getImageForKategorie($katId){
  $sql = "SELECT * FROM stadt_kategorien k WHERE id = " .$katId;
  $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
  
  $row  = mysql_fetch_array($result);
  
  if(strlen($row['symbolpic'])>0){
	  $file = dirname($_SERVER['SCRIPT_NAME'])."/pics/kategoriesymbole/".$row['symbolpic'];
	  
	  $img = new Image($file);
	  $img->setToolTip($row['name']);
	  $img->setGenerated(false);
	  $img->setWidth(100);
	  
	  return $img;
  }
  return new Text("");
}



function getLaengeBreiteForAdressId($addressId){
	$bg=null;
	$lg=null;
	
  	  $dbTableAdressen = new DbTable($_SESSION['config']->DBCONNECT,
	                              "adressen", 
	                              array("*"),   
	                              "",     
								  "",
						          "", 
						          " id = ".$addressId ." ");
						          

	  $adressRow = $dbTableAdressen->getRow(1);
		
  	  
	// Zu Zentrierende Adresse
	  $adressId     = $adressRow->getNamedAttribute("id");
	  $strasse      = $adressRow->getNamedAttribute("strasse");
	  $hausNr       = $adressRow->getNamedAttribute("hausnummer");
	  $ort     		= $adressRow->getNamedAttribute("ort");
	  $breitenGrad  = $adressRow->getNamedAttribute("breitengrad");
	  $laengenGrad  = $adressRow->getNamedAttribute("laengengrad");

	if(strlen($breitenGrad)==0 || strlen($laengenGrad) == 0  && strlen($strasse)>0 && strlen($ort)>0){
		  $gmapInfo = getGmapAdressinfo ( $strasse, $hausNr, $ort );
		  
		  $breitenGrad = $gmapInfo[2];
		  $laengenGrad = $gmapInfo[3];
		  
		  $adressRow->setNamedAttribute("breitengrad", $breitenGrad);
		  $adressRow->setNamedAttribute("laengengrad", $laengenGrad);
		  $adressRow->updateDB();
	  }
	
	
	return array($breitenGrad, $laengenGrad);
}


function getKoordsForAdress($addressId){
  if (strlen($addressId)==0){
  	echo "KEINE ADRESSE?";
	return;
  }
  $sql = "SELECT k.hoch, k.rechts "
        ."FROM koordinatenzuordnung k, strassenschluessel s, adressen a " 
        ."WHERE a.id = ".$addressId
		."  AND a.strasse = s.name "
		."  AND k.str_id = s.id "
		."  AND k.hausnr = a.hausnummer ";
    
     
  $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
  
  if(mysql_num_rows($res)>0){
      $korRow = mysql_fetch_array($res);
	  $rw = $korRow['rechts'];
	  $hw = $korRow['hoch'];
	  
	  $k = new Koordinate($rw, $hw);
	  return $k;
  } else {
  	  $sql = "SELECT k.hoch, k.rechts "
        ."FROM koordinatenzuordnung k, strassenschluessel s, adressen a " 
        ."WHERE a.id = ".$addressId 
		."  AND a.strasse = s.name "
		."  AND k.str_id = s.id ";

      $res = $_SESSION['config']->DBCONNECT->executeQuery($sql);
  
	  if(mysql_num_rows($res)>0){
	      new Message("Leider ist für diesen Eintrag nur eine Strassen-Genaue Anzeige möglich!");
		  $korRow = mysql_fetch_array($res);
		  
		  $rw = $korRow['rechts'];
		  $hw = $korRow['hoch'];
          
		  $k = new Koordinate($rw, $hw);
	      return $k;

	  } else {
	      new Message("Für diese Adresse sind leider keine Koordinaten hinterlegt.\n Es ist daher keine Positionierung der Karte möglich!");
		  return null; 	
	  }
  }
} 
   
function getAdressIdForInstitution($id){
	$sql = "SELECT i.adresse FROM  stadt_institution i WHERE i.id = ".$id;

	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);


	$ret = null;  
    if(mysql_num_rows($res)>0){
       $row = mysql_fetch_array($res);
       $ret = $row["adresse"];
    }

	return $ret;
}
  

function getAdressIdForAngebot($id){
	$sql = "SELECT i.adresse FROM stadt_angebot a,  stadt_institution i WHERE a.id = ".$id ." AND a.institutionid = i.id ";

	$res = $_SESSION['config']->DBCONNECT->executeQuery($sql);


	$ret = null;  
    if(mysql_num_rows($res)>0){
       $row = mysql_fetch_array($res);
       $ret = $row["adresse"];
    }

	return $ret;
}
  

?>