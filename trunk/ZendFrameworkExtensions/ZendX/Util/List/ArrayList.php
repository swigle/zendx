<?php

require_once('Zend/Exception.php');
require_once('ZendX/Util/List/Abstract.php');
require_once('ZendX/Util/List/Interface.php');
require_once('ZendX/Util/RandomAccess/Interface.php');

class ZendX_Util_List_ArrayList extends ZendX_Util_List_Abstract implements ZendX_Util_List_Interface, ZendX_Util_RandomAccess_Interface {
	
	private $elementData;
	
	private $size = 0;
	
	public function __construct(ZendX_Util_Collection_Interface $c = null) {
		$this->elementData = array();
		
		if ($c != null) {
			$this->elementData = $c->toArray();
			$this->size = count($this->elementData);
		}
	}
	
	public function add($o) {
		$this->elementData[] = $o;
		$this->size++;
		return true;	
	}
	
	public function addAll(ZendX_Util_Collection_Interface $c) {
		$a = $c->toArray();
		$numNew = count($a);
		if ($numNew > 0) {
			$this->elementData = array_merge($this->elementData, $a);
		}
		return $numNew > 0;
	}
	
	public function clear() {
		unset($this->elementData);
		$this->elementData = array();
		$this->size = 0;
	}
	
	public function contains($o) {
		return $this->indexOf($o);
	}
	
	/*
     * Private remove method that skips bounds checking and does not
     * return the value removed.
     */
	private function fastRemoveAt($index) {
		// delete old value and reindex array
		unset($this->elementData[$index]);
		$this->elementData = $this->toArray();
	}
	
	public function get($index) {
		$this->rangeCheck($index);
		return $this->elementData[$index];
	}
	
	public function indexOf($o) {
		if ($o == null) {
			for ($i = 0; $i < $this->size; $i++) {
				if ($this->elementData == null) {
					return $i;
				}
			}
		} else {
			for ($i = 0; $i < $this->size; $i++) {
				if ($this->elementData === $o) {
					return $i;
				}
			}
		}
		return -1;
	}
	
	public function insert($index, $o) {
		if ($index > $this->size || $index < 0) {
			throw new Zend_Exception('IndexOutOfBounds');
		}
	    	
		// split the array into upper and lower portions at the point of insertion
		$lower = array_slice($this->elementData, 0, $index);
		$upper = array_slice($this->elementData, $index);
		
		$lower[] = $o;
		$this->elementData = array_merge($lower, $upper);
		$this->size++;
	}
	
	public function insertAll($index, ZendX_Util_Collection_Interface $c) {
		if ($index > $this->size || $index < 0) {
			throw new Zend_Exception('IndexOutOfBounds');
		}
	    
		$a = $c->toArray();
		$numNew = count($a);
		if ($numNew > 0) {	
			// split the array into upper and lower portions at the point of insertion
			$lower = array_slice($this->elementData, 0, $index);
			$upper = array_slice($this->elementData, $index);
			
			$this->elementData = array_merge($lower, $a, $upper);
			$this->size += $numNew;
		}
		
		return $numNew > 0;
	}
	
	public function isEmpty() {
		return $this->size == 0;
	}
	
	public function lastIndexOf($o) {
		if ($o == null) {
			for ($i = ($this->size -1); $i >= 0; $i--) {
				if ($this->elementData == null) {
					return $i;
				}
			}
		} else {
			for ($i = ($this->size -1); $i >= 0; $i--) {
				if ($this->elementData === $o) {
					return $i;
				}
			}
		}
		return -1;
	}
	
	private function rangeCheck($index) {
		if ($index >= $this->size) {
			throw new Zend_Exception('IndexOutOfBounds');
		}	
	}
	
	public function remove($o) {
		$index = $this->indexOf($o);
		if ($index < 0) {
			return false;
		}
		
		$this->fastRemoveAt($index);
		$this->size--;
		return true;
	}
	
	public function removeAt($index) {
		$this->rangeCheck($index);	
		
		$oldValue = $this->elementData[$index];	
		$this->fastRemoveAt($index);
		$this->size--;
		return $oldValue;
	}
	
	protected function removeRange($fromIndex, $toIndex) {
		$numMoved = $this->size - $fromIndex;
		
		// split the array into upper and lower above and below the range
		$lower = array_slice($this->elementData, 0, $fromIndex);
		$upper = array_slice($this->elementData, $toIndex);
		
		$this->elementData = array_merge($lower, $upper);
		$this->size = $this->size - ($toIndex - $fromIndex);	
	}
	
	public function set($index, $o) {
		$this->rangeCheck($index);
		
		$oldValue = $this->elementData[$index];
		$this->elementData[$index] = $o;
		return $oldValue;
	}
	
	public function size() {
		return $this->size;
	}
	
	public function toArray() {
		return array_values($this->elementData);
	}
	
}
