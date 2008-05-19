<?php

require_once('ZendX/Util/Queue/Interface.php');

interface ZendX_Util_Deque_Interface extends ZendX_Util_Queue_Interface {
	
	public function addFirst($o);
	
	public function addLast($o);
	
	public function getFirst();
	
	public function getLast();
	
	public function offerFirst($o);
	
	public function offerLast($o);
	
	public function peekFirst();
	
	public function peekLast();
	
	public function pollFirst();
	
	public function pollLast();
	
	public function pop();
	
	public function push($o);
	
	public function removeFirst($o);
	
	public function removeLast($o);
	
	public function removeFirstOccurrance($o);
	
	public function removeLastOccurrance($o);
	
}
