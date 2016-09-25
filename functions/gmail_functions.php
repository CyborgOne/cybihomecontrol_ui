<?PHP
/**
 * Liefert die Anzahl vorhandener Emails im Posteingang (Je nach 3. Parameter nur ungelesene oder alle)
 */
function getEmailCount($inbox, $emails, $onlyUnread=false){
  $count = 0;

  if($emails) {
    foreach($emails as $email_number) {
      $overview = imap_fetch_overview($inbox,$email_number,0);
      
      if(!$overview[0]->seen || !$onlyUnread){		
	$count++;
      }
    }
  }

  return $count;
} 

/**
 * Liefert ein Array mit den Betreffs von, je nach 3. Parameter, allen oder nur den ungelesenen Mails
 */
function getMailSubjects($inbox, $emails, $onlyUnread=false){
  $subjects = array();

  if($emails) {
    foreach($emails as $email_number) {
      $overview = imap_fetch_overview($inbox,$email_number,0);
      
      if(!$overview[0]->seen || !$onlyUnread){		
	 $subjects[count($subjects)] = $overview[0]->subject;
      }
    }
  }

  return $subjects;
} 


/**
 * Liefert ein Array mit den Texten von, je nach 3. Parameter, allen oder nur den ungelesenen Mails
 */
function getMailMessages($inbox, $emails, $onlyUnread=false){
  $messages = array();

  if($emails) {
    foreach($emails as $email_number) {
      $message = imap_fetchbody($inbox,$email_number,2);
      
      if(!$overview[0]->seen || !$onlyUnread){		
	 $messages[count($messages)] = $message;
      }
    }
  }

  return $message;
} 

function getMailsFromGmailInbox($inbox){
  $mails = imap_search($inbox, 'ALL');

  return $mails;
}

function connectToGmailInbox($user,$pass){
  $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
  $inbox = imap_open($hostname,$user,$pass) or die('Cannot connect to Gmail: ' . imap_last_error());

  return $inbox;
}

function closeGmailInbox($inbox){
  imap_close($inbox);
}



/* *********************************
 * Verwendungs-Beispiel: 
 ********************************* 
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';

// Ihre Email Adresse bei Gmail
$username = 'YOUR_ADDRESS@gmail.com';

// Passwort muss ein App-Passwort sein (https://security.google.com/settings/security/apppasswords)
$password = 'YOUR_APP_PASSWORD';

$in = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
$mails = imap_search($in, 'ALL');
echo "Ungelesene Mails: ".getEmailCount($in, $mails, true);
imap_close($in);



// -------------------------------------------------------
//  Alternativ (verwendet Login aus PageConfig-Tabelle)
// -------------------------------------------------------
$in    = connectToGmailInbox($_SESSION['config']->PUBLICVARS['gmailAdress'], $_SESSION['config']->PUBLICVARS['gmailAppPassword']);
$mails = getMailsFromGmailInbox($in);
echo "Ungelesene Mails: ".getEmailCount($in, $mails, true);
closeGmailInbox($in);
*/
?>