<?PHP

	/**
	 * erzeugt die Galery.xml 
	 * 
	 * Erzeugt die Standard-Parameter anhand der Übergebenen Werte 
	 * und fügt anschließend für jedes Bild im Unterordner "images" 
	 * des $folderFullName einen Eintrag in der XML hinzu.
	 * 
	 * 
	 * @param $folderFullName  String  -  Volle Pfadangabe zum ZielOrdner
	 * @param $imagePadding    int     -  Abstand zwischen den Bildern in px
	 * @param $displayTime     int     -  Anzeigedauer pro Bild 
	 */
	function createXmlForBB($folderFullName, $imagePadding, $displayTime){
		if(substr($folderFullName, strlen($folderFullName)-1,1)!="/"){
			$folderFullName .= "/";
		}
		
		//Datei öffnen
		$xmlFile = fopen ($folderFullName."gallery.xml", "w");
		flock($xmlFile, 2); //Schreibschutz Lock
		
		$nl =  chr(10); 
		
		$headerString1 = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".$nl;
		
		$headerString2 = "<gallery title=\"Bildergalerie_" .date("d-m-Y")  ."\" frameColor=\"0xEEEEEE\" frameWidth=\"3\" imagePadding=\"" 
		                   .$imagePadding ."\" displayTime=\"" .$displayTime .".00\">".$nl;
		
		// XML Header
		fputs ( $xmlFile, $headerString1 );
		// Gallery-Header
		fputs ( $xmlFile, $headerString2 );
		
	
	
		writeXmlImageEntrys($xmlFile, $folderFullName."images/");		
		
	
	
		// Gallery-CloseTag
		fputs ( $xmlFile, "</gallery>".$nl);
		
		flock($xmlFile, 3); //Schreibschutz aufheben
		fclose($xmlFile);   
	}
	
	
	
  function getFileNameFromFullPath($p){
  	if(substr($p, strlen($p)-1, 1 ) == "/" ){
	  $p = substr($p, 0, strlen($p)-1);	
	}
	
	$exp = explode("/", $p);
	
	return $exp[ count($exp)-1 ];
  }
	
	
	/**
	 * aktuallisiert die Galery.xml 
	 * 
	 * Aktuallisiert die Standard-Parameter anhand des Übergebenen Verzeichnisses 
	 * und fügt anschließend für jedes Bild im Unterordner "images" 
	 * des $folderFullName einen Eintrag in der XML hinzu.
	 * 
	 * 
	 * @param $folderFullName  String  -  Volle Pfadangabe zum ZielOrdner
	 */
	function updateXmlForBB($folderFullName){
		if(substr($folderFullName, strlen($folderFullName)-1,1)!="/"){
			$folderFullName .= "/";
		}
		
		$nl = chr(10); 
		
		
		//Ursprung öffnen und 2 Zeilen einlesen
		$xmlFileOriginal = fopen ($folderFullName."gallery.xml", "r");
		$l1 = fgets($xmlFileOriginal);
		$l2 = fgets($xmlFileOriginal);


		//Datei öffnen
		$xmlFile = fopen ($folderFullName."gallery.xml", "w");
		flock($xmlFile, 2); //Schreibschutz Lock

		// XML Header
		fputs ( $xmlFile, $l1 );
		fputs ( $xmlFile, $l2 );	
		
	
	
		writeXmlImageEntrys($xmlFile, $folderFullName."images/");		
		
	
	
		// Gallery-CloseTag
		fputs ( $xmlFile, "</gallery>".$nl);
		
		flock($xmlFile, 3); //Schreibschutz aufheben
		fclose($xmlFile);   
	}
	
	
	
	
	/**
	 * erzeugt die Image-Einträge in der übergebenen XML 
	 * 
	 * !!! Einträge werden an der aktuellen Cursor-Position geschrieben !!!
	 * 
	 * 
	 * @param $xmlFile              FileRessource    -  DateiObjekt (Rückgabewert von fopen)
	 * @param $imageFolderFullName  String  	     -  Volle Pfadangabe zum Ordner mit den Bildern
	 */
	function writeXmlImageEntrys($xmlFile, $imageFolderFullName){
		if(substr($imageFolderFullName, strlen($imageFolderFullName)-1,1)!="/"){
			$imageFolderFullName .= "/";
		}
		
		// Bilder im Images-Ordner ermitteln
		$folder = new Folder($imageFolderFullName);
		$imgArr = $folder->getArrayOfPictures();	
		
		// Bilder der Reihe nach einlesen und in die Xml schreiben
		foreach($imgArr as $imgName){
			$img = new Image($imgName);
			
			$fileName    = "images/".getFileNameFromFullPath($imgName);
			$fileWidth   = $img->getImageRealWidth();
			$fileHeight  = $img->getImageRealHeight();
			
			$sizeArr     = recalculateBBImageSize($fileWidth, $fileHeight);
			$fileWidth   = $sizeArr[0];
			$fileHeight  = $sizeArr[1];
			
			
			$imageXmlString = "
				<image>
					<filename>" .$fileName ."</filename>
					<caption></caption>
					<width>" .$fileWidth ."</width>
					<height>" .$fileHeight ."</height>
				</image>
			";		
			
			// XML Header
			fputs ( $xmlFile, $imageXmlString.chr(13).chr(10) );			
		}
		
	}



	
	/**
	 * liefert neue Höhen/Breiten-Werte als Array zurück
	 * 
	 * @param $width    int   Breite
	 * @param $height   int   Höhe
	 * 
	 * @return array(WIDTH, HEIGHT)
	 */
	function recalculateBBImageSize($width, $height){
	
		if($width>600){
			$nFactor = $width / 600;
			
			$width  = 600;
			$height = round($height / $nFactor, 0);
		}
		
		
		if($height>350){
			$nFactor = $height / 350;
			
			$height  = 350;
			$width = round($width / $nFactor,0);
		}
		
		
		return array($width, $height);
	}

?>