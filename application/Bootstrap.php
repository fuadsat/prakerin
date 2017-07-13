<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initLoader() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('My_');
        return $autoloader;
    }
    
    protected function _initRegisterControllerPlugins() {
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $front->registerPlugin(new My_Controller_Plugins_Navigation());
    }
    
    public function _initLog() {
        $writer = new Zend_Log_Writer_Stream('D:/www/prakerin'.date('dmy').'.log');
        /* $writer = new Zend_Log_Writer_Stream('/var/www/html/app_log/prakerin'.date('dmy').'.log');
        $writer = new Zend_Log_Writer_Stream('/opt/log_app/prakerin'.date('dmy').'.log'); */
        $logger = new Zend_Log($writer);
        Zend_Registry::set('log', $logger);
    }

    protected function _initView() {
        $view = new Zend_View;
        $project = '/prakerin';
        
        $view->headLink()->appendStylesheet($project.'/public/css/bootstrap.min.css');
        $view->headLink()->appendStylesheet($project.'/public/css/bootstrap-theme.min.css');
        $view->headLink()->appendStylesheet($project.'/public/css/datepicker3.css');
        $view->headLink()->appendStylesheet($project.'/public/css/bootstrap-timepicker.min.css');
        $view->headLink()->appendStylesheet($project.'/public/css/ie10-viewport-bug-workaround.css');
        $view->headLink()->appendStylesheet($project.'/public/css/jqGrid.bootstrap.css');
        $view->headLink()->appendStylesheet($project.'/public/css/ui.jqgrid.css');
        $view->headLink()->appendStylesheet($project.'/public/css/jquery.dataTables.min.css');
        $view->headLink()->appendStylesheet($project.'/public/css/fixedHeader.dataTables.min.css');
        $view->headLink()->appendStylesheet($project.'/public/css/fixedColumns.dataTables.min.css');
        $view->headLink()->appendStylesheet($project.'/public/css/styling.css');
    
        $view->headScript()->appendFile($project.'/public/js/jquery.min.js');
        $view->headScript()->appendFile($project.'/public/js/bootstrap.min.js');
        $view->headScript()->appendFile($project.'/public/js/bootstrap-datepicker.js');
        $view->headScript()->appendFile($project.'/public/js/bootstrap-timepicker.min.js');
        $view->headScript()->appendFile($project.'/public/js/i18n/grid.locale-en.js');
        $view->headScript()->appendFile($project.'/public/js/jquery.jqGrid.min.js');
        $view->headScript()->appendFile($project.'/public/js/jquery.jqGrid.src.js');
        $view->headScript()->appendFile($project.'/public/js/jquery.dataTables.min.js');
        $view->headScript()->appendFile($project.'/public/js/dataTables.fixedHeader.min.js');
        $view->headScript()->appendFile($project.'/public/js/dataTables.fixedColumns.min.js');
        $view->headScript()->appendFile($project.'/public/js/numeral.min.js');
        $view->headScript()->appendFile($project.'/public/js/global.js');
        $view->headScript()->appendFile($project.'/public/js/event.js');
        $view->headScript()->appendFile($project.'/public/js/ajax.js');
    
        return $view;
    }
    
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
}

