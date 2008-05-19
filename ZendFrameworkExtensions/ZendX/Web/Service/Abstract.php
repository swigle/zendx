<?php

require_once('ZendX/Http/Request/Handler/Interface.php');
require_once('ZendX/Http/Response/Handler/Interface.php');
require_once('ZendX/Http/Request/Handler/Json.php');
require_once('ZendX/Http/Response/Handler/Json.php');
require_once('ZendX/Web/Service/Exception.php');

abstract class ZendX_Web_Service_Abstract {
	
	protected $requestHandler;
	protected $responseHandler;
	
	public function __construct(
			ZendX_Http_Request_Handler_Interface $requestHandler = null, 
			ZendX_Http_Response_Handler_Interface $responseHandler = null) {
		// create default handler if one not provided
		if ($requestHandler == null) {
			$requestHandler = new ZendX_Http_Request_Handler_Json();	
		}
		
		// create default handler if one not provided
		if ($responseHandler == null) {
			$responseHandler = new ZendX_Http_Response_Handler_Json();
		}
		
		$this->setRequestHandler($requestHandler);
		$this->setResponseHandler($responseHandler);
	}
	
	public function getRequest() {
		return $this->requestHandler->getRequest();
	}
	
	public function getRawRequest() {
		return $this->requestHandler->getRawRequest();
	}
	
	public function getRequestHandler() {
		return $this->requestHandler;
	}
	
	public function setRequestHandler(
			ZendX_Http_Request_Handler_Interface $handler) {
		$this->requestHandler = $handler;
	}
	
	public function getResponseHandler() {
		return $this->responseHandler;
	}
	
	public function setResponseHandler(
			ZendX_Http_Response_Handler_Interface $handler) {
		$this->responseHandler = $handler;
	}
	
	public function getRequestedMethod() {
		if (isset($_GET['mn'])) {
			return $_GET['mn'];
		}
		return null;
	}
	
	protected final function invoke($name, $request) {
		$class = new ReflectionClass($this); 
 
 		$errorMsg = "WebMethodInvocationException: The web method <$name> "
			. "cannot be found for the requested service - {$class->getName()}";
		
 		// attempt to invoke the requested method
		try {
			if ($method = $class->getMethod($name)) {
				if ($method->isPublic() && !$method->isAbstract()) {
					// invoke the requested method, then route the return 
					// value to the response handler automagically
					if ($request == null) {
						$result = $method->invoke($this);
					} else {
						$result = $method->invokeArgs($this, $request);
					}
					$this->send($result);
					return $result;					
				} else {
					throw new ZendX_Web_Service_Exception($errorMsg);
				}
			}	
		} catch (ReflectionException $ex) {
			throw new ZendX_Web_Service_Exception($errorMsg);
		}
			
	}
	
	protected function send($response = null) {
		$this->requestHandler->flush();
		$this->responseHandler->setResponse($response);
		$this->responseHandler->handle();
	}
	
	public function handle() {
		$this->requestHandler->flush();
		$this->requestHandler->handle();
		$this->invoke($this->getRequestedMethod(), $this->getRequest());
	}
	
} 