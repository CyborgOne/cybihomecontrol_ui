<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

class Folder {
  public $PATH; //Pfad des Verzeichnisses
  public $NAME; //Name des Verzeichnisses
  public $CONTENT; //Inhalte (Dateien und Ordner)
  public $CHECKED; // Gibt an, ob die Adresse bereits geprÃÂÃÂÃÂÃÂ¼ft (mit setter gesetzt) wurde
  
  /**
  *  Standard Konstruktor
  *  ÃÂÃÂÃÂÃÂbergabeparameter: vollstÃÂÃÂÃÂÃÂ¤ndige Pfadangabe
  */
  function Folder($path){
    $this->CONTENT = array();

    $this->CHECKED = false;
     
    $this->setPath($path);
    $this->NAME = $this->getFolderName();

    // Aktuelles Verzeichniss laden
    $verzeichnis = opendir($this->getPath());



/* IDEE:
  evtl wÃÂÃÂÃÂÃÂ¤re ein explode(execute("ls")...);  schneller ???
*/

    // EintrÃÂÃÂÃÂÃÂ¤ge in Array schreiben
    $eintrag = readdir($verzeichnis);
    while($eintrag){
      if($eintrag!=".." && ($eintrag != ".")){
        array_push($this->CONTENT, $this->PATH."/".$eintrag);
      }

      //NÃÂÃÂÃÂÃÂ¤chsten Eintrag holen
      $eintrag = readdir($verzeichnis);
      
    }
  }


 /**
 * Setzt das aktuelle Verzeichniss (volle Pfandangabe)
 */ 
  function setPath($path){
    // DOC-ROOT vorbereiten
    $dr = $_SERVER['DOCUMENT_ROOT'];
    if(substr($dr, strlen($dr)-1, 1) == "/" ){
        $dr = substr($dr,0,strlen($dr)-1);
    }

  	// Relatives Verzeichniss vorbereiten    
    // DOC-ROOT abschneiden wenn mit ÃÂÃÂÃÂÃÂ¼bergeben
    if( substr($path, 0, strlen($dr)) == $dr ){
      $path = substr($path, strlen($dr));
    }
    
    // FÃÂÃÂÃÂÃÂ¼hrendes Slash einfÃÂÃÂÃÂÃÂ¼gen wenn nÃÂÃÂÃÂÃÂ¶tig
    if(substr($path, 0, 1) != "/"){
      $path = "/".$path;	
    }
    
    // AbschlieÃÂÃÂÃÂÃÂendes Slash einfÃÂÃÂÃÂÃÂ¼gen wenn nÃÂÃÂÃÂÃÂ¶tig
    if( substr( $path, strlen($path)-1, 1 ) != "/"){
      $path = $path."/";
    }
    
   
  	if(is_dir($dr .$path)){
        $this->PATH = $dr.$path;
        $this->setChecked(true);
    } else { 
        new Error("FEHLER", "Verzeichniss: " .$dr.$path ." existiert nicht!");
    }
    
  }

  function setChecked($c){
     $this->CHECKED = ($c  === true);
  }

  function isChecked(){
    return $this->CHECKED === true;
  }

 /**
 * Liefert die volle Pfandangabe zum aktuellen Verzeichniss
 */ 
  function getPath(){
    if(! $this->isChecked() ){
        $this->setPath($this->PATH);
    }
    
    return $this->PATH;
  }








   /**
   *  liefert den Ordnernamen ohne komplette Pfadangabe
   */
  function getFolderName(){
    $ret = "";

    $array = explode ( '/', $this->PATH );
 
    for ( $x = 0; $x < count ( $array ); $x++ ){
       $ret = $array[$x];
    }
    
    return $ret;
  }


   /**
   *  liefert den Ordnernamen des ÃÂÃÂÃÂÃÂ¼bergeordneten Ordners ohne komplette Pfadangabe
   */
  function getParentFolderName(){
	$ret = "";
	$p = $this->getParentFolderPath();
	$array = explode ( '/', $p );
    for ( $x = 0; $x < count ( $array ); $x++ ){
       $ret = $array[$x];
    }
	return $ret;
  }


  /** 
  *  Liefert ein Array mit allen Dateinamen im aktuellen Ordner
  */
  function getFileArray(){
    $ret = array();
    
    foreach($this->CONTENT as $cont){
      if(is_file($cont)){
        array_push($ret, $cont);
      }
    }

    return $ret;
  }


   /**
   *  liefert den Pfad des ÃÂÃÂÃÂÃÂbergeordneten Ordners 
   */
  function getParentFolderPath(){
	$ret = "";
	
	$endPos = strlen($this->PATH) - strlen($this->getFolderName())-1;
	
	$ret = substr($this->PATH, 0, $endPos);
	
	return $ret;
  }




  /** 
  *  Liefert ein Array mit allen Dateinamen im aktuellen Ordner
  *  die den ÃÂÃÂÃÂÃÂ¼bergebenen String enthalten
  */
  function getFileArrayByName($part){
    $ret = array();
    
    foreach($this->CONTENT as $cont){
      if(is_file($cont) && strpos($cont, $part)){
        // Dateiname in Array hinzufÃÂÃÂÃÂÃÂ¼gen
        array_push($ret, $cont);
      }
    }

    return $ret;
  }




  /** 
  *  Liefert ein Array mit allen Verzeichnissnamen der Unterverzeichnisse 
  */
  function getFolderArray(){
    $ret = array();

    foreach($this->CONTENT as $cont){
      if(is_dir($cont) &&  substr(substr($cont,strrpos($cont,"/")+1),0,1)!="."){
        array_push($ret, $cont);
      }
    }

    return $ret;
  }





  /** 
  *  Liefert ein Array mit allen Verzeichnissnamen der Unterverzeichnisse 
  *  die den ÃÂÃÂÃÂÃÂ¼bergebenen String enthalten
  */
  function getFolderArrayByName($part){
    $ret = array();

    foreach($this->CONTENT as $cont){
      if(is_dir($cont) && strpos($cont, $part)){
        $ret[count($ret)] = $cont;
      }
    }

    return $ret;
  }







  /** 
  *  Liefert ein Array mit allen Dateinamen bei denen es sich laut Dateiendung um Bilder handelt
  */
  function getArrayOfPictures(){
    $ret = array();
    $fileTypes = array("jpg", "gif", "png", "bmp", "tif");


    foreach($this->CONTENT as $cont){
      foreach($fileTypes as $ftype){
        if(is_file($cont) && strpos(strtolower($cont), strtolower($ftype)) ){
          $ret[count($ret)] = $cont;
        }
      }
    }

    return $ret;
  }


  /** 
  *  Liefert ein Array mit allen Dateinamen bei denen es sich laut Dateiendung um Bilder handelt
  *  Die ÃÂÃÂÃÂÃÂ¼bergebene Liste wird im gegensatz zu  getArrayOfPictures() auf ÃÂÃÂÃÂÃÂbereinstimmung des Anfangs 
  *  vom Dateinamen gegen das ÃÂÃÂÃÂÃÂ¼bergeben prefix geprÃÂÃÂÃÂÃÂ¼ft
  * 
  * @param $prefix Gibt den zu suchenden Dateianfang an  
  */
  function getArrayOfPicturesByPrefix($prefix){
	//Array aller Bilder holen
	$arr = $this->getArrayOfPictures();
	$ret = array();
	
	//Array filtern und in neues Array ($ret) schreiben
	foreach($arr as $key=>$val){
	  if(substr($val, strlen($this->getPath())+1, strlen($prefix)) == $prefix){
		array_push($ret, $val); 
	  }
	}
	
	return $ret;
  }



  /** 
  *  Liefert ein Array mit dem aktuellen Inhalt des Objektes
  *  der RÃÂÃÂÃÂÃÂ¼ckgabetyp ist ein Array in dem die Dateinamen als Strings zurÃÂÃÂÃÂÃÂ¼ckgegeben werden
  */
  function getContent(){
    return $this->CONTENT;
  }


  /**
  * Zeigt die Dateiauswahl fÃÂÃÂÃÂÃÂ¼r uploadFile() an 
  */  
  function showFilechooser($accept=""){
  	if(!is_writeable($this->getPath())){
		new Error("Verzeichniss schreibgeschÃÂÃÂÃÂÃÂ¼tzt", "Das Verzeichniss *" .$this->getPath() ."* ist schreibgeschÃÂÃÂÃÂÃÂ¼tzt." );
	}
	$form = new Form($_SERVER['SCRIPT_NAME']);
	$form->setParam( "enctype='multipart/form-data'");
	
	$fileChooser = new Filechooser("probe", $accept);
	$form->add($fileChooser);
	
	$hidden = new HiddenField("folderFileUploader", "doUpload");
	$form->add($hidden);
	
	$btn = new Button("", "Hochladen");
	$form->add($btn);
	
	$form->Show();
  }


  /**
  * LÃÂÃÂÃÂÃÂ¤dt eine ausgewÃÂÃÂÃÂÃÂ¤hlte Datei (Filechooser = Textfeld + Durchsucheb-Button) in das aktuelle Verzeichniss
  */   
  function uploadFile(){
	if(isset($_REQUEST['folderFileUploader']) && $_REQUEST['folderFileUploader'] == "doUpload"){	  
	  $this->doUploadFile( $_FILES['probe'], substr($this->getPath(), strlen($_SERVER['DOCUMENT_ROOT'])) );
	} else {
	  $this->showFilechooser();
	}
  }
  
  
  /**
  * FÃÂÃÂÃÂÃÂ¼hrt den Dateiupload der ÃÂÃÂÃÂÃÂ¼bergebenen Datei ($_FILES['probe']) in das (optional) ÃÂÃÂÃÂÃÂ¼bergebene Zielverzeichniss (String) aus.
  * Das Zielverzeichniss wird relativ zum DocumentRoot Verzeichniss  ausgewertet!     
  */   
  function doUploadFile($probe, $targetPath="", $targetfile_prefix="", $targetFileName=""){
  	  if(!is_writeable($this->getPath())){
		new Error("Verzeichniss schreibgeschützt", "Das Verzeichniss *" .$this->getPath() ."* ist schreibgeschÃÂÃÂÃÂÃÂ¼tzt." );
	  }

	  if ( strlen($probe['tmp_name'])>0  ) {
	  	if ($probe['error']) {
		  new Error("Fehler beim Upload", $probe['error'] ); 
		}
	  	
	    //VollstÃÂÃÂÃÂÃÂ¤ndiger Ziel-Name incl. gesamter Pfadangabe
	    $target= $_SERVER['DOCUMENT_ROOT'].$targetPath.$targetfile_prefix.$probe['name'];
	    
	    if($targetFileName!=""){
			$target= $_SERVER['DOCUMENT_ROOT'].$targetPath.$targetFileName;	
		}
	    
	    move_uploaded_file($probe['tmp_name'], $target );

	    
		return $target;
	  
	  }	else {
	    new Error("Fehlende Eingabe", "Es wurden nicht alle Werte fÃÂÃÂÃÂÃÂ¼r den Dateiupload angegeben!" );
	  }
  }
  
}

?>