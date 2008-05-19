<?php

abstract class ZendX_Web_Page_Abstract {
	
	private $scriptPath;
	
	public function __construct() {
		$this->scriptPath = $_SERVER['PHP_SELF'];
	}
	
	public final function getScriptPath() {
		return $this->scriptPath;
	}
	
	public final function printScriptPath() {
		print($this->getScriptPath());
	}
	
}
