<?PHP



class HomeControlAlarmgeber{
    
    private $ID = 0;
    private $X = 0;
    private $Y = 0;
    private $OBJNAME = "";
    private $IP = "";
    private $ART = 0;
    private $ETAGE = 0;
    private $ZIMMER = 0;
    private $PIC = "";

    private $EDIT_MODE = false;

    private $CONTROL_IMAGE_WIDTH = 40;
    private $CONTROL_IMAGE_HEIGHT = 40;

    function HomeControlAlarmgeber($currConfigRow, $editModus) {
        $this->ID = $currConfigRow->getNamedAttribute("id");
        $this->OBJNAME = $currConfigRow->getNamedAttribute("name");
        $this->IP = $currConfigRow->getNamedAttribute("ip");
        $this->X = $currConfigRow->getNamedAttribute("x");
        $this->Y = $currConfigRow->getNamedAttribute("y");
        $this->ETAGE = $currConfigRow->getNamedAttribute("etage_id");
        $this->ZIMMER = $currConfigRow->getNamedAttribute("zimmer_id");

        $this->ART = $currConfigRow->getNamedAttribute("control_art");

        $this->PIC = $this->getIconPath();
        if (strlen($this->getIconPath()) <= 4){
            $this->PIC = "pics/homecontrol/sirene.png";
        }

        $this->EDIT_MODE = $editModus;
    }


    function getIconTooltip($configButtons = true) {
        $ttt = "<table cellspacing='10'><tr><td colspan=2><center><b>" . $this->OBJNAME .
            "</b></center><hr></td></tr>" . "<tr><td>Ip: </td><td align='right'>" . $this->
            IP . "</td></tr>";

         $ttt .= "<tr><td colspan=2 height='1px'> </td></tr>";

         $ttt .= "<tr><td>" .$this->getControlArtIconSrc() ."</td></tr>" .
                 "</table>";

        return $ttt;
    }


    function getMobileSwitch() {

        $tbl = new Table(array("", "", "", ""));
        $tbl->setAlignments(array("center", "left", "left", "right"));
        $tbl->setColSizes(array(60, "", 170, 150));
        $tbl->setBorder(0);
        $rowTtl = $tbl->createRow();
        $rowTtl->setVAlign("middle");

        $txtAn = new Text("AN", 7, true);
        $txtAus = new Text("AUS", 7, true);


        $divAn = new Div();
        $divAn->add($txtAn);
        $divAn->setWidth(150);
        $divAn->setHeight(50);
        $divAn->setAlign("center");
        $divAn->setVAlign("middle");
        $divAn->setStyle("line-height", "50px");
        $divAn->setBorder(1);
        $divAn->setBackgroundColor("green");

        $divAus = new Div();
        $divAus->setWidth(150);
        $divAus->setHeight(50);
        $divAus->setAlign("center");
        $divAus->setVAlign("middle");
        $divAus->setStyle("line-height", "50px");
        $divAus->add($txtAus);
        $divAus->setBorder(1);
        $divAus->setBackgroundColor("red");


        $txtName = new Text($this->OBJNAME, 6, true);

        $img = $this->getControlArtIcon(false);

        $lnkAn = new Link("http://" .$this->IP."?schalte=on", $divAn, false, "arduinoSwitch");
        $lnkAus = new Link("http://" .$this->IP."?schalte=off", $divAus, false, "arduinoSwitch");

        $rowTtl->setAttribute(0, $img);
        $rowTtl->setAttribute(1, $txtName);
        $rowTtl->setAttribute(2, $lnkAn);
        $rowTtl->setAttribute(3, $lnkAus);

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

    /**
     *  Liefert das Grafik-Symbol zur�ck (Image),
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
     *  Liefert das Grafik-Symbol zur�ck (als HTML String),
     *  welches zur Alarmgeber-Art passt.
     */
    function getControlArtIconSrc($tooltipActive = true) {
        $lnkImg = new Image($this->PIC);
        $lnkImg->setWidth($this->CONTROL_IMAGE_WIDTH);

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
            echo "</div>";
        }
    }


}

?>