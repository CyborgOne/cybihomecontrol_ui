<?php
$rowgroup=array();
$picnr = 1;
 
if (($_SESSION['config']->CURRENTUSER->STATUS != "user")&&($_SESSION['config']->CURRENTUSER->STATUS != "admin")){
  echo " Sie haben keine Berechtigung Sätze anzulegen!";
  exit();
}


echo "
<form action='" .$_SERVER['SCRIPT_NAME'] ."' method='post' enctype='multipart/form-data'>
<input type='hidden' name='run' value='doUserpicUpload'>
<table width='600'>
  <tr>
    <th>
      Bild für das Benutzerprofil hochladen
    </th>
  </tr>
  <tr>
    <td height='10'>
    </td>
  </tr> 
  <tr>
    <td>
      <font size='2'>
      	Hier können Sie ein Bilder für ihr Benutzerprofil hochladen.<br>
      	Hierzu müssen Sie einfach über den 'Durchsuchen'-Button die gewünschte Datei auswählen.
      	<br>Dann nur noch auf Hochladen klicken. <br>
      	<b>Warten Sie bitte auf die Bestätigungsmeldung bevor Sie das Fenster schließen oder weiter navigieren</b><br><br>
      	
      	Die ausgewählte Datei wird sofort auf dem Webserver abgelegt und in ihr Profil eingebunden.<br>
      	<br>
      	<b>Bitte NUR <font size='4'>GIF, JPG und PNG - Dateien</font> hochladen, da andere Dateiformate fehler verursachen können</b>
      </font>
    </td>
  </tr>  
  <tr>
    <td height='25'>
    <hr>
    </td>
  </tr>

  <tr>
    <td align='center'>
      <b>Quelldatei auswählen</b>
    </td>
  </tr>
  <tr>
    <td align='center'>
	<input type='file' name='probe' accept='images' />
	<input type='submit' value=' Hochladen! ' />
	<hr>
    </td>
  </tr>
</table>

</form>
";

?>
