<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';

/**
 * Description of InserirDados
 *
 * @author anderson
 */
class InserirDadosDAO extends Conn {
    //put your code here

    /** @var PDO */
    private $Conn;

    public function salvarDados($dados, $pagina) {

        $this->Conn = parent::getConn();

        $sql = "INSERT INTO DADOS_MOBILE ("
                . " DTHR "
                . " , APLICATIVO "
                . " , PAGINA "
                . " , DTHR_TRANS "
                . " , DADOS "
                . " ) "
                . " VALUES ("
                . " SYSDATE "
                . " , 'PMM' "
                . " , '" . $pagina . "'"
                . " , SYSDATE "
                . " , '" . $dados . "'"
                . " )";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
