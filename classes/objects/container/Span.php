<?php

/**
 * SPAN
 * 
 * @author  Daniel Scheidler
 * @copyright Mai 2008
 */

class Span extends Container {
    private $VISIBLE;
    private $TITLE;
    private $LOGACTIVE_HIDDEN_NAME;
    private $LINKACTIVE;

    /**
     * erzeugt ein Span-Objekt
     * 
     * @param $name Der Name der fÃÂÃÂÃÂÃÂ¼r das JS genutzt wird
     * @param $title Der Wert der als "ÃÂÃÂÃÂÃÂffnen-Link" angezeigt wird
     */
    function Span($name, $title, $LogActiveHiddenName = "") {
        $this->setFonttype(new FontType());
        $this->setVisible(false);
        $this->setLINKACTIVE(true);
        $this->NAME = $name;
        $this->LOGACTIVE_HIDDEN_NAME = $LogActiveHiddenName;
        $this->TITLE = $title;
        $this->setFontsize(4);
    }


    function setVisible($bool) {
        if ($bool === true) {
            $this->VISIBLE = true;
        } else {
            $this->VISIBLE = false;
        }
    }

    function isVisible() {
        return $this->VISIBLE;
    }


    function setLinkactive($bool) {
        if ($bool === true) {
            $this->LINKACTIVE = true;
        } else {
            $this->LINKACTIVE = false;
        }
    }

    function isLinkactive() {
        return $this->LINKACTIVE;
    }


    /**
     * liefert einen String zurÃÂÃÂÃÂÃÂ¼ck welcher den Link zum ÃÂÃÂÃÂÃÂffnen des Spans wiederspiegelt
     */
    function getOpenlinkString() {
        if (strlen($this->LOGACTIVE_HIDDEN_NAME) > 0) {
            return "<a href='#' onClick=\"makeVisible('" . $this->NAME .
                "');changeFieldValue(" . $this->LOGACTIVE_HIDDEN_NAME . ", " . $this->NAME . ");\" id='" .
                $this->NAME . "_link' title='" . $this->TITLE . "'><font size='" .$this->getFontsize()."'>" . $this->
                TITLE . "</font></a>";
        } else {
            return "<a href='#' onClick=\"makeVisible('" . $this->NAME . "');\" id='" . $this->
                NAME . "_link' title='" . $this->TITLE . "'><font size='" .$this->getFontsize()."'>" . $this->TITLE .
                "</font></a>";
        }
    }


    /**
     * liefert einen String zurÃÂÃÂÃÂÃÂ¼ck welcher den Link zum ÃÂÃÂÃÂÃÂffnen des Spans wiederspiegelt
     * Dieser Link wird nicht ausgeblendet!
     */
    function getNoHideOpenlinkString() {
        if (strlen($this->LOGACTIVE_HIDDEN_NAME) > 0) {
            return "<a href='#' onClick=\"makeVisibleWithoutLink('" . $this->NAME .
                "');changeFieldValue('" . $this->LOGACTIVE_HIDDEN_NAME . "', '" . $this->NAME .
                "');\" id='" . $this->NAME . "_link' title='" . $this->TITLE .
                "'><font size='" .$this->getFontsize()."'>" . $this->TITLE . "</font></a>";
        } else {
            return "<a href='#' onClick=\"makeVisibleWithoutLink('" . $this->NAME . "');\" id='" .
                $this->NAME . "_link' title='" . $this->TITLE . "'><font size='" .$this->getFontsize()."'>" . $this->
                TITLE . "</font></a>";
        }
    }


    /**
     * liefert ein Array zurÃÂÃÂÃÂÃÂ¼ck welches den Link zum ÃÂÃÂÃÂÃÂffnen des Spans wiederspiegelt
     * 
     * Das Array ist nach folgender Struktur aufgebaut:
     * 
     * array(HREF, ONCLICK, ID, TITEL, TEXT)
     * 
     * Dieser Link wird nicht ausgeblendet!
     */
    function getNoHideOpenlinkAsArray() {
        if (strlen($this->LOGACTIVE_HIDDEN_NAME) > 0) {
            return array("#", "makeVisibleWithoutLink('" . $this->NAME .
                "');changeFieldValue('" . $this->LOGACTIVE_HIDDEN_NAME . "', '" . $this->NAME .
                "');", $this->NAME . "_link", $this->TITLE, $this->TITLE);
        } else {
            return array("#", "makeVisibleWithoutLink('" . $this->NAME . "');", $this->NAME .
                "_link", $this->TITLE, $this->TITLE);
        }
    }


    function show() {
        if ($this->isLinkactive() && !$this->isVisible()) {
            echo $this->getOpenlinkString();
        }

        echo "<span id='" . $this->NAME . "_toggle' style='display:";
        echo "none;";

        //if (strlen($this->getBackgroundColor()) > 0) {
           echo "background-color:#e1e1e1;padding:5px;";
        //}

        echo "'>" . "<center>";

        if ($this->isLinkactive() && !$this->isVisible()) {
 

            echo "<a href='#' onClick=\"makeVisible('" . $this->NAME . "');\" >
                <div align='left'>";

            $t = new Text($this->NAME, $this->getFontsize(), true);
            $t->show();

            echo "  </div>
                 <div align='right'>
                   <font size='" .$this->getFontsize()."'>[schliessen]</font>
			     </div>
               </a>
         ";
        }

        if (count($this->OBJECTS) > 0) {
            foreach ($this->OBJECTS as $obj) {
                if (method_exists($obj, "show")) {
                    $obj->show();
                }
            }
        }

        echo "</center>";

        echo "
	       </span>
	 ";

        if ($this->isVisible()) {
            if ($this->isLinkactive()) {
                echo "<script>makeVisible('" . $this->NAME . "');</script>";
            } else {
                echo "<script>makeVisibleWithoutLink('" . $this->NAME . "');</script>";
            }
        }

    }


}

?>