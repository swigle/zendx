<?php

require_once('ZendX/Util/Iterator/Interface.php');

interface ZendX_Util_List_Iterator_Interface extends ZendX_Util_Iterator_Interface {
	
	public function add($o);
	
	public function hasPrevious();

	public function nextIndex();
		
	public function previous();
	
	public function previousIndex();
	
	public function set($o);
	
}
