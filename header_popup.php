<?php
  include_once("init.php"); 

  echo "
	<html>
 	  <head>
  ";

  include_once("generateCss.php"); 


//  <META NAME=GOOGLEBOT CONTENT=unavailable_after: 25-Aug-2007 15:00:00 GMT>

echo "	  <script type=\"text/javascript\" src=\"scripts/swfobject.js\"></script>

     <!-- Inhaltsangaben / Inhaber  -->
	      <meta name='author' content='' >
		  <meta name='page-topic' Content='Dienstleistung, Internet'>
		  <meta name='keywords' lang='de' content='" .htmlspecialchars($PUBLICVARS['pagetitel'], ENT_QUOTES ) ." Daniel Scheidler '>      
          <meta name='audience' Content='Zielgruppe'>
          <meta name='publisher' content='@'>
            
            
          
          
     <!-- Robots  -->  
          <meta name='robots' content='index, nofollow '>
		  <meta name='revisit-after' content='7 days'>  
          
		  
		  
		  
	 <!-- Standardeinstellungen  -->  
	      <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>

		  <meta http-equiv='pragma' content='no-cache'>
          <meta http-equiv='expires' content='0'>
          <meta http-equiv='cache-control' content='no-cache'>
          <link rev='made' content='mailto:d.scheidler@web.de'>
          <meta name='copyright' content='d.scheidler@web.de'>
";

	
echo "<title> " .$PUBLICVARS['pagetitel'] ."  </title>
";


echo "
          </head>
          <body class='indexbody' ";

if(!isset($_REQUEST['stayAlive'])){
	echo " onblur='self.close();' ";
}	  
		  
echo ">


		  <script type=\"text/javascript\" src=\"scripts/changeValue.js\"></script>
	      <script type=\"text/javascript\" src=\"scripts/tooltip.js\"></script>
		  <script type=\"text/javascript\" src=\"scripts/tooltip_menu.js\"></script>
		  <script type=\"text/javascript\" src=\"scripts/popup.js\"></script>
	      

  ";  


?>