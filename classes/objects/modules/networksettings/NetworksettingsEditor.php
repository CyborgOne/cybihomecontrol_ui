<?PHP

class NetworksettingsEditor{
  
  private  $SAVE_BTN_LABEL = "Netzwerkeinstellungen Speichern";
  
  function getNetworkSettingsMask(){
    $tbl = new Table(array("",""));
    
    $rIp = $tbl->createRow();
    $rIp->setAttribute(0, new Text("IP:"));
    $rIp->setAttribute(1, new Textfield("ip", $this->getLocalIp()));
    $tbl->addRow($rIp);
    
    $rMask = $tbl->createRow();
    $rMask->setAttribute(0, new Text("Subnet-Mask:"));
    $rMask->setAttribute(1, new Textfield("mask", $this->getLocalMask()));
    $tbl->addRow($rMask);
    
    $rGate = $tbl->createRow();
    $rGate->setAttribute(0, new Text("Gateway:"));
    $rGate->setAttribute(1, new Textfield("gate", $this->getLocalGate()));
    $tbl->addRow($rGate);
    
    $rDns = $tbl->createRow();
    $rDns->setAttribute(0, new Text("DNS-Server:"));
    $rDns->setAttribute(1, new Textfield("dns", $this->getLocalDns()));
    $tbl->addRow($rDns);
    
    $rOk = $tbl->createRow();
    $rOk->setSpawnAll(true);
    $rOk->setAttribute(0, new Button("saveNetworkSettingsMask", $this->SAVE_BTN_LABEL));
    $tbl->addRow($rOk);    
    
    $f = new Form();
    $f->add($tbl);
    
    return $f;
  }
 
 

  private function getLocalIp(){
    $defaultIp = "192.168.1.99";
    $ip = findInFileBehind("/etc/network/interfaces", "address");
    
    return $ip!=null?$ip:$defaultIp;
  }
 
  private function getLocalMask(){
    $defaultIp = "255.255.255.0";
    $ip = findInFileBehind("/etc/network/interfaces", "netmask");
    
    return $ip!=null?$ip:$defaultIp;
  }

  private function getLocalGate(){
    $defaultIp = "192.168.1.1";
    $ip = findInFileBehind("/etc/network/interfaces", "gateway");
    
    return $ip!=null?$ip:$defaultIp;
  }
 
  private function getLocalDns(){
    $defaultIp = "192.168.1.1";
    $ip = findInFileBehind("/etc/network/interfaces", "dns-nameservers");
    
    return $ip!=null?$ip:$defaultIp;
  }

  private function setNetwork($ip,$mask,$gateway,$dns){
    $file=fopen("/etc/network/interfaces",'w');
    fwrite($file,"auto lo eth0\n");
    fwrite($file,"iface lo inet loopback\n");
    fwrite($file,"iface eth0 inet static\n");
    fwrite($file,"  address ".$ip."\n");
    fwrite($file,"  netmask ".$mask."\n");
    fwrite($file,"  gateway ".$gateway."\n");
    fwrite($file,"  dns-nameservers ".$dns."\n");
    fclose($file);
  }
  
  function show(){
    if(isset($_REQUEST['saveNetworkSettingsMask']) && $_REQUEST['saveNetworkSettingsMask']==$this->SAVE_BTN_LABEL){
        $err = false;
        $errMsg = "";
        
        if(!isset($_REQUEST['ip']) || strlen($_REQUEST['ip'])<=0 ){
           $err=true; 
           $errMsg = "IP muss angegeben werden!";
        }
                 
        if(!isset($_REQUEST['mask']) || strlen($_REQUEST['mask'])<=0 ){
           $err=true; 
           $errMsg = "Subnet-Mask muss angegeben werden!";
        }      
               
        if(!isset($_REQUEST['gate']) || strlen($_REQUEST['gate'])<=0 ){
           $err=true; 
           $errMsg = "Gateway muss angegeben werden!";
        }   
               
        if(!isset($_REQUEST['dns']) || strlen($_REQUEST['dns'])<=0 ){
           $err=true; 
           $errMsg = "DNS-Server muss angegeben werden!";
        }
        
        if($err){
            $e = new Error("Ungültige Eingabe", $errMsg); 
        } else {
            $this->setNetwork($_REQUEST['ip'],$_REQUEST['mask'],$_REQUEST['gate'],$_REQUEST['dns']);
            
            $tSaved = new Text("Einstellungen gespeichert!<br>");
            $tSaved->setFilter(false);
            $ft = $tSaved->getFonttype();
            $ft->setColor("red");
            $ft->setFontsize(4);
            $tSaved->setFonttype($ft);
            
            $msk = $this->getNetworkSettingsMask();
            $msk->add($tSaved);
            $msk->add(new Text("Einstellungen werden erst nach einem Neustart aktiv!"));
            $msk->show();
        }
    } else {
        $msk = $this->getNetworkSettingsMask(isset($_REQUEST['ip'])?$_REQUEST['ip']:"0.0.0.0", isset($_REQUEST['mask'])?$_REQUEST['mask']:"0.0.0.0",isset($_REQUEST['gate'])?$_REQUEST['gate']:"0.0.0.0",isset($_REQUEST['dns'])?$_REQUEST['dns']:"0.0.0.0");
        $msk->show();
    }
 }
}

?>