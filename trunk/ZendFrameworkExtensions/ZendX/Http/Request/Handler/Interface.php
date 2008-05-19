<?php
   
interface ZendX_Http_Request_Handler_Interface {
	
	/**
	 * Gets the processed request
	 * @return the request 
	 */
	public function getRequest();
	
	/**
	 * Gets the raw POST body of the request
	 * @return a string containing the raw request data
	 */
	public function getRawRequest();
	
	/**
	 * Gets the request header
	 * @return the request header
	 */
	public function getRequestHeader();
	
	/**
	 * Gets the current input stream definition
	 * @return a php stream string (e.g. php://input)
	 */
	public function getInputStream();
	
	/**
	 * The input stream to read requests from
	 * @param $stream a php stream string (e.g. php://input)
	 */
	public function setInputStream($stream);
	
	/**
	 * Flushes the request buffer 
	 */
	public function flush();

	/**
	 * Handles the incoming request
	 * @return 
	 */
	public function handle($stream = null);
}
