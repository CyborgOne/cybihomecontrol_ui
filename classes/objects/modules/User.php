<?php
//FileNAME: User.php
 
class User  extends Object {
  var $CONFIG;
  var $USERTABLE;
  var $DBCONNECT;
  var $USERNAME="";
  var $EMAIL="";
  var $USERID="";
  var $USERGRPID="";
  var $VORNAME="";
  var $NACHNAME="";
  var $USERSTATUS;
  var $STATUS;
  private $USER_DBROW;

 
  function User(){
      $this->CONFIG = $_SESSION['config'];
      $this->DBCONNECT = &$this->CONFIG->DBCONNECT;
      $this->USERTABLE = new UserTable($this->DBCONNECT);
      $this->STATUS="";
  }
  
  
  function login($user, $pw){
    $userRow =& $this->USERTABLE->getUserByUserNAME($user);
    if(isset($userRow['User']) && strlen($userRow['User'])>0  ){
        if( $userRow['aktiv']=="J" ) {
           if ($userRow['Pw'] == md5($pw)){
      	  //LOGIN OK
             $this->USERID = $userRow['id'];
             $this->USERGRPID = $userRow['user_group_id'];
             $this->USERNAME = $userRow['User'];
             $this->VORNAME = $userRow['Vorname'];
             $this->NACHNAME = $userRow['Nachname'];
             $this->EMAIL = $userRow['Email'];
             $this->STATUS = $userRow['Status'];
             
             
             $_SESSION['config']->DBCONNECT->executeQuery("UPDATE user SET LastLogin = '".date("Y-m-d G:i:s") ."' WHERE id = ".$userRow['id']);
             
    
             $this->CONFIG->LOG->add("Login OK");	  
             
             $sqlClearActionChk = "DELETE FROM `chkActions` WHERE user_id = " .$this->USERID;
           } else {
     	  //PASSWORT FALSCH
             $e = new Error("falsches Passwort!", "Das eingegebene Passwort passt nicht zu dem angegebenen Benutzer!", $_SERVER['SCRIPT_NAME']);
    
             $this->CONFIG->LOG->add("falsches Passwort");	  
    
           }
        } else {
 	  
       //Nicht aktiviert
         $e = new Error("Benutzer nicht aktiv!", "Der angegebene Benutzer wurde noch nicht aktiviert!", $_SERVER['REQUEST_URI']);

         $this->CONFIG->LOG->add("Benutzer nicht aktiv");	  
 
        }
    } else {
 	  //FALSCHER BENUTZER
         $e = new Error("Benutzer nicht gefunden!", "Der angegebene Benutzer existiert nicht!", $_SERVER['REQUEST_URI']);

         $this->CONFIG->LOG->add("Benutzer nicht gefunden");	  

    }
  }
 
 
 
  function showStatus($XPOS=-1,$YPOS=-1){
     $this->USERSTATUS = new UserStatus($this,$XPOS,$YPOS);
     $this->USERSTATUS->show();
  }


  function logout(){
    $this->CONFIG->LOG->add("Logout");	

    $this->VORNAME="";
    $this->NACHNAME="";
    $this->USERNAME="";
    $this->USERID="";
    $this->STATUS="";
    $this->EMAIL="";
    
    $_REQUEST['menuParent'] = "Treffpunkt";
    $RUNLINKS = new RunLinks($_SESSION['config']->DBCONNECT, "mainpage");
    
    $_SESSION['MENU_PARENT'] = $_REQUEST['menuParent'];
    
    $_REQUEST['run'] = getPageConfigParam( $_SESSION['config']->DBCONNECT, "default_runlink" );
    
    $_SESSION['mainpage'] = $_REQUEST['run']; 
  }



  function createPw($length=6){
  /* --------------------------------------------
     Diese Methode generiert ein Passwort
  -------------------------------------------- */

     //erzeugt ein neues Passwort und gibt es zurÃÂÃÂÃÂÃÂ¼ck
      $pw=array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
      $pwd="";
     
      for ($x=1;$x<$length;$x++){
        $pwd .= $pw[mt_rand(1,count($pw))];	
      }
   	
      return $pwd;
  }



  function needNewPassword($email){
  /* --------------------------------------------
     Diese Methode setzt dem Benutzer der 
     ÃÂÃÂÃÂÃÂbergebenen Email ein frei generiertes
     Passwort
  -------------------------------------------- */

  //PASSWORT AUTOMATISCH GENERIEREN
     $pw = $this->createPw();
          
     $rowUser = $this->USERTABLE->getUserByEmail($email);
     $this->setPassword($rowUser['id'], $pw);
  }




  function setPassword($userId, $pw){
  /* --------------------------------------------
     Diese Methode setzt dem Benutzer der 
     ÃÂÃÂÃÂÃÂbergebenen ID das angegebene Passwort
  -------------------------------------------- */

  //USER-ROW HOLEN 
     $r = $this->USERTABLE->getUser($userId);

  //PASSWORT SPEICHERN     
     $upd = "UPDATE user SET Pw = '" .md5($pw) ."' WHERE id = ".$r['id'];
     $this->DBCONNECT->executeQuery($upd);

  //EMAIL TEMPLATE GENERIEREN
     $tmplArray = array();
     $tmplArray[0] = $r["Vorname"];
     $tmplArray[1] = $r["Nachname"];
     $tmplArray[2] = $r["User"];
     $tmplArray[3] = $pw;
     $tmplArray[4] = date("d. m Y");
     
     if ( $r["aktiv"] != "J" ) {
        $activateCode = $this->createPw(10);
        
        $upd = "UPDATE user SET activationString = '" .$activateCode ."' WHERE id = ".$r['id'];
        $this->DBCONNECT->executeQuery($upd);

        $tmplArray[5] = "klicken Sie auf den folgenden Link um Ihren Zugang zu aktivieren:
                          
                          http://".$_SERVER["HTTP_HOST"]."?do=Activate&userActivate=" .$r["id"] ."&ActivationCode=" .$activateCode ."
                        ";
     }
    
  //MAIL MIT TEMPLATE SENDEN
     $mail = new Mail();
     $mail->setTo($r["Email"]);
     $mail->setFrom("Userverwaltung@".$_SERVER["HTTP_HOST"]);
     $mail->setSendername($r["Email"]);
     $mail->setText("In dieser Mail finden Sie ihr neues Passwort.");
     $mail->setType("pwrequest");
     $mail->setSubject("Login-Informationen fÃÂÃÂÃÂÃÂ¼r: ".$_SERVER["HTTP_HOST"]);
     
     $mail->setTemplateArray($tmplArray);
     $mail->send();


  //MELDUNG AUSGEBEN 
     $m = new Message("Passwort erstellt", "Ihnen wurde soeben ein neues Passwort per Email zugesendet.");
     $m->show();
 

  } 
  
  
  
  
  
   

    /**
     * prÃÂÃÂÃÂÃÂ¼ft ob der angemeldete User 
     * von der ÃÂÃÂÃÂÃÂ¼bergebenen Ressource genug besitzt. 
     */
    function checkHasEnoughRessource( $anzahl, $ressource ){
        $ret = false;
          
        $guthaben = $this->getRessourceGuthaben( $ressource->getRessourceName() );
        
        if($guthaben >= $anzahl){
            $ret = true;
        }
        
        return $ret;
    }






 

    function getRessourceRowByName( $dbTbl, $name ) {
        
        foreach( $dbTbl->ROWS as $row ){
            if( $row->getNamedAttribute("name") == $name ){
                return $row;
            }
        }
        
        return null;
    }
 
 
 
 
 
 
    function existsBuildingOnKoor( $x=null, $y=null, $buildingId ){
        
        $dbTableBuildings = new DbTable($_SESSION['config']->DBCONNECT,
                                        "game_karte_detail",
                                        array("*"),
                                        "",
                                        "",  
                                        "",
                                        "      x=".$x 
                                        ." AND y=".$y
                                        ." AND gebaeude_art_id=".$buildingId
                                        ." AND exists(SELECT 'X' FROM game_karte WHERE x=" .$x ." AND y=".$y." AND user_id=" .$this->USERID .")"
                                        ." AND exists(SELECT 'X' FROM game_gebaeude_art WHERE id=".$buildingId ." AND level<=" .$this->getLevel() .")"
                                        );
                         
        return $dbTableBuildings->getRowCount()>0;
    }
    
    
    
    
    
    
 
    function getRessourceInfoBar(){
        if($this->getUserRow()==null) {
            return new Div();
        }
        
        
        $dbTbl =    new DbTable( $_SESSION['config']->DBCONNECT,
                                 "game_ressourcen_art",
                                 array("*"),
                                 "",
                                 "",
                                 "",
                                 "" );
        
        
        
        
        
        
        $tbl  = new Table(array("Level", "", "EP", "", "Spacer", "Gold", "", "Erz", "", "Felsen", "", "Holz", "", "Wasser", "", "Nahrung", "" ));
        $tbl->setWidth("670"); 
        $tbl->setColSizes( array(16, 30, 16, 40, 10, 13, 80, 13, 80, 13, 80, 13, 80, 13, 80, 13, 80) );
        $tbl->setAlign("left");
        
        $r = $tbl->createRow(); 
    
        $r->setAttribute(0, "Level: " );
        $r->setAttribute(1, $this->getLevel());

        $r->setAttribute(2, "EP: ");
        $r->setAttribute(3, $this->getEP());
 
        $r->setAttribute(4, "");
 
        $ress = new Ressource($this->getRessourceRowByName($dbTbl, "Gold"));
        $gTx = new Text($this->getGold());
        $gTx->setToolTip("Gold");
        $gImg = $ress->getRessourceImage();
        $gImg->setWidth(10);
        $gImg->setHeight(10);
        $gImg->setToolTip("Gold");

        $r->setAttribute(5, $gImg);
        $r->setAttribute(6, $gTx );
          
        $ress = new Ressource($this->getRessourceRowByName($dbTbl, "Erz"));
        $eTx = new Text($this->getErz());
        $eTx->setToolTip("Felsen");
        $eImg = $ress->getRessourceImage();
        $eImg->setWidth(10);
        $eImg->setHeight(10);
        $eImg->setToolTip("Erz");

        $r->setAttribute(7, $eImg);
        $r->setAttribute(8, $eTx);

        $ress = new Ressource($this->getRessourceRowByName($dbTbl, "Felsen"));
        $fTx = new Text($this->getFelsen());
        $fTx->setToolTip("Felsen");
        $fImg = $ress->getRessourceImage();
        $fImg->setWidth(10);
        $fImg->setHeight(10);
        $fImg->setToolTip("Felsen");

        $r->setAttribute(9, $fImg);
        $r->setAttribute(10, $fTx );
        
        $ress = new Ressource($this->getRessourceRowByName($dbTbl, "Holz"));
        $hTx = new Text($this->getHolz());
        $hTx->setToolTip("Holz");
        $hImg = $ress->getRessourceImage();
        $hImg->setWidth(10);
        $hImg->setHeight(10);
        $hImg->setToolTip("Holz");
 
        $r->setAttribute(11, $hImg);
        $r->setAttribute(12, $hTx);

        $ress = new Ressource($this->getRessourceRowByName($dbTbl, "Wasser"));
        $wTx = new Text($this->getWasser());
        $wTx->setToolTip("Wasser");
        $wImg = $ress->getRessourceImage();
        $wImg->setWidth(10);
        $wImg->setHeight(10);
        $wImg->setToolTip("Wasser");

        $r->setAttribute(13, $wImg);
        $r->setAttribute(14, $wTx);

        $ress = new Ressource($this->getRessourceRowByName($dbTbl, "Nahrung"));
        $nTx = new Text($this->getNahrung());
        $nTx->setToolTip("Nahrung");
        $nImg = $ress->getRessourceImage();
        $nImg->setWidth(10);
        $nImg->setHeight(10);
        $nImg->setToolTip("Nahrung");

        $r->setAttribute(15, $nImg);
        $r->setAttribute(16, $nTx);
        
        $tbl->addRow($r);
        
        return $tbl;
    }





    function getLevel(){
        $row = $this->getUserRow();
        
        if($row != null){
            return $row->getNamedAttribute("Level");            
        } else {
            return 0;
        }
    }




    function getEP(){
        $row = $this->getUserRow();
        
        if($row != null){
            return $row->getNamedAttribute("EP");            
        } else {
            return 0;
        }    
    }

    function getGold(){
        return $this->getRessourceGuthaben( "Gold" );    
    }

    function getHolz(){
        return $this->getRessourceGuthaben( "Holz" );
    }

    function getErz(){
        return $this->getRessourceGuthaben( "Erz" );    
    }

    function getFelsen(){
        return $this->getRessourceGuthaben( "Felsen" ); 
    }

    function getWasser(){
        return $this->getRessourceGuthaben( "Wasser" );
    }

    function getNahrung(){
        $row = $this->getUserRow();
        
        if($row != null){
            return $row->getNamedAttribute("Nahrung");            
        } else {
            return 0;
        }
    }







    function setRessourceGuthaben($ressourceName, $betrag){
        $row = $this->getUserRow();
        
        if($row != null){
            $row->setNamedAttribute( $ressourceName, $betrag );
            $row->updateDB();            
        }
    }
     
    
 
    function getRessourceGuthaben( $ressourceName ){
        $row = $this->getUserRow();
        
        if($row != null){
            return $row->getNamedAttribute( $ressourceName );            
        } else {
            
            echo "USER NICHT GEFUNDEN (User-getRessourceGuthaben)";
            return 0;
        }   
    } 



    function existsUsername($name){
            
            $dbTblUser = new DbTable($this->DBCONNECT, 
                                         "user", 
                                         array("*"), 
                                         "", 
                                         "", 
                                         "", 
                                         "User = '".$name ."' ");
                                         
            return $dbTblUser->getRowCount() > 0;
    }


    function getUserRow(){
        if( $this->STATUS == "user" || $this->STATUS == "admin" ){
            
            $dbRowUser = $this->USER_DBROW;
            
            
            $dbTblUser = new DbTable($this->DBCONNECT, 
                                         "user", 
                                         array("*"), 
                                         "", 
                                         "", 
                                         "", 
                                         "id = ".$this->USERID);
            $dbRowUser = $dbTblUser->getRow(1);
            $this->USER_DBROW = $dbRowUser; 
            
            return $dbRowUser;
            
        }
        return null;
    }
    
    
    
    
    
}

















class UserStatus extends Object{
   var $USER; //User Objekt

  function UserStatus(&$usr,$x,$y){
     $this->USER =& $usr;
     $this->XPOS = $x;
     $this->YPOS = $y;
  }

  function show(){
     if($this->USER->STATUS=="admin" || $this->USER->STATUS=="user"){
	$this->showLoggedIn();
     } else {
	$this->showLoggedOff();
     }
  }

  
  function activateUser( $userId, $activateLink ) {
     $usrDbRow = $this->getRowById($userId);
     
     if ( $usrDbRow->getNamedAttribute( "activationString" ) == $activateLink ){
                
        $upd = "UPDATE user SET aktiv = 'J', status = 'user' WHERE id = ".$userId;
        $_SESSION['config']->DBCONNECT->executeQuery($upd);

        $err = new Message("Aktivierungs-Code OK", "Aktivierung abgeschlossen. Sie kÃÂÃÂÃÂÃÂ¶nnen sich nun anmelden");

     } else {
        
        $err = new Error("Falscher Aktivierungs-Code", "Der Aktivierungs-Code ist ungÃÂÃÂÃÂÃÂ¼ltig!");
     }
     
  } 
  
    
  function getRowById($userId){
        $dbTbl = new DbTable( $_SESSION['config']->DBCONNECT, 
                              "user",
                              array("*"),
                              "",
                              "",
                              "",
                              "id=".$userId
                              );
                              
        return $dbTbl->getRow(1);
  }
  
  function getRegisterMask(){
    $frm = new Form($_SERVER['SCRIPT_NAME']);
    
    $tbl = null;
    $arrStr = implode($_REQUEST);
  
    if (  isset($_REQUEST['email']) && strlen($_REQUEST['email'])>0 
       && isset($_REQUEST['vorname']) && strlen($_REQUEST['vorname'])>0
       && isset($_REQUEST['nachname']) && strlen($_REQUEST['nachname'])>0  
       && isset($_REQUEST['user']) && strlen($_REQUEST['user'])>0 
       && isset($_REQUEST['passwort']) && strlen($_REQUEST['passwort'])>0
       && checkAction( $arrStr ) 
       ) {
        $tbl = $this->getCommitRegisterMask();

    } else {
        $tbl = $this->getDefaultRegisterMask();
        
    }

    $frm->add($tbl);
    
    return $frm;
  }
  
  
  
  
  function getCommitRegisterMask(){
    if($this->USER->existsUsername($_REQUEST['user'])){
        $dv = new Div();
        $dv->add(new Title("Benutzername existiert bereits"));
        $dv->add($this->getDefaultRegisterMask());
        
        return $dv;
    }
    
    $ttl = new Title("Benutzer wurde angelegt");
    $ttl->show();
  
    $txt = "Innerhalb der nÃÂÃÂÃÂÃÂ¤chsten Minuten erhalten Sie eine eMail mit dem aktivierungs-Link.<br>"
          ."Sobald sie durch anklicken dieses Links ihre E-Mail bestÃÂÃÂÃÂÃÂ¤tigt haben, ist Ihr Account vollstÃÂÃÂÃÂÃÂ¤ndig aktiviert.<br>"
          ."AnschlieÃÂÃÂÃÂÃÂend kÃÂÃÂÃÂÃÂ¶nnen Sie sich sofort mit ihren Benutzerdaten anmelden.";
    
    $sql = "INSERT INTO user( Vorname, Nachname, Email, User, Pw ) "
          ."VALUES( '" .$_REQUEST['vorname'] ."', '" .$_REQUEST['nachname'] ."', '" .$_REQUEST['email'] ."', '" .$_REQUEST['user'] ."', '" .md5($_REQUEST['passwort']) ."' )";
    $_SESSION['config']->DBCONNECT->executeQuery($sql);
 

     $rowUser = $this->USER->USERTABLE->getUserByEmail($_REQUEST['email']);
     $this->USER->setPassword($rowUser['id'], $_REQUEST['passwort']); 

  }
  
  
  
  function getDefaultRegisterMask(){
    
    $tbl = new Table( array( "", "" ) );
    $tbl->setSpacing(4);
    $tbl->setWidth(250);
    
    $r = $tbl->createRow();
    $r->setAttribute(0, "Vorname:");
    $txfUsername = new Textfield( "vorname" );
    $r->setAttribute(1, $txfUsername);
    $tbl->addRow($r);
    
    $r = $tbl->createRow();
    $r->setAttribute(0, "Nachname:");
    $txfUsername = new Textfield( "nachname" );
    $r->setAttribute(1, $txfUsername);
    $tbl->addRow($r);
    
    $r = $tbl->createRow();
    $r->setAttribute(0, "E-Mail:");
    $txfUsername = new Textfield( "email" );
    $r->setAttribute(1, $txfUsername);
    $tbl->addRow($r);
    
    
    
    
    $r = $tbl->createRow();
    $r->setAttribute(0, "Benutzer:");
    $txfUsername = new Textfield( "user" );
    $r->setAttribute(1, $txfUsername);
    $tbl->addRow($r);
    
    $r = $tbl->createRow();
    $r->setAttribute(0, "Passwort:");
    $txfUsername = new Textfield( "passwort" );
    $r->setAttribute(1, $txfUsername);
    $tbl->addRow($r);
    
    
    $r = $tbl->createRow();
    $r->setAttribute(0, new Button("saveRegister", "Speichern"));
    
    $r->setAttribute(1, "");
    $tbl->addRow($r);
    
    $frm = new Form($_SERVER['SCRIPT_NAME']);
    $frm->add($tbl);
    return $frm;
  }
  
  
  
  
  
  
  function showLoggedIn(){
	echo "
         <table WIDTH='125' border='0' cellspacing='0' cellpadding='0' ";

        if($this->XPOS>=0 && $this->YPOS>=0){        
         echo "style=\" background-repeat:repeat;background-image:url(pics/halftransparent.png);background-position: 0px 0px;  position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; \" ";
       }

       echo "	 >
          <tr>
            <td colspan='2' class='userstatus'> 
              <font size='1'>
			<b>Sie sind angemeldet als:</b>
             </font>
           </td>
          </tr>
          <tr>
            <td WIDTH='40'  class='userstatus'> 
              <font size='1'>
		       NAME:
	       </font>
            </td>
            <td  class='userstatus'>                    
              <font size='1'>
			 <b>" .$this->USER->VORNAME ." " .$this->USER->NACHNAME."</b><br> 
	       </font>
           </td>
         </tr>
          <tr>
            <td  class='userstatus'> 
              <font size='1'>
		       STATUS:
              </font>
            </td>
            <td  class='userstatus'>                    
              <font size='1'>
			 <b>" .$this->USER->STATUS ."</b><br> 
              </font>
           </td>
         </tr>
          <tr>
            <td colspan='2'  class='userstatus'> ";

	   $changeUserLink = new Link("index.php?run=changeMyProfile");
	   $changeUserLink->setText("Profil bearbeiten");
	   $changeUserLink->show();		
		
       echo "<br>";
	   $logoutLink = new Link("index.php?do=logout");
	   $logoutLink->setText("Abmelden");
	   $logoutLink->show();

		
	   echo "
           </td>
          </tr>
       </table>
       ";
  }



  function showLoggedOff(){
	echo "<form method='post' action='index.php' target='_top'>
        <table WIDTH='125' border='0' cellspacing='0' cellpadding='0'";

        if($this->XPOS>=0 && $this->YPOS>=0){        
         echo "style=\" background-repeat:repeat;background-image:url(pics/halftransparent.png);background-position: 0px 0px;   position:absolute; top:" .$this->YPOS ."px; left:" .$this->XPOS ."px; \" ";
       }

       echo ">
          <tr>
            <td colspan='2' > 
              <font size='1'>
		       Benutzer:<br>
              </font>
		<input type='text' NAME='user' maxlength='20' size='6'>
            </td>
          </tr>

          <tr>
            <td WIDTH='45'>                    
              <font size='1'>
			Passwort:<br>
	       </font>
		<input type='password' NAME='pw' maxlength='20'  size='6'>
            </td>
            <td valign='bottom'>
              <input type='submit' NAME='do' value='login'>
            </td>

          </tr>
          <tr> 
            <td colspan='2'> 
                <a href='index.php?run=userRequestPw' target='_top'> 
                    <font size='1'>Passwort vergessen?</font>
                </a> 
            </td>
        </tr>
        <tr>
            <td colspan='2'> 
                <a href='index.php?run=register' target='_top'> 
                    <font size='1'>Registrieren</font>
                </a>
            </td>
        </tr>
       </table>
     </form>
       ";
  }

}






?>