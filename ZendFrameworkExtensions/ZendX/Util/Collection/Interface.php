<?php

require_once('ZendX/Util/Iterable/Interface.php');

interface ZendX_Util_Collection_Interface extends ZendX_Util_Iterable_Interface {
	
	/**
	 * Adds an item to the collection.
	 * @param {Object} o The item to add.
	 * @return {Object} The item added.
	 */
	public function add($o);
	
	public function addAll(ZendX_Util_Collection_Interface $collection);
	
	public function clear();
	
	public function contains($o);
	
	public function containsAll(ZendX_Util_Collection_Interface $collection);
	
	public function isEmpty();
	
	public function remove($o);
	
	public function removeAll(ZendX_Util_Collection_Interface $collection);
	
	public function retainAll(ZendX_Util_Collection_Interface $collection);
	
	public function size();
	
	public function toArray();
	
}
