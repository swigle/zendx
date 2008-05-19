<?php

require_once('ZendX/Util/Collection/Interface.php');

class ZendX_Util_Collection_Simple implements ZendX_Util_Collection_Interface {
	
	private $store;
	
	public function __construct() {
		$this->store = array();	
	}
	
	public function add($o) {
		$this->store[] = $o;
	}
	
	public function addAll(ZendX_Util_Collection_Interface $collection) {
		$iterator = $collection->iterator();
		while($iterator->hasNext()) {
			$this->add($iterator->next());
		}
	}
	
	public function clear() {
		foreach ($this->store as $i => $value) {
		    unset($this->store[$i]);
		}
		$this->reindex();		
	}
	
	public function contains($o) {
		return in_array($o, $this->store);
	}
	
	public function containsAll(ZendX_Util_Collection_Interface $collection) {
		$iterator = $collection->iterator();
		while($iterator->hasNext()) {
			$item = $iterator->next();
			if (!$this->contains($item)) {
				return false;
			}
		}
		return true;
	}
	
	private function find($o) {
		return array_search($o, $this->store, true);
	}
	
	public function get($index) {
		if (array_key_exists($index, $this->store)) {
			return $this->store[$index];
		}
		return null;
	}
	
	public function isEmpty() {
		return count($this->store) > 0;
	}
	
	public function iterator() {
		return new ZendX_Util_Collection_Iterator_Simple($this);		
	}
	
	private function reindex() {
		$this->store = $this->toArray();	
	}
	
	public function remove($o) {
		if ($index = $this->find($o)) {
			unset($this->store[$index]);
			$this->reindex();	
		}		
	}
	
	public function removeAll(ZendX_Util_Collection_Interface $collection) {
		$iterator = $collection->iterator();
		while($iterator->hasNext()) {
			$item = $iterator->next();
			if ($this->contains($item)) {
				$this->remove($item);
			}
		}
	}
	
	public function retainAll(ZendX_Util_Collection_Interface $collection) {
		$newStore = array();
		$iterator = $collection->iterator();
		while($iterator->hasNext()) {
			$item = $iterator->next();
			if ($this->contains($item)) {
				$newStore[] = $item;
			}
		}
		$this->store = $newStore;
	}
	
	public function size() {
		return count($this->store);
	}
	
	public function toArray() {
		return array_values($this->store);
	}
	
}
