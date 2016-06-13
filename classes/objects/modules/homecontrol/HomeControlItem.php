<?PHP

class HomeControlItem extends Object {
    private $ID = 0;
    private $X = 0;
    private $Y = 0;
    private $OBJNAME = "";
    private $FUNK_ID = 0;
    private $FUNK_ID2 = 0;
    private $BESCHREIBUNG = "";
    private $ART = 0;
    private $ETAGE = 0;
    private $ZIMMER = 0;
    private $PIC = "";
    private $FUNKID2_NEED = false;
    private $DIMMER = "N";

    private $EDIT_MODE = false;

    private $CONTROL_IMAGE_WIDTH = 40;
    private $CONTROL_IMAGE_HEIGHT = 40;


    function HomeControlItem($currConfigRow, $editModus) {
        $this->ID = $currConfigRow->getNamedAttribute("id");
        $this->X = $currConfigRow->getNamedAttribute("x");
        $this->Y = $currConfigRow->getNamedAttribute("y");
        $this->OBJNAME = $currConfigRow->getNamedAttribute("name");
        $this->FUNK_ID = $currConfigRow->getNamedAttribute("funk_id");
        $this->FUNK_ID2 = $currConfigRow->getNamedAttribute("funk_id2");
        $this->BESCHREIBUNG = $currConfigRow->getNamedAttribute("beschreibung");
        $this->ART = $currConfigRow->getNamedAttribute("control_art");
        $this->ETAGE = $currConfigRow->getNamedAttribute("etage");
        $this->ZIMMER = $currConfigRow->getNamedAttribute("zimmer");
        $this->DIMMER = $currConfigRow->getNamedAttribute("dimmer");
        $this->PIC = $this->getIconPath();
        if (strlen($this->getIconPath()) <= 4){
            $this->PIC = "pics/homecontrol/steckdose_100.jpg";
        }
        $this->FUNKID2_NEED = $this->isFunkId2Needed() == "J";

        $this->EDIT_MODE = $editModus;
    }

    function isDimmable(){
        return $this->DIMMER=="J";
    }
 
    function getIconTooltip($configButtons = true) {
        $ttt = "<table cellspacing='10'><tr><td>" .$this->getControlArtIconSrc(false,80) ."</td><td><center><b>" . $this->OBJNAME .
            "</b></center><hr></td></tr>" . "<tr><td>Funk-Id: </td><td align='right'>" . $this->
            FUNK_ID . "</td></tr>";

        if ($this->FUNKID2_NEED && strlen($this->FUNK_ID2) > 0 && $this->FUNK_ID2 > 0) {
            $ttt .= "<tr><td>Funk-Id2: </td><td align='right'>" . $this->FUNK_ID2 .
                "</td></tr>";
        }

        $ttt .= "<tr><td colspan=2 height='1px'> </td></tr>";

        if($this->isDimmable()){
            $ttt .= "<tr><td>Dimmer-Level:</td><td>"
            ."<form action='".$_SERVER['SCRIPT_NAME']."'>"
            ."<select id='dimmer' name='dimmer' size='1' onchange=\"this.form.submit();\" >"
            ."<option value='0'></option>"
            ."<option value='1'>1</option>"
            ."<option value='2'>2</option>"
            ."<option value='3'>3</option>"
            ."<option value='4'>4</option>"
            ."<option value='5'>5</option>"
            ."<option value='6'>6</option>"
            ."<option value='7'>7</option>"
            ."<option value='8'>8</option>"
            ."<option value='9'>9</option>"
            ."<option value='10'>10</option>"
            ."<option value='11'>11</option>"
            ."<option value='12'>12</option>"
            ."<option value='13'>13</option>"
            ."<option value='14'>14</option>"
            ."<option value='15'>15</option>"
            ."<option value='16'>16</option>"
          ."</select>"
          ."<input id='schalte' name='schalte' type='hidden' value='".$this->FUNK_ID."'>"
          ."</form></td></tr>";
        }
        
        switch ($this->ART) {
            case 1:
                // Steckdosen
                $ttt .= "<tr><td><a href='". 
                    "?schalte=" . $this->FUNK_ID ."&tmstmp=".time(). "'><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:green;width:50;height:40;text-align:center;\" ><h1>AN</h1></div></a></td>" .
                    "<td align='right'><a href='".
                    "?schalte=-" . $this->FUNK_ID ."&tmstmp=".time(). "'><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:red;width:50;height:40;text-align:center;\" ><h1>AUS</h1></div></a></td></tr>" .
                    "</table>";
                break;

            case 2:
                // Jalousien
                $ttt .= "<tr><td><a href='" .
                    "?schalte=" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:orange;width:70;height:40;text-align:center;\" ><h1>AUF</h1></div></a></td>" .
                    "<td align='right'><a href='" .
                    "?schalte=-" . $this->FUNK_ID ."&tmstmp=".time(). "'><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:#7777ee;width:70;height:40;text-align:center;\" ><h1>ZU</h1></div></a></td></tr>" .
                    "</table>";
                break;

            case 3:
                // Glühbirne
                $ttt .= "<tr><td><a href='".
                    "?schalte=" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:green;width:70;height:40;text-align:center;\" ><h1>AN</h1></div></a></td>" .
                    "<td align='right'><a href='" .
                    "?schalte=-" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:red;width:70;height:40;text-align:center;\" ><h1>AUS</h1></div></a></td></tr>" .
                    "</table>";
                break;

            case 4:
                // Heizung
                $ttt .= "<tr><td><a href='".
                    "?schalte=-" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"spacing:1px;border-width:3px; border-style:solid;border-color:black;background-color:blue;width:100;height:40;text-align:center;\" ><h1>KALT</h1></div></a></td>" .
                    "<td align='right'><a href='".
                    "?schalte=" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"border-width:3px; border-style:solid;border-color:black;width:100;height:40;text-align:center;background-color:red;\" ><h1>WARM</h1></div></a></td></tr>" .
                    "</table>";
                break;

            default:
                $ttt .= "<tr><td><a href='".
                    "?schalte=" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:green;width:70;height:40;text-align:center;\" ><h1>AN</h1></div></a></td>" .
                    "<td align='right'><a href='" .
                    "?schalte=-" . $this->FUNK_ID ."&tmstmp=".time(). "' ><div style=\"border-width:3px; border-style:solid;border-color:black;background-color:red;width:70;height:40;text-align:center;\" ><h1>AUS</h1></div></a></td></tr>" .
                    "</table>";

        }


        //    $ttt .= "<tr><td colspan=2 height='10px'> </td></tr>";

        return $ttt;
    }


    function getSwitchButtons() {
        $tbl = new Table(array("","",""));
        $tbl->setStyle("position", "relative");
        $tbl->setStyle("left", "-17px");
        $tbl->setStyle("top", "-20px");
        $tbl->setAlignments(array("left", "right"));
        $tbl->setColSizes(array(40, 5, 40));
        $tbl->setBorder(0);
        $rowTtl = $tbl->createRow();
        $rowTtl->setVAlign("middle");

        $txtAn = null;
        $txtAus = null;

        switch ($this->ART) {
            case 1: // Steckdosen
            case 3: // Glühbirne
                $txtAn = new Text("AN", 3, true);
                $txtAus = new Text("AUS", 3, true);

                break;

            case 2: // Jalousien
                $txtAn = new Text("AUF", 3, true);
                $txtAus = new Text("ZU", 3, true);

                break;

            case 4: // Heizung
                $txtAn = new Text("WARM", 3, true);
                $txtAus = new Text("KALT", 3, true);

                break;

            default:
                $txtAn = new Text("AN", 3, true);
                $txtAus = new Text("AUS", 3, true);

        }


        $divAn = new Div();
        $divAn->add($txtAn);
        $divAn->setWidth(35);
        $divAn->setHeight(20);
        $divAn->setAlign("center");
        $divAn->setVAlign("middle");
        $divAn->setStyle("line-height", "20px");
        $divAn->setBorder(1);
        $divAn->setBackgroundColor("green");
        $divAn->setOverflow("hidden");

        $divAus = new Div();
        $divAus->setWidth(35);
        $divAus->setHeight(20);
        $divAus->setAlign("center");
        $divAus->setVAlign("middle");
        $divAus->setStyle("line-height", "20px");
        $divAus->add($txtAus);
        $divAus->setBorder(1);
        $divAus->setBackgroundColor("red");
        $divAus->setOverflow("hidden");

        $lnkAn = new Link("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'] .
            "?schalte=" . $this->FUNK_ID, $divAn, false, "arduinoSwitch");
        $lnkAus = new Link("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'] .
            "?schalte=-" . $this->FUNK_ID, $divAus, false, "arduinoSwitch");

        $rowTtl->setAttribute(0, $lnkAn);
        $rowTtl->setAttribute(1, " ");
        $rowTtl->setAttribute(2, $lnkAus);

        $tbl->addRow($rowTtl);

        return $tbl;
    }
   
    function getMobileSwitch() {
        $tbl = new Table(array("", "", "", "", ""));
        $tbl->setAlignments(array("center", "left", "left", "left", "right"));
        $tbl->setColSizes(array(60, "", 100, 160, 150));
        $tbl->setBorder(0);
        $rowTtl = $tbl->createRow();
        $rowTtl->setVAlign("middle");

        $txtAn = new Text("AN", 7, true);
        $txtAus = new Text("AUS", 7, true);


        switch ($this->ART) {
            case 1: // Steckdosen
            case 3: // Glühbirne
                $txtAn = new Text("AN", 7, true);
                $txtAus = new Text("AUS", 7, true);

                break;

            case 2: // Jalousien
                $txtAn = new Text("AUF", 7, true);
                $txtAus = new Text("ZU", 7, true);

                break;

            case 4: // Heizung
                $txtAn = new Text("WARM", 7, true);
                $txtAus = new Text("KALT", 7, true);

                break;

            default:
                $txtAn = new Text("AN", 7, true);
                $txtAus = new Text("AUS", 7, true);

        }


        $divAn = new Div();
        $divAn->add($txtAn);
        $divAn->setWidth(150);
        $divAn->setHeight(50);
        $divAn->setAlign("center");
        $divAn->setVAlign("middle");
        $divAn->setStyle("line-height", "50px");
        $divAn->setBorder(1);
        $divAn->setBackgroundColor("green");
        $divAn->setOverflow("hidden");

        $divAus = new Div();
        $divAus->setWidth(150);
        $divAus->setHeight(50);
        $divAus->setAlign("center");
        $divAus->setVAlign("middle");
        $divAus->setStyle("line-height", "50px");
        $divAus->add($txtAus);
        $divAus->setBorder(1);
        $divAus->setBackgroundColor("red");
        $divAus->setOverflow("hidden");


        $txtName = new Text($this->OBJNAME, 6, true);

        $img = $this->getControlArtIcon(false);

        $lnkAn = new Link("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'] .
            "?schalte=" . $this->FUNK_ID, $divAn, false, "arduinoSwitch");
        $lnkAus = new Link("http://" . $_SESSION['config']->PUBLICVARS['arduino_url'] .
            "?schalte=-" . $this->FUNK_ID, $divAus, false, "arduinoSwitch");
        
        $fDimmLvl="";
        if($this->isDimmable()){
            $fDimmLvl  = new Form();
            $cobDimmLvl = new Combobox("dimmer", getNumberComboArray(1,16),""," ");
            $cobDimmLvl->setDirectSelect(true);
            $cobDimmLvl->setStyle("font-size", "40px");
            $fDimmLvl->add($cobDimmLvl);
            $fDimmLvl->add(new Hiddenfield("schalte", $this->FUNK_ID));
        }
        
        $rowTtl->setAttribute(0, $img);
        $rowTtl->setAttribute(1, $txtName);
        $rowTtl->setAttribute(2, $fDimmLvl);
        $rowTtl->setAttribute(3, $lnkAn);
        $rowTtl->setAttribute(4, $lnkAus);
        
        $tbl->addRow($rowTtl);

        return $tbl;
    }

    function getIconPath() {
        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_art', array
            ("pic"), "", "", "", "id=" . $this->ART);
        $row = $dbTable->getRow(1);

        return $row->getNamedAttribute("pic");
    }
    
    function getPic(){
        // $_SESSION['config']->getImageFromCache($this->PIC)
        return $this->PIC;
    }

    function isFunkId2Needed() {
        $dbTable = new DbTable($_SESSION['config']->DBCONNECT, 'homecontrol_art', array
            ("zweite_funkid_jn"), "", "", "", "id=" . $this->ART);
        $row = $dbTable->getRow(1);

        return $row->getNamedAttribute("zweite_funkid_jn");
    }


    /**
     *  Liefert das Grafik-Symbol zurück (Image),
     *  welches zur Control-Art passt.
     */
    function getControlArtIcon($tooltipActive = true) {
        $lnkImg = new Image($this->PIC);
        $lnkImg->setWidth($this->CONTROL_IMAGE_WIDTH);

        if ($tooltipActive) {
            $ttt = $this->getIconTooltip();
            $lnkImg->setToolTip($ttt);
        }

        $lnkImg->setGenerated(false);
        return $lnkImg;
    }

    /**
     *  Liefert das Grafik-Symbol zurück (Image),
     *  welches zur Control-Art passt.
     */
    function getControlArtIconSrc($tooltipActive = true,$width=0) {
        $lnkImg = new Image($this->PIC);
        $lnkImg->setWidth($width==0?$this->CONTROL_IMAGE_WIDTH:$width);

        if ($tooltipActive) {
            $ttt = $this->getIconTooltip();
            $lnkImg->setToolTip($ttt);
        }
        $lnkImgSrc = $lnkImg->getImgSrc($this->PIC);

        return $lnkImgSrc;
    }

    function show() {
        if ($this->EDIT_MODE) {
            echo "<a href=\"?editControl=" . $this->ID . "\" style=\"position:absolute; left:" .
                $this->X . "px; top:" . ($this->Y + $_SESSION['additionalLayoutHeight']) .
                "px; width:" . $this->CONTROL_IMAGE_WIDTH . "px; height:" . $this->
                CONTROL_IMAGE_HEIGHT . "px;\">";
            echo $this->getControlArtIconSrc(false);
            echo "</a>";

        } else {
            echo "<div style=\"position:absolute; left:" . $this->X . "px; top:" . ($this->
                Y + $_SESSION['additionalLayoutHeight']) . "px; width:" . $this->
                CONTROL_IMAGE_WIDTH . "px; height:" . $this->CONTROL_IMAGE_HEIGHT . "px;\">";
            echo $this->getControlArtIconSrc();

            $this->getSwitchButtons()->show();            
            echo "</div>";
            
            if($this->DIMMER=="J"){
                $f  = new Form();
                $cobDimmLvl = new Combobox("dimmer", getNumberComboArray(1,16),""," ");
                $cobDimmLvl->setDirectSelect(true);
                $cobDimmLvl->setStyle("position", "absolute"); 
                $cobDimmLvl->setStyle("left", $this->X ."px"); 
                $cobDimmLvl->setStyle("top", $this->Y+$this->CONTROL_IMAGE_HEIGHT-3+$_SESSION['additionalLayoutHeight']."px"); 
                $f->add($cobDimmLvl);
                $f->add(new Hiddenfield("schalte",$this->FUNK_ID));
                $f->show();
            }

        }
    }


}

?>