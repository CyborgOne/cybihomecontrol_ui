<?php

$xkor     = 0;
$ykor     = 0;

if (!isset($_REQUEST['picname']) || $_REQUEST['picname']==""){
  echo "Kein Bild übergeben!";
  exit();
}


$picName  = $_SERVER['DOCUMENT_ROOT'] .$_REQUEST['picname'];


$image = imagecreatefrompng("$picName");

//  Grafik orginalgröße berechnen
//----------------------------------
$info = getimagesize("$picName");
$Breite_Quelle = $info[0];
$Hoehe_Quelle  = $info[1];
$Breite_Ziel = $Breite_Quelle; // falls nicht übergeben, vordefinierten
$groesse     =  getimagesize("$picName");



if(isset($_REQUEST['picwidth'])){
  $Breite_Ziel = $_REQUEST['picwidth'];
}

$Hoehe_Ziel  =  $Hoehe_Quelle * ($Breite_Ziel/$Breite_Quelle);
$Hoehe_Ziel = round($Hoehe_Ziel ,0);


//  Grafik generieren
//-----------------------------
$image1 = imagecreatetruecolor($Breite_Ziel,$Hoehe_Ziel);
$farbe_body=imagecolorallocate($image1,0,0,0);
imagecopyresized($image1, $image, 0,0, 0,0, $Breite_Ziel,$Hoehe_Ziel,$Breite_Quelle,$Hoehe_Quelle);


//Bild ausgeben
Header ("Content-type: image/png");
imagejpeg ($image1);


?>
