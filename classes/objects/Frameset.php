<?php
//FileNAME: Frameset.php

class Frameset extends Object {
  var $FRAMES;  //Object welches die einzelnen Frames bzw das noframe-element enthÃÂÃÂÃÂÃÂ¤lt
  var $ROWORCOL;  // mÃÂÃÂÃÂÃÂ¶gliche Werte:  r, c  entscheidet ob das Frameset Rows oder Columns enthÃÂÃÂÃÂÃÂ¤lt
  var $HEIGHT;

  function Frameset(){
    $this->FRAMES = array();
    $this->ROWORCOL = "r";
  }


  function setHeight($r){
    $this->HEIGHT = $r;
  }

  function getHeight($r){
    return $this->HEIGHT;
  }


  function setRowOrCol($r){
    $this->ROWORCOL = $r;
  }


  function addFrame($f){
    $this->FRAMES[count($this->FRAMES)] = $f;
  }


  function getTableWidth(){
    $ret = "";

    foreach ($this->FRAMES as $frame){
      if(method_exists($frame, "getsize") && $frame->getSize()!= '*'){
        $ret = $ret + $frame->getSize();
      } else {
        if(method_exists($frame, "getsize") && $frame->getSize() == '*'){       
 	   $ret = '*';
 	   break;
        }
      }
    }

    return $ret;
  }



  function getSizes(){
    //liefert einen String mit den HÃÂÃÂÃÂÃÂ¶hen- bzw Breiten-angaben der Frames (komma-getrennt)

    $ret = "";

    foreach ($this->FRAMES as $frame){
      if(method_exists($frame, "getsize")){
        if(strlen($ret)>0){
  	   $ret .= ", ";
        }
        $ret .= $frame->getSize();
      }
    }

    return $ret;
  }



  function setSize($s){
    $this->SIZE = $s;
  }



  function  show(){
    if($this->ROWORCOL == "r"){
      echo "
        <frameset rows='" .$this->getSizes() ."'>
      ";

      //----------------------------
      // Frames anzeigen
      //----------------------------
      foreach($this->FRAMES as $frame){
        if(method_exists($frame, "getsize")){
  	   $frame->show();
        }
      }
      //----------------------------
      echo "        
        </frameset>
      ";

    } else {

      echo "
        <frameset cols='" .$this->getSizes() ."' frameborder='0'>
      ";
      //----------------------------
      // Frames anzeigen
      //----------------------------
      foreach($this->FRAMES as $frame){
        if(method_exists($frame, "getsize")){
  	   $frame->show();
        }
      }
      //----------------------------

      echo "        
        </frameset>
      ";
    }
  }



  function showAsTableCenter($width, $height){
    echo "
          <table border='0'>
            <tr height='*'>
              <td width='*'>
              </td>
              <td width='" .$width ."'>
              </td>
              <td width='*'>
              </td>
            </tr>

            <tr height='" .$height ."'>
              <td>
              </td>
              <td>
    ";

    $this->showAsTable();

    echo "
              </td>
              <td>
              </td>
            </tr>

            <tr height='*'>
              <td>
              </td>
              <td>
              </td>
              <td>
              </td>
            </tr>
    ";
  }


  
  function  showAsTable(){
      echo "
        <table width = '" .$this->getTableWidth() ."'> 
      ";

      if($this->ROWORCOL == "c"){
        echo "<tr ";
        
        
        echo "> 
        ";
      }
      //----------------------------
      // Frames als Zeilen anzeigen
      //----------------------------
      foreach($this->FRAMES as $frame){
        if($this->ROWORCOL == "r"){
          if(method_exists($frame, "getsize")){
  	     $frame->showAsRow();
          }
        } else {
          if(method_exists($frame, "getsize")){
  	     $frame->showAsCol();
          }
        }
      }
      //----------------------------

      if($this->ROWORCOL == "c"){
        echo "</tr> 
        ";
      }

      echo "        
        </table>
      ";

  }

}



//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
//          SUB-CLASS:                    Frame
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------

class Frame extends Object {
  var $SOURCE;  
  var $NAME;
  var $SIZE;
  
  function Frame($name, $source){
    $this->NAME = $name;
    $this->SOURCE = $source;
    $this->SIZE = "*";
  }


  function setSize($s){
    $this->SIZE = $s;
  }

  function getSize(){
    return $this->SIZE;
  }


  function  show(){
    echo "
          <frame src='" .$this->SOURCE ."' name='" .$this->NAME ."'>    
    ";
  }


  function  showAsRow(){
    echo "
          <tr>
            <td ";
    if($this->getSize() > 0 && $this->getSize() != "*"){
      echo " width='" .$this->getSize() ."' ";
    }
    echo ">
    ";

    include($this->SOURCE);

    echo "
            </td>
          </tr> 
    ";
  }


  function  showAsCol(){
    echo "<td ";
    if($this->getSize() > 0 && $this->getSize() != "*"){
      echo " width='" .$this->getSize() ."' ";
    }
    echo ">
    ";

    include($this->SOURCE);

    echo "
            </td>
     ";
  }


  
}




//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
//          SUB-CLASS:                    NoFrame
//
// Wenn Sie XHTML-Standard-konform arbeiten, 
// muss das SOURCE-Element zwingend ein body-Element enthalten! 
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------


class noFrame extends Object {
  var $SOURCE;  
  var $SIZE;

  function NoFrame($source){
    $this->SOURCE = $source;
    $this->SIZE = "*";
  }

  function setSize($s){
    $this->SIZE = $s;
  }

  function getSize(){
    return $this->SIZE;
  }

  function  show(){
    echo "
          <noframes>
    ";

    include($this->SOURCE);    

    echo "
          </noframes>
    ";
  }


  
}
