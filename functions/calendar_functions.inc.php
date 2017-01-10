<?php
/**
* 
* @author Daniel Scheidler 
* @copyright November 2008
*/

function getBirthdayForCalendarView($day, $month, $year){
  $ret = new Text("");
  /*-------------------------------------------------
  /  Prüft ob an angegebenem Datum ein Geburtstag
  /  ist, und zeigt diesen im Kalender an.
  /-------------------------------------------------*/
  if(strlen($month)==1){
  	$month="0".$month;
  }
  if(strlen($day)==1){
    $day="0".$day;
  }  
  
  $sqlBirthday = "SELECT * FROM user ";
  $sqlBirthday .= "WHERE Geburtstag like '%-".$month."-".$day ."' ";
  $sqlBirthday .= "AND Name != 'Superuser' ";
  $resultBirthday = mysql_query($sqlBirthday);
  
  $ret = new Div();	
  while($rowBirthday = mysql_fetch_array($resultBirthday)){
	$t = getBDayInfoForCalendarView($rowBirthday);
    $ret->add($t);
  }
  
  return $ret;
}








function getBDayInfoForCalendarView($Userrow){
	
	$ret  = new Text("[fett]Geburtstag: [/fett]".$Userrow['Vorname'] ." " .$Userrow['Nachname'] ."<hr>");
	$ret->setFilter(false);
	$ret->setToolTip(getUserprofilAsTooltipText($Userrow['id']));
	return $ret;
}









function getTerminInfoForCalendarView($rowTermin){
	$ret = new Text("");
	
	if($rowTermin['terminart']=="angebot"){
	  $res = $_SESSION['config']->DBCONNECT->executeQuery("SELECT * FROM stadt_angebot where terminserien_id =  " .$rowTermin['id']);
	  $rowAngebot = mysql_fetch_array($res);	
	  
	  if( $rowAngebot["kategorie"]!=99 && $rowAngebot["aktivjn"]=='J' ){
		  $txt = new Text($rowAngebot['name']."<hr>");
		  $txt->setFilter(false);
		  
		  $ret = new Link("?menuParent=Stadtplan&run=stadtplansuche&suchauswahlAnzeigen=" .$rowAngebot['id'] ."&suchauswahl=stadt_angebot" ,$txt);
		  $ret->setToolTip("<b>" .$rowAngebot['name'] ."</b><br>" .$rowAngebot['text']);
	  }
	}
	
	return $ret;
}

















function getTerminForCalendarView($day, $month, $year){
  $ret = new Text("");
  //W - Wochennummer des Jahres (z.B.: 28)
  //w - Wochentag (0(Sonntag) bis 6(Samstag))
  $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;
  
  if($wochentag==0){
    $wochentag = 7;	
  }
  /*-------------------------------------------------
  /  Prüft ob an angegebenem Datum ein Geburtstag
  /  ist, und zeigt diesen im Kalender an.
  /-------------------------------------------------*/
  if(strlen($month)==1){
  	$month="0".$month;
  } 
  
  if(strlen($day)==1){
    $day="0".$day;
  }  
  // Alle mit passendem Start/Enddatum
  $sqlTermin = "SELECT * FROM terminserie ";
  $sqlTermin .= "WHERE serien_start_datum <= '" .$year ."-".$month."-".$day ."'  ";
  $sqlTermin .= "AND (serien_end_datum  >= '" .$year ."-".$month."-".$day ."' OR enddatumjn != 'J') ";
  
  //echo $sqlTermin;
  
  $resultTermin = mysql_query($sqlTermin);
  
  $ret = new Div();
   	
  while($rowTermin = mysql_fetch_array($resultTermin)){
    switch ($rowTermin['serienmuster']){ 
      case -1:
      // Einzeltermin
      
        if($rowTermin['fixdatum'] == $year ."-".$month."-".$day  	){
			$ret->add(getTerminInfoForCalendarView($rowTermin));
		}
		break;


// ---------------------------------------      
            
	  case 1:
      // Täglicher Serientermin
      //Wenn xten==1 gilt der Termin entweder nur Wochentags oder an allen Tagen 
      if(isset($rowTermin['xten']) && $rowTermin['xten'] == 1){
	    $chkWeekday = false;
        switch ($wochentag ){ 
          case 1:
            if($rowTermin['montag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 2:
            if($rowTermin['dienstag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 3:
            if($rowTermin['mittwoch']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 4:
            if($rowTermin['donnerstag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 5:
            if($rowTermin['freitag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 6:
            if($rowTermin['samstag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 7:
            if($rowTermin['sonntag']=="J"){
		      $chkWeekday = true;
			}
			break;
        }
        
        if($chkWeekday){
			$ret->add(getTerminInfoForCalendarView($rowTermin));
		}
      //Sonst (Wenn xten!=1 gilt der Termin im festen Interval von x-Tagen)	
      	
      } else {
      //	z - Tag des Jahres (z.B. 148 (entspricht 29.05.2001))
		$jahrestag = date ( 'z', mktime ( 0, 0, 0, $month, $day, $year ) ) ;
		$starttag = date ( 'z', mktime ( 0, 0, 0, substr($rowTermin['serien_start_datum'],5,2),  substr($rowTermin['serien_start_datum'],8,2), substr($rowTermin['serien_start_datum'],0,4) ) ) ;  
        
		if( ($jahrestag-$starttag)%$rowTermin['xten'] == 0){		  
          $ret->add(getTerminInfoForCalendarView($rowTermin));
	    }
	  }
      break;
      
// ---------------------------------------      
      
      case 2:
      // Wöchentlicher Serientermin
	
      if(strlen($rowTermin['xten'])>0){
      	$chkWeekday=false;


      	$jahreswoche = date ( 'W', mktime ( 0, 0, 0, $month, $day, $year ) ) ; 
      	$startwoche = date ( 'W', mktime ( 0, 0, 0, substr($rowTermin['serien_start_datum'],5,2), substr($rowTermin['serien_start_datum'],8,2), substr($rowTermin['serien_start_datum'],0,4) ) ) ;
        $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;

		        
		if( ($jahreswoche-$startwoche)%$rowTermin['xten']==0){	
        	$chkWeekday=false;	  
	        switch ($wochentag ){ 
	          case 1:
	            if($rowTermin['montag']=="J"){
			      $chkWeekday = true;
			      $d=1;
				}
				break;
	          case 2:
	            if($rowTermin['dienstag']=="J"){
			      $chkWeekday = true;
			      $d=2;
				}
				break;
	          case 3:
	            if($rowTermin['mittwoch']=="J"){
			      $chkWeekday = true;
			      $d=3;
				}
				break;
	          case 4:
	            if($rowTermin['donnerstag']=="J"){
			      $chkWeekday = true;
			      $d=4;
				}
				break;
	          case 5:
	            if($rowTermin['freitag']=="J"){
			      $chkWeekday = true;
			      $d=5;       
				}
				break;
	          case 6:
	            if($rowTermin['samstag']=="J"){
			      $chkWeekday = true;
			      $d=6;
				}
				break;
	          case 7:
	            if($rowTermin['sonntag']=="J"){
			      $chkWeekday = true;
			      $d=7;
				}
				break;
	        }
        }
        if($chkWeekday){
           $aktuellerWochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;

	       //Wochentag auswerten
	       if($chkWeekday && $aktuellerWochentag==$d){
     		    $ret->add(getTerminInfoForCalendarView($rowTermin));
     	   }
		}
	  }
      break;

// ---------------------------------------      
            
      case 3:
      // Monatlicher Serientermin
        if($rowTermin['monatwochentagjn'] == "N"){
          if($rowTermin['xten'] == $day){
            $startmonat = substr($rowTermin['serien_start_datum'],5,2);
            if($month>$startmonat && bcmod( $month - $startmonat, $rowTermin['xten2'])==0){	
	          $ret->add(getTerminInfoForCalendarView($rowTermin));
	        }
          }
        } else {
      	  $chkWeekday=false;
      	  // Passt der Monat?
      	
          $startmonat = substr($rowTermin['serien_start_datum'],5,2);
          $xten2 = $rowTermin['xten2']!=null?$rowTermin['xten2']:1;
          
      	  if($month >= $startmonat && ( $month - $startmonat)%$xten2==0){
         	$d=0;
			switch ($wochentag ){ 
	          case 1:
	            if($rowTermin['montag']=="J"){
			      $chkWeekday = true;
			      $d = 1;
				}
				
	          case 2:
	            if($rowTermin['dienstag']=="J"){
			      $chkWeekday = true;
			      $d = 2;
				}
				
	          case 3:
	            if($rowTermin['mittwoch']=="J"){
			      $chkWeekday = true;
			      $d = 3;
				}
				
	          case 4:
	            if($rowTermin['donnerstag']=="J"){
			      $chkWeekday = true;
			      $d = 4;
				}
				
	          case 5:
	            if($rowTermin['freitag']=="J"){
			      $chkWeekday = true;
			      $d = 5;
				}
				
	          case 6:
	            if($rowTermin['samstag']=="J"){
			      $chkWeekday = true;
			      $d = 6;
				}
				
	          case 7:
	            if($rowTermin['sonntag']=="J"){
			      $chkWeekday = true;
			      $d = 7;
				}
			  }	
              $jahreswoche = date ( 'W', mktime ( 0, 0, 0, $month, $day, $year ) ) ; 
              $ersteMonatswoche = date ( 'W', mktime ( 0, 0, 0, $month, 1, $year) ) ;

	           //w - Wochentag (0(Sonntag) bis 6(Samstag))
		      $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, 1, $year ) ) ;
              if ($wochentag==0){
         	    $wochentag = 7;
	          } 
		      if($wochentag>$d){
                $ersteMonatswoche++;
              }
			// Passt die Woche?
      	   if($jahreswoche - $ersteMonatswoche == $rowTermin['xten']+1){
             if($chkWeekday){
  		       $aktuellerWochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;

	           //Wochentag auswerten
			   if($chkWeekday && $aktuellerWochentag==$d){
	             $ret->add(getTerminInfoForCalendarView($rowTermin));	
	           }
	         }
           }
	    }
		  
	  }
      break;

// ---------------------------------------      
      
	  case 4:
      // Jährlicher Serientermin
      if($rowTermin['jaehrlichwochentag']=="J"){
         	$d=0;
         	$chkWeekday=false;
			switch ($wochentag ){ 
	          case 1:
	            if($rowTermin['montag']=="J"){
			      $chkWeekday = true;
			      $d = 1;
				}
				
	          case 2:
	            if($rowTermin['dienstag']=="J"){
			      $chkWeekday = true;
			      $d = 2;
				}
				
	          case 3:
	            if($rowTermin['mittwoch']=="J"){
			      $chkWeekday = true;
			      $d = 3;
				}
				
	          case 4:
	            if($rowTermin['donnerstag']=="J"){
			      $chkWeekday = true;
			      $d = 4;
				}
				
	          case 5:
	            if($rowTermin['freitag']=="J"){
			      $chkWeekday = true;
			      $d = 5;
				}
				
	          case 6:
	            if($rowTermin['samstag']=="J"){
			      $chkWeekday = true;
			      $d = 6;
				}
				
	          case 7:
	            if($rowTermin['sonntag']=="J"){
			      $chkWeekday = true;
			      $d = 7;
				}
			  }	
              $jahreswoche = date ( 'W', mktime ( 0, 0, 0, $month, $day, $year ) ) ; 
              $ersteMonatswoche = date ( 'W', mktime ( 0, 0, 0, $month, 1, $year) ) ;

	           //w - Wochentag (0(Sonntag) bis 6(Samstag))
		      $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, 1, $year ) ) ;
		      $aktuellerWochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;
              if ($wochentag==0){
         	    $wochentag = 7;
	          } 
		      if($wochentag>$d){
                $ersteMonatswoche++;
              }

		   // Passt die Woche?
      	   if($jahreswoche - $ersteMonatswoche == $rowTermin['xten']-1){
			 //Passt der Monat
	         if ($month == $rowTermin['monat']){
	           //Wochentag auswerten
			   if($chkWeekday && $aktuellerWochentag==$d){
	             $ret->add(getTerminInfoForCalendarView($rowTermin));	
	           }
	         }
           }
      } else {
		if($day == $rowTermin['xten'] && $month == $rowTermin['monat']){
		  $ret->add(getTerminInfoForCalendarView($rowTermin));	
		}		
	  }
      
	  break;      
    }

  }
  
  return $ret;
}




function getTerminTextForCalendarView($day, $month, $year){
  $ret = "";
  //W - Wochennummer des Jahres (z.B.: 28)
  //w - Wochentag (0(Sonntag) bis 6(Samstag))
  $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;
  
  if($wochentag==0){
    $wochentag = 7;	
  }
  /*-------------------------------------------------
  /  Prüft ob an angegebenem Datum ein Geburtstag
  /  ist, und zeigt diesen im Kalender an.
  /-------------------------------------------------*/
  if(strlen($month)==1){
  	$month="0".$month;
  }
  if(strlen($day)==1){
    $day="0".$day;
  }  
  // Alle mit passendem Start/Enddatum
  $sqlTermin = "SELECT * FROM terminserie ";
  $sqlTermin .= "WHERE serien_start_datum <= '" .$year ."-".$month."-".$day ."'  ";
  $sqlTermin .= "AND (serien_end_datum  >= '" .$year ."-".$month."-".$day ."' OR enddatumjn != 'J') ";
  //echo $sqlTermin;
  
  $resultTermin = mysql_query($sqlTermin);
  
   	
  while($rowTermin = mysql_fetch_array($resultTermin)){
    switch ($rowTermin['serienmuster']){ 
      case -1:
      // Einzeltermin
      
        if($rowTermin['fixdatum'] == $year ."-".$month."-".$day  	){

            $tx = getTerminInfoForCalendarView($rowTermin)->getText();
            if($tx instanceof Object){
                if(method_exists($tx, "getText")){
                    $tx = $tx->getText();
                } else {
                    $tx = "";
                }
            }
            if($ret!="" && $tx!=""){
                 $ret = $ret."<br>";
            }
			$ret = $ret .$tx;
		}
		break;

// ---------------------------------------      
            
	  case 1:
      // Täglicher Serientermin
      //Wenn xten==1 gilt der Termin entweder nur Wochentags oder an allen Tagen 
      if(isset($rowTermin['xten']) && $rowTermin['xten'] == 1){
	    $chkWeekday = false;
        switch ($wochentag ){ 
          case 1:
            if($rowTermin['montag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 2:
            if($rowTermin['dienstag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 3:
            if($rowTermin['mittwoch']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 4:
            if($rowTermin['donnerstag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 5:
            if($rowTermin['freitag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 6:
            if($rowTermin['samstag']=="J"){
		      $chkWeekday = true;
			}
			break;
          case 7:
            if($rowTermin['sonntag']=="J"){
		      $chkWeekday = true;
			}
			break;
        }
        
        if($chkWeekday){
            $tx = getTerminInfoForCalendarView($rowTermin)->getText();
            if($tx instanceof Object){
                if(method_exists($tx, "getText")){
                    $tx = $tx->getText();
                } else {
                    $tx = "";
                }
            }
            if($ret!="" && $tx!=""){
                 $ret = $ret."<br>";
            }
			$ret = $ret .$tx;
   		}
      //Sonst (Wenn xten!=1 gilt der Termin im festen Interval von x-Tagen)		
      } else {
		$jahrestag = date ( 'z', mktime ( 0, 0, 0, $month, $day, $year ) ) ;
		$starttag = date ( 'z', mktime ( 0, 0, 0, substr($rowTermin['serien_start_datum'],5,2),  substr($rowTermin['serien_start_datum'],8,2), substr($rowTermin['serien_start_datum'],0,4) ) ) ;  
        
		if( ($jahrestag-$starttag)%$rowTermin['xten'] == 0){		  
            $tx = getTerminInfoForCalendarView($rowTermin)->getText();
            if($tx instanceof Object){
                if(method_exists($tx, "getText")){
                    $tx = $tx->getText();
                } else {
                    $tx = "";
                }
            }
            if($ret!="" && $tx!=""){
                 $ret = $ret."<br>";
            }
			$ret = $ret .$tx;
	    }
	  }
      break;
      
// ---------------------------------------      
      
      case 2:
      // Wöchentlicher Serientermin
      
      if(strlen($rowTermin['xten'])>0){
      	$chkWeekday=false;
      //	z - Tag des Jahres (z.B. 148 (entspricht 29.05.2001))
      	$jahreswoche = date ( 'W', mktime ( 0, 0, 0, $month, $day, $year ) ) ; 
      	$startwoche = date ( 'W', mktime ( 0, 0, 0, substr($rowTermin['serien_start_datum'],5,2), substr($rowTermin['serien_start_datum'],8,2), substr($rowTermin['serien_start_datum'],0,4) ) ) ;
		        
		if( ($jahreswoche-$startwoche)%$rowTermin['xten']==0){	
			$chkWeekday=false;	  
	        switch ($wochentag ){ 
	          case 1:
	            if($rowTermin['montag']=="J"){
			      $chkWeekday = true;
			      $d=1;
				}
				break;
	          case 2:
	            if($rowTermin['dienstag']=="J"){
			      $chkWeekday = true;
			      $d=2;
				}
				break;
	          case 3:
	            if($rowTermin['mittwoch']=="J"){
			      $chkWeekday = true;
			      $d=3;
				}
				break;
	          case 4:
	            if($rowTermin['donnerstag']=="J"){
			      $chkWeekday = true;
			      $d=4;
				}
				break;
	          case 5:
	            if($rowTermin['freitag']=="J"){
			      $chkWeekday = true;
			      $d=5;
				}
				break;
	          case 6:
	            if($rowTermin['samstag']=="J"){
			      $chkWeekday = true;
			      $d=6;
				}
				break;
	          case 7:
	            if($rowTermin['sonntag']=="J"){
			      $chkWeekday = true;
			      $d=7;
				}
				break;
	        }
        }
        if($chkWeekday){
            $tx = getTerminInfoForCalendarView($rowTermin)->getText();
            if($tx instanceof Object){
                if(method_exists($tx, "getText")){
                    $tx = $tx->getText();
                } else {
                    $tx = "";
                }
            }
            if($ret!="" && $tx!=""){
                 $ret = $ret."<br>";
            }
			$ret = $ret .$tx;
		}
	  }
      break;

// ---------------------------------------      
            
      case 3:
      // Monatlicher Serientermin
        if($rowTermin['monatwochentagjn'] == "N"){
          if($rowTermin['xten'] == $day){
            $startmonat = substr($rowTermin['serien_start_datum'],5,2);
            if($month>$startmonat && bcmod( $month - $startmonat, $rowTermin['xten2'])==0){	
                $tx = getTerminInfoForCalendarView($rowTermin)->getText();
                if($tx instanceof Object){
                    if(method_exists($tx, "getText")){
                        $tx = $tx->getText();
                    } else {
                        $tx = "";
                    }
                }
                if($ret!="" && $tx!=""){
                     $ret = $ret."<br>";
                }
			     $ret = $ret .$tx;
	        }
          }
        } else {
      	  $chkWeekday=false;
      	  // Passt der Monat?
      	
         $startmonat = substr($rowTermin['serien_start_datum'],5,2);
         
	  $xten2 = $rowTermin['xten2'];
	  if($xten2==null || $xten2==0){
		$xten2 = 1;
	  }

      	  if($month >= $startmonat && ( $month - $startmonat)%$xten2==0){
         	$d=0;
		switch ($wochentag ){ 
	          case 1:
	            if($rowTermin['montag']=="J"){
			      $chkWeekday = true;
			      $d = 1;
				}
				
	          case 2:
	            if($rowTermin['dienstag']=="J"){
			      $chkWeekday = true;
			      $d = 2;
				}
				
	          case 3:
	            if($rowTermin['mittwoch']=="J"){
			      $chkWeekday = true;
			      $d = 3;
				}
				
	          case 4:
	            if($rowTermin['donnerstag']=="J"){
			      $chkWeekday = true;
			      $d = 4;
				}
				
	          case 5:
	            if($rowTermin['freitag']=="J"){
			      $chkWeekday = true;
			      $d = 5;
				}
				
	          case 6:
	            if($rowTermin['samstag']=="J"){
			      $chkWeekday = true;
			      $d = 6;
				}
				
	          case 7:
	            if($rowTermin['sonntag']=="J"){
			      $chkWeekday = true;
			      $d = 7;
				}
			  }	
              $jahreswoche = date ( 'W', mktime ( 0, 0, 0, $month, $day, $year ) ) ; 
              $ersteMonatswoche = date ( 'W', mktime ( 0, 0, 0, $month, 1, $year) ) ;

	           //w - Wochentag (0(Sonntag) bis 6(Samstag))
		      $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, 1, $year ) ) ;
              if ($wochentag==0){
         	    $wochentag = 7;
	          } 
		      if($wochentag>$d){
                $ersteMonatswoche++;
              }
			// Passt die Woche?
      	   if($jahreswoche - $ersteMonatswoche == $rowTermin['xten']+1){
             if($chkWeekday){
  		       $aktuellerWochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;

	           //Wochentag auswerten
			   if($chkWeekday && $aktuellerWochentag==$d){
                    $tx = getTerminInfoForCalendarView($rowTermin)->getText();
                    if($tx instanceof Object){
                        if(method_exists($tx, "getText")){
                            $tx = $tx->getText();
                        } else {
                            $tx = "";
                        }
                    }
                    if($ret!="" && $tx!=""){
                         $ret = $ret."<br>";
                    }
        			$ret = $ret .$tx;
	           }
	         }
           }
	    }
		  
	  }
      break;

// ---------------------------------------      
      
	  case 4:
      // Jährlicher Serientermin
      if($rowTermin['jaehrlichwochentag']=="J"){
         	$d=0;
         	$chkWeekday=false;
			switch ($wochentag ){ 
	          case 1:
	            if($rowTermin['montag']=="J"){
			      $chkWeekday = true;
			      $d = 1;
				}
				
	          case 2:
	            if($rowTermin['dienstag']=="J"){
			      $chkWeekday = true;
			      $d = 2;
				}
				
	          case 3:
	            if($rowTermin['mittwoch']=="J"){
			      $chkWeekday = true;
			      $d = 3;
				}
				
	          case 4:
	            if($rowTermin['donnerstag']=="J"){
			      $chkWeekday = true;
			      $d = 4;
				}
				
	          case 5:
	            if($rowTermin['freitag']=="J"){
			      $chkWeekday = true;
			      $d = 5;
				}
				
	          case 6:
	            if($rowTermin['samstag']=="J"){
			      $chkWeekday = true;
			      $d = 6;
				}
				
	          case 7:
	            if($rowTermin['sonntag']=="J"){
			      $chkWeekday = true;
			      $d = 7;
				}
			  }	
              $jahreswoche = date ( 'W', mktime ( 0, 0, 0, $month, $day, $year ) ) ; 
              $ersteMonatswoche = date ( 'W', mktime ( 0, 0, 0, $month, 1, $year) ) ;

	           //w - Wochentag (0(Sonntag) bis 6(Samstag))
		      $wochentag = date ( 'w', mktime ( 0, 0, 0, $month, 1, $year ) ) ;
		      $aktuellerWochentag = date ( 'w', mktime ( 0, 0, 0, $month, $day, $year ) ) ;
              if ($wochentag==0){
         	    $wochentag = 7;
	          } 
		      if($wochentag>$d){
                $ersteMonatswoche++;
              }

		   // Passt die Woche?
      	   if($jahreswoche - $ersteMonatswoche == $rowTermin['xten']-1){
			 //Passt der Monat
	         if ($month == $rowTermin['monat']){
	           //Wochentag auswerten
			   if($chkWeekday && $aktuellerWochentag==$d){
                    $tx = getTerminInfoForCalendarView($rowTermin)->getText();
                    if($tx instanceof Object){
                        if(method_exists($tx, "getText")){
                            $tx = $tx->getText();
                        } else {
                            $tx = "";
                        }
                    }
                    if($ret!="" && $tx!=""){
                         $ret = $ret."<br>";
                    }
        			$ret = $ret .$tx;
	           }
	         }
           }
      } else {
		if($day == $rowTermin['xten'] && $month == $rowTermin['monat']){
		    $tx = getTerminInfoForCalendarView($rowTermin)->getText();
            if($tx instanceof Object){
                if(method_exists($tx, "getText")){
                    $tx = $tx->getText();
                } else {
                    $tx = "";
                }
            }
            if($ret!="" && $tx!=""){
                 $ret = $ret."<br>";
            }
			$ret = $ret .$tx;


		}		
	  }
      
	  break;      
    }

  }
  
  return $ret;
}





/**
 * Die Methode liefert die Beschreibung zur Serien-Wiederholung als Text zurück.
 * Text: "Dieser Termin wiederholt sich ....."
 * 
 * @param int(11)  ID des Termins
 * 
 * @return String  Beschreibungstext 
 */
function getTerminDatumText($id){
  $ret = "";
  // Termin-Infos holen
  $sqlTermin  = "SELECT t.*, m.name as Muster_Name FROM terminserie t, terminserien_muster m ";
  $sqlTermin .= "WHERE  t.serienmuster = m.id ";
  $sqlTermin .= "AND    t.id = ".$id;
  
  $resultTermin = mysql_query($sqlTermin);
  
  if (mysql_num_rows($resultTermin)==1){
     $row = mysql_fetch_array($resultTermin);
     
     // Einzeltermin anzeigen
	 if( $row['serienmuster'] == -1 ){  // Einzel-Termin
	    	$ret = getFormatedDate($row['fixdatum'])."\n\r";
	 
	 // Terminserie anzeigen  
	 } else {
			
			$ret .= "Jeden ";
				 
				 
				 
			switch ($row['serienmuster']){ 
			  case 1:  // Täglich
			  	  if($row['xten']>1){
				    $ret .= $row['xten'] .". ";	 
				  }
						
				  $ret .= "Tag";
				  
				  if ( $row['montag']=="J" && $row['dienstag']=="J" && $row['mittwoch']=="J" && $row['donnerstag']=="J" && $row['freitag']=="J" && $row['samstag']=="N" && $row['sonntag']=="N" ){
					$ret .= "(gilt nur an Werktagen)";
				  }
				  
		    break;
				
				
			  case 2:  // Monatlich		
			      
			      if ($row['xten'] > 1){
				    $ret .= $row['xten'] .". ";
				  }
				  $chkBool = false;
				  
				  if ( $row['montag']=="J"){
					$ret .= "Montag ";
					$chkBool = true;
				  }
		
				  if ( $row['dienstag']=="J"){
					if($chkBool){
					  $ret .= ",";	
					}
					
					$ret .= "Dienstag ";
					$chkBool = true;
				  }
		
				  if ( $row['mittwoch']=="J"){
					if($chkBool){
					  $ret .= ",";	
					}
					
					$ret .= "Mittwoch ";
					$chkBool = true;
				  }
		
				  if ( $row['donnerstag']=="J"){
					if($chkBool){
					  $ret .= ",";	
					}
					
					$ret .= "Donnerstag ";
					$chkBool = true;
				  }
		
				  if ( $row['freitag']=="J"){
					if($chkBool){
					  $ret .= ",";	
					}
					
					$ret .= "Freitag ";
					$chkBool = true;
				  }
		
				  if ( $row['samstag']=="J"){
					if($chkBool){
					  $ret .= ",";	
					}
					
					$ret .= "Samstag ";
					$chkBool = true;
				  }
		
				  if ( $row['sonntag']=="J"){
					if($chkBool){
					  $ret .= ",";	
					}
					
					$ret .= "Sonntag ";
				  }
		
		    break;
				
			  case 3:  // Wöchentlich
				if($row['monatwochentagjn'] == "N"){
					$ret .= $row['xten'] .". Tag jedes ".$row['xten2'].". Monats.";
				} else {
					$ret .= $row['xten'] .". " .getWochentag($row) ." jedes ".$row['xten2'].". Monats.";
				}
				  
		    break;
				
			  case 4:  // Jährlich	
				if($row['jaehrlichwochentag'] == "N"){
					$ret .= $row['xten'] .". " .getMonatsString($row['monat']) ." ";
				} else {
					$ret .= $row['xten'] .". " .getWochentag($row) ." im " .getMonatsString($row['monat']) ." ";
				}

				  
		    break;
		
			}
			
			
			if($row['enddatumjn'] == "J"){
				$ret .= "\r\nGültig ab: ".getFormatedDate($row['serien_start_datum'])."    bis: " .getFormatedDate($row['serien_end_datum'])."\n\r";
			}

	  }
	 
  }
  
  return $ret;
}


/**
 * Die Methode liefert die Beschreibung zur Serien-Wiederholung als Text zurück.
 * Text: "Dieser Termin wiederholt sich ....."
 * 
 * @param DbRow  ID des Termins
 * 
 * @return String  Beschreibungstext 
 */
function getWochentag($terminRow){
				  if ( $terminRow['montag']=="J"){
					return "Montag";
				  }
		
				  if ( $terminRow['dienstag']=="J"){
					return "Dienstag";
				  }
		
				  if ( $terminRow['mittwoch']=="J"){
					return "Mittwoch";
				  }
		
				  if ( $terminRow['donnerstag']=="J"){
					return "Donnerstag";
				  }
		
				  if ( $terminRow['freitag']=="J"){
					return "Freitag";
				  }
		
				  if ( $terminRow['samstag']=="J"){
				  	return "Samstag";
				  }
		
				  if ( $terminRow['sonntag']=="J"){					
					return "Sonntag";
				  }
}



/**
 * Die Methode liefert den Monat als Text zurück.
 * 
 * @param int(2)   Monats-Zahl
 * 
 * @return String  Monat als Text 
 */
function getMonatsString($monatsZahl){
	$ret = "";
	
	if($monatsZahl>0 && $monatsZahl<=12){
      $sql = "SELECT * FROM default_combo_values WHERE combo_name = 'Monate' and code = ".$monatsZahl;
      $result = mysql_query($sql);
      $row = mysql_fetch_array($result);
      
      $ret = $row['value'];
    }
    
	return $ret;    
}



/**
 * Die Methode liefert die Beschreibung zur Serien-Wiederholung als Text zurück.
 * Text: "Dieser Termin wiederholt sich ....."
 * 
 * @param int(11)  ID des Termins
 * 
 * @return String  Beschreibungstext 
 */
function getTerminWiederholText($id){
  $ret = "";
  
  $sqlTermin  = "SELECT t.*, m.name as Muster_Name FROM terminserie t, terminserien_muster m ";
  $sqlTermin .= "WHERE  t.serienmuster = m.id ";
  $sqlTermin .= "AND    t.id = ".$id;
  
  $resultTermin = mysql_query($sqlTermin);
  
  if (mysql_num_rows($resultTermin)==1){
	$ret = "Dieser Termin wiederholt sich ";
	 
	$row = mysql_fetch_array($resultTermin);
	 


	 
	switch ($row['serienmuster']){ 
      case -1: // Einzeltermin
     	  $ret .= " nie.";
		   
	    break;
		
	  case 1:  // Täglich	
		  $ret .= " täglich.";
		  
	    break;
		
	  case 2:  // Monatlich	
		  $ret .= " wöchentlich.";
		  
	    break;
		
	  case 3:  // Wöchentlich	
		  $ret .= " monatlich.";

	    break;
		
	  case 4:  // Jährlich	
		  $ret .= " jährlich.";
		  
	    break;

	}
  }
  
  return $ret;
}





?>
