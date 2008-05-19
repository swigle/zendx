<?php

require_once('Zend/Json.php');
require_once('ZendX/Http/Response/Handler/Interface.php');

class ZendX_Http_Response_Handler_Json implements ZendX_Http_Response_Handler_Interface {
	
	private $outputStream;
	private $rawResponse;
	private $response;
	
	public function __construct() {
		$this->outputStream = 'php://output';
		$this->rawResponse = null;
		$this->response = '';
	}
	
	/**
	 * Gets the response
	 * @return the response 
	 */
	public function getResponse() {
		return $this->response;
	}	
	
	/**
	 * Gets the raw unencoded response object
	 * @return the response object
	 */
	public function getRawResponse() {
		return $this->rawResponse;
	}
	
	/**
	 * Sets the response
	 * @return the response object
	 */
	public function setResponse($response) {
		$this->rawResponse = $response;
		$this->response = Zend_Json::encode($response);
	}
	
	/**
	 * Gets only the response header
	 * @return the response header 
	 */
	public function getResponseHeader() {
		return null;
	}	
	
	/**
	 * Gets the output stream definition that is in use
	 * @return a php stream (e.g. php://output)
	 */
	public function getOutputStream() {
		return $this->outputStream;
	}
	
	/**
	 * Sets the output stream 
	 * @param $stream a php stream string (e.g. php://output) 
	 */
	public function setOutputStream($stream) {
		$this->outputStream = $stream;
	}
	
	/**
	 * Flushes the response buffer 
	 */
	public function flush() {
		// TODO:  need to write response buffer to output stream here
		 
		$this->rawResponse = null;
		$this->response = '';
	}

	/**
	 * Handles the outgoing response
	 * @return 
	 */
	public function handle($stream = null, $response = null) {
		if ($stream != null) {
			$this->setOutputStream($stream);
		}
		
		if ($response != null) {
			$this->setResponse($response);
		}
		
		// write response to output stream
		file_put_contents($this->outputStream, $this->response);
	}
	
}
