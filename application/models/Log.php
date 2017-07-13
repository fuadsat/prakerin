<?php

class Application_Model_Log
{
    protected $_logger;
    public function __construct() {
        $this->_logger = Zend_Registry::get('log');
    }
    
    public function logError ($message) {
        $session = new Zend_Session_Namespace('overtime');
        $nik = $session->nik;
        $this->_logger->log('===================='.date('dmy').'===================='.PHP_EOL, Zend_Log::INFO);
        $this->_logger->log("NIK login : ".$nik.PHP_EOL, Zend_Log::INFO);
        $this->_logger->log($message.PHP_EOL, Zend_Log::INFO);
        $this->_logger->log('===================='.date('dmy').'===================='.PHP_EOL, Zend_Log::INFO);
    }
}