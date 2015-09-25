<?php

class App_Controller_Action_Util extends Zend_Controller_Request_Abstract {

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
        }
    }

//14.241.143.240
    function obtenerPerfil() {
        $detect = new App_MobileDetect();
        if ($detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS() || $detect->isWindowsPhoneOS()) {
            return 3;
        } elseif ($detect->isGenericPhone()) {
            return 1;
        } else {
            return 2;
        }
    }

}
