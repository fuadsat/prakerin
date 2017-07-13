<?php

class Application_Model_Execute
{
    protected $_logger;
    public function __construct() {
        $this->_logger = new Application_Model_Log();
    }
    
    public function getDbCon ($conn) {
        switch ($conn) {
            case 'satdw':
                $db = Zend_Db::factory('Oracle', array(
                'host'       => '10.234.152.179',
                'username'   => 'report',
                'password'   => 'r4pt0rsdw',
                'dbname'     => '//10.234.152.179/SATDW',
                ));
                break;
            default:
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                break;
        }
    
        return $db;
    }
    
    public function executeSelectQuery ($query, $fetchMode, $bind, $log, $conn = null) {
        $result = array();
        $result['status'] = 'true';
        $result['det'] = '';
        
        try {
            if (is_null($conn)) {
                $db = $this->getDbCon('');
            } else {
                $db = $this->getDbCon($conn);
            }
    
            switch ($fetchMode) {
                case 'all':
                    $result['det'] = $db->fetchAll($query, $bind);
                    break;
    
                case 'row':
                    $result['det'] = $db->fetchRow($query, $bind);
                    break;
    
                case 'one':
                    $result['det'] = $db->fetchOne($query, $bind);
                    break;
            }
    
            $db->closeConnection();
        } catch (Zend_Exception $e) {
            $result['status'] = 'false';
            $result['det'] = 'Database Error !';
            $this->_logger->logError($log.' || '.$e->getMessage());
        }
    
        return $result;
    }
    
    public function executeDbSelectQuery ($select, $fetchMode, $log, $conn = null) {
        $result = array();
        $result['status'] = 'true';
        $result['det'] = '';
    
        try {
            if (is_null($conn)) {
                $db = $this->getDbCon('');
            } else {
                $db = $this->getDbCon($conn);
            }
    
            $stmt = $db->query($select);
            switch ($fetchMode) {
                case 'all':
                    $result['det'] = $stmt->fetchAll();
                    break;
    
                case 'row':
                    $result['det'] = $stmt->fetchRow();
                    break;
    
                case 'one':
                    $result['det'] = $stmt->fetchOne();
                    break;
            }
    
            $db->closeConnection();
        } catch (Zend_Exception $e) {
            $result['status'] = 'false';
            $result['det'] = 'Database Error !';
            $this->_logger->logError($log.' || '.$e->getMessage());
        }
    
        return $result;
    }
    
    public function executeDbSelect ($select) {
        $result = array();
        $result['status'] = 'true';
        $result['det'] = '';
    
        try {
            $result['det'] = $select;
        } catch (Zend_Exception $e) {
            $result['status'] = 'false';
            $result['det'] = 'Database Error !';
            $this->_logger->logError($e->getMessage());
        }
    
        return $result;
    }
    
    public function paginatorBuild ($select, $limit, $page) {
        try {
            $select = new Zend_Paginator_Adapter_DbSelect($select);
            $paginator = new Zend_Paginator($select);
    
            if ($limit <> '') {
                $paginator->setItemCountPerPage($limit);
            }
    
            $paginator->setCurrentPageNumber($page);
        } catch (Zend_Exception $e) {
            $this->_logger->logError($e->getMessage());
        }
    
        return $paginator;
    }
    
    public function paginatorLovBuild ($select, $limit, $page, $sidx, $sord, $keyId) {
        $select = new Zend_Paginator_Adapter_DbSelect($select);
        $paginator = new Zend_Paginator($select);
    
        $paginator->setItemCountPerPage($limit)
        ->setCurrentPageNumber($page);
    
        $items = $paginator->getCurrentItems();
        $output = array();
        $output['total'] = $paginator->count();
        $output['page'] = $paginator->getCurrentPageNumber();
        $output['records'] = $paginator->getTotalItemCount();
        $output['rows'] = array();
    
        foreach ($items as $item) {
            $output['rows'][] = array('id' => $item[$keyId], 'cell' => array_values($item));
        }
    
        return $output;
    }
    
    public function executeQuery ($query, $message, $bind, $log, $conn = null) {
        $result = array();
        $result['status'] = 'true';
        $result['det'] = '';
    
        $db = $this->getDbCon($conn);
        $db->beginTransaction();
    
        try {
            $db->query($query, $bind);
            $db->commit();
            $result['det'] = $message;
        } catch (Zend_Exception $e) {
            $db->rollBack();
            $result['status'] = 'false';
            $result['det'] = 'Database Error !';
            $this->_logger->logError($log.' || '.$e->getMessage());
        }
    
        $db->closeConnection();
    
        return $result;
    }
    
    public function executeMultipleQuery ($query, $message, $bind, $log, $conn = null) {
        $result = array();
        $result['status'] = 'true';
        $result['det'] = '';
    
        $db = $this->getDbCon($conn);
        $db->beginTransaction();
    
        try {
            for ($i = 0; $i < sizeof($query); $i++) {
                $db->query($query[$i], $bind[$i]);
            }
            
            $db->commit();
            $result['det'] = $message;
        } catch (Zend_Exception $e) {
            $db->rollBack();
            $result['status'] = 'false';
            $result['det'] = 'Database Error !';
            $this->_logger->logError($log . ' || ' . $e->getMessage());
        }
    
        $db->closeConnection();
    
        return $result;
    }
    
    public function executeProcedure ($query, $bind, $conn = null) {
        $result = array();
        $result['status'] = 'true';
        $result['det'] = '';
        $out = array();
    
        $db = $this->getDbCon($conn);
    
        try {
            $stmt = new Zend_Db_Statement_Oracle($db, $query);
    
            foreach ($bind as $key => $val) {
                if (!is_null($val['length'])) {
                    $stmt->bindParam($val['key'], $out[][$val['var_out']], $val['type'], $val['length']);
                } else {
                    $stmt->bindParam($val['key'], $val['variable'], $val['type'], $val['length']);
                }
            }
    
            $stmt->execute();
            $result['det'] = $out;
        } catch (Zend_Exception $e) {
            $result['status'] = 'false';
            $result['det'] = 'Database Error !';
            $this->_logger->logError($e->getMessage());
        }
    
        $db->closeConnection();
    
        return $result;
    }
    
    public function putLog ($det) {
        $this->_logger->logError($det);
    }
    
    public function generateReport ($select, $title) {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $stmt = $select->query();
        $result = $stmt->fetchAll();
    
        $header = 0;
        $arrHeader = array();
    
        foreach ($result as $key => $val) {
            $header = sizeof($val);
            $arrHeader = array_keys($val);
            break;
        }
    
        $string = "<html><head><title>".$title."</title></head>";
        $string .= "<body>";
        $string .= "<table border=\"1\">";
        $string .= "<tr>";
    
        for ($i = 0; $i < $header; $i++) {
            $string .= "<th style=\"background-color: #C2E0FF; padding: 12px 12px 6px 6px;\">".$arrHeader[$i]."</th>";
        }
    
        $string .= "</tr>";
    
        foreach ($result as $key => $value) {
            $string .= "<tr>";
    
            $indexData = '';
            for ($i = 0; $i < $header; $i++) {
                $indexData = $arrHeader[$i];
                $string .= "<td>=\"".$value[$indexData]."\"</td>";
            }
    
            $string .= "</tr>";
        }
    
        $string .= "</table>";
        $string .= "</body>";
        $string .= "</html>";
    
        return $string;
    }
    
    public function getSequenceNextval ($seq, $conn = null) {
        $db = $this->getDbCon($conn);
        $no = 0;
    
        try {
            $no = $db->fetchOne("select ".$seq.".nextval from dual");
        } catch (Zend_Exception $e) {
            $this->_logger->logError($e->getMessage());
        }
    
        $db->closeConnection();
    
        return $no;
    }
}