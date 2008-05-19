<?php

interface ZendX_Http_Response_Handler_Interface {
	
	/**
	 * Gets the serialized response
	 * @return the response
	 */
	public function getResponse();	
	
	/**
	 * Gets the raw unencoded response object
	 * @return the response object
	 */
	public function getRawResponse();
	
	/**
	 * Sets the response
	 * @return the response 
	 */
	public function setResponse($response);	
	
	/**
	 * Gets only the response header
	 * @return the response header 
	 */
	public function getResponseHeader();	
	
	/**
	 * Gets the output stream definition that is in use
	 * @return a php stream (e.g. php://output)
	 */
	public function getOutputStream();
	
	/**
	 * Sets the output stream 
	 * @param $stream a php stream string (e.g. php://output) 
	 */
	public function setOutputStream($stream);
	
	/**
	 * Flushes the response buffer 
	 */
	public function flush();

	/**
	 * Handles the outgoing response
	 * @param $stream string[optional] a php stream string (e.g. php://output)
	 * @param $response Object[optional] the response
	 */
	public function handle($stream = null, $response = null);
}
