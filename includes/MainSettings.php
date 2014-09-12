<?PHP
if ( $_SESSION['config']->CURRENTUSER->STATUS != "admin" && $_SESSION['config']->CURRENTUSER->STATUS != "user" ) {
            
   /* ------------------------------------
      BENUTZERSTATUS ANZEIGEN
    ------------------------------------ */
    $USR = $_SESSION['config']->CURRENTUSER;
     
    $USERSTATUS = new UserStatus($USR, -1, -1);
    
    $tbl = new Table( array("") );
    $tbl->setAlign("center");
    $r = $tbl->createRow();
    $r->setAttribute( 0, $USERSTATUS );
    $tbl->addRow( $r );
    
    $tbl->show();
    /* --------------------------------- */


} else {

    
    $ttl = new Title("Basis-Einstellungen");
    $ttl->show();
    
    $configDb  = new DbTable($_SESSION['config']->DBCONNECT,
                            'pageconfig', 
                            array("name", "value") , 
                            "Bezeichnung , Wert",
                            "",
                            "name",
                            "name IN ('pagetitel', 'arduino_url', 'NotifyTargetMail')");
    
    if( isset($_REQUEST['DbTableUpdate'.$configDb->TABLENAME]) && $_REQUEST['DbTableUpdate'.$configDb->TABLENAME] == "Speichern" ){
      $configDb->doUpdate();
    }
    
    $updMsk = $configDb->getUpdateAllMask();
    
    // Standard-Update Tabellen-Objekt zum bearbeiten holen
    $updTbl = $updMsk->getAttribute(2);
    
    foreach ($updTbl->ROWS as $row) {
        // Textfeld gegen normalen Text austauschen
        $obj0 = $row->getAttribute(0);
        $txtLabel = new Text($obj0->getText());
        $row->setAttribute(0, $txtLabel);
    
        // Textarea gegen Textfeld des Wertes ändern
        $obj1 = $row->getAttribute(1);
        $txtWert = new Textfield($obj1->getName(), $obj1->getText(), 100);
        $row->setAttribute(1, $txtWert);
    }
    
    $updMsk->show();
}

?>