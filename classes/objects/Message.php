<?php
//FileNAME: Message.php

class Message {
  function Message($text){
    echo "<script>setTimeout(function(){\$(\".messageText\").text(\""
         .$text 
         ."\").fadeIn(400).delay(3000).fadeOut(400);}, " .($_SESSION['msgCount']*4000).");</script>";

    $_SESSION['msgCount']++;
  }

}

?>