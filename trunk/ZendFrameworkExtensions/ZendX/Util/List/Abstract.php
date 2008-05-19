<?php

require_once('Zend/Exception.php');
require_once('ZendX/Util/Iterator/Interface.php');
require_once('ZendX/Util/List/Iterator/Interface.php');
require_once('ZendX/Util/List/Interface.php');
require_once('ZendX/Util/Collection/Abstract.php');
require_once('ZendX/Util/RandomAccess/Interface.php');

abstract class ZendX_Util_List_Abstract extends ZendX_Util_Collection_Abstract implements ZendX_Util_List_Interface {
	
	protected function __construct() {}
	
	public function add($o) {
		$this->insert($this->size(), $o);
		return true;	
	}
	
	public function clear() {
		$this->removeRange(0, $this->size());	
	}
	
	public function indexOf($o) {
		$e = $this->listIterator();
		if ($o == null) {
			while ($e->hasNext()) {
				if ($e->next() == null) {
					return $e->previousIndex();
				}
			}
		} else {
			while ($e->hasNext()) {
				if ($e->next() === $o) {
					return $e->previousIndex();
				}
			}
		}
	}
	
	public function insert($index, $o) {
		throw new Zend_Exception("UnsupportedOperation");	
	}
	
	public function insertAll($index, ZendX_Util_Collection_Interface $c) {
		$modified = false;
		$e = $c->iterator();
		while ($e->hasNext()) {
			$this->insert($index++, $e->next());
			$modified = true;
		}
		return $modified;
	}
	
	public function iterator() {
		return new ZendX_Util_List_Itr($this);	
	}
	
	public function lastIndexOf($o) {
		$e = $this->listIterator($this->size());
		if ($o == null) {
			while ($e->hasPrevious()) {
				if ($e->previous() == null) {
					return $e->nextIndex();
				}
			}
		} else {
			while ($e->hasPrevious()) {
				if ($e->previous() === $o) {
					return $e->nextIndex();
				}
			}
		}
	}
	
	public function listIterator($index = 0) {
		if ($index < 0 || $index > $this->size()) {
			throw new ZendException('IndexOutOfBounds');
		}
		
		return new ZendX_Util_List_ListItr($index);
	}
	
	public function removeAt($index) {
		throw new Zend_Exception('UnsupportedOperation');
	}
	
	protected function removeRange($fromIndex, $toIndex) {
		$e = $this->listIterator($fromIndex);
		for ($i=0, $n=$toIndex-$fromIndex; $i < $n; $i++) {
			$e->next();
			$e->remove();
		}	
	}
	
	public function set($index, $o) {
		throw new Zend_Exception('UnsupportedOperation');
	}
	
	public function subList($fromIndex, $toIndex) {
		return ($this instanceof ZendX_Util_RandomAccess_Interface ? 
				new ZendX_Util_List_RandomAccessSubList($this, $fromIndex, $toIndex) : 
				new ZendX_Util_List_SubList($this, $fromIndex, $toIndex));
	}
	
}

class ZendX_Util_List_Itr implements ZendX_Util_Iterator_Interface {
	
	protected $collection;
	
	protected $cursor = 0;
	
	protected $lastRet = -1;
	
	public function __construct(ZendX_Util_Collection_Interface $c) {
		$this->collection = $c;
	}
	
	public function hasNext() {
		return $this->cursor != $this->collection->size();
	}
	
	public function next() {
		try {
			$next = $this->collection->get($this->cursor);
			$this->lastRet = $this->cursor++;
			return $next;
		} catch (Zend_Exception $e) {
			// TODO: clean this up when I can be bothered creating custom exceptions
			if ($e->getMessage() == 'UnsupportedOperation') {
				throw $e;	
			} else {
				throw new Zend_Exception('NoSuchElement');
			}
		}
	}
	
	public function remove() {
		if ($this->lastRet == -1) {
			throw new Zend_Exception('IllegalState');
		}
		
		try {
			$this->collection->removeAt($this->lastRet);
			if ($this->lastRet < $this->cursor) {
				$this->cursor--;
			}
			$this->lastRet = -1;
		} catch (Zend_Exception $e) {
			// TODO: clean this up when I can be bothered creating custom exceptions
			if ($e->getMessage() == 'IndexOutOfBounds') {
				throw new Zend_Exception('ConcurrentModification');
			} else {
				throw $e;
			}
		}
	}
	
}

class ZendX_Util_List_ListItr extends ZendX_Util_List_Itr implements ZendX_Util_List_Iterator_Interface {
	
	public function __construct(ZendX_Util_List_Interface $c, $index) {
		parent::__construct($c);
		$this->cursor = $index;
	}
	
	public function add($o) {
		try {
			$this->collection->insert($this->cursor++, $o);
			$this->lastRet = -1;	
		} catch (Zend_Exception $e) {
			// TODO: clean this up when I can be bothered creating custom exceptions
			if ($e->getMessage() == 'IndexOutOfBounds') {
				throw new Zend_Exception('ConcurrentModification');
			} else {
				throw $e;
			}
		}		
	}
	
	public function hasPrevious() {
		return $this->cursor != 0;
	}
	
	public function nextIndex() {
		return $this->cursor;
	}
	
	public function previous() {
		try {
			$i = $this->cursor - 1;
			$previous = $this->collection->get($i);
			$this->lastRet = $this->cursor = $i;
			return $previous;
		} catch (Zend_Exception $e) {
			// TODO: clean this up when I can be bothered creating custom exceptions
			if ($e->getMessage() == 'IndexOutOfBounds') {
				throw new Zend_Exception('NoSuchElement');
			} else {
				throw $e;
			}
		}	
	}
	
	public function previousIndex() {
		return $this->cursor - 1;
	}
	
	public function set($o) {
		if ($this->lastRet == -1) {
			throw new Zend_Exception('IllegalState');
		}
		
		try {
			$this->collection->set($this->lastRet, $o);
		} catch (Zend_Exception $e) {
			// TODO: clean this up when I can be bothered creating custom exceptions
			if ($e->getMessage() == 'IndexOutOfBounds') {
				throw new Zend_Exception('ConcurrentModification');
			} else {
				throw $e;
			}
		}
	}
	
} 

class ZendX_Util_List_SubList extends ZendX_Util_List_Abstract {
	
	private $list;
	
	private $offset;
	
	private $size;
	
	function __construct(ZendX_Util_List_Abstract $list, $fromIndex, $toIndex) {
		if ($fromIndex < 0) {
			throw new Zend_Exception('IndexOutOfBounds');
		}
		if ($toIndex > $list->size()) {
			throw new Zend_Exception('IndexOutOfBounds');
		}
		if ($fromIndex > $toIndex) {
			throw new Zend_Exception('IllegalArgument');
		}
		
		$this->list = $list;
		$this->offset = $fromIndex;
		$this->size = $toIndex - $fromIndex; 
	}
	
	public function addAll(ZendX_Util_Collection_Interface $c) {
		return $this->list-addAll($c);
	}
	
	public function get($index) {
		$this->rangeCheck($index);
		return $this->list->get($index + $this->offset);
	}

	public function insert($index, $o) {
		$this->rangeCheck($index);
		$this->list->insert($index + $this->offset, $o);
		$this->size++;
	}
	
	public function insertAll($index, ZendX_Util_Collection_Interface $c) {
		$this->rangeCheck($index);
		
		$cSize = $c->size();
		if ($cSize == 0) {
			return false;
		}
		
		$result = $this->list->insertAll($index + $this->offset, $c);
		$this->size += $cSize;
		return $result;	
	}
	
	public function iterator() {
		return $this->listIterator();
	}
	
	public function listIterator($index = 0) {
		$this->rangeCheck($index);
		
		// TODO: implement proxy iterator
		throw new Zend_Exception('UnsupportedOperation');
	}
	
	private function rangeCheck($index) {
		if ($index < 0 || $index > $this->size) {
			throw new Zend_Exception('IndexOutOfBounds');
		}
	}
	
	public function removeAt($index) {
		$this->rangeCheck($index);
		$result = $this->list->removeAt($index + $this->offset);
		$this->size--;
		return $result;	
	}
	
	protected function removeRange($fromIndex, $toIndex) {
		$this->list->removeRange($fromIndex + $this->offset, $toIndex + $this->offset);
		$this->size -= ($toIndex - $fromIndex);	
	}
	
	public function set($index, $o) {
		$this->rangeCheck($index);
		return $this->list->set($index + $this->offset, $o);	
	}
		
	public function size() {
		return $this->size;
	}
	
	public function subList($fromIndex, $toIndex) {
		return new ZendX_Util_List_SubList($this, $fromIndex, $toIndex);	
	}
	
}

class ZendX_Util_List_RandomAccessSubList extends ZendX_Util_List_SubList implements ZendX_Util_RandomAccess_Interface {
	
	function __construct(ZendX_Util_List_Abstract $list, $fromIndex, $toIndex) {
		parent::__construct($list, $fromIndex, $toIndex);
	}
	
	public function subList($fromIndex, $toIndex) {
		return new ZendX_Util_List_RandomAccessSubList($this, $fromIndex, $toIndex);
	}
	
}
