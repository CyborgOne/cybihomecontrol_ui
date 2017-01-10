<?php
  include("init.php"); 

  echo "
	<html>
 	  <head>
 	  	  
 	  <script type='text/javascript' src='scripts/calendar_popup.js'></script>
	  <script src=\"http://maps.google.com/maps?file=api&amp;v=2.x&amp;sensor=false&amp;key=" .getPageConfigParam($_SESSION['config']->DBCONNECT, "google_maps_API_key") ."\" type=\"text/javascript\"></script>
	  <script type=\"text/javascript\">
	    function initialize() {
	      if (GBrowserIsCompatible()) {
	        var map = new GMap2(document.getElementById(\"map_canvas\"));
	        map.setCenter(new GLatLng(37.4419, -122.1419), 13);
	        map.setUIToDefault();
	      }
	    }
      </script>
  
      <title>" .$PAGETITEL ."</title>
  ";

  include_once("generateCss.php"); 

  echo "
	      <meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
          <meta name='keywords' lang='de' content='" .$PAGETITEL ."'>        

         </head>
	  <body class='iframebody'>
  ";  

?>