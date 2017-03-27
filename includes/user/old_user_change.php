<?PHP

if (($_SESSION['config']->CURRENTUSER->STATUS=="admin") && (! $_SESSION['config']->CURRENTUSER->STATUS=="user"))
{
   $e = new Error("Kein Zugriff", " Sie haben keine Berechtigung Sätze anzulegen!");
}



$sql1 =  "SELECT * FROM user ";
$sql1 .= "WHERE id = '";
$sql1 .= $_SESSION['config']->CURRENTUSER->USERID ."' ";
$result1 = mysql_query($sql1);  	
$usrrow = mysql_fetch_array($result1);  	

if (isset($usrrow['pic'])&& strlen(isset($usrrow['pic'])>4))
{
	$usrpic  = "pics/" .$usrrow['pic'];
}
else
{
  $usrpic  = "pics/unknown_user.jpg";
}

if ( isset($_REQUEST['Submit'])) {
if (( strlen($_REQUEST['Vorname'])>0) && ( strlen($_REQUEST['Nachname'])>0))
 {
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
   $sql1 .= "Hobbys='" .$_REQUEST['Hobbys']."', ";
   $sql1 .= "Beschreibung='" .$_REQUEST['Beschreibung']."', ";
  
   $sql1 .= "Name='" .$_REQUEST['Vorname']." ".$_REQUEST['Nachname']."' ";
   
   
   if (isset($_REQUEST['Status'])) 
   {
     $sql1 .= ", Status='" .$_REQUEST['Status']."' ";      
   }
   
   $sql1 .= " WHERE id='";
   $sql1 .= $_REQUEST['change'] ."' ";
   $result1 = mysql_query($sql1);  	

// evtl hier CURRENTUSER anpassen???

  
if (strlen($_REQUEST['Pw'])>1) 
{
   //Passwort
   $sql1 =  "UPDATE user ";
   $sql1 .= "SET Pw='" .md5($_REQUEST['Pw']);
   $sql1 .= "' WHERE id = '";
   $sql1 .= $_REQUEST['change'] ."'";
   $result1 = mysql_query($sql1);  	

  $HTTP_SESSION_VARS['pw']=$_REQUEST['Pw'];
}


  echo " 	<table width='600' border='1'>
  	  <tr>
  	    <td>

  <a href='" $_SERVER['SCRIPT_NAME'] ."?change=" .$_SESSION['config']->CURRENTUSER->USERID ."' >Ihr Userprofil wurde für Sie geändert!<br><br><br>Zurück</a>
  </td></tr></table>
  ";
  
exit();	
}

else 
{
  $e = new Message("Es wurden nicht alle Werte angegeben!");
}
}


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

if (isset($_REQUEST['change']))
{	
  echo "<input type='hidden' name='change' value='".$_REQUEST['change'] ."'> ";
}

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
	        <td colspan='2'>
	          <center>
	            <img src='" .$usrpic ."' width='150'>
	          </center>
	        </td>
	      </tr>
	     
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
		    Geburtstag: ( Format:  YYYY-MM-DD )
		  </td>
		  <td>
		    <input type='text' name='Geburtstag' size='10' maxlength='50' value='" .$usrrow['Geburtstag'] ."'>
		  </td>
		</tr>



		<tr>
		  <td>
		    Strasse: <br>
		  </td>
		  <td>
		    <input type='text' name='Strasse' size='37' maxlength='50' value='" .$usrrow['Strasse'] ."'>
		  </td>
		</tr>
		<tr>
		  <td>
		    Plz/Ort: <br>
		  </td>
		  <td>
		    <input type='text' name='Plz' size='5' maxlength='5' value='" .$usrrow['Plz'] ."'><input type='text' name='Ort' size='28' maxlength='50' value='" .$usrrow['Ort'] ."'>
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
	
		<tr>
		  <td>
		    Telefon:
		  </td>
		  <td>
		    <input type='text' name='Telefon' size='37' maxlength='20' value='" .$usrrow['Telefon'] ."'>
		  </td>
		</tr>
		
		<tr>
		  <td>
		    Fax:
		  </td>
		  <td>
		    <input type='text' name='Fax' size='37' maxlength='20' value='" .$usrrow['Fax'] ."'>
		  </td>
		</tr>

		<tr>
		  <td>
		    Handy:
		  </td>
		  <td>
		    <input type='text' name='Handy' size='37' maxlength='20' value='" .$usrrow['Handy'] ."'>
		  </td>
		</tr>

		<tr>
		  <td>
  	    ICQ:
		  </td>
		  <td>
		    <input type='text' name='Icq' size='37' maxlength='50' value='" .$usrrow['Icq'] ."'>
		  </td>
		</tr>
		<tr>
		  <td>
		    Meine Homepage:
		  </td>
		  <td>
		    <input type='text' name='Homepage' size='37' maxlength='50' value='" .$usrrow['Homepage'] ."'>
		  </td>
		</tr>

   	    <tr height='10'>
		  <td colspan='2'>
		  </td>
		</tr>

   	    <tr height='25'>
		  <td colspan='2'>
		  </td>
		</tr>

		<tr>
		  <td>
		    Signatur:<br>
		    <font size='1'>(Ein Spruch fürs Forum)</font>
		  </td>
		  <td>
		    <input type='text' name='Signatur' size='37' maxlength='100' value='" .$usrrow['Signatur'] ."'>
		  </td>
		</tr>
";

if ($_SESSION['config']->CURRENTUSER->STATUS=='admin')
{
  echo "		
		
		<tr>
		  <td>
		    Status:<br>
		  </td>
		  <td>
		    <input type='text' name='Status' size='7' maxlength='5' value='" .$usrrow['Status'] ."'>
		  </td>
		</tr>
	";
}




echo "
		<tr>
		  <td>
		    Neues Passwort:<br>
		    <font color='red'>!!! Nur bei Änderung eingeben !!!</font>
		  </td>
		  <td>
		    <input type='text' name='Pw' size='37' maxlength='15' value=''>
		  </td>
		</tr>

	 	<tr valign='middle'>
	 	  <td colspan='2'> 
";

//	 	    <br><a href='userpic_upload.php'><font color='blue'>Profil-Bild ändern</font></a>

echo "
	 	  </td>
	 	</tr>

		<tr border='0'><td border='0' colspan='2'>
		  <center>
			<input type='submit' name='Submit' value='Abschicken'>
		  </center>
		</td></tr>
		</table>
		</td></tr>
";


/*
echo "		<tr>
		  <td>
	      <table width='580'>	 
  	     <tr>
	          <td colspan='2' height='5'>
	          	            <hr>

						</td>
				 </tr>
 				 <tr>
	          <td width='320' valign='middle'>
							<b>Emailbenachrichtigung für Persönliche Nachrichten ist:</b>
					  </td>
					  <td>
";


//EMAIL-BENACHRICHTIGUNG FÜR PNs
if(getUserPnNotify($usrrow['id'])=='Y'){
  echo "
		 <font color='green' size='4'><b>AKTIV</b></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a href='user_change.php?PnNotifyDeactivate=" .$usrrow['id'] ."&change=" .$usrrow['id'] ."'>Deaktivieren</a>
		 <br>
  ";
} else {
	  echo "
		 <font color='red' size='4'><b>INAKTIV</b></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a href='user_change.php?PnNotifyActivate=" .$usrrow['id'] ."&change=" .$usrrow['id'] ."'>Aktivieren</a>
		 <br>
  ";
}



echo "      </td>
					</tr>
				  <tr>
	          <td colspan='2' height='5'>
	          	  <hr>
						</td>
				 </tr>

 				 <tr>
	          <td width='320' valign='middle'>
							<b>Automatische Aktivierung der Benachrichtigung über neue Foren-Beiträge ist:</b>
					  </td>
					  <td>
";



if(getUserAutoForumNotify($usrrow['id'])=='Y'){
  echo "
		 <font color='green' size='4'><b>AKTIV</b></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a href='user_change.php?AutoForumNotifyDeactivate=" .$usrrow['id'] ."&change=" .$usrrow['id'] ."'>Deaktivieren</a>
		 <br>
  ";
} else {
	  echo "
		 <font color='red' size='4'><b>INAKTIV</b></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a href='user_change.php?AutoForumNotifyActivate=" .$usrrow['id'] ."&change=" .$usrrow['id'] ."'>Aktivieren</a>
		 <br>
  ";
}



echo "      </td>
					</tr>			
   	      <tr>
	          <td colspan='2' height='5'>
   	            <hr>
						</td>
				 </tr>
 				 <tr>
	          <td width='320' valign='middle'>
							<b>Der Empfang des Newsletters ist:</b>
					  </td>
					  <td>
";

if(getUserNewsletter($usrrow['id'])=='true'){
  echo "
		 <font color='green' size='4'><b>AKTIV</b></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a href='user_change.php?NewsletterDeactivate=" .$usrrow['id'] ."&change=" .$usrrow['id'] ."'>Deaktivieren</a>
		 <br>
  ";
} else {
	  echo "
		 <font color='red' size='4'><b>INAKTIV</b></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a href='user_change.php?NewsletterActivate=" .$usrrow['id'] ."&change=" .$usrrow['id'] ."'>Aktivieren</a>
		 <br>
  ";
}



echo "      </td>
					</tr>										
  	      <tr>
	          <td colspan='2' height='15'>
	            <hr>
	          <br><br>
						</td>
				 </tr>
  	     <tr>
	          <th colspan='2'>
							<b>Emailbenachrichtigung für Themen im Forum:</b>
						</th>
				 </tr>
  	     <tr>
	          <td colspan='2' height='5'>
	            Für alle hier aufgelisteten Themen ist im Forum die Email-Benachrichtigung aktiviert.
	            Durch klicken auf *Deaktivieren* wird die Benachrichtigung <font color='red'>SOFORT</font> abgeschaltet!<br>
	            <br>
	            Wenn Sie neue Themen in die Benachrichtigungsliste aufnehmen wollen, können Sie dies im Forum im jeweiligen Thema aktivieren. (neben der Sortierung)
						</td>
				 </tr>
				 <tr>
	          <td colspan='2'>
						

						  
						</td>
				 </tr>
				 		 
				</table>		    
		  </td>
		</tr>
";

include("forumNotifyList.php");
*/

echo "		
		</center>
	      </table>
	</form>
	
	";
	
?>
