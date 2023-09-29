<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of REquipPneuDAO
 *
 * @author anderson
 */
class REquipPneuDAO extends Conn {
    
    public function dados() {

        $select = " SELECT "
                        . " VEP.EQUIP_ID AS \"idEquip\" "
                        . " , VEP.POSPNCONF_ID AS \"idPosConfPneu\" "
                        . " , VEP.POS_PNEU AS \"posPneu\" "
                    . " FROM "
                        . " VMB_EQUIP_PNEU VEP ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
