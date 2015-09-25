<?php

/**
 * Description of User
 *
 * @author Josmel
 */
class App_Model_ConfigPerfil extends App_Db_Table_Abstract {

    private function registerCdr() {
        $DatosUtil = new App_Controller_Action_Util();
        $telefono = $DatosUtil->obtenerNumero();
        $perfil = $DatosUtil->obtenerPerfil();
        $datos = array(
            'telefono' => $telefono,
            'perfil' => $perfil,
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
        );

        return $datos;
    }

    public function saveCdr($userAgent, $result) {
        $name = date('YmdH');
       $datos = $this->registerCdr();
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/cdr/' . $name . ".moso");
        $formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
        $writer->setFormatter($formatter);
        $log = new Zend_Log($writer);

        $mensaje = $datos['fecha'] . "," . $datos['hora'] . "," . $_SERVER['REMOTE_ADDR']
                . "," . $_SERVER['SERVER_ADDR'] . "," . $datos['telefono'] . "," . 'perfil:'. $datos['perfil']
                . "," . $result . "," . $userAgent;
        $log->info($mensaje);
    }
    public function saveCdrDedicatorias($resultado) {
        $name = date('YmdH');
           $datos = $this->registerCdr();
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/cdr/' . $name . ".dedicatoria");
        $formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
        $writer->setFormatter($formatter);
        $log = new Zend_Log($writer);
        $mensaje = $datos['fecha'] . "," . $datos['hora'] . "," . $_SERVER['REMOTE_ADDR']
                . ",". $datos['telefono'] . "," . 'perfil:'. $datos['perfil']
                . "," . $resultado;
        $log->info($mensaje);
    }

}
