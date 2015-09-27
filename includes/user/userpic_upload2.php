<?php
//Datei hochladen und zurück zum Admin-Menü


 
  function getUpperPath($pathname){
    $i = strlen($pathname);

   //Letzten Backslash abschneiden
    if (substr($pathname ,strlen($pathname)-1,1)=="/"){
	  $pathname = substr($pathname,0,strlen($pathname)-1);
	}
	
	while($i>0 && substr($pathname,$i-1,1)!="/" ){
	  $pathname = substr($pathname,0,strlen($pathname)-1);
	  $i=$i-1;
	}

   //Letzten Backslash abschneiden
    if (substr($pathname ,strlen($pathname)-1,1)=="/"){
	  $pathname = substr($pathname,0,strlen($pathname)-1);
	}
	
	return $pathname;
  }


if (($_SESSION['config']->CURRENTUSER->STATUS != "user") && ($_SESSION['config']->CURRENTUSER->STATUS != "admin"))
{
  echo " Sie haben keine Berechtigung Sätze anzulegen!";
  exit();
}

umask(0755);

  if (isset($_FILES['probe']) &&(! $_FILES['probe']['error'])) 
  {
      $xn = $_FILES['probe']['name'];
      
      $target= $_SESSION['config']->CURRENTUSER->USERID.time() ;
      $target .= substr($xn, ((strlen($xn))-4) , 4);
    
    move_uploaded_file($_FILES['probe']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] .substr(dirname($_SERVER['SCRIPT_NAME']),1)."/".'pics/user/'.$target);

    chmod ("pics/user/".$target, 0755); 

    echo "<br><br><br>";
    printf("Die Datei %s steht jetzt als " .$_FILES['probe']['name'] 
          ." zur Verfügung.<br />\n",
      $_FILES['probe']['name']);
    
    echo "<br>";
    
    printf("Sie ist %u Bytes groß und vom Typ %s.<br />\n",
      $_FILES['probe']['size'], $_FILES['probe']['type']);

    echo "<center>
          <br><br><br>
  	  <a href='" .$_SERVER['SCRIPT_NAME'] ."?run=changeMyProfile'>Zurück zum User-Profil</a>
        </center>";

    $sqlup =  "UPDATE user ";
    $sqlup .= "SET pic='".$target."' ";
    $sqlup .= "WHERE id='".$_SESSION['config']->CURRENTUSER->USERID ."' ";

    $result = mysql_query($sqlup);

  }
  else
  {
    echo "<font size='4' color='red'><b><center>Fehlende Eingabe</center></b></font>";
    include("userpic_upload.php");
  }

?>
