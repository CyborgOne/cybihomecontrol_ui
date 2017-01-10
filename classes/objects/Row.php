<?php
// FileNAME: Row.php


// Muss ZeilenhÃÂÃÂÃÂÃÂ¶he als zusÃÂÃÂÃÂÃÂ¤tzlichen Parameter erhalten



class Row extends Object {
  var $ROW;        // Array welches die Zeile darstellt
  var $COLNAMES;   // Array welches die Namen der Spalten enthÃÂÃÂÃÂÃÂ¤lt 
  var $FONTTYPES;  // Array welches die Schriftformatierung fÃÂÃÂÃÂÃÂ¼r einzelne Spalten vorgibt
  var $BACKGROUNDS;  // Array welches die Hintergrund-Dateinamen fÃÂÃÂÃÂÃÂ¼r einzelne Zelle vorgibt
  var $COLSIZES;
  var $ALIGNMENTS;
  var $HEADER;     // true/false  => th oder td
  var $PADDING;
  var $SPACING;
  var $SPAN;     //2-dimensionales array in dem alle Spans hinterlegt werden.  
  public $STYLES; // Array welches fÃÂÃÂÃÂÃÂ¼r jede Spalte eigene Styles bereit hÃÂÃÂÃÂÃÂ¤lt (optional)
  
  private $SPAWNALL; //Boolean der angibt ob alle Spalten verbunden werden sollen


  function Row($colNamesArray){
    $this->setFonttype(new FontType());

    $this->ROW = array();
    $this->BACKGROUNDS = array();
    $this->setColNames($colNamesArray);
    $this->HEADER = false;

    $this->prepareFonts();
    $this->PADDING = 0;
    $this->SPACING = 0;
    $this->setStyle("background-repeat", "repeat-all");
    $this->SPAN = array(); 
    $this->STYLEATTRS = array();
  }
  
  
  function addSpan($newSpanVon,$newSpanBis){
  	$tAr = array($newSpanVon,$newSpanBis);
  	
	$this->SPAN[count($this->SPAN)] = $tAr;
  }
  
  
  function getSpans(){
	return $this->SPAN;
  }
  
  
  
  
  
  function addStyles( $arrayStyles ){
	$this->STYLES = $arrayStyles;
  }
  
  
  function getStyles(){
	return $this->STYLES;
  }
  
  
  
  
  function setBackgrounds($b){
    $this->BACKGROUNDS = $b;	
  }

  
  function prepareFonts(){
     //legt Standard-Fonttypes an
     $fts = array();

     for( $i=0; $i<count($this->COLNAMES); $i++ ){
    	$fts[$i] = new Fonttype();
     }
     
     $this->FONTTYPES = $fts;
  }
  
  function setSpawnAll($bool){
    if($bool){
	  $this->SPAWNALL = true;
	} else {
	  $this->SPAWNALL = false;
	}
  }

  function setColNames($nameArray){
     $this->COLNAMES= $nameArray;
  }
  function getColCount(){
  	 if($this->SPAWNALL){
	   return 1;	
     }
     return count($this->COLNAMES) ;
  }

  function setColSizes($array){
     $this->COLSIZES = $array;
  }


  function setAlignments($array){
     $this->ALIGNMENTS = $array;
  }

  function setAlign($algn){
     $this->setStyle("horizontal-align", $this->ALIGN);
     $this->ALIGN = $algn;
     $this->ALIGNMENTS = null;
  }

  function setHeader($bool){
     $this->HEADER = $bool;
  }


  function getFontsize($colIndex){
     return $this->FONTTYPES[$colIndex]->FONTSIZE;
  }




  function setFonttypes($f){
    $this->FONTTYPES = $f;
    $counter = 0;

    foreach($this->ROW as $OBJ){
      if(isset($this->FONTTYPES[$counter])){	
        $OBJ->setFonttype($this->FONTTYPES[$counter]);
      }else {
		$OBJ->setFonttype($this->getFonttype());
	  }
	  
      $counter++;
    }   
  }

  function getColNames(){
     return $this->COLNAMES;
  }

  function getAttribute($colIndex){
  	if(count($this->ROW)>$colIndex){
      return $this->ROW[$colIndex];
    }
  }

  function setAttribute($colIndex, $obj){
     if($this->getColCount()>$colIndex){
       //Art der Verarbeitung auswÃÂÃÂÃÂÃÂ¤hlen

       

       if(is_object($obj) && method_exists($obj, "setfonttype") ){
       	 
         if($this->FONTTYPES[$colIndex] != null  ){
		   if($this->FONTTYPES[$colIndex]->getColor() != null || $this->FONTTYPES[$colIndex]->getFontsize() != null ){
		     $obj->setFonttype($this->FONTTYPES[$colIndex]);
		   }
		 }
         
		 $this->ROW[$colIndex] = $obj;
       } else {
         $t = new Text($obj);
         $t->setFonttype($this->FONTTYPES[$colIndex]);
         $this->ROW[$colIndex] = $t;
       }
     }
  }

  function getNamedAttribute($colName){
    if($colName=="rowid" && isset($this->ROW[count($this->ROW)-1])){
		return $this->ROW[count($this->ROW)-1];
	}
    for ( $i=0;  $i<count($this->ROW);  $i++ ){
      if($this->COLNAMES[$i]==$colName){
        return $this->ROW[$i];
      }
    }
  }
  
  function getText($colIndex){
    $ret1 = $this->getAttribute($colIndex);
    
    if($ret1==null || $ret1==""){
      $ret1 = '-';
    }

    return $ret1;
  }

  function setNamedAttribute($colName, $text){
    for ( $i=0;  $i<count($this->ROW);  $i++ ){
      if($this->COLNAMES[$i]==$colName){
        $this->ROW[$i] = $text;
      }
    }
  }


  function setPadding($p){
    $this->PADDING = $p;
  }

  function setSpacing($s){
    $this->SPACING = $s;
  }




  function getSpanForColIndex($index){
	foreach($this->SPAN as $span){
      if($span[0]==$index){
      	return ($span[1]-$span[0]+1);
      }
	}
  }



  function isSpanForColIndex($index){
	foreach($this->SPAN as $span){
		if($span[0]==$index){
			return true;
		}
	}
	return false;
  }





  function isColIndexBetweenSpan($index){
	foreach($this->SPAN as $span){
		if($span[0]<=$index && $span[1]>=$index){
			return true;
		}
	}
	return false;
  }


  function getSpanSizeForColIndex($index){
	$ret = 0;
	
	foreach($this->SPAN as $span){
		if($span[0]<$index && $span[1]>=$index){
			for ($xi=$span[0];$xi<=$span;$xi++){
				if(isset($this->COLSIZES[$xi]) && $this->COLSIZES[$xi]>0){
				   $ret = $ret + $this->COLSIZES[$xi];	
				}
			}
		}
	}
	return $ret;
  }







  function show(){
    echo "
	      <tr";

    if(isset($this->HEIGHT) && $this->HEIGHT>0){
        if( strpos($this->HEIGHT,"%")<0 ){
            echo " height='" .round($this->HEIGHT) ."' ";
        } else {
//            $this->setStyle("height", $this->HEIGHT);
            echo " height='" .$this->HEIGHT ."' ";
        }
        if(strlen($this->getAlign())>0){
            " align='".$this->getAlign()."' ";
        }
    }
    
    echo $this->showStyles();
    
    echo ">";



    // ------------------------------------------------------------------------------------
    // Wenn nur eine Grafik fÃÂÃÂÃÂÃÂ¼r BG hinterlegt ist, 
    // Aber mehrere Spalten vorhanden sind, 
    // eine Tabelle hinter die mehreren spalten legen um durchgÃÂÃÂÃÂÃÂ¤ngigen BG zu ermÃÂÃÂÃÂÃÂ¶glichen
    // ------------------------------------------------------------------------------------
    if ( ( count($this->BACKGROUNDS)==1 && (count($this->getColNames())>1 && !$this->SPAWNALL ) ) ) {	
        if(isset($this->BACKGROUNDS[0]) && strlen($this->BACKGROUNDS[0])>0){

            echo " <td  style='horizontal-align: left; border: 0px solid ; background-color: #a59181; '>
                     <table height='100%' cellpadding=0 cellspacing=0 border=0 style='horizontal-align: left; border: 0px solid ; background-color: #a59181; background-image: url(".$this->BACKGROUNDS[0].")  '><tr>
		     
            ";

        }
    }





    for ( $i=0;  $i<(count($this->getColNames()));  $i++ ){
     if( !($this->SPAWNALL && $i > 0)){
	  if(! $this->isColIndexBetweenSpan($i) || $this->isSpanForColIndex($i)){ 

       if($this->HEADER==true){
          echo "
		       <th " .$this->getClassTag();
        } else {
          echo "
		        <td " .$this->getClassTag();
        }
         
        if($this->SPAWNALL){
			echo " colspan='".count($this->COLNAMES) ."' ";

		} else {
          if($this->isSpanForColIndex($i)){  
		  	  echo " colspan='".$this->getSpanForColIndex($i) ."' ";
          }
        }
        
        if(count($this->ALIGNMENTS)>0){
          if(  isset($this->ALIGNMENTS[$i]) && strlen($this->ALIGNMENTS[$i])>0  ){
			 echo " align='".$this->ALIGNMENTS[$i]."' ";
		  }
        } else {
		  if(isset($this->ALIGN) && strlen($this->ALIGN)>0){
			echo " align='".$this->ALIGN."' ";
		  } else {
			echo " align='left' ";
		  }
		}
	

        if( strlen($this->VALIGN)>0){
	    echo " valign='".$this->VALIGN."' ";
	}		

	if (! ( count($this->BACKGROUNDS)==1 && (count($this->getColNames())>1 && !$this->SPAWNALL ) ) ) {	

            if(isset($this->BACKGROUNDS[$i]) && strlen($this->BACKGROUNDS[$i])>0){

                if($this->STYLES!=null && count($this->STYLES)>0 && $this->STYLES[$i] instanceof Style ){
                    $this->STYLES[$i]->setStyle("background-image", "url(".$this->BACKGROUNDS[$i].")");
                } else {
	            $this->setStyle("background-image", "url(".$this->BACKGROUNDS[$i].")");
                }
            }
        } else {
            $this->setStyle("background-image", null);	
        }


	if(!$this->SPAWNALL && !$this->isSpanForColIndex($i) && isset($this->COLSIZES[$i]) && $this->COLSIZES[$i]>0 && !$this->isColIndexBetweenSpan($i)){
		  echo " width='" .round($this->COLSIZES[$i]) ."' ";
        } else if($this->isColIndexBetweenSpan($i)) {
		  echo " width='" .$this->getSpanSizeForColIndex($i) ."' ";	
		}

        echo $this->getToolTipTag();

        if($this->STYLES!=null && count($this->STYLES)>0 && $this->STYLES[$i] instanceof Style ){
            $this->STYLES[$i]->showStyles();
        } else {
            $this->showStyles();
        }
        
        echo ">";

        if( method_exists($this->getAttribute($i),"show") ){
          $o = $this->getAttribute($i);

		  $o->show();
        } 

        if($this->HEADER==true){
          echo "</th>
		  ";
        } else {
          echo "</td>
		  ";
        }
      }
     }
    }


    // ------------------------------------------------------------------------------------
    // Wenn nur eine Grafik fÃÂÃÂÃÂÃÂ¼r BG hinterlegt ist, 
    // Aber mehrere Spalten vorhanden sind, 
    // eine Tabelle hinter die mehreren spalten legen um durchgÃÂÃÂÃÂÃÂ¤ngigen BG zu ermÃÂÃÂÃÂÃÂ¶glichen
    // ------------------------------------------------------------------------------------
    if ( ( count($this->BACKGROUNDS)==1 && (count($this->getColNames())>1 && !$this->SPAWNALL ) ) ) {	
        if(isset($this->BACKGROUNDS[0]) && strlen($this->BACKGROUNDS[0])>0){

            echo " 
                     </tr></table>
                     </td>
            ";

        }
    }



    echo "</tr>
	";
  }



}

?>