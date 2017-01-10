<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

function getGeotiffForKoor($x,$y){	
	if ($x%400 != 0){
	  $x = $x - ($x%400);
	}
	
	
	if ($y%400 != 0){
	  $y = $y - ($y%400);
	}
	
	$fn = $x."-".($y+400)."-".($x+400)."-".$y;
	
	
	/**
	*  Dateiinformationen einlesen 
	*/ 
	$tilename = $fn.".tif";
	//echo "Datei: ".$tilename."<hr><br><br>";
	
//	$info = `gdalinfo /home/stadtplan/public_html/tmp/geo/$tilename`;
	
	
	/**
	* Geo-Tiff in JPG umwandeln und anzeigen
	*/ 
    $jpgSource = str_replace(".tif", ".jpg", "tmp/" .$tilename);
	if(file_exists($jpgSource)){
	   $img = new Image($jpgSource);
	   
	   $img->setGenerated(false);
       return $img; 
    } else {
		$geoTiff = new GeoTiff("tmp/geo/" .$tilename);
		if(is_subclass_of($geoTiff,"Object") ){
			return $geoTiff;
		} else {
			return new Image("");
		}
	}
}


?>