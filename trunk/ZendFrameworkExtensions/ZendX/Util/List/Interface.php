<?php

require_once('ZendX/Util/Collection/Interface.php');

interface ZendX_Util_List_Interface extends ZendX_Util_Collection_Interface {
	
	public function get($index);
	
	public function insert($index, $o);
	
	public function insertAll($index, ZendX_Util_Collection_Interface $c);
	
	public function indexOf($o);
		
	public function lastIndexOf($o);
	
	public function listIterator($index = 0);
	
	public function removeAt($index);
		
	public function set($index, $o);
	
	public function subList($fromIndex, $toIndex);
		
}
