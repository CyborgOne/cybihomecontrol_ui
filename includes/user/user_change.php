<?PHP

if (($_SESSION['config']->CURRENTUSER->STATUS=="admin") && (! $_SESSION['config']->CURRENTUSER->STATUS=="user"))
{
   $e = new Error("Kein Zugriff", " Sie haben keine Berechtigung!");
}



if ( isset($_REQUEST['Submit'])) {
  if (( strlen($_REQUEST['Vorname'])>0) && ( strlen($_REQUEST['Nachname'])>0)) {
    $sql1 =  "UPDATE user ";
    $sql1 .= "SET Email='" .$_REQUEST['Email']."', ";
    $sql1 .= "Strasse='" .$_REQUEST['Strasse']."', ";
    $sql1 .= "Plz='" .$_REQUEST['Plz']."', ";
    $sql1 .= "Ort='" .$_REQUEST['Ort']."', ";
    $sql1 .= "Telefon='" .$_REQUEST['Telefon']."', ";
    $sql1 .= "Fax='" .$_REQUEST['Fax']."', ";
    $sql1 .= "Handy='" .$_REQUEST['Handy']."', ";
    $sql1 .= "Geburtstag='" .$_REQUEST['Geburtstag']."', ";
    $sql1 .= "Icq='" .$_REQUEST['Icq']."', ";
    $sql1 .= "Signatur='" .$_REQUEST['Signatur']."', ";
    $sql1 .= "Homepage='" .$_REQUEST['Homepage']."', ";
    $sql1 .= "Vorname='" .$_REQUEST['Vorname']."', ";
    $sql1 .= "Nachname='" .$_REQUEST['Nachname']."', ";
//    $sql1 .= "Hobbys='" .$_REQUEST['Hobbys']."', ";
//    $sql1 .= "Beschreibung='" .$_REQUEST['Beschreibung']."', ";
  
    $sql1 .= "Name='" .$_REQUEST['Vorname']." ".$_REQUEST['Nachname']."' ";
   
   
    if (isset($_REQUEST['Status']))  {
      $sql1 .= ", Status='" .$_REQUEST['Status']."' ";      
    }
   
    $sql1 .= " WHERE id=" .$_SESSION['config']->CURRENTUSER->USERID ." ";
    $result1 = mysql_query($sql1);  	

// evtl hier CURRENTUSER anpassen???
  
  
  
    if (strlen($_REQUEST['Pw'])>1) {
      //Passwort
      $sql1 =  "UPDATE user ";
      $sql1 .= "SET Pw='" .md5($_REQUEST['Pw']);
      $sql1 .= "' WHERE id = " .$_SESSION['config']->CURRENTUSER->USERID  ." ";
      $result1 = mysql_query($sql1);  	

      $HTTP_SESSION_VARS['pw']=$_REQUEST['Pw'];
    }

    $m = new Message("Ihr Profil wurde aktualisiert!");
  }else {
    $e = new Message("Es wurden nicht alle Werte angegeben!");
  }
}

$sqlUser =  "SELECT * FROM user WHERE id = '" .$_SESSION['config']->CURRENTUSER->USERID ."' ";
$result = $_SESSION['config']->DBCONNECT->executeQuery($sqlUser);
$usrrow = mysql_fetch_array($result);  	


/*
/// ÄNDERUNGEN OHNE ZWISCHENANZEIGE
if( isset($_REQUEST['PnNotifyActivate']) ){
	UserActivatePnNotification($_REQUEST['PnNotifyActivate']);
}

if( isset($_REQUEST['PnNotifyDeactivate']) ){
	UserDeactivatePnNotification($_REQUEST['PnNotifyDeactivate']);
}

if( isset($_REQUEST['AutoForumNotifyActivate']) ){
	UserActivateAutoForumNotification($_REQUEST['AutoForumNotifyActivate']);
}

if( isset($_REQUEST['AutoForumNotifyDeactivate']) ){
	UserDeactivateAutoForumNotification($_REQUEST['AutoForumNotifyDeactivate']);
}

if( isset($_REQUEST['NewsletterActivate']) ){
	UserActivateNewsletter($_REQUEST['NewsletterActivate']);
}

if( isset($_REQUEST['NewsletterDeactivate']) ){
	UserDeactivateNewsletter($_REQUEST['NewsletterDeactivate']);
}

if( isset($_REQUEST['delForumNotify']) ){
		DeleteForumNotification($_REQUEST['delForumNotify']);
}
*/

  ////////////////////////////////
 //  MASKE ZUM ÄNDERN ANZEIGEN //
////////////////////////////////


echo "	<form name='user_change' method='post' action='" .$_SERVER['SCRIPT_NAME']  ."'>";

echo "
	  <center>
  	<table width='600' border='1'>
  	  <tr>
  	    <th colspan='2'>
  	      <font size='4'>
  	        Userprofil ändern<br>
  	      </font>
  	    </th>
  	  </tr>
  	  
	  <tr>
	    <td align='center'><center> 
	    <br>
	      <table width='580'>	     
		<tr>
		  <td>
		    Vorname: <br>
		  </td>
		  <td>
		    <input type='text' name='Vorname' size='37' maxlength='50' value='" .$usrrow['Vorname'] ."'>
		  </td>
		</tr>
		<tr>
		  <td>
		    Nachname: <br>
		  </td>
		  <td>
		    <input type='text' name='Nachname' size='37' maxlength='50' value='" .$usrrow['Nachname'] ."'>
		  </td>
		</tr>

		<tr>
		  <td>
		    Email:
		  </td>
		  <td>
		    <input type='text' name='Email' size='37' maxlength='50' value='" .$usrrow['Email'] ."'>
		  </td>
		</tr>

   	    <tr height='5'>
		  <td colspan='2'>
		  </td>
		</tr>

";

if ($_SESSION['config']->CURRENTUSER->STATUS=='admin'){
    $cobStatus = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, title FROM userstatus", "Status", $_SESSION['config']->CURRENTUSER->STATUS);
    $r = new Row(array("",""));
    $r->setAttribute(0,"Status: ");
    $r->setAttribute(1,$cobStatus);
    $r->show();
}

echo "
   	    <tr height='5'>
		  <td colspan='2'>
		  </td>
		</tr>

		<tr>
		  <td>
		    Neues Passwort:<br>
		    <font color='red'>!!! Nur bei Änderung eingeben !!!</font>
		  </td>
		  <td>
		    <input type='text' name='Pw' size='37' maxlength='15' value=''>
		  </td>
		</tr>
		
   	    <tr height='25'>
		  <td colspan='2'>
		  </td>
		</tr>
        
		<tr border='0'><td border='0' colspan='2'>
		  <center>
			<input type='submit' name='Submit' value='Abschicken'>
		  </center>
		</td></tr>
		</table>
		</td></tr>
        <tr height='25'>
		  <td colspan='2'>
		  </td>
		</tr>
    </table>
	</form>
	";
	
?>
