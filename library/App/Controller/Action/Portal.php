<?php

class App_Controller_Action_Portal extends App_Controller_Action {

    public function init() {
        parent::init();
        $this->_GetResultSoap = $this->_helper->getHelper('GetResultSoap');
        $this->_flashMessage = new App_Controller_Action_Helper_FlashMessengerCustom();
    }

    function mobileDetect() {
        $detect = new App_MobileDetect();
        if ($detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS() || $detect->isWindowsPhoneOS()) {
            return 'avanzado';
        } elseif ($detect->isGenericPhone()) {
            return 'basico240';
        } else {
            return 'basico360';
        }
    }


   function mensaje($estado) {
        switch ($estado) {
            case '1':
                $this->_flashMessage->success('Usted no tiene saldo suficiente');
                break;
            case '2':
                $this->_flashMessage->success('Ubo un problema en procesar su informacion');
                break;
            case '4':
                $this->_flashMessage->success('No se ha podido obtener su numero. Conéctate a la red Entel e intenta nuevamente. ');
                break;
            case '5':
                $this->_flashMessage->success('En breves momentos recibira un sms en su movil');
                break;
            case '6':
                $this->_flashMessage->success('No se ha podido procesar su solicitud');
                break;
            default:
                $this->_flashMessage->success('No podemos resolver tu solicitud en este momento, intentelo nuevamente.');
                break;
            return;
        }
    }
    
    public function preDispatch() {
        parent::preDispatch();

//        var_dump($this->_GetResultSoap->seccionMusicaTopResult->albumDetalleBE);exit;
//        $this->view->SoapMusica = $this->_GetResultSoap->seccionMusicaTopResult->albumDetalleBE;
//        $this->view->SoapTonos = $this->_GetResultSoap->seccionTonosResult->albumDetalleBE;
    }

    public function obtenerNumero() {

        $apnIfun = strpos($_SERVER['REMOTE_ADDR'], '216.194.');
        if ($apnIfun !== FALSE) {
            $dosG = $_SERVER['HTTP_X_UP_SUBNO'];
            $url = file_get_contents("http://wsperu.multibox.pe/ws-nextel.php?nextel-2g=$dosG");
            $conteDosG = json_decode($url);

            $telefono = "51" . $conteDosG->PTN;
            if ($telefono == "51") {
                $b = strpos($_SERVER['HTTP_COOKIE'], "msisdn=") + 7;
                if ($b != "7") {
                    $num = substr($_SERVER['HTTP_COOKIE'], $b);
                    $telefono = $num;
                } else {
                    $telefono = '';
                }
            }
        } elseif (isset($_SERVER['HTTP_MSISDN']) && $_SERVER['HTTP_MSISDN'] != "") {
            $telefono = $_SERVER['HTTP_MSISDN'];
        } elseif (isset($_SERVER['HTTP_COOKIE']) && $_SERVER['HTTP_COOKIE'] != "") {
            $b = strpos($_SERVER['HTTP_COOKIE'], "msisdn=") + 7;
            if ($b != "7") {
                $num = substr($_SERVER['HTTP_COOKIE'], $b);
                $telefono = $num;
            } else {
                $telefono = '';
            }
        } elseif (isset($_SERVER['HTTP_X_UP_SUBNO']) && $_SERVER['HTTP_X_UP_SUBNO'] != "") {
            $dosG = $_SERVER['HTTP_X_UP_SUBNO'];
            $url = file_get_contents("http://wsperu.multibox.pe/ws-nextel.php?nextel-2g=$dosG");
            $conteDosG = json_decode($url);
            $telefono = "51" . $conteDosG->PTN;
        } else {
            $telefono = '';
        }
        if (ctype_digit($telefono) && strlen(trim($telefono)) == 11) {
            return $telefono;
        } else {
           return '';
//            return '51983435107';
        }
        
    }

}
