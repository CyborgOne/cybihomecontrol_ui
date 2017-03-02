<?PHP

class ShortcutSidebar extends Object {
    public $LAYOUT_ART_DESKTOP = "DESKTOP";
    public $LAYOUT_ART_MOBILE = "MOBILE";

    private $LAYOUT_ART;
    private $SHORTCUTS_DB;

    private $SHORTCUTS_ROW_COLOR1 = "#dedede";
    private $SHORTCUTS_ROW_COLOR2 = "#cdcdcd";
    private $SHORTCUTS_ROW_COLOR_LAST;

    function ShortcutSidebar($LAYOUT_ART = "DESKTOP") {
        $this->LAYOUT_ART = $LAYOUT_ART;

        $this->SHORTCUTS_DB = new DbTable($_SESSION['config']->DBCONNECT,
            'homecontrol_shortcut', array("id", "name", "beschreibung"),
            "Id, Name, Beschreibung", "", "name", "show_shortcut='J'");
    }




    /**
     * Shortcut-Sidebar anzeigen 
     * (Standard-Anzeige-Methode)
     */
    function show() {
        if ($this->LAYOUT_ART == $this->LAYOUT_ART_MOBILE) {
            $this->showMobile();
            return;
        }
        
        $dvSc = new Div();
        $dvSc->setWidth("100%");

        $title = new Title("Shortcuts");

        $spc = new Line();

        $dvSc->add(new Spacer(20));
        $dvSc->add($title);
        
        $tblItems = new Table(array("", ""));
        $tblItems->setBackgroundColorChange(true);
        $tblItems->setColSizes(array("30"));
        $tblItems->addRow($tblItems->createRow());
        $tblItems->addSpacer(0,15);
        $tblItems->addRow($tblItems->createRow());
        
        foreach ($this->SHORTCUTS_DB->ROWS as $shortcutRow) {
            $cronName = new Title($shortcutRow->getNamedAttribute("name"));
            $cronName->setAlign("left");            
            $shortcut = new HomeControlShortcut($shortcutRow);

            $s = new Span($shortcut->getName(), $shortcut->getName());
            $s->setFontsize(3);
            
            $itemRows = $shortcut->getItemRowsForShortcut();

            if (count($itemRows)>0) {
                // aktiver Link mit konfigurierten Items
                foreach($itemRows as $itemRow){
                    $itm = new HomeControlItem($itemRow);
                    
                    $tblParams = new Table(array("", ""));
                    $rParam = $tblParams->createRow();
                    $rParam->setSpawnAll(true);
                    $rParam->setAttribute(0, new Text($itm->getName(), 2, true));
                    $tblParams->addRow($rParam);

                    $params = $itm->getAllParameter();
                   
                    foreach($params as $param){
                        $value = "";
                        if((!$param->isFix()||$param->isDefaultLogic()) && (!$param->isOptional() || $itm->isParameterOptionalActive($param->getId()))){
                            $value = $itm->getParameterValueForShortcut($param->getRow(), $shortcut->getId());
            
                            if($param->isDefaultLogic()){
                                $tAn=$itm->getDefaultLogicAnText();
                                $tAus=$itm->getDefaultLogicAusText();

                                $value = $value==$tAn?$tAn:$tAus;
                            } 
                
                            $rParam = $tblParams->createRow();
                            $rParam->setAttribute(0, $param->getName());
                            $rParam->setAttribute(1, $value);
                            $tblParams->addRow($rParam);
                        }
                    }

                    $tblParams->addSpacer(0,6);

                    $s->add($tblParams);
                }

                $frmRun = new Form();
                $frmRun->add(new Button("switchShortcut", " "));
                $frmRun->add(new Hiddenfield("doShortcutId", $shortcut->getId()));

                $rCron = $tblItems->createRow();
                $rCron->setHeight(30);
                $rCron->setVAlign("middle");
                $rCron->setStyle("padding-left", "3px");
                $rCron->setAttribute(0, $frmRun);
                $rCron->setAttribute(1, $s);
                $tblItems->addRow($rCron);

            } else {
                // inaktiv (Keine konfigurierten Items)
                $txt = new Text($shortcutRow->getNamedAttribute("name"), 3, false);
                $txt->setTooltip("Noch keine Konfiguration hinterlegt");

                $rCron = $tblItems->createRow();
                $rCron->setSpawnAll(true);
                $rCron->setAttribute(0, $frmRun);
                $tblItems->addRow($rCron);
            }

            $tblItems->addSpacer(0,10);
            $tblItems->addRow($tblItems->createRow());
        }
        
        $dvSc->add($tblItems);

        $dvSc->show();
    }


    function showMobile() {
        
        $dvSc = new Div();
        $dvSc->setWidth("100%");

        $title = new Title("Shortcuts",0,7);

        $spc = new Line();

        $dvSc->add(new Spacer(20));
        $dvSc->add($title);
        
        $tblItems = new Table(array("", ""));
        $tblItems->setBackgroundColorChange(true);
        $tblItems->setColSizes(array("130"));
        $tblItems->addRow($tblItems->createRow());
        $tblItems->addSpacer(0,15);
        $tblItems->addRow($tblItems->createRow());
        
        foreach ($this->SHORTCUTS_DB->ROWS as $shortcutRow) {
            $cronName = new Title($shortcutRow->getNamedAttribute("name"));
            $cronName->setAlign("left");            
            $shortcut = new HomeControlShortcut($shortcutRow);

            $s = new Span($shortcut->getName(), $shortcut->getName());
            $s->setFontsize(7);
            
            $itemRows = $shortcut->getItemRowsForShortcut();

            if (count($itemRows)>0) {
                // aktiver Link mit konfigurierten Items
                foreach($itemRows as $itemRow){
                    $itm = new HomeControlItem($itemRow);
                    
                    $tblParams = new Table(array("", ""));
                    $rParam = $tblParams->createRow();
                    $rParam->setSpawnAll(true);
                    $rParam->setAttribute(0, new Text($itm->getName(), 5, true));
                    $tblParams->addRow($rParam);

                    $params = $itm->getAllParameter();
                   
                    foreach($params as $param){
                        $value = "";
                        if((!$param->isFix()||$param->isDefaultLogic()) && (!$param->isOptional() || $itm->isParameterOptionalActive($param->getId()))){
                            $value = $itm->getParameterValueForShortcut($param->getRow(), $shortcut->getId());
            
                            if($param->isDefaultLogic()){
                                $tAn=$itm->getDefaultLogicAnText();
                                $tAus=$itm->getDefaultLogicAusText();

                                $value = $value==$tAn?$tAn:$tAus;
                            } 
                
                            $rParam = $tblParams->createRow();
                            $rParam->setColSizes(array("400"));
                            $rParam->setAttribute(0, new Text($param->getName(), 5));
                            $rParam->setAttribute(1, new Text($value, 5));
                            $tblParams->addRow($rParam);
                        }
                    }

                    $tblParams->addSpacer(0,20);

                    $s->add($tblParams);
                }

                $frmRun = new Form();
                $frmRun->add(new Button("switchShortcut", " "));
                $frmRun->add(new Hiddenfield("doShortcutId", $shortcut->getId(),0,0));

                $rCron = $tblItems->createRow();
                $rCron->setHeight(50);
                $rCron->setVAlign("middle");
                $rCron->setStyle("padding-left", "3px");
                $rCron->setStyle("padding-top", "15px");
                $rCron->setAttribute(0, $frmRun);
                $rCron->setAttribute(1, $s);
                $tblItems->addRow($rCron);

            } else {
                // inaktiv (Keine konfigurierten Items)
                $txt = new Text($shortcutRow->getNamedAttribute("name"), 3, false);
                $txt->setTooltip("Noch keine Konfiguration hinterlegt");

                $rCron = $tblItems->createRow();
                $rCron->setSpawnAll(true);
                $rCron->setAttribute(0, $frmRun);
                $tblItems->addRow($rCron);
            }

            $tblItems->addSpacer(0,10);
            $tblItems->addRow($tblItems->createRow());
        }
        
        $dvSc->add($tblItems);

        $dvSc->show();
    }

}

?>