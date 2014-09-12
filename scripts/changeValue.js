

function changeFieldValue(name, value)
{ 
  var elm=document.getElementById(name);
  if(!elm){
    	alert("OBJEKT: "+name+" nicht gefunden. Daher war Wert-Änderung nicht möglich!" );
  }

  elm.value=value;
}




function addComboValue(name, id, text)
{ 
  var elm=document.getElementById(name);
  if(!elm){
    	alert("OBJEKT: "+name+" nicht gefunden. Daher war das hinzufügen des Wertes zur Combobox nicht möglich!" );
  }

  NeuerEintrag = new Option(id, text, false, true);
  elm[elm.length] = NeuerEintrag;
  
}


function addComboValueFromPopup(name, id, text)
{ 
  var elm=opener.document.getElementById(name);
  if(!elm){
    	alert("OBJEKT: "+name+" nicht gefunden. Daher war das hinzufügen des Wertes zur Combobox nicht möglich!" );
  }

  NeuerEintrag = new Option(text, id, false, true);
  elm[elm.length] = NeuerEintrag;
	
  
  
}