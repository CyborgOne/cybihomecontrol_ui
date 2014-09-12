<?php
//FileNAME: Table.php


// Muss Spaltenbreiten bei create(columnNAMEs setzen) konfigurieren 
// und fÃÂÃÂÃÂÃÂ¼r jede Row bei createrow ÃÂÃÂÃÂÃÂ¼bergeben



class Table extends Object {
  var $BORDER;
  var $FONTTYPES;

  var $ROWS;       // Array of Objects welches die Objecte enthÃÂÃÂÃÂÃÂ¤lt 
  var $COLNAMES;   // Array welches die Namen der Spalten enthÃÂÃÂÃÂÃÂ¤lt 
  var $COLSIZES;
  var $ALIGNMENTS;

  var $ONCLICK;

  /**
  * $DESIGN
  * gibt an ob die Tabelle eine Design-Grafik im Hintergrund darstellen soll
  * Die Grafik besteht aus 9 Teilen. Um die Tabelle in jede Richtung "elastisch" zu halten.
  */
  var $DESIGNJN;
  var $TABLEDESIGN;
  var $DESIGNWIDTH;

  var $HEADER_ENABLED;
  
  var $BACKGROUNDCOLOR_CHANGE;

/**
 * @param $columnNames   array of Strings in dem die Spaltennamen angegeben werden
 */
  function Table($columnNames){
     $this->NAME = "Table";
     $this->XPOS = "";
     $this->YPOS = "";
     $this->WIDTH = "100%";
     $this->HEIGHT = 0;
     $this->BORDER = 0;
     $this->setPadding(0);
     $this->setSpacing(0);
     $this->COLNAMES = $columnNames;
     $this->COLSIZES = array();
     $this->ROWS = array();
     $this->FONTTYPES = array();
     $this->prepareFonts();
      

     $this->DESIGNJN = "N";
     $this->TABLEDESIGN = "default";
     
     $this->BACKGROUNDCOLOR_CHANGE = false;    
     
     $info = getimagesize($_SERVER['DOCUMENT_ROOT'] .dirname($_SERVER['SCRIPT_NAME']) ."/pics/table/" .$this->TABLEDESIGN ."_top_left_bg.png");
     $Breite_Quelle = $info[0];
     $Hoehe_Quelle  = $info[1];

     $this->DESIGNWIDTH = $Breite_Quelle;
     $this->DESIGNHEIGHT = $Hoehe_Quelle;

     $this->HEAD_ENABLED = true;
  }
  
  
  
  
  
  
  
  function setBackgroundColorChange($boolean){
      $this->BACKGROUNDCOLOR_CHANGE = $boolean;
  }
  
  function isBackgroundColorChange(){
      return $this->BACKGROUNDCOLOR_CHANGE;
  }
  
  function setOnClick($strng){
      $this->ONCLICK = $strng;
  }
  
  function getOnClick(){
      return $this->ONCLICK;
  }
  
  
  
  /**
  * setDesignJN($jn) aktiviert oder deaktiviert die Hintergrund-Design-Anzeige.
  * Als ÃÂÃÂÃÂÃÂbergabeparameter wird entweder "J" oder "N" erwartet.  DEFAULT = "N"
  */
  function setDesignJN($jn){
    if($jn=="J") {
      $this->DESIGNJN = "J";
    } else {
      $this->DESIGNJN = "N";
    }
  }

  function setHeadEnabled($b){
     if($b == true){
       $this->HEADER_ENABLED = true;
     } else {
       $this->HEADER_ENABLED = false;
     }
  }


  function prepareFonts(){
     //legt Standard-Fonttypes an
     $fts = array();

     for( $i=0; $i<count($this->COLNAMES); $i++ ){
	$fts[$i] = new Fonttype();
     }
     
     $this->FONTTYPES = $fts;
  }
  

  function setFonttypes($f){
    $this->FONTTYPES = $f;
    $ri = 0;
    //Allen Rows neuen Parameter mitgeben
    foreach($this->ROWS as $row){  
      $row->setFonttypes($this->FONTTYPES);      
      $ri++;
    }
  }


  function createRow(){
    $r = new Row($this->COLNAMES);
    $r->setFontTypes($this->FONTTYPES);
    $r->setColsizes($this->COLSIZES);
    $r->setAlignments($this->ALIGNMENTS);
    $r->setPadding($this->getPadding());
    $r->setSpacing($this->getSpacing());

  	 if(isset($this->ALIGN)){  
	   $r->setAlign($this->ALIGN);
  	 }

    return $r;
  }


  function addRow($row){
     $this->ROWS[count($this->ROWS)] = $row;
  }

  function addSpacer($height=1, $spacing=5){
     $s = $this->createRow();
     $s->setHeight($spacing);
     $l = new Line();
     $l->setHeight($height);
     $s->setAttribute(0,$l);
     $s->setSpawnAll(true);
     
     $this->addRow($s);
  }


  function setColSizes($array){
    foreach($this->ROWS as $r) {
     $r->COLSIZES= $array;
	}  
    $this->COLSIZES = $array;
  }


  function setAlignments($array){
    foreach($this->ROWS as $r) {
     $r->ALIGNMENTS = $array;
	}  
    $this->ALIGNMENTS = $array;
  }



  function getRow($index){
  	if($index > $this->getRowcount()){
		return null;
	}
	return $this->ROWS[$index];
  }

  function getRowcount(){
	return count($this->ROWS);
  }

  function setRow($index, $row){
     $this->ROWS[$index] = $row;
  }

 function getTabledesign(){
	return $this->TABLEDESIGN;
  }

  function setTabledesign($index){
     $this->TABLEDESIGN = $index;
  }

  function changeCell($x, $y, $text){
    $this->rows[$y]->setAttribute($x, $text);
  }


  /**
  *  Zeigt die gesamte Tabelle an (wenn eingestellt mit Design)
  */
  function show(){
    if($this->DESIGNJN == "J"){
      echo "
	  <table cellpadding='" .$this->getPadding() ."' cellspacing='" .$this->getSpacing() ."' " .$this->getClassTag();
      
      $this->showStyles();
      

      echo ">
          <tr>
            <td background='pics/table/" .$this->TABLEDESIGN ."_top_left_bg.png' height='" .$this->DESIGNHEIGHT ."' width='" .$this->DESIGNWIDTH ."'>
            </td>
            <td background='pics/table/" .$this->TABLEDESIGN ."_top_center_bg.png' height='" .$this->DESIGNHEIGHT ."'>
            </td>
            <td background='pics/table/" .$this->TABLEDESIGN ."_top_right_bg.png' height='" .$this->DESIGNHEIGHT ."' width='" .$this->DESIGNHEIGHT ."'>
            </td>
          </tr>

          <tr>
            <td background='pics/table/" .$this->TABLEDESIGN ."_middle_left_bg.png' width='" .$this->DESIGNWIDTH ."'>
            </td>
            <td background='pics/table/" .$this->TABLEDESIGN ."_middle_center_bg.png'>
      ";

      $this->showTable();
      
      echo "
            </td>
            <td background='pics/table/" .$this->TABLEDESIGN ."_middle_right_bg.png' width='" .$this->DESIGNWIDTH ."'>
            </td>
          </tr>

          <tr>
            <td background='pics/table/" .$this->TABLEDESIGN ."_bottom_left_bg.png' height='" .$this->DESIGNHEIGHT ."' width='" .$this->DESIGNWIDTH ."'>
            </td>
            <td background='pics/table/" .$this->TABLEDESIGN ."_bottom_center_bg.png' height='$this->DESIGNHEIGHT'>
            </td>
            <td background='pics/table/" .$this->TABLEDESIGN ."_bottom_right_bg.png' height='$this->DESIGNHEIGHT' width='$this->DESIGNWIDTH'>
            </td>
          </tr>
        </table>
      ";
    } else {
      $this->showTable();
    }
  }
  
  
  /**
  *  Zeigt den Inhalt der Tabelle (wenn eingestellt mit Border) an (Ohne Design)
  */
  function showTable(){
      echo "
	          <table 
		   ";

      if($this->WIDTH > 0){
        $w = $this->WIDTH;
        
        if($this->DESIGNJN == "J"){
          $w = $w - ($this->DESIGNWIDTH*2);
        }
        $w = $w;
        echo " WIDTH='" .$w ."' ";
      }

     
  
      if(strlen($this->HEIGHT) > 0){
          echo " HEIGHT='" .$this->HEIGHT ."' ";
      }



      if($this->BORDER >= 0){
	      echo " BORDER=" .$this->BORDER ." ";
      }

    	if($this->getPadding() >= 0){
	      echo " cellpadding=" .$this->getPadding() ." ";
      }

    	if($this->getSpacing() >= 0){
	      echo " cellspacing=" .$this->getSpacing() ." ";
      }
      
      echo $this->getToolTipTag();

      if($this->DESIGNJN != "J"){
        $this->showStyles();
      }


      if(strlen($this->ONCLICK)>0){
	 echo " onclick=\"" .$this->ONCLICK ."\" ";
      }
 
    	echo ">
		";
      //---------------------------------------------------
      // HEADER ANZEIGEN
      //---------------------------------------------------
  	  if(count($this->COLNAMES)>=0 && $this->HEADER_ENABLED && isset($this->COLNAMES[0]) && strlen($this->COLNAMES[0])>0){
         echo "
				 <tr>
			  ";
         for ( $i=0;  $i < count($this->COLNAMES);  $i++ ){
	          echo "
			         <th ";
   

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

		if(isset($this->COLSIZES[$i]) && strlen($this->COLSIZES[$i])>0){
		  echo " width='" .$this->COLSIZES[$i] ."' ";
        }

 			  
			  echo " >" .$this->COLNAMES[$i] ."</th>";

         }
	       echo "
		   		    </tr>
           ";
      }
      //---------------------------------------------------
      
      
      $bgCtr = 1;
      
      //---------------------------------------------------
      // ROWS ANZEIGEN
      //---------------------------------------------------
      for ( $i=0;  $i<count($this->ROWS);  $i++ ){
         if(method_exists($this->ROWS[$i], "show")){
	         	       
	       if(strlen($this->getVAlign()) > 0 ){
			 $this->ROWS[$i]->setVAlign($this->getVAlign());
		   }
	       
		   if(strlen($this->getAlign()) > 0 ){
			 $this->ROWS[$i]->setAlign($this->getAlign());
		   }
           
           if($this->BACKGROUNDCOLOR_CHANGE){
                $this->ROWS[$i]->setBackgroundColor($_SESSION['config']->COLORS['Tabelle_Hintergrund_'.$bgCtr]);
                
                if($bgCtr == 1){
                 $bgCtr = 2;
                } else {
                 $bgCtr = 1;
                }
           }
	       
      	    $this->ROWS[$i]->show();
         }
      }

      //---------------------------------------------------

	    echo "
		       </table>
		     ";
  }

}




/* --------------------------------------------------
  ABGELEITETE KLASSE (anderer Konstruktor/Parameter)
-------------------------------------------------- */

class TableByArray extends Table {
  function TableByArray($array){
    foreach ($array as $rowArray){
       // Row aus array erzeugen
       $newRow = $this->createRow();
       $c=0;
       
	   foreach ($rowArray as $cell){
	     $newRow->setAttribute($c, $cell);
         $c++;
       }
       
       $this->addRow($newRow);
    }
  }
}



/* --------------------------------------------------
  ABGELEITETE KLASSE (anderer Konstruktor/Parameter)
-------------------------------------------------- */

class TableBySql extends Table {
  var $DBCONNECT;
  var $SQL;  

  function TableBySql($sql){
     $this->NAME = "Table";
     $this->XPOS = "";
     $this->YPOS = "";
     $this->WIDTH = "100%";
     $this->HEIGHT = 0;
     $this->BORDER = 0;
     $this->setPadding(0);
     $this->setSpacing(0);
     $this->COLSIZES = array();
     $this->ROWS = array();
     $this->FONTTYPES = array();

     $this->DESIGNJN = "N";
     $this->TABLEDESIGN = "default";
     $this->DESIGNWIDTH = 20;
     $this->DESIGNHEIGHT = 20;

     $this->HEAD_ENABLED = true;

     $this->DBCONNECT = $_SESSION['config']->DBCONNECT;
     $this->SQL = $sql;

     $result = $this->DBCONNECT->executeQuery($this->SQL);
     $menge = mysql_num_fields ( $result );

     // SpaltenNamen  holen
     for ( $x = 0; $x < $menge; $x++ ){
       $cN[$x] = mysql_field_name( $result, $x );
     }	

     $this->COLNAMES = $cN;
     $this->prepareFonts();

     //Select-Ergebniss in Rows *packen*
     while($rowArray = @mysql_fetch_array($result)){
       $newRow = $this->createRow();
       
       if(strlen($this->getVAlign()) > 0 ){
		 $newRow->setVAlign($this->getVAlign());
	   }
       
	   if(strlen($this->getAlign()) > 0 ){
		 $newRow->setAlign($this->getAlign());
	   }
       
	   for($i=0; $i<count($this->COLNAMES); $i++){
	   	 if (mysql_field_type($result, $i) == "blob"){
	   	   $txa = new TextArea("txt", $rowArray[$i]);
	   	   $txa->setWidth(40);
	   	   $txa->setHeight(3);
	   	   $txa->setReadOnly(true);			

	       $newRow->setAttribute($i, $txa);

	     } else {
           $newRow->setAttribute($i, new Text($rowArray[$i]));

         }
       }
       
       $this->addRow($newRow);
    }

  }

}