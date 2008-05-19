<?php

require_once('ZendX/Util/Deque/Interface.php');
require_once('ZendX/Util/Collection/Interface.php');
require_once('ZendX/Util/List/Interface.php');
require_once('ZendX/Util/List/Sequential/Abstract.php');

class ZendX_Util_List_LinkedList extends ZendX_Util_List_Sequential_Abstract implements ZendX_Util_List_Interface, ZendX_Util_Deque_Interface {

	private $header;
	
	private $size = 0;
	
	public function __construct(ZendX_Util_Collection_Interface $c = null) {
		$this->header = new ZendX_Util_List_Entry(null, null, null);
		$this->header->next = $this->header->previous = $this->header;
		
		if ($c == null) {
			$this->addAll($c);
		}
	}
	
	public function add($o) {
		$this->addBefore($o, $this->header);
		return true;	
	}
	
	public function addAll(ZendX_Util_Collection_Interface $c) {
		return $this->insertAll($this->size, $c);
	}
	
	public function addFirst($o) {
		$this->addBefore($o, $this->header->next);
	}
	
	public function addLast($o) {
		$this->addLast($o, $this->header);
	}
	
	public function contains($o) {
		return $this->indexOf($o) != -1;
	}
	
	public function clear() {
		$e = $this->header->next;
        while ($e != $this->header) {
            $next = $e->next;
            $e->next = $e->previous = null;
            $e->element = null;
            $e = $next;
        }
        $this->header->next = $this->header->previous = $header;
        $this->size = 0;
	}
	
	public function element() {
		return $this->getFirst();	
	}
	
	public function entry($index) {
		if ($index < 0 || $index >= $this->size)
            throw new Zend_Exception('IndexOutOfBounds');
			
        $e = $this->header;
        if ($index < ($this->size >> 1)) {
            for ($i = 0; $i <= $index; $i++) {
                $e = $e->next;
			}
        } else {
            for ($i = $this->size; $i > $index; $i--) {
                $e = $e->previous;
			}
        }
        return $e;
	}
	
	public function get($index) {
		return $this->entry($index)->element;
	}
	
	public function getFirst() {
		if ($this->size == 0) {
			throw new Zend_Exception('NoSuchElement');
		}
		
		return $this->header->next->element;
	}
	
	public function getLast() {
		if ($this->size == 0) {
			throw new Zend_Exception('NoSuchElement');
		}
		
		return $this->header->previous->element;	
	}
	
	public function indexOf($o) {
		$index = 0;
        if ($o == null) {
            for ($e = $this->header->next; $e != $this->header; $e = $e->next) {
                if ($e->element == null) {
                    return $index;
				}
                $index++;
            }
        } else {
            for ($e = $this->header->next; $e != $this->header; $e = $e->next) {
                if ($o === $e->element) {
                    return $index;
				}
                $index++;
            }
        }
        return -1;	
	}
	
	public function insert($index, $o) {
		$this->addBefore($o, ($index == $this->size ? $this->header : $this->entry($index)));
	}
	
	public function insertAll($index, ZendX_Util_Collection_Interface $c) {
		if ($index < 0 || $index > $this->size) {
			throw new Zend_Exception('IndexOutOfBounds');
		}
		
		$a = $c->toArray();
		$numNew = count($a);
		if ($numNew == 0) {
			return false;
		}
		
		$successor = ($index == $this->size ? $this->header : $this->entry($index));
		$predecessor = $successor->previous;
		for ($i = 0; $i < $numNew; $i++) {
			$e = new ZendX_Util_List_Entry($a[$i], $successor, $predecessor);
            $predecessor->next = $e;
            $predecessor = $e;
		}
		$successor->previous = $predecessor;

        $this->size += $numNew;
        return true;
	}
	
	public function lastIndexOf($o) {
		$index = $this->size;
        if ($o == null) {
            for ($e = $this->header->previous; $e != $this->header; $e = $e->previous) {
                $index--;
                if ($e->element == null) {
                    return $index;
				}
            }
        } else {
            for ($e = $this->header->previous; $e != $this->header; $e = $e->previous) {
                $index--;
                if ($o === $e->element) {
                    return $index;
				}
            }
        }
        return -1;	
	}
	
	public function offer($o) {
		return $this->add($o);	
	}
	
	public function offerFirst($o) {
		$this->addFirst($o);
		return true;
	}
	
	public function offerLast($o) {
		$this->addLast($o);
		return true;
	}
	
	public function peek() {
		if ($this->size == 0) {
			return null;
		}
		return $this->getFirst();
	}
	
	public function peekFirst() {
		if ($this->size == 0) {
			return null;
		}
		return $this->getFirst();	
	}
	
	public function peekLast() {
		if ($this->size == 0) {
			return null;
		}
		return $this->getLast();
	}
	
	public function poll() {
		if ($this->size == 0) {
			return null;
		}
		return $this->removeFirst();	
	}
	
	public function pollFirst() {
		if ($this->size == 0) {
			return null;
		}
		return $this->removeFirst();
	}
	
	public function pollLast() {
		if ($this->size == 0) {
			return null;
		}
		return $this->removeLast();
	}
	
	public function pop() {
		return $this->removeFirst();	
	}
	
	public function push($o) {
		$this->addFirst($o);
	}
	
	public function remove($o) {
		if ($o == null) {
			for ($e = $this->header->next; $e != $this->header; $e = $e->next) {
				if ($e->element == null) {
					$this->removeEntry($e);
					return true;
				}
			}
		} else {
			for ($e = $this->header->next; $e != $this->header; $e = $e->next) {
				if ($e->element === $o) {
					$this->removeEntry($e);
					return true;
				}
			}
		}
		return false;
	}
	
	public function removeAt($index) {
		return $this->removeEntry($this->entry($index));	
	}
	
	public function removeFirst() {
		return $this->remove($this->header->next);
	}
	
	public function removeFirstOccurrence($o) {
		return $this->remove($o);
	}
	
	public function removeLast() {
		return $this->remove($this->header->previous);
	}
	
	public function removeLastOccurrence($o) {
		if ($o == null) {
            for ($e = $this->header->previous; $e != $this->header; $e = $e->previous) {
                if ($e->element == null) {
                    $this->removeEntry($e);
                    return true;
                }
            }
        } else {
            for ($e = $this->header->previous; $e != $this->header; $e = $e->previous) {
                if ($o === $e->element) {
                    $this->removeEntry($e);
                    return true;
                }
            }
        }
        return false;
	}
	
	public function set($index, $o) {
		$e = $this->entry($index);
		$oldVal = $e->element;
		$e->element = $o;
		return $oldVal;
	}
	
	public function size() {
		return $this->size;
	}
	
	public function toArray() {
		$a = array();
        $i = 0;
        for ($e = $this->header->next; $e != $this->header; $e = $e->next) {
        	$a[$i++] = $e->element;
        }
		return $a;
	}
	
}

class ZendX_Util_List_Entry {
	
	public $element;
	public $next;
	public $previous;
	
	public function __construct($element, $next, $previous) {
		$this->element = $element;
		$this->next = $next;
		$this->previous = $previous;
	}
	
}
