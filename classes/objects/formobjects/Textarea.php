<?php
//FileNAME: Textarea.php

class Textarea extends Object {
  var $VALUE;
  var $isReadOnly;
  var $isTextEditor;
  
  function Textarea($n, $v="", $w=40, $h=3 ){
     $this->NAME   = $n;
     $this->VALUE   = $v;
     $this->XPOS   = 0;
     $this->YPOS   = 0;
     $this->WIDTH  = $w;
     $this->HEIGHT = $h;
     $this->BORDER = 0;
     $this->isReadOnly = false;
  }


  function setReadOnly($b){
    if($b==true)
      $this->isReadOnly = true;
    else
      $this->isReadOnly = false;
  }

  function isReadOnly(){
    return $this->isReadOnly;
  }
 


  function setTextEditor($b){
    if($b==true)
      $this->isTextEditor = true;
    else
      $this->isTextEditor = false;
  }

  function isTextEditor(){
    return $this->isTextEditor;
  }
 


  function setBorder($size){
    $this->border = $size;
  }


 function setWidth($w){
    $this->WIDTH = $w;
  }

 function getWidth(){
    return $this->WIDTH;
  }

 function getText(){
    return $this->VALUE;
  }

 function setValue($w){
    $this->VALUE = $w;
  }


 function setHeight($h){
    $this->HEIGHT = $h;
  }

 function getHeight(){
    return $this->HEIGHT;
  }



  function changeForSQL(){
  	$tmp = $this->VALUE;
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ", "ss", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¼", "ue", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¤", "ae", $tmp);
    $tmp = str_replace("ÃÂÃÂÃÂÃÂ¶", "oe", $tmp);
    $tmp = str_replace("-", "_", $tmp);
	$this->VALUE = $tmp;	
  }







  function  show(){
    echo "
          <textarea  ";
          if( $this->isTextEditor() ){
            echo " class=\"ckeditor\" id=\"editor_" .$this->NAME ."\" ";
          } else {
            echo " id='" .$this->NAME ."' ";
          }
          
    echo "	
                    NAME     =  '" .$this->NAME ."' 
		            cols     =  '" .$this->WIDTH ."' 
					rows     =  '" .$this->HEIGHT ."' ";

    if($this->isReadOnly()){
		echo " readonly ";
	}				
	

    echo $this->getToolTipTag();
    

	echo ">".$this->VALUE."</textarea>
	";
    
        

    if( $this->isTextEditor() ){
        echo "  
             <script type=\"text/javascript\">
                  // alert('Editor configurieren'); 
        		   CKEDITOR.replace( 'editor_" .$this->NAME ."',
        				{
        					toolbar : [ [  'Bold', 'Italic', 'Underline', 'Strike','-','Link', "
                                         ." '-', 'Cut','Copy','Paste', "
                                         ." '-', 'Preview', 'Source', "
                                         ." '-', 'Cut','Copy','Paste'"
                                   ."   ], '/', "

                                   ."   ['Styles','Format','Font','FontSize'],"

                                   ."   ['TextColor','BGColor'] "
                                   
                                   ." ] 
                                 
        				});
             </script>            
        ";
    }
  
  
  }



}

?>