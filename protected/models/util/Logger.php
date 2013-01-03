<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Logger {
    
    public $classname;
    public $logger;
    public $methodName;
    public $prefix;
    
    public function __construct($classname){
        $this->classname=$classname;
        $this->logger = new KLogger('protected/log',KLogger::DEBUG);
    }
    
    public function setMethod($methodName){
        $this->methodName = $methodName;
        $this->prefix = "<<".$this->classname."->".$this->methodName.">> ";
        $this->logger->logInfo($this->prefix."begin");
    }
    
    public function logInfo($txt){
        $txt = print_r($txt,true);
        $this->logger->logInfo($this->prefix.$txt);
    }
    
    public function logError($txt){
        $txt = print_r($txt,true);
        $this->logger->logError($this->prefix.$txt);
    }
    
    public function logWarn($txt){
        $txt = print_r($txt,true);
        $this->logger->logWarn($this->prefix.$txt);
    }
    
    public function logAlert($txt){
        $txt = print_r($txt,true);
        $this->logger->logAlert($this->prefix.$txt);
    }
    
    public function logFatal($txt){
        $txt = print_r($txt,true);
        $this->logger->logFatal($this->prefix.$txt);
    }
    
    
    
}

?>
