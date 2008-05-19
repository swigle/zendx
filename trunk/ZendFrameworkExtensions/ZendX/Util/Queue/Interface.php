<?php

require_once('ZendX/Util/Collection/Interface.php');

interface ZendX_Util_Queue_Interface extends ZendX_Util_Collection_Interface {
	
	public function offer($o);
	
	public function poll();
	
	public function element();
	
	public function peek();
	
}
