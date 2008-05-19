<?php

require_once('Zend/Json.php');
require_once('ZendX/Http/Request/Handler/Interface.php');

class ZendX_Http_Request_Handler_Json implements ZendX_Http_Request_Handler_Interface {
	
	const STD_INPUT_STREAM = 'php://input';
	
	private $inputStream;
	private $rawRequest;
	private $request;
	
	public function __construct() {		
		$this->inputStream = self::STD_INPUT_STREAM;
		$this->rawRequest = '';
		$this->request = null;
	}
	
	public function getInputStream() {
		return $this->inputStream();	
	}
	
	public function setInputStream($stream) {
		$this->inputStream = $stream;
	}
	
	public function getRawRequest() {
		return $this->rawRequest;
	}
	
	public function getRequest() {
		return $this->request;
	}
	
	public function getRequestHeader() {
		// TODO: add some real handling here
		return null;
	}
	
	public function flush() {
		$this->rawRequest = '';
		$this->request = null;
	}
	
	public function handle($stream = null) {
		if ($stream != null) {
			$this->setInputStream($stream);
		}
		
		$this->rawRequest = file_get_contents($this->inputStream);	
		$this->request = Zend_Json::decode($this->rawRequest);
	}
	
}
