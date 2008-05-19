<?php

require_once('ZendX/Util/Observable/Abstract.php');

abstract class ZendX_Biz_Object_Abstract extends ZendX_Util_Observable_Abstract {
    
    protected $id;
    
    public function __construct($id) {
        $this->id = $id;
        
        $this->addEvents(array(
            'createEvent', 
            'updateEvent', 
            'populateEvent', 
            'deleteEvent'
        ));
    }
   
}