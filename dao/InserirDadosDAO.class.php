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
                . " , DADOS "
                . " ) "
                . " VALUES ("
                . " SYSDATE "
                . " , 'PMM' "
                . " , ?"
                . " , ?"
                . " )";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->bindParam(1, $pagina, PDO::PARAM_STR, 30);
        $this->Create->bindParam(2, $dados, PDO::PARAM_STR, 32000);
        $this->Create->execute();
    }

}
