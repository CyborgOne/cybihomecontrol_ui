var strOldPreId = null;

function makeVisible(strPreId){
  if(strPreId != strOldPreId){
    if(strOldPreId != null){
      // Offene Togglebox schliessen
      document.getElementById(strOldPreId+'_toggle').style.display = 'none';
      document.getElementById(strOldPreId+'_link').style.display = '';
    }

    document.getElementById(strPreId+'_toggle').style.display = 'block';
    document.getElementById(strPreId+'_link').style.display = 'none';
    strOldPreId = strPreId;
  }else{
    document.getElementById(strPreId+'_toggle').style.display = 'none';
    document.getElementById(strPreId+'_link').style.display = '';
    strOldPreId = null;
  }
}



function makeVisibleWithoutLink(strPreId){
  if(strPreId != strOldPreId){
    if(strOldPreId != null){
      // Offene Togglebox schliessen
      document.getElementById(strOldPreId+'_toggle').style.display = 'none';
     // document.getElementById(strOldPreId+'_link').style.display = '';
    }

    document.getElementById(strPreId+'_toggle').style.display = 'block';
    //document.getElementById(strPreId+'_link').style.display = 'none';
    strOldPreId = strPreId;
  }
}

