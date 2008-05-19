<?php

require_once('ZendX/Util/Event.php');
require_once('ZendX/Util/Event/Listener/Interface.php');

abstract class ZendX_Util_Observable_Abstract {

    private $eventsSuspended = false;
    private $events = array(); 
 
    public function addEvent($event) {
        $retVal = false;
        if (is_string($event)) {
            $this->events[$event] = new ZendX_Util_Event(strtolower($event));
            $retVal = true; 
        } else if ($event instanceof ZendX_Util_Event) {
            $this->events[$event->getName()] = $event;
            $retVal = true;
        }
        return $retVal;
    }
    
    public function addEvents(array $events) {
        for ($i = 0, $len = count($events); $i < $len; $i++) {
            $e = $events[i];
            if (is_string($events[$i])) {
                $e = new ZendX_Util_Event(strtolower($e));
                $this->addEvent($e);
            } else if ($events[i] instanceof ZendX_Util_Event) {
                $this->addEvent($e);
            }
        }
    }
    
    public function relayEvents(ZendX_Util_Observable_Abstract $o, array $events) {
        // TODO: figure out how to proxy events for other observables
    }    
    
    public function fireEvent($eventName) {
        if (!$this->eventsSuspended) {
            $e = $this->events[strtolower($eventName)];
            if($e instanceof ZendX_Util_Event){
                return $e->fire(array_slice(func_get_args(), 1));
            }
        }
        return true;
    }
    
    public function suspendEvents() {
        $this->eventsSuspended = true;
    }
    
    public function resumeEvents() {
        $this->eventsSuspended = false;
    }
    
    public function addListener($eventName, ZendX_Util_Event_Listener_Interface $listener) {   
        $e = $this->events[$eventName];
        if ($e instanceof ZendX_Util_Event) {
            $e->addListener($listener);
        } else {
            throw new Zend_Exception("NonExistentEvent: event {$eventName} doesn't exist in this context");
        }
    }
    
    public function on($eventName, ZendX_Util_Event_Listener_Interface $listener) {
        $this->addListener($eventName, $listener);
    }
    
    public function removeListener($eventName, ZendX_Util_Event_Listener_Interface $listener) {
        $e = $this->events[strtolower($eventName)];
        if($e instanceof ZendX_Util_Event){
            $e->removeListener($listener);
        }
    }
    
    public function un($eventName, ZendX_Util_Event_Listener_Interface $listener) {
        $this->removeListener($eventName, $listener);
    }
    
    public function hasListener($eventName) {
        $e = $this->events[$eventName];
        return ($e instanceof ZendX_Util_Event) && ($e->getListenerCount() > 0);
    }
    
    public function purgeListeners() {
        foreach ($this->events as $e) {
            $e->clearListeners();
        }
    }
    
}