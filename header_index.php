<?php
  
  echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='de' lang='de'>
		<meta http-equiv='Content-Type' content='text/html; charset=utf8' />

	 	<head>
  ";

//          <script language='javascript'>AC_FL_RunContent = 0;</script>
//          <script src='AC_RunActiveContent.js' language='javascript'></script>
//          <script type='text/javascript' src='scripts/swfobject.js'></script>

  include_once("generateCss.php"); 

//  <META NAME=”GOOGLEBOT” CONTENT=”unavailable_after: 25-Aug-2007 15:00:00 GMT”>

  echo "
     <!-- Inhaltsangaben / Inhaber  -->
          <meta name='description' content='" .htmlspecialchars($PUBLICVARS['pagetitel'], ENT_QUOTES ) ." " .htmlspecialchars($PUBLICVARS['suchbegriffe'], ENT_QUOTES ) ." '>
          <meta name='author' content='".getPageConfigParam($_SESSION['config']->DBCONNECT, "pageowner")."' >
	   <meta name='page-topic' Content='Dienstleistung, Internet'>
	   <meta name='keywords' lang='de' content='" .htmlspecialchars($PUBLICVARS['pagetitel'], ENT_QUOTES ) ." " .htmlspecialchars($PUBLICVARS['suchbegriffe'], ENT_QUOTES ) ." Daniel Scheidler '>      
          <meta name='audience' Content='Zielgruppe'>
          <meta name='publisher' content='d.scheidler@web.de'>
          
     <!-- Standardeinstellungen  -->  
          <meta http-equiv='Content-Type' content='text/html; charset=utf8'>
          <link rev='made' content='mailto:d.scheidler@web.de'>
          <meta name='copyright' content='d.scheidler@web.de'>
";

$default_title  = $PUBLICVARS['pagetitel'];
$currRunLnkName = getTextByRunlink( $_SESSION['runLink'] );

$sub_title      = isset($PUBLICVARS['pagesubtitel'])?$PUBLICVARS['pagesubtitel']:"";

$ttl = strlen($currRunLnkName)>0 ?
            ( $PUBLICVARS['pagetitel'] ." - " .$currRunLnkName) :  $PUBLICVARS['pagetitel']   ;

echo "<title> " .$ttl."  </title>";


$detect = new Mobile_Detect();

if (!$detect->isMobile()) {
  echo "   
    <!-- CK Editor  -->
        	<script type=\"text/javascript\" src=\"ckeditor/ckeditor.js\"></script>
        	<script src=\"ckeditor/ckedit_cbo.js\" type=\"text/javascript\"></script>
        	<link href=\"ckeditor/ckedit_cbo.css\" rel=\"stylesheet\" type=\"text/css\" />
             	<script type=\"text/javascript\">
                CKEDITOR.config.customConfig = 'ckeditor/config.js';
	         CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
                CKEDITOR.config.forceEnterMode = false;
             	</script>

  ";
}

if($_SESSION['runLink']=="start" || $_SESSION['runLink']=="sensorList" || $_SESSION['runLink']=="sensorlogView"){
    $autoRefreshTime = getPageConfigParam($_SESSION['config']->DBCONNECT, "autoRefreshTime");
    if(strlen($autoRefreshTime)>0 && $autoRefreshTime>0){
       echo "<meta http-equiv='refresh' content='" .$autoRefreshTime ."'>\n"; 
    }
}

echo "
      </head>
    
    
    <body class='indexbody'  onload='uhrzeit('jetzt'); setInterval('uhrzeit()', 1000); initialize()' onunload='GUnload()'>
    
	
	<!-- Tooltips -->
	  <script type='text/javascript' src='scripts/calendar_popup.js'></script>
	  <script type=\"text/javascript\" src=\"scripts/tooltip.js\"></script>
	  <script type=\"text/javascript\" src=\"scripts/tooltip_menu.js\"></script>
	
	<!-- Pop-Up anzeigen-->
	  <script type=\"text/javascript\" src=\"scripts/popup.js\"></script>
	
	
	<!-- Spans Sichtbar/Unsichtbar machen -->
	 <script type=\"text/javascript\" src=\"scripts/makeVisible.js\"></script>
    
    
       <!-- Werte von Objekten ändern (value von Text bzw Hidden-Field ) -->	      
        <script type=\"text/javascript\" src=\"scripts/changeValue.js\"></script>
  ";

if (!$detect->isMobile()) {
  echo "
  	<div id=\"alerts\">
		<noscript>
			<p>
		<strong>Einige Funktionen dieser Webseite benötigen JavaScript!</strong>.<br>
                In einem Browser wie z.B. ihrem, werden daher einige Funktionen deaktiviert.
                Für vollen Funktionsumfang installieren Sie bitte JavaScript bzw. aktivieren Sie die JS-Funktionen in den Sicherheitseinstellungen.
			</p>
		</noscript>
	</div>

  ";  
}












  function getTextByRunlink($id){

    $sql = "SELECT * FROM menu WHERE (link LIKE '%run=" .$id ."%') ";
    $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);

    if(mysql_num_rows($result)>0){
      $r = mysql_fetch_array($result);
      return $r['text'];
    }

    return "";
  }


?>
