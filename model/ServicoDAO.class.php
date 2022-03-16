<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../dbutil/Conn.class.php');
/**
 * Description of ServicoDAO
 *
 * @author anderson
 */
class ServicoDAO extends Conn {

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($base) {

        $select = " SELECT "
                . " SERVICO_ID AS \"idServico\" "
                . " , CD AS \"codServico\" "
                . " , DESCR AS \"descrServico\" "
                . " FROM "
                . " VMB_SERVICO_AUTO "
                . " ORDER BY "
                . " CD "
                . " ASC ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
