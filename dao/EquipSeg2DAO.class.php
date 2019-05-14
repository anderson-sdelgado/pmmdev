<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of EquipSegDAO
 *
 * @author anderson
 */
class EquipSeg2DAO extends Conn {
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
                    . " , E.CLASSOPER_DESCR AS \"descrClasseEquip\" "
                    . " , E.TP_EQUIP AS \"tipoEquip\" "
                . " FROM " 
                    . " V_PMM_EQUIP_SEG E " 
                . " ORDER BY " 
                    . " E.NRO_EQUIP " 
                    . " ASC ";
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
}
