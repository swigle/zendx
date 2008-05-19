<?php

interface ZendX_Util_Iterator_Interface {
	
	/**
	 * Checks to see if there are items remaining in the underlying collection.
	 * @return {boolean} true if there is items remaining in the underlying collection 
	 */
	public function hasNext();
	
	/**
	 * Gets each successive item in the collection, advancing the cursor by one 
	 * element for each call
	 * @return {Object} the next item in the collection 
	 */
	public function next();
	
	/**
	 * Removes the current item from the underlying collection 
	 */
	public function remove();
	
}
