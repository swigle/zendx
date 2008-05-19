<?php

require_once('ZendX/Util/Event/Listener/Interface.php');

interface ZendX_Db_Mapper_Interface extends ZendX_Util_Event_Listener_Interface {
    
    public function connect();
    
    public function suspendUpdating();
    
    public function resumeUpdating();
    
    public function create();
    
    public function populate();
    
    public function update();
    
    public function delete();
    
    public function validate();
    
}