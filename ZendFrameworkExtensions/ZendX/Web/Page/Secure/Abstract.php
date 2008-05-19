<?php

require_once('Zend/Auth.php');
require_once('Zend/Json.php');
require_once('ZendX/Web/Page/Abstract.php');
require_once('ZendX/Web/Page/Exception.php');

class ZendX_Web_Page_Secure_Abstract extends ZendX_Web_Page_Abstract {
	
	private $auth;
	private $privileged;
	
	protected $rurl;
	
	/**
	 * Constructs a secure web page instance
	 * @param $privileged boolean[optional] Specify whether user must be logged 
	 * in or not in order to view the page. This allows for gateway, or mixed 
	 * mode pages (such as a login page) to still display even though the user 
	 * is not yet logged in. Setting $privileged to true requires that the user 
	 * be logged in or else the page will redirect to provided return url. 
	 * Warning: if a page is marked is privileged and no rurl is supplied a
	 * ZendX_Web_Page_Exception will be thrown. Defaults to false. 
	 * @param $rurl string[optional] a return URL to redirect to in the event
	 * that the page is set to private and the user is not logged in. Defaults
	 * to "".
	 */
	public function __construct($privileged = false, $rurl = '') {
		parent::__construct();
	
		$this->auth = Zend_Auth::getInstance();	
		
		$this->privileged = $privileged;
		if ($privileged) {
			if (strlen($rurl) > 0) {
				$this->rurl = $rurl;	// TODO: some further regex testing to determine if it's a valid URL
			} else {
				throw new ZendX_Web_Page_Exception(
					'No return URL specified for privileged page: '
					. $this->getScriptPath());			
			}
		
			// since page is privileged, user is required to be logged in
			// prior to viewing the page. Check and redirect if they're not.
			if (!$this->userLoggedIn()) {
				header("Location: {$this->rurl}");
			}
		}
	}
	
	public final function isPrivileged() {
		return $this->privileged;	
	}
	
	public final function userLoggedIn() {
		return $this->auth->hasIdentity();
	}
	
	public final function getUserIdentity($format = null) {
		$identity = $this->auth->getIdentity();
		
		if ($format == null) {
			// return raw object
			return $identity;
		} else if ($format == 'json') {
			// return as JSON formatted object
			return Zend_Json::encode($identity);
		}
	}
	
}
