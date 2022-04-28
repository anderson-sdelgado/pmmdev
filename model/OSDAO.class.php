<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of OSDAO
 *
 * @author anderson
 */
class OSDAO extends Conn {
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

        
    public function dados() {

        $select = "SELECT DISTINCT "
                        . " OS_ID AS \"idOS\" "
                        . " , NRO_OS AS \"nroOS\" "
                        . " , ID_LIB_OS AS \"idLibOS\" "
                        . " , ID_PROPR_AGR AS \"idProprAgr\" "
                    . " FROM "
                        . " USINAS.V_ECM_OS ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
    public function pesq($os) {

        $select = " SELECT DISTINCT "
                        . " OS_ID AS \"idOS\" "
                        . " , NRO_OS AS \"nroOS\" "
                        . " , NVL(AREA_PROGR, 10) AS \"areaProgrOS\" "
                        . " , SERV_AGR AS \"tipoOS\" "
                    . " FROM "
                        . " USINAS.V_PMM_OS "
                    . " WHERE "
                        . " NRO_OS = " . $os
                    . " ORDER BY "
                        . " OS_ID "
                    . " ASC";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function pesqMecan($os, $equip) {

        $select = " SELECT "
                    . " OS_ID AS \"idOS\" "
                    . " , NRO AS \"nroOS\" "
                    . " , EQUIP_ID AS \"idEquip\" "
                . " FROM "
                    . " VMB_OS_AUTO "
                . " WHERE "
                    . " NRO = " . $os
                    . " AND "
                    . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
