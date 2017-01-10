<?php

/**
 * @author Daniel Scheidler
 * @copyright Mai 2008
 */

/**
*  Das Bilderbuch zeigt, wie der Name schon vermuten lässt, Bilder aus einem angegebenen Ordner an
*  Optional lassen sich Unterordner als Unterkategorien mit einblenden.
*  (Kompletter Verzeichniss-Baum unterhalb $this->PATH wird als BB angesehen.)
*/

class Bilderbuch extends Object {
  private $PATH;  //Der aktuelle Pfad
  private $ROOTPATH;//*untersten Ebene*
  private $FOLDER;
  
  private $LINKPREFIX;
  
  private $CURRENT_PICTURE;
  private $CURRENT_PAGE;
  private $PARENT_PAGE;
  private $SHOW_TITLE = false;
  private $IMAGEHEIGHT;
  private $IMAGEDIRECTLINK; // nächstes/voriges Navigation an ausschalten

  private $PICS;  //array() in dem alle Bilder als Volle Pfadangabe bereit gehalten werden.
  private $PICS_PER_LINE;  //gibt an wie viele Bilder pro Zeile in der Tabellarischen Ansicht angezeigt werden sollen.
  private $MAXIMUM_LINES;  //gibt an wie viele Zeilen in der Tabellarischen Ansicht angezeigt werden sollen.


  /**
  *  @param $path muss die volle Pfadangabe enthalten
  *  @param $prefix kann optional einen Anfangswert des Dateinamens vorgeben (Suche nach Dateianfang)   
  */  
  function Bilderbuch($path,$prefix="",$linkPrefix="",$imgDirect=false){
    $this->PICS_PER_LINE = 6;
    $this->MAXIMUM_LINES = 2;
    $this->WIDTH = $_SESSION['mainpanelwidth'];

  	//Verzeichniss vorbereiten
    $this->setPath($path);
    $this->ROOTPATH = $this->PATH;  //zur ÃÂÃÂÃÂÃÂberprÃÂÃÂÃÂÃÂ¼fung der *untersten Ebene*
    $this->setImageHeight($_SESSION['mainpanelheight']-150);
    $this->CURRENT_PICTURE = 0;
    $this->CURRENT_PAGE = 1;
    $this->IMAGEDIRECTLINK = $imgDirect;
    $this->setShowTitle(true);
 
    $this->LINKPREFIX = $linkPrefix;

    $this->PARENT_PAGE =  basename($_SERVER['SCRIPT_NAME']);
    if( strlen($prefix)>0){
      $this->setPics($this->FOLDER->getArrayOfPicturesByPrefix($prefix));
    }else{
      $this->setPics($this->FOLDER->getArrayOfPictures());
    }
  }



  /**
  *  Gibt an ob Links in showPicList() direkt zum Vollbild verweisen sollen.
  * @param $bool (boolean)  Wird hier false angegeben (default), leiten die Links der Bilderliste in eine Einzelvorschau mit "nÃÂÃÂÃÂÃÂ¤chstes/voriges Bild-Navigation"
  *                         bei true verweist der Link sofort zur Vollbildansicht in neuem Target    
  */  
  function setImageDirectLink($bool){
    if ($bool){
	  $this->IMAGEDIRECTLINK = $bool;
	} else {
	  $this->IMAGEDIRECTLINK = false;
	}
	
  }

  function isShowTitle(){
    return $this->SHOW_TITLE;
  }

  function setShowTitle($bool){
    if ($bool){
	  $this->SHOW_TITLE = $bool;
	} else {
	  $this->SHOW_TITLE = false;
	}
	
  }

  function getImageDirectLink(){
    $this->IMAGEDIRECTLINK = $pics;
  }




/**
*  setzt und sortiert das Array aller Bildnamen im aktuellen Verzeichniss.
*/
  function setPics($pics){
    //alle Bilder holen
    $this->PICS = $pics;
    if(count($this->PICS)>1){
      sort($this->PICS);
    }
  }

  function setPicsPerLine($pics){
    $this->PICS_PER_LINE = $pics;
  }

  function setMaximumLines($lines){
    $this->MAXIMUM_LINES = $lines;
  }

  function setImageHeight($h){
    $this->IMAGEHEIGHT = $h;
  }



/**
*  setzt das Aktuelle Verzeichniss (aktualisiert ebenfalls $this->FOLDER)
*/
  function setPath($p){
    if(! isset($this->FOLDER) || $p != $this->FOLDER->getPath() && $p != '') {
      $this->FOLDER = new Folder($p);
      $this->PATH = $this->FOLDER->getPath();

      $this->setPics($this->FOLDER->getArrayOfPictures());
    }
  }




/**
*  liefert das Aktuelle Verzeichniss als String
*/
  function getPath(){
    return $this->PATH;
  }


/**
*  veranlasst einen Verzeichnisswechsel
*  aktueller Pfad wird geÃÂÃÂÃÂÃÂ¤ndert (aktualisiert das $this->FOLDER Objekt automatisch)
*/
  function handleChanges(){
    if (isset($_REQUEST['Current_BB_Path']) && strlen($_REQUEST['Current_BB_Path'])>0 ){
	  $_SESSION['tmp']['Current_BB_Path'] = $_REQUEST['Current_BB_Path'];
	}
	
	if (isset($_SESSION['tmp']['Current_BB_Path']) && strlen($_SESSION['tmp']['Current_BB_Path'])>=0 ){
	  $this->setPath($_SERVER['DOCUMENT_ROOT'].$_SESSION['tmp']['Current_BB_Path']);
    }


    if( isset($_REQUEST['BB_Change_To_Parent_Folder']) && strlen($_REQUEST['BB_Change_To_Parent_Folder'])>0 ){
      $newPath = $this->getPath() ."/" .$_REQUEST['BB_Change_To_Parent_Folder'];
      $np = $this->FOLDER->getParentFolderPath();
      $_SESSION['tmp']['Current_BB_Path'] = substr($np, strlen($_SERVER['DOCUMENT_ROOT']));
	  $this->setPath($np);
    }

    if( isset($_REQUEST['BB_Change_Folder']) && strlen($_REQUEST['BB_Change_Folder'])>0 ){
      $newPath = $this->getPath() ."/" .$_REQUEST['BB_Change_Folder'];
      $_SESSION['tmp']['Current_BB_Path'] = substr($newPath, strlen($_SERVER['DOCUMENT_ROOT']));
      $this->setPath($newPath);
    }

    if( isset($_REQUEST['changeBbPage']) && strlen($_REQUEST['changeBbPage'])>0 ){
      $this->changeCurrentPage($_REQUEST['changeBbPage']);
    }

    if( isset($_REQUEST['showPic'])  && $_REQUEST['showPic'] >= 0 ){
      $this->CURRENT_PICTURE = $_REQUEST['showPic'];
    }

    if( isset($_REQUEST['BB_Change_To_Next_Picture']) && $_REQUEST['BB_Change_To_Next_Picture'] >= 0 ){
      $this->getNextPicture();
    }

    if( isset($_REQUEST['BB_Change_To_Prev_Picture']) && $_REQUEST['BB_Change_To_Prev_Picture'] >= 0 ){
      $this->getPreviousPicture();
    }
  }


 /**
*  veranlasst den Wechsel der aktuell angezeigten Seite (bzgl Picture-List)
*/
  function changeCurrentPage($cp){
    $this->CURRENT_PAGE = $cp;
    $this->CURRENT_PICTURE = $this->getFirstPictureOfPage($this->CURRENT_PAGE);
  }


 /**
*  liefert den Dateinamen des ersten Bildes der Seite zur ÃÂÃÂÃÂÃÂ¼bergebenen Seiten-Nr.
*  ÃÂÃÂÃÂÃÂbergabeparameter = Seiten-Nr. (>= 1)
*/
  function getFirstPictureOfPage($page){
    $pic = "";
    $picnr = ($page - 1) * $this->MAXIMUM_LINES * $this->PICS_PER_LINE;

    return $picnr;
  }



/**
*  liefert ein Array zurÃÂÃÂÃÂÃÂ¼ck, welches dem Format fÃÂÃÂÃÂÃÂ¼r die COLNAMES
*  der Tabelle zum anzeigen der Bilder entspricht
*/
  function   getPictureTableColnamesArray(){
     $ret = array();
     for ($i=0;$i<$this->PICS_PER_LINE;$i++ ){
       array_push($ret, "");
     }
     return $ret;
  }






/**
* liefert das nÃÂÃÂÃÂÃÂ¤chste Bild im aktuellen Verzeichniss
*/
  function   getNextPicture (){
     if($this->CURRENT_PICTURE+1 < count($this->PICS)){
       $this->CURRENT_PICTURE++;
     } else {
       $this->CURRENT_PICTURE = 0;
     }

     return $this->PICS[$this->CURRENT_PICTURE];
  }


  function getImageByIndex($index){
	 if(count($this->PICS)<=$index){
		return "";
	 }
	 
     return $this->PICS[$index];	
  }

/**
* liefert das vorherige Bild im aktuellen Verzeichniss
*/
  function  getPreviousPicture(){
     if($this->CURRENT_PICTURE > 0){
       $this->CURRENT_PICTURE--;
     } else {
       $this->CURRENT_PICTURE = count($this->PICS)-1;
     }

     return $this->PICS[$this->CURRENT_PICTURE];
   }


/**
*  liefert die benÃÂÃÂÃÂÃÂ¶tigte Seitenanzahl um alle Bilder des aktuellen Verzeichnisses anzuzeigen
*/
  function getPageCount(){
    $picsPerPage = $this->PICS_PER_LINE * $this->MAXIMUM_LINES;
    return ceil(count($this->PICS) / $picsPerPage);
  }



/**
*  Zeigt (Wenn mehr als eine Seite notwendig) eine Seitennavigation an
*/
 function showPageNavigation(){
    $pn = $this->getPageNavigation();
    $pn->show();
 }
 
 function getPageNavigation(){
   if ($this->getPageCount() <= 1){
     return;
   }

   $maxPage = $this->getPageCount();

   $label = new Text("Seite: ");
   $label->setFontsize(2);
   $label->show();

   for ($i=1; $i <= $maxPage; $i++){
      $txt = new Text($i);
      //$txt = "<font color='" .$_SESSION['config']->COLORS['hover'] ."' >" .$txt ."</font>";
      
      if($this->CURRENT_PAGE == $i){
        $ft = new FontType();
        $ft->setBold(true);
        $ft->setColor($_SESSION['config']->COLORS['hover']);
		$txt->setFonttype($ft);
      } 
      
	  if($this->LINKPREFIX!="" && substr($this->LINKPREFIX,strlen($this->LINKPREFIX)-1,1)!="&" ){
			$this->LINKPREFIX = $this->LINKPREFIX."&";
	  }

	  $link = new Link($this->PARENT_PAGE ."?".$this->LINKPREFIX ."changeBbPage=".$i."&Current_BB_Path=" .substr($this->PATH, strlen($_SERVER['DOCUMENT_ROOT'])), $txt ,false);
     
	  $link->show();

      if($i < $maxPage){
        $label = new Text(", ");
        $label->setFontsize(2);
        $label->show();
      }
   }
 }




function showPicturelist(){
  $pl = $this->getPicturelist();
  $pl->show();
}


/**
*  Zeigt eine Tabellarische ÃÂÃÂÃÂÃÂbersicht der Bilder an
*  Hier werden die Zeilenanzahl und die Bilder-Pro Zeile
*  wie sie im Objekt definiert wurden als Konfiguration verwendet
*  ($this->PICS_PER_LINE   und    $this->MAXIMUM_LINES)
*/
  function getPicturelist(){
    if(count($this->PICS)<=0){
      return;
    }
    $picCounter = 0;
    $lineCounter = 1;
    $reachedFirstPicture = false;

    $picWidth = round((($this->WIDTH - 50) / $this->PICS_PER_LINE) - $this->PICS_PER_LINE*4 )  ;
    if ($picWidth<0 || $picWidth > $this->WIDTH){
      $picWidth = 150;
    }

    $picnr = ($this->CURRENT_PAGE - 1) * $this->MAXIMUM_LINES * $this->PICS_PER_LINE;
    $pCnt  = $this->MAXIMUM_LINES * $this->PICS_PER_LINE;

    $hiddenPath = new HiddenField("Current_BB_Path", substr($this->PATH, strlen($_SERVER['DOCUMENT_ROOT'])));

    $form = new Form($this->PARENT_PAGE, "haupt");
    $form->add($hiddenPath);

    $table = new Table($this->getPictureTableColnamesArray());
    $table->setAlign($this->getAlign());
    $table->setBorder(0);
    $row = $table->createRow();

    for($i=$picnr; $i < $picnr+$pCnt && $i < count($this->PICS); $i++){
      $pic = $this->PICS[$i];

      if(is_file($pic) && $lineCounter <= $this->MAXIMUM_LINES){

        $img = new Image(substr($pic, strlen($_SERVER['DOCUMENT_ROOT']).dirname($_SERVER['SCRIPT_NAME'])), -1, -1, $picWidth, 0, 0);
        $img->setToolTip(getCommentDialogForImage($img->getSource()));
		$img->setCommentsActive(true);
		checkForInsertBBComment();
        $img->setBorder(0);

        $linkStartPos = strlen($_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']));
        if(strlen(dirname($_SERVER['SCRIPT_NAME'])) <= 1 ){
          $linkStartPos = $linkStartPos - 1;
        }
        
        if($this->LINKPREFIX!="" && substr($this->LINKPREFIX,strlen($this->LINKPREFIX)-1,1)!="&" ){
			$this->LINKPREFIX = $this->LINKPREFIX."&";
		}
        
        $lnk = new Link($this->PARENT_PAGE ."?" .$this->LINKPREFIX ."showPic=".$i ."&Current_BB_Path=" .substr($this->PATH ,strlen($_SERVER['DOCUMENT_ROOT'])) , $img ,false);
		$lnkDirect = new Link(substr($this->PICS[$i], $linkStartPos), $img ,false, $this->CURRENT_PICTURE, "newPic");
       
       if($this->IMAGEDIRECTLINK){
	     $row->setAttribute($picCounter, $lnkDirect);
       } else {
		 $row->setAttribute($picCounter, $lnk);
	   }
	   
        // Neue Zeile starten
        if($picCounter+1 >= $this->PICS_PER_LINE){
          $picCounter = -1;
          $table->addRow($row);

          $lineCounter++;

          if($lineCounter <= $this->MAXIMUM_LINES){
            $row = $table->createRow();
          }
        }
        $picCounter++;
      }
    }

    if($picCounter != 0){
      $table->addRow($row);
    }

    $form->add($table);
    return $form;
  }


/**
*  Zeigt das aktuelle Bild an (incl nÃÂÃÂÃÂÃÂ¤chstes/voriges Bild-Navigation)
*/
  function showPicture(){
    $p = $this->getPicture();
    $p->show();
  }
  
  function getPicture(){
     $picHeight=$this->IMAGEHEIGHT;
    
     if ($picHeight <=0){
       $picHeight = 300;
     }
    
     $linkStartPos = strlen($_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']));

     if(strlen(dirname($_SERVER['SCRIPT_NAME'])) <= 1 ){
       $linkStartPos = $linkStartPos - 1;
     }

     $img = new Image(substr($this->PICS[$this->CURRENT_PICTURE], strlen($_SERVER['DOCUMENT_ROOT']).dirname($_SERVER['SCRIPT_NAME'])), -1, -1, 0, $picHeight , 0);
	 $img->setCommentsActive(true);
 
     $lnk = new Link(substr($this->PICS[$this->CURRENT_PICTURE], $linkStartPos), $img ,false, $this->CURRENT_PICTURE, "newPic");

     $lnk->show();

   // Navigation
     $form = new Form($this->PARENT_PAGE);
	 $aStr = "";
	 
	 if($this->LINKPREFIX!="" && strpos($this->LINKPREFIX, "=")>0){
			$this->LINKPREFIX = ereg_replace("&", "", $this->LINKPREFIX);
			$aStr = split("=", $this->LINKPREFIX);
	 }
	 
	 
     $hiddenPath = new HiddenField("Current_BB_Path", substr($this->PATH, strlen($_SERVER['DOCUMENT_ROOT'])));
     $hiddenPage = new HiddenField("changeBbPage", $this->CURRENT_PAGE);
     $hiddenPic = new HiddenField("showPic", $this->CURRENT_PICTURE);
     $hiddenPref = new HiddenField($aStr[0], $aStr[1]);
     
     $form->add($hiddenPath);
     $form->add($hiddenPage);
     $form->add($hiddenPic);
	 $form->add($hiddenPref);

     $btnNext = new Button("BB_Change_To_Next_Picture", "Nächstes Bild");
     $btnPrev = new Button("BB_Change_To_Prev_Picture", "Voriges Bild");

     $btnBackToList = new Button("BB_BackToList", "Zurück zur Übersicht");

     $spacer = new Text("<br>");
     $spacer->setFilter(false);

	 $form->add($spacer);
     $form->add($btnPrev);
     $form->add($btnBackToList);
     $form->add($btnNext);
     
     return $form;
  }




/**
*  Zeigt Unterordner des aktuellen Verzeichnisses als Navigations-Buttons an
*/
  function showFolderslist(){
    $fl = $this->getFolderlist();
    $fl->show();
  }
  
  function getFolderslist(){
    //alle Ordner holen
    $form = new Form($this->PARENT_PAGE);

    $hiddenPath = new HiddenField("Current_BB_Path", substr($this->PATH, strlen($_SERVER['DOCUMENT_ROOT'])));
    $form->add($hiddenPath);

    $folders = $this->FOLDER->getFolderArray();
    $btnText = "";

    if($this->PATH != $this->ROOTPATH && strlen($this->PATH)> strlen($this->ROOTPATH) ){
      $p = $this->FOLDER->getParentFolderPath();

      $btnBack = new Button("BB_Change_To_Parent_Folder", "ZurÃÂÃÂÃÂÃÂ¼ck zu: " .$this->FOLDER->getParentFolderName() );
      $form->add($btnBack);

	  $spacer = new Text("&nbsp;&nbsp;");
      $spacer->setFilter(false);
      $form->add($spacer);
    }

    foreach ($folders as $dir){
      $dirarray = explode("/", $dir);
      $btnText = $dirarray[count($dirarray)-1];

      $btn = new Button("BB_Change_Folder", $btnText);
      $form->add($btn);

    }

    return $form;
  }





/**
*  Zeigt Als erstes die Ordner-Navigation ( showFoldersList() ) an und darunter die Bilder-Liste ( showPicList() )
*  wenn angefordert, wird das aktuelle Bild einzeln angezeigt.
*  setzen des aktuellen Bildes erfolgt in changeHandler().
*/
  function show(){
    $this->handleChanges();
    
    $tbl = new Table(array(""));
    $tbl->setAlignments(array("center"));
    
    if($this->isShowTitle()){
      $title = new Title($this->FOLDER->NAME);
      $rTtl = $tbl->createRow();
      $rTtl->setAlign("center");
      $rTtl->setAttribute(0,$title);
      $tbl->addRow($rTtl);
    }

    $fl = $this->getFolderslist();
   
    $rFl = $tbl->createRow();
    $rFl->setAttribute(0,$fl);
    $tbl->addRow($rFl);

    if( isset($_REQUEST['showPic'])&& !isset($_REQUEST['BB_BackToList']) && strlen($_REQUEST['showPic'])>0 ){
      $p = $this->getPicture();
      $rP = $tbl->createRow();
      $rP->setAlign("center");
      $rP->setAttribute(0,$p);
      $tbl->addRow($rP);

    } else {
      $pn = $this->getPageNavigation();
      $pl = $this->getPicturelist();
      
      $rPn = $tbl->createRow();
      $rPn->setAlign("center");
      $rPn->setAttribute(0,$pn);
      $tbl->addRow($rPn);

      $rPl = $tbl->createRow();
      $rPl->setAlign("center");
      $rPl->setAttribute(0,$pl);
      $tbl->addRow($rPl);
    }
    $tbl->show();
  }

}
?>