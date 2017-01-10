<?php
//FileNAME: Gb.php
 /*
`id` int( 11 )
`Title` varchar( 50 )
`Text` text
`Autor` varchar( 30 )
`Date` varchar( 30 )
`email` varchar( 50 )
*/


class News  extends Object {
  var $CONFIG;
  var $NewsTABLE;
  var $DBCONNECT;
  var $News_CONFIG;

  function News(& $conf){
      $this->CONFIG = &$conf;
      $this->DBCONNECT = &$this->CONFIG->DBCONNECT;
      $this->NewsTABLE = new NewsTable($this->DBCONNECT);
      $this->News_CONFIG = $this->CONFIG->News_CONFIG;
      $this->setWidth("99%");
  }

  
  function getRowsToShow(){
     $startRow = 0;
     $endRow   = $this->NewsTABLE->getRowCount();
     
     
     $rowArray = array();
 
     if(! $this->News_CONFIG['showAll'] && $this->News_CONFIG['showCount']<$endRow){
		$endRow = $this->News_CONFIG['showCount'];
     }

     $rowArray = $this->NewsTABLE->getRowsVonBis($startRow ,$endRow);

     return $rowArray;
  }

  
  
  /**
   * Zeigt die Eingabemaske an, wenn isset($_REQUEST['dbTableNew'])
   * ansonsten gibt die Methode false zurÃÂÃÂÃÂÃÂ¼ck.
   * 
   * return boolean
   */
  function showInsertMaskIfNeeded(){
	 
	 if(isset($_REQUEST['dbTableNew']) ) {      
		$insert = new DbTable($_SESSION['config']->DBCONNECT, 
		                      "news", 
							  array( "Title", "Text", "Datum", "Autor"), 
							  "Titel, Text, Datum, Autor",
							  "Datum='" .date("Y-m-d") ."', Autor = '" .$_SESSION['config']->CURRENTUSER->VORNAME 
							                                      ." " .$_SESSION['config']->CURRENTUSER->NACHNAME ."' ",
							  "Datum DESC");
	    $insert->setBorder(0);
	    $insert->showInsertMask();
	  
	    
	    return true;
	  
	  } else {
	  	
	    return false;	
	  }
	
  }
  




  function show(){
  	//Wenn notwendig Eingabemaske, ansonsten Standard-Anzeige
	if( !$this->showInsertMaskIfNeeded() ){
  		
	    $rowsToShow = $this->getRowsToShow();
	
	//Tabelle fÃÂÃÂÃÂÃÂ¼r gesamte NEWS
	    $tbl = new Table(array("News"));
	    $tbl->setHeadEnabled(false);
	    $tbl->setBorder(0);
	    
	    foreach($rowsToShow as $row){
		 $tt = new Text($row['Text']);
		 $tt->setFilter(false);

	        $ta = new Text($row['Autor']);
	        $tblxTitle = new Table(array("",""));
	        $tblxTitle->setColSizes(array("50"));
	        $tblxTitle->setAlign("left");
	        
			$rxtitle = $tblxTitle->createRow();
	        $t0 = new Title(getFormatedDate($row['Datum'], "standard").":");
	        $t0->setWidth(20);
			$rxtitle->setAttribute(0,$t0);
	        $t1 = new Title($row['Title']);
			$rxtitle->setAttribute(1,$t1);
	        $rxtitle->setAlign("left");
	        $tblxTitle->addRow($rxtitle);
	        
	        
	      //Tabelle fÃÂÃÂÃÂÃÂ¼r den jeweiligen Eintrag
	        $tblx = new Table(array(""));
	        
	        if(strlen($row['pic'])>0){
			   $tblx = new Table(array("", ""));	
			   $tblx->setColSizes(array("180"));
			}
		    $tblx->setBorder($this->CONFIG->GB_CONFIG['border']);
		    $tblx->setWidth("100%");
		 
		 
	      //TEXT
	        $rx = $tblx->createRow();
	
	        if(strlen($row['pic'])>0 && !(isset( $_REQUEST['removeNewsPicId']) && $_REQUEST['removeNewsPicId']==$row['id'])){
	        	$img = new Image($row['pic']);
				$img->setWidth(175);
				
			    $rx->setAttribute(0, $img);
				$rx->setAttribute(1,$tt);
		    } else {
				$rx->setAttribute(0,$tt);
			}
		    
			$rx->setAlign("left");
		    $tblx->addRow($rx);
		
			$f = new FontType();
			$rx->setFonttypes(array(0=>$f));
	

			$f = new FontType();
		    $f->setFontsize(1);
		    $f->setItalic(false);
			$rx->setFonttypes(array(0=>$f));
		
		
		    $r = $tbl->createRow();	
		    $r->setAttribute(0,$tblxTitle);
			$tbl->addRow($r);
		    $r1 = $tbl->createRow();	
		    $r1->setAttribute(0,$tblx);
			$tbl->addRow($r1);
  			
			
			$ytElem = new YouTubeElement($row['yt_video'], "news", "yt_video", $row['id']);
		    $r1 = $tbl->createRow();	
		    $r1->setAttribute(0,$ytElem);
			$tbl->addRow($r1);


			
		
			$tbl->addSpacer(0,7);   
		
		    if( $_SESSION['config']->CURRENTUSER->STATUS=="admin" || $_SESSION['config']->CURRENTUSER->STATUS=="user"){
			    $r2 = $tbl->createRow();
			    $xDv = new Div(); 
                
			    $xDv->setWidth(450);
			    $xDv->setHeight(40);
			    
                $txChngePic = new Text("Bild Hochladen/ÃÂÃÂÃÂÃÂndern<br>");
                $txChngePic->setFilter(false);
                
				$newPicLink = new Link("?popupRunLink=imageUploaderPopup&targetPath=pics/news&DbInsertTable=news&DbInsertCol=pic&DbInsertId="
						                                                                        .$row['id']."&stayAlive=ok", $txChngePic);
                                                                                                
                $txRemPic = new Text("Bild Entfernen<br>");
                $txRemPic->setFilter(false);

				$removePicLink = new Link("?removeNewsPicId=".$row['id']."", $txRemPic);

			    $spTx = new Text("&nbsp;&nbsp;&nbsp;");
			    $spTx->setFilter(false);
							    
				$xDv->add($newPicLink);		
                
                if( strlen($row['pic'])>0 ){
                    if(isset( $_REQUEST['removeNewsPicId']) && $_REQUEST['removeNewsPicId']==$row['id'] ){
                       $sql = "UPDATE news SET pic = null WHERE id = " .$_REQUEST['removeNewsPicId'];
                       $_SESSION['config']->DBCONNECT->executeQuery($sql);
                    } else {
    				  $xDv->add($removePicLink);
                    }
                }
				$newPicLink->setPopup(true);
			    $r2->setAttribute(0, $xDv );
				$tbl->addRow($r2);
				
			
		    }
		    
	        $tbl->addSpacer(0,35);    
	    }
	
	
	    if($_SESSION['config']->CURRENTUSER->STATUS=="admin" || $_SESSION['config']->CURRENTUSER->STATUS=="user"){
	
			// Neuer Eintrag- BUTTON   
			//--------------------------------   
			$insertButton = new Button('dbTableNew','News eintragen');
			
			$form = new Form($_SERVER['SCRIPT_NAME']);
			$form->add($insertButton);
			$form->show();
        }


		// Tabelle anzeigen
		//--------------------------------   
	    $tbl->show();
	    

	    if($_SESSION['config']->CURRENTUSER->STATUS=="admin" || $_SESSION['config']->CURRENTUSER->STATUS=="user" && $tbl->getRowcount()>2){
	
			// Neuer Eintrag- BUTTON   
			//--------------------------------   
			$insertButton = new Button('dbTableNew','News eintragen');
			
			$form = new Form($_SERVER['SCRIPT_NAME']);
			$form->add($insertButton);
			$form->show();
        }

	}
  }



}