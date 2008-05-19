<?php

require_once('ZendX/Util/Event/Listener/Interface.php');

class ZendX_Util_Event {

    private $name;
    private $firing;
    private $listeners;
    private $numListeners;

    public function __construct($name) {
        $this->name = $name;
        $this->firing = false;
        $this->listeners = array();
        $this->numListeners = 0;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function isFiring() {
        return $this->firing;
    }
    
    public function getListenerCount() {
        return $this->numListeners;
    }
    
    public function addListener(ZendX_Util_Event_Listener_Interface $listener){
        if(!$this->isListening($listener)){
            if($this->firing){
                array_push($this->listeners, $listener);
            }else{ // if we are currently firing this event, don't disturb the listener loop
                $this->listeners = array_slice($this->listeners, 0);
                array_push($this->listeners, $listener);
            }
            $this->numListeners++;
        }
    }
    
    public function findListener(ZendX_Util_Event_Listener_Interface $listener){
        $ls = $this->listeners;
        for($i = 0, $len = count($ls); $i < $len; $i++) {
            $l = $ls[i];
            if($l == $listener){
                return $i;
            }
        }
        return -1;
    }

    public function isListening(ZendX_Util_Event_Listener_Interface $listener) {
        return $this->findListener($listener) != -1;
    }

    public function removeListener(ZendX_Util_Event_Listener_Interface $listener) {
        $index;
        if(($index = $this->findListener($listener)) != -1){
            if(!$this->firing){
                array_splice($this->listeners, index, 1);
            }else{
                $this->listeners = array_slice($this->listeners, 0);
                array_splice($this->listeners, index, 1);
            }
            $this->numListeners--;
            return true;
        }
        return false;
    }

    public function clearListeners() {
        unset($this->listeners);
        $this->listeners = array();
        $this->numListeners = 0;
    }

    public function fire() {
        $ls = $this->listeners;
        $len = count($ls);
        if($len > 0) {
            $this->firing = true;
            $args = array_slice(func_get_args(), 0);
            for ($i = 0; $i < $len; $i++) {
                $l = $ls[i];
                if ($l.handle($this, $args) === false){
                    $this->firing = false;
                    return false;
                }
            }
            $this->firing = false;
        }
        return true;
    }

}