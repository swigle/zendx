<?php

require_once('ZendX/Util/Collection/Interface.php');
require_once('ZendX/Util/List/Abstract.php');

abstract class ZendX_Util_List_Sequential_Abstract extends ZendX_Util_List_Abstract {

	protected function __construct() {
	}
	
	public function get($index) {
		try {
			return $this->listIterator($index)->next();
		// TODO: implement custom exceptions when I can be bothered
		} catch (Zend_Exception $ex) {
			if ($e->getMessage() == 'NoSuchElement') {
				throw new Zend_Exception('IndexOutOfBounds');
			} else {
				throw $ex;
			}
		}
	}
	
	public function insert($index, $o) {
		try {
			$this->listIterator($index)->add($o);
		// TODO: implement custom exceptions when I can be bothered
		} catch (Zend_Exception $ex) {
			if ($e->getMessage() == 'NoSuchElement') {
				throw new Zend_Exception('IndexOutOfBounds');
			} else {
				throw $ex;
			}
		}

	}
	
	public function insertAll($index, ZendX_Util_Collection_Interface $c) {
		try {
			$modified = false;
			$e1 = $this->listIterator($index);
			$e2 = $c->iterator();
			while ($e2->hasNext()) {
				$e1->add($e2->next());
				$modified = true;
			}
			return $modified;	
		// TODO: implement custom exceptions when I can be bothered
		} catch (Zend_Exception $ex) {
			if ($e->getMessage() == 'NoSuchElement') {
				throw new Zend_Exception('IndexOutOfBounds');
			} else {
				throw $ex;
			}
		}
	}

	public function iterator() {
		return $this->listIterator();
	}
	
	public function listIterator($index = 0) {
		throw new Zend_Exception('UnsupportedOperation');
	} 
	
	public function removeAt($index) {
		try {
			$e = $this->listIterator($index);
			$oldValue = $e->next();
			$e->remove();
			return $oldValue;
		// TODO: implement custom exceptions when I can be bothered
		} catch (Zend_Exception $ex) {
			if ($e->getMessage() == 'NoSuchElement') {
				throw new Zend_Exception('IndexOutOfBounds');
			} else {
				throw $ex;
			}
		}
	}
	
	public function set($index, $o) {
		try {
			$e = $this->listIterator($index);
			$oldVal = $e->next();
			$e->set($o);
			return $oldVal;
		// TODO: implement custom exceptions when I can be bothered
		} catch (Zend_Exception $ex) {
			if ($e->getMessage() == 'NoSuchElement') {
				throw new Zend_Exception('IndexOutOfBounds');
			} else {
				throw $ex;
			}
		}
	}
		
}
