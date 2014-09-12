<?php
/**
 * Interface zur Datenabfrage / Verwaltung der GpsGame-Daten 
 * 
 * 
 * Pflicht-Parameter:
 *  -   User
 *  -   Pw 
 *  -   command
 * 
 * 
 * Ausnahme bei Command (Kein User/Pw erforderlich):
 *  -   register
 *  -   PwForgot
 * 
 * ------------------------------
 *       Gültige COMMANDS 
 *   (Anmeldedaten notwendig)
 * ------------------------------
 *  -   login
 *  -   updateGpsKoords
 *  -   getFriendKoords
 *  -   getFriendlist
 *  -   getUnacceptedFriendlist
 *  -   getFriendRequestlist
 *  -   addFriendRequest
 *  -   acceptFriendship
 *  -   changePassword
 *  -   getUserprofile
 *  -   updateUserProfile
 *  
 */





include("init.php");
/* ---------------------------------------
        DATENBANK-VERBINDUNG
--------------------------------------- */
$DBCONNECT =  new DbConnect($DBHOST,$DBUSER,$DBPASS,$DBNAME);

  
// Session-Gültigkeit in Minuten
  $sessionTime = 30; 

// Welches Kommando soll ausgeführt werden?
  $command = isset($_REQUEST['command'])?$_REQUEST['command']:"";
 


/*
 * Kommando auswerten
 * (Bei bedarf neu anmelden)
 */  
  switch ( $command ) {

    // @deprecated
    case "login":
        if( strlen($_REQUEST['User'])>0 && strlen($_REQUEST['Pw'])>0 ){
                $_SESSION['config']->CURRENTUSER->login( $_REQUEST['User'], $_REQUEST['Pw'] );
        } else {
            echo "Benutzername und Passwort müssen eingegeben werden";
        }

        break;

    case "register":
        if( strlen($_REQUEST['Vorname'])>0 && strlen($_REQUEST['Nachname'])>0 && strlen($_REQUEST['User'])>0 && strlen($_REQUEST['Email'])>0  && strlen($_REQUEST['Pw'])>0 ){

            register( $_REQUEST['Vorname'], $_REQUEST['Nachname'], $_REQUEST['User'], $_REQUEST['Pw'] , $_REQUEST['Email'] );
            echo "Benutzer wurde erfolgreich angelegt.\r\n Sie erhalten in kürze eine Email mit dem Aktivierungs-Link von unserem System.";
        } else {
            echo "Vor-/Nachname, Benutzername, Passswort und Email müssen eingegeben werden.";
        }

        break;

    case "PwForgot":
        if( strlen($_REQUEST['Email'])>0 ){
            forgotPasswd( $_REQUEST['Email'] );
        } else {
            echo "Email muss eingegeben werden.";
        }

        break;

    default:
    
      if ( !isset($_REQUEST['User']) || !isset($_REQUEST['Pw']) ||  strlen($_REQUEST['User'])==0 || strlen($_REQUEST['Pw'])==0 ){
            echo "Benutzer und Passwort müssen eingegeben werden.";
      } else if ( ! validateLogin( $DBCONNECT, $_REQUEST['User'], $_REQUEST['Pw'] ) ){
            echo "Benutzer oder Passwort fehlerhaft";
      } else {
            
    /**
     * LOGIN ERFOLGREICH
     * ---------------------------------
     * 
     * AB HIER STANDARF-FUNKTIONALITÄT 
     * FÜR REGISTRIERTE BENUTZER  
     * 
     * ---------------------------------
     */        
        $_SESSION['config']->CURRENTUSER->login( $_REQUEST['User'], $_REQUEST['Pw'] );

        $userId = $_SESSION['config']->CURRENTUSER->USERID;
        
        
        
        switch ( $command ) {
          case "updateGpsKoords":
            if( strlen($_REQUEST['long'])>0 && strlen($_REQUEST['lat'])>0 ){
                updateGpsKoords($userId, $_REQUEST['long'], $_REQUEST['lat']);
            } else {
                echo "Koordinaten müssen angegeben werden.";
            }

            break;


          case "getFriendKoords":
            echo getFriendKoords($userId);
            
            break;
            
          
          case "addFriendRequest":
            if( strlen($_REQUEST['addFriendId'])>0 ){
                addFriendRequest($userId, $_REQUEST['addFriendId']);
            } else {
                echo "Freund muss angegeben werden.";
            }
            
            break;
            
            
          case "getFriendSearchResult":
            if( strlen($_REQUEST['friendNameSearch'])>0 || strlen($_REQUEST['friendOrtSearch'])>0 || strlen($_REQUEST['friendMailSearch'])>0 ){
                echo getFriendSearchResultXml($userId, $_REQUEST['friendMailSearch'], $_REQUEST['friendNameSearch'], $_REQUEST['friendOrtSearch']);
            } else {
                echo "Gesuchte Email, Name oder Ort müssen angegeben werden.";
            }

            break;

          case "getFriendlist":
            echo getFriendlistXml($userId);
            
            break;


          case "getUnacceptedFriendlist":
            echo getUnacceptedFriendlistXml( $userId);
            
            break;



          case "getFriendRequestlist":
            echo getFriendRequestlistXml($userId );
            
            break;


          case "acceptFriendship":
            if( strlen($_REQUEST['acceptFriendListId'])>0 ){
                acceptFriendship($_REQUEST['acceptFriendListId']);
            } else {
                echo "Freund muss angegeben werden.";
            }
            
            break;


          case "changePassword":
            if( strlen($_REQUEST['newPasswd'])>0 ){
                changePasswd($_REQUEST['User'], $_REQUEST['Pw'], $_REQUEST['newPasswd']);
            } else {
                echo "Neues Passwort muss eingegeben werden.";
            }
            
            break;

          case "getUserprofile":
            echo getUserprofileXml($userId);
                
            break;
          
          case "updateUserProfile";
            updateUserProfile($userId);
          
            break;

          case "searchUser":
            $emailSuche = null;
            $nameSuche  = null;
            $ortSuche   = null;
            
            if( strlen($_REQUEST['SucheEmailText'])>0 ){
                $emailSuche = $_REQUEST['SucheEmailText'];
            } else if( strlen($_REQUEST['SucheNameText'])>0 ){
                $nameSuche = $_REQUEST['SucheNameText'];
            } else if( strlen($_REQUEST['SucheOrtText'])>0 ){
                $ortSuche = $_REQUEST['SucheOrtText'];
            }
          
            echo getUserBySearchvaluesXml($emailSuche, $nameSuche, $ortSuche);
          
            break;

          default:
            echo "Unknown Command";            
            break;
        }
        
      }  
      
      break;
  } 



// ----------------------------------------------------
//                 BENUTZER FUNKTIONEN
// ----------------------------------------------------

 /**
  * Erstellt den angegebenen Benutzer 
  * und sendet eine Mail mit den Login-Daten 
  * an die angegebene Mail-Adresse.
  * 
  * @param $vorname     String      Vorname
  * @param $nachname    String      Nachname
  * @param $User        String      Benutzername
  * @param $Pw      String      Passwort
  * @param $email       String      Email
  */
  function register($vorname, $nachname, $User, $Pw,$email) {
    if (  strlen($vorname)>0 
       && strlen($nachname)>0
       && strlen($User)>0  
       && strlen($Pw)>0 
       && strlen($email)>0
       ) {
        
        if( $_SESSION['config']->CURRENTUSER->existsUsername($_REQUEST['User'])){
            echo "Benutzername existiert bereits";
            return;
        }
    
        if( $_SESSION['config']->CURRENTUSER->existsEmail($_REQUEST['Email'])){
            echo "Diese Email existiert bereits!<br> Fordern Sie einfach ein neues Passwort an, falls Sie ihr altes nicht mehr wissen.";
            return;
        }
                    
        $status = new UserStatus($_SESSION['config']->CURRENTUSER);
        $status->createUserProfile( $vorname, $nachname, $User, $Pw, $email );
    } else {
        echo "Es wurden nicht alle notwendigen Daten angegeben!";
    }
  }



  function acceptFriendship($acceptFriendListId){
    $sql = "UPDATE freundesliste SET accepted = 'J' WHERE id = " .$acceptFriendListId 
            ." AND user_id = " .$userId 
            ." AND accepted='N' ";

    $_SESSION['config']->DBCONNECT->executeQuery($sql);
    
    echo "Akzeptiert";
  }
  




 /**
  * setzt ein neues Passwort 
  * und sendet eine Email mit den Login-Daten 
  * an die angegebene Mail-Adresse.
  * 
  * @param $User    String      Benutzername
  * @param $PwOld   String      Altes Passwort
  * @param $PwNew   String      Neues Passwort
  */
  function changePasswd($User, $PwOld, $PwNew) {
    $_SESSION['config']->CURRENTUSER->setPassword($_SESSION['config']->CURRENTUSER->USERID, $PwNew, true, false);
  
    echo "Passwort wurde geändert!";
  }




 /**
  * generiert ein neues Passwort 
  * und sendet eine Email mit den Login-Daten 
  * an die angegebene Mail-Adresse.
  * 
  * @param $email       String      Email-Adresse des Benutzers
  */
  function forgotPasswd($email) {
    $_SESSION['config']->CURRENTUSER->needNewPassword($email, true, false);
    echo "neues Passwort wurde generiert und per Email zugesandt.";
  }



 /**
  * Prüft bei aufruf ob die 
  * Benutzer-Passwort-Kombination gültig ist
  * 
  * @param $dbCon   DbConnect       DbConnect-Objekt
  * @param $User    String          Benutzername
  * @param $Pw  String          Passwort
  * 
  * @return  boolean                true wenn die Kombination Gültig ist
  *                                 sonst falce
  */
  function validateLogin($dbCon, $User, $Pw ){
    
    $sql  = "SELECT Pw FROM user WHERE User = '" .$User ."' ";

    $rslt = $dbCon->executeQuery($sql);
    
    $row  = mysql_fetch_array($rslt);
    
    if( $row['Pw'] == md5($Pw) ){
        return true;
    }
    return false;
  }
  
  
  
  
  /**
   * Ändert alle Werte des aktuellen Users, 
   * die im $_REQUEST angegeben sind, und im 
   * User-Objekt als Änderbare Spalten definiert sind.
   * 
   * zusätzlich wird die eigenschaft Pflichtparameter geprüft.
   * Ist ein Pflicht-Wert nicht angegeben, wird dieser 
   * nicht aktualisiert!
   */
  function updateUserProfile($userId){
    $i = 0;
    
    $changeableColumns = $_SESSION['config']->CURRENTUSER->getAenderbareFelder();
    $UserRow = $_SESSION['config']->CURRENTUSER->getUserRow();
    
    if( $UserRow == null ){
        echo "Es ist ein Problem beim Update des Benutzers aufgetreten!"; 
        
        return false;
    }  
    
    
    foreach ($changeableColumns as $colName) {
        
        if( isset($_REQUEST[$colName]) ){
            if($_SESSION['config']->CURRENTUSER->isColumnMandatory( $colName )){
                // Behandlung Pflichtfelder

                if( strlen($_REQUEST[$colName]) > 0 ){
                   $UserRow->setNamedAttribute($colName, $_REQUEST[$colName]);
                }

                // Sonderbehandlung bei Name
                if ($colName == "Vorname" || $colName == "Nachname"){
                    $name = $UserRow->getNamedAttribute("Vorname")." ".$UserRow->getNamedAttribute("Nachname");
                    $UserRow->setNamedAttribute("Name", $name);
                }

            } else {
                // Behandlung optionale Felder
                $UserRow->setNamedAttribute($colName, $_REQUEST[$colName]);
            }
        }

    } // ENDE FOREACH

    $UserRow->updateDB();
    
    echo "Ihr Profil wurde aktualisiert.";
  }
  
  
  /**
   * aktualisiert den aktuellen Standort 
   * des angemeldeten Benutzers
   *  
   * ---------------------
   *     gpsPositions
   * ---------------------
   * id
   * timeonupdate
   * longitude
   * latitude
   * altitiude
   * accuracy
   * user_id
   */
  function updateGpsKoords($userId, $long, $lat, $alt=1, $acc=1){
         
    $dbKoordsTbl = new DbTable(
                                $_SESSION['config']->DBCONNECT,
                                "gpsPositions",
                                array("id",  "longitude", "latitude", "altitiude", "accuracy", "user_id"),
                                "Id, Longitude, Latitude, Altitude, Accuracy, Benutzer-Id",
                                "",
                                "",
                                "user_id = " .$userId                        
                    );
    
    $dbKoordsRow = null;
    
    // Wenn bereits ein Satz mit Koordinaten existiert
    // diesen aktualisieren
    // sonst neu anlegen
    if( $dbKoordsTbl->getRowCount() == 1 ){
        
        $dbKoordsRow = $dbKoordsTbl->getRow(1);
        
        $dbKoordsRow->setNamedAttribute("longitude", $long);
        $dbKoordsRow->setNamedAttribute("latitude",  $lat);
        $dbKoordsRow->setNamedAttribute("altitiude", $alt);
        $dbKoordsRow->setNamedAttribute("accuracy",  $acc);
        $dbKoordsRow->setNamedAttribute("user_id",   $userId);
        
        $dbKoordsRow->updateDB();
        
    } else {
        
        if ( $dbKoordsTbl->getRowCount() > 1) {
            // Wenn mehrere Sätze zu benutzer existieren, 
            // alle löschen und neuen Satz anlegen.
            $sqlDel = "DELETE FROM gpsPositions WHERE user_id =" .$userId;
            $_SESSION['config']->DBCONNECT->executeQuery($sql);
        }      
        
        // Ab hier neuen Eintrag erzeugen 
        $dbKoordsRow = $dbKoordsTbl->createRow();
        
        $dbKoordsRow->setNamedAttribute("longitude", $long);
        $dbKoordsRow->setNamedAttribute("latitude",  $lat);
        $dbKoordsRow->setNamedAttribute("altitiude", $alt);
        $dbKoordsRow->setNamedAttribute("accuracy",  $acc);
        $dbKoordsRow->setNamedAttribute("user_id",   $userId);
        
        $dbKoordsRow->insertIntoDB();
    }
  }
  
  
  
  
  /**
   * 
   * @param  $usrName String 
   * 
   * @return  boolean
   */
  function validateUsername($usrName){

        if( $_SESSION['config']->CURRENTUSER->existsUsername($usrName)){
            echo "Benutzername existiert bereits";
            return false;
        }
        
        if(strlen($usrName)<4 ){
            echo "Benutzername muss aus mindestens 4 Zeichen bestehen!";
            return false;
        }

        return true;
  }
  
  
  
    
  
  /**
   * 
   * @param  $passwd String 
   * 
   * @return  boolean
   */
  function validatePassword($usrName){

        if( strlen($passwd)< 5 ){
            echo "Passwort muss aus mindestens 5 Zeichen bestehen!";
            return false;
        }
        


        $existUpper   = preg_match( '#[A-Z]#', $passwd );
        $existLower   = preg_match( '#[a-z]#', $passwd );
        $existNumber  = preg_match( '#[0-9]#', $passwd );
        $existSonder  = preg_match( '#[+-&\$%/\(\)\#=]#', $passwd );
        
        if( !$existUpper || !$existLower || !$existNumber ){
            echo "Passwort muss Kleinbuchstaben, Großbuchstaben und Zahlen enthalten!";
            return false;
        }

        return true;
  }
  
  
  
  
  
  
  
  
  
  

// ----------------------------------------------------
//                  Karten Funktionen
// ----------------------------------------------------


  function getFriendKoords($userId){
    $UserImagePath = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" ."/pics/user/";

    $sql = "SELECT f.id, 
                   f.User_id, 
                   f.friend_id, 
                   f.text, 
                   f.accepted, 
                   u.Vorname,
                   u.Nachname,
                   u.Name,
                   u.Geburtstag,
                   u.Strasse,
                   u.Plz,
                   u.Ort,
                   u.Email,
                   u.User,
                   u.Nation,
                   concat( '" .$UserImagePath ."', u.pic ) as pic,
                   u.aktiv,
                   gps.timeonupdate,
                   gps.longitude,
                   gps.latitude,
                   (now() - gps.timeonupdate) as diffTime
        
            FROM freundesliste f, user u, gpsPositions gps
        
            WHERE f.friend_id = u.id 
              AND u.id = gps.user_id
              AND f.User_id = " .$userId ." 
              AND f.accepted = 'J' ";

    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    
    return getSqlResultAsXml($_SESSION['config']->DBCONNECT, $sql);

  }


  /**
   * Liefert das Suchergebniss zurück.
   * - Suche nur nach Email
   * - Keine Treffer aus Freundesliste
   * - Kein eigener User 
   * 
   * @param $userId
   * @param searchEmail
   * 
   * @return String   XML Suchergebniss (nach email)
   */
  function getFriendSearchResultXml($userId, $searchEmail="", $searchName="", $searchOrt="") {
    if(strlen($searchEmail)==0 && strlen($searchName)==0 && strlen($searchOrt)==0){
        echo "Es muss mindestens ein Suchkriterium angegeben werden";
        return;
    }
    
    $UserImagePath = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" ."/pics/user/";
    
    $sql = "SELECT id, Vorname, Nachname, Name, Email, Ort, publicProfile, concat('" .$UserImagePath  ."', pic) as UserImage, 
            ( IFNULL( (SELECT DISTINCT  'J' FROM freundesliste f WHERE user_id = " .$userId ." AND f.friend_id = u.id),  'N' )) AS friendJn
                    FROM user u WHERE id != " .$userId ." ";
                             

    if( $searchEmail!=null && strlen($searchEmail) > 0 ){
        $sql .= " AND lower(u.Email) = '" .strtolower($searchEmail) ."' ";
    }    
        
    if( $searchName!=null && strlen($searchName) > 0 ){
        $sql .= " AND (   ( lower(u.Vorname) like '" .strtolower($searchName) ."' OR  lower(u.Nachname) like '" .strtolower($searchName) ."' OR  lower(u.Name) like '" .strtolower($searchName) ."' )  
                AND u.publicProfile = 'J'
            ) 
        ";
    }
    
    if( $searchOrt!=null && strlen($searchOrt) > 0){
        $sql .= " AND ( lower(u.Ort) like '" .strtolower($searchOrt) ."' AND u.publicProfile = 'J' ) ";
    }
                            
    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    return getSqlResultAsXml($_SESSION['config']->DBCONNECT, $sql);
         
        
  }



  /**
   * Liefert die Daten der Freundesliste
   * des Users zur übergebenen ID  
   * 
   * @param  $userId
   * 
   * @return XML Freundesliste
   */
  function getFriendlistXml($userId){
    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    $UserImagePath = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" ."/pics/user/";
    
    echo getSqlResultAsXml($_SESSION['config']->DBCONNECT, "SELECT f.id, 
                                               f.User_id, 
                                               f.friend_id, 
                                               f.text, 
                                               f.accepted, 
                                               u.Vorname,
                                               u.Nachname,
                                               u.Name,
                                               u.Geburtstag,
                                               u.Strasse,
                                               u.Plz,
                                               u.Ort,
                                               u.Email,
                                               u.User,
                                               u.Nation,
                                               u.Status,
                                               u.Signatur,
                                               u.Lastlogin,
                                               concat( '" .$UserImagePath ."', u.pic ) as pic,
                                               u.aktiv,
                                               (SELECT (now() - g.timeonupdate) as diffTme FROM  gpsPositions g WHERE g.user_id = u.id) as diffTime
        
                                        FROM freundesliste f, user u

                                        WHERE f.friend_id = u.id 
                                          AND f.User_id = " .$userId ." 
                                          AND f.accepted = 'J' ");
  }




  /**
   * getUserprofileXml()
   * 
   * @param mixed $userId
   * @return
   */
  function getUserprofileXml( $userId ){
    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    $UserImage = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" .getUserImagePath($userId);;
    
    $lastGpsUpdate  = getLastGpsUpdateSince($_SESSION['config']->DBCONNECT, $userId );

    
    echo getSqlResultAsXml($_SESSION['config']->DBCONNECT, "SELECT  id,
                                                Vorname,
                                                Nachname,
                                                Name,
                                                Geburtstag,
                                                Strasse,
                                                Plz,
                                                Ort,
                                                Email,
                                                Telefon,
                                                Fax,
                                                Handy,
                                                Icq,
                                                Aim,
                                                Homepage,
                                                User,
                                                Nation,
                                                Status,
                                                User_group_id,
                                                Newsletter,
                                                Signatur,
                                                Lastlogin,
                                                Posts,
                                                Beschreibung,
                                                pic,
                                                pnnotify,
                                                autoforumnotify,
                                                geaendert,
                                                emailJN,
                                                icqJN,
                                                telefonJN,
                                                aktiv,
                                                angelegt,
                                                '" .$UserImage ."' as UserImage,
                                                '" .$lastGpsUpdate ."' as LastGpsUpdate,
                                                (SELECT (now() - g.timeonupdate) as diffTme FROM  gpsPositions g WHERE g.user_id = user.id) as diffTime 
        
                                        FROM user
                                        WHERE id = " .$userId);
  }
   


 



  /**
   * getUnacceptedFriendlistXml()
   * 
   * @param mixed $userId
   * @return
   */
  function getUnacceptedFriendlistXml($userId){
    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    $UserImagePath = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" ."/pics/user/";
    
    echo getSqlResultAsXml($_SESSION['config']->DBCONNECT, "SELECT f.id, 
                                               f.User_id, 
                                               f.friend_id, 
                                               f.text, 
                                               f.accepted, 
                                               u.Vorname,
                                               u.Nachname,
                                               u.Name,
                                               u.Geburtstag,
                                               u.Strasse,
                                               u.Plz,
                                               u.Ort,
                                               u.Email,
                                               u.User,
                                               u.Nation,
                                               u.Status,
                                               u.Signatur,
                                               u.Lastlogin,
                                               concat( '" .$UserImagePath ."', u.pic ) as pic,
                                               u.aktiv,
                                               (SELECT (now() - g.timeonupdate) as diffTme FROM  gpsPositions g WHERE g.user_id = u.id) as diffTime                                               
                                        
                                        FROM freundesliste f, user u

                                        WHERE f.friend_id = u.id 
                                          AND f.User_id = " .$userId ." 
                                          AND f.accepted = 'N' ");
  }





  /**
   * getFriendRequestlistXml()
   * 
   * @param mixed $userId
   * @return
   */
  function getFriendRequestlistXml($userId){
    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    $UserImagePath = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" ."/pics/user/";
    
    return getSqlResultAsXml($_SESSION['config']->DBCONNECT,
                             "SELECT   f.id, 
                                       f.User_id, 
                                       f.friend_id, 
                                       f.text, 
                                       f.accepted, 
                                       u.Vorname,
                                       u.Nachname,
                                       u.Name,
                                       u.Geburtstag,
                                       u.Strasse,
                                       u.Plz,
                                       u.Ort,
                                       u.Email,
                                       u.User,
                                       u.Nation,
                                       u.Status,
                                       u.Signatur,
                                       u.Lastlogin,
                                       concat( '" .$UserImagePath ."', u.pic ) as pic,
                                       u.aktiv,
                                       (SELECT (now() - g.timeonupdate) as diffTme FROM  gpsPositions g WHERE g.user_id = u.id) as diffTime
                                
                                FROM freundesliste f, user u
        
                                WHERE f.user_id = u.id 
                                  AND f.friend_id = " .$userId ." 
                                  AND f.accepted = 'N' ");
  }



  function getUserBySearchvaluesXml($emailSuche, $nameSuche, $ortSuche){
    $xsltProcessor = new XsltProcessor;
    $xsltProcessor->registerPHPFunctions();
    
    $UserImagePath = "http://" .$_SERVER['HTTP_HOST'] .substr(dirname($_SERVER['SCRIPT_NAME']),1) ."/includes/pictures/generateBBThumb.inc.php?picname=" ."/pics/user/";
    
    $searchSql = "SELECT   u.id,
                           u.Vorname,
                           u.Nachname,
                           u.Name,
                           u.Geburtstag,
                           u.Strasse,
                           u.Plz,
                           u.Ort,
                           u.Email,
                           u.User,
                           u.Nation,
                           u.Status,
                           u.Signatur,
                           u.Lastlogin,
                           concat( '" .$UserImagePath ."', u.pic ) as pic,
                           u.aktiv,
                           (SELECT (now() - g.timeonupdate) as diffTme FROM  gpsPositions g WHERE g.user_id = u.id) as diffTime, 
                           if (exists (SELECT * FROM freundesliste f WHERE f.friend_id = u.id AND f.user_id = " .$_SESSION['config']->CURRENTUSER->USERID ."), 'J', 'N' ) as onFriendList
                    FROM user u
                    WHERE u.id != " .$_SESSION['config']->CURRENTUSER->USERID ."
                    ";

                                    
                                            
    if( $emailSuche != null && strlen($emailSuche)>1 ){
        $searchSql .= " AND LOWER(u.email) = '" .strtolower($emailSuche) ."' ";
    }
                                                
    if( $nameSuche != null && strlen($nameSuche)>1 ){
        $searchSql .= " AND LOWER(u.name) = '" .strtolower($nameSuche) ."' ";
    }
                                                
    if( $ortSuche != null && strlen($ortSuche)>1 ){
        $searchSql .= " AND LOWER(u.ort) = '" .strtolower($ortSuche) ."' ";
    }
    //echo $searchSql;
    
    return getSqlResultAsXml( $_SESSION['config']->DBCONNECT, 
                              $searchSql ); 
  }





  function addFriendRequest($userId, $friendId){
    
    $dbTblFriends = new DbTable($_SESSION['config']->DBCONNECT,
                                "freundesliste",
                                array("user_id", "friend_id", "accepted"),
                                "",
                                "accepted = 'N'",
                                "",
                                "user_id=".$userId ." AND friend_id=".$friendId
                                );
    if ( $dbTblFriends->getRowCount() > 0 ){
        echo "Dieser Benutzer ist bereits in Ihrer Freundesliste, oder Sie haben bereits eine Anfrage gestellt.";
        return;
    }                 
                   
    $dbRowNewFriend = $dbTblFriends->createRow();
    $dbRowNewFriend->setNamedAttribute("user_id", $userId);
    $dbRowNewFriend->setNamedAttribute("friend_id", $friendId);
    $dbRowNewFriend->setNamedAttribute("accepted", "N");
    
    $dbRowNewFriend->insertIntoDB();
    
    echo "Freundesanfrage gesendet";
  }

?>