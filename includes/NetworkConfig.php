<?PHP
  $ttl = new Title("Netzwerk-Einstellungen");
  $ttl->show();
  
  $netConfigEditor = new NetworksettingsEditor();
  $netConfigEditor->show();
?>