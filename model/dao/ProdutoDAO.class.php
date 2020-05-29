<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';

/**
 * Description of ProdutoDAO
 *
 * @author anderson
 */
class ProdutoDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                . " PROD_ID AS \"idProduto\" "
                . " , CD AS \"codProduto\" "
                . " , DESCR AS \"descProduto\" "
                . " FROM "
                . " PROD "
                . " WHERE "
                . " CD LIKE 'A500207' OR CD LIKE 'A500055' ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
