<?php

require_once('ZendX/Util/Collection/Iterator/Interface.php');
require_once('ZendX/Util/Collection/Interface.php');

class ZendX_Util_Collection_Iterator_Simple implements ZendX_Util_Collection_Iterator_Interface {
	
	private $cursor;
	private $collection;
	
	public function __construct(ZendX_Util_Collection_Interface $collection) {
		$this->cursor = 0;
		$this->collection = $collection;	
	}
	
	public function hasNext() {
		return $this->cursor < $collection->size();
	}

	public function next() {		
		return $collection->get($this->cursor++);
	}
		
}
