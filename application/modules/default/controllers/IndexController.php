<?php

class Default_IndexController extends App_Controller_Action_Portal {

    public function init() { 
        parent::init();
        $this->_numero = $this->obtenerNumero();
        if ($this->_numero != '') {
            $registroUsuario = new App_Model_Registro();
            $post = $registroUsuario->getusuario($this->_numero);
            if (!empty($post["nombres"])) {
                $this->view->usuario = $post["nombres"];
            }
        }
        $this->_GetResultSoap = $this->_helper->getHelper('GetResultSoap');
        $SoapMusica = $this->_GetResultSoap->_seccionMusicaTop();
        $SoapTonos = $this->view->SoapTonos = $this->_GetResultSoap->_seccionTonos();
        $config = $this->getConfig();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $model = new App_Model_ConfigPerfil();
        $model->saveCdr($ua, $config->app->siteUrl);
        $estado = $this->_getParam('estado', '');
        if (isset($estado) && $estado != '') {
            $this->mensaje($estado);
             $this->_redirect('/');
        }
        $this->forward($this->mobileDetect());
        $this->view->numero = $this->_numero;
        $this->view->perfil = $this->mobileDetect();
        $this->view->SoapMusica = $SoapMusica->seccionMusicaTopResult->albumDetalleBE;
        $this->view->SoapTonos = $SoapTonos->seccionTonosResult->albumDetalleBE;
    }

// 
    public function indexAction() {

        $this->_redirect('/basico240');
    }

    public function basicoAction() {
        $this->_helper->layout->disableLayout();
    }

    public function basico240Action() {
        $this->_helper->layout->setLayout('layout-basico240');
    }

    public function basico360Action() {
        $this->_helper->layout->setLayout('layout-basico360');
    }

    public function avanzadoAction() {
        $this->_helper->layout->setLayout('layout-avanzado');
    }

    function noCache() {
        header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

}
