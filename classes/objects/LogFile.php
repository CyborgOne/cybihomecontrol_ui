<?PHP

class LogFile extends Object {
    private $FULL_PATH    = "";
    private $CATEGORY     = "";
    private $DESCRIPTION  = "";
    
    private $LINES_TO_SHOW    = 50;
    private $SHOW_CATEGORY    = false;
    private $SHOW_DESCRIPTION = true;
    
    function LogFile($path, $category, $description){
        $this->FULLPATH    = $path;
        $this->CATEGORY    = $category;
        $this->DESCRIPTION = $description;
    }
    
    function getLinesToShow(){
        return $this->LINES_TO_SHOW;
    }    
    function setLinesToShow($showLinesCount){
        $this->LINES_TO_SHOW = $showLinesCount;
    }    
    
    function getPath(){
        return $this->FULLPATH;
    }
    
    function getCategory(){
        return $this->CATEGORY;
    }
    
    function getDescription(){
        return $this->DESCRIPTION;
    }
    
    function setShowCategory($showCategory){
        $this->SHOW_CATEGORY = $showCategory===true;
    }    
        
    function isShowCategory(){
        return $this->SHOW_CATEGORY;
    }    
    
    function setShowDescription($showDesc){
        $this->SHOW_DESCRIPTION = $showDesc===true;
    }    
        
    function isShowDescription(){
        return $this->SHOW_DESCRIPTION;
    }    
    
    function showDescription(){
        $spc = new Spacer();
        $spc->show();
        $td = new Text("Beschreibung:",2,true);
        $td->show();
        $d = new Text($this->getDescription());
        $d->show();

    }
    
    function showCategorie(){
        $ln = new Line();
        $ln->show();
        $t = new Title($this->getCategory());
        $t->show();
        $ln->show();
    }
    
    
    function showFullname(){
        $fp = new Text($this->getPath(), 3, true);
        
       
        $btnDel = new Button("clearLog", "Log leeren");
        $hdnDel = new Hiddenfield("clearLogFile", $this->FULLPATH);
        
        $frmDel = new Form();
        
        $tblTtl = new Table(array("",""));
        $tblTtl->setColSizes(array(null, 100));
        $tblTtl->setAlignments(array("left", "right"));
        $rTtl = $tblTtl->createRow();
        $rTtl->setAttribute(0,$fp);
        $rTtl->setAttribute(1,$btnDel);
        $tblTtl->addRow($rTtl);
        
        $frmDel->add($tblTtl);
        $frmDel->add($hdnDel);
        
        $frmDel->show();
        
    }
    
    function showLogContent(){
        $div = new Div();
        $div->setWidth("100%");
        $div->setHeight("250");
        $div->setStyle("white-space","nowrap");

        $output = array();
        $tmp = exec("tail -n " .$this->getLinesToShow() ." " .$this->getPath(), $output);
        foreach(array_reverse($output) as $out){
          $tx = new Text($out."<br>",2,false,false,false, false);
          $div->add($tx);
        }

        $ln = new Line();
                
        $ln->show();
        $div->show();
        $ln->show();
    }
    
    
    
    function checkClearLogAction(){
        if(isset($_REQUEST['clearLog']) && $_REQUEST['clearLog'] == "Log leeren"){
            if($_REQUEST['clearLogFile']==$this->FULLPATH){
                try {
                    $myfile = fopen($this->FULLPATH, "w") or die("Unable to open file!");
                    fwrite($myfile, "(".date("d.M.Y - H:i:s")."): Clear Log\n");
                    fclose($myfile);
                } catch (Exception $e){
                     echo $e->getMessage(). "\n";
                } 
            }
        }
    }
    
    
    
    
    
    
    function show(){
        $this->checkClearLogAction();
        
        // Kategorie anzeigen
        if($this->isShowCategory()){
            $this->showCategorie();
        } 
        
        $spc = new Spacer();
        $spc->show();
        
        // Datei-Pfad + Name anzeigen
        $this->showFullName();        
        
        // Beschreibung anzeigen 
        if($this->isShowDescription()){
            $this->showDescription();
        } 
        
        // Log-Inhalt im Div mit Scrollbar anzeigen 
        $this->showLogContent();
        $spc->show();
    }

    
}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        /*
        $fh = fopen($this->getPath(),'r');
        while ($line = fgets($fh)) {
           echo($line."</br>");
        }
        fclose($fh);
        */
        
        
        /*
         
if (isset($_GET['logfile']) && isset($files[$_GET['logfile']])) {
    $lines = readLastLinesOfFile($_GET['logfile'], $files[$_GET['logfile']]); ?>
    <html>
        <head>
            <meta http-equiv="refresh" content="5">
        </head>
        <body style="margin: 0">
            <div style="font-size: 0.6em; white-space: nowrap">
                Filesize: <?=round(filesize($_GET['logfile'])/1024/1024,2) ?> MB<br/>
            <?   foreach ($lines as $line) {
                    echo "<nowrap>$line</nowrap><br/>\n";
                } ?>
            </div>
        </body>
    </html>
<? } else { ?>
        <html>
            <head>
            </head>
            <body>
            <?   foreach($files as $filename => $lines) {
                    echo $filename; ?>
                    <iframe src="tail.php?logfile=<?=urlencode($filename)?>" style="height: <?=$lines*13+15?>px; width: 100%;"></iframe>
            <?   } ?>
            </body>
        </html>
<? }
 
function readLastLinesOfFile($filePath, $lines = 10) {
    //global $fsize;
    $handle = fopen($filePath, "r");
    if (!$handle) {
        return array();
    }
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true;
                break;
            }
            $t = fgetc($handle);
            $pos--;
        }
        $linecounter--;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = str_replace(array("\r", "\n", "\t"), '', fgets($handle));
        if ($beginning) break;
    }
    fclose($handle);
    return array_reverse($text);
}
 
        */


?>