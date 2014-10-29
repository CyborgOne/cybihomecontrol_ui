<?php

/*
  FUNKTIONIERT NUR WENN INIT BEREITS ERFOLGT IST!!!
*/

/**
  margin-left: 0;
  margin-right: 0;
  margin-bottom: 0;
  margin-top: 0;
  padding-left: 0;
  padding-right: 0;
  padding-bottom: 0;
  padding-top: 0;

*/
echo "

<style type='text/css'>

*
{
 font-family: Arial;
 horizontal-align:left;
}


textarea
{
  font-style:normal;
  font-weight:normal;  
  font-size: 12px;
}

iframe
{
  margin-left: 0;
  margin-right: 0;
  margin-bottom: 0;
  margin-top: 0;
  padding-left: 0;
  padding-right: 0;
  padding-bottom: 0;
  padding-top: 0;
}


input
{
  font-style:normal;
  font-weight:normal;  
  font-size: 12px;
}


select 
{
  font-style:normal;
  font-weight:normal;  
  font-size: 12px;	
}

  
body
{
  oncontextmenu:return false;
  background-color:" .$COLORS['background'] .";
  background-position:fixed;
  background-repeat:no-repeat;
  color: " .$COLORS['text'] .";
  margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px
}

body.indexbody
{
//  background-image:url(pics/indxBottomRightBG.jpg);
  background-repeat:no-repeat;
  background-position: bottom right;
}

table
{
  margin-left: 0;
  margin-right: 0;
  margin-bottom: 0;
  margin-top: 0;

  padding-left: 0;
  padding-right: 0;
  padding-bottom: 0;
  padding-top: 0;
  vertical-align: top;
}


tr
{
  padding-left: 0;
  padding-right: 0;
  padding-bottom: 0;
  padding-top: 0;
  
  margin-left: 0;
  margin-right: 0;
  margin-bottom: 0;
  margin-top: 0;
  
}



th
{
  color:" .$COLORS['titel'] .";
  font-style:normal;
  font-weight:bolder;  
  image-size:100%;
  font-size: 14px;
  horizontal-align:left;
  vertical-align:top;
}


td.panelTitle
{ 
}


td.panelBody
{
}

td
{
  font-size: 11px;
  font-weight:normal; 
  margin-left: 0px;
  margin-right: 0px;
  margin-bottom: 0px;
  margin-top: 0px;
  padding-left: 0px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-top: 0px;
  vertical-align: top;
  horizontal-align:left;
}



td.tabbedPaneTab
{
  margin-left: 0px;
  margin-right: 0px;
  margin-bottom: 0px;
  margin-top: 0px;
  padding-left: 0px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-top: 0px;
  background-repeat:repeat;
}


div
{
  vertical-align:top;
  horizontal-align:left;
}


a
{
  text-decoration:none;
  
}

a:link 
{
  color:" .$COLORS['link'] .";
}

a:visited 
{
  color:" .$COLORS['link'] .";
}

a:active 
{
  color:" .$COLORS['link'] .";
}

a:hover
{
  color:" .$COLORS['hover'] .";
}



a.menulink
{
  font-weight:bolder;  
  text-decoration:none;
}


a.menulink:link 
{
  color:" .$COLORS['menu'] .";
}

a.menulink:visited 
{
  color:" .$COLORS['menu'] .";
}

a.menulink:active 
{
  color:" .$COLORS['menu'] .";
}

a.menulink:hover
{
  color:" .$COLORS['hover'] .";
}


hr
{
  color:" .$COLORS['text'] .";
}

</style>
";


?>