<?PHP



class ImageUploader extends Object {
	// Relative Pfadangabe des Zielverzeichnisses
	private $TARGET_PATH;
	
	// Prefix welches vor den Dateinamen gesetz wird
	private $FILE_PREFIX;

	
	// Wenn gefÃÂÃÂÃÂÃÂ¼llt, wird in die entsprechende Row der Tabelle in die angegebene Spalte 
	// der Dateiname mit relativer Pfadangabe gespeichert
	private $DB_INSERT_TABLE;
	private $DB_INSERT_COLUMN;
	private $DB_INSERT_ID;
	public  $HIDDEN_OBJECT;
	
	public  $TARGETFILENAME;		
	
	function ImageUploader($targetPath, $filePrefix="", $dbInsertTable="", $dbInsertColumn="", $dbInsertId="", $hiddenObj="", $targetFileName=""){
		
		
		$this->setTargetPath($targetPath);
		$this->setFilePrefix($filePrefix);

		$this->DB_INSERT_TABLE   = $dbInsertTable;
		$this->DB_INSERT_COLUMN  = $dbInsertColumn;
		$this->DB_INSERT_ID      = $dbInsertId;
		
		$this->TARGETFILENAME    = $targetFileName;
		
		if($hiddenObj instanceof Object){
		  $this->HIDDEN_OBJECT     = $hiddenObj;
		}
	}
	
	
	
	function getTargetPath(){
		return $this->TARGET_PATH;
	}
	
	
	function setTargetPath($tp){
	  $f = dirname($_SERVER['SCRIPT_NAME']);
	  
	  if( file_exists($tp) ){	
		  $this->TARGET_PATH = $tp;
	  } else {
	    $m = new Error("Fehlerhaftes Verzeichniss", "Verzeichniss *" .$tp ."* existiert nicht!");	
	  }
	}
	
	
	
	function getFilePrefix(){
		return $this->FILE_PREFIX;
	}
	
	
	function setFilePrefix($fp){
		$this->FILE_PREFIX = $fp;
	}
	
	
	
	/**
	 * diese Methode muss ÃÂÃÂÃÂÃÂ¼berschrieben werden, 
	 * wenn nach dem Hochladen der Datei noch eine weitere Aktion ausgefÃÂÃÂÃÂÃÂ¼hrt werden soll.
	 * 
	 * Informationen zur hochgeladenen Datei erhÃÂÃÂÃÂÃÂ¤lt man mittels  $_FILES['probe']
	 * 
	 * Bspl:
	 * $_FILES['probe']['name']
	 * $_FILES['probe']['tmp_name']
	 * $_FILES['probe']['error']
	 */
	function doPostUpload(){
		
	}



	/**
	 * trÃÂÃÂÃÂÃÂ¤gt den Dateinamen mit relativer Pfadangabe in die angegebene Tabelle/Spalte/Zeile ein, 
	 * wenn die entsprechenden 3 DbInsert... Werte angegeben wurden.
	 */
	function insertIntoDbIfNeeded($newFileName){
	
		if( strlen($this->DB_INSERT_TABLE)>0 &&
	        strlen($this->DB_INSERT_COLUMN)>0 &&
		    strlen($this->DB_INSERT_ID)>0 ){
		    	
		      $sql = "UPDATE "  .$this->DB_INSERT_TABLE 
			        ."   SET " .$this->DB_INSERT_COLUMN ." = '" .$newFileName ."' "
					." WHERE id = " .$this->DB_INSERT_ID;
			  
			  $_SESSION['config']->DBCONNECT->executeQuery($sql); 
		}

	}
	

	
	/**
	*  Dateiupload-Dialog  
	*/
	function showFileUploadMask(){
	  $title = new Title("Bild hochladen.");
	  $title->show();
	  
	  $form = new Form(basename($_SERVER['SCRIPT_NAME']),"","","Upload");
	  $form->setParam("enctype='multipart/form-data'");
	  
	  if( $this->HIDDEN_OBJECT instanceof Object ){
		 $form->add($this->HIDDEN_OBJECT);
	  }
	  
	  $text = new Text("Im folgenden Dialog wÃÂÃÂÃÂÃÂ¤hlen sie das hochzuladende Bild aus und kÃÂÃÂÃÂÃÂ¶nnen es anschlieÃÂÃÂÃÂÃÂend durch klicken des *Datei Hochladen* Buttons auf den Server laden. \n \n Es sollten nur .JPG Dateien hochgeladen werden da andere Formate Probleme verursachen kÃÂÃÂÃÂÃÂ¶nnen, und evtl nicht angezeigt werden.\n");
	  $form->add($text);
	  
	  $fileChooser = new Filechooser("probe","image/*");
	  $fileChooser->setToolTip("WÃÂÃÂÃÂÃÂ¤hlen Sie Hier die hochzuladende Datei aus.");
	  $form->add($fileChooser);
	
	  $hidden = new HiddenField("picImportUpload", "doUpload");
	  $form->add($hidden);

	  if(isset($_REQUEST['targetPath'])){
		$hiddenpath = new HiddenField("targetPath", $_REQUEST['targetPath']);
	    $form->add($hiddenpath);
	  }

	  if(isset($_REQUEST['filePrefix'])){
		$hiddenpre = new HiddenField("filePrefix", $_REQUEST['filePrefix']);
	    $form->add($hiddenpre);
	  }

	  if(isset($_REQUEST['DbInsertTable'])){
		$hiddent = new HiddenField("DbInsertTable", $_REQUEST['DbInsertTable']);
	    $form->add($hiddent);
	  }

	  if(isset($_REQUEST['DbInsertCol'])){
		$hiddenc = new HiddenField("DbInsertCol", $_REQUEST['DbInsertCol']);
	    $form->add($hiddenc);
	  }
	  
	  if(isset($_REQUEST['DbInsertId'])){
		$hiddeni = new HiddenField("DbInsertId", $_REQUEST['DbInsertId']);
	    $form->add($hiddeni);
	  }

	
	  $btn = new Button("upload", "Datei hochladen", "alert('Datei wird hochgeladen.Dieser Vorgang kann einige Zeit in Anspruch nehmen!Nach Abschluss erhalten Sie eine BestÃÂÃÂÃÂÃÂ¤tigung des Upload-Vorgangs.Brechen Sie nicht vorher Ab!')");
	  $btn->setOnClickType("Submit");
	  $form->add($btn);
	  
	  $form->show();
	}
	
	
	
	
	
	/**
	 * Dateiupload-Vorgang ausfÃÂÃÂÃÂÃÂ¼hren
	 */ 
	function doUploadFile(){
	  //Relative Pfadangabe fÃÂÃÂÃÂÃÂ¼r Upload-Verzeichniss ermitteln
	  $f = dirname($_SERVER['SCRIPT_NAME']);
	  //wenn vorhanden ersten slash abschneiden
	  if(substr($f,0,1)=="/" && strlen($f)>0){$f = substr($f,1);}
	  //wenn nicht vorhanden letzten slash anhÃÂÃÂÃÂÃÂ¤ngen
	  if(substr($f,strlen($f)-1&& strlen($f)>0) != "/"){	$f = $f."/";}
	  //Verzeichniss erzeugen
	  $folder = new Folder($_SERVER['DOCUMENT_ROOT'] ."/" .$f .$this->getTargetPath() );  
	  
	  $newFileName = $folder->doUploadFile($_FILES['probe'], substr($folder->getPath(), strlen($_SERVER['DOCUMENT_ROOT'])), $this->getFilePrefix(), $this->TARGETFILENAME );
	  
	  $t = new Text("Datei wurde hochgeladen.\n\n");
	  $t->show();  
	  
	  $this->insertIntoDbIfNeeded($newFileName);
	  
	  $this->doPostUpload();	
	}





	/**
	 * Zeigt je nach bedarf die Datei-Auswahl an oder fÃÂÃÂÃÂÃÂ¼hrt den Upload-Vorgang durch.
	 */
	function show(){
		if(!isset($_REQUEST['picImportUpload']) || (isset($_REQUEST['picImportUpload']) && $_REQUEST['picImportUpload']!="doUpload")){
		  //Solange der Upload-Dialog nicht bestÃÂÃÂÃÂÃÂ¤tigt wurde, Upload-Maske anzeigen
		  $this->showFileUploadMask();
		} else if( isset($_FILES['probe']) ){
		  //Wenn $_FILES['probe'] gesetzt ist, Datei hochladen
		  $this->doUploadFile();

		} else {
		  
		  //Fehlt die Berichts-Id, diese auswÃÂÃÂÃÂÃÂ¤hlen und Datei hochladen.
		  if( isset($_FILES['probe']) ){
		    $e = new Error("Datei nicht gefunden", "Keine Datei zum hochladen gefunden. ");
		  }

		  $e = new Error("Unerwarteter Fehler", "Es ist ein unerwarteter Fehler aufgetreten. \n\nSollte der Fehler wieder auftauchen informieren Sie uns bitte ÃÂÃÂÃÂÃÂ¼ber das Kontaktformular. ");
		}
	}
	
}

?>