<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('./dbutil/Conn.class.php');
/**
 * Description of PneuDAO
 *
 * @author anderson
 */
class REquipPneuDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($equip) {

        $select = " SELECT " 
                    . " VEP.POSPNCONF_ID AS \"idPosConfPneu\" "
                    . " , VEP.POS_PNEU AS \"posPneu\" "
                    . " FROM " 
                    . " VMB_EQUIP_PNEU VEP "
                    . " , EQUIP E"
                    . " WHERE"
                    . " E.NRO_EQUIP = " . $equip
                    . " AND "
                    . " VEP.EQUIP_ID = E.EQUIP_ID ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
