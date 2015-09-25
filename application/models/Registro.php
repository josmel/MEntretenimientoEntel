<?php

/**
 * Description of User
 *
 * @author Josmel
 */
class App_Model_Registro extends App_Db_Table_Abstract {

    protected $_name = 'UsuarioActivo';
    protected $_primary = "id";

    public function getPrimaryKey() {
        return $this->_primary;
    }

    public function getusuario($cod) {
        $smt = $this->select()
             ->from($this->_name)
             ->where('numero = ?', $cod)->query();
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
    public function getUsuarioTotal() {
        $smt = $this->select()
             ->from($this->_name, array("COUNT(DISTINCT numero) as cantidad",'perfil'))
                ->group("perfil")
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

}
