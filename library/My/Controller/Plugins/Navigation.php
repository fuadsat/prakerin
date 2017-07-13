<?php
class My_Controller_Plugins_Navigation extends Zend_Controller_Plugin_Abstract {
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $session = new Zend_Session_Namespace('prakerin');

        $sesNik = $session->nik;
        $sesLogged = $session->logged;
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');

        
    }
}