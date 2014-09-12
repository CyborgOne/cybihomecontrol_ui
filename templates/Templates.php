<?php
function getXTemplate($template,$endung="tmpl"){
	$file = file("templates/".$template.".".$endung);
	$txt  = implode("",$file);

	debugOutput($txt);

    return str_replace("\"","\\\"", $txt);
}
function dooutput($template) {
   return  $template;
}


function getTemplate($tmpl,  $text = array()){
	// Hier sollte das array auf Hochkommas etc in den Werten geprüft werden.
	$t = "";
    eval("\$t = \"".getXTemplate($tmpl)."\";"); 
    return $t;
}


?>