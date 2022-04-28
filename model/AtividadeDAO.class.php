<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of AtividadeDAO
 *
 * @author anderson
 */
class AtividadeDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                        . " A.ATIVAGR_ID AS \"idAtiv\" "
                        . " , A.ATIVAGR_CD AS \"codAtiv\" "
                        . " , CARACTER(A.ATIVAGR_DESCR) AS \"descrAtiv\" "
                . " FROM "
                    . " USINAS.VMB_ATIVAGR_MECAN A ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
