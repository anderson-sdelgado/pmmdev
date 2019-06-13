<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of EquipDAO
 *
 * @author anderson
 */
class EquipDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                    . " E.EQUIP_ID AS \"idEquip\" "
                    . " , E.NRO_EQUIP AS \"codEquip\" "
                    . " , E.CLASSOPER_CD AS \"codClasseEquip\" "
                    . " , CARACTER(E.CLASSOPER_DESCR) AS \"descrClasseEquip\" "
                    . " , E.TPTUREQUIP_CD AS \"codTurno\" "
                    . " , NVL(C.PLMANPREV_ID, 0) AS \"idChecklist\" "
                    . " , CASE WHEN E.CLASSOPER_CD = 211 AND R.TP_EQUIP IS NULL THEN 4 "
                        . " ELSE NVL(R.TP_EQUIP, 0) END AS \"tipoEquipFert\" "
                . " FROM "
                    . " USINAS.V_SIMOVA_EQUIP E "
                    . " , USINAS.V_EQUIP_PLANO_CHECK C "
                    . " , USINAS.ROLAO R "
                . " WHERE "
                    . " E.NRO_EQUIP = C.EQUIP_NRO(+) "
                    . " AND E.EQUIP_ID = R.EQUIP_ID(+) "
                . " ORDER BY "
                    . " NRO_EQUIP "
                    . " ASC ";
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
