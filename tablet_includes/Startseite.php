<?PHP
//Filename: Startseite.php
$vars = get_class_vars("HomeControlMap");

$hcMap = new HomeControlMap(false, "TABLET");

$hcMap->show();

?>