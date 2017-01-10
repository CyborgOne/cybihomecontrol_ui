<?php

/**
 * @author 
 * @copyright 2008
 */


class Spacer extends Object{
	
	
	function Spacer($h = "20"){
		$this->setHeight($h);
	}
	
	function show(){
		$t = new Table(array());
		$t->setHeight($this->getHeight());
		$r = $t->createRow();
		$r->setHeight($this->getHeight());
		$t->addRow($r);
		$t->show();
	}
	
}

?>