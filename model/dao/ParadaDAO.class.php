<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('./dbutil/Conn.class.php');
/**
 * Description of ParadaDAO
 *
 * @author anderson
 */
class ParadaDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                    . " MOTPARADA_ID AS \"idParada\" "
                    . " , CD AS \"codParada\" "
                    . " , CARACTER(DESCR) AS \"descrParada\" "
                    . " , DECODE(CD, 66, 1, 0) AS \"flagCalibragem\" "
                    . " , DECODE(MOTPARADA_ID, 180, 1, 0) AS \"flagCheckList\" "
                . " FROM "
                    . " USINAS.MOTIVO_PARADA "
                . " ORDER BY "
                    . " MOTPARADA_ID "
                . " ASC ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
