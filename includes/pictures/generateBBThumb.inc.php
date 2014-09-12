<?php

$xkor     = 0;
$ykor     = 0;

if (!isset($_REQUEST['picname']) || $_REQUEST['picname']==""){
  echo "Kein Bild übergeben!";
  exit();
}


$picName  = $_SERVER['DOCUMENT_ROOT'] .$_REQUEST['picname'];


$image = imagecreatefromjpeg("$picName");
//Wasserzeichen anlegen
//$imgzeichen = imagecreatefrompng("CyborgOne_Logo.png"); //Wasserzeichen
//Hintergrund entfernen
if($_REQUEST['picname'] == $_SERVER['SCRIPT_NAME']."/pics/unknownpic.jpg"){
 // imagecolortransparent( $image, imagecolorallocate ( $image, 200, 100, 50) );
}

//  Grafik orginalgröße berechnen
//----------------------------------
$info = getimagesize("$picName");
$Breite_Quelle = $info[0];
$Hoehe_Quelle  = $info[1];
$Breite_Ziel = $Breite_Quelle; // falls nicht übergeben, vordefinierten
$groesse     =  getimagesize("$picName");



if(isset($_REQUEST['picwidth'])){
  $Breite_Ziel = $_REQUEST['picwidth'];
} else {
  $Breite_Ziel = 250;
}

$Hoehe_Ziel  =  $Hoehe_Quelle * ($Breite_Ziel/$Breite_Quelle);
$Hoehe_Ziel = round($Hoehe_Ziel ,0);
//echo "quelle: " .$Hoehe_Quelle ."/" .$Breite_Quelle ."<br>";
//echo "ziel: " .$Hoehe_Ziel ."/" .$Breite_Ziel ."<br>";



//  Grafik generieren
//-----------------------------
$image1 = imagecreatetruecolor($Breite_Ziel,$Hoehe_Ziel);
$farbe_body=imagecolorallocate($image1,0,0,0);
imagecopyresized($image1, $image, 0,0, 0,0, $Breite_Ziel,$Hoehe_Ziel,$Breite_Quelle,$Hoehe_Quelle);



//Wasserzeichen einfügen
//imagecopyresized($image1, $imgzeichen, $Breite_Ziel-20,$Hoehe_Ziel-10, 0,0, 20,10, 200, 100);


//Bild ausgeben
Header ("Content-type: image/jpeg");
imagejpeg ($image1);


?>
