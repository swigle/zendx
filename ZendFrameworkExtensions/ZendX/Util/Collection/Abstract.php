<?php

require_once('ZendX/Util/Collection/Interface.php');

abstract class ZendX_Util_Collection_Abstract implements ZendX_Util_Collection_Interface {
	
	protected function __construct() {}
	
	public function addAll(ZendX_Util_Collection_Interface $c) {
		$modified = false;
		$e = $c->iterator();
		while($e->hasNext()) {
			$this->add($e->next());
			$modified = true;
		}
		return $modified;
	}
	
	public function clear() {
		$e = $this->iterator();
		while ($e->hasNext()) {
			$e->next();
			$e->remove();
		}
	}
	
	public function contains($o) {
		$e = $this->iterator();
		if ($o == null) {
			while ($e->hasNext()) {
				if ($e->next() == null) {
					return true;
				}
			}
		} else {
			while ($e->hasNext()) {
				if ($e->next() === $o) {
					return true;
				}
			}
		}		
		return false;
	}
	
	public function containsAll(ZendX_Util_Collection_Interface $c) {
		$e = $c->iterator();
		while($e->hasNext()) {
			if (!$this->contains($e->next())) {
				return false;
			}
		}
		return true;
	}
	
	public function isEmpty() {
		return $this->size() == 0;
	}
	
	public function remove($o) {
		$e = $this->iterator();
		if ($o == null) {
			while ($e->hasNext()) {
				if ($e->next() == null) {
					$e->remove();
					return true;
				}	
			}
		} else {
			while ($e->hasNext()) {
				if ($e->next() === $o) {
					$e->remove();
					return true;
				}
			}
		}
		return false;	
	}
	
	public function removeAll(ZendX_Util_Collection_Interface $c) {
		$modified = false;
		$e = $this->iterator();
		while ($e->hasNext()) {
			if ($c->contains($e->next())) {
				$e->remove();
				$modified = true;
			}
		}
		return $modified;
	}
	
	public function retainAll(ZendX_Util_Collection_Interface $c) {
		$modified = false;
		$e = $this->iterator();
		while ($e->hasNext()) {
			if (!$c->contains($e->next())) {
				$e->remove();
				$modified = true;
			}
		}
		return $modified;
	}
	
	public function toArray() {
		$a = array();
		$e = $this->iterator();
		while ($e->hasNext()) {
			$a[] = $e->next();
		}
		return $a;
	}
	
	public function __toString() {
		$e = $this->iterator();
		if (!$e->hasNext()) {
			return '[]';
		}
		
		$s = '[';
		while ($e->hasNext()) {
			$s .= $e->next();
			if ($e->hasNext()) {
				$s .= ', ';
			}	
		}
		$s .= ']';
	}
	
}
