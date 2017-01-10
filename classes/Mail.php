<?php
//FileNAME: Mail.php

class Mail {
  var $FROM;  
  var $SENDERNAME;  
  var $TO;
  var $SUBJECT;
  var $HEADER;
  var $TEXT;    
  var $TYPE;
  var $TEMPL_ARRAY;

  function Mail (){
    $this->SUBJECT = "";
    $this->TYPE = "standard";
  }


  function setTo($to){
    $this->TO = $to;
  }
  
  function setType($to){
    $this->TYPE = $to;
  }

  function setFrom($from){
    $this->FROM = $from;
  }

  function setSendername($sn){
    $this->SENDERNAME = $sn;
  }

  function setSubject($s){
    $this->SUBJECT = $s;
  }

  function setText($t){
    $this->TEXT = $t;
  }

  function prepareHeader(){
    $this->HEADER = "From:".$this->FROM."\n";
    $this->HEADER .= "X-Mailer: PHP/" .phpversion() ."\n";          
    $this->HEADER .= "X-Sender-IP: " .$_SERVER['REMOTE_ADDR'] ."\n";
    $this->HEADER .= "Content-type: text/plain\n"; 
    $this->HEADER .= "Sender: ".$this->FROM."\n";
  }

  function setTemplateArray($array){
  	/*
  	 // TODO: 
  	  Hier in etwa so etwas im loop auf alle Strings im array anwenden um Parsing-Fehler zu vermeiden
  	  
  	  	$tmpTxt = str_replace("'", "", $_REQUEST['text']);
		$tmpTxt = str_replace("\"", "", $tmpTxt);
	    $tmpTxt = str_replace("\\", "", $tmpTxt);
	    $tmpTxtx = substr(nl2br($tmpTxt),0,300);

  	*/
	$this->TEMPL_ARRAY = $array;
  }

  function getMailTemplate($name){
    $tmpl = getTemplate("mails/".$name,  $this->TEMPL_ARRAY);
	return $tmpl;
  }


  function send(){

    if($this->TYPE != "standard"){   
      $txt = $this->getMailTemplate($this->TYPE);
    } else {
	  $txt = $this->TEXT;	
	}

    $this->prepareHeader();
/*
    echo "<hr>";
    echo "Sende Email"."<br>";
    echo "an: "      .$this->TO        ."<br>";
    echo "betreff: " .$this->SUBJECT   ."<br>";
    echo "head: " .$this->HEADER   ."<br>";
    
    echo "<hr>";
*/

    mail($this->TO,$this->SUBJECT,$txt,$this->HEADER);
  }




}
?>