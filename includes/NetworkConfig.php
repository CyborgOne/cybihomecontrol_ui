<?PHP
  $ttl = new Title("Netzwerk-Einstellungen");
  $ttl->setAlign("left");
  $ttl->show();
  
  $spc = new Spacer(10);
  $spc->show();
  
  $netConfigEditor = new NetworksettingsEditor();
  $netConfigEditor->show();
?>