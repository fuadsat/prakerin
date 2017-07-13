<?php

class Application_Model_Login
{
    protected $_execute;
    
    public function __construct() {
        $this->_execute = new Application_Model_Execute();
    }
    
    public function getLogin ($nik, $pin) {
        $sql = "select app_user_security.get_valid_user(:nik, :pin) from dual";
        $bind = array(':nik' => $nik, ':pin' => $pin);
        $position = 'Login - getLogin('.$nik.', '.$pin.')';
        return array('status' => 'true', 'det' => 'T');
        return $this->_execute->executeSelectQuery($sql, 'one', $bind, $position);
    }
}

