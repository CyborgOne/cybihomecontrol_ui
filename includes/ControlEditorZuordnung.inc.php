<?PHP

$ttl = new Title("Parameter-Editoren zuordnen");

$cobEditoren = new ComboBoxBySql($_SESSION['config']->DBCONNECT, "SELECT id, name FROM homecontrol_editoren", "editorChoosenToAdd");
$cobEditoren->setDirectSelect(true);

$btnChooseEditorToAdd = new Button("chooseEditorToAdd", "Hinzuf&uuml;gen");

$editorAddPanel = new Form();
$editorAddPanel->add($cobEditoren);
$editorAddPanel->add($btnChooseEditorToAdd);


// ----------------------------

$tbl = new Table(array("",""));

$rTtl = $tbl->createRow();
$rTtl->setSpawnAll(true);
$rTtl->setAttribute(0, $ttl);
$tbl->addRow($rTtl);


$rTtl = $tbl->createRow();
$rTtl->setAttribute(0, $editorAddPanel);
$tbl->addRow($rTtl);


// ----------------------------

$frm = new Form();

$frm->add($tbl);

$frm->show();

?>