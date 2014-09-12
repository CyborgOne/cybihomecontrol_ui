<?php
/**
* 
* @author Daniel Scheidler 
* @copyright März 2009
*/




/**
  	getGmapAdressinfo ( $strasse, $hausNr, $ort ) 
	
	liefert ein Array zurück, welches der Antwort des Google-Services zur Anfrage der Geo-Informationen entspricht.
	
	Das Array hat folgende Struktur:
	Index	Beschreibung
    0. 		HTTP-Statuscode
    1. 		Genauigkeit (siehe die Genauigkeits-Konstanten)
    2. 		Geografische Breite
    3. 		Geografische Länge
    ----------------------------------------------------------------------------------

	zu Index 1:  
	Die genauigkeit wird mittels einder der folgenden  Konstanten beschrieben.
		
	Konstanten 	Beschreibung
	0 	Unbekannter Ort. (Seit 2.59)
	1 	Land. (Seit 2.59)
	2 	Bundesland/Bundesstaat, Provinz, Präfektur usw. (Seit 2.59)
	3 	Bezirk, Gemeinde usw. (Seit 2.59)
	4 	Ortschaft (Stadt, Dorf). (Seit 2.59)
	5 	Postleitzahl (PLZ). (Seit 2.59)
	6 	Straße. (Seit 2.59)
	7 	Kreuzung. (Seit 2.59)
	8 	Adresse. (Seit 2.59)
    ----------------------------------------------------------------------------------
    
	@param String 			Der gesuchte Strassenname
	@param String 			Die gesuchte Hausnummer
	@param String 			Der gesuchte Ort 
 */
function getGmapAdressinfo($strasse, $hausNr, $ort){
	// Koordinaten bei google abrufen  
  $adressSearchString = $strasse."%20".$hausNr ."+" .$ort;
  $tempUrlString      = "http://maps.google.com/maps/geo?q=" .str_replace(" ", "%20", $adressSearchString) ."&output=csv&key=" .getPageConfigParam($_SESSION['config']->DBCONNECT, "google_maps_API_key");

  $googleAdressInformations = file_get_contents($tempUrlString, 'r');
  
  $arr = explode(",", $googleAdressInformations);
  
  return $arr;
}














/**
 * HEADER - FUNKTIONS
 * 
 * Hilfsfunktionen für die Generierung der Scripte etc im Head-Bereich
 */
 
 function getHeaderMapDefinition($breitenGrad=51.15, $laengenGrad=7.21667, $gesamtBreite=700, $gesamtHoehe=300, $zoomFaktor=11){
    $ret = "
    		    var myGeographicCoordinates = new GLatLng(" .$breitenGrad .", " .$laengenGrad .");
		        
				map = new GMap2(document.getElementById(\"GoogleMap_canvas\"), { size: new GSize(" .$gesamtBreite .", " .$gesamtHoehe .") } );
		        
		        map.setCenter(myGeographicCoordinates, ".$zoomFaktor.");
		        //map.removeMapType(G_HYBRID_MAP);
		       // map.removeMapType(G_SATELLITE_MAP);
		       
		        var zoomControl = new GSmallZoomControl();
				//map.addControl(zoomControl);		
			    map.addControl(new GSmallMapControl());
                map.addControl(new GMapTypeControl());	
				 
                var lscOptions = {
				    resultList : google.maps.LocalSearch.RESULT_LIST_INLINE
				};

				var lsc = new google.maps.LocalSearch(lscOptions); 
				map.addControl(lsc);
				
				GEvent.addDomListener(map, \"dblclick\", function() {
				    map.zoomIn();
				});
  				
  				GEvent.addDomListener(map, \"singlerightclick\", function() {
				    map.zoomOut();
				});
				
	";


	return $ret;	
 }
 
 
 
 
 
 
 function getHeaderIconDefinitions(){
	$ret = "
				var iconDefault = new GIcon(G_DEFAULT_ICON);
				iconDefault.shadow = \"\";
				

				var iconCurrent = new GIcon(G_DEFAULT_ICON);
				iconCurrent.image = \"http://".$_SERVER['HTTP_HOST']."/".substr(dirname($_SERVER['SCRIPT_NAME']),1)."/pics/kategoriesymbole/currInst_btn.png\";
				iconCurrent.shadow = \"\";
				iconCurrent.iconSize = new GSize(40, 40);
				iconCurrent.iconAnchor = new GPoint(20, 20);
				
				var iconWkWappen = new GIcon(G_DEFAULT_ICON);
				iconWkWappen.image = \"http://".$_SERVER['HTTP_HOST']."/".substr(dirname($_SERVER['SCRIPT_NAME']),1)."/pics/WK_Wappen_Karte.png\";
				iconWkWappen.shadow = \"\";
				iconWkWappen.iconSize = new GSize(60,87); 
				iconWkWappen.iconAnchor = new GPoint(30,0);
                
                
                var blueIcon = new GIcon(G_DEFAULT_ICON);
                blueIcon.image = \"http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png\";

           ";
		

	
	$dbTableIcons = new DbTable($_SESSION['config']->DBCONNECT,
                                "user", 
                                array("id", "name", "pic"),
                                "",
                                "",
                                "",
                                "length(pic)>1" );

	$rowCount = $dbTableIcons->getRowCount();
	
	
	
	for($i=1; ($i<=$rowCount && $rowCount>0 ) ; $i++){
		$iconRow = $dbTableIcons->getRow($i);
		
		$ret .= "
				var icon" .$iconRow->getNamedAttribute("id") ." = new GIcon(G_DEFAULT_ICON);";
				
		if (strlen($iconRow->getNamedAttribute("pic"))>2){				
			$ret .= "
				icon" .$iconRow->getNamedAttribute("id") .".image = \"http://".$_SERVER['HTTP_HOST']."/".substr(dirname($_SERVER['SCRIPT_NAME']),1)."/pics/user/" .$iconRow->getNamedAttribute("pic") ."\";
				icon" .$iconRow->getNamedAttribute("id") .".iconSize = new GSize(30, 30); 
				icon" .$iconRow->getNamedAttribute("id") .".iconAnchor = new GPoint(15,15);
				icon" .$iconRow->getNamedAttribute("id") .".shadow = \"\"; ";
        }
	}
	
			
	return $ret;	
 }
 
 
 
 
 
 
 
 function getHeaderPOIEntryDefinitions($zoomFaktor=14){

	
	$dbTablePOI = new DbTable($_SESSION['config']->DBCONNECT, 
                                  "gpsPositions", 
                                  array("id",  "timeonupdate", "longitude", "latitude", "altitiude", "accuracy", "user_id"),
                                  "",
                                  "",
                                  " IF( user_id = " .$_SESSION['config']->CURRENTUSER->USERID ." , 1, 0 ) ", 
                                  "" );
                                  
	$rowCount = $dbTablePOI->getRowCount();


	$ret = "    
			var markers = [];
			icon =  null;
			title = \"\";
			posn = null;
			          
			allmarkers.length = 0;

			mgr = new GMarkerManager(map);
	   ";
	for($i=1; ($i<=$rowCount && $rowCount>0 ) ; $i++){
		$poiRow = $dbTablePOI->getRow($i);
		
		// Ermittelter POI
          $userId       = $poiRow->getNamedAttribute("user_id");
		  $user         = getUserFullnameById($poiRow->getNamedAttribute("user_id"));
		  $lg           = $poiRow->getNamedAttribute("latitude");
		  $bg           = $poiRow->getNamedAttribute("longitude");

		
        $ret .= "
				marker".$userId." = null; 
                ";
        
	   	  if($bg!=0 && $lg!=0){
			
			if($userId != null && $userId == $_SESSION['config']->CURRENTUSER->USERID ){
   	            $ret .= "
				          icon = iconCurrent; 
						  
   					      var myGeographicCoordinates = new GLatLng(" .$bg .", " .$lg .");
		       			  map.setCenter(myGeographicCoordinates, ".$zoomFaktor.");";
			} else if ($userId != null && isUserOnFriendlist($userId) ){
				$ret .= "
				          icon = icon" .$userId  ."; ";
             
            } else {
				$ret .= "
				          icon = blueIcon; ";
            }

            
			$ret .= "
				          title = \"".$user ."\" 
				          posn = new GLatLng( " .$bg .", " .$lg ." );
				          marker".$userId." = createMarker(posn,title,icon);
                          
                          var html".$userId." = \"" .getUserMarkerInfoSource($userId) ."\";  
				          
                          
                          
           				  GEvent.addDomListener(marker".$userId.", \"click\", function() {
						    // document.location.href = \"?showUser=".$userId."\";
                            marker".$userId.".openInfoWindowHtml(html".$userId.")
						  });

				          
						  markers.push(marker".$userId.");
				          allmarkers.push(marker".$userId.");
			";
		
		}	
	}
    
    $ret .= "	
                mgr.addMarkers(markers, 10); 
            ";
    
	
    
    
// ------------------------    
//     Statische POIs
// ------------------------    
	$ret .= "
 				
 				var wkMarker = [];
				var wkText = \"Wermelskirchen\";
				
				markerWk = createMarker(new GLatLng(51.15, 7.21667), wkText, iconWkWappen); 
				wkMarker.push(markerWk);
				
 				mgr.addMarkers(wkMarker, 6);
 				mgr.refresh();
			";

	return $ret;	
 }
 
 
 function getUserMarkerInfoSource($userId){
	$ret          = ""; 
    $user         = getUserFullnameById($userId);
   
    $ret = $user;
    
    return $ret;
 }
?>