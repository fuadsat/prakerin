<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $message = '';
        $session = new Zend_Session_Namespace('prakerin');
        
        if ($this->_request->isPost()) {
            $nik = $this->_request->getParam('nik');
            $pin = $this->_request->getParam('pin');
            
            $login = new Application_Model_Login();
            
            $arrGetLogin = $login->getLogin($nik, $pin);
            $isValid = 'F';
            
            if ($arrGetLogin['status'] == 'true' && $arrGetLogin['det']) {
                $isValid = $arrGetLogin['det'];
                
                if ($isValid == 'T') {
                    $session->logged = 'true';
                    $session->nik = $nik;
                    
                    $this->_helper->redirector('mainmenu', 'index');
                } else {
                    $message = 'Kombinasi NIK dan PIN tidak sesuai !';
                }
            } else {
                $message = $arrGetLogin['det'];
            }
        }
        
        $this->view->message = $message;
    }

    public function mainmenuAction()
    {
        $this->_helper->layout()->setLayout('main-layout');
    }

    public function logoutAction() {
        $session = new Zend_Session_Namespace('prakerin');
        $session->unsetAll();
        $this->_helper->redirector('index', 'index');
    }
}