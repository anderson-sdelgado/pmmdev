<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of TurnoDAO
 *
 * @author anderson
 */
class TurnoDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                . " TURNOTRAB_ID AS \"idTurno\" "
                . " , TPTUREQUIP_CD AS \"codTurno\" "
                . " , NRO_TURNO AS \"nroTurno\" "
                . " , 'TURNO ' || NRO_TURNO || ': ' || HR_INI || ' - ' || HR_FIM AS \"descTurno\" "
                . " FROM "
                . " USINAS.V_SIMOVA_TURNO_EQUIP_NEW ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
