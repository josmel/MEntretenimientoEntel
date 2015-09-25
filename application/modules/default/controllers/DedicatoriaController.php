<?php

class Default_DedicatoriaController extends App_Controller_Action_Portal {

    public function init() {
        parent::init();
        $this->_config = $this->getConfig();
        $this->_numero = $this->obtenerNumero();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $model = new App_Model_ConfigPerfil();
        $controller = $this->getParam('controller');
        $model->saveCdr($ua, $controller);
        switch ($this->mobileDetect()):
            case 'basico240' :
                $this->_helper->layout->setLayout('layout-basico240');
                $this->_perfil = 1;
                break;
            case 'basico360' :
                $this->_helper->layout->setLayout('layout-basico360');
                $this->_perfil = 2;
                break;
            case 'avanzado' :
                $this->_helper->layout->setLayout('layout-avanzado');
                $this->_perfil = 3;
                break;
        endswitch;
    }

    public function dedicatoriaAction() {

        if (empty($this->_numero)) {
            $this->_redirect('/?estado=4');
        } else {
            try {
                $model = new App_Model_ConfigPerfil();
                $web = "http://174.121.234.90/SVARequest/Request.aspx?op=3&nu=" . $this->_numero . "&sc=333&su=1&k=DEDICAWAP&v=1&o=7";
                $result = file_get_contents($web);
                $model->saveCdrDedicatorias($result);
                if ($result == 'OK') {
                    $this->_redirect('/?estado=5');
                } else {
                    $this->_redirect('/?estado=6');
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->_redirect('/');
            }
        }
    }

    public function registroAction() {
        if ($this->_request->isPost()) {
            $dataForm = $this->_request->getPost();
            try {
                if ($dataForm['nombres'] == '') {
                    $this->_redirect('/');
                }
                $dataForm['perfil'] = $this->_perfil;
                $dataForm['numero'] = $this->_numero;
                $dataForm['fecha_nacimiento'] = $dataForm['anio'] . "-" . $dataForm['mes'] . "-" . $dataForm['dia'];
                $dataForm['fecha_registro'] = date('Y-m-d H:i:s');
                $registroUsuario = new App_Model_Registro();
                $registroUsuario->insert($dataForm);
                $this->_redirect('/');
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function dashboardRegistroAction() {
        try {
            $ModelRegistro = new App_Model_Registro();
            $registrousuario = $ModelRegistro->getUsuarioTotal();
            $this->view->registro = $registrousuario;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function personalizaAction() {
        
    }
    
    public function legalAction() {
        
    }

    public function goAction() {
        $_getVars = $_GET;
        $_key = array_keys($_getVars);
        $_valor = array_values($_getVars);
        $numGet = count($_getVars);
        $bucleGet = $numGet - 1;
        for ($x = 0; $x <= $bucleGet; $x++) {
            if ($x == 0) {
                $link.=$_valor[$x];
            } else {
                $link.="&" . $_key[$x] . "=" . $_valor[$x];
            }
        }
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $model = new App_Model_ConfigPerfil();
        $model->saveCdr($ua, $link);
        header("Location: " . $link);
        exit;
    }

}
