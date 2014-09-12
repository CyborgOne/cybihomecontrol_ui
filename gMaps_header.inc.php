<?php
/**
* 
* @author Daniel Scheidler 
* @copyright März 2009
*/


// Zoomfaktor
  $zoomFaktor =  8;

    

// Größenangaben für die Karte
  $gesamtBreite =  715;
  $gesamtHoehe  =  500;
  

// Zu Zentrierende Adresse
  $strasse = "Burgerstrasse";
  $hausNr  = "28";
  $ort     = "Wermelskirchen";  
  
  $breitenGrad = 51.14;
  $laengenGrad = 7.21667;
  
 // if(isset($_SESSION['tmp']['gMap_Zoom_Faktor']) && $_SESSION['tmp']['gMap_Zoom_Faktor']>0 && $_SESSION['tmp']['gMap_Zoom_Faktor']<=20){
  //  $zoomFaktor = $_SESSION['tmp']['gMap_Zoom_Faktor']; 
 // }

  
  if(isset($_SESSION['tmp']['gMap_Width']) && $_SESSION['tmp']['gMap_Width']>0 ){
    $gesamtBreite = $_SESSION['tmp']['gMap_Width']; 
  }
    
  if(isset($_SESSION['tmp']['gMap_Height']) && $_SESSION['tmp']['gMap_Height']>0 ){
    $gesamtHoehe = $_SESSION['tmp']['gMap_Height']; 
  }
  
  
  // Wenn Position von anderem Spieler angezeigt werden soll: 
  if(isset($_SESSION['tmp']['gMap_GpsPosition_RowId']) && $_SESSION['tmp']['gMap_GpsPosition_RowId']>0){
  	  $dbTableAdressen = new DbTable($_SESSION['config']->DBCONNECT,
	                              "gpsPositions", 
	                              array("*"),   // "name", "strasse", "hausnummer", "plz", "ort", "ortsteil", "email", "telefon", "handy", "fax", "icq", "homepage", "text"), 
	                              "",      //"Name, Strasse, Hausnr., Plz, Ort, Ortsteil, Email, Telefon, Handy, Fax, ICQ, Homepage, Zusatz-Info",
								  "",
						          "", 
						          " id = ".$_SESSION['tmp']['gMap_GpsPosition_RowId'] ." ");
						          
	  $adressRow = $dbTableAdressen->getRow(1);
		
	// Zu Zentrierende Adresse
	  $userId       = $adressRow->getNamedAttribute("user_id");
      $user         = getUsernameById($userId);
	  $breitenGrad  = $adressRow->getNamedAttribute("longitude");
	  $laengenGrad  = $adressRow->getNamedAttribute("latitude");
  }
	
	
  echo "  <script src=\"http://maps.google.com/maps?file=api&amp;v=2.x&amp;sensor=false&amp;key=" .getPageConfigParam($_SESSION['config']->DBCONNECT, "google_maps_API_key") ."\" type=\"text/javascript\"></script>
		  <script src=\"http://www.google.com/uds/api?file=uds.js&v=1.0&key=" .getPageConfigParam($_SESSION['config']->DBCONNECT, "google_maps_API_key")  ."\" type=\"text/javascript\"></script>
		  <script src=\"http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js\" type=\"text/javascript\"></script>

	  	  <style type=\"text/css\">
		    @import url(\"http://www.google.com/uds/css/gsearch.css\");
		    @import url(\"http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css\");
		  </style>
		  
		  <script language=\"javascript\">
		      var map;
			  var mgr;
	          function createMarker(posn, ttl, icn) {
			      var marker = new GMarker(posn, {title: ttl, icon: icn, draggable:false });
			   
			      return marker;
			  }

			  function centerMapTo(x,y) {
			      map.setCenter(new GLatLng( x, y), 11);
			  }

			  

		    function initialize() {
		    	
		      if (GBrowserIsCompatible()) {
			    var allmarkers = [];
			    
				".getHeaderMapDefinition($breitenGrad, $laengenGrad, $gesamtBreite, $gesamtHoehe, $zoomFaktor)."
				
				// Erstellt die kleinen Markierungssymbole
				".getHeaderIconDefinitions()."
				
                // Bereitet die Liste der POIs vor
				".getHeaderPOIEntryDefinitions()."
			  }
              
              
		      GSearch.setOnLoadCallback(initialize);
			}
            

		  </script>         
  ";



/*

NOTIZEN:

GLatLng(myLatitude, myLongitude)

WK Längen und Breitengrad:  B: 51.15 / L: 7.21667
2582445.667, -5667001.07
*/
	 
	 
	 
	  
  /*
  In diesem Beispiel wird eine statische Datei ("data.xml"), 
  die eine Liste von Längen- und Breitenkoordinaten in XML enthält, 
  mithilfe der Methode GDownloadUrl heruntergeladen. Wenn der Herunterladevorgang abgeschlossen ist,
  wird das XML mit GXml analysiert und eine Markierung an jedem dieser Punkte im XML-Dokument erstellt.
----------------------------------------------------------------------------------------------------------

		var map = new GMap2(document.getElementById("map_canvas"));
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(new GLatLng(37.4419, -122.1419), 13);
		
		// Lädt die Daten in data.xml herunter und lädt sie auf die Karte. Das erwartete Format lautet:
		// <markers>
		//   <marker lat="37.441" lng="-122.141"/>
		//   <marker lat="37.322" lng="-121.213"/>
		// </markers>
		GDownloadUrl("data.xml", function(data, responseCode) {
		  var xml = GXml.parse(data);
		  var markers = xml.documentElement.getElementsByTagName("marker");
		  for (var i = 0; i < markers.length; i++) {
		    var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")),
		                            parseFloat(markers[i].getAttribute("lng")));
		    map.addOverlay(new GMarker(point));
		  }
		});
*/
  
  
  
  
  
  
/*
Symbole

Für Markierungen können Symbole definiert werden, die anstelle des Standardsymbols angezeigt werden. Die Definition eines Symbols ist komplex, da ein einzelnes Symbol in der Maps-API aus verschiedenen Bildern besteht. Es müssen mindestens das Vordergrundbild, die Größe des Typs GSize und ein Symbolversatz zur Positionierung des Symbols definiert werden.

Die einfachsten Symbole basieren auf dem Typ G_DEFAULT_ICON. Wenn Sie ein Symbol auf der Grundlage dieses Typs erstellen, müssen Sie nur wenige Eigenschaften bearbeiten, um das Standardsymbol zu ändern.

Im untenstehenden Beispiel wird ein Symbol mithilfe des Typs G_DEFAULT_ICON erstellt und dann so verändert, dass es ein anderes Bild verwendet. (Wenn Sie andere Bilder verwenden, achten Sie darauf, dass Sie die richtige Bildgröße entsprechend der Größe des Standardbilds festlegen, damit das Bild korrekt angezeigt wird.)

var map = new GMap2(document.getElementById("map_canvas"));
map.addControl(new GSmallMapControl());
map.setCenter(new GLatLng(37.4419, -122.1419), 13);

// Erstellt das "winzige" Markierungssymbol
var blueIcon = new GIcon(G_DEFAULT_ICON);
blueIcon.image = "http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png";
            
// Richtet das GMarkerOptions-Objekt ein
markerOptions = { icon:blueIcon };

// Fügt 10 Markierungen an zufällig ausgewählten Positionen hinzu
var bounds = map.getBounds();
var southWest = bounds.getSouthWest();
var northEast = bounds.getNorthEast();
var lngSpan = northEast.lng() - southWest.lng();
var latSpan = northEast.lat() - southWest.lat();
for (var i = 0; i < 10; i++) {
var point = new GLatLng(southWest.lat() + latSpan * Math.random(),
southWest.lng() + lngSpan * Math.random());
map.addOverlay(new GMarker(point, markerOptions));
}
*/














?>