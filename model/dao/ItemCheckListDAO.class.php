<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');

/**
 * Description of ItemChecklistDAO
 *
 * @author anderson
 */
class ItemCheckListDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($base) {

        $select = " SELECT "
                . " ITMANPREV_ID AS \"idItemCheckList\" "
                . " , PLMANPREV_ID AS \"idCheckList\" "
                . " , CARACTER(PROC_OPER) AS \"descrItemCheckList\" "
                . " FROM "
                . " V_ITEM_PLANO_CHECK ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function atualCheckList($equip, $base) {
        
        $sql = " UPDATE USINAS.ATUALIZA_CHECKLIST_MOBILE  "
                . " SET DT_MOBILE = SYSDATE "
                . " WHERE EQUIP_NRO = " . $equip;

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
        
    }

}
